###################################################################
#                  Wyplayer led policy menu                       #
###################################################################
#                           Polo                                  #
###################################################################

from __future__ import absolute_import

from pygui.menu.menu.core import Menu
from pygui.item.core import Item
from pygui.item.parameters import IterItemContainer
from pygui.item.containers import GenericContainer
from pygui.eventmanager.menus.led_policy import LedPolicyMenuEventHandler
from pygui.window import ConfirmWindow
from pygui.window.core import Button

from xml.etree.ElementTree import Element, ElementTree, XMLTreeBuilder

import os, shutil

glob_led_policy_etree = ElementTree()
glob_led_policy = {}
glob_led_signal_list = {}


glob_led_dict = {0: 'RED', 1: 'BLUE', 2: 'ORA', 3: 'ALL_LED'}
glob_led_use_dict = {0: 'yes', 1: 'no'}
glob_led_action_dict = {0: 'LED_ON', 1: 'LED_OFF', 2: 'LED_SWITCH', 3: 'XBLINKS', 4: 'QUICK_BLINK', 5: 'ROUND_TRIP'}
glob_led_action_property_dict = {0: 'NB_BLINKS', 1: 'ON_TIME', 2: 'OFF_TIME'}
 
glob_signal_RBO = """
<signal xml:id="xid_rbo" name="rbo">
    <led name="RED" use="yes" action="LED_ON" priority="10">
    </led>
    <led name="BLUE"  use="yes" action="LED_ON" priority="10">
    </led>
    <led name="ORA"  use="yes" action="LED_ON" priority="10">
    </led>
  </signal>
"""
glob_signal_ALL = """
  <signal xml:id="xid_all" name="all">
    <led name="ALL_LED" use="yes" action="ROUND_TRIP" priority="10">
      <on_time>500000</on_time>
    </led>
  </signal>
"""


class LedCommonSetupItem(Item):
	def __init__(self, name, type_, menu = None, display_type = None, action = None, args = None, kwargs = None):
		Item.__init__(self, name, type_, menu=menu, display_type=display_type)
		self.action = action
		self.preview_list = []
		self.args = (args or tuple())
		self.kwargs = (kwargs or dict())

	def reset_view(self):
		for i in range(len(self.preview_list)):
			self.preview_list[i].reset_view()
		Item.reset_view(self)

	def execute(self):
		if (self.action is None):
			return 
		try:
			self.action(*self.args, **self.kwargs)
		except:
			pass

	def browse(self):
		return self.preview_list


class LedUsedSetupItem(LedCommonSetupItem):
	def __init__(self, signal_num, led_num, *args, **kw):
		self.led_num = led_num
		self.signal_num = signal_num
		LedCommonSetupItem.__init__(self, *args, **kw)
		self.preview_list = [LedCommonSetupItem(name='Yes', type_='setupitem', menu=self.menu, display_type=self._get_use('y'), action=self._set_use, args=[0]),
		LedCommonSetupItem(name='No', type_='setupitem', menu=self.menu, display_type=self._get_use('n'), action=self._set_use, args=[1])]

	def reset_view(self):
		self.preview_list[0].display_type = self._get_use('y')
		self.preview_list[1].display_type = self._get_use('n')
		LedCommonSetupItem.reset_view(self)

	def _get_use(self, val):
		global glob_led_signal_list
		ret = 'not_checked'
		led_list = glob_led_signal_list[self.signal_num].getiterator('led')
		for led in led_list:
			if glob_led_dict[self.led_num] in led.attrib['name'] and val in led.attrib['use']:
				ret = 'checked'
		return ret

	def _set_use(self, val):
		global glob_led_signal_list
		led_list = glob_led_signal_list[self.signal_num].getiterator('led')
		for led in led_list:
			if glob_led_dict[self.led_num] in led.attrib['name']:
				led.attrib['use'] = glob_led_use_dict[val]
		for i in range(len(self.preview_list)):
			if i == val:
				self.preview_list[i].display_type = 'checked'
			else:
				self.preview_list[i].display_type = 'not_checked'
			self.preview_list[i].reset_view()


