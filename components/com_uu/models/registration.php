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

require_once (JPATH_ROOT .'/components/com_uu/helpers/mail.php');
require_once (JPATH_ROOT .'/components/com_uu/helpers/config.php');


/**
 * Methods supporting a list of Uu records.
 */
class UuModelRegistration extends JModelForm {

    /**
     * @var		object	The user registration data.
     * @since	1.6
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to get the registration form data.
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

            $this->data	= new stdClass();
            $app	= JFactory::getApplication();
            //we user users params go get the groups
            $params	= JComponentHelper::getParams('com_users');


            // Override the base user data with any data in the session.
            $temp = (array)$app->getUserState('com_uu.registration.data', array());
            foreach ($temp as $k => $v) {
                $this->data->$k = $v;
            }

            // Get the groups the user should be added to after registration.
            $this->data->groups = array();

            // Get the default new user group, Registered if not specified.
            $system	= $params->get('new_usertype', 2);

            $this->data->groups[] = $system;

            // Unset the passwords.
            unset($this->data->password1);
            unset($this->data->password2);

            // Get the dispatcher and load the users plugins.
            $dispatcher	= JDispatcher::getInstance();
            JPluginHelper::importPlugin('user');

            // Trigger the data preparation event.
            $results = $dispatcher->trigger('onContentPrepareData', array('com_uu.registration', $this->data));

            // Check for errors encountered while preparing the data.
            if (count($results) && in_array(false, $results, true)) {
                $this->setError($dispatcher->getError());
                $this->data = false;
            }
        }

        return $this->data;
    }

    /**
     * Method to get the registration form.
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
        $form = $this->loadForm('com_uu.registration', 'registration', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
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
        $userParams	= JComponentHelper::getParams('com_uu');

        //Add the choice for site language at registration time
        if ($userParams->get('site_language') == 1 && $userParams->get('frontend_userparams') == 1)
        {
            $form->loadFile('sitelang', false);
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
        $app	= JFactory::getApplication();
        $params	= $app->getParams('com_uu');

        // Load the parameters.
        $this->setState('params', $params);
    }



    //TODO use Helper Class or UltimateUser Class
    public function getRegistrationFields($filter) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__uu_fields'));

        if(! empty($filter))
        {
            foreach($filter as $column => $value)
            {
                //TODO on doit avoir (1,2) et pas ('1,2') protégéer la requête
                $query->where($db->quoteName($column).' IN ('.$value.')' );
            }
        }
        $query->order('ordering');


        $db->setQuery($query);

        $fields = $db->loadObjectList();

        $extraFields	= array();

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
          $extraField->params = $field->params;


          if ($extraField->type != 'group') {
            $extraField->html = UuModelRegistration::getFieldHTML($field);
          }
          $extraFields[] = $extraField;
        }

        return $extraFields;
    }

    static public function getFieldHTML( $field , $showRequired = '&nbsp; *' )
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

    /**
     * Method to save the form data.
     *
     * @param	array		The form data.
     * @return	mixed		The user id on success, false on failure.
     * @since	1.6
     */
    public function register($temp)
    {
        $config = JFactory::getConfig();
        $db		= $this->getDbo();
        $params = JComponentHelper::getParams('com_users');

        // Initialise the table with JUser.
        $user = new JUser;
        $data = (array)$this->getData();

        // Merge in the registration data.
        foreach ($temp as $k => $v) {
            $data[$k] = $v;
        }

        // Prepare the data for the user object.
        $data['email']		= $data['email1'];
        $data['password']	= $data['password1'];
        $useractivation = $params->get('useractivation');
        $sendpassword = $params->get('sendpassword', 1);

        // Check if the user needs to activate their account.
        if (($useractivation == 1) || ($useractivation == 2)) {
            $data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
            $data['block'] = 1;
        }
        // Bind the data.
        if (!$user->bind($data)) {
            $this->setError(JText::sprintf('COM_UU_REGISTRATION_BIND_FAILED', $user->getError()));
            return false;
        }
        // Load the users plugin group.
        JPluginHelper::importPlugin('user');

        //used to save a user
        JTable::addIncludePath(JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table');

        // Store the joomla user data.
        if (!$user->save()) {
            $this->setError(JText::sprintf('COM_UU_REGISTRATION_SAVE_FAILED', $user->getError()));
            return false;
        }

        // Store the uu data
        //get all published custom field for profile
        //TODO ne pas récupérer les groupes avec ce filtre
        $filter       = array('core'=>'0','published'=>'1,2', 'registration' => '1,2');
        $fields       =  $this->getRegistrationFields($filter);

        $columns = array('user_id','ip_address','accepted_terms');
        $values = array($db->quote($user->get('id')),$db->quote($data['ip_address']),$db->quote($data['accepted_terms']));

        $query = $db->getQuery(true);
        foreach ($fields as $field) {
            //TOTO le faire à la cécupation
            if ($field->type != 'group' && $field->type != 'captcha'){
                $columns[] = $field->fieldcode;
                //implode array values of custom fields
                if (isset($data[$field->fieldcode]) && is_array($data[$field->fieldcode])) {
                    $values[] = $db->quote(implode(',',$data[$field->fieldcode]));
                } else {
	                if ($field->type != 'date') {
		                $values[] = $db->quote($data[$field->fieldcode]);
	                } else {
		                $fieldDate = new FieldDate($field->id);
		                $date = $fieldDate->format($data[$field->fieldcode]);
		                $values[] = $db->quote($date);
	                }
                }
            }
        }


        $query->insert($db->quoteName('#__uu_users'))
              ->columns($db->quoteName($columns))
              ->values(implode(',', $values));
        $db->setQuery($query);
        $result = $db->query();

        // Compile the notification mail values.
        $data = $user->getProperties();
        $data['fromname']	= $config->get('fromname');
        $data['mailfrom']	= $config->get('mailfrom');
        $data['sitename']	= $config->get('sitename');
        $data['siteurl']	= JUri::root();


        $conf = new UuConfig();
        $mailhelper = new UMailHelper();

        $htmlmode = true;
        // Handle account activation/confirmation emails by admin
        if ($useractivation == 2)
        {
            // Set the link to confirm the user email.
            $uri = JURI::getInstance();
            $base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
            $data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);

            //'COM_USERS_EMAIL_ACCOUNT_DETAILS'
            $emailSubject = $mailhelper->renderMail($conf->get('admin_activation_mail_subject'),$data,$user);

            if ($sendpassword)
            {
               //COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY
               $emailBody = $mailhelper->renderMail($conf->get('admin_activation_mail_body'),$data,$user);
            }
            else
            {
                //COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY_NOPW
                $emailBody = $mailhelper->renderMail($conf->get('admin_activation_mail_body_nopw'),$data,$user);
            }
        }

        elseif ($useractivation == 1)
        {
            // Set the link to activate the user account.
            //$uri = JURI::getInstance();
            //$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
            //$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);


            $emailSubject = $mailhelper->renderMail($conf->get('activation_mail_subject'),$data,$user);

//            $emailSubject	= JText::sprintf(
//                'COM_USERS_EMAIL_ACCOUNT_DETAILS',
//                $data['name'],
//                $data['sitename']
//            );

            if ($sendpassword)
            {
                //COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY
                $emailBody = $mailhelper->renderMail($conf->get('activation_mail_body'),$data,$user);

            }
            else
            {
                //COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY_NOPW
                $emailBody = $mailhelper->renderMail($conf->get('activation_mail_body_nopw'),$data,$user);
            }
        }
        else
        {
            //COM_USERS_EMAIL_ACCOUNT_DETAILS
            $emailSubject = $mailhelper->renderMail($conf->get('registered_mail_subject'),$data,$user);

            //COM_USERS_EMAIL_REGISTERED_BODY
            $emailBody = $mailhelper->renderMail($conf->get('registered_mail_body'),$data,$user);
        }

        // Send the registration email.
        $return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody,$htmlmode);

