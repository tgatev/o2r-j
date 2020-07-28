<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.12
	@build			3rd October, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		currency_exchange_rates.php
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
 * Currency_exchange_rates Controller
 */
class OfrsControllerCurrency_exchange_rates extends JControllerAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_OFRS_CURRENCY_EXCHANGE_RATES';

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
	public function getModel($name = 'Currency_exchange_rate', $prefix = 'OfrsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function exportData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('currency_exchange_rate.export', 'com_ofrs') && $user->authorise('core.export', 'com_ofrs'))
		{
			// Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// Sanitize the input
			JArrayHelper::toInteger($pks);
			// Get the model
			$model = $this->getModel('Currency_exchange_rates');
			// get the data to export
			$data = $model->getExportData($pks);
			if (OfrsHelper::checkArray($data))
			{
				// now set the data to the spreadsheet
				$date = JFactory::getDate();
				OfrsHelper::xls($data,'Currency_exchange_rates_'.$date->format('jS_F_Y'),'Currency exchange rates exported ('.$date->format('jS F, Y').')','currency exchange rates');
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_OFRS_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=currency_exchange_rates', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('currency_exchange_rate.import', 'com_ofrs') && $user->authorise('core.import', 'com_ofrs'))
		{
			// Get the import model
			$model = $this->getModel('Currency_exchange_rates');
			// get the headers to import
			$headers = $model->getExImPortHeaders();
			if (OfrsHelper::checkObject($headers))
			{
				// Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('currency_exchange_rate_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'currency_exchange_rates');
				$session->set('dataType_VDM_IMPORTINTO', 'currency_exchange_rate');
				// Redirect to import view.
				$message = JText::_('COM_OFRS_IMPORT_SELECT_FILE_FOR_CURRENCY_EXCHANGE_RATES');
				$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=import', false), $message);
				return;
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_OFRS_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=currency_exchange_rates', false), $message, 'error');
		return;
	}
}
