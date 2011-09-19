# Youtube Gdata class
# Fix Summer 2010 video URL modification

# Copyright 2010, Polo35
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

		std_headers = {
			'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0.1) Gecko/20100101 Firefox/5.0.1',
			'Accept-Charset': 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
			'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'Accept-Encoding': 'gzip, deflate',
			'Accept-Language': 'en-us,en;q=0.5',
		}
		
		_VALID_URL = r'^((?:https?://)?(?:youtu\.be/|(?:\w+\.)?youtube(?:-nocookie)?\.com/)(?!view_play_list|my_playlists|artist|playlist)(?:(?:(?:v|embed|e)/)|(?:(?:watch(?:_popup)?(?:\.php)?)?(?:\?|#!?)(?:.+&)?v=))?)?([0-9A-Za-z_-]+)(?(1).+)?$'
		# Listed in order of quality
		_available_formats = ['38', '37', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13']
  	
		# Extract video id from URL
		mobj = re.match(_VALID_URL, video_uri)
		
		if mobj is None:
			print ('ERROR: invalid URL: %s' % video_uri)
			return ''
		
		video_id = mobj.group(2)
#		print ('video_id: %s' % video_id)
		
		# Get video webpage
		request = urllib2.Request('http://www.youtube.com/watch?v=%s&gl=US&hl=en&amp;has_verified=1' % video_id)
		try:
			video_webpage = urllib2.urlopen(request).read()
		except (urllib2.URLError, httplib.HTTPException, socket.error), err:
			print ('ERROR: unable to download video webpage: %s' % str(err))
			return ''
		
		# Attempt to extract SWF player URL
		mobj = re.search(r'swfConfig.*?"(http:\\/\\/.*?watch.*?-.*?\.swf)"', video_webpage)
		if mobj is not None:
			player_url = re.sub(r'\\(.)', r'\1', mobj.group(1))
#			print ('player_url: %s' % player_url)
		else:
			player_url = None

		# Get video info
		for el_type in ['&el=embedded', '&el=detailpage', '&el=vevo', '']:
			video_info_url = ('http://www.youtube.com/get_video_info?&video_id=%s%s&ps=default&eurl=&gl=US&hl=en'
					% (video_id, el_type))
#			print ('video_info_url: %s' % video_info_url)
			request = urllib2.Request(video_info_url)
			try:
				video_info_webpage = urllib2.urlopen(request).read()
				video_info = parse_qs(video_info_webpage)
				if 'token' in video_info:
					break
			except (urllib2.URLError, httplib.HTTPException, socket.error), err:
				print ('ERROR: unable to download video info webpage: %s' % str(err))
				return ''
		if 'token' not in video_info:
			if 'reason' in video_info:
				print ('ERROR: YouTube said: %s' % video_info['reason'][0].decode('utf-8'))
			else:
				print ('ERROR: "token" parameter not in video info for unknown reason')
			return ''
		
		# Get video token
		video_token = urllib.unquote_plus(video_info['token'][0])
#		print ('video_token: %s' % video_token)

		# Get video real url
#		print video_info
		if 'conn' in video_info and video_info['conn'][0].startswith('rtmp'):
			video_real_url = video_info['conn'][0]
		elif 'url_encoded_fmt_stream_map' in video_info and len(video_info['url_encoded_fmt_stream_map']) >= 1:
			url_data_strs = video_info['url_encoded_fmt_stream_map'][0].split(',')
			url_data = [parse_qs(uds) for uds in url_data_strs]
			url_data = filter(lambda ud: 'itag' in ud and 'url' in ud, url_data)
			url_map = dict((ud['itag'][0], ud['url'][0]) for ud in url_data)
			existing_formats = [x for x in _available_formats if x in url_map]
			if len(existing_formats) == 0:
				print 'ERROR: no known formats available for video'
				return ''
			video_real_url = url_map[existing_formats[0]] # Best quality
		else:
			print 'ERROR: no conn or url_encoded_fmt_stream_map information found in video info'
			return ''
#		print ('video_real_url: %s' % video_real_url)
		return video_real_url

	def _get_entries(self, path):
#		print path
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

	def search(self, what=0, lang='fr', orderby='relevance', start_idx=1, max_res=50):
		params = {}
		root_uri = ""
		res_list = []

		# Parsing options
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
		if orderby is not None:
			params['orderby'] = orderby

		# Parsing search what: _standard_feeds or keyword
		if isinstance(what, int) and (int(what) >= 0 and int(what) <= 7):
			root_uri = 'standardfeeds/' + self._standard_feeds[int(what)].lower().replace(' ', '_')
		else:
			root_uri = 'videos'
			params['vq'] = what
			if lang is not None:
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
	from pygui.facilities.turlututube_wyplay import ToutubeData
	ttd = ToutubeData()
	ytd = YoutubeData()
	if (len(sys.argv) > 1):
		if sys.argv[1].isdigit():
			what = int(sys.argv[1])
		else:
			what = sys.argv[1]
		print "Polo64 Search"
		for e in ytd.search(what, lang='fr', max_res=1, orderby='rating'):
			print ('%(title)s / rated: %(rating)s / viewed: %(view_count)s' % e)
			print e
			print ('#' * 80)
		print "Wyplay Search"
		for e in ttd.search(what, lang='fr', max_res=1, orderby='rating'):
			print ('%(title)s / rated: %(rating)s / viewed: %(view_count)s' % e)
			print e
			print ('#' * 80)
	else:
#		for (i, name,) in enumerate(YoutubeData._standard_feeds):
#			print 'FEED',
#			print name
#			for e in ytd.search(i, lang='fr', max_res=5, orderby='viewCount'):
#				print e
#				print 'URI:',
#				print e['uri']()
#				print ('#' * 80)


		print ('#' * 80)
		print 'Polo Search'
		print ('#' * 80)
		for e in ytd.search('Pixar', lang='fr', orderby='viewCount', max_res=1):
			print e
			print 'URI:',
			print e['uri']()
			print ('#' * 80)


#		for (i, name,) in enumerate(ToutubeData._standard_feeds):
#			print 'FEED',
#			print name
#			for e in ttd.search(i, lang='fr', max_res=5, orderby='viewCount'):
#				print e
#				print 'URI:',
#				print e['uri']()
#				print ('#' * 80)

		print ('#' * 80)
		print 'Wyplay Search'
		print ('#' * 80)
		for e in ttd.search('Pixar', lang='fr', orderby='viewCount', max_res=1):
			print e
			print 'URI:',
			print e['uri']()
			print ('#' * 80)