class LedActionChildrenSetupItem(LedCommonSetupItem):
	def __init__(self, mode, signal_num, led_num, *args, **kw):
		self.mode = mode
		self.led_num = led_num
		self.signal_num = signal_num
		if self.mode == 'on_time':
			self.prefix = 'On Time : '
		elif self.mode == 'off_time':
			self.prefix = 'Off Time : '
		elif self.mode == 'nb_blinks':
			self.prefix = 'Nb Blinks : '
		else:
		  return
		if self.mode == 'nb_blinks':
			self.name = '%s%d'%(self.prefix, int(self._get_value()))
		else:
			self.name = '%s%.1f'%(self.prefix, float(self._get_value()))
		LedCommonSetupItem.__init__(self, name=self.name, *args, **kw)

	def reset_view(self):
		if self.mode == 'nb_blinks':
			self.name = '%s%d'%(self.prefix, int(self._get_value()))
		else:
			self.name = '%s%.1f'%(self.prefix, float(self._get_value()))
		LedCommonSetupItem.reset_view(self)

	def _get_value(self):
		global glob_led_signal_list
		ret = 0.0
		led_list = glob_led_signal_list[self.signal_num].getiterator('led')
		for led in led_list:
			if glob_led_dict[self.led_num] in led.attrib['name']:
				child_list = led.getiterator(self.mode)
				for child in child_list:
					ret = float(child.text)
					if self.mode != 'nb_blinks':
						ret = ret / 1000000
		return ret

	def _set_value(self, val):
		global glob_led_signal_list
		led_list = glob_led_signal_list[self.signal_num].getiterator('led')
		for led in led_list:
			if glob_led_dict[self.led_num] in led.attrib['name']:
				child_list = led.getiterator(self.mode)
				for child in child_list:
					old_value = int(child.text)
					if self.mode != 'nb_blinks':
						val = val * 500000
					new_value = old_value + val
					if new_value <= 1 and self.mode == 'nb_blinks':
						new_value = 1
					if new_value <= 500000 and self.mode != 'nb_blinks':
						new_value = 500000
					elif new_value >= 99 and self.mode == 'nb_blinks':
						new_value = 99
					elif new_value >= 99000000 and self.mode != 'nb_blinks':
						new_value = 99000000
					child.text = str(new_value)
		if self.mode == 'nb_blinks':
			self.name = '%s%d'%(self.prefix, int(self._get_value()))
		else:
			self.name = '%s%.1f'%(self.prefix, float(self._get_value()))
		self.reset_view()


class LedActionPropertySetupItem(LedCommonSetupItem):
	def __init__(self, signal_num, led_num, *args, **kw):
		self.led_num = led_num
		self.signal_num = signal_num
		LedCommonSetupItem.__init__(self, *args, **kw)
		if self.name == 'Led Switch':
			self.preview_list= [LedActionChildrenSetupItem(type_='setupitem', menu=self.menu, mode='on_time', signal_num=self.signal_num, led_num=self.led_num),
			LedActionChildrenSetupItem(type_='setupitem', menu=self.menu, mode='off_time', signal_num=self.signal_num, led_num=self.led_num)]
		elif self.name == 'X Blinks':
			self.preview_list= [LedActionChildrenSetupItem(type_='setupitem', menu=self.menu, mode='nb_blinks', signal_num=self.signal_num, led_num=self.led_num),
			LedActionChildrenSetupItem(type_='setupitem', menu=self.menu, mode='on_time', signal_num=self.signal_num, led_num=self.led_num),
			LedActionChildrenSetupItem(type_='setupitem', menu=self.menu, mode='off_time', signal_num=self.signal_num, led_num=self.led_num)]
		if self.name == 'Round Trip':
			self.preview_list= [LedActionChildrenSetupItem(type_='setupitem', menu=self.menu, mode='on_time', signal_num=self.signal_num, led_num=self.led_num)]

