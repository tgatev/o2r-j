<?php 

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ROOT .'/components/com_uu/libraries/fields/sql.php');

class FieldCheckbox extends CustomField implements uuFieldInterface {
    public function getSqlType() {
        return 'tinyint';
    }

    public function hasOptions() {
        return false;
    }
    
    public function getFieldHTML( $field , $required , $isDropDown = true) {
        $params	= new UuParameter($field->params);
        $readonly	= $params->get('readonly') ? ' readonly="readonly"' : '';
        $style 				= $this->getStyle()?' style="' .$this->getStyle() . '" ':'';
        
        $data_validation = '';
        $data_validation_url = '';
        $validation_message ='';
        
//         switch ($field->fieldcode) {
//             case 'name':
//                 $data_validation = ' data-validation="length" data-validation-length="min3"';
//                 $validation_message = ' data-validation-error-msg="'.JText::_('COM_UU_NAME_TOO_SHORT').'"';
//                 break;
//             case 'username':
//                 $data_validation = ' data-validation="required server"';
//                 $data_validation_url = ' data-validation-url="'.JRoute::_('index.php?option=com_uu&task=registration.ajaxCheckUserName&format=json').'"';
//                 $validation_message  = ' data-validation-error-msg="'.JText::_('COM_UU_USERNAME_REQUIRED').'"';
//                 break;
//             default:
//                 $data_validation = ($field->required > 0) ? ' data-validation="required"' : '';
//                 $validation_message  = ($field->required > 0) ? ' data-validation-error-msg="'.JText::_('COM_UU_ENTRY_MISSING').'"' : '';
//                 break;
//         }
        
        // If maximum is not set, we define it to a default
//         $field->max	= empty( $field->max ) ? 200 : $field->max;
        //$class	= ($field->required > 0) ? ' required' : '';
        $class	= !empty( $field->description ) ? ' uuNameTips tipRight' : '';
        $class	.= !empty( $readonly) ? ' readonly' : '';
        //$class  .= !empty( $field->core) ? ' validate-'.$field->fieldcode : '';
        
        $html	= '<input '. // title="' . UStringHelper::escape( JText::_( $field->description ) ).'"
						'type="checkbox" value="1"
						'.$data_validation.'
						'.$data_validation_url.'
						'.$validation_message.'
						id="jform_' . $field->fieldcode . '"
						name="jform[' . $field->fieldcode . ']"
						class=" ' . $class . '" '.$style.$readonly.
						($field->value ? ' checked' : '').
						'/>';
        return $html;
    }
}

?>