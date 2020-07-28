<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.27
	@build			5th February, 2020
	@created		5th July, 2019
	@package		Offer Monster
	@subpackage		ad_networks.php
	@author			Delta Flip Ltd <http://deltaflip.com>	
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
 * Ad_networks Model
 */
class OfrsModelAd_networks extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
        {
			$config['filter_fields'] = array(
				'a.id','id',
				'a.published','published',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'a.name','name',
				'a.tracking_platform_id','tracking_platform_id',
				'a.currency_id','currency_id',
				'a.import_setup','import_setup'
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		$name = $this->getUserStateFromRequest($this->context . '.filter.name', 'filter_name');
		$this->setState('filter.name', $name);

		$tracking_platform_id = $this->getUserStateFromRequest($this->context . '.filter.tracking_platform_id', 'filter_tracking_platform_id');
		$this->setState('filter.tracking_platform_id', $tracking_platform_id);

		$currency_id = $this->getUserStateFromRequest($this->context . '.filter.currency_id', 'filter_currency_id');
		$this->setState('filter.currency_id', $currency_id);

		$import_setup = $this->getUserStateFromRequest($this->context . '.filter.import_setup', 'filter_import_setup');
		$this->setState('filter.import_setup', $import_setup);
        
		$sorting = $this->getUserStateFromRequest($this->context . '.filter.sorting', 'filter_sorting', 0, 'int');
		$this->setState('filter.sorting', $sorting);
        
		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);
        
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
        
		$created_by = $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '');
		$this->setState('filter.created_by', $created_by);

		$created = $this->getUserStateFromRequest($this->context . '.filter.created', 'filter_created');
		$this->setState('filter.created', $created);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		// check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

		// Set values to display correctly.
		if (OfrsHelper::checkArray($items))
		{
			// Get the user object if not set.
			if (!isset($user) || !OfrsHelper::checkObject($user))
			{
				$user = JFactory::getUser();
			}
			foreach ($items as $nr => &$item)
			{
				// decode payment_method
				$payment_methodArray = json_decode($item->payment_method, true);
				if (OfrsHelper::checkArray($payment_methodArray))
				{
					$payment_methodNames = array();
					foreach ($payment_methodArray as $payment_method)
					{
						$payment_methodNames[] = JText::_($this->selectionTranslation($payment_method, 'payment_method'));
					}
					$item->payment_method = implode(', ', $payment_methodNames);
				}
				// convert display_properties
				$item->display_properties = OfrsHelper::jsonToString($item->display_properties, ', ', 'display_properties');
			}
		}

		// set selection value to a translatable value
		if (OfrsHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// convert account_created
				$item->account_created = $this->selectionTranslation($item->account_created, 'account_created');
				// convert payment_method
				$item->payment_method = $this->selectionTranslation($item->payment_method, 'payment_method');
				// convert import_setup
				$item->import_setup = $this->selectionTranslation($item->import_setup, 'import_setup');
			}
		}

        
		// return items
		return $items;
	}

	/**
	 * Method to convert selection values to translatable string.
	 *
	 * @return translatable string
	 */
	public function selectionTranslation($value,$name)
	{
		// Array of account_created language strings
		if ($name === 'account_created')
		{
			$account_createdArray = array(
				'Y' => 'COM_OFRS_AD_NETWORK_YES',
				'N' => 'COM_OFRS_AD_NETWORK_NO'
			);
			// Now check if value is found in this array
			if (isset($account_createdArray[$value]) && OfrsHelper::checkString($account_createdArray[$value]))
			{
				return $account_createdArray[$value];
			}
		}
		// Array of payment_method language strings
		if ($name === 'payment_method')
		{
			$payment_methodArray = array(
				'P' => 'COM_OFRS_AD_NETWORK_PAYPAL',
				'C' => 'COM_OFRS_AD_NETWORK_CHECK',
				'W' => 'COM_OFRS_AD_NETWORK_WIRE',
				'A' => 'COM_OFRS_AD_NETWORK_ACH',
				'D' => 'COM_OFRS_AD_NETWORK_DIRECT_DEPOSIT'
			);
			// Now check if value is found in this array
			if (isset($payment_methodArray[$value]) && OfrsHelper::checkString($payment_methodArray[$value]))
			{
				return $payment_methodArray[$value];
			}
		}
		// Array of import_setup language strings
		if ($name === 'import_setup')
		{
			$import_setupArray = array(
				'N' => 'COM_OFRS_AD_NETWORK_NEVER',
				'E' => 'COM_OFRS_AD_NETWORK_EVERY_TIME',
				'D' => 'COM_OFRS_AD_NETWORK_ONCE_A_DAY'
			);
			// Now check if value is found in this array
			if (isset($import_setupArray[$value]) && OfrsHelper::checkString($import_setupArray[$value]))
			{
				return $import_setupArray[$value];
			}
		}
		return $value;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Get the user object.
		$user = JFactory::getUser();
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the ofrs_item table
		$query->from($db->quoteName('#__ofrs_ad_network', 'a'));

		// From the ofrs_tracking_platform table.
		$query->select($db->quoteName('g.name','tracking_platform_id_name'));
		$query->join('LEFT', $db->quoteName('#__ofrs_tracking_platform', 'g') . ' ON (' . $db->quoteName('a.tracking_platform_id') . ' = ' . $db->quoteName('g.id') . ')');

		// From the ofrs_currency table.
		$query->select($db->quoteName('h.name','currency_id_name'));
		$query->join('LEFT', $db->quoteName('#__ofrs_currency', 'h') . ' ON (' . $db->quoteName('a.currency_id') . ' = ' . $db->quoteName('h.id') . ')');

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}
		// Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search) . '%');
				$query->where('(a.name LIKE '.$search.' OR a.tracking_platform_id LIKE '.$search.' OR g.name LIKE '.$search.' OR a.description LIKE '.$search.' OR a.payment_method LIKE '.$search.' OR a.api_params LIKE '.$search.')');
			}
		}

		// Filter by tracking_platform_id.
		if ($tracking_platform_id = $this->getState('filter.tracking_platform_id'))
		{
			$query->where('a.tracking_platform_id = ' . $db->quote($db->escape($tracking_platform_id)));
		}
		// Filter by Import_setup.
		if ($import_setup = $this->getState('filter.import_setup'))
		{
			$query->where('a.import_setup = ' . $db->quote($db->escape($import_setup)));
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'asc');	
		if ($orderCol != '')
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get list export data.
	 *
	 * @param   array  $pks  The ids of the items to get
	 * @param   JUser  $user  The user making the request
	 *
	 * @return mixed  An array of data items on success, false on failure.
	 */
	public function getExportData($pks, $user = null)
	{
		// setup the query
		if (OfrsHelper::checkArray($pks))
		{
			// Set a value to know this is export method. (USE IN CUSTOM CODE TO ALTER OUTCOME)
			$_export = true;
			// Get the user object if not set.
			if (!isset($user) || !OfrsHelper::checkObject($user))
			{
				$user = JFactory::getUser();
			}
			// Create a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// Select some fields
			$query->select('a.*');

			// From the ofrs_ad_network table
			$query->from($db->quoteName('#__ofrs_ad_network', 'a'));
			$query->where('a.id IN (' . implode(',',$pks) . ')');

			// Order the results by ordering
			$query->order('a.ordering  ASC');

			// Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// Set values to display correctly.
				if (OfrsHelper::checkArray($items))
				{
					foreach ($items as $nr => &$item)
					{
						// unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// Add headers to items array.
				$headers = $this->getExImPortHeaders();
				if (OfrsHelper::checkObject($headers))
				{
					array_unshift($items,$headers);
				}
				return $items;
			}
		}
		return false;
	}

	/**
	* Method to get header.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getExImPortHeaders()
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		// get the columns
		$columns = $db->getTableColumns("#__ofrs_ad_network");
		if (OfrsHelper::checkArray($columns))
		{
			// remove the headers you don't import/export.
			unset($columns['asset_id']);
			unset($columns['checked_out']);
			unset($columns['checked_out_time']);
			$headers = new stdClass();
			foreach ($columns as $column => $type)
			{
				$headers->{$column} = $column;
			}
			return $headers;
		}
		return false;
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @return  string  A store id.
	 *
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.name');
		$id .= ':' . $this->getState('filter.tracking_platform_id');
		$id .= ':' . $this->getState('filter.currency_id');
		$id .= ':' . $this->getState('filter.import_setup');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to checkin all items left checked out longer then a set time.
	 *
	 * @return  a bool
	 *
	 */
	protected function checkInNow()
	{
		// Get set check in time
		$time = JComponentHelper::getParams('com_ofrs')->get('check_in');

		if ($time)
		{

			// Get a db connection.
			$db = JFactory::getDbo();
			// reset query
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__ofrs_ad_network'));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				// Get Yesterdays date
				$date = JFactory::getDate()->modify($time)->toSql();
				// reset query
				$query = $db->getQuery(true);

				// Fields to update.
				$fields = array(
					$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
					$db->quoteName('checked_out') . '=0'
				);

				// Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('checked_out') . '!=0', 
					$db->quoteName('checked_out_time') . '<\''.$date.'\''
				);

				// Check table
				$query->update($db->quoteName('#__ofrs_ad_network'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
