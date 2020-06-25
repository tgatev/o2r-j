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

jimport('joomla.log.log');

/**
 * Class CwGearsHelperTools
 */
class CwGearsHelperTools
{

    /**
     * @param $text
     * @param bool $stripHtml
     * @param $limit
     * @param null $tags
     * @return null|string|string[]|Tidy
     */
    public static function textClean($text, $stripHtml = true, $limit, $tags = null)
    {
        // Now decoded the text
        $decoded = html_entity_decode($text);

        // Remove any HTML based on module settings
        $notags = $stripHtml ? strip_tags($decoded, $tags) : $decoded;

        // Remove bracket or bracket sets such as with plugin code
        $nobrackets1st = preg_replace('/{[^}]+\}(.*?){\/[^}]+\}/s', " ", $notags);
        $nobrackets2nd = preg_replace("/\{[^}]+\}/", " ", $nobrackets1st);

        //Lets make sure there are no page builder [] tags
        $nobrackets3rd = preg_replace('/\[.*\]/', '', $nobrackets2nd);

        //Now reduce the text length if needed
        $chars = strlen($nobrackets3rd);
        if ($chars <= $limit) {
            $description = $nobrackets3rd;
        } else {
            $description = JString::substr($nobrackets3rd, 0, $limit) . "...";
        }

        // One last little clean up
        $cleanText = preg_replace("/\s+/", " ", $description);

        // Lastly repair any HTML that got cut off if Tidy is installed
        if (extension_loaded('tidy') && !$stripHtml) {
            $tidy = new Tidy();
            $config = array(
                'output-xml' => true,
                'input-xml' => true,
                'clean' => false
            );
            $tidy->parseString($cleanText, $config, 'utf8');
            $tidy->cleanRepair();
            $cleanText = $tidy;
        }

        return $cleanText;
    }

    /**
     * Clean and minimize code
     *
     * @param string $code
     * @return string
     */
    public static function codeClean($code)
    {

        // Remove comments.
        $pass1 = preg_replace('~//<!\[CDATA\[\s*|\s*//\]\]>~', '', $code);
        $pass2 = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\)\/\/[^"\'].*))/', '', $pass1);

        // Minimize.
        $pass3 = str_replace(array("\r\n", "\r", "\n", "\t"), '', $pass2);
        $pass4 = preg_replace('/ +/', ' ', $pass3); // Replace multiple spaces with single space.
        $codeClean = trim($pass4);  // Trim the string of leading and trailing space.

        return $codeClean;
    }

    /**
     * Checks if multiple keys exist in an array
     *
     * @param array $array
     * @param array|string $keys
     *
     * @return bool
     */
    public static function keysExist(array $array, $keys)
    {
        $count = 0;
        if (!is_array($keys)) {
            $keys = func_get_args();
            array_shift($keys);
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $count++;
            }
        }

