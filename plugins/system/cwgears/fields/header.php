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
 * Class CWElementHeader
 */
class CWElementHeader extends CWElement {

    /**
     * @param $name
     * @param $value
     * @param $node
     * @param $control_name
     * @return string
     */
    public function fetchElement($name, $value, &$node, $control_name) {

        return '<div class="paramHeaderContainer">'
                . '<div class="paramHeaderContent">' . JText::_($value) . '</div>'
                . '<div class="cwclr"></div></div>';
    }

    /**
     * @param $label
     * @param $description
     * @param $node
     * @param $control_name
     * @param $name
     * @return null
     */
    public function fetchTooltip($label, $description, &$node, $control_name, $name) {
        return NULL;
    }

}

/**
 * Class JFormFieldHeader
 */
class JFormFieldHeader extends CWElementHeader {

    var $type = 'header';

}
