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

/**
 * Class CWElementInherit
 */
class CWElementInherit extends CWElement {

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
        $arr = explode("_", $description);
        
        switch ($arr[0]) {
            case "com":
                $url = JPATH_ADMINISTRATOR . '/' . 'components/' . $description . '/version.php';
                $check = JComponentHelper::isEnabled($description, true);
                break;
            case "mod":
                $url = JPATH_SITE . '/' . 'modules/' . $description . '/version.php';
                break;
            case "plg":
                $url = JPATH_SITE . '/' . 'plugins/' . $arr[1] . '/' . $arr[2] . '/version.php';
                $check = JPluginHelper::isEnabled($arr[1], $arr[2], true);
                break;
        }   
        
        if (file_exists($url) && $check){
            return '<div class="cw-message-block">'
                    . '<div class="cw-message">'
                    . '<p class="alert">' . JText::_($label . '_MSG_INHERIT') . '</p>'
                    . '</div></div>';
        }
    }

}

/**
 * Class JFormFieldInherit
 */
class JFormFieldInherit extends CWElementInherit {
    var $type = 'inherit';
}
