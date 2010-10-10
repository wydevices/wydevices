# HW monitoring items

# Copyright 2010, Polo35
# Licenced under Academic Free License version 3.0
# Review wydev_pygui README & LICENSE files for further details.


from __future__ import absolute_import

from pygui.menu.menu.cpu_temp import CpuTempSetupMenu
from pygui.item.parameters import ParametersSetupItem
from pygui.item.core import ActionItem
from pygui.item.core import Item
from pygui.shared import pygui_globs
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


class CpuTempSetPointItem(ActionItem):
	def __init__(self, *args, **kwargs):
		ActionItem.__init__(self, *args, **kwargs)

	def execute(self):
		CpuTempSetupMenu().show(hide_previous_menu=False)


class HwMonitoringItem(ParametersSetupItem):
	def __init__(self, device, *args, **kw):
		self.device = device
		if self.device == 'Fan':
			self.prefix = _('Fan Speed : ')
			self.unit = _('Rpm')
			self.div = 1
			self.command = 'cat /sys/devices/platform/stm-pwm/pwm1 | tr -d "\n"'
		elif self.device == 'Cpu':
			self.prefix = _('Cpu Temp : ')
			self.unit = '\xc2\xb0C'
			self.command = 'cat /sys/bus/i2c/devices/0-0048/temp1_input | tr -d "\n"'
			self.div = 1000
		elif self.device == 'Hdd':
			self.prefix = _('Hdd Temp : ')
			self.unit = '\xc2\xb0C'
			self.command = '/wymedia/usr/bin/hddtemp -n /dev/sda -f /wymedia/usr/lib/share/misc/hddtemp.db 2> /dev/null | tr -d "\n"'
			self.div = 1
		self.name = '%s-- %s'%(self.prefix, self.unit)
		ParametersSetupItem.__init__(self, name=self.name, *args, **kw)
		if self.device == 'Cpu':
			self.preview_list = [CpuTempSetPointItem(name=_('Set Cpu Temp (%d %s)') % (self._get_setpoint_temp(), self.unit), type_='setupitem', menu=self.menu)]

	def _get_setpoint_temp(self):
		try:
			output = os.popen('cat /etc/wyclim/pid.conf')
			output_lines = output.readlines()
			output.close()
			for line in output_lines:
				if 'maxtemp' in line:
					temp = line.split(' ')
					temp = temp[1].replace('\n', '')
			return int(temp) / 1000
		except:
			return 0

	def reset_view(self):
#		log.debug('reset_view call')
		new_name = '%s%d %s'%(self.prefix, self._get_value(self.command), self.unit)
		if new_name != self.name:
			self.name = new_name
			ParametersSetupItem.reset_view(self)

	def _get_value(self, command):
#		log.debug('_get_value call')
		try:
			fd = os.popen(command)
			output = fd.read()
			fd.close()
			return int(output) / self.div
		except:
			return 0


class CpuLoadMonitoringItem(ParametersSetupItem):
	def __init__(self, *args, **kw):
		self.name = _('Cpu Usage : -- %')
		ParametersSetupItem.__init__(self, name=self.name, *args, **kw)

	def reset_view(self):
#		log.debug('reset_view call')
		new_name = _('Cpu Usage : %d %%') % (self._get_value())
		if new_name != self.name:
			self.name = new_name
			ParametersSetupItem.reset_view(self)

	def _get_value(self):
#		log.debug('_get_value call')
		try:
			cpu_loads = [0, 0]
			for i in range(2):
				fd = os.popen('cat /proc/stat | tr -d "\n"')
				stat_line = fd.readline()
				fd.close()
				if 'cpu ' in stat_line:
					element_list = stat_line.split(' ')
					cpu_loads[i] = int(element_list[2]) + int(element_list[3]) + int(element_list[4])
			return cpu_loads[1] - cpu_loads[0]
		except:
			return 0


class MemLoadMonitoringItem(ParametersSetupItem):
	def __init__(self, *args, **kw):
		self.name = _('Mem Usage : -- %')
		ParametersSetupItem.__init__(self, name=self.name, *args, **kw)

	def reset_view(self):
#		log.debug('reset_view call')
		new_name = _('Mem Usage : %d %%') % (self._get_value())
		if new_name != self.name:
			self.name = new_name
			ParametersSetupItem.reset_view(self)

	def _get_value(self):
#		log.debug('_get_value call')
		try:
			mem_load = []
			fd = os.popen('free')
			fd.readline()
			stat_line = fd.readline()
			fd.close()
			if 'Mem: ' in stat_line:
				element_list = stat_line.split(' ')
				for element in element_list:
					if not 'Mem:' in element and not element is '0' and not element is ' ' and not element is '':
						mem_load.append(int(element))
			return int((100.00/float(mem_load[0]))*float(mem_load[1]))
		except:
			return 0


class MonitorConfigurationItem(ParametersSetupItem):
	depth = 3
	def __init__(self, *args, **kw):
		ParametersSetupItem.__init__(self, *args, **kw)
		self.refresh_task = Task(self._refresh_infos)
		Task(self._set_preview_list).start(delay=1,loop=False)
		
	def browse(self):
		if self.menu.selected_main.__eq__(self):
			self.refresh_task.start(delay=5, loop=True, init_delay=0, consider_idle=True)
			return self.preview_list

	def _set_preview_list(self):
#		log.debug('_set_preview_list call')
		self.preview_list = [HwMonitoringItem(type_='setupitem', device='Fan'),
			HwMonitoringItem(type_='setupitem', device='Cpu'),
			HwMonitoringItem(type_='setupitem', device='Hdd'),
			CpuLoadMonitoringItem(type_='setupitem'),
			MemLoadMonitoringItem(type_='setupitem')]

	def _refresh_infos(self):
#		log.debug('_refresh_infos call')
		if self.menu.selected_main.__eq__(self):
			for i in range(len(self.preview_list)):
				self.preview_list[i].reset_view()
		else:
			self.refresh_task.stop()
