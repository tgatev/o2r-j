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
class plgJotcachepluginsCrawlerext extends JPlugin {
private $baseUrl;
public $logging;
private $hits;
private $runner;
private $root;
function __construct() {
$config = JFactory::getConfig();
$this->root = $config->get('cache_path', JPATH_ROOT . '/cache') . '/page/';
}function onJotcacheRecache($starturl, $jcplugin, 
$jcparams, $jcstates) {
    if ($jcplugin != 'crawlerext') {
return;
}$this->baseUrl = $starturl;
$params = JComponentHelper::getParams('com_jotcache');
$database = JFactory::getDBO();
/* @var $query JDatabaseQuery */$query = $database->getQuery(true);
$query->update($database->quoteName('#__jotcache'))
->set($database->quoteName('agent') . ' = ' . $database->quote(0));
$database->setQuery($query)->execute();
$this->logging = $params->get('recachelog', 0) == 1 ? true : false;
if ($this->logging) {
JLog::add(sprintf('....running in plugin %s', $jcplugin), JLog::INFO, 'jotcache.recache');
}$noHtmlFilter = JFilterInput::getInstance();
$depth = $noHtmlFilter->clean($jcstates['depth'], 'int');
$depth++;
$activeBrowsers = BrowserAgents::getActiveBrowserAgents();
$this->hits = array();
$ret = '';
foreach ($activeBrowsers as $browser => $def) {
$agent = $def[1] . ' jotcache';
$ret = $this->crawl_page($starturl, $browser, $agent, $depth);
if ($ret == 'STOP') {
break;
}}return array("crawlerext", $ret, $this->hits);
}function crawl_page($url, $browser, $agent, $depth = 5) {
$seen = array();
$this->hits[$browser] = 0;
$hrefs = array(array());
$hrefs[0][0] = $url;
$this->runner = new RecacheRunner();
for ($i = 0; $i < $depth; $i++) {
if ($this->logging && $i > 0) {
JLog::add(sprintf('....for browser %s returned %d links on level %d', $browser, count($hrefs[$i]), $i), JLog::INFO, 'jotcache.recache');
}foreach ($hrefs[$i] as $href) {
$href = htmlspecialchars_decode($href);
$html = $this->runner->getData($href, $agent);
/*        if ($this->logging && empty($html) && ($url == $this->baseUrl)) {
                  JLog::add(sprintf('....inside crawlerext plugin - starting page is empty >> server cannot reach external URL', $html), JLog::INFO, 'jotcache.recache');
                }*/if ($this->logging) {
if ($href == $this->baseUrl) {
if (empty($html)) {
JLog::add(sprintf('....inside crawlerext plugin - starting page is empty >> server cannot reach external URL %s', $href), JLog::INFO, 'jotcache.recache');
} else {
JLog::add(sprintf('....inside crawlerext plugin - starting page URL %s', $href), JLog::INFO, 'jotcache.recache');
file_put_contents(JPATH_ROOT . '/logs/jotcache.crawlerext_initial_page.html', $html);
}}}preg_match_all('#<a.*?href="?([^>" ]*)"?.*?>#', $html, $matches);
foreach ($matches[1] as $link) {
$this->hits[$browser]++;
if (!file_exists($this->root . 'jotcache_recache_flag_tmp.php')) {
return 'STOP';
}if (strpos($link, '#') !== FALSE) {
continue;
}if (0 !== strpos($link, 'http')) {                               if (preg_match('#^(\w*:)#', trim($link))) {
continue;
}$path = '/' . ltrim($link, '/');                   $parts = parse_url($url);
$link = $parts['scheme'] . '://';
$link .= $parts['host'];
if (isset($parts['port'])) {
$link .= ':' . $parts['port'];
}$link .= $path;
}if (stripos($link, $this->baseUrl) !== 0) {
continue;
}$hash = md5(strtolower($link) . $browser);
if (isset($seen[$hash])) {
continue;
}$seen[$hash] = true;
$hrefs[$i + 1][] = $link;
}}}return 'DONE';
}}