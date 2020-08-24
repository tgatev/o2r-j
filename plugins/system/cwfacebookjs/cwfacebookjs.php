<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Facebook JS
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Facebook JS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');

// Load version.php
$version_php = JPATH_SITE . '/plugins/system/cwfacebookjs/version.php';
if (!defined('PLG_CWFACEBOOKJS_VERSION') && JFile::exists($version_php)) {
    include_once $version_php;
}

/**
 * Class plgSystemCwfacebookjs
 */
class plgSystemCwfacebookjs extends JPlugin
{

    private $checkOk;
    private $debug;

    /**
     * plgSystemCwfacebookjs constructor.
     * @param $subject
     * @param $config
     */
    public function __construct(&$subject, $config)
    {

        parent::__construct($subject, $config);

        // Load the language files
        $jlang = JFactory::getLanguage();

        // Plugin
        $jlang->load('plg_system_cwfacebookjs', JPATH_ADMINISTRATOR, 'en-GB', true);
        $jlang->load('plg_system_cwfacebookjs', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
        $jlang->load('plg_system_cwfacebookjs', JPATH_ADMINISTRATOR, null, true);

        $this->checkOk = $this->checkDependencies();
        $this->debug = $this->params->get('debug', '0');

    }

    /**
     *
     * @return void
     */
    function onAfterRender()
    {
        $doc = JFactory::getDocument();
        $app = JFactory::getApplication();

        // Lets do a few checks first
        if (
            $app->getName() !== 'site' ||
            $doc->getType() !== 'html'
        ) {

            return;
        }

        // Dependency checks
        if ($this->checkOk['ok'] === false) {
            if ($this->debug === '1') {
                $app->enqueueMessage($this->checkOk['msg'], $this->checkOk['type']);
            }
            return;
        }

        // Social Links
        $socialLoad = '';
        $appIdS = '';
        $social = 'com_coalawebsociallinks';
        $versionS = JPATH_ADMINISTRATOR . '/' . 'components/' . $social . '/version.php';

        if (file_exists($versionS)) {
            $checkS = JComponentHelper::isEnabled($social, true);
            $comParams = JComponentHelper::getParams('com_coalawebsociallinks');
            $appIdS = $comParams->get('fb_app_id');
            if ($checkS) {
                $socialLoad = $comParams->get('load_fb_js', '1');
            }
        }

        // Comments
        $commentLoad = '';
        $fbComments = '';
        $appIdC = '';
        $comments = 'com_coalawebcomments';
        $versionC = JPATH_ADMINISTRATOR . '/' . 'components/' . $comments . '/version.php';

        if (file_exists($versionC)) {
            $checkC = JComponentHelper::isEnabled($comments, true);
            $comParamsTwo = JComponentHelper::getParams('com_coalawebcomments');
            $appIdC = $comParamsTwo->get('fb_app_id');
            if ($checkC) {
                $commentLoad = $comParamsTwo->get('load_fb_js', '1');
            }
        }

        // Lets see if we should load the Facebook JS
        if ($socialLoad === '1' || $commentLoad === '1') {
            $loadJs = '1';
        } else {
            $loadJs = '';
        }

        // Do we want to load Facebook JS?
        if (!$loadJs) {
            return;
        }

        // Lets check for a Facebook App ID
        if ($appIdS) {
            $appId = $comParams->get('fb_app_id');
        } elseif ($appIdC) {
            $appId = $comParamsTwo->get('fb_app_id');
        } else {
            $appId = '';
        }

        // Type of SDK
        $sdkType = $this->params->get('sdk_type', 'all');

        // Load on all pages
        $loadAll = $this->params->get('load_all', '0');

        // Comments
        $url = JURI::getInstance()->toString();
        $mailUrl = JRoute::_('index.php');

        // Helper class to check if we should load Facebook JS on the current page
        $helpFunc = new CwGearsHelperLoadcount();

        // Detect language
        $lang = JFactory::getLanguage();
        $locale = $lang->getTag();
        $locale = str_replace("-", "_", $locale);

        // Facebook and Google only seem to support es_ES and es_LA for all of LATAM
        $locale = (substr($locale, 0, 3) == 'es_' && $locale != 'es_ES') ? 'es_LA' : $locale;

        $body = $app->getBody();

        if (file_exists($versionC)) {
            if ($comParamsTwo->get('send_mail') && $comParamsTwo->get('recipient')) {
                $fbComments = "	
                FB.Event.subscribe('comment.create', function(response) {
                    new Request({
                            method: 'post',
                            url:'" . $mailUrl . "',
                            data: 'cwfbcomments=notify&href=' + response.href
                    }).send();
                });";
            }
        }

        $html = "\n" . '<!-- CoalaWeb Facebook JS -->'
            . "\n" . '<div id="fb-root"></div>
        <script>      
            window.fbAsyncInit = function() {
            FB.init({
              appId      : "' . $appId . '",
              xfbml      : true,
              status     : true,
              cookie     : true,
              autoLogAppEvents : true,
              version    : "v2.10"
            });
            ' . $fbComments . '
          };
          (function(d, s, id){
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement(s); js.id = id;
             js.src = "//connect.facebook.net/' . $locale . '/' . $sdkType . '.js";
             fjs.parentNode.insertBefore(js, fjs);
           }(document, "script", "facebook-jssdk"));
        </script>';

        //Should we load the Facebook code?
        $facebookJs = $helpFunc::getCounts($url, 'facebook_js');


        if ($loadAll || $facebookJs > 0) {
            $load = TRUE;
        } else {
            $load = FALSE;
        }

        if ($load) {
            $matches = preg_split('/(<body.*?>)/i', $body, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            /* assemble the HTML output back with the code in it */
            $injectedHTML = $matches[0] . $matches[1] . $html . $matches[2];

            $app->setBody($injectedHTML);
        }

        return;
    }

    /**
     * Check dependencies
     *
     * @return array
     */
    private function checkDependencies()
    {

        $langRoot = 'PLG_CWFACEBOOKJS';

        /**
         * Gears dependencies
         */
        $version = (PLG_CWFACEBOOKJS_MIN_GEARS_VERSION); // Minimum version

        // Classes that are needed
        $assets = [
            'mobile' => false,
            'count' => true,
            'tools' => true,
            'latest' => false
        ];

        // Check if Gears dependencies are meet and return result
        $results = self::checkGears($version, $assets, $langRoot);

        if ($results['ok'] == false) {
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
            'files' => array(),
            'folders' => array()
        );

        // Check if they are available
        $exists = $tools::checkFilesAndFolders($filesAndFolders, $langRoot);

        // If any of the file/folder dependencies fail return
        if ($exists['ok'] == false) {
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
        if ($extExists['ok'] == false) {
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
    private function checkGears($version, $assets = array(), $langRoot)
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