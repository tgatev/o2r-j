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

$doc = JFactory::getDocument();
JHtml::_('jquery.framework');

// Lets get our helper functions
$help= new ModCoalawebSocialLinksHelper();

// Keeping the parameters in the component keeps things clean and tidy.
$comParams = JComponentHelper::getParams('com_coalawebsociallinks');

// Check dependencies
$checkOk = $help::checkDependencies();
// Use local param or from the component
$debug = null !== $params->get('debug') ? $params->get('debug') : $comParams->get('debug', '0');
if ($checkOk['ok'] === false) {
    if ($debug === '1') {
        JFactory::getApplication()->enqueueMessage($checkOk['msg'], $checkOk['type']);
    }
    return;
}

// Load component helper
$comPath = '/components/com_coalawebsociallinks/helpers/';
JLoader::register('CoalawebsociallinksHelper', JPATH_ADMINISTRATOR . $comPath . 'coalawebsociallinks.php');

// Are we viewing on a mobile device?
if ($checkOk['ok']) {
    $detect = new Cwmobiledetect();
    $mobile = $detect->isMobile();
    $tablet = $detect->isTablet();
} else {
    $mobile = false;
    $tablet = false;
}

// Load the language files
$jlang = JFactory::getLanguage();

// Module
$jlang->load('mod_coalawebsociallinks', JPATH_SITE, 'en-GB', true);
$jlang->load('mod_coalawebsociallinks', JPATH_SITE, $jlang->getDefault(), true);
$jlang->load('mod_coalawebsociallinks', JPATH_SITE, null, true);

// Component
$jlang->load('com_coalawebsociallinks', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_coalawebsociallinks', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_coalawebsociallinks', JPATH_ADMINISTRATOR, null, true);

$moduleClassSfx = htmlspecialchars($params->get('moduleclass_sfx'));

/* Link details */
$linkDefault = $params->get('link_default');
if ($linkDefault) {
    $link = $linkDefault;
} else {
    $link = JURI::getInstance()->toString();
}
$linklong = $link;
$link = rawurlencode($link);

// Let's shorten that URL!
if($comParams->get('short_url_service', '') && $comParams->get('shorten_social_mod', 0)) {
    $link = $help->getShortUrl($link, $params);
}

/* Title details */
$titleDefault = $params->get('title_default');
if ($titleDefault) {
    $title = $titleDefault;
} else {
    $title = $doc->getTitle();
}
$title= rawurlencode($title);

//Lets get a description
if ($params->get('desc_default')) {
    $desc = $params->get('desc_default');
} else {
    $desc = $doc->getDescription();
}
$desc = rawurlencode($desc);

//Site name
$config = JFactory::getConfig();
$siteName= $config->get('sitename');

$linknofollow = ($params->get('link_nofollow') ? ' rel="' . $params->get('link_nofollow'). '"' : '');
$linkText = $params->get('link_text_on', 0);

//Do we want a popup window
$popupChoice = $params->get('popup', 0);
$popupChoiceFollow = $params->get('popup_follow', 0);
$popup = ($popupChoice ? 'cwshare ' : '');
$popupFollow = ($popupChoiceFollow ? 'cwshare ' : '');
if ($popupChoice || $popupChoiceFollow){
    $doc->addScript(JURI::base(true) . '/media/coalaweb/modules/generic/js/popup.js');
}

//Facebook Application ID
$shareType = $comParams->get('fb_share_type', 'new');
$localId = $params->get('app_id');
$appId = $localId ? $localId : $comParams->get('fb_app_id');

//Icon settings
$icon_align = $params->get('icon_align');

//Theme
$themes_icon = $params->get('themes_icon');
$iconEffect = $params->get('icon_effect', 'fadein');
$size = $params->get('icon_size');

$themeUpdate = CoalawebsociallinksHelper::checkTheme($themes_icon);
if ($themeUpdate['changed'] === true){
    $themes_icon = $themeUpdate['theme'];
    $iconEffect = $themeUpdate['effect'];
}

//Module Settings
$module_unique_id = 'cw-sl-' . $module->id;
$module_width = $params->get('module_width');

//Sections settings
$display_bm_sec = $params->get('display_bm_sec');
$display_f_sec = $params->get('display_f_sec');

//Border settings
$display_borders = $params->get('display_borders');
$border_width = $params->get('border_width');
$border_color_bm = $params->get('border_color_bm');
$border_color_f = $params->get('border_color_f');

//Layouts
$layout = $params->get('layout');

