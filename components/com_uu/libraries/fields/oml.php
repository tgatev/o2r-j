<?php 

/*
 * Този код не е довършен и може би е за изхвърляне. Пробвах концепция при която профайла на потребителя
 * се редактира само в една страцица. Получи се потенциално по-сложна реализация. Сложна с смисъл получаване
 * на коректна по отношение на потребителя форма.
 */

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ROOT .'/components/com_uu/libraries/fields/json.php');

class FieldOml extends FieldJson {
    
    public function getFieldHTML( $field , $required , $isDropDown = true) {
        $html = '';
        $db = JFactory::getDBO();
        $sql = 
            'SELECT o.*
               FROM  jc_uu_users u,
                   JSON_TABLE(u.cf_oml, 
            							"$[*]" COLUMNS(offer_id INT PATH "$")) omon,
            		jc_ofrs_offer o
            WHERE u.user_id = 505
                  AND o.id = omon.offer_id;';
        $query = $db->getQuery(true);
        $db->setQuery($sql);
        $res = $db->loadObjectList();
        foreach ($res as $offer) {
//             echo('<a>' . $offer->name . '</a><br>');
            $html .= '<a>' . $offer->name . '</a><br>';
        }
        
        
//         if(!empty($sql)){
//             if($db->getErrorNum()) {
//                 JError::raiseError( 500, $db->stderr());
//             }
//             return $db->loadObjectList();
//         } else {
//             return null;
//         }
        
        
        
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
        
        $html .= '<input title="' . UStringHelper::escape( JText::_( $field->description ) ).'"
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
}

?>