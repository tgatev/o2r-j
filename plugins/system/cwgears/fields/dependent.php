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

JFormHelper::loadFieldClass('note');

/**
 * Form Field class for CoalaWeb extensions
 * Checks and displays messages related to dependencies
 */
class JFormFieldDependent extends JFormFieldNote
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'Dependent';

    /**
     * Method to get the field label markup.
     *
     * @return  string  The field label markup.
     *
     * @since   11.1
     */
    protected function getLabel()
    {
        if (empty($this->element['label']) || empty($this->element['description'])) {
            return '';
        }

        $description = (string)$this->element['description'];
        $langRoot = (string)$this->element['label'];

        $html = array();

        $arr = explode("_", $description);

        switch ($arr[0]) {
            case "com":
                $url = JPATH_ADMINISTRATOR . '/' . 'components/' . $description . '/version.php';
                $check = JComponentHelper::isEnabled($description, true);
                if (!file_exists($url) || !$check) {
                    $html[] = '<div class="alert alert-warning"><span class="icon-notification"></span><button type="button" class="close" data-dismiss="alert">&times;</button>' . JText::sprintf($langRoot . '_NOEXT_CHECK_MESSAGE', $description);
                    return '</div>' . implode('', $html);
                }
                break;
            case "mod":
                $url = JPATH_SITE . '/' . 'modules/' . $description . '/version.php';
                $check = JModuleHelper::isEnabled($description, true);
                if (!file_exists($url) || !$check) {
                    $html[] = '<div class="alert alert-warning"><span class="icon-notification"></span><button type="button" class="close" data-dismiss="alert">&times;</button>' . JText::sprintf($langRoot . '_NOEXT_CHECK_MESSAGE', $description);
                    return '</div>' . implode('', $html);
                }
                break;
            case "plg":
                $url = JPATH_SITE . '/' . 'plugins/' . $arr[1] . '/' . $arr[2] . '/version.php';
                $check = JPluginHelper::isEnabled($arr[1], $arr[2], true);
                if (!file_exists($url) || !$check) {
                    $html[] = '<div class="alert alert-warning"><span class="icon-notification"></span><button type="button" class="close" data-dismiss="alert">&times;</button>' . JText::sprintf($langRoot . '_NOEXT_CHECK_MESSAGE', $description);
                    return '</div>' . implode('', $html);
                }
                break;
        }

        return;

    }

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        return '';
    }
}
