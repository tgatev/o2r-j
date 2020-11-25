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
class FieldEmail extends CustomField implements uuFieldInterface
{
    public function getSqlType()
    {
        return 'varchar(255)';
    }

    public function hasOptions(){
        return false;
    }

	/**
	 * Method to format the specified value for text type
	 **/	 	
	public function getFieldData( $field )
	{
		$value = $field['value'];
		
		if( empty( $value ) )
			return $value;
		
		//CFactory::load( 'helpers' , 'linkgenerator' );
		
		return CLinkGeneratorHelper::getEmailURL($value);
	}
	
	public function getFieldHTML( $field , $required )
	{
        $params	= new UuParameter($field->params);
        $readonly	= $params->get('readonly') ? ' readonly="readonly"' : '';

		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;
		
		//get the value in param
		$params	= new UuParameter($field->params);
		$style 				= $this->getStyle()?' style="' .$this->getStyle() . '" ':'';

		//use for server validation of corefielld email1
		$data_validation = 'data-validation="email"';
		$data_validation_url = '';


		$class	= ($field->required > 0) ? ' required' : '';
		$class	.= $params->get('min_char') != '' && $params->get('max_char') != '' ? ' minmax_'.$params->get('min_char').'_'.$params->get('max_char') : '';
		$class	.= !empty( $field->description ) ? ' uuNameTips tipRight' : '';
        $class	.= !empty( $readonly) ? ' readonly' : '';
        if (!empty( $field->core)){
			if ($field->fieldcode == 'email1'){
				$data_validation = 'data-validation="email server"';
				$data_validation_url = 'data-validation-url="'.JRoute::_('index.php?option=com_uu&task=registration.ajaxCheckEmail&format=json').'"';
			}
			if ($field->fieldcode == 'email2'){
				$data_validation = 'data-validation="confirmation"  data-validation-confirm="jform[email1]"';
			}
        }
        $html	= '<input title="' . UStringHelper::escape( JText::_( $field->description ) ).'"
        				type="text" value="' . $field->value . '"
						'.$data_validation.'
						'.$data_validation_url.'
        				id="jform_' . $field->fieldcode . '"
        				name="jform[' . $field->fieldcode . ']"
        				maxlength="' . $field->max . '"
        				size="40" class=" ' . $class . '" '.$style.$readonly.' />';


		return $html;
	}
	
	public function isValid( $value , $required )
	{
		//CFactory::load( 'helpers' , 'validate' );
		
		$isValid	= CValidateHelper::email( $value );

		if( !empty($value) && !$isValid )
		{
			return false;
		}
		//validate string length
		if(!$this->validLength($value)){
			return false;
		}		
		//validate allowed domain
		if(isset($this->params)){
			$allowed = $this->params->get('allowed');
			if($allowed){
				$delimiter = ';';
				$allowed_list = explode($delimiter,$allowed);
				$valid = false;
				if(count($allowed_list) > 0 ){
					foreach($allowed_list as $domain){
						if(CValidateHelper::domain( $value, $domain))
						{
							$valid = true;
						}
					}
				}
				if(!$valid){
					return false;
				}
			}
		}
		//validate backlist domain
		if(isset($this->params)){
			$blacklist = $this->params->get('blacklist');
			if($blacklist){
				$delimiter = ';';
				$blacklists = explode($delimiter,$blacklist);
				if(count($blacklists) > 0 ){
					foreach($blacklists as $domain){
						if(CValidateHelper::domain( $value, $domain))
						{
							return false;
						}
					}
				}
			}
		}		
		return true; 
	}
}