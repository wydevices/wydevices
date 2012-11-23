/*
 * Unbuffered io for ffmpeg system
 * Copyright (c) 2001 Fabrice Bellard
 *
 * This file is part of FFmpeg.
 *
 * FFmpeg is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * FFmpeg is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with FFmpeg; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 */
#include <unistd.h>
#include "avformat.h"
#include "os_support.h"
#include "libavutil/avstring.h"
#include "http.h"

extern URLProtocol http_protocol;
static int default_interrupt_cb(void);

URLProtocol *first_protocol = NULL;
URLInterruptCB *url_interrupt_cb = default_interrupt_cb;

URLProtocol *av_protocol_next(URLProtocol *p)
{
    if(p) return p->next;
    else  return first_protocol;
}


int register_protocol(URLProtocol *protocol)
{
    URLProtocol **p;

    // Wydev hack. Prevent Wyplay libs from registering some of their protocols
    if (!strcmp(protocol->name, "http") && protocol != &(http_protocol))
    {
        av_log(NULL, AV_LOG_DEBUG, "Wydev prevent Wyplay %s protocol registering\n", protocol->name);
        return 0;
    }

    p = &first_protocol;
    while (*p != NULL)p = &(*p)->next;
    *p = protocol;
    protocol->next = NULL;
    return 0;
}

static int url_alloc_for_protocol (URLContext **puc, struct URLProtocol *up,
                                   const char *filename, int flags)
{
    URLContext *uc;
    int err;

    uc = av_mallocz(sizeof(URLContext) + strlen(filename) + 1);
    if (!uc) {
        err = AVERROR(ENOMEM);
        goto fail;
    }
    uc->filename = (char *) &uc[1];
    strcpy(uc->filename, filename);
    uc->prot = up;
    uc->flags = flags;
    uc->is_streamed = 0; /* default = not streamed */
    uc->max_packet_size = 0; /* default: stream file */
    if (!strcmp(up->name, "http")) {
        uc->priv_data = av_mallocz(sizeof(HTTPContext));
    }

    *puc = uc;
    return 0;
 fail:
    *puc = NULL;

    return err;
}

int url_connect(URLContext* uc)
{
    int err;

    err = uc->prot->url_open(uc, uc->filename, uc->flags);
    if (err)
        return err;
    //We must be careful here as url_seek() could be slow, for example for http
    if(   (uc->flags & (URL_WRONLY | URL_RDWR))
       || !strcmp(uc->prot->name, "file"))
        if(!uc->is_streamed && url_seek(uc, 0, SEEK_SET) < 0)
            uc->is_streamed= 1;
    return 0;
}

int url_open_protocol (URLContext **puc, struct URLProtocol *up,
                       const char *filename, int flags)
{
    int ret;

    ret = url_alloc_for_protocol(puc, up, filename, flags);
    if (ret)
        goto fail;
    ret = url_connect(*puc);
    if (!ret)
        return 0;
 fail:
    url_close(*puc);
    *puc = NULL;
    return ret;
}

#define URL_SCHEME_CHARS                        \
    "abcdefghijklmnopqrstuvwxyz"                \
    "ABCDEFGHIJKLMNOPQRSTUVWXYZ"                \
    "0123456789+-."

int url_alloc(URLContext **puc, const char *filename, int flags)
{
    URLProtocol *up;
    char proto_str[128], proto_nested[128], *ptr;
    size_t proto_len = strspn(filename, URL_SCHEME_CHARS);

    if (filename[proto_len] != ':' || is_dos_path(filename))
        strcpy(proto_str, "file");
    else
        av_strlcpy(proto_str, filename, FFMIN(proto_len+1, sizeof(proto_str)));

    av_strlcpy(proto_nested, proto_str, sizeof(proto_nested));
    if ((ptr = strchr(proto_nested, '+')))
        *ptr = '\0';

    // Fix mms protocol handling.
    // mms:// is infact mmsh://
    if (!strcmp(proto_str, "mms"))
    {
        // Append "h" to "mms"
        av_strlcat(proto_str, "h", sizeof(proto_str));
    }

    up = first_protocol;
    while (up != NULL) {
        if (!strcmp(proto_str, up->name))
            return url_alloc_for_protocol (puc, up, filename, flags);
        up = up->next;
    }
    *puc = NULL;
    return AVERROR(ENOENT);
}

int url_open(URLContext **puc, const char *filename, int flags)
{
    int ret;

    ret = url_alloc(puc, filename, flags);
    if (ret)
        return ret;
    ret = url_connect(*puc);
    if (!ret)
        return 0;
    url_close(*puc);
    *puc = NULL;
    return ret;
}

int url_open2(URLContext **puc, const char *filename, int flags, int *interrupted)
{
    int ret;

    ret = url_alloc(puc, filename, flags);
    if (ret)
        return ret;
    (*puc)->interrupted = interrupted;
    ret = url_connect(*puc);
    if (!ret)
        return 0;
    url_close(*puc);
    *puc = NULL;
    return ret;
}

