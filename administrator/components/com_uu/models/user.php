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
jimport('joomla.access.access');

/**
 * Uu model.
 */
class UuModelUser extends JModelAdmin
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
	public function getTable($type = 'User', $prefix = 'UuTable', $config = array())
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
		$form = $this->loadForm('com_uu.user', 'user', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

        //Add the profile fields to the form.
//        JForm::addFormPath(JPATH_ADMINISTRATOR.'/components/com_users/models/forms');
//        $result = $form->loadFile('user', false);

        //TODO add custom fields to this form here injecting FormField



		return $form;
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
		$data = JFactory::getApplication()->getUserState('com_uu.edit.user.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

        if ($data->user_id > 0 ) {
            //get com_users fields and inject them in $data
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')->from('#__users')->where('id ='.$data->user_id);

            $db->setQuery($query);
            $result = $db->loadObject();

            //TODO Make this methode with a loop
            $data->id = $result->id;
            $data->name = $result->name;
            $data->username = $result->username;
            $data->email = $result->email;
            //$data->usertype = $result->usertype;@deprecated
            $data->block = $result->block;
            $data->sendEmail = $result->sendEmail;
            $data->registerDate = $result->registerDate;
            $data->lastvisitDate = $result->lastvisitDate;
            $data->activation = $result->activation;
            // Load the JSON string
            $params = new JRegistry;
            $params->loadString($result->params,'JSON');
            $data->params = $params->toArray();
            $data->lastResetTime = $result->lastResetTime;
            $data->resetCount = $result->lastResetTime;
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
				$db->setQuery('SELECT MAX(ordering) FROM #__uu_users');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}

    public function preprocessForm(JForm $form,$data,$group='user_details')
    {
        //Add the profile fields to the form.
        JForm::addFormPath(JPATH_ADMINISTRATOR.'/components/com_users/models/forms');
        $result = $form->loadFile('user', false);

        $filter  = array('core'=>'0','published'=>'1');
        $customfields = $this->getCustomFields($filter);
        //add all custom fields to the form object
        $customfieldxml = '<form><fieldset >';
        foreach ($customfields as $field) {
            $customfieldxml .= '<field name="'.$field->fieldcode.'" type="'.$field->type.'" label="'.$field->name.'" description="'.$field->description.'"/>';
        }
        $customfieldxml .='</fieldset></form>';

        $form->load($customfieldxml);
        parent::preprocessForm($form,$data,$group);
    }

    public function getCustomFields($filter = array(),$readonly = false) {
        $filter  = array('core'=>'0','published'=>'1');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__uu_fields'));

        if(! empty($filter))
        {
            foreach($filter as $column => $value)
            {
                $query->where($db->quoteName($column).' = '.$db->Quote($value) );
            }
        }

        //remove captcha : not editable field
        $query->where($db->quoteName('type').' != '.$db->Quote('captcha') );

        //remove group
        $query->where($db->quoteName('type').' != '.$db->Quote('group') );

        $query->order('ordering');

        $db->setQuery($query);

        $fields = $db->loadObjectList();

        $item = $this->getItem();

        $extraFields	= array();

        //TOOD add recursivly all field.
        foreach ($fields as $field) {
            $extraField	= new stdClass();
            $extraField->id = $field->id;
            $extraField->type = $field->type;
            $extraField->name = $field->name;
            $extraField->fieldcode = $field->fieldcode;
            $extraField->description = $field->description;
            $extraField->required = $field->required;

            //set value
            $code = $field->fieldcode;
            $field->value = $item->$code;

            if ($extraField->type != 'group') {
                $extraField->html = self::getFieldHTML($field);
            }
            $extraFields[] = $extraField;
        }

        return $extraFields;
    }

    protected function getFieldHTML( $field , $showRequired = '&nbsp; *' )
    {
        $fieldType	= strtolower( $field->type);

        if(is_array($field))
        {
            jimport( 'joomla.utilities.arrayhelper');
            $field = JArrayHelper::toObject($field);
        }

        $class	= 'Field' . ucfirst( $fieldType );

        if(isset($field->options) && is_object($field->options))
        {
            $field->options = JArrayHelper::fromObject($field->options);
        }

        // Clean the options
        if( !empty( $field->options ) && !is_array( $field->options ) )
        {
            array_walk( $field->options , array( 'JString' , 'trim' ) );
        }

        // Escape the field name
        $field->name	= UStringHelper::escape($field->name);


        if( !isset($field->value) )
        {
            $field->value	= '';
        }
        // max value
        if (isset($field->params)) {
            $params = json_decode($field->params);
            if (isset($params->max_char) && $params->max_char != null) {
                $field->max = $params->max_char;
            }
        }

        $classPath = JPATH_ROOT . '/components/com_uu/libraries/fields/'. $field->type.'.php';
        jimport('joomla.filesystem.file');
        if( JFile::exists($classPath) )
        {
            require_once ($classPath);
        }

        if( class_exists( $class ) )
        {
            $object	= new $class($field->id);

            if( method_exists( $object, 'getFieldHTML' ) )
            {
                $html	= $object->getFieldHTML( $field , $showRequired );
                return $html;
            }
        }
        return JText::sprintf('COM_UU_UNKNOWN_USER_FIELD_TYPE' , $class , $fieldType );


    }


    public function save($data)
    {
        //get extra fields
        $filter  = array('core'=>'0','published'=>'1');
        $customfields = $this->getCustomFields($filter);
        //add all custom fields to the form object
        foreach ($customfields as $field) {
            //bind extra fields of type array to string representation
            if (isset($data[$field->fieldcode])) {
                if (is_array($data[$field->fieldcode])) {
                    $data[$field->fieldcode] = implode(',', $data[$field->fieldcode]);
                }
                if ($field->type == 'date') {
                    //TODO Probably load for all data the refering field and apply a format on it.
                    //format the date to the sql format
                    $fieldDate = new FieldDate($field->id);
                    $date = $fieldDate->format($data[$field->fieldcode]);
                    $data[$field->fieldcode] = $date;
                }

            } else {
              $data[$field->fieldcode] = '';
            }
        }
        //save ultimate user

        parent::save($data);

        return true;

    }

    //copy from com_users user model
    public function getGroups()
    {
        $user = JFactory::getUser();
        if ($user->authorise('core.edit', 'com_users') && $user->authorise('core.manage', 'com_users'))
        {
            require_once(JPATH_ADMINISTRATOR.'/components/com_users/models/groups.php');
            //TODO Jloader don't work on www2 production server why ???
            //JLoader::import('joomla.application.component.model');
            //JLoader::import( 'Groups', JPATH_ADMINISTRATOR.'/components/com_users/models' );
            $model = JModelLegacy::getInstance('Groups', 'UsersModel', array('ignore_request' => true));
            //used to save a user
            JTable::addIncludePath(JPATH_ROOT.'/libraries/joomla/database/table');

            return $model->getItems();
        }
        else
        {
            return null;
        }
    }

    //copy from com_users user model
    public function getAssignedGroups($userId = null)
    {
        // Initialise variables.
        $userId = (!empty($userId)) ? $userId : (int)$this->getState('user.id');

        if (empty($userId))
        {
            $result = array();
            $config = JComponentHelper::getParams('com_users');
            if ($groupId = $config->get('new_usertype'))
            {
                $result[] = $groupId;
            }
        }
        else
        {
            $result = JUserHelper::getUserGroups($userId);
        }

        return $result;
    }

    public function loadUserById($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('u.*,uu.*')
              ->from('#__users as u')
              ->join('LEFT','#__uu_users as uu ON u.id=uu.user_id')
              ->where('u.'.$db->quoteName('id').' = '.(int)$id . ' limit 1');
        $db->setQuery($query);

        return  $db->loadObject();
    }

}