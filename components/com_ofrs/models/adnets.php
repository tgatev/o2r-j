<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			26th November, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		adnets.php
	@author			SMIG <http://fuckitall.info>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Ofrs Model for Adnets
 */
class OfrsModelAdnets extends JModelList
{
    CONST ORDER_MAP = [
        "43" => "a.name",            //    <option value="43">Network</option>
        "24" => 'offer_count',
        "49" => "a.modified",            //    <option value="49">Updated</option>
    ];
	/**
	 * Model user data.
	 *
	 * @var        strings
	 */
	protected $user;
	protected $userId;
	protected $guest;
	protected $groups;
	protected $levels;
	protected $app;
	protected $input;
	protected $uikitComp;

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Get the current user for authorisation checks
		$this->user = JFactory::getUser();
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
		$this->initSet = true;

        // Fetch request inputs from caches
        $filter = $this->getState('adnets.filter', array());
        $f_search = $filter['search'];
        // Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// [Prepare Query to ] Get data

/***[JCBGUI.dynamic_get.php_custom_get.40.$$$$]***/
		// Get from #__ofrs_ad_network as a
		$query->select('a.id AS adnet_id,
                        a.name AS adnet_name,
                        a.description AS adnet_description,
                        a.adnet_text_color AS adnet_text_color,
                        a.adnet_background_color AS adnet_background_color,
                        b.offer_count AS offer_count,
                        b.modified AS adnet_modified
                        ');
		$query->from($db->quoteName('#__ofrs_ad_network', 'a'));
		
        // Join to offers
 		$query->join('INNER', ($db->quoteName('ofrs_ad_network_summary', 'b')) . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.ad_network_id') . ')');

 		if (!is_null($f_search))
 		    $query->where("a.name LIKE '%" . $f_search . "%'");

        $query->where("a.published = 1");
		$query->group(array('a.id'));
        $ord_col = $this->getState('list.ordering', self::ORDER_MAP[$filter['sort_by']] ?? 'a.modified');
        $ord_direction = $this->getState('list.direction', 'DESC');
        $query->order($db->escape($ord_col).' '.$db->escape($ord_direction));
        return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$user = JFactory::getUser();
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_ofrs', true);

		// Insure all item fields are adapted where needed.
		if (OfrsHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// Always create a slug for sef URL's
				$item->slug = (isset($item->alias) && isset($item->id)) ? $item->id.':'.$item->alias : $item->id;
			}
		}

		// return items
		return $items;
	}


/***[JCBGUI.site_view.php_model.26.$$$$]***/
protected function populateState($ordering = null, $direction = null) {
	    parent::populateState('a.name', 'ASC');

        $app = JFactory::getApplication();
        $filter = $app->getUserStateFromRequest('adnets.filter', 'filter', array());
        $offset = $app->getUserStateFromRequest('list.start', 'limitstart', 0);

        if(is_integer((int) $filter['sort_by'])){ // Only simple Types
            $this->setState('list.ordering', self::ORDER_MAP[$filter['sort_by']] ?? 'a.modified'  );
            $this->setState('list.direction', $filter['sort_direction'] ?? 'DESC'  );
        }else{
            // implement custom optional values like: <int|string>.(asc|desc)
        }
        //  Set positions
        $this->setState('list.start', $offset ?? 0 );
        $this->setState('list.limit', $filter['count_per_page'] ?? 20 );
        $this->setState('adnets.filter', $filter );
	}
	
	public function __construct($config = array())
	{
	    $config['filter_fields'] = array('a.name','offer_count');
	    parent::__construct($config);
	}/***[/JCBGUI$$$$]***/

    public function getCountsOfFilterResults(){
        $query= $this->getListQuery();
        $db = JFactory::getDbo();
        $db->setQuery($query);
        $db->execute();
        return $db->getNumRows();
    }
}
