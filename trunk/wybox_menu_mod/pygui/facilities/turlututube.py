# -*- coding: utf-8 -*- 

# Youtube Gdata class
# Fix Summer 2010 video URL modification
# Fix Summer 2011 video URL modification
# Modify video format choice to match the WyBox codecs restrictions
# Add default language for search using user_config
# Add MessageWindows to inform user of errors


# Copyright 2010-2011, Polo35
# Licenced under Academic Free License version 3.0
# Review wydev_pygui README & LICENSE files for further details.
# Parts taken from 'youtube-dl' : https://github.com/rg3/youtube-dl


__all__ = ['YoutubeData']

import urllib
import urllib2
import httplib
import socket
import re
import os
import string

try:
	from urlparse import parse_qs
except ImportError:
	from cgi import parse_qs

from peewee.misc_utils import MetaSingleton
from pygui.config import user_config
from pygui.window import MessageWindow


class YoutubeData(object):

	__module__ = __name__
	__metaclass__ = MetaSingleton


	_standard_feeds = ('Recently featured', 'Top rated', 'Top favorites', 'Most viewed', 'Most recent', 'Most discussed', 'Most linked', 'Most responded')


	def __init__(self):
		import gdata.service
		self.service = gdata.service.GDataService(server='gdata.youtube.com')
		self.url_open = None
		return None

	def _get_flv_uri(self, video_uri):
		
		_VALID_URL = r'^((?:https?://)?(?:youtu\.be/|(?:\w+\.)?youtube(?:-nocookie)?\.com/)(?!view_play_list|my_playlists|artist|playlist)(?:(?:(?:v|embed|e)/)|(?:(?:watch(?:_popup)?(?:\.php)?)?(?:\?|#!?)(?:.+&)?v=))?)?([0-9A-Za-z_-]+)(?(1).+)?$'

		"""
		|---------------------------------------------------------------------------------------------------------------|
		|                                       Youtube available formats table                                         |
		|---------------------------------------------------------------------------------------------------------------|
		| Format codes              |   5   |  6   | 34|   35  |   18   |   22  |   37  | 38 | 43| 44| 45 |  13  |  17  |
		|---------------------------------------------------------------------------------------------------------------|
		| Container                 |             FLV          |              MP4            |    WebM    |     3GP     |
		|---------------------------------------------------------------------------------------------------------------|
		|       |Encoding           |Sorenson H.263|              MPEG-4 AVC (H.264)         |     VP8    |MPEG-4 Visual|
		|       |-------------------------------------------------------------------------------------------------------|
		|       |Profile            |      –       |    Main   |Baseline|        High        |     –      |      –      |
		|       |-------------------------------------------------------------------------------------------------------|
		| Video |Max width (pixels) |  400  | 448  |640|  854  |   640  | 1280  |  1920 |4096|640|854|1280|     176     |
		|       |-------------------------------------------------------------------------------------------------------|
		|       |Max height (pixels)|  240  | 336  |360|  480  |   360  |  720  |  1080 |3072|360|480| 720|     144     |
		|       |-------------------------------------------------------------------------------------------------------|
		|       |Bitrate (Mbit/s)   | 0.25  | 0.8  |0.5|0.8–1.0|   0.5  |2.0–2.9|3.5–5.0|  – |0.5| 1 |  2 |      –      |
		|-------|-------------------------------------------------------------------------------------------------------|
		|       |Encoding           |  MP3         |                  AAC                    |    Vorbis  |     AAC     |
		|       |-------------------------------------------------------------------------------------------------------|
		|       |Channels           |  1–2         |                          2 (stereo)                         | 1-2  |
		|       |-------------------------------------------------------------------------------------------------------|
		| Audio |Sampling rate (Hz) | 22050 |                                    44100                                  |
		|       |-------------------------------------------------------------------------------------------------------|
		|       |Bitrate (kbit/s)   |  64   |  96  |    128    |   96   |       152          |  128  | 192|      –      |
		|---------------------------------------------------------------------------------------------------------------|
		"""

		# Available formats in order of quality and compatible with our WyBox
		_available_formats = ['38', '37', '22', '18', '13', '17']

		# Extract video id from URL
		mobj = re.match(_VALID_URL, video_uri)
		if mobj is not None:
			video_id = mobj.group(2)
		else:
			MessageWindow(('ERROR: invalid URL: %s' % video_uri)).show()
			return ''

		# Get video infos
		for el_type in ['&el=embedded', '&el=detailpage', '&el=vevo', '']:
			video_info_url = ('http://www.youtube.com/get_video_info?&video_id=%s%s&ps=default&eurl=&gl=US&hl=en' % (video_id, el_type))
			request = urllib2.Request(video_info_url)
			try:
				video_info_webpage = urllib2.urlopen(request).read()
				video_info = parse_qs(video_info_webpage)
				if 'token' in video_info:
					break
			except (urllib2.URLError, httplib.HTTPException, socket.error), err:
				MessageWindow(('ERROR: unable to download video info webpage: %s' % str(err))).show()
				return ''

		# Get video token
		if 'token' not in video_info:
			if 'reason' in video_info:
				MessageWindow(('ERROR: YouTube said: %s' % video_info['reason'][0].decode('utf-8'))).show()
			else:
				MessageWindow('ERROR: "token" parameter not in video info for unknown reason').show()
			return ''
		else:
			video_token = urllib.unquote_plus(video_info['token'][0])

		# Get video real url
		if 'url_encoded_fmt_stream_map' in video_info and len(video_info['url_encoded_fmt_stream_map']) >= 1:
			url_data_strs = video_info['url_encoded_fmt_stream_map'][0].split(',')
			url_data = [parse_qs(uds) for uds in url_data_strs]
			url_data = filter(lambda ud: 'itag' in ud and 'url' in ud, url_data)
			url_map = dict((ud['itag'][0], ud['url'][0]) for ud in url_data)
			existing_formats = [x for x in _available_formats if x in url_map]
			if len(existing_formats) == 0:
				MessageWindow('ERROR: no known formats available for video').show()
				return ''
			video_real_url = url_map[existing_formats[0]] # Best quality choice
		elif 'conn' in video_info and video_info['conn'][0].startswith('rtmp'):
			video_real_url = video_info['conn'][0] # Real Time Messaging Protocol
		else:
			MessageWindow('ERROR: no conn or url_encoded_fmt_stream_map information found in video info').show()
			return ''
		return video_real_url

	def _get_entries(self, path):
		return self.service.Get(('/feeds/api/' + path)).entry

	def _get_dict_from_entry(self, e):
		group_ext = e.FindExtensions(tag='group')[0]
		try:
			view_count = e.FindExtensions(tag='statistics')[0].attributes['viewCount']
		except IndexError:
			view_count = 'NA'
		try:
			rating_ext = e.FindExtensions(tag='rating')[0].attributes['average']
		except IndexError:
			rating_ext = 0
		return dict(title=group_ext.FindChildren(tag='title')[0].text, thumbnail=group_ext.FindChildren(tag='thumbnail')[0].attributes['url'], player_url=group_ext.FindChildren(tag='player')[0].attributes['url'], uri=(lambda uri=group_ext.FindChildren(tag='player')[0].attributes['url']: self._get_flv_uri(uri)), view_count=view_count, rating=rating_ext, description='')

	def simple_search(self, name):
		res_list = []
		for e in self._get_entries('videos/-/' + urllib.quote_plus(name)):
			res_list.append(self._get_dict_from_entry(e))
		return res_list

	def search(self, what=0, lang=user_config['base']['language'], orderby='relevance', start_idx=1, max_res=50):
		params = {}
		root_uri = ""
		res_list = []

		# Parse options
		if start_idx <= 0:
			params['start-index'] = 1
		else:
			params['start-index'] = start_idx
		if max_res >= 50:
			params['max-results'] = 50
		elif max_res <= 0:
			params['max-results'] = 1
		else:
			params['max-results'] = max_res
		params['orderby'] = orderby

		# Parse search what: _standard_feeds or keyword
		if isinstance(what, int) and (int(what) >= 0 and int(what) <= 7):
			root_uri = 'standardfeeds/' + self._standard_feeds[int(what)].lower().replace(' ', '_')
		else:
			root_uri = 'videos'
			params['vq'] = what
			params['lr'] = lang
			params['restriction'] = lang.upper()

		# Perform search
		for e in self._get_entries(  '%s?%s' % ( root_uri, urllib.urlencode(params) )  ):
			try:
				noembed = e.FindExtensions(tag='noembed')[0]
			except IndexError:
				res_list.append(self._get_dict_from_entry(e))
		return res_list




if (__name__ == '__main__'):
	import sys
	ytd = YoutubeData()
	if (len(sys.argv) > 1):
		if sys.argv[1].isdigit():
			what = int(sys.argv[1])
		else:
			what = sys.argv[1]
		print ('#' * 80)
		print "Search ",
		print what
		print ('#' * 80)
		for e in ytd.search(what, max_res=1, orderby='rating'):
			print 'RESULT:'
			print ('%(title)s / rated: %(rating)s / viewed: %(view_count)s' % e)
			print 'DATA:'
			print e
			print 'URI:'
			print e['uri']()
			print ('#' * 80)
	else:
		print ('#' * 80)
		print 'Search David GUETTA'
		print ('#' * 80)
		for e in ytd.search('David GUETTA', orderby='viewCount', max_res=1):
			print 'RESULT:'
			print ('%(title)s / rated: %(rating)s / viewed: %(view_count)s' % e)
			print 'DATA:'
			print e
			print 'URI:'
			print e['uri']()
			print ('#' * 80)