class LedActionSetupItem(LedCommonSetupItem):
	def __init__(self, signal_num, led_num, *args, **kw):
		self.led_num = led_num
		self.signal_num = signal_num
		LedCommonSetupItem.__init__(self, *args, **kw)
		if self.led_num != 3:
			self.preview_list= [LedCommonSetupItem(name='Led On', type_='setupitem', menu=self.menu, display_type=self._get_action(0), action=self._set_action, args=[0]),
			LedCommonSetupItem(name='Led Off', type_='setupitem', menu=self.menu, display_type=self._get_action(1), action=self._set_action, args=[1]),
			LedActionPropertySetupItem(name='Led Switch', type_='setupitem', menu=self.menu, display_type=self._get_action(2), action=self._set_action, args=[2], signal_num=self.signal_num, led_num=self.led_num),
			LedActionPropertySetupItem(name='X Blinks', type_='setupitem', menu=self.menu, display_type=self._get_action(3), action=self._set_action, args=[3], signal_num=self.signal_num, led_num=self.led_num),
			LedCommonSetupItem(name='Quick Blink', type_='setupitem', menu=self.menu, display_type=self._get_action(4), action=self._set_action, args=[4])]
		else:
			self.preview_list= [LedActionPropertySetupItem(name='Round Trip', type_='setupitem', menu=self.menu, display_type=self._get_action(5), action=self._set_action, args=[5], signal_num=self.signal_num, led_num=self.led_num)]

	def reset_view(self):
		for i in range(len(self.preview_list)):
			if self.led_num != 3:
				self.preview_list[i].display_type = self._get_action(i)
			else:
				self.preview_list[i].display_type = self._get_action(5)
		LedCommonSetupItem.reset_view(self)

	def _get_action(self, val):
		global glob_led_signal_list
		ret = 'not_checked'
		led_list = glob_led_signal_list[self.signal_num].getiterator('led')
		for led in led_list:
			if glob_led_dict[self.led_num] in led.attrib['name'] and glob_led_action_dict[val] in led.attrib['action']:
				ret = 'checked'
		return ret

	def _set_action(self, val):
		global glob_led_signal_list
		led_list = glob_led_signal_list[self.signal_num].getiterator('led')
		for led in led_list:
			if glob_led_dict[self.led_num] in led.attrib['name']:
				led.attrib['action'] = glob_led_action_dict[val]
				try:
					child_list = led.getiterator('on_time')
					led.remove(child_list[0])
					child_list = led.getiterator('off_time')
					led.remove(child_list[0])
					child_list = led.getiterator('nb_blinks')
					led.remove(child_list[0])
				except:
					pass
				nb_blinks = Element('nb_blinks')
				nb_blinks.text = '3'
				nb_blinks.tail = '\n     '
				on_time = Element('on_time')
				on_time.text = '1000000'
				on_time.tail = '\n     '
				off_time = Element('off_time')
				off_time.text = '1000000'
				off_time.tail = '\n    '
				if glob_led_action_dict[val] == 'LED_SWITCH':
					led.append(on_time)
					led.append(off_time)
				elif glob_led_action_dict[val] == 'XBLINKS':
					led.append(nb_blinks)
					led.append(on_time)
					led.append(off_time)
		for i in range(len(self.preview_list)):
			if i == val:
				self.preview_list[i].display_type = 'checked'
			else:
				self.preview_list[i].display_type = 'not_checked'
			self.preview_list[i].reset_view()


class LedPrioritySetupItem(LedCommonSetupItem):
	def __init__(self, signal_num, led_num, *args, **kw):
		self.led_num = led_num
		self.signal_num = signal_num
		self.name = 'Priority : %d'%(self._get_value())
		LedCommonSetupItem.__init__(self, name=self.name, *args, **kw)

	def reset_view(self):
		self.name = 'Priority : %d'%(self._get_value())
		LedCommonSetupItem.reset_view(self)

	def _get_value(self):
		global glob_led_signal_list
		ret = 0
		led_list = glob_led_signal_list[self.signal_num].getiterator('led')
		for led in led_list:
			if glob_led_dict[self.led_num] in led.attrib['name']:
				ret = int(led.attrib['priority'])
		return ret

	def _set_value(self, val):
		global glob_led_signal_list
		led_list = glob_led_signal_list[self.signal_num].getiterator('led')
		for led in led_list:
			if glob_led_dict[self.led_num] in led.attrib['name']:
				old_value = int(led.attrib['priority'])
				new_value = old_value + val
				if new_value <= 0:
					led.attrib['priority'] = '0'
				elif new_value >= 10:
					led.attrib['priority'] = '10'
				else:
					led.attrib['priority'] = new_value
		self.name = 'Priority : %d'%(self._get_value())
		self.reset_view()


