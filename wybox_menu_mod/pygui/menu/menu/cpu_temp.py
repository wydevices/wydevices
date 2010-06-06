###################################################################
#               Wyplayer cpu setpoint temp menu                   #
###################################################################
#                           Polo                                  #
###################################################################

from __future__ import absolute_import

from pygui.menu.menu.core import Menu
from pygui.item.core import Item
from pygui.eventmanager.menus.cpu_temp import CpuTempMenuEventHandler
from pygui.window import ConfirmWindow
from pygui.window.core import Button

import os


glob_setpoint_temp = 0

class TempSetupItem(Item):
	def __init__(self, *args, **kw):
		self.name = 'SetPoint : %d \xc2\xb0C'%(self._get_value())
		Item.__init__(self, name=self.name, *args, **kw)

	def _get_value(self):
		global glob_setpoint_temp
		return glob_setpoint_temp

	def _set_value(self, val):
		global glob_setpoint_temp
		glob_setpoint_temp = glob_setpoint_temp + val
		if glob_setpoint_temp >= 55:
			glob_setpoint_temp = 55
		elif glob_setpoint_temp <= 40:
			glob_setpoint_temp = 40
		self.name = 'SetPoint : %d \xc2\xb0C'%(self._get_value())
		self.reset_view()

class CpuTempSetupMenu(Menu):

	def __init__(self, **kw):
		self.eventhandler = CpuTempMenuEventHandler(self)
		self._get_setpoint_temp()
		Menu.__init__(self, name='Cpu Setpoint Temperature', choices=[TempSetupItem(type_='setupitem', menu=self)], type='cputempsetup', **kw)


	def _get_setpoint_temp(self):
		global glob_setpoint_temp
		output = os.popen('cat /etc/wyclim/pid.conf')
		output_lines = output.readlines()
		output.close()
		for line in output_lines:
			if 'maxtemp' in line:
				temp = line.split(' ')
				temp = temp[1].replace('\n', '')
				glob_setpoint_temp = int(temp) / 1000

	def apply_change(self):
		global glob_setpoint_temp
		output_lines = []
		input_file = os.popen('cat /etc/wyclim/pid.conf')
		input_lines = input_file.readlines()
		input_file.close()
		output = os.open('/etc/wyclim/pid.conf', (os.O_WRONLY | os.O_TRUNC))
		for line in input_lines:
			if 'maxtemp' in line:
				os.write(output, ('maxtemp %d\n'%(glob_setpoint_temp * 1000)))
			else:
				os.write(output, line)
		os.close(output)
		w = ConfirmWindow(text='Modifications will take effect after reboot.\nDo you want to reboot now ?', confirm_action=self._reinit_box, buttons=[Button(_('Yes'), False), Button(_('No'), True)])
		w.show()

	def _reinit_box(self):
		os.system('/sbin/reboot')
