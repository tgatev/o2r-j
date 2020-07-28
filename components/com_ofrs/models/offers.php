<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			26th November, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		offers.php
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
 * Ofrs Model for Offers
 */
class OfrsModelOffers extends JModelList
{

    CONST ORDER_MAP = [
        "45" => "a.name",            //    <option value="45">Offer name</option>
        "43" => "b.name",            //    <option value="43">Network</option>
        "22" => "c.display",            //    <option value="22">Payout</option>
        "23" => "d.name",            //    <option value="23">Type</option>
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
        $filter = $this->getState('ofrs.filter', array());
        $f_search = $filter['search'];
        $f_network_id = $filter['ad_network_id'];
        $f_geo = $filter['geo_targeting'];
        $f_verticals = $filter['verticals'];
        $f_payout_type = $filter['payout_type'];

//        var_dump($stateVar);
//        die();
		// Get a db connection.
		$db = JFactory::getDbo();

		// [Prepare Query to ] Get data
/***[JCBGUI.dynamic_get.php_custom_get.41.$$$$]***/
		// Create a new query object.
		$query = $db->getQuery(true);

        // Get from #__ofrs_offer as a
        // b.display_properties as display_properties,
		$query->select('a.id AS id,
                        a.ad_network_id AS ad_network_id,
                        a.name AS name,
                        a.description AS description,
                        a.preview_url AS preview_url,
                        GROUP_CONCAT(g.name SEPARATOR \', \') AS verticals,
                        GROUP_CONCAT(DISTINCT(e.vertical_id) SEPARATOR \',\') AS verticals_ids,
                        GROUP_CONCAT(DISTINCT(h.code) SEPARATOR \', \') AS geo_targeting,'
                        .'countries_display as geo_targeting_full,'
                        ."verticals_display as verticals_full, "
                        .'a.published AS published,
                        a.modified AS modified,
                        b.name AS adnet_name,
                        d.name AS payout_type,
                        c.display');

		$query->from($db->quoteName('#__ofrs_offer', 'a'));
		$query->join('', ($db->quoteName('#__ofrs_ad_network', 'b')) . ' ON (' . $db->quoteName('a.ad_network_id') . ' = ' . $db->quoteName('b.id') . ')');
        $query->join('LEFT', ($db->quoteName('#__ofrs_offer_payout', 'c')) . 'ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('c.offer_id') . ')');
        $query->join('LEFT', ($db->quoteName('#__ofrs_payout_type', 'd')) . 'ON (' . $db->quoteName('c.type') . ' = ' . $db->quoteName('d.id') . ')');
        $query->join('LEFT', ($db->quoteName('#__ofrs_offer_vertical', 'e')) . 'ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('e.offer_id') . ')');
        $query->join('LEFT', ($db->quoteName('#__ofrs_offer_country', 'f')) . 'ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('f.offer_id') . ')');
        $query->join('LEFT', ($db->quoteName('#__ofrs_vertical', 'g')) . 'ON (' . $db->quoteName('e.vertical_id') . ' = ' . $db->quoteName('g.id') . ')');
        $query->join('LEFT', ($db->quoteName('#__ofrs_country', 'h')) . 'ON (' . $db->quoteName('f.country_id') . ' = ' . $db->quoteName('h.id') . ')');

		// published only
		$query->where('a.published = 1');

            // filter on general word
		if (strlen($f_search)) $query->where("(a.name LIKE '%" . $f_search . "%' OR g.name LIKE '%" . $f_search . "%')");

		    // filter on network
		if (isset($f_network_id) && count($f_network_id)) $query->where("a.ad_network_id IN ('" . implode("','", $f_network_id) . "')");
		    
				
		    // filter on country
        if (isset($f_geo) && count($f_geo)) $query->where("f.country_id IN ('516','" . implode("','", $f_geo) . "')");
		
            // filter on vertical
        if (isset($f_verticals)) $query->where("e.vertical_id IN ('" . implode("','", $f_verticals) . "')");

            // filter on payout type
        if (isset($f_payout_type) && count($f_payout_type)) $query->where('c.type IN (\'' . implode("','",$f_payout_type) . '\')');
//
        if($ordering = $this->getState('list.ordering', 'a.modified')){
            $query->order($db->escape($ordering).' '.$db->escape($this->getState('list.direction', 'DESC')));
        }
//		$query->group('a.id,a.ad_network_id,a.name,a.description,a.published,a.modified,b.name,d.name,c.payout,c.display');
		$query->group('a.id,d.name,c.display');
//
//        echo $query->dump();
//        die();
        // return the query object
		return $query;
	}

    /**
     * Get all verticals defined and prepare as associative map [ id => name ]
     * @return array
     * @throws Exception
     */
    public function getVerticalsMap()
    {

        // Get the current user for authorisation checks
        $this->user = JFactory::getUser();
        $this->userId = $this->user->get('id');
        $this->guest = $this->user->get('guest');
        $this->groups = $this->user->get('groups');
        $this->authorisedGroups = $this->user->getAuthorisedGroups();
        $this->levels = $this->user->getAuthorisedViewLevels();
        $this->app = JFactory::getApplication();
        $this->initSet = true;

        // FETCH List of verticals

        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // [Prepare Query to ] Get data
        /***[JCBGUI.dynamic_get.php_custom_get.41.$$$$]***/
        // Create a new query object.
        $query = $db->getQuery(true);

        // Get from #__ofrs_offer as a
        // b.display_properties as display_properties,
        $query->select('id, `name`')->from('jc_ofrs_vertical');
        $db->setQuery($query);
        $db->execute();

        // Prepare results
        foreach ( $db->loadAssocList() as $key => $val){
            $result[$val["id"]] = $val["name"];
        }

        return $result ?? [] ;
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


/***[JCBGUI.site_view.php_model.27.$$$$]***/
    protected function populateState($ordering = 'a.modified', $direction = null) {
        // get filter values
	    parent::populateState($ordering, 'DESC');

        $app = JFactory::getApplication();
        $filter = $app->getUserStateFromRequest('ofrs.filter', 'filter', array());
        $offset = $app->getUserStateFromRequest('list.start', 'limitstart', 0);

        // parse value in sort_by select,
        if(!strpos($filter['sort_by'], '.')){
            // SIMPLE select option
            $this->setState('list.ordering', self::ORDER_MAP[$filter['sort_by']] ?? 'a.modified'  );
            $this->setState('list.direction', $filter['sort_direction'] ?? 'DESC'  );
        }else{
            // split direction and column in case of payout ( <int>.(asc|desc))
            $order_parts = explode(".", $filter['sort_by'] );
            $this->setState('list.ordering', self::ORDER_MAP[$order_parts[0]] ?? 'a.modified'  );
            if($order_parts[1] === "asc" ){
                $this->setState('list.direction', 'ASC'  );
                $filter['sort_direction'] = "ASC";
            }elseif($order_parts[1] === "desc" ){
                $this->setState('list.direction', 'DESC'  );
                $filter['sort_direction'] = "DESC";
            }else{ //Default
                $this->setState('list.direction', 'ASC'  );
                $filter['sort_direction'] = "ASC";
            }
        }

        $this->setState('list.start', $offset ?? 0 );
        $this->setState('list.limit', $filter['count_per_page'] ?? 20 );
        $this->setState('ofrs.filter', $filter );
        $app->setUserState('ofrs.filter_parsed', $filter );

	}
	
	public function __construct($config = array())
	{
	    $config['filter_fields'] = array('a.name','b.name','c.display','d.name','a.modified');
	    parent::__construct($config);
	}

	public function getCountsOfFilterResults(){
        $query= $this->getListQuery();
        $db = JFactory::getDbo();
        $db->setQuery($query);
        $db->execute();
        return $db->getNumRows();
    }
/***[/JCBGUI$$$$]***/

}
