# Core hand actions
# Add "Copy to My Videos" and "Move to My Videos" entries in Navigator "hand menu"
# In menu implementation of Rec2vid script

# Copyright 2010, Polo35
# Licenced under Academic Free License version 3.0
# Review wydev_pygui README & LICENSE files for further details.


from __future__ import absolute_import

import os
import re
import glob
import shutil

from peewee.formatters import ellipsize
from peewee.notifier import Task, HookThread
from peewee.debug import GET_LOGGER, PRINT_EXCEPTION

from pygui import config
from pygui.item.core import ActionItem, Item
from pygui.markerlist import get_marker_from_item
from pygui.item.containers import GenericContainer
from pygui.item.mediaitem.audiovideo import TVRecordItem
from pygui.actions.jobs.fs import execute_delete, GraphicalProgress
from pygui.window import BigMessageWindow, KeyboardWindow, NoHomeProgressWindow

log = GET_LOGGER(__name__)


class Rec2VidThread(HookThread):
	def __init__(self, new_name, context, delete):
		HookThread.__init__(self)
		self.context = context
		self.new_name = new_name
		self.delete = delete
		self.chunk_list = glob.glob(os.path.join(('/wymedia/timeshift/records/%s' % self.context['selected'].wymedia_resource['oid']), '*.ts'))
		self.total_chunks = len(self.chunk_list) 
		self.elapsed_chunk = 0
		self.actual_chunk_name = ''
		self.dest_file = None
		self.canceled = False

	def _async_func_process(self):
		if not os.path.exists('/wymedia/My Videos/TS'):
			os.mkdir('/wymedia/My Videos/TS')
#		print ('opening file %s' % ('/wymedia/My Videos/TS/%s.ts' % self.new_name))
		self.dest_file = open(('/wymedia/My Videos/TS/%s.ts' % self.new_name), 'wb') 
		for chunk in self.chunk_list:
			self.actual_chunk_name = os.path.basename(chunk)
#			print ('copying %s' % chunk)
			shutil.copyfileobj(open(chunk, 'rb'), self.dest_file)
			self.elapsed_chunk += 1
			if self.canceled == True:
				break
		self.dest_file.close()
		if self.canceled == True:
			os.remove(('/wymedia/My Videos/TS/%s.ts' % self.new_name))
		else:
			if self.delete == True:
				self.context['selected'].vfs_delete()
				os.system('/wymedia/usr/bin/sqlite3 /etc/params/wymedia/.wyplay_db.-1.db \"UPDATE object SET state=\'tocheck\' WHERE (class=\'object.container\' OR  class=\'object.container.storageSystem\') AND state=\'ok\'\"')
		self.completed.set()
#		print ('closing file %s' % ('/wymedia/My Videos/TS/%s.ts' % self.new_name))

	def func_process(self):
#		print 'func_process'
		self.started.set()
		self._async_func_process()

	def get_infos(self):
#		print 'get_infos'
		self.started.wait()
		if self.elapsed_chunk == self.total_chunks:
			self.completed.set()
			return None
#		print 'get_infos done'
		return dict(percent=self.elapsed_chunk * 100.0 / self.total_chunks, description=(_('Copying %s') % self.actual_chunk_name), total=('%d / %d' % (self.elapsed_chunk, self.total_chunks)), elapsed='', remaining='')

	def on_cancel(self):
		self.canceled = True
		HookThread.on_cancel()


def slugify(value): 
    import unicodedata 
    value = unicodedata.normalize('NFKD', value).encode('ascii', 'ignore') 
    value = unicode(re.sub('[^\w\s-]', '', value).strip()) 
    return re.sub('[-\s]+', '_', value) 


def execute_copy_to_myvideos(context, delete=False):
	text = None

	def _do_copy(kbd):
		new_name = kbd._text
		if new_name:
			if os.path.exists(('/wymedia/My Videos/TS/%s.ts' % new_name)):
				BigMessageWindow(text=(_('File %s already exist.\nAborting...') % new_name), title=_('Error')).show(timeout=5)
				kbd.hide()
				return
			try:
				GraphicalProgress(job=Rec2VidThread(new_name, context, delete), title=(_('Copying \"%s\" to \"%s\"...') % (ellipsize(context['selected'].name, 10), _('My Videos'))) , context=context['parent'], win=NoHomeProgressWindow()).start(delay=1)
			except:
				BigMessageWindow(text=_('Error while copying files'), title=_('Error')).show(timeout=5)
		kbd.hide()

	text = slugify(context['selected'].name)
	kbd = KeyboardWindow(_('Enter the new video name'), text=text, confirm_action=_do_copy)
	context['selected'].show_menu()
	kbd.show()
	return


class ActionsGroup(GenericContainer):
	__module__ = __name__

	def __init__(self, module):
		self.name	 = module.name
		self.position = module.position
		self.category = module.category.strip().lower()
		self._module  = module
		self.default  = getattr(module, 'default', False)
		self.unique   = getattr(module, 'unique', False)
		GenericContainer.__init__(self, self.name, type_='action')

	def is_available(self, context):
		return self._module.is_available(context)

	def browse(self, context = None):
		try:
			actions = self._module.get_action_info_list(context)
			if isinstance(context['selected'], TVRecordItem):
				selected_caption = ellipsize(context['selected'].name, 10)
				actions.insert(0, ActionItem((_('Move \"%s\" to \"%s\"') % (selected_caption, _('My Videos'))), type_='action', action=execute_copy_to_myvideos, args=[context, True]))
				actions.insert(0, ActionItem((_('Copy \"%s\" to \"%s\"') % (selected_caption, _('My Videos'))), type_='action', action=execute_copy_to_myvideos, args=[context, False]))
			return actions
		except Exception, e:
			print('Module %s get_action_info_list failed !' % self.name)
			return []

	def __cmp__(self, other):
		return cmp(self.position, other.position)


category_dict = dict()

for action in config.plugins.get('hand_actions'):
	exec 'from . import hand_%s as act_module' % action
	ag = ActionsGroup(act_module)
	if not category_dict.has_key(ag.category):
		category_dict[ag.category] = []
	category_dict[ag.category].append(ag)

for group_list in category_dict.values():
	group_list.sort()

def get_actiongroups_by_category(context, category):
	chosen_group_list = category_dict[category]
	for group in chosen_group_list:
		if group.is_available(context):
			group.sensitive = True
			group.display_type = group._module.image
		else:
			group.sensitive = False
			group.display_type = 'blank'

	return chosen_group_list

__all__ = ['get_actiongroups_by_category']

