###################################################################
#                  Wyplayer led management items                  #
###################################################################
#                           Polo                                  #
###################################################################

from __future__ import absolute_import

from pygui.item.core import ActionItem
from pygui.menu.menu.led_policy import LedPolicySetupMenu

class LedConfigurationItem(ActionItem):
	def __init__(self, *args, **kwargs):
		ActionItem.__init__(self, *args, **kwargs)

	def execute(self):
		LedPolicySetupMenu().show(hide_previous_menu=False)