        //load com_users language file
        //use to send mail from com_users to administator
        $lang =JFactory::getLanguage();
        $lang->load('com_users', JPATH_SITE);


        //Send Notification mail to administrators
        if (($params->get('useractivation') < 2) && ($params->get('mail_to_admin') == 1)) {
//            $emailSubject = JText::sprintf(
//                'COM_USERS_EMAIL_ACCOUNT_DETAILS',
//                $data['name'],
//                $data['sitename']
//            );

            //COM_USERS_EMAIL_ACCOUNT_DETAILS
            $emailSubject = $mailhelper->renderMail($conf->get('notification_to_admin_mail_subject'),$data,$user);

//            $emailBodyAdmin = JText::sprintf(
//                'COM_USERS_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY',
//                $data['name'],
//                $data['username'],
//                $data['siteurl']
//            );

            //COM_USERS_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY
            $emailBodyAdmin = $mailhelper->renderMail($conf->get('notification_to_admin_mail_body'),$data,$user);

            // get all admin users
            $query = 'SELECT name, email, sendEmail' .
                ' FROM #__users' .
                ' WHERE sendEmail=1';

            $db->setQuery( $query );
            $rows = $db->loadObjectList();

            // Send mail to all superadministrators id
            foreach( $rows as $row )
            {
                $return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $emailBodyAdmin,$htmlmode);

                // Check for an error.
                if ($return !== true) {
                    $this->setError(JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
                    return false;
                }
            }
        }
        // Check for an error.
        if ($return !== true) {
            //display default joomla user message
            $this->setError(JText::_('COM_USERS_REGISTRATION_SEND_MAIL_FAILED'));

            // Send a system message to administrators receiving system mails
            $db = JFactory::getDBO();
            $q = "SELECT id
				FROM #__users
				WHERE block = 0
				AND sendEmail = 1";
            $db->setQuery($q);
            $sendEmail = $db->loadColumn();
            if (count($sendEmail) > 0) {
                $jdate = new JDate();
                // Build the query to add the messages
                $q = "INSERT INTO ".$db->quoteName('#__messages')." (".$db->quoteName('user_id_from').
                    ", ".$db->quoteName('user_id_to').", ".$db->quoteName('date_time').
                    ", ".$db->quoteName('subject').", ".$db->quoteName('message').") VALUES ";
                $messages = array();

                foreach ($sendEmail as $userid) {
                    $messages[] = "(".$userid.", ".$userid.", '".$jdate->toSql()."', '".JText::_('COM_USERS_MAIL_SEND_FAILURE_SUBJECT')."', '".JText::sprintf('COM_USERS_MAIL_SEND_FAILURE_BODY', $return, $data['username'])."')";
                }
                $q .= implode(',', $messages);
                $db->setQuery($q);
                $db->query();
            }
            return false;
        }

