<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2015. All rights reserved.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

/**
 * Profile model class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class UuModelProfile extends JModelForm
{
	/**
	 * @var		object	The user profile data.
	 * @since	1.6
	 */
	protected $data;

	/**
	 * Method to check in a user.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkin($userId = null)
	{
		// Get the user id.
		$userId = (!empty($userId)) ? $userId : (int)$this->getState('user.id');

        //used to save a user
        JTable::addIncludePath(JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table');

		if ($userId) {
			// Initialise the table with JUser.
			$table = JTable::getInstance('User');

			// Attempt to check the row in.
			if (!$table->checkin($userId)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to check out a user for editing.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkout($userId = null)
	{
		// Get the user id.
		$userId = (!empty($userId)) ? $userId : (int)$this->getState('user.id');

        //used to save a user
        JTable::addIncludePath(JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table');

		if ($userId) {
			// Initialise the table with JUser.
			$table = JTable::getInstance('User');

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (!$table->checkout($user->get('id'), $userId)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to get the profile form data.
	 *
	 * The base form data is loaded and then an event is fired
	 * for users plugins to extend the data.
	 *
	 * @return	mixed		Data object on success, false on failure.
	 * @since	1.6
	 */
	public function getData()
	{
		if ($this->data === null) {

			$userId = $this->getState('user.id');

            //used to save a user
            JTable::addIncludePath(JPATH_ROOT.'/libraries/joomla/database/table');

            // Initialise the table with JUser.
			$this->data	= new JUser($userId);

            //TODO changer de manière de faire new Uusers();
            //load custom values
            require_once(JPATH_ADMINISTRATOR.'/components/com_uu/models/user.php');
            $usermodel = new UuModelUser();
            $Uuser = $usermodel->loadUserById($userId);
            //set extrafields
            foreach($Uuser as $field => $value){
                if (substr($field,0,3) == 'cf_'){
                  $this->data->$field = $value;
                }
            }

			// Set the base user data.
			$this->data->email1 = $this->data->get('email');
			$this->data->email2 = $this->data->get('email');

			// Override the base user data with any data in the session.
			$temp = (array)JFactory::getApplication()->getUserState('com_uu.edit.profile.data', array());
			foreach ($temp as $k => $v) {
				$this->data->$k = $v;
			}

			// Unset the passwords.
			unset($this->data->password1);
			unset($this->data->password2);

			$registry = new JRegistry($this->data->params);
			$this->data->params = $registry->toArray();

			// Get the dispatcher and load the users plugins.
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('user');

			// Trigger the data preparation event.
			$results = $dispatcher->trigger('onContentPrepareData', array('com_uu.profile', $this->data));

			// Check for errors encountered while preparing the data.
			if (count($results) && in_array(false, $results, true)) {
				$this->setError($dispatcher->getError());
				$this->data = false;
			}
		}
		return $this->data;
	}

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_uu.profile', 'profile', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

        //add custom fields
        //TODO ne pas récupérer les groupes