class LedColorSetupItem(LedCommonSetupItem):
	def __init__(self, signal_num, led_num, *args, **kw):
		self.led_num = led_num
		self.signal_num = signal_num
		LedCommonSetupItem.__init__(self, *args, **kw)
		self.preview_list= [LedUsedSetupItem(name="Used", type_='setupitem', menu=self.menu, signal_num=self.signal_num, led_num=self.led_num),
		LedActionSetupItem(name="Action", type_='setupitem', menu=self.menu, signal_num=self.signal_num, led_num=self.led_num),
		LedPrioritySetupItem(type_='setupitem', menu=self.menu, signal_num=self.signal_num, led_num=self.led_num)]


class LedSignalSetupItem(LedCommonSetupItem):
	def __init__(self, signal_num, *args, **kw):
		self.signal_num = signal_num
		LedCommonSetupItem.__init__(self, *args, **kw)
		self.preview_list = [LedColorSetupItem(name="Red", type_='setupitem', menu=self.menu, display_type=self._get_led(0), action=self._set_led, args=[0], signal_num=self.signal_num, led_num=0),
		LedColorSetupItem(name="Blue", type_='setupitem', menu=self.menu, display_type=self._get_led(1), action=self._set_led, args=[1], signal_num=self.signal_num, led_num=1),
		LedColorSetupItem(name="Orange", type_='setupitem', menu=self.menu, display_type=self._get_led(2), action=self._set_led, args=[2], signal_num=self.signal_num, led_num=2),
		LedColorSetupItem(name="All", type_='setupitem', menu=self.menu, display_type=self._get_led(3), action=self._set_led, args=[3], signal_num=self.signal_num, led_num=3)]

	def _get_led(self, val):
		global glob_led_signal_list
		ret = 'not_checked'
		led_list = glob_led_signal_list[self.signal_num].getiterator('led')
		for led in led_list:
			if glob_led_dict[val] in led.attrib['name']:
				ret = 'checked'
		return ret

	def _set_led(self, val):
		global glob_led_policy, glob_led_signal_list
		if not self.preview_list[val].display_type == 'not_checked':
			return
		new_signal = ElementTree()
		parser = XMLTreeBuilder()
		if val == 3:
			parser.feed(glob_signal_ALL)
		else:
			parser.feed(glob_signal_RBO)
		new_signal._root = parser.close()
		new_signal_list = new_signal.getiterator("signal")
		new_signal_list[0].attrib = glob_led_signal_list[self.signal_num].attrib
		new_signal_list[0].tail = glob_led_signal_list[self.signal_num].tail
		glob_led_policy.remove(glob_led_signal_list[self.signal_num])
		glob_led_policy.insert(self.signal_num, new_signal_list[0])
		glob_led_signal_list[self.signal_num] = new_signal_list[0]
		for i in range(len(self.preview_list)):
			self.preview_list[i].display_type = self._get_led(i)
			self.preview_list[i].reset_view()


