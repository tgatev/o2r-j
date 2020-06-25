<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Social Links
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Social Links is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.plugin.helper');

JHtml::_('behavior.tabstate');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_coalawebsociallinks')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

$jlang = JFactory::getLanguage();

$jlang->load('com_coalawebsociallinks', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_coalawebsociallinks', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_coalawebsociallinks', JPATH_ADMINISTRATOR, null, true);

// Load version.php
$version_php = JPATH_COMPONENT_ADMINISTRATOR . '/version.php';
if (!defined('COM_CWSOCIALLINKS_VERSION') && JFile::exists($version_php)) {
    include_once $version_php;
}

// Required autoloader for the upcoming namespaces.
if (!is_file(JPATH_PLUGINS . '/system/cwgears/libraries/CoalaWeb/vendor/autoload.php')) {
    JFactory::getApplication()->enqueueMessage(JText::_('COM_CWSOCIALLINKS_FILE_MISSING_MESSAGE'), 'error');
    return;
}
require_once JPATH_PLUGINS . '/system/cwgears/libraries/CoalaWeb/vendor/autoload.php';

//Our helpers
JLoader::register('CoalawebsociallinksHelper', dirname(__FILE__) . '/helpers/coalawebsociallinks.php');

//Lets check if our classes exist and if not display a nice graceful message
if (!class_exists('CoalawebsociallinksHelper')) {
    JFactory::getApplication()->enqueueMessage(JText::_('COM_CWSOCIALLINKS_FILE_MISSING_MESSAGE'), 'error');
    return;
}

// Check dependencies
$checkOk = CoalawebsociallinksHelper::checkDependencies();
if ($checkOk['ok'] === false) {
    JFactory::getApplication()->enqueueMessage($checkOk['msg'], $checkOk['type']);
    return;
}

// Load SweetAlert for modal messages
$doc = JFactory::getDocument();
$doc->addScript('https://unpkg.com/sweetalert/dist/sweetalert.min.js');

$controller = JControllerLegacy::getInstance('Coalawebsociallinks');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
?>

<div class="cw-powerby-back">
    <span class="cw-powerby-back">
        <span class="icon-cogs"></span><?php echo JTEXT::_('COM_CWSOCIALLINKS_POWEREDBY_MSG'); ?>
        <a href="https://www.coalaweb.com" target="_blank" title="CoalaWeb">CoalaWeb</a> -
        <?php echo JTEXT::_('COM_CWSOCIALLINKS_FIELD_RELEASE_VERSION_LABEL');
        echo COM_CWSOCIALLINKS_PRO == 1 ? ' ' . COM_CWSOCIALLINKS_VERSION . ' ' . JTEXT::_('COM_CWSOCIALLINKS_RELEASE_TYPE_PRO') : ' ' . COM_CWSOCIALLINKS_VERSION . ' ' . JTEXT::_('COM_CWSOCIALLINKS_RELEASE_TYPE_CORE') ?>
    </span>
</div>
