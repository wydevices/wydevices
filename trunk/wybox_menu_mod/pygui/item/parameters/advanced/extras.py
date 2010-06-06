###################################################################
#               Wyplayer extras management items                  #
###################################################################
#                           Polo                                  #
###################################################################

from __future__ import absolute_import

from pygui.item.parameters import ParametersSetupItem
from pygui.item.core import ActionItem

import os


extras_status = [['', '', ''], ['', '', ''], ['', '', ''], ['', '', ''], ['', '', ''], ['', '', '']]


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
		ret_status = 'not_checked'
		if val in extras_status[self.extra_num][key]:
			ret_status = 'checked'
		return ret_status

	def _start_extra(self):
		os.system("/wymedia/usr/bin/extras start %s"%(extras_status[self.extra_num][0]))
		self.Start_Extra_Item.display_type = 'checked'
		self.Stop_Extra_Item.display_type = 'not_checked'
		self.Start_Extra_Item.reset_view()
		self.Stop_Extra_Item.reset_view()

	def _stop_extra(self):
		os.system("/wymedia/usr/bin/extras stop %s"%(extras_status[self.extra_num][0]))
		self.Start_Extra_Item.display_type = 'not_checked'
		self.Stop_Extra_Item.display_type = 'checked'
		self.Start_Extra_Item.reset_view()
		self.Stop_Extra_Item.reset_view()

	def _enable_extra(self):
		os.system("/wymedia/usr/bin/extras enable %s"%(extras_status[self.extra_num][0]))
		self.Enable_Extra_Item.display_type = 'checked'
		self.Disable_Extra_Item.display_type = 'not_checked'
		self.Enable_Extra_Item.reset_view()
		self.Disable_Extra_Item.reset_view()

	def _disable_extra(self):
		os.system("/wymedia/usr/bin/extras disable %s"%(extras_status[self.extra_num][0]))
		self.Enable_Extra_Item.display_type = 'not_checked'
		self.Disable_Extra_Item.display_type = 'checked'
		self.Enable_Extra_Item.reset_view()
		self.Disable_Extra_Item.reset_view()


class ExtrasConfigurationItem(ParametersSetupItem):
	depth = 3
	def __init__(self, *args, **kw):
		ParametersSetupItem.__init__(self, *args, **kw)
		self._get_extras_status()

	def browse(self):
		return [ExtraSetupItem(name='DbUpdater', type_='setupitem', menu=self.menu, extra_name='DbUpdater', extra_num=0),
		ExtraSetupItem(name='InaDyn', type_='setupitem', menu=self.menu, extra_name='InaDyn', extra_num=1),
		ExtraSetupItem(name='Pure FTP', type_='setupitem', menu=self.menu, extra_name='Pure FTP', extra_num=2),
		ExtraSetupItem(name='Samba Client', type_='setupitem', menu=self.menu, extra_name='Samba Client', extra_num=3),
		ExtraSetupItem(name='Samba Server', type_='setupitem', menu=self.menu, extra_name='Samba Server', extra_num=4),
		ExtraSetupItem(name='Transmission', type_='setupitem', menu=self.menu, extra_name='Transmission', extra_num=5)]

	def _get_extras_status(self):
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
					extras_status[extra_index][extra_attr] = element.replace('\n', '')
					extra_attr = extra_attr + 1
					if extra_attr == 3:
						extra_attr = 0
						extra_index = extra_index + 1
