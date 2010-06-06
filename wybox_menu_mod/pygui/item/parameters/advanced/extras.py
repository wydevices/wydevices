###################################################################
#               Wyplayer extras management items                  #
###################################################################
#                           Polo                                  #
###################################################################

from __future__ import absolute_import

from pygui.item.parameters import ParametersSetupItem
from pygui.item.core import ActionItem
from peewee.notifier import Task

#from logging.handlers import SysLogHandler

import os
#import logging


#os.environ['DEBUG'] = '1'

#logging.basicConfig(level=logging.DEBUG, format=' %(levelname)s.%(threadName)s[%(relativeCreated).8s]  %(name)s::%(filename)s:%(funcName)s:%(lineno)s %(message)s', datefmt='%m-%d %H:%M')

#log = logging.getLogger(__name__)
#syslog = logging.handlers.SysLogHandler(address='/dev/log')
#formatter = logging.Formatter('%(name)s: %(levelname)s %(message)s')
#syslog.setFormatter(formatter)
#log.addHandler(syslog)

#log.debug('Hello debug')


global_extras_readed = 0
global_extras_status = [['', '', ''], ['', '', ''], ['', '', ''], ['', '', ''], ['', '', ''], ['', '', '']]


class ExtraSetupItem(ParametersSetupItem):
	def __init__(self, extra_name, extra_num, *args, **kw):
		ParametersSetupItem.__init__(self, *args, **kw)
		self.extra_name = extra_name
		self.extra_num = extra_num
		self.Start_Extra_Item = ActionItem('Start: Extra will start now', type_='setupitem', display_type=self._get_extra_status('yes', 1), action=self._start_extra, args=[])
		self.Stop_Extra_Item = ActionItem('Stop: Extra will stop now', type_='setupitem', display_type=self._get_extra_status('no', 1), action=self._stop_extra, args=[])
		self.Enable_Extra_Item = ActionItem('Enable: Extra will be automatically started after reboot', type_='setupitem', display_type=self._get_extra_status('running', 2), action=self._enable_extra, args=[])
		self.Disable_Extra_Item = ActionItem('Disable: Extra will not be automatically started after reboot', type_='setupitem', display_type=self._get_extra_status('stopped', 2), action=self._disable_extra, args=[])
		self.preview_list = [self.Start_Extra_Item, self.Stop_Extra_Item, self.Enable_Extra_Item, self.Disable_Extra_Item]

	def _get_extra_status(self, val, key):
		global global_extras_status
		ret_status = 'not_checked'
		if val in global_extras_status[self.extra_num][key]:
			ret_status = 'checked'
		return ret_status

	def _start_extra(self):
		global global_extras_status
		os.system("/wymedia/usr/bin/extras start %s"%(global_extras_status[self.extra_num][0]))
		self.Start_Extra_Item.display_type = 'checked'
		self.Stop_Extra_Item.display_type = 'not_checked'
		self.Start_Extra_Item.reset_view()
		self.Stop_Extra_Item.reset_view()

	def _stop_extra(self):
		global global_extras_status
		os.system("/wymedia/usr/bin/extras stop %s"%(global_extras_status[self.extra_num][0]))
		self.Start_Extra_Item.display_type = 'not_checked'
		self.Stop_Extra_Item.display_type = 'checked'
		self.Start_Extra_Item.reset_view()
		self.Stop_Extra_Item.reset_view()

	def _enable_extra(self):
		global global_extras_status
		os.system("/wymedia/usr/bin/extras enable %s"%(global_extras_status[self.extra_num][0]))
		self.Enable_Extra_Item.display_type = 'checked'
		self.Disable_Extra_Item.display_type = 'not_checked'
		self.Enable_Extra_Item.reset_view()
		self.Disable_Extra_Item.reset_view()

	def _disable_extra(self):
		global global_extras_status
		os.system("/wymedia/usr/bin/extras disable %s"%(global_extras_status[self.extra_num][0]))
		self.Enable_Extra_Item.display_type = 'not_checked'
		self.Disable_Extra_Item.display_type = 'checked'
		self.Enable_Extra_Item.reset_view()
		self.Disable_Extra_Item.reset_view()

	def reset_view(self):
		self.preview_list[0].display_type=self._get_extra_status('yes', 1)
		self.preview_list[1].display_type=self._get_extra_status('no', 1)
		self.preview_list[2].display_type=self._get_extra_status('running', 2)
		self.preview_list[3].display_type=self._get_extra_status('stopped', 2)
		for i in range(len(self.preview_list)):
			self.preview_list[i].reset_view()
		ParametersSetupItem.reset_view(self)


class ExtrasConfigurationItem(ParametersSetupItem):
	depth = 3
	def __init__(self, *args, **kw):
		ParametersSetupItem.__init__(self, *args, **kw)
		Task(self._set_preview_list).start(delay=1,loop=False)
		Task(self._get_extras_status).start(delay=1, loop=False)

	def browse(self):
		global global_extras_readed
		if self.menu.selected_main.__eq__(self):
			if global_extras_readed == 0:
				self._get_extras_status()
				for i in range(len(self.preview_list)):
					self.preview_list[i].reset_view()
			return self.preview_list

	def _set_preview_list(self):
#		log.debug('_set_preview_list call')
		self.preview_list = [ExtraSetupItem(name='DbUpdater', type_='setupitem', menu=self.menu, extra_name='DbUpdater', extra_num=0),
		ExtraSetupItem(name='InaDyn', type_='setupitem', menu=self.menu, extra_name='InaDyn', extra_num=1),
		ExtraSetupItem(name='Pure FTP', type_='setupitem', menu=self.menu, extra_name='Pure FTP', extra_num=2),
		ExtraSetupItem(name='Samba Client', type_='setupitem', menu=self.menu, extra_name='Samba Client', extra_num=3),
		ExtraSetupItem(name='Samba Server', type_='setupitem', menu=self.menu, extra_name='Samba Server', extra_num=4),
		ExtraSetupItem(name='Transmission', type_='setupitem', menu=self.menu, extra_name='Transmission', extra_num=5)]

	def _get_extras_status(self):
#		log.debug('_get_extras_status call')
		global global_extras_readed, global_extras_status
		global_extras_readed = 1
		output = os.popen('/wymedia/usr/bin/extras')
		output_lines = output.readlines()
		output.close()
		extra_attr = 0
		extra_index = 0
		for line in output_lines:
			if 'Status' in line or '====' in line:
				continue
			elements = line.split(' ')
			for element in elements:
				if not element is '*' and not element is ' ' and not element is '':
					global_extras_status[extra_index][extra_attr] = element.replace('\n', '')
					extra_attr = extra_attr + 1
					if extra_attr == 3:
						extra_attr = 0
						extra_index = extra_index + 1