//Titles
$title_align = $params->get('title_align');
$title_format = $params->get('title_format');
$title_bm = $params->get('title_bm', JTEXT::_('MOD_COALAWEBSOCIALLINKS_TITLE_BOOKMARK'));
$display_title_bm = $params->get('display_title_bm');
$title_color_bm = $params->get('title_color_bm');
$title_f = $params->get('title_f', JTEXT::_('MOD_COALAWEBSOCIALLINKS_TITLE_FOLLOW'));
$display_title_f = $params->get('display_title_f');
$title_color_f = $params->get('title_color_f');

//Text
$display_text_bm = $params->get('display_text_bm');
$text_bm = $params->get('text_bm');
$display_text_f = $params->get('display_text_f');
$text_f = $params->get('text_f');

//Follow Links
$linkfacebook = $params->get('link_facebook');
$linklinkedin = $params->get('link_linkedin');
$linktwitter = $params->get('link_twitter');
$rootRss = $params->get('root_rss', 'http://');
$linkrss = $params->get('link_rss', 'http://');
$linkmyspace = $params->get('link_myspace');
$linkvimeo = $params->get('link_vimeo');
$linkyoutube = $params->get('link_youtube');
$linkdribbble = $params->get('link_dribbble');
$linkdeviantart = $params->get('link_deviantart');
$linkjoomla = $params->get('link_joomla', '');
$linkyelp = $params->get('link_yelp', '');
$linkitunes= $params->get('link_itunes', '');
$linkweibo = $params->get('link_weibo', '');

if ($params->get('link_target_contact', 'self') === 'anchor'){
    $linkcontact = $params->get('link_contact');
    $linkTargetContact = '';
} else {
    $rootContact = $params->get('root_contact', 'http://');
    $linkcontact = $rootContact . $params->get('link_contact');
    $linkTargetContact = 'target="_' . $params->get('link_target_contact', 'self') . '"';
}

$linkebay = $params->get('link_ebay');
$linktuenti = $params->get('link_tuenti');
$linkbehance = $params->get('link_behance');
$linkdesignmoo = $params->get('link_designmoo');
$linkflickr = $params->get('link_flickr');
$linklastfm = $params->get('link_lastfm');
$linkpinterest = $params->get('link_pinterest');
$linktumblr = $params->get('link_tumblr');
$linkinstagram = $params->get('link_instagram');
$linkxing = $params->get('link_xing');
$linkspotify = $params->get('link_spotify');
$linkmail = $params->get('link_mail');
$linkblogger = $params->get('link_blogger');
$linktripadvisor = $params->get('link_tripadvisor');
$linkgithub = $params->get('link_github');
$linkandroid = $params->get('link_android');

//Load css
$load_layout_css = $params->get('load_layout_css');
$urlModMedia = JURI::base(true) . '/media/coalawebsociallinks/modules/sociallinks/';
$urlComMedia = JURI::base(true) . '/media/coalawebsociallinks/components/sociallinks/';

if ($load_layout_css) {
    $doc->addStyleSheet($urlModMedia . 'css/cw-' . $layout . '.css');
}
$doc->addStyleSheet($urlComMedia . 'themes-icon/' . $themes_icon . '/cwsl_style.css');

if ($params->get('display_twitter_bm', 0) && $params->get('include_twitter_js', 1)) {
    $doc->addScript('//platform.twitter.com/widgets.js');
}
            
//Follow Custom Links

//Custom One
if ($params->get('link_target_customone', 'self') === 'anchor') {
    $linkcustomone = $params->get('link_customone');
    $linkTargetCustomone = '';
} else {
    $rootCustomone = $params->get('root_customone', 'http://');
    $linkcustomone = $rootCustomone . $params->get('link_customone');
    $linkTargetCustomone = 'target="_' . $params->get('link_target_customone', 'self') . '"';
}
$textcustomone = $params->get('text_customone');
if ($params->get('icon_customone')) {
    $iconcustomone = JURI::base(true) . '/' . $params->get('icon_customone');
} elseif ($params->get('icon_ext_customone')) {
    $iconcustomone = $params->get('icon_ext_customone');
} else {
    $iconcustomone = '';
}
//Custom Styles
if ($params->get("display_customone_f")) {
    $help->getCustomonestyle($themes_icon, $iconcustomone, $size, $module_unique_id);
}

require JModuleHelper::getLayoutPath('mod_coalawebsociallinks', $params->get('layout', 'default'));

