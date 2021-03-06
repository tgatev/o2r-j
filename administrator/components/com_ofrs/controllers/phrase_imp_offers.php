<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.26
	@build			5th February, 2020
	@created		5th July, 2019
	@package		Offer Monster
	@subpackage		phrase_imp_offers.php
	@author			Delta Flip Ltd <http://deltaflip.com>	
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
 * Phrase_imp_offers Controller
 */
class OfrsControllerPhrase_imp_offers extends JControllerAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_OFRS_PHRASE_IMP_OFFERS';

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
	public function getModel($name = 'Phrase_imp_offer', $prefix = 'OfrsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function exportData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('phrase_imp_offer.export', 'com_ofrs') && $user->authorise('core.export', 'com_ofrs'))
		{
			// Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// Sanitize the input
			JArrayHelper::toInteger($pks);
			// Get the model
			$model = $this->getModel('Phrase_imp_offers');
			// get the data to export
			$data = $model->getExportData($pks);
			if (OfrsHelper::checkArray($data))
			{
				// now set the data to the spreadsheet
				$date = JFactory::getDate();
				OfrsHelper::xls($data,'Phrase_imp_offers_'.$date->format('jS_F_Y'),'Phrase imp offers exported ('.$date->format('jS F, Y').')','phrase imp offers');
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_OFRS_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=phrase_imp_offers', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('phrase_imp_offer.import', 'com_ofrs') && $user->authorise('core.import', 'com_ofrs'))
		{
			// Get the import model
			$model = $this->getModel('Phrase_imp_offers');
			// get the headers to import
			$headers = $model->getExImPortHeaders();
			if (OfrsHelper::checkObject($headers))
			{
				// Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('phrase_imp_offer_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'phrase_imp_offers');
				$session->set('dataType_VDM_IMPORTINTO', 'phrase_imp_offer');
				// Redirect to import view.
				$message = JText::_('COM_OFRS_IMPORT_SELECT_FILE_FOR_PHRASE_IMP_OFFERS');
				$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=import', false), $message);
				return;
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_OFRS_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=phrase_imp_offers', false), $message, 'error');
		return;
	}
}
