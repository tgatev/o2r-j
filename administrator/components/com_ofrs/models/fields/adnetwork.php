<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.27
	@build			5th February, 2020
	@created		5th July, 2019
	@package		Offer Monster
	@subpackage		adnetwork.php
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

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Adnetwork Form Field class for the Ofrs component
 */
class JFormFieldAdnetwork extends JFormFieldList
{
	/**
	 * The adnetwork field type.
	 *
	 * @var		string
	 */
	public $type = 'adnetwork';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
	 */
	protected function getOptions()
	{
		// Get the user object.
		$user = JFactory::getUser();
		// Get the databse object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// $query->select($db->quoteName(array('a.id','a.name'),array('id','ad_network_id_name')));
		// $query->from($db->quoteName('#__ofrs_ad_network', 'a'));
		// $query->where($db->quoteName('a.published') . ' = 1');
		// $query->order('a.name ASC');
		// Implement View Level Access (if set in table)
		// if (!$user->authorise('core.options', 'com_ofrs'))
		// {
		// 	$columns = $db->getTableColumns('#__ofrs_ad_network');
		// 	if(isset($columns['access']))
		// 	{
		// 		$groups = implode(',', $user->getAuthorisedViewLevels());
		// 		$query->where('a.access IN (' . $groups . ')');
		// 	}
		// }
		
		$query->select('a.id AS id,a.name AS ad_network_id_name
						FROM jc_ofrs_ad_network AS a
						JOIN ofrs_ad_network_summary s ON (a.id = s.ad_network_id)
						WHERE a.published = 1
						ORDER BY a.name ASC');
		
		// echo('<pre>');
		// print_r($query->__toString());
		// echo('</pre>');
		// die();
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
//			$options[] = JHtml::_('select.option', '', 'Select Network');
			foreach($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->id, $item->ad_network_id_name);
			}
		}
		return $options;
	}
}
