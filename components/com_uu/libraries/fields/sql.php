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

class FieldSql extends CustomField implements uuFieldInterface
{

	public function getSqlType()
    {
        return 'varchar(255)';
    }

    public function hasOptions(){
	    return false;
    }

	public function getFieldHTML( $field , $required , $isDropDown = true)
	{
        $params	= new UuParameter($field->params);
        
		$readonly	= $params->get('readonly') ? ' readonly="readonly"' : '';

		$class	= !empty( $field->description ) ? ' uuNameTips tipRight' : '';
		$class	.= !empty( $readonly) ? ' readonly' : '';


		$data_validation = ($field->required > 0) ? ' data-validation="required"' : '';
		$validation_message  = ($field->required > 0) ? ' data-validation-error-msg="'.JText::_('COM_UU_ENTRY_MISSING').'"' : '';

		$optionSize	= 1; // the default 'select below'

		$field->options = $this->loadOptions();
		

		if( !empty( $field->options ) )
		{
			$optionSize	+= count($field->options);
		}
		
		$dropDown	= ($isDropDown) ? '' : ' size="'.$optionSize.'"';

		$html		= '<select id="jform_'.$field->fieldcode.'" 
		                        ' . $data_validation . '
                                ' . $validation_message . '
								name="jform[' . $field->fieldcode . ']"' . $dropDown . ' 
								class="select'.$class.'" 
								title="' . UStringHelper::escape( JText::_( $field->description ) ). '" 
								style="'.$this->getStyle().'" 
								size="1" '.$readonly.'>';

		$defaultSelected	= '';

		//@rule: If there is no value, we need to default to a default value
		if(empty( $field->value ) )
		{
			$defaultSelected	.= ' selected="selected"';
		}

		if($isDropDown)
		{
			$html	.= '<option value="" ' . $defaultSelected . '>' . JText::_('COM_UU_SELECT_BELOW') . '</option>';
		}

		if( !empty( $field->options ) )
		{
			$selectedElement	= 0;

			foreach( $field->options as $option )
			{
				$selected	= ( $option->value == $field->value ) ? ' selected="selected"' : '';

				if( !empty( $selected ) )
				{
					$selectedElement++;
				}

// 							echo('<pre>');
// 							print_r($option->text);
// 							print_r($option->value);
// 							echo('</pre>');
// 							die();
				
				$html	.= '<option value="' . UStringHelper::escape( $option->value ) . '"' . $selected . '>' . JText::_( $option->text ) . '</option>';
			}
			
			if($selectedElement == 0)
			{
				//if nothing is selected, we default the 1st option to be selected.
				$eleName	= 'jform_'.$field->fieldcode;
				$html			.=<<< HTML
					   <script type='text/javascript'>
						   var slt = document.getElementById('$eleName');
						   if(slt != null)
						   {
						       slt.options[0].selected = true;
						   }
					   </script>
HTML;
			}
		}
		$html	.= '</select>';
		$html   .= '<span id="err_jform_'.$field->fieldcode.'_msg" style="display:none;">&nbsp;</span>';
		
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

	public function loadOptions() {
		$db		= JFactory::getDBO();

		$sql = $this->params->get('sqlquery');
        
		if(!empty($sql)){
			$query = $db->getQuery(true);
			$db->setQuery($sql);
			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
			}
			
			return $db->loadObjectList();
		} else {
			return null;
		}
	}



}