###################################################################
#                 Wyplayer HW monitoring items                    #
###################################################################
#                           Polo                                  #
###################################################################

from __future__ import absolute_import

from pygui.menu.menu.cpu_temp import CpuTempSetupMenu
from pygui.item.parameters import ParametersSetupItem
from pygui.item.core import ActionItem
from pygui.item.core import Item
from pygui.shared import pygui_globs
from peewee.notifier import Task

import os


class CpuTempSetPointItem(ActionItem):
	def __init__(self, *args, **kwargs):
		ActionItem.__init__(self, *args, **kwargs)

	def execute(self):
		CpuTempSetupMenu().show(hide_previous_menu=False)


class HwMonitoringItem(ParametersSetupItem):
	def __init__(self, device, *args, **kw):
		self.device = device
		if self.device == 'Fan':
			self.prefix = 'Fan Speed : '
			self.unit = 'Rpm'
			self.div = 1
			self.command = 'cat /sys/devices/platform/stm-pwm/pwm1 | tr -d "\n"'
		elif self.device == 'Cpu':
			self.prefix = 'Cpu Temp : '
			self.unit = '\xc2\xb0C'
			self.command = 'cat /sys/bus/i2c/devices/0-0048/temp1_input | tr -d "\n"'
			self.div = 1000
		elif self.device == 'Hdd':
			self.prefix = 'Hdd Temp : '
			self.unit = '\xc2\xb0C'
			self.command = '/wymedia/usr/bin/hddtemp -n /dev/sda -f /wymedia/usr/lib/share/misc/hddtemp.db 2> /dev/null | tr -d "\n"'
			self.div = 1
		self.name = '%s%d %s'%(self.prefix, self._get_value(self.command), self.unit)
		ParametersSetupItem.__init__(self, name=self.name, *args, **kw)
		if self.device == 'Cpu':
			self.preview_list = [CpuTempSetPointItem(name='Set Cpu Temp (%d %s)'%(self._get_setpoint_temp(), self.unit), type_='setupitem', menu=self.menu)]

	def _get_setpoint_temp(self):
		ret = 0
		output = os.popen('cat /etc/wyclim/pid.conf')
		output_lines = output.readlines()
		output.close()
		for line in output_lines:
			if 'maxtemp' in line:
				temp = line.split(' ')
				temp = temp[1].replace('\n', '')
				ret = int(temp) / 1000
		return ret

	def reset_view(self):
		self.name = '%s%d %s'%(self.prefix, self._get_value(self.command), self.unit)
		ParametersSetupItem.reset_view(self)

	def _get_value(self, command):
		fd = os.popen(command)
		output = fd.read()
		fd.close()
		return int(output) / self.div


class MonitorConfigurationItem(ParametersSetupItem):
	depth = 3
	def __init__(self, *args, **kw):
		ParametersSetupItem.__init__(self, *args, **kw)
		self.preview_list = [HwMonitoringItem(type_='setupitem', device='Fan'),
		HwMonitoringItem(type_='setupitem', device='Cpu'),
		HwMonitoringItem(type_='setupitem', device='Hdd')]
		self.refresh_task = Task(self._refresh_infos)
		self.refresh_task.start(delay=10, loop=True, init_delay=10, consider_idle=True)
		
	def _refresh_infos(self):
		if self.menu in pygui_globs['menustack']:
			if self.name == self.menu.selected_main.name:
				for i in range(len(self.preview_list)):
					self.preview_list[i].reset_view()
		else:
			self.refresh_task.stop()
