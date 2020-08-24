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
class UuControllerRegistration extends UuController
{
    /**
     * @var		object	The user registration data.
     * @since	1.6
     */
    protected $data;

    /**
     * Method to register a user.
     *
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function register()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // If registration is disabled - Redirect to login page.
        if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
            $this->setRedirect(JRoute::_('index.php?option=com_uu&view=login', false));
            return false;
        }

        // Initialise variables.
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Registration', 'UuModel');

        //get captcha key and version
        //1.8.3 only published captcha fiel must be loaded
        $filter       = array('type'=>'"captcha"','published'=>'1');
        $captchaFields = $model->getRegistrationFields($filter);

        $input = JFactory::getApplication()->input;

        // Get the user data.
        $requestData = $input->post->get('jform', array(), 'array');


        if (!empty($captchaFields)) {
            $version = '1.0';
            foreach ($captchaFields as $field) {
                require_once(JPATH_ROOT . '/components/com_uu/libraries/parameter.php');
                $captcha_params = new UuParameter($field->params);
                $recaptcha_public = $captcha_params->get('recaptcha_public');
                $recaptcha_private = $captcha_params->get('recaptcha_private');
                $version = $captcha_params->get('version');
            }

            switch ($version) {
                case '1.0':
                    //check captcha
                    $challenge = JRequest::getVar('recaptcha_challenge_field', null);
                    break;
                case '2.0':
                    $challenge = $input->get('g-recaptcha-response', '', 'string');
                    break;
            }

            if (isset($challenge)) {
                //get captcha key

                $user = JFactory::getUser();
                $solved = $user->get($challenge, 0);
                if ($solved) {
                    $user->set($challenge, null);
                } else {
                    // get a reCAPTCHA object
                    require_once(JPATH_COMPONENT . '/libraries/captcha/recaptcha.php');
                    $recaptcha = JXRecaptcha::getInstance();

                    // set the API keys for reCAPTCHA
                    $recaptcha->setKeyPair($recaptcha_public, $recaptcha_private);

                    // validate the captcha
                    if (!$recaptcha->checkCaptcha()) {
                        JError::raiseWarning(403, JText::_('COM_UU_RECAPTCHA_CHECK_FAILED'));
                        $this->setRedirect(JRoute::_('index.php?option=com_users&view=registration', false));
                        return false;
                    } else {
                        //unset captcha input
                    }
                }
            }
        }

        //if use mail as username set it in the username request data
        $conf = new UuConfig();
        if ($conf->get('email_as_username')) {
            $requestData['username'] = $requestData['email1'];
        }


        // Validate the posted data.
        $form	= $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }


        $data	= $model->validate($form, $requestData);

        // Check for validation errors.
        if ($data === false) {
            // Get the validation messages.
            $errors	= $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_uu.registration.data', $requestData);

            // Redirect back to the registration screen.
            $this->setRedirect(JRoute::_('index.php?option=com_uu&view=registration', false));
            return false;
        }

        // since 1.8.7 store ipAdresses if configured for
	    // get the ipAddress
	    if ($conf->get('store_ip_address'))
	    {
	        $ipAddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	        $data['ip_address'] = $ipAddress;
	    } else {
		    $data['ip_address'] = '';
	    }


        // Attempt to save the data.
        $return	= $model->register($data);
        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_uu.registration.data', $data);

            // Redirect back to the edit screen.
            $this->setRedirect(JRoute::_('index.php?option=com_uu&view=registration', false));
            return false;
        }

        // Flush the data from the session.
        $app->setUserState('com_uu.registration.data', null);

        //get uu configg
        $conf = new UuConfig();
        $userParams	= JComponentHelper::getParams('com_users');
        $useractivation =  $userParams->get('useractivation',0);//2=>admin - 1=>user 0=>aucun/



        // Redirect to the profile screen.
        if ($useractivation == 2){
            if ($conf->get('red_registration_with_activation') == 'default') {
                $this->setMessage(JText::_('COM_UU_REGISTRATION_COMPLETE_VERIFY'));
                $this->setRedirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
            } else {
                $returnUrl = UuSiteHelper::getRedirectUrl($conf->get('red_registration_with_activation'),$conf->get('red_registration_with_activation_custom'),$conf->get('red_registration_with_activation_custom_menu'));
                $this->setRedirect(JRoute::_($returnUrl, false));;
            }
        } elseif ($useractivation == 1) {
            if ($conf->get('red_registration_with_activation') == 'default') {
                $this->setMessage(JText::_('COM_UU_REGISTRATION_COMPLETE_ACTIVATE'));
                $this->setRedirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
            } else {
                $returnUrl = UuSiteHelper::getRedirectUrl($conf->get('red_registration_with_approval'),$conf->get('red_registration_with_approval_custom'),$conf->get('red_registration_with_approval_custom_menu'));
                $this->setRedirect(JRoute::_($returnUrl, false));;
            }

        } else {
            //Ajout du message par defaut
            if ($conf->get('red_registration_success') != 'custom') {
                $this->setMessage(JText::_('COM_UU_REGISTRATION_SAVE_SUCCESS'));
            }
            $returnUrl = UuSiteHelper::getRedirectUrl($conf->get('red_registration_success'),$conf->get('red_registration_success_custom'),$conf->get('red_registration_success_custom_menu'));
            $this->setRedirect(JRoute::_($returnUrl, false));;
        }


        return true;
    }

}