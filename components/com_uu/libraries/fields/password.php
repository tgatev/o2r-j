<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ROOT .'/components/com_uu/libraries/fields/customfield.php');
class FieldPassword extends CustomField implements uuFieldInterface
{

    public function getSqlType()
    {
        return 'varchar(255)';
    }

    public function hasOptions(){
        return true;
    }

	public function getFieldHTML( $field , $required )
	{
        $params	= new UuParameter($field->params);


		//$readonly	= $params->get('readonly') ? ' readonly=""' : '';
        $readonly	= $params->get('readonly') ? ' readonly="readonly"' : '';

		$style 				= $this->getStyle()?' style="' .$this->getStyle() . '" ':'';

        $auto		= ' autocomplete="off"';

		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;
//		$class	= ($field->required > 0) ? ' required' : '';
		$class	= !empty( $field->description ) ? ' uuNameTips tipRight' : '';
        $class	.= !empty( $readonly) ? ' readonly' : '';

//        if (!empty( $field->core)){
//            $class .= $field->fieldcode == 'password1' ? ' validate-password':' validate-passverify';
//        }

        //password 2 must be the same of the first
        $data_validation = '';
        if ( $field->fieldcode == 'password2'){
            $data_validation = ' data-validation="required confirmation" data-validation-confirm="jform[password1]"  ';
        }
        if ( $field->fieldcode == 'password1') {
            $data_validation = ' data-validation="required strength" data-validation-strength="1"  ';
        }

        $html	= '<input
                        title="' . UStringHelper::escape( JText::_( $field->description ) ).'"
                        type="password" value="' . $field->value . '"
                        name="jform[' . $field->fieldcode . ']"
                        '.$data_validation.'
                        id="jform_' . $field->fieldcode . '"
                        maxlength="' . $field->max . '"
                        size="40"
                        class="' . $class . '" '.$style.$readonly.$auto.'
                        />';

		return $html;
	}

	public function isValid( $value , $required )
	{
		if( $required && empty($value))
		{
			return false;
		}
		//validate string length
		if(!$this->validLength($value)){
			return false;
		}
		return true;
	}


}