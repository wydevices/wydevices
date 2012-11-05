# Led policy menu eventmanager

# Copyright 2010, Polo35
# Licenced under Academic Free License version 3.0
# Review wydev_pygui README & LICENSE files for further details.


from pygui.eventmanager.menus.core import MenuEventHandler
from pygui.shared import pygui_globs

class LedPolicyMenuEventHandler(MenuEventHandler):
	def event_left(self, event):
		if self.player.active_list == 'main_list':
			pygui_globs['menustack'].back_one_menu()
			return True
		self.player.focus_previous()
		return True

	def event_right(self, event):
		self.player.focus_next()
		return True

	def event_up(self, event):
		MenuEventHandler.event_up(self, event)
		return True

	def event_down(self, event):
		MenuEventHandler.event_down(self, event)
		return True

	def event_wheel_rwd(self, event):
		try:
			ac = self.player.active_list
			if ac == 'main_list':
				MenuEventHandler.event_wheel_rwd(self, event)
			else:
				sel = self.player.get_item_list(ac).selected
				if sel is None:
					return True
				sel._set_value(-1)
		except:
			pass
		return True

	def event_wheel_fwd(self, event):
		try:
			ac = self.player.active_list
			if ac == 'main_list':
				MenuEventHandler.event_wheel_fwd(self, event)
			else:
				sel = self.player.get_item_list(ac).selected
				if sel is None:
					return True
				sel._set_value(1)
		except:
			pass
		return True

	def event_select(self, event, list_name = 'main_list'):
		MenuEventHandler.event_select(self, event, list_name = self.player.active_list)
		return True

	def event_home(self, event):
		return True

	def event_toggle_menu(self, event):
		return True

	def event_stop(self, event):
		self.player.return_to_factory()
		return True

	def event_record(self, event):
		self.player.apply_change()
		return True
