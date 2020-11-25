<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');


class UuModelConfiguration extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_UU';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	$record	A record object.
	 *
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		return $user->authorise('core.delete', 'com_uu.configuration.'.(int) $record->id);
	}


	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Configuration', $prefix = 'UuTable', $options = array())
	{

		return JTable::getInstance($type, $prefix, $options);

	}


	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_uu.configuration', 'configuration', array('control' => 'jform', 'load_data' => $loadData));
                if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the configuration data.
	 *
	 * This method will load the global configuration data straight from
	 * JConfig. If configuration data has been saved in the session, that
	 * data will be merged into the original data, overwriting it.
	 *
	 * @return	array		An array containg all global config data.
	 * @since	1.6
	 */
	public function getData()
	{
                $db = JFactory::getDBO();
                $query	= $db->getQuery(true);
                $query->select('a.`key`, a.`value`');
                $query->from('#__uu_configuration as a');
                $db->setQuery($query);
                
                 $items = $db->loadObjectList();
                 $data = array();

                 foreach ($items as $item) {
                        $data[$item->key] = $item->value;
                } 
		return JArrayHelper::toObject($data, 'JObject');
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_uu.edit.configuration.data', array());

		if (empty($data)) {
			$data = $this->getData();
		}
		return $data;
	}

	/**
	 * Method to save the configuration data.
	 *
	 * @param	array	An array containing all global config data.
	 * @return	bool	True on success, false on failure.
	 * @since	1.6
	 */
    //TODO vérifier le besoin de cette méthode
	public function save($data)
	{

            $db = JFactory::getDBO();
            foreach ($data as $key => $value) {
                $query = 'UPDATE '.$db->quoteName('#__uu_configuration').' set `value` = '.$db->Quote($value).' WHERE `key`=' .$db->Quote($key);
                $db->setQuery($query);
                if (!$db->query()) {
                        $this->setError($db->getErrorMsg());
                        return false;
                }
            }
        }
}