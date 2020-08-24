<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Users list controller class.
 */
class UuControllerUsers extends UuController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Users', $prefix = 'UuModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

    /**
     * Method to activate a user from com_users /components/com_users/controllers/registration.php
     *
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function activate()
    {
        $user		= JFactory::getUser();
        $uParams	= JComponentHelper::getParams('com_users');

        //load message from com_users
        $lang =JFactory::getLanguage();
        $lang->load('com_users', JPATH_SITE);


        // If the user is logged in, return them back to the homepage.
        if ($user->get('id')) {
            $this->setRedirect('index.php');
            return true;
        }


        // If user registration or account activation is disabled, throw a 403.
        if ($uParams->get('useractivation') == 0 || $uParams->get('allowUserRegistration') == 0) {
            JError::raiseError(403, JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
            return false;
        }

        $model = $this->getModel('Registration', 'UuModel');

        $input=JFactory::getApplication()->input;
        $token = $input->getAlnum('token', null);

        // Check that the token is in a valid format.
        if ($token === null || strlen($token) !== 32) {
            JError::raiseError(403, JText::_('JINVALID_TOKEN'));
            return false;
        }
        // Attempt to activate the user.
        $return = $model->activate($token);

        // Check for errors.
        if ($return === false) {
            // Redirect back to the homepage.
            $this->setMessage(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $model->getError()), 'warning');
            $this->setRedirect('index.php');
            return false;
        }

        $useractivation = $uParams->get('useractivation');

        // Redirect to the login screen.
        if ($useractivation == 0)
        {
            $this->setMessage(JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS'));
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
        }
        elseif ($useractivation == 1)
        {
            $this->setMessage(JText::_('COM_USERS_REGISTRATION_ACTIVATE_SUCCESS'));
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
        }
        elseif ($return->getParam('activate'))
        {
            $this->setMessage(JText::_('COM_USERS_REGISTRATION_VERIFY_SUCCESS'));
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
        }
        else
        {
            $this->setMessage(JText::_('COM_USERS_REGISTRATION_ADMINACTIVATE_SUCCESS'));
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
        }
        return true;
    }


}