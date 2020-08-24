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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

// Load version.php
$version_php = JPATH_ADMINISTRATOR . '/components/com_coalawebsociallinks/version.php';
if (!defined('COM_CWSOCIALLINKS_VERSION') && JFile::exists($version_php)) {
    include_once $version_php;
}

/**
 *  Component Helper
 */
class CoalawebsociallinksHelper {

    /**
     * Configure the Linkbar.
     *
     * @param string $vName The name of the active view.
     *
     * @return void
     */
    public static function addSubmenu($vName = 'controlpanel')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_CWSOCIALLINKS_TITLE_CPANEL'), 'index.php?option=com_coalawebsociallinks&view=controlpanel', $vName == 'controlpanel');
        if (COM_CWSOCIALLINKS_PRO == 1) {
            JHtmlSidebar::addEntry(
                JText::_('COM_CWSOCIALLINKS_TITLE_COUNTS'), 'index.php?option=com_coalawebsociallinks&view=counts', $vName == 'counts');
        }
    }
    
    /**
     * Check page social counts based on URL
     * @param $url
     * @return mixed
     */
    public static function getPageCount($url) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('facebook_total', 'linkedin', 'pinterest', 'reddit')));
        $query->from($db->quoteName('#__cwsocial_count'));
        $query->where('url = ' . $db->quote($url));
        $db->setQuery($query);
        $current = $db->loadAssoc();

        return $current;
    }

    /**
     * Check page Open Graph based on URL
     * @param $url
     * @return mixed
     */
    public static function getOpenGraph($url) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('title_og', 'type_og','image_og','image_twitter','url_og','site_name_og','description_og','video_og')));
        $query->from($db->quoteName('#__cwsocial_metacheck'));
        $query->where('url = ' . $db->quote($url));
        $db->setQuery($query);
        $current = $db->loadAssoc();

        return $current;
    }

    /**
     * Updating old theme choices to current
     *
     * @param $currentTheme
     * @return array
     */
    public static function checkTheme($currentTheme)
    {
        $changed = false;
        $theme = $effect = null;

        $media = JPATH_SITE . '/media/coalawebsociallinks/components/sociallinks/themes-icon/';

        //List of obsolete themes
        $obsolete = array(
            'cws-circle-fadein',
            'cws-circle-fadeout',
            'cws-square-fadein',
            'cws-square-fadeout',
            'cws-square-rc-fadein',
            'cws-square-rc-fadeout',
            'wpzoom-fadein',
            'wpzoom-fadeout'
        );

        if (in_array($currentTheme, $obsolete)) {
            switch ($currentTheme) {
                case 'cws-circle-fadein':
                    $theme = 'cws-circle';
                    $effect = 'fadein';
                    $changed = true;
                    break;
                case 'cws-circle-fadeout':
                    $theme = 'cws-circle';
                    $effect = 'fadeout';
                    $changed = true;
                    break;
                case 'cws-square-fadein':
                    $theme = 'cws-square';
                    $effect = 'fadein';
                    $changed = true;
                    break;
                case 'cws-square-fadeout':
                    $theme = 'cws-square';
                    $effect = 'fadeout';
                    $changed = true;
                    break;
                case 'cws-square-rc-fadein':
                    $theme = 'cws-square-rc';
                    $effect = 'fadein';
                    $changed = true;
                    break;
                case 'cws-square-rc-fadeout':
                    $theme = 'cws-square-rc';
                    $effect = 'fadeout';
                    $changed = true;
                    break;
                case 'wpzoom-fadein':
                    $exists = JFile::exists($media . 'wpzoom-fadein/cwsl_style.css');
                    if(!$exists) {
                        $theme = 'cws-square-rc';
                        $effect = 'fadeout';
                        $changed = true;
                    }
                    break;
                case 'wpzoom-fadeout':
                    $exists = JFile::exists($media . 'wpzoom-fadeout/cwsl_style.css');
                    if(!$exists) {
                        $theme = 'cws-square-rc';
                        $effect = 'fadeout';
                        $changed = true;
                    }
                    break;
            }
        }

        $newTheme = [
            'changed' => $changed,
            'theme' => $theme,
            'effect' => $effect
        ];

        return $newTheme;
    }

    public static function checkOg($headData, $url) {


        // Get tools from gears
        $help = new CwGearsHelperTools();

        // Get our date array
        $ourDates = $help::getDatetimeNow();

        /**
         * Do we have to check
         */
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select($db->qn('lastcheck'))
            ->from($db->qn('#__cwsocial_metacheck'))
            ->where($db->qn('url') . ' = ' . $db->q($url));

        $lastCheck = $db->setQuery($query)->loadResult();

        if (intval($lastCheck)) {
            $lastCheck = new \DateTime($lastCheck);
        } else {
            $lastCheck = null;
        }

        // Let make sure we only check every three hours
        $now = $ourDates['long'];
        $past = new \DateTime($now);

        // Take three hours from now
        $past->sub(new \DateInterval('PT3H'));

        if ($lastCheck != null && $lastCheck > $past) {
            return;
        }

        // Update last run status, if I have the time of the last run, I can update, otherwise insert
        if ($lastCheck) {
            $query = $db->getQuery(true)
                ->update($db->qn('#__cwsocial_metacheck'))
                ->set($db->qn('lastcheck') . ' = ' . $db->q($ourDates['long']))
                ->where($db->qn('url') . ' = ' . $db->q($url));
        } else {
            $query = $db->getQuery(true)
                ->insert($db->qn('#__cwsocial_metacheck'))
                ->columns(array($db->qn('url'), $db->qn('lastcheck')))
                ->values($db->q($url) . ', ' . $db->q($ourDates['long']));
        }

        try {
            $result = $db->setQuery($query)->execute();
        } catch (Exception $exc) {
            $result = false;
        }

        if (!$result) {
            return;
        }

        // Does this page already have Open Graph tags?
        // If so lets store this info for the other parts of the extension to use

        $ogTitle = isset($headData['metaTags']['name']['og:title']) ? 1 : 0;
        $ogType = isset($headData['metaTags']['name']['og:type']) ? 1 : 0;
        $ogImage = isset($headData['metaTags']['name']['og:image']) || isset($headData['metaTags']['name']['og:image:url']) ? 1 : 0;
        $ogUrl = isset($headData['metaTags']['name']['og:url']) ? 1 : 0;
        $ogSiteName = isset($headData['metaTags']['name']['og:site_name']) ? 1 : 0;
        $ogDescription = isset($headData['metaTags']['name']['og:description']) ? 1 : 0;
        $ogVideo = isset($headData['metaTags']['name']['og:video']) ? 1 : 0;

        $query = $db->getQuery(true);

        // Fields to update.
        $fields = array(
            $db->qn('title_og') . ' = ' . $ogTitle,
            $db->qn('type_og') . ' = ' . $ogType,
            $db->qn('image_og') . ' = ' . $ogImage,
            $db->qn('url_og') . ' = ' . $ogUrl,
            $db->qn('site_name_og') . ' = ' . $ogSiteName,
            $db->qn('description_og') . ' = ' . $ogDescription,
            $db->qn('video_og') . ' = ' . $ogVideo
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->qn('url') . ' = ' . $db->q($url)
        );

        $query->update($db->quoteName('#__cwsocial_metacheck'))->set($fields)->where($conditions);


        $db->setQuery($query);

        try {
            $db->execute();
        } catch (Exception $e) {

        }
    }

    /**
     * @param $id extension ID
     * @param $type extension type
     * @param $keycheck array key we are looking for
     * @param $needle array of values we are looking for
     * @return bool
     */
    public static function removeParams($id, $type, $keycheck, $needle)
    {
        $db = JFactory::getDbo();
        $json_original = null;

        //Lets get some json based on type
        switch ($type) {
            case 'module':
                $query = $db->getQuery(true);
                $query->select('m.*');
                $query->from('#__modules AS m');
                $query->where('id = ' . $id);

                try {
                    $db->setQuery($query);
                    $module = $db->loadObject();
                } catch (JDatabaseExceptionExecuting $e) {
                    return false;
                }
                $json_original = $module->params;
                break;
        }

        //Do we have anything to check against?
        if(!isset($json_original)){
            return false;
        }

        //Update values base on key and needle
        $json_decoded = json_decode($json_original, true);

        //Have we all ready done this?
        if (array_key_exists('valuecheck', $json_decoded) && $json_decoded['valuecheck'] === '1') {
            //we dont need to do this more than once
            return true;
        }


        foreach ($json_decoded as $key => $value) {
            if ($key === $keycheck) {
                $new_values = array_diff(str_getcsv($value), $needle);
                $json_decoded[$keycheck] = join(',', $new_values);
            }
        }
        //We only want to do this once so lets add a hidden value to the extension params
        $json_decoded['valuecheck'] = '1';

        //Lets encode our data again
        $json_updated = json_encode($json_decoded);

        //Lets write back some json
        switch ($type) {
            case 'module':
                $query = $db->getQuery(true);
                $query->update($db->qn('#__modules'));
                $query->set($db->qn('params') . ' = ' . $db->q($json_updated));

                $query->where('id = ' . $id);

                try {
                    $db->setQuery($query);
                    $db->execute();
                } catch (JDatabaseExceptionExecuting $e) {
                    return false;
                }
                break;
        }

        return true;

    }

    /**
     * Check dependencies
     *
     * @return array
     */
    public static function checkDependencies() {

        $langRoot = 'COM_CWSOCIALLINKS';

        /**
         * Gears dependencies
         */
        $version = (COM_CWSOCIALLINKS_MIN_GEARS_VERSION); // Minimum version

        // Classes that are needed
        $assets = [
            'mobile' => false,
            'count' => true,
            'tools' => true,
            'latest' => true
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
            ),
            'modules' => array(
            ),
            'plugins' => array(
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
                        'type' => 'notice',
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
                        'type' => 'notice',
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
                        'type' => 'notice',
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
                        'type' => 'notice',
                        'msg' => JText::_($langRoot . '_NOGEARSPLUGIN_HELPER_MESSAGE')
                    ];
                    return $result;
                }
            }
        } else {
            // Looks like Gears isn't meeting the requirements
            $result = [
                'ok' => false,
                'type' => 'notice',
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