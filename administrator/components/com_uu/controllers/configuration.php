<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2015. All rights reserved.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class UuControllerConfiguration extends JControllerForm
{
	// Define protected variables and custom methods if necessary.
	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param	int		$recordId	The primary key id for the item.
	 * @param	string	$key		The name of the primary key variable.
	 *
	 * @return	string	The arguments to append to the redirect URL.
	 * @since	1.6
	 */
	protected function getRedirectToItemAppend($recordId = null, $key = 'lang_id')
	{
		return parent::getRedirectToItemAppend($recordId, $key);
	}

	/**
	 * Method to save the configuration.
	 *
	 * @return	bool	True on success, false on failure.
	 * @since	1.5
	 */
	public function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));


		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Configuration');
		$form	= $model->getForm();
		$data	= JRequest::getVar('jform', array(), 'post', 'array');

		// Attempt to save the configuration.
		$return = $model->save($data);

		// Check the return value.
		if ($return === false)
		{
                    // Save failed, go back to the screen and display a notice.
                    $message = JText::sprintf('JERROR_SAVE_FAILED', $model->getError());
                    $this->setRedirect('index.php?option=com_uu&view=configuration', $message, 'error');
                    return false;
                }

		// Set the success message.
		$message = JText::_('COM_UU_SAVE_CONFIGURATION_SUCCESS');

		// Set the redirect based on the task.
		switch ($this->getTask())
		{
			case 'apply':
				$this->setRedirect('index.php?option=com_uu&view=configuration', $message);
				break;

			case 'save':
			default:
				$this->setRedirect('index.php?option=com_uu', $message);
				break;
		}

		return true;

        }

	/**
	 * Cancel operation
	 */
	public function cancel($key = null)
	{
        $return = parent::cancel($key);
		$this->setRedirect(JRoute::_('index.php?option=com_uu'), false);
        return $return;
    }
}