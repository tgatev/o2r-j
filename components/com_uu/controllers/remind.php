<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Reset controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @version		1.6
 */
class UuControllerRemind extends UuController
{
	/**
	 * Method to request a username reminder.
	 *
	 * @since	1.6
	 */
	public function remind()
	{
		// Check the request token.
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

        //load com_users language file
        $lang =JFactory::getLanguage();
        $lang->load('com_users', JPATH_SITE);

		$app	= JFactory::getApplication();
		$model	= $this->getModel('Remind', 'UuModel');
		$data	= JRequest::getVar('jform', array(), 'post', 'array');

		// Submit the password reset request.
		$return	= $model->processRemindRequest($data);

		// Check for a hard error.
		if ($return == false) {
			// The request failed.
			// Get the route to the next page.
			$itemid = UuHelperRoute::getRemindRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_uu&view=remind'.$itemid;

			// Go back to the request form.
			$message = JText::sprintf('COM_USERS_REMIND_REQUEST_FAILED', $model->getError());
			$this->setRedirect(JRoute::_($route, false), $message, 'notice');
			return false;
		} else {
			// The request succeeded.
			// Get the route to the next page.
			$itemid = UuHelperRoute::getRemindRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_uu&view=login'.$itemid;

			// Proceed to step two.
			$message = JText::_('COM_USERS_REMIND_REQUEST_SUCCESS');
			$this->setRedirect(JRoute::_($route, false), $message);
			return true;
		}
	}
}
