<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;

class UStringHelper
{

    static public function escape($var, $function='htmlspecialchars')
    {
        if (in_array($function, array('htmlspecialchars', 'htmlentities')))
        {
            return call_user_func($function, $var, ENT_COMPAT, 'UTF-8');
        }
        return call_user_func($function, $var);
    }
}