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
 * Ofrs View class for the Ad_networks
 */
class OfrsViewAd_networks extends JViewLegacy
{
	/**
	 * Ad_networks view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			OfrsHelper::addSubmenu('ad_networks');
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
		$this->canDo = OfrsHelper::getActions('ad_network');
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
		JToolBarHelper::title(JText::_('COM_OFRS_AD_NETWORKS'), 'joomla');
		JHtmlSidebar::setAction('index.php?option=com_ofrs&view=ad_networks');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('ad_network.add');
		}

		// Only load if there are items
		if (OfrsHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('ad_network.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('ad_networks.publish');
				JToolBarHelper::unpublishList('ad_networks.unpublish');
				JToolBarHelper::archiveList('ad_networks.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('ad_networks.checkin');
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
				JToolbarHelper::deleteList('', 'ad_networks.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('ad_networks.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('ad_network.export'))
			{
				JToolBarHelper::custom('ad_networks.exportData', 'download', '', 'COM_OFRS_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('ad_network.import'))
		{
			JToolBarHelper::custom('ad_networks.importData', 'upload', '', 'COM_OFRS_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$help_url = OfrsHelper::getHelpUrl('ad_networks');
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

		// Set Tracking Platform Id Name Selection
		$this->tracking_platform_idNameOptions = JFormHelper::loadFieldType('Trackingplatform')->options;
		// We do some sanitation for Tracking Platform Id Name filter
		if (OfrsHelper::checkArray($this->tracking_platform_idNameOptions) &&
			isset($this->tracking_platform_idNameOptions[0]->value) &&
			!OfrsHelper::checkString($this->tracking_platform_idNameOptions[0]->value))
		{
			unset($this->tracking_platform_idNameOptions[0]);
		}
		// Only load Tracking Platform Id Name filter if it has values
		if (OfrsHelper::checkArray($this->tracking_platform_idNameOptions))
		{
			// Tracking Platform Id Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_OFRS_AD_NETWORK_TRACKING_PLATFORM_ID_LABEL').' -',
				'filter_tracking_platform_id',
				JHtml::_('select.options', $this->tracking_platform_idNameOptions, 'value', 'text', $this->state->get('filter.tracking_platform_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Tracking Platform Id Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_AD_NETWORK_TRACKING_PLATFORM_ID_LABEL').' -',
					'batch[tracking_platform_id]',
					JHtml::_('select.options', $this->tracking_platform_idNameOptions, 'value', 'text')
				);
			}
		}

		// Set Import Setup Selection
		$this->import_setupOptions = $this->getTheImport_setupSelections();
		// We do some sanitation for Import Setup filter
		if (OfrsHelper::checkArray($this->import_setupOptions) &&
			isset($this->import_setupOptions[0]->value) &&
			!OfrsHelper::checkString($this->import_setupOptions[0]->value))
		{
			unset($this->import_setupOptions[0]);
		}
		// Only load Import Setup filter if it has values
		if (OfrsHelper::checkArray($this->import_setupOptions))
		{
			// Import Setup Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_OFRS_AD_NETWORK_IMPORT_SETUP_LABEL').' -',
				'filter_import_setup',
				JHtml::_('select.options', $this->import_setupOptions, 'value', 'text', $this->state->get('filter.import_setup'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Import Setup Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_AD_NETWORK_IMPORT_SETUP_LABEL').' -',
					'batch[import_setup]',
					JHtml::_('select.options', $this->import_setupOptions, 'value', 'text')
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
		$this->document->setTitle(JText::_('COM_OFRS_AD_NETWORKS'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_ofrs/assets/css/ad_networks.css", (OfrsHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'a.name' => JText::_('COM_OFRS_AD_NETWORK_NAME_LABEL'),
			'g.name' => JText::_('COM_OFRS_AD_NETWORK_TRACKING_PLATFORM_ID_LABEL'),
			'h.name' => JText::_('COM_OFRS_AD_NETWORK_CURRENCY_ID_LABEL'),
			'a.import_setup' => JText::_('COM_OFRS_AD_NETWORK_IMPORT_SETUP_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheImport_setupSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('import_setup'));
		$query->from($db->quoteName('#__ofrs_ad_network'));
		$query->order($db->quoteName('import_setup') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $import_setup)
			{
				// Translate the import_setup selection
				$text = $model->selectionTranslation($import_setup,'import_setup');
				// Now add the import_setup and its text to the options array
				$_filter[] = JHtml::_('select.option', $import_setup, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}
}
