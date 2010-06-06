###################################################################
#               Wyplayer reboot management items                  #
###################################################################
#                           Polo                                  #
###################################################################

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
		return [RebootWyDevItem(name='Reboot Wydevice: it\'s the same than using the clip. Full Reboot.', type_='action', user_arg='/sbin/reboot'),
		RebootWyDevItem(name='Reboot Splash: restarts the python GUI, will free mem and less time than a full reboot. Do not restarts transmission.', type_='action', user_arg='/bin/killall python2.5'),
		RebootWyDevItem(name='Restart Player: is ok when the player is hung after a crash.', type_='action', user_arg='/wymedia/usr/bin/pkill wyplayer'),
		RebootWyDevItem(name='Shutdown Wydevice: turns off device and HDD. Good for nights of before planning a trip, for example. ', type_='action', user_arg='/sbin/poweroff'),
		ReInitBoxItem(name='Original WyDev Reboot', type_='setupitem')]