//        $filter       = array('core'=>'0','published'=>'1,2', 'registration' => '1,2');
//        //TODO use Helper Class or UltimateUser Class
//        $fields       =  $this->getProfileFields($filter);
//
//        foreach ($fields as $field) {
//            //TODO doit être fait avant pour les groues
//            if ($field->type != 'group'){
//                $form->setFieldAttribute($field->fieldcode, 'class', '');
//            }
//        }

		if (!JComponentHelper::getParams('com_users')->get('change_login_name'))
		{
			$form->setFieldAttribute('username', 'class', '');
			$form->setFieldAttribute('username', 'filter', '');
			$form->setFieldAttribute('username', 'description', 'COM_USERS_PROFILE_NOCHANGE_USERNAME_DESC');
			$form->setFieldAttribute('username', 'validate', '');
			$form->setFieldAttribute('username', 'message', '');
			$form->setFieldAttribute('username', 'readonly', 'true');
			$form->setFieldAttribute('username', 'required', 'false');
		}

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
		return $this->getData();
	}

	/**
	 * Override preprocessForm to load the user plugin group instead of content.
	 *
	 * @param	object	A form object.
	 * @param	mixed	The data expected for the form.
	 * @throws	Exception if there is an error in the form event.
	 * @since	1.6
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		if (JComponentHelper::getParams('com_users')->get('frontend_userparams'))
		{
			$form->loadFile('frontend', false);
			if (JFactory::getUser()->authorise('core.login.admin')) {
				$form->loadFile('frontend_admin', false);
			}
		}

		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		// Get the application object.
		$params	= JFactory::getApplication()->getParams('com_users');

		// Get the user id.
		$userId = JFactory::getApplication()->getUserState('com_users.edit.profile.id');
		$userId = !empty($userId) ? $userId : (int)JFactory::getUser()->get('id');

		// Set the user id.
		$this->setState('user.id', $userId);

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data)
	{
        $db = JFactory::getDbo();
		$userId = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('user.id');

		$user = new JUser($userId);

		// Prepare the data for the user object.
		$data['email']		= $data['email1'];
		$data['password']	= $data['password1'];

		// Unset the username so it does not get overwritten
		unset($data['username']);

		// Unset the block so it does not get overwritten
		unset($data['block']);

		// Unset the sendEmail so it does not get overwritten
		unset($data['sendEmail']);

		// Bind the data.
		if (!$user->bind($data)) {
			$this->setError(JText::sprintf('USERS PROFILE BIND FAILED', $user->getError()));
			return false;
		}

		// Load the users plugin group.
		JPluginHelper::importPlugin('user');

		// Null the user groups so they don't get overwritten
		$user->groups = null;

		// Store the data.
		if (!$user->save()) {
			$this->setError($user->getError());
			return false;
		}

        // Store the uu data
        //get all published custom field for profile and editable
        //TODO ne pas récupérer les groupes avec ce filtre
        $filter       = array('core'=>'0','published'=>'1,2', 'editable' => '1,2');
        $fields       =  $this->getProfileFields($filter);

        $query = $db->getQuery(true);
		$needUpdate = false;
        foreach ($fields as $field) {
            //TOTO le faire à la cécupation
            if ($field->type != 'group'){
                //implode array values of custom fields
                if (isset($data[$field->fieldcode]) && is_array($data[$field->fieldcode])) {
                    $query->set($field->fieldcode.' = '.$db->quote(implode(',',$data[$field->fieldcode])));
                } else {
                    //if field type date with need to format it to the right database type
                    if ($field->type != 'date') {
                        $query->set($field->fieldcode . ' = ' . $db->quote($data[$field->fieldcode]));
                    } else {
                        //TODO Probably load for all data the refering field and apply a format on it.
                        //format the date to the sql format
                        $fieldDate = new FieldDate($field->id);
                        $date = $fieldDate->format($data[$field->fieldcode]);
                        $query->set($field->fieldcode . ' = ' . $db->quote($date));
                    }
                }
				$needUpdate = true;
            }
        }

		//we update only if there are somthing to update : bug in other case
		if ($needUpdate) {
			$query->update($db->quoteName('#__uu_users'))
				->where('user_id = ' . $db->quote($user->get('id')));
			$db->setQuery($query);
			$result = $db->query();
		}

		return $user->id;
	}


    //TODO avoir une seule méthode entre la registration et le profile et le back end use UuSiteHelper

    public function getProfileFields($filter = null,$readonly = false) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__uu_fields'));


        if(! empty($filter))
        {
            foreach($filter as $column => $value)
            {
                $query->where($db->quoteName($column).' IN ('.$value.')' );
            }
        }

		//remove capatcha field in view and edit profile : not editable field
		$query->where($db->quoteName('type').' != '.$db->Quote('captcha') );

        $query->order('ordering');

        $db->setQuery($query);

        $fields = $db->loadObjectList();

        $extraFields	= array();

        jimport('joomla.application.component.helper');
        $params = JComponentHelper::getParams('com_users');


        require_once(JPATH_SITE.'/components/com_uu/models/registration.php');
        foreach ($fields as $field) {

            $extraField	= new stdClass();
            $extraField->id = $field->id;
            $extraField->type = $field->type;
            $extraField->name = $field->name;
            $extraField->fieldcode = $field->fieldcode;
            $extraField->description = $field->description;
            $extraField->core = $field->core;
            $extraField->required = $field->required;
            $extraField->editable = $field->editable;

            if ($field->fieldcode == 'username' && $params->get('change_login_name') == 0 ) {
                //set field not editable even if $readlonly is false
                $extraField->editable = 0;
            }

            //set value to the field to get the html with the value
            $field->value = $this->data->get($field->fieldcode);


            //set field as readonly if asking readonly or if set as not editable
            if (($extraField->editable == 0 || $readonly) && isset($field->params)) {
                $registry = new JRegistry();
                $registry->loadString($field->params);
                $registry->set('readonly', true);
                $field->params = $registry->toString();
            }

            if ($extraField->type != 'group') {
                $extraField->html = UuModelRegistration::getFieldHTML($field);
            }
            $extraFields[] = $extraField;
        }

        return $extraFields;
    }

    public function validate($form, $data, $group = null)
    {
        //TODO make field validataion
        //il faut ajouter les fields a la form
        return $data;
    }
}
