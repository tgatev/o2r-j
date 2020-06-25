<?php
/*
 * @version 6.2.1
 * @package JotCache
 * @category Joomla 3.8
 * @copyright (C) 2010-2018 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\String\StringHelper, Joomla\Utilities\ArrayHelper;
define('JOTCACHE_STATUS_NORMAL', ' #36C7AA, green');
define('JOTCACHE_STATUS_SPECIAL', ' #CBCB36, yellow');
define('JOTCACHE_STATUS_ATTENTION', ' #FAA528, orange');
define('JOTCACHE_STATUS_WARNING', ' #EE0000, red');
require 'JotcacheRefresh.php';
require 'JotcacheStore.php';
class MainModelMain extends JModelLegacy {
public $exclude = array();
public $fileOrder = null;
public $fileOrderDir = null;
public $filterCom = null;
public $filterMark = null;
public $filterView = null;
public $filterDomain = null;
public $mode = null;
protected $_data = null;
protected $_db;
protected $_item = null;
protected $_pagination = null;
protected $_sql = "";
protected $_total = null;
protected $storage = null;
protected $app = null;
protected $refresh = null;
protected $store = null;
protected $root = null;
function __construct() {
parent::__construct();
$config = JFactory::getConfig();
$this->root = $config->get('cache_path', JPATH_ROOT . '/cache') . '/page/';
$this->app = JFactory::getApplication();
$pars = $this->getPluginParams();
if (!is_object($pars->storage)) {
$pars->storage = new stdClass();
$pars->storage->type = 'file';
}switch ($pars->storage->type) {
case 'memcache':
JLoader::register('JotcacheMemcache', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/memcache.php');
$this->storage = new JotcacheMemcache($pars);
break;
case 'memcached':
JLoader::register('JotcacheMemcached', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/memcached.php');
$this->storage = new JotcacheMemcached($pars);
break;
default:
break;
}$this->refresh = new JotcacheRefresh($this->_db, $this->storage);
$this->store = new JotcacheStore($this->_db, $this->storage);
}function getPluginParams() {
$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('enabled,params')    ->from('#__extensions')
->where($this->_db->quoteName('type') . " = 'plugin'")
->where($this->_db->quoteName('element') . " = 'jotcache'")
->where($this->_db->quoteName('folder') . " = 'system'");
try {
$cols = $this->_db->setQuery($this->_sql)->loadRow();
$pars = json_decode($cols[1]);
$pars->enabled = $cols[0];
return $pars;
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
return null;
}}function getData() {
    $data = new stdClass();
$params = JComponentHelper::getParams("com_jotcache");
$this->mode = (bool)$params->get('mode');
$where = array();
$limit = $this->app->getUserStateFromRequest('global.list.limit', 'limit', $this->app->get('list_limit'), 'int');
$limitstart = $this->app->getUserStateFromRequest('com_jotcache.limitstart', 'limitstart', 0, 'int');
$search = $this->app->getUserStateFromRequest('com_jotcache.search', 'filter_search', '', 'string');
if (version_compare(JVERSION, '3.5', 'ge')) {
$search = StringHelper::strtolower($search);
} else {
$search = JString::strtolower($search);
}$this->filterCom = $this->app->getUserStateFromRequest('com_jotcache.filter_com', 'filter_com', '', 'cmd');
$this->filterView = $this->app->getUserStateFromRequest('com_jotcache.filter_view', 'filter_view', '', 'cmd');
$this->filterDomain = $this->app->getUserStateFromRequest('com_jotcache.filter_domain', 'filter_domain', '', 'string');
$this->filterMark = $this->app->getUserStateFromRequest('com_jotcache.filter_mark', 'filter_mark', '', 'cmd');
$query = $this->_db->getQuery(true);
if ($this->filterCom) {
$query->where("m.com='$this->filterCom'");
}if ($this->filterView) {
$part = $this->_db->quoteName('view');
$query->where("m.$part='$this->filterView'");
}if ($this->filterDomain) {
$query->where("m.domain='$this->filterDomain'");
}if (strlen($this->filterMark) > 0) {
$query->where("m.mark='$this->filterMark'");
}if (!empty($search)) {
      if ($this->mode) {
$where[] = 'LOWER(m.uri) LIKE ' . $this->_db->quote('%' . $this->_db->escape($search, true) . '%', false);
} else {
$query->where('LOWER(m.title) LIKE ' . $this->_db->quote('%' . $this->_db->escape($search, true) . '%', false));
}} else {
$where = '1=1';
}    $query->select('COUNT(*)')
->from('#__jotcache AS m ')
->where($where);
try {
$total = $this->_db->setQuery($query)->loadResult();
jimport('joomla.html.pagination');
$data->pageNav = new JPagination($total, $limitstart, $limit);
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}if ($this->mode) {
$this->fileOrder = $this->app->getUserStateFromRequest('com_jotcache.file_order', 'filter_order', 'm.uri', 'cmd');
} else {
$this->fileOrder = $this->app->getUserStateFromRequest('com_jotcache.file_order', 'filter_order', 'm.title', 'cmd');
}$this->fileOrderDir = $this->app->getUserStateFromRequest('com_jotcache.file_order_Dir', 'filter_order_Dir', 'asc', 'word');
$query->clear('select');
$query->select('m.*');
if ($this->fileOrder != "") {
$query->order("$this->fileOrder $this->fileOrderDir");
}try {
$data->rows = $this->_db->setQuery($query, $data->pageNav->limitstart, $data->pageNav->limit)->loadObjectList();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}$this->checkExpired($data->rows);
$data->mode = $this->mode;
$data->showcookies = (bool)$params->get('showcookies');
$data->showsessionvars = (bool)$params->get('showsessionvars');
$data->showfname = (bool)$params->get('showfname');
$data->fastdelete = (bool)$params->get('fastdelete');
    return $data;
}function checkExpired(&$data) {
if (isset($this->storage)) {
for ($i = 0; $i < count($data); $i++) {
$hit = $this->storage->get($data[$i]->fname);
if ($hit === FALSE) {
$data[$i]->ftime = '#' . $data[$i]->ftime . '#';
}}} else {
      for ($i = 0; $i < count($data); $i++) {
$filename = $this->root . $data[$i]->fname . '.php_expire';
if ($this->_db->name == "sqlsrv") {
$data[$i]->ftime = substr($data[$i]->ftime, 0, 19);
}if (file_exists($filename)) {
$exp = file_get_contents($filename);
if (time() - $exp > 0)
$data[$i]->ftime = '(' . $data[$i]->ftime . ')';
} else {
$data[$i]->ftime = '#' . $data[$i]->ftime . '#';
}}}}function getBcData() {
return $this->getExtraData(2);
}function getExtraData($type) {
$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('*')
->from('#__jotcache_exclude')
->where($this->_db->quoteName('type') . " = '$type'")
->order($this->_db->quoteName('value'));
try {
return $this->_db->setQuery($this->_sql, 0, 20)->loadObjectList();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}}function getEtData() {
return $this->getExtraData(6);
}function getLists() {
$where = array();
$lists = array();
$query = $this->_db->getQuery(true);
$query->select('com as value, com as text')
->from('#__jotcache AS c')
->group('com')
->order('com');
try {
$lists['com'] = JHtml::_('select.options', $this->_db->setQuery($query)->loadObjectList(), 'value', 'text', $this->filterCom);
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}if ($this->filterCom) {
$where[] = "c.com='$this->filterCom'";
}$part = $this->_db->quoteName('view');
$where[] = "c.$part<>''";
$query->clear();
$query->select($this->_db->quoteName('view', 'value'))
->select($this->_db->quoteName('view', 'text'))
->from('#__jotcache AS c')
->where($where)
->group($this->_db->quoteName('view'))
->order($this->_db->quoteName('view'));
try {
$lists['view'] = JHtml::_('select.options', $this->_db->setQuery($query)->loadObjectList(), 'value', 'text', $this->filterView);
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}$query->clear();
$query->select($this->_db->quoteName('domain', 'value'))
->select($this->_db->quoteName('domain', 'text'))
->from('#__jotcache AS c')
->where($where)
->group($this->_db->quoteName('domain'))
->order($this->_db->quoteName('domain'));
try {
$res = $this->_db->setQuery($query)->loadObjectList();
if (empty($res[0]->value)) {
$res[0] = null;
}$lists['domain'] = JHtml::_('select.options', $res, 'value', 'text', $this->filterDomain);
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}$mark[] = JHTML::_('select.option', '0', JText::_('JOTCACHE_RS_SEL_MARK_NO'), 'value', 'text');
$mark[] = JHTML::_('select.option', '1', JText::_('JOTCACHE_RS_SEL_MARK_YES'), 'value', 'text');
$lists['mark'] = JHtml::_('select.options', $mark, 'value', 'text', $this->filterMark);
$query->clear();
$query->select('name')
->from('#__extensions')
->where("type = 'plugin'")
->where("folder = 'system'")
->where("enabled = '1'")
->order('ordering DESC');
try {
$lists['last'] = ($this->_db->setQuery($query, 0, 1)->loadResult() != "JotCache") ? true : false;
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}if ($lists['last']) {
$query->clear('where');
$query->where("type = 'plugin'")
->where("folder = 'system'");
try {
$lists['last'] = ($this->_db->setQuery($query, 0, 1)->loadResult() != "JotCache") ? true : false;
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}}$query->clear();
$query->select('extension_id')
->from('#__extensions')
->where("type = 'plugin'")
->where("folder = 'system'")
->where("name = 'JotCache'");
try {
$lists['plgid'] = $this->_db->setQuery($query)->loadResult();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}$lists['current_Domain'] = $this->filterDomain;
return $lists;
}function getStatus() {
$status = array();
$caching = (int)$this->app->get('caching');
switch ($caching) {
case 0:
$status['gclass'] = JOTCACHE_STATUS_SPECIAL;
$status['gtitle'] = JText::_('JOTCACHE_RS_GLOBAL_SPECIAL');
break;
case 1:
$status['gclass'] = JOTCACHE_STATUS_NORMAL;
$status['gtitle'] = JText::_('JOTCACHE_RS_GLOBAL_NORMAL');
break;
default:
$status['gclass'] = JOTCACHE_STATUS_WARNING;
$status['gtitle'] = JText::_('JOTCACHE_RS_GLOBAL_WARNING');
break;
}    $cnt = 0;
if (file_exists($this->root) && $handle = opendir($this->root)) {
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != "..") {
$ext = strrchr($file, ".");
          if ($ext == ".php_expire") {
$time = @file_get_contents($this->root . $file);
if ($time >= time()) {
$cnt++;
}}}}closedir($handle);
}$status['clear'] = $cnt;
return $status;
}function getTplLists() {
$lists = array();
$selectId = 1;
$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('position')
->from('#__modules')
->where('client_id = 0')
->where('published = 1')
->where('position <>' . $this->_db->quote(''))
->group('position')
->order('position');
$this->_db->setQuery($this->_sql);
try {
$items = $this->_db->loadColumn();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}natcasesort($items);
$lists['pos'] = $items;
$this->_sql->clear();
$this->_sql->select($this->_db->quoteName('value'))
->from('#__jotcache_exclude')
->where($this->_db->quoteName('type') . ' = 4')
->where($this->_db->quoteName('name') . ' = ' . (int)$selectId);
try {
$value = $this->_db->setQuery($this->_sql)->loadResult();
$tplDef = unserialize($value);
$lists['value'] = is_array($tplDef) ? $tplDef : array();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}return $lists;
}function renew($token) {
if (is_a($this->storage, 'JotcacheMemcache') || is_a($this->storage, 'JotcacheMemcached')) {
$this->storage->remove($token);
} else {
      if (file_exists($this->root . $token . '.php')) {
unlink($this->root . $token . '.php');
unlink($this->root . $token . '.php_expire');
}}$this->cleanGlobal();
}function cleanGlobal($all = false) {
$params = JComponentHelper::getParams("com_jotcache");
if ((bool)$params->get('cleanglobal', true)) {
$conf = JFactory::getConfig();
$options = array(
'defaultgroup' => '',
'storage' => $conf->get('cache_handler', ''),
'caching' => true,
'cachebase' => ($this->getState('clientId') == 1) ? JPATH_ADMINISTRATOR . '/cache' : $conf->get('cache_path', JPATH_SITE . '/cache')
);$cacheGlobal = JCache::getInstance('', $options);
$data = $cacheGlobal->getAll();
$blocked = array('page', 'plg_jch_optimize', 'rokbooster', 'plg_scriptmerge');
foreach ($data as $item) {
if ($all || !in_array($item->group, $blocked)) {
          $cacheGlobal->clean($item->group);
}}}}function resetMark() {
$query = $this->_db->getQuery(true);
$query->update($this->_db->quoteName('#__jotcache'))->set('mark=NULL');
try {
$this->_db->setQuery($query)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}}function delete() {
    $cid = $this->app->input->get('cid', array(0), 'array');
$list = implode("','", $cid);
$query = $this->_db->getQuery(true);
$query->delete()
->from('#__jotcache')
->where("fname IN ('$list')");
try {
$this->_db->setQuery($query)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}foreach ($cid as $fname) {
if (is_a($this->storage, 'JotcacheMemcache') || is_a($this->storage, 'JotcacheMemcached')) {
$this->storage->remove($fname);
} else {
if (file_exists($this->root . $fname . '.php')) {
unlink($this->root . $fname . '.php');
unlink($this->root . $fname . '.php_expire');
}}}$this->cleanGlobal();
}function deletedomain() {
$query = $this->_db->getQuery(true);
$filterDomain = $this->app->getUserStateFromRequest('com_jotcache.filter_domain', 'filter_domain', '', 'string');
if (is_a($this->storage, 'JotcacheMemcache')) {
$query->select('fname')->from('#__jotcache')->where('domain=' . $this->_db->quote($filterDomain));
$fnames = $this->_db->setQuery($query)->loadAssocList();
foreach ($fnames as $key => $val) {
$this->storage->remove($val['fname']);
}$query->clear();
}$query->delete()
->from('#__jotcache')->where('domain=' . $this->_db->quote($filterDomain));
try {
$this->_db->setQuery($query)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}$this->app->setUserState('com_jotcache.filter_domain', '');
$this->refresh();
$this->cleanGlobal();
}function refresh() {
$this->app->setUserState('com_jotcache.filter_com', '');
$this->app->setUserState('com_jotcache.filter_view', '');
$this->app->setUserState('com_jotcache.filter_mark', '');
if (is_a($this->storage, 'JotcacheMemcache') || is_a($this->storage, 'JotcacheMemcached')) {
$this->refresh->refreshMemcache();
} else {
$this->refresh->refreshFileCache();
}}function deleteall() {
$query = $this->_db->getQuery(true);
if (is_a($this->storage, 'JotcacheMemcache') || is_a($this->storage, 'JotcacheMemcached')) {
$query->select('fname')->from('#__jotcache');
$fnames = $this->_db->setQuery($query)->loadAssocList();
foreach ($fnames as $key => $val) {
$this->storage->remove($val['fname']);
}$query->clear();
}$query->delete()
->from('#__jotcache');
try {
$this->_db->setQuery($query)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}$this->refresh();
$this->cleanGlobal(true);
}function getExList() {
$data = new stdClass;
$data->exclude = $this->getExclude();
$query = $this->_db->getQuery(true);
$query->select(array("extension_id AS " . $this->_db->quoteName('id'), $this->_db->quoteName('name'), "element AS " . $this->_db->quoteName('option')))
->from('#__extensions')
->where("type='component'")
->where("element<>''")
->order($this->_db->quote('option'));
try {
$data->rows = $this->_db->setQuery($query)->loadObjectList();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}$componentDir = JPATH_SITE . '/components/';
$max = count($data->rows);
for ($i = 0; $i < $max; $i++) {
$row = $data->rows[$i];
if (!file_exists($componentDir . '/' . $row->option) || ($row->option == 'com_jotcache')) {
unset($data->rows[$i]);
}}$data->rows = array_values($data->rows);
return $data;
}function getExclude() {
$query = $this->_db->getQuery(true);
$query->select('id,name,value')
->from('#__jotcache_exclude');
try {
$rows = $this->_db->setQuery($query)->loadObjectList();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}if (!empty($rows)) {
foreach ($rows as $row) {
$this->exclude[$row->name] = $row->value;
}}return $this->exclude;
}function store($post, $cid) {
$query = $this->_db->getQuery(true);
if (count($cid) > 0) {
$idlist = implode(',', $cid);
$query->select($this->_db->quoteName('element', 'option'))
->from('#__extensions')
->where("extension_id IN ($idlist)");
try {
$rows = $this->_db->setQuery($query)->loadObjectList();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
return false;
}$excludeJc = array();
foreach ($rows as $row) {
$views = 'ex_' . $row->option;
$value = array_key_exists($views, $post) ? str_replace(' ', '', $post[$views]) : '';
if ($value == '')
$value = '1';
$excludeJc[$row->option] = $value;
}$excludeDb = $this->store->getExcludePost($post);
$this->store->storeUrlInsDel($excludeJc, $excludeDb);
$this->store->storeUrlUpdate($excludeJc);
}return true;
}function tplstore($post, $cids) {
$tplId = 1;
if (count($cids) > 0 && $tplId > 0) {
$tplexDef = array();
foreach ($cids as $cid) {
$style = array_key_exists('ex1_' . $cid, $post) ? trim($post['ex1_' . $cid]) : '';
$attr = array_key_exists('ex2_' . $cid, $post) ? trim($post['ex2_' . $cid]) : '';
$tplexDef[$cid] = $style . '|' . $attr;
}$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('*')
->from('#__jotcache_exclude')
->where($this->_db->quoteName('type') . ' = 4')
->where($this->_db->quoteName('name') . ' = ' . (int)$tplId);
$tplStored = $this->_getListCount($this->_sql);
$packed = serialize($tplexDef);
$this->store->storeTplInsDel($tplStored, $packed);
}return $tplId;
}function extraStore($post, $type) {
$defs = $this->store->getBrowserPost($post, $type);
$this->store->storeExtraInsDel($defs, $type);
return $this->extraPack($type);
}function extraPack($type) {
$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('*')
->from('#__jotcache_exclude')
->where($this->_db->quoteName('type') . " = '$type'")
->order('value');
try {
$rows = $this->_db->setQuery($this->_sql)->loadObjectList();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
return false;
}$bcDef = array();
foreach ($rows as $row) {
if ($type == 2) {
$bcDef[$row->value] = $row->name;
} else {
parse_str($row->value, $parts);
$bcDef[serialize($parts)] = $row->name;
}}$packed = serialize($bcDef);
$this->_sql->clear();
$packtype = $type + 1;
$this->_sql->select('COUNT(*)')
->from('#__jotcache_exclude')
->where("type='$packtype'");
try {
$bcDefExists = (bool)$this->_db->setQuery($this->_sql)->loadResult();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
return false;
}$this->_sql->clear();
if ($bcDefExists) {
$this->_sql->update($this->_db->quoteName('#__jotcache_exclude'))
->set($this->_db->quoteName('name') . ' = ' . $this->_db->quote('pack'))
->set($this->_db->quoteName('value') . ' = ' . $this->_db->quote($packed))
->where("type='$packtype'");
try {
$this->_db->setQuery($this->_sql)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
return false;
}} else {
$this->_sql->insert('#__jotcache_exclude')
->columns('name,value,type')
->values("'pack', '$packed', '$packtype'");
try {
$this->_db->setQuery($this->_sql)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
return false;
}}return true;
}function extraDelete($type) {
$input = $this->app->input;
$cid = $input->post->get('cid', array(0), 'array');
ArrayHelper::toInteger($cid, array(0));
    $list = implode("','", $cid);
$this->_sql = $this->_db->getQuery(true);
$id = $this->_db->quoteName('id');
$this->_sql->delete('#__jotcache_exclude')
->where("$id IN ('$list')");
try {
$this->_db->setQuery($this->_sql)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
return false;
}return $this->extraPack($type);
}function getCachedContent() {
$pageData = new stdclass;
$input = JFactory::getApplication()->input;
$fname = $input->getCmd('fname');
$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('title')
->from('#__jotcache')
->where($this->_db->quoteName('fname') . ' = ' . $this->_db->quote($fname));
try {
$pageData->title = $this->_db->setQuery($this->_sql)->loadResult();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}if (is_a($this->storage, 'JotcacheMemcache') || is_a($this->storage, 'JotcacheMemcached')) {
$content = $this->storage->get($fname);
$pageData->content = trim(str_replace('<?php die("Access Denied"); ?>', '', $content));
} else {
$fnamePath = $this->root . $fname . '.php';
$content = @file_get_contents($fnamePath);
$pageData->content = trim(str_replace('<?php die("Access Denied"); ?>', '', $content));
}$pageData->error = false;
preg_match_all('#<jot\s([_a-zA-Z0-9-]*)\s[es]\s?((?:\w*="?[_a-zA-Z0-9-\.\s]*"?\s*)*)><\/jot>#', $pageData->content, $matches);
$marks = $matches[0];
$checks = array_unique($matches[1]);
$attrs = $matches[2];
$err = array();
$cnt = 0;
for ($i = 0; $i < count($marks); $i = $i + 2) {
if (!empty($attrs[$i])) {
$attrs[$i] = ' ' . $attrs[$i];
}if ($marks[$i] != "<jot " . @$checks[$i] . " s" . @$attrs[$i] . "></jot>" || @$marks[$i + 1] != "<jot " . @$checks[$i] . " e></jot>") {
$err[] = @$checks[$i];
} else {
$cnt++;
}}if (array_key_exists(0, $err))
$pageData->error = true;
    $pageData->count = $cnt;
return $pageData;
}}