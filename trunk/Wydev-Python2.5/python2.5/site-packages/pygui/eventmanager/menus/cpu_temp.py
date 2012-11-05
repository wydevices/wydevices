# Cpu setpoint temp menu eventmanager

# Copyright 2010, Polo35
# Licenced under Academic Free License version 3.0
# Review wydev_pygui README & LICENSE files for further details.


from pygui.eventmanager.menus.core import MenuEventHandler
from pygui.shared import pygui_globs

class CpuTempMenuEventHandler(MenuEventHandler):
	def event_left(self, event):
		pygui_globs['menustack'].back_one_menu()
		return True

	def event_right(self, event):
		return True

	def event_up(self, event):
		sel = self.player.get_item_list('main_list').selected
		sel._set_value(1)
		return True

	def event_down(self, event):
		sel = self.player.get_item_list('main_list').selected
		sel._set_value(-1)
		return True

	event_wheel_fwd = event_up
	event_wheel_rwd = event_down

	def event_select(self, event, list_name = 'main_list'):
		return True

	def event_home(self, event):
		return True

	def event_toggle_menu(self, event):
		return True

	def event_stop(self, event):
		return True

	def event_record(self, event):
		self.player.apply_change()
		return True
