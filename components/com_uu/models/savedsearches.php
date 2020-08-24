<?php 

defined('_JEXEC') or die;

// jimport('joomla.application.component.modelform');
// jimport('joomla.event.dispatcher');

/**
 * Profile model class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class UuModelSavedSearches extends JModelList
{
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
    public function getItems() {
        $items = parent::getItems();
        return $items;
    }
    
    
    
    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery() {
                // Get the current user for authorisation checks
        $this->user = JFactory::getUser();
        $this->userId = $this->user->get('id');
        
        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);
        
        //
        $sql = "id AS saved_search_id,
                name AS saved_search_name
                FROM ofrs_user_saved_search
                WHERE user_id = " . $this->userId;
        $query -> select($sql);
        return $query;
    }
}
    