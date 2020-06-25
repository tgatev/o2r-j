<?php

/*
 * @version 6.2.1
 * @package JotCache
 * @category Joomla 3.8
 * @copyright (C) 2010-2018 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */class JotcacheRefresh {
private $app;
private $db;
private $deleted;
private $existed;
private $root;
private $storage;
function __construct($db, $storage) {
$this->app = JFactory::getApplication();
$this->db = $db;
$config = JFactory::getConfig();
$this->root = $config->get('cache_path', JPATH_ROOT . '/cache') . '/page';
$this->storage = $storage;
}function refreshMemcache() {
$query = $this->db->getQuery(true);
$query->select('fname')->from('#__jotcache');
$existed = array();
$deleted = array();
try {
$rows = $this->db->setQuery($query)->loadObjectList();
foreach ($rows as $row) {
$hit = $this->storage->get($row->fname);
if ($hit === FALSE) {
$deleted[] = $row->fname;
} else {
$existed[$row->fname] = 1;
}}} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}if (count($deleted) > 0) {
$list = implode("','", $deleted);
$query->clear();
$query->delete()
->from('#__jotcache')
->where("fname IN ('$list')");
try {
$this->db->setQuery($query)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}}if (is_a($this->storage, 'JotcacheMemcached')) {
$keys = $this->storage->getAllKeys();
foreach ($keys as $key) {
if (!array_key_exists($key, $existed)) {
$this->storage->remove($key);
}}} else {
foreach ($deleted as $key) {
$this->storage->remove($key);
}}return;
}function refreshFileCache() {
$query = $this->db->getQuery(true);
if (!file_exists($this->root)) {
$query->delete()->from('#__jotcache');
try {
$this->db->setQuery($query)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}return;
}$query->clear();
$query->select('fname')->from('#__jotcache');
$this->deleted = array();
$this->existed = array();
try {
$rows = $this->db->setQuery($query)->loadObjectList();
      $this->checkFileCache($rows);
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
return;
}    $this->removeFromDb();
    $this->removeFromFs();
}function checkFileCache($rows) {
foreach ($rows as $row) {
$filename = $this->root . '/' . $row->fname . '.php_expire';
if (file_exists($filename)) {
$exp = file_get_contents($filename);
if (time() - $exp > 0) {
$this->deleted[] = $row->fname;
} else {
$this->existed[$row->fname] = 1;
}} else {
$this->deleted[] = $row->fname;
}}}function removeFromDb() {
if (count($this->deleted) > 0) {
$list = implode("','", $this->deleted);
$query = $this->db->getQuery(true);
$query->delete()
->from('#__jotcache')
->where("fname IN ('$list')");
try {
$this->db->setQuery($query)->execute();
} catch (RuntimeException $ex) {
$this->app->enqueueMessage($ex->getMessage(), 'error');
}}}function removeFromFs() {
if ($handle = opendir($this->root)) {
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != "..") {
$ext = strrchr($file, ".");
$fname = substr($file, 0, -strlen($ext));
if (!array_key_exists($fname, $this->existed) && ($ext == ".php" || $ext == ".php_expire")) {
unlink($this->root . '/' . $file);
}}}closedir($handle);
}}}