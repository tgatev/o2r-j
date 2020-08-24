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
class FieldText extends CustomField implements uuFieldInterface
{

	public function getSqlType()
    {
        return 'varchar(255)';
    }

    public function hasOptions(){
        return false;
    }

	public function getFieldHTML( $field , $required )
	{
        $params	= new UuParameter($field->params);

		$readonly	= $params->get('readonly') ? ' readonly="readonly"' : '';

		$style 				= $this->getStyle()?' style="' .$this->getStyle() . '" ':'';

		$data_validation = '';
		$data_validation_url = '';
		$validation_message ='';

		switch ($field->fieldcode) {
			case 'name':
						$data_validation = ' data-validation="length" data-validation-length="min3"';
						$validation_message = ' data-validation-error-msg="'.JText::_('COM_UU_NAME_TOO_SHORT').'"';
						break;
			case 'username':
						$data_validation = ' data-validation="required server"';
						$data_validation_url = ' data-validation-url="'.JRoute::_('index.php?option=com_uu&task=registration.ajaxCheckUserName&format=json').'"';
				        $validation_message  = ' data-validation-error-msg="'.JText::_('COM_UU_USERNAME_REQUIRED').'"';
						break;
			default:
						$data_validation = ($field->required > 0) ? ' data-validation="required"' : '';
						$validation_message  = ($field->required > 0) ? ' data-validation-error-msg="'.JText::_('COM_UU_ENTRY_MISSING').'"' : '';
						break;
		}

		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;
		//$class	= ($field->required > 0) ? ' required' : '';
		$class	= !empty( $field->description ) ? ' uuNameTips tipRight' : '';
        $class	.= !empty( $readonly) ? ' readonly' : '';
        //$class  .= !empty( $field->core) ? ' validate-'.$field->fieldcode : '';

		$html	= '<input title="' . UStringHelper::escape( JText::_( $field->description ) ).'"
						type="text" value="' . $field->value . '"
						'.$data_validation.'
						'.$data_validation_url.'
						'.$validation_message.'
						id="jform_' . $field->fieldcode . '"
						name="jform[' . $field->fieldcode . ']"
						maxlength="' . $field->max . '"
						size="40" class=" ' . $class . '" '.$style.$readonly.'
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