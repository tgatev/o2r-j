<?php 

/*
 * Този код не е довършен и може би е за изхвърляне. Пробвах концепция при която профайла на потребителя
 * се редактира само в една страцица. Получи се потенциално по-сложна реализация. Сложна с смисъл получаване
 * на коректна по отношение на потребителя форма.
 */

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ROOT .'/components/com_uu/libraries/fields/customfield.php');

class FieldJson extends CustomField implements uuFieldInterface {
    public function getSqlType()
    {
        return 'json';
    }
    
    public function hasOptions(){
        return false;
    }
    
    public function getFieldHTML( $field , $required , $isDropDown = true) {
        return '<a>json type</a>';
    }
}

?>