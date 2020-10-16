<?php
/*
 * @version 6.2.1
 * @package JotCache
 * @category Joomla 3.8
 * @copyright (C) 2010-2018 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.helper');
use Joomla\Registry\Registry;
class RecacheRunner {
protected $dbo;
protected $recacheParams;
protected $logging;
protected $logPath;
public function __construct() {
$config = JFactory::getConfig();
$this->logPath = $config->get('log_path', JPATH_ROOT . '/logs/');
JLog::addLogger(array('text_file' => 'jotcache.recache.log.php', 'text_entry_format' => "{DATE} {TIME}\t{MESSAGE}", 'text_file_path' => $this->logPath), JLog::INFO, 'jotcache.recache');
$this->dbo = JFactory::getDbo();
$this->recacheParams = JComponentHelper::getParams('com_jotcache');
$this->logging = $this->recacheParams->get('recachelog', 0) == 1;
}    public function doExecute($starturl, $jcplugin, $jcparams, $jcstates) {
    if (!defined('JPATH_PLUGINS') || !is_dir(JPATH_PLUGINS)) {
throw new Exception('JPATH_PLUGINS not defined');
}    if ($this->logging) {
JLog::add('Starting recache run', JLog::INFO, 'jotcache.recache');
}$query = $this->dbo->getQuery(true);
$query->select('p.*')
->from('#__extensions AS p')
->where('p.enabled = 1')
->where('p.type = ' . $this->dbo->quote('plugin'))
->where('p.folder = ' . $this->dbo->quote('jotcacheplugins'))
->order('p.ordering');
$this->dbo->setQuery($query);
$plugins = $this->dbo->loadObjectList();
if ($this->logging) {
JLog::add(sprintf('.loaded %d jotcache plugin(s)', count($plugins)), JLog::INFO, 'jotcache.recache');
}$dispatcher = $this->assignDispatcher($plugins);
if (!isset($dispatcher)) {
$msg = "Warning : none of recache plugins enabled. Recache is stopped.\r\n";
JLog::add($msg, JLog::INFO, 'jotcache.recache');
exit($msg);
}    if ($this->logging) {
JLog::add('...triggering `onJotcacheRecache` event', JLog::INFO, 'jotcache.recache');
}foreach ($dispatcher->trigger('onJotcacheRecache', array($starturl, $jcplugin, $jcparams, $jcstates)) as $result) {
if (count($result) == 3 && $this->logging) {
if ($result[2] !== null) {
foreach ($result[2] as $key => $value) {
$browserInfo = ($key === '') ? ' non-splitted browsers' : ' with browser:' . $key;
JLog::add(sprintf('....during recache %s returned %d hits', $browserInfo, $value), JLog::INFO, 'jotcache.recache');
}}JLog::add(sprintf('...%s plugin returned `%s`', $result[0], $result[1]), JLog::INFO, 'jotcache.recache');
}}if ($this->logging) {
JLog::add("Finished recache run\r\n", JLog::INFO, 'jotcache.recache');
}}private function assignDispatcher($plugins) {
$dispatcher = null;
foreach ($plugins as $plugin) {
$className = 'plg' . ucfirst($plugin->folder) . ucfirst($plugin->element);
$element = preg_replace('#[^A-Z0-9-~_]#i', '', $plugin->element);
      if (!class_exists($className)) {
$path = sprintf(rtrim(JPATH_PLUGINS, '\\/') . '/jotcacheplugins/%s/%s.php', $element, $element);
if (is_file($path)) {
include $path;
if (!class_exists($className)) {
if ($this->logging) {
JLog::add(sprintf('..plugin class for `%s` not found in file', $element), JLog::INFO, 'jotcache.recache');
}continue;
}} else {
if ($this->logging) {
JLog::add(sprintf('..plugin file for `%s` not found', $element), JLog::INFO, 'jotcache.recache');
}continue;
}}if ($this->logging) {
JLog::add(sprintf('..registering `%s` plugin', $element), JLog::INFO, 'jotcache.recache');
}      $dispatcher = JEventDispatcher::getInstance();
$dispatcher->register('onJotcacheRecache', new $className(
$dispatcher, array('params' => new Registry($plugin->params))
));}return $dispatcher;
}public function getDbo() {
return $this->dbo;
}public function getData($url, $agent) {
    $ch = curl_init();
if (FALSE === $ch) {
JLog::add(sprintf('..inside recacherunner - CURL init failed'), JLog::INFO, 'jotcache.recache');
return false;
}$timeout = 60;
$seed='site';
$name = \JApplicationHelper::getHash($seed);
$session_name = md5($name);
$state = $session_name.'=ojh3tvo2ikgp012clq021okjd5;';
$headers = [
'Connection: keep-alive',
'Cache-Control: max-age=0'
];$headers[] = 'Cookie: '.$state.' 0f0da984a73234b70b1034c351a8ff6f=9d7gc5cg3vnpjaio457tlcad57; sid=6c54d97f7f14bba66ed33e67128b7455; 1b0f626bf3dcb81260ec77077f94746d=90dfe35ekam7jihs46d3dp3e92';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$data = @curl_exec($ch);
if ($this->logging && curl_errno($ch)) {
JLog::add(sprintf('....for url %s returned error number : %d', $url, curl_error($ch)), JLog::INFO, 'jotcache.recache');
}curl_close($ch);
return $data;
}}