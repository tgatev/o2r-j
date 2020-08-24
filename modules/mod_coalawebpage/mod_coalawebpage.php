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

require_once dirname(__FILE__) . '/helper.php';

$app = JFactory::getApplication();
$doc = JFactory::getDocument();

//Keeping the parameters in the component keeps things clean and tidy.
$comParams = JComponentHelper::getParams('com_coalawebsociallinks');

//Check dependencies
$checkOk = CoalawebPageHelper::checkDependencies();
// Use local param or from the component
$debug = null !== $params->get('debug') ? $params->get('debug') : $comParams->get('debug', '0');
if ($checkOk['ok'] === false) {
    if ($debug === '1') {
        JFactory::getApplication()->enqueueMessage($checkOk['msg'], $checkOk['type']);
    }
    return;
}

// Load the language files
$jlang = JFactory::getLanguage();

// Module
$jlang->load('mod_coalawebpage', JPATH_SITE, 'en-GB', true);
$jlang->load('mod_coalawebpage', JPATH_SITE, $jlang->getDefault(), true);
$jlang->load('mod_coalawebpage', JPATH_SITE, null, true);

// Component
$jlang->load('com_coalawebsociallinks', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_coalawebsociallinks', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_coalawebsociallinks', JPATH_ADMINISTRATOR, null, true);

// Detect language
$fbLocale = $lang->getTag();
$fbLocale = str_replace('-', '_', $fbLocale);

// Facebook only seems to support es_ES and es_LA for all of LATAM
$fbLocale = (substr($fbLocale, 0, 3) == 'es_' && $fbLocale != 'es_ES') ? 'es_LA' : $fbLocale;

//Page parameters
$pageParams = [
    'fbPageLink' => $params->get('fb_page_link'),
    'fbWidth' => $params->get('fb_width', '300'),
    'fbHeight' => $params->get('fb_height', '400'),
    'fbAlign' => $params->get('fb_align', 'center'),
    'fbFacepile' => $params->get('fb_facepile') ? 'true' : 'false',
    'fbSmallHeader' => $params->get('fb_small_header') ? 'true' : 'false',
    'fbCover' => $params->get('fb_cover') ? 'false' : 'true',
    'fbTabsList' => is_array($params->get('fb_tabs')) ? implode(', ', $params->get('fb_tabs')) : $params->get('fb_tabs')
];

/* Module Settings */
$fbAlign = $params->get('fb_align', 'center');
$moduleAlign = 'text-align: ' . $pageParams['fbAlign'] . ';';
$setHeight = $params->get('module_height', 0);
$moduleHeight = $setHeight ? 'height: ' . $pageParams['fbHeight'] . 'px;': '';
$moduleClassSfx = htmlspecialchars($params->get('moduleclass_sfx'));
$module_unique_id = 'cw-page-' . $module->id;

/* Load css */
$urlModMedia = JURI::base(true) . '/media/coalawebsociallinks/modules/page/css/';
if ($params->get('load_layout_css', '1')) {
    $doc->addStyleSheet($urlModMedia . 'cwp-default.css');
}

$helpFunc = new CwGearsHelperLoadcount();
$url = JURI::getInstance()->toString();
$helpFunc::setFacebookJSCount($url);


require JModuleHelper::getLayoutPath('mod_coalawebpage', $params->get('layout', 'default'));