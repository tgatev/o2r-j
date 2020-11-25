<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Uu records.
 */
class UuModelUsers extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'user_id', 'u.id',
                'name', 'u.name',
                'username', 'u.username',
                'email', 'u.email',
            );
        }

        parent::__construct($config);
    }


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));
		$this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'cmd'));

		// Load the parameters.
		$params = JComponentHelper::getParams('com_uu');
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'u.*,uu.*'
			)
		);
		$query->from('`#__uu_users` AS uu');

        $query->leftJoin('`#__users` AS u ON uu.user_id = u.id');


        // Filter the items over the search string if set.
        if ($this->getState('filter.search') !== '')
        {
            // Escape the search token.
            $token	= $db->Quote('%'.$db->escape($this->getState('filter.search')).'%');

            // Compile the different search clauses.
            $searches	= array();
            $searches[]	= 'u.name LIKE '.$token;
            $searches[]	= 'u.username LIKE '.$token;
            $searches[]	= 'u.email LIKE '.$token;

            // Add the clauses to the query.
            $query->where('('.implode(' OR ', $searches).')');
        }

		// Add the list ordering clause.
		$query->order($db->qn($db->escape($this->getState('list.ordering', 'u.id'))) . ' ' . $db->escape($this->getState('list.direction', 'desc')));

		return $query;
	}

    public function checkSyncUserMissing() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('u.id,uu.user_id')
              ->from($db->quoteName('#__users').' AS u')
              ->leftJoin($db->quoteName('#__uu_users').' AS uu ON u.id = uu.user_id')
              ->where('uu.user_id is null');
        $db->setQuery($query);
        $db->query();
        $count = $db->getNumRows();

        return $count;
    }

    public function checkSyncUserExtra() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('u.id')
            ->from($db->quoteName('#__uu_users').' AS uu')
            ->leftJoin($db->quoteName('#__users').' AS u ON uu.user_id = u.id')
            ->where('u.id is null');
        $db->setQuery($query);
        $db->query();
        $count = $db->getNumRows();

        return $count;
    }

    public function SyncUsers() {
        $db= JFactory::getDbo();
        //add missing user to uu_users
        $query = 'INSERT INTO '.$db->quoteName('#__uu_users'). '( '.$db->quoteName('user_id'). ' )';
        $query .= ' SELECT u.id FROM #__users AS u ';
        $query .= ' LEFT JOIN #__uu_users AS uu ON u.id = uu.user_id ';
        $query .= ' WHERE uu.user_id IS NULL';

        $db->setQuery($query);
        $result1 = $db->query();

        //remove extra user from uu.users
        $query = 'DELETE '.$db->quoteName('#__uu_users');
        $query .= ' FROM '.$db->quoteName('#__uu_users');
        $query .= ' LEFT JOIN #__users AS u ON #__uu_users.user_id = u.id';
        $query .= ' WHERE u.id IS NULL';

        $db->setQuery($query);
        $result2 = $db->query();


        return $result1 && $result2;

    }
}
