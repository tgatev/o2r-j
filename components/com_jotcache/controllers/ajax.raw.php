<?php
/*
 * @version 6.2.1
 * @package JotCache
 * @category Joomla 3.8
 * @copyright (C) 2010-2018 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;
class JotcacheControllerAjax extends JControllerLegacy {
public function __construct($config = array()) {
parent::__construct($config);
}public function status() {
$flag = $this->input->getWord('flag', '');
if ($flag == 'stop') {
$this->controlRecache(0);
} else {
$plugin = strtolower($this->input->getWord('plugin'));
include JPATH_PLUGINS . '/jotcacheplugins/' . $plugin . '/' . $plugin . '_status.php';
}}protected function controlRecache($flag) {
$cacheDir = JPATH_ROOT . '/cache/page';
if (!file_exists($cacheDir)) {
mkdir($cacheDir, 0755);
}$flagPath = $cacheDir . '/jotcache_recache_flag_tmp.php';
if ($flag) {
file_put_contents($flagPath, "defined('_JEXEC') or die;", LOCK_EX);
} else {
if (file_exists($flagPath)) {
unlink($flagPath);
$db = JFactory::getDbo();
$sql = $db->getQuery(true);
$sql->update($db->quoteName('#__jotcache'))
->set($db->quoteName('recache') . ' = ' . $db->quote(0));
$db->setQuery($sql)->execute();
}}}}