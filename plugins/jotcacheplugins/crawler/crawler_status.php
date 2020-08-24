<?php
/*
 * @version 6.2.1
 * @package JotCachePlugins
 * @category Joomla 3.8
 * @copyright (C) 2010-2018 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
$lang = JFactory::getLanguage();
$lang->load('plg_jotcacheplugins_crawler', JPATH_ADMINISTRATOR, null, false, true);
$database = JFactory::getDBO();
$sql = $database->getQuery(true);
$sql->select('COUNT(*)')
->from('#__jotcache')
->where($database->quoteName('agent') . ' = ' . $database->quote(1));
$database->setQuery($sql);
$total = $database->loadResult();
echo sprintf(JText::_('PLG_JCPLUGINS_CRAWLER_STATUS'), $total);