class LedPolicySetupMenu(Menu):

	def __init__(self, **kw):
		global glob_led_signal_list
		main_items = []
		self._init_etree('/etc/led_policy.xml')
		self._save_led_policy()
		for i in range(len(glob_led_signal_list)):
			main_items.append(LedSignalSetupItem(name=glob_led_signal_list[i].attrib["name"], type_='setupitem', menu=self, signal_num=i))
		self.eventhandler = LedPolicyMenuEventHandler(self)
		Menu.__init__(self, name='Led Policy', choices=main_items, type='ledsetup', **kw)
		self.available_choices = ['main_list', 'led_list', 'property_list', 'option0_list', 'option1_list']
		self._getitems_keywords.update(dict(led_list=(lambda : self.get_item_list('led_list')), property_list=(lambda : self.get_item_list('property_list')), option0_list=(lambda : self.get_item_list('option0_list')), option1_list=(lambda : self.get_item_list('option1_list'))))
		self._browse_main()
		self.active_list = 'main_list'

	def _init_etree(self, path):
		global glob_led_policy, glob_led_signal_list, glob_led_policy_etree
		glob_led_policy_etree.parse(path)
		glob_led_policy = glob_led_policy_etree.find('led_policy/signal_list')
		glob_led_signal_list = glob_led_policy.getiterator('signal')

	def _set_signal(self, value):
		item_list = self.get_item_list('main_list')
		item_list.select(value)

	def _get_signal(self):
		item_list = self.get_item_list('main_list')
		ret = item_list.selected
		if ret is None:
			ret = self['main_list'][0]
		return ret

	selected_signal = property(_get_signal, _set_signal)
	del _get_signal
	del _set_signal


	def _set_led(self, value):
		item_list = self.get_item_list('led_list')
		item_list.select(value)

	def _get_led(self):
		item_list = self.get_item_list('led_list')
		ret = item_list.selected
		if ret is None:
			ret = self['led_list'][0]
		return ret

	selected_led = property(_get_led, _set_led)
	del _get_led
	del _set_led


	def _set_property(self, value):
		item_list = self.get_item_list('property_list')
		item_list.select(value)

	def _get_property(self):
		item_list = self.get_item_list('property_list')
		ret = item_list.selected
		if ret is None:
			ret = self['property_list'][0]
		return ret

	selected_property = property(_get_property, _set_property)
	del _get_property
	del _set_property


	def _set_option0(self, value):
		item_list = self.get_item_list('option0_list')
		item_list.select(value)

	def _get_option0(self):
		item_list = self.get_item_list('option0_list')
		ret = item_list.selected
		if ret is None:
			ret = self['option0_list'][0]
		return ret

	selected_option0 = property(_get_option0, _set_option0)
	del _get_option0
	del _set_option0

	def _browse_main(self):
		content = self.selected_signal.browse()
		self.set_items(content, 'led_list')
		self._browse_led()

	def _browse_led(self):
		content = self.selected_led.browse()
		self.set_items(content, 'property_list')
		self._browse_property()

	def _browse_property(self):
		try:
			content = self.selected_property.browse()
			self.set_items(content, 'option0_list')
			self._browse_option0()
		except:
			content = []
			self.set_items(content, 'option0_list')

	def _browse_option0(self):
		try:
			content = self.selected_option0.browse()
		except:
			content = []
		self.set_items(content, 'option1_list')

	def _browse_selected(self):
		switch = {'main_list': self._browse_main, 'led_list': self._browse_led, 'property_list': self._browse_property, 'option0_list': self._browse_option0}
		switch.get(self.active_list, (lambda : None))()


	def select(self, item, list_name=None, force_pos=False):
		ret = Menu.select(self, item, list_name, force_pos)
		if ret and self.active_list != 'option1_list':
			self._browse_selected()
		return ret

	def focus_next(self):
		ret = Menu.focus_next(self)
		if ret and self.active_list != 'option1_list':
			self._browse_selected()
		return ret

	def focus_previous(self):
		ret = Menu.focus_previous(self)
		if ret:
			self._browse_selected()
		return ret

	def _save_led_policy(self):
		if not os.path.exists('/etc/led_policy_save.xml'):
			shutil.copy2('/etc/led_policy.xml', '/etc/led_policy_save.xml')

	def return_to_factory(self):
		global glob_led_signal_list
		self.active_list = 'main_list'
		self._init_etree('/etc/led_policy_save.xml')
		main_items = []
		for i in range(len(glob_led_signal_list)):
			main_items.append(LedSignalSetupItem(name=glob_led_signal_list[i].attrib["name"], type_='setupitem', menu=self, signal_num=i))
		self.reset(name='Led Policy', choices=main_items, type='ledsetup', universe='setup')
		self._browse_main()


	def apply_change(self):
		global glob_led_policy_etree
		glob_led_policy_etree.write('/etc/led_policy.xml', 'UTF-8')
		w = ConfirmWindow(text='Modifications will take effect after reboot.\nDo you want to reboot now ?', confirm_action=self._reinit_box, buttons=[Button(_('Yes'), False), Button(_('No'), True)])
		w.show()

	def _reinit_box(self):
		os.system('/sbin/reboot')