<?php
/*
 * @version 6.2.1
 * @package JotCachePlugins
 * @category Joomla 3.8
 * @copyright (C) 2010-2018 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;
include_once JPATH_ADMINISTRATOR . '/components/com_jotcache/helpers/browseragents.php';
class plgJotcachepluginsRecache extends JPlugin {
private $root;
function __construct() {
$config = JFactory::getConfig();
$this->root = $config->get('cache_path', JPATH_ROOT . '/cache') . '/page/';
}function onJotcacheRecache($starturl, $jcplugin, 
$jcparams, 
$jcstates) {
if ($jcplugin != 'recache') {
return array();
}$params = JComponentHelper::getParams('com_jotcache');
$jotcachePlugin = JPluginHelper::getPlugin('system','jotcache');
$jotcacheParams = json_decode($jotcachePlugin->params);
    $logging = $params->get('recachelog', 0) == 1 ? true : false;
$database = JFactory::getDBO();
$sql = $database->getQuery(true);
$sql->select('fname,uri,browser')
->from('#__jotcache')
->where("recache = 1");
$database->setQuery($sql);
$rows = $database->loadObjectList();
$browsers = BrowserAgents::getBrowserAgents();
$hits = array();
$warns = 0;
$delcount = 5;
$delpages = array();
$runner = new RecacheRunner();
foreach ($rows as $row) {
if (defined('JOTCACHE_RECACHE_BROWSER')) {
if (!file_exists($this->root . 'jotcache_recache_flag_tmp.php')) {
return;
}}$browser = (array_key_exists($row->browser, $browsers)) ? $browsers[$row->browser][1] : BrowserAgents::getDefaultAgent($jotcacheParams->cacheclient);
$agent = $browser . ' jotcache';
$pos = strpos(trim($row->uri), 'http');
      if ($pos === 0) {
$ret = $runner->getData($row->uri, $agent);
} else {
preg_match('#http[s]?://[^/\n]*#', $starturl, $matches);
$root = $matches[0];
$ret = $runner->getData($root . $row->uri, $agent);
}if ($ret === false) {
$warns++;
if ($logging) {
$browser_err = ($row->browser == "") ? '' : '(' . $row->browser . ')';
JLog::add(sprintf('WARN uri%s `%s` was not accessed during recache', $browser_err, $row->uri), JLog::INFO, 'jotcache.recache');
}if ($warns > 9) {
return array("recache", "STOPPED after 10 WARNs", $hits);
}} else {
if (array_key_exists($row->browser, $hits)) {
$hits[$row->browser] += 1;
} else {
$hits[$row->browser] = 1;
}}$delpages[] = $row->fname;
      if ($delcount === 0) {
$this->clearRecacheFlags($delpages);
$delcount = 6;
}$delcount--;
}$this->clearRecacheFlags($delpages);
return array("recache", "DONE", $hits);
}function clearRecacheFlags($delpages) {
if (count($delpages) > 0) {
$database = JFactory::getDBO();
$delstring = implode("','", $delpages);
$delstring = "'" . $delstring . "'";
$query = $database->getQuery(true);
$query->update($database->quoteName('#__jotcache'))
->set('recache=0')
->where($database->quoteName('fname') . ' IN(' . $delstring . ')');
$database->setQuery($query);
$database->execute();
}}}