        if ($useractivation == 1)
            return "useractivate";
        elseif ($useractivation == 2)
            return "adminactivate";
        else
            return $user->id;
    }

    /**
     * Method to validate the form data.
     *
     * @param   JForm   $form   The form to validate against.
     * @param   array   $data   The data to validate.
     * @param   string  $group  The name of the field group to validate.
     *
     * @return  mixed  Array of filtered data if valid, false otherwise.
     *
     * @see     JFormRule
     * @see     JFilterInput
     * @since   11.1
     */
    public function validate($form, $data, $group = null)
    {
        // Filter and validate the form data.

        //$data = $form->filter($data);
        //TODO make field validataion
        return $data;
        $return = $form->validate($data, $group);

        // Check for an error.
        if ($return instanceof Exception)
        {
            $this->setError($return->getMessage());
            return false;
        }

        // Check the validation results.
        if ($return === false)
        {
            // Get the validation messages from the form.
            foreach ($form->getErrors() as $message)
            {
                $this->setError(JText::_($message));
            }

            return false;
        }

        return $data;
    }

    /**
     * Method to activate a user account.
     *
     * @param	string		The activation token.
     * @return	mixed		False on failure, user object on success.
     * @since	1.6
     */
    public function activate($token)
    {
        $config	= JFactory::getConfig();
        $userParams	= JComponentHelper::getParams('com_users');
        $db		= $this->getDbo();

        // Get the user id based on the token.
        $db->setQuery(
            'SELECT '.$db->quoteName('id').' FROM '.$db->quoteName('#__users') .
                ' WHERE '.$db->quoteName('activation').' = '.$db->Quote($token) .
                ' AND '.$db->quoteName('block').' = 1' .
                ' AND '.$db->quoteName('lastvisitDate').' = '.$db->Quote($db->getNullDate())
        );
        $userId = (int) $db->loadResult();

        // Check for a valid user id.
        if (!$userId) {
            $this->setError(JText::_('COM_USERS_ACTIVATION_TOKEN_NOT_FOUND'));
            return false;
        }

        // Load the users plugin group.
        JPluginHelper::importPlugin('user');

        //used to load a user table
        JTable::addIncludePath(JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table');

        // Activate the user.
        $user = JFactory::getUser($userId);

        //s:v1.4.3
        //load UltimateUser data
        //TODO: woraround for mail but must override user creation to load directly extra fields.
        JLoader::import('joomla.application.component.model');
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_uu/models', 'UuModel');
        $user_model = JModelLegacy::getInstance( 'User', 'UuModel' );
        $user2 = $user_model->loadUserById($userId);

        //copy custom fields in user
        $customFields = $user_model->getCustomFields();
        foreach ($customFields as $customField){
            $user->set($customField->fieldcode,$user2->{$customField->fieldcode});
        }
        //fin load
        //e:v1.4.3

        //load com_users language file
        //use to send mail from com_users to administator
        $lang =JFactory::getLanguage();
        $lang->load('com_users', JPATH_SITE);

        // Admin activation is on and user is verifying their email
        if (($userParams->get('useractivation') == 2) && !$user->getParam('activate', 0))
        {
            $uri = JURI::getInstance();

            // Compile the admin notification mail values.
            $data = $user->getProperties();
            $data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
            $user->set('activation', $data['activation']);
            $data['siteurl']	= JUri::base();
            $base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
            $data['activate'] = $base.JRoute::_('index.php?option=com_uu&task=users.activate&token='.$data['activation'], false);
            $data['fromname'] = $config->get('fromname');
            $data['mailfrom'] = $config->get('mailfrom');
            $data['sitename'] = $config->get('sitename');

            $user->setParam('activate', 1);
//            $emailSubject	= JText::sprintf(
//                'COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_SUBJECT',
//                $data['name'],
//                $data['sitename']
//            );
//
//            $emailBody = JText::sprintf(
//                'COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_BODY',
//                $data['sitename'],
//                $data['name'],
//                $data['email'],
//                $data['username'],
//                $data['siteurl'].'index.php?option=com_uu&task=users.activate&token='.$data['activation']
//            );

            $conf = new UuConfig();
            $mailhelper = new UMailHelper();
            $htmlmode = true;


            //COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT
            $emailSubject = $mailhelper->renderMail($conf->get('activate_with_admin_activation_mail_subject'),$data,$user);

            //COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY
            $emailBody = $mailhelper->renderMail($conf->get('activate_with_admin_activation_mail_body'),$data,$user);


            // get all admin users
            $query = 'SELECT name, email, sendEmail, id' .
                ' FROM #__users' .
                ' WHERE sendEmail=1';

            $db->setQuery( $query );
            $rows = $db->loadObjectList();

            // Send mail to all users with users creating permissions and receiving system emails
            foreach( $rows as $row )
            {
                $usercreator = JFactory::getUser($id = $row->id);
                if ($usercreator->authorise('core.create', 'com_users'))
                {
                    $return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $emailBody,$htmlmode);
                    //$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $emailBody);

                    // Check for an error.
                    if ($return !== true) {
                        $this->setError(JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
                        return false;
                    }
                }
            }
        }

        //Admin activation is on and admin is activating the account
        elseif (($userParams->get('useractivation') == 2) && $user->getParam('activate', 0))
        {
            $user->set('activation', '');
            $user->set('block', '0');

            $uri = JURI::getInstance();

            // Compile the user activated notification mail values.
            $data = $user->getProperties();
            $user->setParam('activate', 0);
            $data['fromname'] = $config->get('fromname');
            $data['mailfrom'] = $config->get('mailfrom');
            $data['sitename'] = $config->get('sitename');
            $data['siteurl']	= JUri::base();

            $conf = new UuConfig();
            $mailhelper = new UMailHelper();
            $htmlmode = true;

            //COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT
            $emailSubject = $mailhelper->renderMail($conf->get('activate_by_admin_activation_mail_subject'),$data,$user);

            //COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY
            $emailBody = $mailhelper->renderMail($conf->get('activate_by_admin_activation_mail_body'),$data,$user);

            $return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody,$htmlmode);

            // Check for an error.
            if ($return !== true) {
                $this->setError(JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
                return false;
            }
        }
        else
        {
            $user->set('activation', '');
            $user->set('block', '0');
        }

        // Store the user object.
        if (!$user->save()) {
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_ACTIVATION_SAVE_FAILED', $user->getError()));
            return false;
        }

        return $user;
    }

    public function isUserNameExists($filter = array()){
        $db			= $this->getDBO();
        $found		= false;

        $query = $db->getQuery(true);
        $query->select($db->quoteName('username'))
              ->from($db->quoteName('#__users'))
              ->where(' UCASE('.$db->quoteName('username').') = UCASE('.$db->Quote($filter['username']).')');

        $db->setQuery( $query );
        if($db->getErrorNum()) {
            JError::raiseError( 500, $db->stderr());
        }
        $result = $db->loadObjectList();
        $found = (count($result) == 0) ? false : true;

        return $found;

    }

    /*
    * Method to check for exsisting email registered
    * remove the id fo user (due to update profile)
    */
    public function isEmailExists($filter = array()){
        $db			= $this->getDBO();
        $found		= false;

        $query = $db->getQuery(true);
        $query->select($db->quoteName('email'))
            ->from($db->quoteName('#__users'))
            ->where(' UCASE('.$db->quoteName('email').') = UCASE('.$db->Quote($filter['email']).')')
            ->where(' id != '.$db->quote($filter['userid']));

        $db->setQuery( $query );
        if($db->getErrorNum()) {
            JError::raiseError( 500, $db->stderr());
        }
        $result = $db->loadObjectList();
        $found = (count($result) == 0) ? false : true;

        return $found;

    }

}
