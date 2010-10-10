# Reboot management items

# Copyright 2010, Polo35
# Licenced under Academic Free License version 3.0
# Review wydev_pygui README & LICENSE files for further details.


from __future__ import absolute_import

from pygui.item.parameters import ParametersSetupItem
from pygui.item.parameters.advanced.advancedsetup import ReInitBoxItem
from pygui.window import ConfirmWindow
from pygui.window.core import Button
from pygui.item.core import ActionItem

import os


class RebootWyDevItem(ActionItem):
	def __init__(self, user_arg, *args, **kw):
		self.user_arg = user_arg
		ActionItem.__init__(self, action=self._check_action, **kw)

	def _check_action(self):
		w = ConfirmWindow(text=_('Are you sure ?'), confirm_action=self.reinit_box, buttons=[Button(_('Yes'), False), Button(_('No'), True)])
		w.show()

	def reinit_box(self):
		os.system(self.user_arg)


class RebootConfigurationItem(ParametersSetupItem):
	depth = 3
	def __init__(self, *args, **kw):
		ParametersSetupItem.__init__(self, *args, **kw)

	def browse(self):
		if self.menu.selected_main.__eq__(self):
			return [RebootWyDevItem(name=_('Reboot Wydevice'), type_='action', user_arg='/sbin/reboot'),
			RebootWyDevItem(name=_('Reboot Splash'), type_='action', user_arg='/bin/killall python2.5'),
			RebootWyDevItem(name=_('Restart Player'), type_='action', user_arg='/wymedia/usr/bin/pkill wyplayer'),
			RebootWyDevItem(name=_('Shutdown Wydevice'), type_='action', user_arg='/sbin/poweroff'),
			ReInitBoxItem(name=_('Original WyDev Reboot'), type_='setupitem')]
