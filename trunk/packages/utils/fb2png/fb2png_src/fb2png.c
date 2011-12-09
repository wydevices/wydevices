#include <stdio.h>
#include <stdlib.h>
#include <stdarg.h>
#include <unistd.h>
#include <fcntl.h>
#include <sys/ioctl.h>
#include <sys/mman.h>
#include <linux/fb.h>
#include <png.h>

static void
die(const char *msg, ...)
{
	va_list ap;

	va_start (ap, msg);
	vfprintf(stderr, msg, ap);
	va_end (ap);

	exit(EXIT_FAILURE);
}

struct fb {
	int width, height, stride;
	char *data;
};

static int
map_frame_buffer(struct fb *fb, const char *device)
{
	struct fb_fix_screeninfo fix;
	struct fb_var_screeninfo var;
	int fd;

	fd = open(device, O_RDWR);
	if (fd < 0)
		die("open %s: %m\n", device);

	if (ioctl(fd, FBIOGET_FSCREENINFO, &fix) < 0)
		die("fb get fixed failed: %m\n");

	if (ioctl(fd, FBIOGET_VSCREENINFO, &var) < 0)
		die("fb get fixed failed: %m\n");

	fb->stride = fix.line_length;
	fb->width = var.xres;
	fb->height = var.yres;
	fb->data = mmap(NULL, fb->stride * fb->height,
			PROT_READ | PROT_WRITE, MAP_SHARED, fd, 0);

	close(fd);
	if (fb->data == MAP_FAILED)
		die("fb map failed: %m\n");

	return 0;
}

static void
stdio_write_func (png_structp png, png_bytep data, png_size_t size)
{
	FILE *fp;
	size_t ret;

	fp = png_get_io_ptr (png);
	while (size) {
		ret = fwrite (data, 1, size, fp);
		size -= ret;
		data += ret;
		if (size && ferror (fp))
			die("write: %m\n");
	}
}

static void
png_simple_output_flush_fn (png_structp png_ptr)
{
}

static void
png_simple_error_callback (png_structp png,
	                   png_const_charp error_msg)
{
	die("png error: %s\n", error_msg);
}

static void
png_simple_warning_callback (png_structp png,
	                     png_const_charp error_msg)
{
	fprintf(stderr, "png warning: %s\n", error_msg);
}


int main(int argc, char *argv[])
{
    png_struct *png;
    png_info *info;
    png_byte **volatile rows = NULL;
    png_color_16 white;
    int png_color_type;
    int depth, i;
    struct fb fb;
    FILE *fp;

    if (argc != 2)
	    die("usage: fb2png OUTPUT.PNG\n");

    if (map_frame_buffer(&fb, "/dev/fb0") < 0)
	    return -1;

    rows = malloc(fb.height * sizeof rows[0]);
    if (rows == NULL)
	    die("malloc failed\n");

    for (i = 0; i < fb.height; i++)
	    rows[i] = (png_byte *) fb.data + i * fb.stride;

    png = png_create_write_struct (PNG_LIBPNG_VER_STRING, NULL,
	                           png_simple_error_callback,
	                           png_simple_warning_callback);
    if (png == NULL)
	    die("png_create_write_struct failed\n");

    info = png_create_info_struct (png);
    if (info == NULL)
	    die("png_create_info_struct failed\n");

    fp = fopen(argv[1], "w");
    if (fp == NULL)
	    die("fopen failed: %m\n");

    png_set_write_fn (png, fp, stdio_write_func, png_simple_output_flush_fn);

    depth = 8;
    png_color_type = PNG_COLOR_TYPE_RGB;

    png_set_IHDR (png, info,
		  fb.width,
		  fb.height, depth,
		  png_color_type,
		  PNG_INTERLACE_NONE,
		  PNG_COMPRESSION_TYPE_DEFAULT,
		  PNG_FILTER_TYPE_DEFAULT);

    white.gray = (1 << depth) - 1;
    white.red = white.blue = white.green = white.gray;
    png_set_bKGD (png, info, &white);

    png_write_info (png, info);

    if (png_color_type == PNG_COLOR_TYPE_RGB)
	png_set_filler (png, 0, PNG_FILLER_AFTER);

    png_write_image (png, rows);
    png_write_end (png, info);

    png_destroy_write_struct (&png, &info);
    fclose(fp);
    free (rows);

    return 0;
}
