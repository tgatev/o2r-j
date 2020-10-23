<?php 

defined('_JEXEC') or die;
require_once (JPATH_ROOT .'/components/com_uu/libraries/parameter.php');
require_once(JPATH_SITE.'/components/com_uu/models/registration.php');

// jimport('joomla.application.component.modelform');
// jimport('joomla.event.dispatcher');

/**
 * Profile model class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class UuModelSubscriptions extends JModelList {
    /**
     * @var		object	The user profile data.
     * @since	1.6
     */
    protected $data;
    
    
    /**
     * Method to get an array of data items.
     *
     * @return  mixed  An array of data items on success, false on failure.
     */
    public function getItems($sql) {
        if (!isset($this->initSet) || !$this->initSet) {
            $this->user = JFactory::getUser();
            $this->userId = $this->user->get('id');
            $this->guest = $this->user->get('guest');
            $this->groups = $this->user->get('groups');
            $this->authorisedGroups = $this->user->getAuthorisedGroups();
            $this->levels = $this->user->getAuthorisedViewLevels();
            $this->initSet = true;
        }
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $sql = str_replace('@u', $user_id, $sql);
        $query -> select($sql);
        
        // Reset the query using our newly populated query object.
        $db->setQuery($query);
        $items = $db->loadObjectList();
        
        if (empty($items))
            return false;
        return $items;
    }
    
    
    
    /**
     * В случая е dummy защото не мога да преценя коя от групите за които се абонираме би трябвало да е "главна"
     *
     * @return      string  An SQL query
     */
    protected function getListQuery() {
        return $query;
    }
    
            
    /**
     * Custom Method
     *
     * @return mixed  An array of objects on success, false on failure.
     *
     */
    public function getVerticalsMonitored() {
        if (!isset($this->initSet) || !$this->initSet) {
            $this->user = JFactory::getUser();
            $this->userId = $this->user->get('id');
            $this->guest = $this->user->get('guest');
            $this->groups = $this->user->get('groups');
            $this->authorisedGroups = $this->user->getAuthorisedGroups();
            $this->levels = $this->user->getAuthorisedViewLevels();
            $this->initSet = true;
        }
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $sql = "v.id AS vertical_id,
            	v.name AS vertical_name
            FROM ofrs_vertical_monitor vm, jc_ofrs_vertical v
            WHERE vm.vertical_id = v.id
            AND vm.user_id = " . $this->userId;
        $query -> select($sql);
        
        // Reset the query using our newly populated query object.
        $db->setQuery($query);
        $items = $db->loadObjectList();
        
        if (empty($items))
            return false;
        
        return $items;
    }

    /**
     * Custom Method
     *
     * @return mixed  An array of objects on success, false on failure.
     *
     */
    public function getAdnetsMonitored() {
        return $this->getItems("n.id AS adnet_id, n.name AS adnet_name
                        FROM jc_ofrs_ad_network n, ofrs_ad_network_monitor nm
                        WHERE n.id = nm.ad_network_id");
        return $items;
    }
        
    public function getSavedSearchesMonitored() {
        if (!isset($this->initSet) || !$this->initSet) {
            $this->user = JFactory::getUser();
            $this->userId = $this->user->get('id');
            $this->guest = $this->user->get('guest');
            $this->groups = $this->user->get('groups');
            $this->authorisedGroups = $this->user->getAuthorisedGroups();
            $this->levels = $this->user->getAuthorisedViewLevels();
            $this->initSet = true;
        }
        
        // Get a db connection.
        $db = JFactory::getDbo();
        
        // Create a new query object.
        $query = $db->getQuery(true);
        
        // Get from #__ofrs_vertical as a
        $sql = "uss.id AS ss_id,
		          uss.name AS ss_name
                FROM ofrs_saved_search_monitor ssm, ofrs_user_saved_search uss
                WHERE ssm.uss_id = uss.id
                AND uss.user_id = " . $this->userId;
        $query -> select($sql);
        
        // Reset the query using our newly populated query object.
        $db->setQuery($query);
        $items = $db->loadObjectList();
        
        if (empty($items))
            return false;
        return $items;
    }
    
    
    public function getSavedSearchesSelect() {
        $field = new StdClass();
        $field->id = 23;
        $field->type = 'sql';
        $field->fieldcode = 'sslist';
        $fieldType	= strtolower( $field->type);
        $class	= 'Field' . ucfirst( $fieldType );
        $field->name	= UStringHelper::escape($field->name);
        
        $classPath = JPATH_ROOT . '/components/com_uu/libraries/fields/'. $field->type.'.php';
        jimport('joomla.filesystem.file');
        if( JFile::exists($classPath) )
            require_once ($classPath);
        
        if( class_exists( $class ) ) {
            $object	= new $class($field->id);
            
            if( method_exists( $object, 'getFieldHTML' ) ) {
                $sql = $object->params->get('sqlquery');
                $sql .= ' WHERE user_id = ' . $this->userId;
//                 echo('<pre>');
//                 echo($sql);
//                 echo('</pre>');
                
                $html	= $object->getFieldHTML( $field , $showRequired );
                return $html;
            }
        }
        
        return JText::sprintf('COM_UU_UNKNOWN_USER_FIELD_TYPE' , $class , $fieldType );
    }
    

    public function getVerticalsSelect() {
        $field = new StdClass();
        $field->id = 21;
        $field->type = 'sql';
        $field->fieldcode = 'vertlist';
        return UuModelRegistration::getFieldHTML($field);
    }
    
    public function getAdnetsSelect() {
        $field = new StdClass();
        $field->id = 22;
        $field->type = 'sql';
        $field->fieldcode = 'adnetlist';
        return UuModelRegistration::getFieldHTML($field);
    }
}