static inline int retry_transfer_wrapper(URLContext *h, unsigned char *buf, int size, int size_min,
                                         int (*transfer_func)(URLContext *h, unsigned char *buf, int size))
{
    int ret, len;
    int fast_retries = 5;

    len = 0;
    while (len < size_min) {
        ret = transfer_func(h, buf+len, size-len);
        if (ret == AVERROR(EINTR))
            continue;
        if (h->flags & URL_FLAG_NONBLOCK)
            return ret;
        if (ret == AVERROR(EAGAIN)) {
            ret = 0;
            if (fast_retries)
                fast_retries--;
            else
                usleep(1000);
        } else if (ret < 1)
            return ret < 0 ? ret : len;
        if (ret)
           fast_retries = FFMAX(fast_retries, 2);
        len += ret;
        if (url_interrupt_cb())
            return AVERROR_EXIT;
    }
    return len;
}

int url_read(URLContext *h, unsigned char *buf, int size)
{
    if (h->flags & URL_WRONLY)
        return AVERROR(EIO);
    return retry_transfer_wrapper(h, buf, size, 1, h->prot->url_read);
}

int url_read_complete(URLContext *h, unsigned char *buf, int size)
{
    if (h->flags & URL_WRONLY)
        return AVERROR(EIO);
    return retry_transfer_wrapper(h, buf, size, size, h->prot->url_read);
}

#if defined(CONFIG_MUXERS) || defined(CONFIG_PROTOCOLS)
int url_write(URLContext *h, unsigned char *buf, int size)
{
    int ret;
    if (!(h->flags & (URL_WRONLY | URL_RDWR)))
        return AVERROR(EIO);
    /* avoid sending too big packets */
    if (h->max_packet_size && size > h->max_packet_size)
        return AVERROR(EIO);
    ret = h->prot->url_write(h, buf, size);
    return ret;
}
#endif //CONFIG_MUXERS || CONFIG_PROTOCOLS

offset_t url_seek(URLContext *h, offset_t pos, int whence)
{
    offset_t ret;

    if (!h || !h->prot)
	return AVERROR(EINVAL);
    if (!h->prot->url_seek)
        return AVERROR(EPIPE);
    ret = h->prot->url_seek(h, pos, whence);
    return ret;
}

int url_close(URLContext *h)
{
    int ret = 0;
    if (!h) return 0; /* can happen when url_open fails */

    if (h->prot->url_close)
        ret = h->prot->url_close(h);
    if (!strcmp(h->prot->name, "http")) {
        av_free(h->priv_data);
    }
    av_free(h);
    return ret;
}

int url_check_interrupted(URLContext *h) {
    return url_interrupt_cb() || (h->interrupted && *h->interrupted);
}

int url_ctl(URLContext *h, int cmd, void *arg)
{
    int ret;

    if (!h || !h->prot)
	    return AVERROR(EINVAL);
	if (!h->prot->url_ctl)
        return AVERROR(ENOTTY);

	ret = h->prot->url_ctl(h, cmd, arg);
	return ret;
}
 

int url_exist(const char *filename)
{
    URLContext *h;
    if (url_open(&h, filename, URL_RDONLY) < 0)
        return 0;
    url_close(h);
    return 1;
}

offset_t url_filesize(URLContext *h)
{
    offset_t pos, size;

    size= url_seek(h, 0, AVSEEK_SIZE);
    if(size<0){
        pos = url_seek(h, 0, SEEK_CUR);
        if ((size = url_seek(h, -1, SEEK_END)) < 0)
            return size;
        size++;
        url_seek(h, pos, SEEK_SET);
    }
    return size;
}

int url_get_max_packet_size(URLContext *h)
{
    return h->max_packet_size;
}

void url_get_filename(URLContext *h, char *buf, int buf_size)
{
    av_strlcpy(buf, h->filename, buf_size);
}


static int default_interrupt_cb(void)
{
    return 0;
}

void url_set_interrupt_cb(URLInterruptCB *interrupt_cb)
{
    if (!interrupt_cb)
        interrupt_cb = default_interrupt_cb;
    url_interrupt_cb = interrupt_cb;
}

int av_url_read_play(URLContext *h)
{
    if (!h->prot->url_read_play)
        return AVERROR(ENOSYS);
    return h->prot->url_read_play(h);
}

int av_url_read_pause(URLContext *h)
{
    if (!h->prot->url_read_pause)
        return AVERROR(ENOSYS);
    return h->prot->url_read_pause(h);
}

int av_url_read_seek(URLContext *h,
        int stream_index, int64_t timestamp, int flags)
{
    if (!h->prot->url_read_seek)
        return AVERROR(ENOSYS);
    return h->prot->url_read_seek(h, stream_index, timestamp, flags);
}