        return count($keys) === $count;
    }

    /**
     * Check if array is empty
     *
     * @param array $arr
     *
     * @return boolean
     */
    function isEmptyArr($arr = array())
    {
        if (!empty($arr)) {
            $count = count($arr);
            $check = 0;
            foreach ($arr as $id => $item) {
                if (empty($item)) {
                    $check++;
                }
            }
            if ($check != $count) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if certain files/folders currently exist
     *
     * @param $filesAndFolders - files and folders lists
     * @param $langRoot
     * @return array
     */
    public static function checkFilesAndFolders($filesAndFolders, $langRoot)
    {

        // Check files exist
        jimport('joomla.filesystem.file');

        // Loop through files
        if (!empty($filesAndFolders['files'])) {
            foreach ($filesAndFolders['files'] as $file) {
                $f = JPATH_ROOT . '/' . $file;
                if (!JFile::exists($f)) {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_FILE_MISSING_MESSAGE')
                    ];
                    return $result;
                } else {
                    continue;
                }
            }
        }
        // Check folders exist
        jimport('joomla.filesystem.folder');

        // Lopp through folders
        if (!empty($filesAndFolders['folders'])) {
            foreach ($filesAndFolders['folders'] as $folder) {
                $f = JPATH_ROOT . '/' . $folder;
                if (!JFolder::exists($f)) {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_FOLDER_MISSING_MESSAGE')
                    ];
                    return $result;
                } else {
                    continue;
                }
            }
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

    /**
     * Check certain extensions are enabled
     *
     * @param $extensions
     * @param $langRoot
     * @return array
     */
    public static function checkExtensions($extensions, $langRoot)
    {
        // Loop through and check components are enabled
        if (!empty($extensions['components'])) {
            foreach ($extensions['components'] as $component) {
                if (!JComponentHelper::isEnabled($component)) {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::sprintf($langRoot . '_NOEXT_CHECK_MESSAGE', $component)
                    ];
                    return $result;
                } else {
                    continue;
                }
            }
        }

        // Loop through and check modules are enabled
        if (!empty($extensions['modules'])) {
            foreach ($extensions['modules'] as $module) {
                if (!JModuleHelper::isEnabled($module)) {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::sprintf($langRoot . '_NOEXT_CHECK_MESSAGE', $module)
                    ];
                    return $result;
                } else {
                    continue;
                }
            }
        }

        // Loop through and check plugins are enabled
        if (!empty($extensions['plugins'])) {
            foreach ($extensions['plugins'] as $plugin) {
                $parts = explode('_', $plugin);
                if (!JPluginHelper::isEnabled($parts[1], $parts[2])) {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::sprintf($langRoot . '_NOEXT_CHECK_MESSAGE', $plugin)
                    ];
                    return $result;
                } else {
                    continue;
                }
            }
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

    /**
     * Format messages
     *
     * @param $type
     * @param $msg
     * @param array $sprint
     * @return string
     */
    public static function getMessage($type, $msg, $sprint = array())
    {
        $returnStatus = null;
        $sprintCheck = array_filter($sprint);

        if (!empty($sprintCheck)) {
            $foo = JText::_($msg);
            $msg = vsprintf($foo, $sprint);
        } else {
            $msg = JText::_($msg);
        }

        switch ($type) {
            case 'info':
                $output[] = '<div class="alert alert-info">';
                $output[] = '<span class="icon-info-circle"></span>';
                $output[] = $msg;
                $output[] = '</div>';

                $returnStatus = implode("\n", $output);
                break;
            case 'danger':
                $output[] = '<div class="alert alert-danger">';
                $output[] = '<span class="icon-warning"></span>';
                $output[] = $msg;
                $output[] = '</div>';

                $returnStatus = implode("\n", $output);
                break;
            case 'warning':
                $output[] = '<div class="alert alert-warning">';
                $output[] = '<span class="icon-notification"></span>';
                $output[] = $msg;
                $output[] = '</div>';

                $returnStatus = implode("\n", $output);
                break;

            case 'success':
                $output[] = '<div class="alert alert-success">';
                $output[] = '<span class="icon-checkmark"></span>';
                $output[] = $msg;
                $output[] = '</div>';

                $returnStatus = implode("\n", $output);
                break;

        }

        return $returnStatus;
    }

    /**
     * Extract content between two delimiters.
     * Example: $unit = extract_unit($text, 'from', 'to');
     *
     * @param $string
     * @param $start
     * @param $end
     *
     * @return string
     */
    public static function extract_unit($string, $start, $end)
    {
        $pos = stripos($string, $start);
        $str = substr($string, $pos);
        $str_two = substr($str, strlen($start));
        $second_pos = stripos($str_two, $end);
        $str_three = substr($str_two, 0, $second_pos);

        $unit = trim($str_three); // remove whitespaces

        return $unit;
    }

    /**
     * Clean up HTML
     *
     * @param $input_string
     * @param string $format
     *
     * @return string
     */
    public static function tidy_html($input_string, $format = 'html')
    {
        if ($format == 'xml') {
            $config = array(
                'input-xml' => true,
                'indent' => true,
                'wrap' => 800
            );
        } else {
            $config = array(
                'output-html' => true,
                'indent' => true,
                'wrap' => 800
            );
        }
        // Detect if Tidy is in configured
        if (function_exists('tidy_get_release')) {
            $tidy = new tidy;
            $tidy->parseString($input_string, $config, 'raw');
            $tidy->cleanRepair();
            $cleaned_html = tidy_get_output($tidy);
        } else {
            # Tidy not configured for this computer
            $cleaned_html = $input_string;
        }
        return $cleaned_html;
    }

    /**
     * Deletes ALL the string contents between the designated characters
     *
     * @param $start - pattern start
     * @param $end - pattern end
     * @param $string - input string
     *
     * @return mixed - string
     */
    public static function deleteAllBetween($start, $end, $string)
    {
        // it helps to assemble comma delimited strings
        $string = strtr($start . $string . $end, array($start => ',' . $start, $end => chr(2)));
        $startPos = 0;
        $endPos = strlen($string);
        while ($startPos !== false && $endPos !== false) {
            $startPos = strpos($string, $start);
            $endPos = strpos($string, $end);
            if ($startPos === false || $endPos === false) {
                $run = false;
                return $string;
            }
            $textToDelete = substr($string, $startPos, ($endPos + strlen($end)) - $startPos);
            // recursion to ensure all occurrences are replaced
            $string = deleteAllBetween($start, $end, str_replace($textToDelete, '', $string));
        }
        return $string;
    }

    /**
     * Get and return everything between two tags
     *
     * @param $string
     * @param $tagname
     * @return mixed
     */
    public static function everything_in_tags($string, $tagname)
    {
        $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
        preg_match($pattern, $string, $matches);
        return $matches[1];
    }

    /**
     * Get and set our dates and times
     *
     * @return array
     * @throws Exception
     */
    public static function getDatetimeNow()
    {
        $config = JFactory::getConfig();
        $siteOffset = $config->get('offset');

        $dtz = new DateTimeZone($siteOffset);

        $now = new DateTime();
        $now->setTimezone($dtz);

        $dates = array(
            'unix' => $now->format('U'),
            'long' => $now->format('Y-m-d H:i:s'),
            'short' => $now->format('Y-m-d')
        );

        return $dates;
    }

    /**
     * Function to clean and format url when using a CDN
     *
     * @param $url
     * @param $cdnDomain
     * @param $cdnRoot
     * @return string
     */
    public static function cdnUrl($url, $cdnDomain, $cdnRoot)
    {
        $cdn = preg_replace(array('#^.*\://#', '#/$#'), '', $cdnDomain);

        $path = parse_url($url, PHP_URL_PATH);
        $cdnUrl = $cdnRoot . $cdn . $path;

        return $cdnUrl;
    }

    /**
     * Get params for a specific module based on ID
     *
     * @param $id
     *
     * @return JRegistry
     *
     * @since  0.5.5
     */
    public static function getModuleParams($id)
    {
        //ref=https://stackoverflow.com/questions/12171947/how-to-get-module-params-in-component-area-in-joomla2-5
        //Example of how to use the returned object
        //$param = $moduleParams->get('param_name', 'default_value');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('m.*');
        $query->from('#__modules AS m');
        $query->where('id = ' . $id);
        $db->setQuery($query);
        $module = $db->loadObject();

        $params = new JRegistry($module->params);

        return $params;

    }
}