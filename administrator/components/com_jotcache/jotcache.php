<?php
/*
 * @version 6.2.1
 * @package JotCache
 * @category Joomla 3.8
 * @copyright (C) 2010-2018 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
$controller = JControllerLegacy::getInstance('Main');
$task = JFactory::getApplication()->input->get('task');
$controller->execute($task);
$controller->redirect();
