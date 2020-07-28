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
 * Ofrs View class for the Imp_messages
 */
class OfrsViewImp_messages extends JViewLegacy
{
	/**
	 * Imp_messages view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			OfrsHelper::addSubmenu('imp_messages');
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
		$this->canDo = OfrsHelper::getActions('imp_message');
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
		JToolBarHelper::title(JText::_('COM_OFRS_IMP_MESSAGES'), 'joomla');
		JHtmlSidebar::setAction('index.php?option=com_ofrs&view=imp_messages');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('imp_message.add');
		}

		// Only load if there are items
		if (OfrsHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('imp_message.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('imp_messages.publish');
				JToolBarHelper::unpublishList('imp_messages.unpublish');
				JToolBarHelper::archiveList('imp_messages.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('imp_messages.checkin');
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
				JToolbarHelper::deleteList('', 'imp_messages.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('imp_messages.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('imp_message.export'))
			{
				JToolBarHelper::custom('imp_messages.exportData', 'download', '', 'COM_OFRS_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('imp_message.import'))
		{
			JToolBarHelper::custom('imp_messages.importData', 'upload', '', 'COM_OFRS_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$help_url = OfrsHelper::getHelpUrl('imp_messages');
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

		// Set Message Type Selection
		$this->message_typeOptions = $this->getTheMessage_typeSelections();
		// We do some sanitation for Message Type filter
		if (OfrsHelper::checkArray($this->message_typeOptions) &&
			isset($this->message_typeOptions[0]->value) &&
			!OfrsHelper::checkString($this->message_typeOptions[0]->value))
		{
			unset($this->message_typeOptions[0]);
		}
		// Only load Message Type filter if it has values
		if (OfrsHelper::checkArray($this->message_typeOptions))
		{
			// Message Type Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_OFRS_IMP_MESSAGE_MESSAGE_TYPE_LABEL').' -',
				'filter_message_type',
				JHtml::_('select.options', $this->message_typeOptions, 'value', 'text', $this->state->get('filter.message_type'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Message Type Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_IMP_MESSAGE_MESSAGE_TYPE_LABEL').' -',
					'batch[message_type]',
					JHtml::_('select.options', $this->message_typeOptions, 'value', 'text')
				);
			}
		}

		// Set Imp Offer Id Selection
		$this->imp_offer_idOptions = $this->getTheImp_offer_idSelections();
		// We do some sanitation for Imp Offer Id filter
		if (OfrsHelper::checkArray($this->imp_offer_idOptions) &&
			isset($this->imp_offer_idOptions[0]->value) &&
			!OfrsHelper::checkString($this->imp_offer_idOptions[0]->value))
		{
			unset($this->imp_offer_idOptions[0]);
		}
		// Only load Imp Offer Id filter if it has values
		if (OfrsHelper::checkArray($this->imp_offer_idOptions))
		{
			// Imp Offer Id Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_OFRS_IMP_MESSAGE_IMP_OFFER_ID_LABEL').' -',
				'filter_imp_offer_id',
				JHtml::_('select.options', $this->imp_offer_idOptions, 'value', 'text', $this->state->get('filter.imp_offer_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Imp Offer Id Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_IMP_MESSAGE_IMP_OFFER_ID_LABEL').' -',
					'batch[imp_offer_id]',
					JHtml::_('select.options', $this->imp_offer_idOptions, 'value', 'text')
				);
			}
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
				'- Select '.JText::_('COM_OFRS_IMP_MESSAGE_IMP_SESSION_ID_LABEL').' -',
				'filter_imp_session_id',
				JHtml::_('select.options', $this->imp_session_idAd_network_idOptions, 'value', 'text', $this->state->get('filter.imp_session_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Imp Session Id Ad Network Id Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_IMP_MESSAGE_IMP_SESSION_ID_LABEL').' -',
					'batch[imp_session_id]',
					JHtml::_('select.options', $this->imp_session_idAd_network_idOptions, 'value', 'text')
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
		$this->document->setTitle(JText::_('COM_OFRS_IMP_MESSAGES'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_ofrs/assets/css/imp_messages.css", (OfrsHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'a.message_type' => JText::_('COM_OFRS_IMP_MESSAGE_MESSAGE_TYPE_LABEL'),
			'a.message' => JText::_('COM_OFRS_IMP_MESSAGE_MESSAGE_LABEL'),
			'a.imp_offer_id' => JText::_('COM_OFRS_IMP_MESSAGE_IMP_OFFER_ID_LABEL'),
			'g.ad_network_id' => JText::_('COM_OFRS_IMP_MESSAGE_IMP_SESSION_ID_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheMessage_typeSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('message_type'));
		$query->from($db->quoteName('#__ofrs_imp_message'));
		$query->order($db->quoteName('message_type') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $message_type)
			{
				// Translate the message_type selection
				$text = $model->selectionTranslation($message_type,'message_type');
				// Now add the message_type and its text to the options array
				$_filter[] = JHtml::_('select.option', $message_type, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheImp_offer_idSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('imp_offer_id'));
		$query->from($db->quoteName('#__ofrs_imp_message'));
		$query->order($db->quoteName('imp_offer_id') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $imp_offer_id)
			{
				// Now add the imp_offer_id and its text to the options array
				$_filter[] = JHtml::_('select.option', $imp_offer_id, $imp_offer_id);
			}
			return $_filter;
		}
		return false;
	}
}
