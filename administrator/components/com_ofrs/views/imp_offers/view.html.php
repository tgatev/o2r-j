<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.27
	@build			5th February, 2020
	@created		5th July, 2019
	@package		Offer Monster
	@subpackage		view.html.php
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
 * Ofrs View class for the Imp_offers
 */
class OfrsViewImp_offers extends JViewLegacy
{
	/**
	 * Imp_offers view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			OfrsHelper::addSubmenu('imp_offers');
		}

		// Assign data to the view
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->user = JFactory::getUser();
		$this->listOrder = $this->escape($this->state->get('list.ordering'));
		$this->listDirn = $this->escape($this->state->get('list.direction'));
		$this->saveOrder = $this->listOrder == 'ordering';
		// set the return here value
		$this->return_here = urlencode(base64_encode((string) JUri::getInstance()));
		// get global action permissions
		$this->canDo = OfrsHelper::getActions('imp_offer');
		$this->canEdit = $this->canDo->get('core.edit');
		$this->canState = $this->canDo->get('core.edit.state');
		$this->canCreate = $this->canDo->get('core.create');
		$this->canDelete = $this->canDo->get('core.delete');
		$this->canBatch = $this->canDo->get('core.batch');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
			// load the batch html
			if ($this->canCreate && $this->canEdit && $this->canState)
			{
				$this->batchDisplay = JHtmlBatch_::render();
			}
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_OFRS_IMP_OFFERS'), 'joomla');
		JHtmlSidebar::setAction('index.php?option=com_ofrs&view=imp_offers');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('imp_offer.add');
		}

		// Only load if there are items
		if (OfrsHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('imp_offer.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('imp_offers.publish');
				JToolBarHelper::unpublishList('imp_offers.unpublish');
				JToolBarHelper::archiveList('imp_offers.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('imp_offers.checkin');
				}
			}

			// Add a batch button
			if ($this->canBatch && $this->canCreate && $this->canEdit && $this->canState)
			{
				// Get the toolbar object instance
				$bar = JToolBar::getInstance('toolbar');
				// set the batch button name
				$title = JText::_('JTOOLBAR_BATCH');
				// Instantiate a new JLayoutFile instance and render the batch button
				$layout = new JLayoutFile('joomla.toolbar.batch');
				// add the button to the page
				$dhtml = $layout->render(array('title' => $title));
				$bar->appendButton('Custom', $dhtml, 'batch');
			}

			if ($this->state->get('filter.published') == -2 && ($this->canState && $this->canDelete))
			{
				JToolbarHelper::deleteList('', 'imp_offers.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('imp_offers.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('imp_offer.export'))
			{
				JToolBarHelper::custom('imp_offers.exportData', 'download', '', 'COM_OFRS_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('imp_offer.import'))
		{
			JToolBarHelper::custom('imp_offers.importData', 'upload', '', 'COM_OFRS_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$help_url = OfrsHelper::getHelpUrl('imp_offers');
		if (OfrsHelper::checkString($help_url))
		{
				JToolbarHelper::help('COM_OFRS_HELP_MANAGER', false, $help_url);
		}

		// add the options comp button
		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			JToolBarHelper::preferences('com_ofrs');
		}

		if ($this->canState)
		{
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
			);
			// only load if batch allowed
			if ($this->canBatch)
			{
				JHtmlBatch_::addListSelection(
					JText::_('COM_OFRS_KEEP_ORIGINAL_STATE'),
					'batch[published]',
					JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('all' => false)), 'value', 'text', '', true)
				);
			}
		}

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			JHtmlBatch_::addListSelection(
				JText::_('COM_OFRS_KEEP_ORIGINAL_ACCESS'),
				'batch[access]',
				JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text')
			);
		}

		// Set Imp Session Id Ad Network Id Selection
		$this->imp_session_idAd_network_idOptions = JFormHelper::loadFieldType('Impsession')->options;
		// We do some sanitation for Imp Session Id Ad Network Id filter
		if (OfrsHelper::checkArray($this->imp_session_idAd_network_idOptions) &&
			isset($this->imp_session_idAd_network_idOptions[0]->value) &&
			!OfrsHelper::checkString($this->imp_session_idAd_network_idOptions[0]->value))
		{
			unset($this->imp_session_idAd_network_idOptions[0]);
		}
		// Only load Imp Session Id Ad Network Id filter if it has values
		if (OfrsHelper::checkArray($this->imp_session_idAd_network_idOptions))
		{
			// Imp Session Id Ad Network Id Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_OFRS_IMP_OFFER_IMP_SESSION_ID_LABEL').' -',
				'filter_imp_session_id',
				JHtml::_('select.options', $this->imp_session_idAd_network_idOptions, 'value', 'text', $this->state->get('filter.imp_session_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Imp Session Id Ad Network Id Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_IMP_OFFER_IMP_SESSION_ID_LABEL').' -',
					'batch[imp_session_id]',
					JHtml::_('select.options', $this->imp_session_idAd_network_idOptions, 'value', 'text')
				);
			}
		}

		// Set Currency Selection
		$this->currencyOptions = $this->getTheCurrencySelections();
		// We do some sanitation for Currency filter
		if (OfrsHelper::checkArray($this->currencyOptions) &&
			isset($this->currencyOptions[0]->value) &&
			!OfrsHelper::checkString($this->currencyOptions[0]->value))
		{
			unset($this->currencyOptions[0]);
		}
		// Only load Currency filter if it has values
		if (OfrsHelper::checkArray($this->currencyOptions))
		{
			// Currency Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_OFRS_IMP_OFFER_CURRENCY_LABEL').' -',
				'filter_currency',
				JHtml::_('select.options', $this->currencyOptions, 'value', 'text', $this->state->get('filter.currency'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Currency Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_IMP_OFFER_CURRENCY_LABEL').' -',
					'batch[currency]',
					JHtml::_('select.options', $this->currencyOptions, 'value', 'text')
				);
			}
		}

		// Set Payout Type Selection
		$this->typeOptions = $this->getThePayout_typeSelections();
		// We do some sanitation for Payout Type filter
		if (OfrsHelper::checkArray($this->typeOptions) &&
			isset($this->typeOptions[0]->value) &&
			!OfrsHelper::checkString($this->typeOptions[0]->value))
		{
			unset($this->typeOptions[0]);
		}
		// Only load Payout Type filter if it has values
		if (OfrsHelper::checkArray($this->typeOptions))
		{
			// Payout Type Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_OFRS_IMP_OFFER_PAYOUT_TYPE_LABEL').' -',
				'filter_payout_type',
				JHtml::_('select.options', $this->typeOptions, 'value', 'text', $this->state->get('filter.payout_type'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Payout Type Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_IMP_OFFER_PAYOUT_TYPE_LABEL').' -',
					'batch[payout_type]',
					JHtml::_('select.options', $this->typeOptions, 'value', 'text')
				);
			}
		}
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		if (!isset($this->document))
		{
			$this->document = JFactory::getDocument();
		}
		$this->document->setTitle(JText::_('COM_OFRS_IMP_OFFERS'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_ofrs/assets/css/imp_offers.css", (OfrsHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var)
	{
		if(strlen($var) > 50)
		{
			// use the helper htmlEscape method instead and shorten the string
			return OfrsHelper::htmlEscape($var, $this->_charset, true);
		}
		// use the helper htmlEscape method instead.
		return OfrsHelper::htmlEscape($var, $this->_charset);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.sorting' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'g.ad_network_id' => JText::_('COM_OFRS_IMP_OFFER_IMP_SESSION_ID_LABEL'),
			'a.name' => JText::_('COM_OFRS_IMP_OFFER_NAME_LABEL'),
			'a.currency' => JText::_('COM_OFRS_IMP_OFFER_CURRENCY_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheCurrencySelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('currency'));
		$query->from($db->quoteName('#__ofrs_imp_offer'));
		$query->order($db->quoteName('currency') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $currency)
			{
				// Now add the currency and its text to the options array
				$_filter[] = JHtml::_('select.option', $currency, $currency);
			}
			return $_filter;
		}
		return false;
	}

	protected function getThePayout_typeSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('type'));
		$query->from($db->quoteName('#__ofrs_imp_offer'));
		$query->order($db->quoteName('type') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $payout_type)
			{
				// Now add the payout_type and its text to the options array
				$_filter[] = JHtml::_('select.option', $payout_type, $payout_type);
			}
			return $_filter;
		}
		return false;
	}
}
