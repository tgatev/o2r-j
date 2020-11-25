<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Uu model.
 */
class UuModelField extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_UU';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Field', $prefix = 'UuTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_uu.field', 'field', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

    public function preprocessForm(JForm $form,$data,$group='default')
    {

        //select the right xml file to load, group or custom
        //for field the layout is edit
        $input = JFactory::getApplication()->input;
        $layout = $input->getString('layout','custom');
        if ($layout == 'group'){
            $type = 'group';
        } else {
            $type = 'custom';
        }

        // Load the specific type file
        if (!$form->loadFile('field_'.$type, true, false)) {
            throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
        }

        parent::preprocessForm($form,$data,$group);
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
		$data = JFactory::getApplication()->getUserState('com_uu.edit.field.data', array());

		if (empty($data)) {
			$data = $this->getItem();
            
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			//Do any procesing on fields here if needed
		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__uu_fields');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}


    public function save($data) {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $id	= (!empty($data['id'])) ? $data['id'] : (int)$this->getState('field.id');
        $isNew	= true;

        // Get a row instance.
        $table = $this->getTable();

        // Load the row if saving an existing item.
        if ($id > 0) {
            $table->load($id);
            $isNew = false;
        }


        if ($isNew) {
            if (isset($data['type']) && $data['type'] != 'group' && $data['type'] != 'captcha') {
                //set fieldcode with cf_+fieldcode like text select....
                //2014/01/02 fix bug on field code

                $data['fieldcode'] = "cf_".UuModelField::stringURLSafe($data['fieldcode']);
                $fieldcode = $data['fieldcode'];

                //check if this field code already exist
                if (in_array($fieldcode,array_keys($db->getTableColumns('#__uu_users',true)))) {
                    $this->setError(JText::_('COM_UU_FIELD_EXIST'));
                    //nicer display with setError, the error msg is after the common text
                    //JFactory::getApplication()->enqueueMessage(JText::_('COM_UU_FIELD_EXIST'), 'error');
                    return false;
                }

               require_once (JPATH_ROOT .'/components/com_uu/libraries/fields/'. $data['type'] .'.php');
               $className  = 'Field'.ucfirst($data['type']);
               $customfield = new $className();
               $sqltype = $customfield->getSqlType();

               $db->setQuery("ALTER TABLE #__uu_users ADD COLUMN ".$db->quoteName($fieldcode). " ".$sqltype);
               $db->query();
            }
        } else {
           //unset
           unset($data['fieldcode']);

        }

        //only one captcha
        if ( $isNew && $data['type'] == 'captcha') {

            // Initialise variables.
            jimport('joomla.application.component.model');
            JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_uu/models');
            $model = JModelLegacy::getInstance( 'Registration', 'UuModel' );

            $filter       = array('type'=>"'captcha'");
            $fields = $model->getRegistrationFields($filter);

            if (isset($fields) && count($fields) >= 1) {
                $application = JFactory::getApplication();
                $application->enqueueMessage(JText::_('COM_UU_FIELDS_CAPTCHA_ONLY_ONE'), 'error');
                return false;
            }

            //set specific value lock edition
            $data['required'] = 2;
            $data['registration'] = 2;
            $data{'editable'}=2;
        }


        // Bind the data.
        if (!$table->bind($data)) {
            $this->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $this->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        }

        $this->setState('field.id', $table->id);

        $pk= $table->id;

        // Clean the cache
        $this->cleanCache();

        $fieldValues = JRequest::getVar('field_values', array(), '', 'array' );

        //delete all previous values for this field
        $query->clear();

        $query->delete();
        $query->from($db->quoteName('#__uu_fields_values'));
        $query->where('id_field ='.(int)$pk);
        $db->setQuery($query);
        $db->query();

        if(!empty($fieldValues)){
            foreach($fieldValues['title'] as $i => $title){
                if(strlen($title)<1 AND strlen($fieldValues['value'][$i])<1) continue;
                $fieldValue = new stdClass();
                $fieldValue->id_field = $pk;
                $fieldValue->value = strlen($fieldValues['value'][$i])<1 ? $title : $fieldValues['value'][$i];
                $fieldValue->title = $title;
                $fieldValue->published = strlen($fieldValues['published'][$i])<1 ? '0' : $fieldValues['published'][$i];
	            $fieldValue->ordering = strlen($fieldValues['ordering'][$i])<1 ? '0' : $fieldValues['ordering'][$i];


                $db->insertObject('#__uu_fields_values', $fieldValue);
            }
        }

        return true;

    }

    //like /libraries/joomla/filter/output.php stringURLSafe method
    public static function stringURLSafe($string)
    {
        // Remove any '-' from the string since they will be used as concatenaters
        $str = str_replace('_', ' ', $string);

        // Trim white spaces at beginning and end of alias and make lowercase
        $str = trim(JString::strtolower($str));

        // Remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace('/(\s|[^A-Za-z0-9\-])+/', '_', $str);

        // Trim dashes at beginning and end of alias
        $str = trim($str, '-_');

        return $str;
    }


    public function getValues(){
        $db = JFactory::getDbo();

        $item = $this->getItem();

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__uu_fields_values');
        $query->where('id_field = '. (int)$item->id);
        $query->order('ordering,id ASC');
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function toggle($ids, $state,$field_name) {

        $query = $this->_db->getQuery(true);
        $query->update('#__uu_fields');
        $query->set($this->_db->quoteName($field_name).' = ' . (int) $state);
        // Build the WHERE clause for the primary keys.
        $query->where('id = ' . implode(' OR id = ', $ids));
        $this->_db->setQuery($query);

        // Check for a database error.
        if (!$this->_db->execute())
        {
            $e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_PUBLISH_FAILED', get_class($this), $this->_db->getErrorMsg()));
            $this->setError($e);

            return false;
        }
        return true;
    }

    public function delete(&$cids) {
        $cids = (array) $cids;
        $table = $this->getTable();

        //TODO make it with a single query ??
        // Iterate the items to check if there are core fields.
        foreach ($cids as $i => $cid)
        {
            if ($table->load($cid))
            {
                //can't delete core field
                if ($table->core == 1) {
                    JError::raiseWarning(403, JText::_('COM_UU_FIELDS_CANT_DELETE_CORE_FIELDS'));
                    return false;
                }

            }
        }

        parent::delete($cids);

    }

}