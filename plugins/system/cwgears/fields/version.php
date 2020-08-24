<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Gears
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Gears is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE . '/plugins/system/cwgears/fields/base.php');

$latestVersion = JPATH_SITE . '/plugins/system/cwgears/helpers/latestversion.php';
if (JFile::exists($latestVersion) && !class_exists('CwGearsLatestversion')) {
    JLoader::register('CwGearsLatestversion', $latestVersion);
}

/**
 * Class CWElementVersion
 */
class CWElementVersion extends CWElement {

    /**
     * @param $name
     * @param $value
     * @param $node
     * @param $control_name
     * @return null
     */
    public function fetchElement($name, $value, &$node, $control_name) {
        return NULL;
    }

    /**
     * @param $label
     * @param $description
     * @param $node
     * @param $control_name
     * @param $name
     * @return string
     */
    public function fetchTooltip($label, $description, &$node, $control_name, $name) {

        // Load version.php
        jimport('joomla.filesystem.file');
        $arr = explode("_", $label);

        //initiate variables
        $version_php = '';
        $current = '';
        $version = '';
        $ispro = '';
        $date = '';

        if (array_key_exists(0, $arr)) {
            switch ($arr[0]) {
                case "com":
                    $version_php = JPATH_ADMINISTRATOR . '/' . 'components/' . $label . '/version.php';
                    break;
                case "mod":
                    $version_php = JPATH_SITE . '/' . 'modules/' . $label . '/version.php';
                    break;
                case "plg":
                    if (array_key_exists(1, $arr)) {
                        $version_php = JPATH_SITE . '/' . 'plugins/' . $arr[1] . '/' . $arr[2] . '/version.php';
                    }
                    break;
            }

        }

                
        if (JFile::exists($version_php)) {
            require_once $version_php;
        }

        //Which extension is being displayed?
        switch ($label) {
            case "com_coalawebcontact":
                $version = (COM_CWCONTACT_VERSION);
                $date = (COM_CWCONTACT_DATE);
                $ispro = (COM_CWCONTACT_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-contact-'. $type, $version );
                }
                break;
            case "com_coalawebsociallinks":
                $version = (COM_CWSOCIALLINKS_VERSION);
                $date = (COM_CWSOCIALLINKS_DATE);
                $ispro = (COM_CWSOCIALLINKS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-sociallinks-'. $type, $version );
                }
                break;
            case "com_coalawebcomments":
                $version = (COM_CWCOMMENTS_VERSION);
                $date = (COM_CWCOMMENTS_DATE);
                $ispro = (COM_CWCOMMENTS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-comments-'. $type, $version );
                }
                break;
            case "com_coalawebtraffic":
                $version = (COM_CWTRAFFIC_VERSION);
                $date = (COM_CWTRAFFIC_DATE);
                $ispro = (COM_CWTRAFFIC_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-traffic-'. $type, $version );
                }
                break;
            case "com_coalawebmembers":
                $version = (COM_CWMEMBERS_VERSION);
                $date = (COM_CWMEMBERS_DATE);
                $ispro = (COM_CWMEMBERS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-members-'. $type, $version );
                }
                break;
            case "com_coalawebmarket":
                $version = (COM_CWMARKET_VERSION);
                $date = (COM_CWMARKET_DATE);
                $ispro = (COM_CWMARKET_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-market-'. $type, $version );
                }
                break;
            case "com_coalawebvideo":
                $version = (COM_CWVIDEO_VERSION);
                $date = (COM_CWVIDEO_DATE);
                $ispro = (COM_CWVIDEO_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-video-'. $type, $version );
                }
                break;
            case "mod_coalawebpanel":
                $version = (MOD_CWPANEL_VERSION);
                $date = (MOD_CWPANEL_DATE);
                $ispro = (MOD_CWPANEL_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-panel-'. $type, $version );
                }
                break;
            case "mod_coalawebnews":
                $version = (MOD_CWNEWS_VERSION);
                $date = (MOD_CWNEWS_DATE);
                $ispro = (MOD_CWNEWS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-news-'. $type, $version );
                }
                break;
            case "mod_coalawebflair":
                $version = (MOD_CWFLAIR_VERSION);
                $date = (MOD_CWFLAIR_DATE);
                $ispro = (MOD_CWFLAIR_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-flair-'. $type, $version );
                }
                break;
            case "mod_coalawebhours":
                $version = (MOD_CWHOURS_VERSION);
                $date = (MOD_CWHOURS_DATE);
                $ispro = (MOD_CWHOURS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-hours-'. $type, $version );
                }
                break;
            case "plg_system_cwgears":
                $version = (PLG_CWGEARS_VERSION);
                $date = (PLG_CWGEARS_DATE);
                $ispro = (PLG_CWGEARS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-gears-'. $type, $version );
                }
                break;
            case "plg_system_cwfacebookjs":
                $version = (PLG_CWFACEBOOKJS_VERSION);
                $date = (PLG_CWFACEBOOKJS_DATE);
                $ispro = (PLG_CWFACEBOOKJS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-facebookjs-'. $type, $version );
                }
                break;
            case "plg_content_coalawebgithub":
                $version = (PLG_CWGITHUB_VERSION);
                $date = (PLG_CWGITHUB_DATE);
                $ispro = (PLG_CWGITHUB_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-github-'. $type, $version );
                }
                break;
            case "plg_content_coalawebversions":
                $version = (PLG_CWVERSIONS_VERSION);
                $date = (PLG_CWVERSIONS_DATE);
                $ispro = (PLG_CWVERSIONS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-versions-'. $type, $version );
                }
                break;
            case "plg_content_cwmarkdown":
                $version = (PLG_CWMARKDOWN_VERSION);
                $date = (PLG_CWMARKDOWN_DATE);
                $ispro = (PLG_CWMARKDOWN_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-markdown-'. $type, $version );
                }
                break;
            case "plg_system_cwprint":
                $version = (PLG_CWPRINT_VERSION);
                $date = (PLG_CWPRINT_DATE);
                $ispro = (PLG_CWPRINT_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-print-'. $type, $version );
                }
                break;
            case "plg_user_cwusers":
                $version = (PLG_CWUSERS_VERSION);
                $date = (PLG_CWUSERS_DATE);
                $ispro = (PLG_CWUSERS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-users-'. $type, $version );
                }
                break;
            case "plg_content_coalawebdate":
                $version = (PLG_CWDATE_VERSION);
                $date = (PLG_CWDATE_DATE);
                $ispro = (PLG_CWDATE_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-date-'. $type, $version );
                }
                break;
            case "plg_system_coalaweboffline":
                $version = (PLG_CWOFFLINE_VERSION);
                $date = (PLG_CWOFFLINE_DATE);
                $ispro = (PLG_CWOFFLINE_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-offline-'. $type, $version );
                }
                break;
            case "plg_system_coalawebanalytics":
                $version = (PLG_CWANALYTICS_VERSION);
                $date = (PLG_CWANALYTICS_DATE);
                $ispro = (PLG_CWANALYTICS_PRO);
                $type = $ispro ? 'pro' : 'core';
                if (class_exists('CwGearsLatestversion')) {
                    $current = CwGearsLatestversion::getCurrent('cw-analytics-'. $type, $version );
                }
                break;
        }

        if ($ispro == 1) {
            $ispro = JText::_('PLG_CWGEARS_RELEASE_TYPE_PRO');
        } else {
            $ispro = JText::_('PLG_CWGEARS_RELEASE_TYPE_CORE');
        }
        
        //No current use default
        if (!$current) {
            $current = [
                'remote' => JText::_('PLG_CWGEARS_NO_FILE'),
                'update' => ''
            ];
        }

        return '<div class="cw-message-block well">'
            . '<h3>' . JText::_('PLG_CWGEARS_RELEASE_TITLE') . '</h3>'
            . '<ul class="cw_module">'
            . '<li><strong>' . JText::_('PLG_CWGEARS_FIELD_RELEASE_TYPE_LABEL') . '</strong> <span class="badge badge-info">' . $ispro . '</span></li>'
            . '<li><strong>' . JText::_('PLG_CWGEARS_FIELD_RELEASE_VERSION_LABEL') . ' </strong><span class="badge badge-info">' . $version . '</span></li>'
            . '<li><strong>' . JText::_('PLG_CWGEARS_FIELD_RELEASE_DATE_LABEL') . ' </strong><span class="badge badge-info">' . $date . '</span></li>'
            . '</ul>'
            . '<h3>' . JText::_('PLG_CWGEARS_LATEST_RELEASE_TITLE') . '</h3>'
            . '<ul class="cw_module">'
            . '<li><strong>' . JText::_('PLG_CWGEARS_FIELD_RELEASE_VERSION_LABEL') . ' </strong><span class="badge badge-success">' . $current['remote'] . '</span> ' . $current['update'] . '</li>'
            . '</ul>'
            . '</div>';
    }

}

/**
 * Class JFormFieldVersion
 */
class JFormFieldVersion extends CWElementVersion {

    var $type = 'version';

}
