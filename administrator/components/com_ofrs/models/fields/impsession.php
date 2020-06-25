<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.27
	@build			5th February, 2020
	@created		5th July, 2019
	@package		Offer Monster
	@subpackage		impsession.php
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
 * Impsession Form Field class for the Ofrs component
 */
class JFormFieldImpsession extends JFormFieldList
{
	/**
	 * The impsession field type.
	 *
	 * @var		string
	 */
	public $type = 'impsession';

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
		$query->select($db->quoteName(array('a.id','a.ad_network_id'),array('id','imp_session_id_ad_network_id')));
		$query->from($db->quoteName('#__ofrs_imp_session', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		$query->order('a.ad_network_id ASC');
		// Implement View Level Access (if set in table)
		if (!$user->authorise('core.options', 'com_ofrs'))
		{
			$columns = $db->getTableColumns('#__ofrs_imp_session');
			if(isset($columns['access']))
			{
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');
			}
		}
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			$options[] = JHtml::_('select.option', '', 'Select an option');
			foreach($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->id, $item->imp_session_id_ad_network_id);
			}
		}
		return $options;
	}
}
