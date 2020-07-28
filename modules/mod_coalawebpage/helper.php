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

jimport('joomla.filesystem.file');

// Load version.php
$version_php = JPATH_ADMINISTRATOR . '/components/com_coalawebsociallinks/version.php';
if (!defined('COM_CWSOCIALLINKS_VERSION') && JFile::exists($version_php)) {
    include_once $version_php;
}

/**
 * Class CoalawebPageHelper
 */
class CoalawebPageHelper extends JObject
{
    /**
     * @param $pageParams
     * @return string
     */
    public static function getPageHtml5($pageParams)
    {

        $output[] = '<div class="fb-page"'
            . ' data-href="' . $pageParams['fbPageLink'] . '"'
            . ' data-tabs="' . $pageParams['fbTabsList'] . '"'
            . ' data-small-header="' . $pageParams['fbSmallHeader'] . '"'
            . ' data-adapt-container-width="true"'
            . ' data-width="' . $pageParams['fbWidth'] . '"'
            . ' data-height="' . $pageParams['fbHeight'] . '"'
            . ' data-show-facepile="' . $pageParams['fbFacepile'] . '"'
            . ' data-hide-cover="' . $pageParams['fbCover'] . '">'
            . ' </div>';

        return implode("\n", $output);
    }


    /**
     * Check dependencies
     *
     * @return array
     */
    public static function checkDependencies() {

        $langRoot = 'MOD_CWPAGE';

        if(!defined('COM_CWSOCIALLINKS_VERSION')){
            $result = [
                'ok' => false,
                'type' => 'warning',
                'msg' => JText::_($langRoot . '_FILE_MISSING_MESSAGE')
            ];

            return $result;

        }

        /**
         * Gears dependencies
         */
        $version = (COM_CWSOCIALLINKS_MIN_GEARS_VERSION); // Minimum version

        // Classes that are needed
        $assets = [
            'mobile' => false,
            'count' => true,
            'tools' => true,
            'latest' => false
        ];

        // Check if Gears dependencies are meet and return result
        $results = self::checkGears($version, $assets, $langRoot);

        if($results['ok'] == false){
            $result = [
                'ok' => $results['ok'],
                'type' => $results['type'],
                'msg' => $results['msg']
            ];

            return $result;
        }


        // Lets use our tools class from Gears
        $tools = new CwGearsHelperTools();

        /**
         * File and folder dependencies
         * Note: JPATH_ROOT . '/' prefix will be added to file and folder names
         */
        $filesAndFolders = array(
            'files' => array(
            ),
            'folders' => array(
            )
        );

        // Check if they are available
        $exists = $tools::checkFilesAndFolders($filesAndFolders, $langRoot);

        // If any of the file/folder dependencies fail return
        if($exists['ok'] == false){
            $result = [
                'ok' => $exists['ok'],
                'type' => $exists['type'],
                'msg' => $exists['msg']
            ];

            return $result;
        }

        /**
         * Extension Dependencies
         * Note: Plugins always need to be entered in the following format plg_type_name
         */
        $extensions = array(
            'components' => array(
                'com_coalawebsociallinks'
            ),
            'modules' => array(
            ),
            'plugins' => array(
                'plg_system_cwfacebookjs'
            )
        );

        // Check if they are available
        $extExists = $tools::checkExtensions($extensions, $langRoot);

        // If any of the extension dependencies fail return
        if($extExists['ok'] == false){
            $result = [
                'ok' => $extExists['ok'],
                'type' => $extExists['type'],
                'msg' => $extExists['msg']
            ];

            return $result;
        }

        // No problems? return all good
        $result = ['ok' => true];

        return $result;
    }

    /**
     * Check Gears dependencies
     *
     * @param $version - minimum version
     * @param array $assets - list of required assets
     * @param $langRoot
     * @return array
     */
    public static function checkGears($version, $assets = array(), $langRoot)
    {
        jimport('joomla.filesystem.file');

        // Load the version.php file for the CW Gears plugin
        $version_php = JPATH_SITE . '/plugins/system/cwgears/version.php';
        if (!defined('PLG_CWGEARS_VERSION') && JFile::exists($version_php)) {
            include_once $version_php;
        }

        // Is Gears installed and the right version and published?
        if (
            JPluginHelper::isEnabled('system', 'cwgears') &&
            JFile::exists($version_php) &&
            version_compare(PLG_CWGEARS_VERSION, $version, 'ge')
        ) {
            // Base helper directory
            $helperDir = JPATH_SITE . '/plugins/system/cwgears/helpers/';

            // Do we need the mobile detect class?
            if ($assets['mobile'] == true && !class_exists('Cwmobiledetect')) {
                $mobiledetect_php = $helperDir . 'cwmobiledetect.php';
                if (JFile::exists($mobiledetect_php)) {
                    JLoader::register('Cwmobiledetect', $mobiledetect_php);
                } else {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_NOGEARSPLUGIN_HELPER_MESSAGE')
                    ];
                    return $result;
                }
            }

            // Do we need the load count class?
            if ($assets['count'] == true && !class_exists('CwGearsHelperLoadcount')) {
                $loadcount_php = $helperDir . 'loadcount.php';
                if (JFile::exists($loadcount_php)) {
                    JLoader::register('CwGearsHelperLoadcount', $loadcount_php);
                } else {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_NOGEARSPLUGIN_HELPER_MESSAGE')
                    ];
                    return $result;
                }
            }

            // Do we need the tools class?
            if ($assets['tools'] == true && !class_exists('CwGearsHelperTools')) {
                $tools_php = $helperDir . 'tools.php';
                if (JFile::exists($tools_php)) {
                    JLoader::register('CwGearsHelperTools', $tools_php);
                } else {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_NOGEARSPLUGIN_HELPER_MESSAGE')
                    ];
                    return $result;
                }
            }

            // Do we need the latest class?
            if ($assets['latest'] == true && !class_exists('CwGearsLatestversion')) {
                $latest_php = $helperDir . 'latestversion.php';
                if (JFile::exists($latest_php)) {
                    JLoader::register('CwGearsLatestversion', $latest_php);
                } else {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_NOGEARSPLUGIN_HELPER_MESSAGE')
                    ];
                    return $result;
                }
            }
        } else {
            // Looks like Gears isn't meeting the requirements
            $result = [
                'ok' => false,
                'type' => 'warning',
                'msg' => JText::sprintf($langRoot . '_NOGEARSPLUGIN_CHECK_MESSAGE', $version)
            ];
            return $result;
        }

        // Set up our response array
        $result = [
            'ok' => true,
            'type' => '',
            'msg' => ''
        ];

        // Return our result
        return $result;

    }
}