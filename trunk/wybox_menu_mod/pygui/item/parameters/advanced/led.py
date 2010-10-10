# Led management items

# Copyright 2010, Polo35
# Licenced under Academic Free License version 3.0
# Review wydev_pygui README & LICENSE files for further details.


from __future__ import absolute_import

from pygui.item.core import ActionItem
from pygui.menu.menu.led_policy import LedPolicySetupMenu

class LedConfigurationItem(ActionItem):
	def __init__(self, *args, **kwargs):
		ActionItem.__init__(self, *args, **kwargs)

	def execute(self):
		LedPolicySetupMenu().show(hide_previous_menu=False)
