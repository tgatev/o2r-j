<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */


defined('_JEXEC') or die;
abstract class JHtmlFields
{
    //TODO voir si on peut passer ça sur le helpers/html/manage
    static function required($value = 0, $i)
    {
        if (UU_J30) {
            $states = array(0=> array('unpublish','fields.required','COM_UU_FIELDS_TOGGLE_REQUIRED'),1=> array('publish','fields.unrequired','COM_UU_FIELDS_TOGGLE_UNREQUIRED'),2=> array('lock','fields.protected','COM_UU_FIELD_PROTECTED') );
        } else {
            $states = array(0=> array('unpublish','fields.required','COM_UU_FIELDS_TOGGLE_REQUIRED'),1=> array('publish','fields.unrequired','COM_UU_FIELDS_TOGGLE_UNREQUIRED'),2=> array('protected','fields.protected','COM_UU_FIELD_PROTECTED') );
        }

        return JHtmlFields::icon($value, $i,$states);;
    }

    static function registration($value = 0, $i)
    {
        if (UU_J30) {
            $states = array(0=> array('unpublish','fields.registration','COM_UU_FIELDS_TOGGLE_REGISTRATION'),1=> array('publish','fields.unregistration','COM_UU_FIELDS_TOGGLE_UNREGISTRATION'),2=> array('lock','fields.protected','COM_UU_FIELD_PROTECTED') );
        } else {
            $states = array(0=> array('unpublish','fields.registration','COM_UU_FIELDS_TOGGLE_REGISTRATION'),1=> array('publish','fields.unregistration','COM_UU_FIELDS_TOGGLE_UNREGISTRATION'),2=> array('protected','fields.protected','COM_UU_FIELD_PROTECTED') );
        }
        return JHtmlFields::icon($value, $i,$states);;
    }

    static function editable($value = 0, $i)
    {
        if (UU_J30) {
            $states = array(0=> array('unpublish','fields.editable','COM_UU_FIELDS_TOGGLE_EDITABLE'),1=> array('publish','fields.uneditable','COM_UU_FIELDS_TOGGLE_UNEDITABLE'),2=> array('lock','fields.protected','COM_UU_FIELD_PROTECTED') );
        } else {
            $states = array(0=> array('unpublish','fields.editable','COM_UU_FIELDS_TOGGLE_EDITABLE'),1=> array('publish','fields.uneditable','COM_UU_FIELDS_TOGGLE_UNEDITABLE'),2=> array('protected','fields.protected','COM_UU_FIELD_PROTECTED') );
        }
        return JHtmlFields::icon($value, $i,$states);;
    }

    static function icon($value, $i, $states)
    {
        if (UU_J30) {
            $state   = JArrayHelper::getValue($states, (int) $value, $states[1]);
            $html = '<i class="icon-'.$state[0].'"></i>';
            if ($value == 2 ) {
                $html    = '<a class="btn btn-micro disabled jgrid" title="'.JText::_($state[2]).'">'. $html.'</a>';
            } else {
                $html    = '<a class="btn btn-micro jgrid" href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[2]).'">'. $html.'</a>';
            }
        } else {
            $state   = JArrayHelper::getValue($states, (int) $value, $states[1]);
            $html = '<span class="state '.$state[0].'"><span class="text">'.JText::_($state[2]).'</span></span>';
            if ($value == 2 ) {
                $html    = '<a class="jgrid" title="'.JText::_($state[2]).'">'. $html.'</a>';
            } else {
                $html    = '<a class="jgrid" href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[2]).'">'. $html.'</a>';
            }
        }
        return $html;

    }

}
?>