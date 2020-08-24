<?php 

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ROOT .'/components/com_uu/libraries/fields/sql.php');

class FieldSelect extends FieldSql {
    public function loadOptions() {
//         return array(
//             (object) [text => 'Publisher', value => 'P'],
//             (object) [text => 'Advertiser / Network', value => 'N']
//         );
        $db		= JFactory::getDBO();
//         $sql = $this->params->get('sqlquery');
        $sql = "SELECT title AS text, value FROM jc_uu_fields_values WHERE published = 1 AND id_field = " . $this->fieldId;
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

?>