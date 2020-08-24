<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Library
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Library is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

namespace CoalaWeb;

use Joomla\CMS\Language\Text as JText;

defined('_JEXEC') or die;

/**
 * Class Xml
 * @package CoalaWeb
 */
class Messages
{
    public static $instance = null;

    /**
     * @return static instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new static;
        }

        return self::$instance;
    }

    /**
     * Format messages
     *
     * @param $type
     * @param $msg
     * @param array $sprint
     * @return string
     */
    public function getMessage($type, $msg, $sprint = array())
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
}