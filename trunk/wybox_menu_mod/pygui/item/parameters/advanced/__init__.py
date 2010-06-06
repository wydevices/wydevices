###################################################################
#               Wyplayer advanced parameters items                #
###################################################################
#                           Polo                                  #
###################################################################

from __future__ import absolute_import

from pygui.item.containers import GenericContainer
from pygui.item.parameters.advanced.monitor import MonitorConfigurationItem
from pygui.item.parameters.advanced.extras import ExtrasConfigurationItem
from pygui.item.parameters.advanced.reboot import RebootConfigurationItem
from pygui.item.parameters.advanced.led import LedConfigurationItem


def getitem(menu):
	return AdvancedSetupItem(_('Advanced'), type_='general', display_type='setup_advance', menu=menu)

class AdvancedSetupItem(GenericContainer):
	def browse(self):
		return [MonitorConfigurationItem(name='Monitor', type_='setupitem', menu=self.menu),
		ExtrasConfigurationItem(name='Extras', type_='setupitem', menu=self.menu),
		RebootConfigurationItem(name='Reboot', type_='setupitem', menu=self.menu),
		LedConfigurationItem(name='Led Policy', type_='setupitem', menu=self.menu)]

	def update_name(self):
		self._name = _('Advanced')



