<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				SMIG 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.2
	@build			16th July, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		import_sessions.php
	@author			SMIG <http://fuckitall.info>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Import_sessions Controller
 */
class OfrsControllerImport_sessions extends JControllerAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_OFRS_IMPORT_SESSIONS';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JModelLegacy  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Imp_session', $prefix = 'OfrsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function exportData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('imp_session.export', 'com_ofrs') && $user->authorise('core.export', 'com_ofrs'))
		{
			// Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// Sanitize the input
			JArrayHelper::toInteger($pks);
			// Get the model
			$model = $this->getModel('Import_sessions');
			// get the data to export
			$data = $model->getExportData($pks);
			if (OfrsHelper::checkArray($data))
			{
				// now set the data to the spreadsheet
				$date = JFactory::getDate();
				OfrsHelper::xls($data,'Import_sessions_'.$date->format('jS_F_Y'),'Import sessions exported ('.$date->format('jS F, Y').')','import sessions');
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_OFRS_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=import_sessions', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('imp_session.import', 'com_ofrs') && $user->authorise('core.import', 'com_ofrs'))
		{
			// Get the import model
			$model = $this->getModel('Import_sessions');
			// get the headers to import
			$headers = $model->getExImPortHeaders();
			if (OfrsHelper::checkObject($headers))
			{
				// Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('imp_session_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'import_sessions');
				$session->set('dataType_VDM_IMPORTINTO', 'imp_session');
				// Redirect to import view.
				$message = JText::_('COM_OFRS_IMPORT_SELECT_FILE_FOR_IMPORT_SESSIONS');
				$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=import', false), $message);
				return;
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_OFRS_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=import_sessions', false), $message, 'error');
		return;
	}
}
