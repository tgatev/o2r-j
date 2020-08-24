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
 * Ofrs View class for the Phrase_verticals
 */
class OfrsViewPhrase_verticals extends JViewLegacy
{
	/**
	 * Phrase_verticals view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			OfrsHelper::addSubmenu('phrase_verticals');
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
		$this->canDo = OfrsHelper::getActions('phrase_vertical');
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
		JToolBarHelper::title(JText::_('COM_OFRS_PHRASE_VERTICALS'), 'joomla');
		JHtmlSidebar::setAction('index.php?option=com_ofrs&view=phrase_verticals');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('phrase_vertical.add');
		}

		// Only load if there are items
		if (OfrsHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('phrase_vertical.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('phrase_verticals.publish');
				JToolBarHelper::unpublishList('phrase_verticals.unpublish');
				JToolBarHelper::archiveList('phrase_verticals.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('phrase_verticals.checkin');
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
				JToolbarHelper::deleteList('', 'phrase_verticals.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('phrase_verticals.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('phrase_vertical.export'))
			{
				JToolBarHelper::custom('phrase_verticals.exportData', 'download', '', 'COM_OFRS_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('phrase_vertical.import'))
		{
			JToolBarHelper::custom('phrase_verticals.importData', 'upload', '', 'COM_OFRS_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$help_url = OfrsHelper::getHelpUrl('phrase_verticals');
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

		// Set Phrase Id Selection
		$this->phrase_idOptions = $this->getThePhrase_idSelections();
		// We do some sanitation for Phrase Id filter
		if (OfrsHelper::checkArray($this->phrase_idOptions) &&
			isset($this->phrase_idOptions[0]->value) &&
			!OfrsHelper::checkString($this->phrase_idOptions[0]->value))
		{
			unset($this->phrase_idOptions[0]);
		}
		// Only load Phrase Id filter if it has values
		if (OfrsHelper::checkArray($this->phrase_idOptions))
		{
			// Phrase Id Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_OFRS_PHRASE_VERTICAL_PHRASE_ID_LABEL').' -',
				'filter_phrase_id',
				JHtml::_('select.options', $this->phrase_idOptions, 'value', 'text', $this->state->get('filter.phrase_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Phrase Id Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_PHRASE_VERTICAL_PHRASE_ID_LABEL').' -',
					'batch[phrase_id]',
					JHtml::_('select.options', $this->phrase_idOptions, 'value', 'text')
				);
			}
		}

		// Set Vertical Id Name Selection
		$this->vertical_idNameOptions = JFormHelper::loadFieldType('Vertical')->options;
		// We do some sanitation for Vertical Id Name filter
		if (OfrsHelper::checkArray($this->vertical_idNameOptions) &&
			isset($this->vertical_idNameOptions[0]->value) &&
			!OfrsHelper::checkString($this->vertical_idNameOptions[0]->value))
		{
			unset($this->vertical_idNameOptions[0]);
		}
		// Only load Vertical Id Name filter if it has values
		if (OfrsHelper::checkArray($this->vertical_idNameOptions))
		{
			// Vertical Id Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_OFRS_PHRASE_VERTICAL_VERTICAL_ID_LABEL').' -',
				'filter_vertical_id',
				JHtml::_('select.options', $this->vertical_idNameOptions, 'value', 'text', $this->state->get('filter.vertical_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Vertical Id Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_OFRS_PHRASE_VERTICAL_VERTICAL_ID_LABEL').' -',
					'batch[vertical_id]',
					JHtml::_('select.options', $this->vertical_idNameOptions, 'value', 'text')
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
		$this->document->setTitle(JText::_('COM_OFRS_PHRASE_VERTICALS'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_ofrs/assets/css/phrase_verticals.css", (OfrsHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'a.phrase_id' => JText::_('COM_OFRS_PHRASE_VERTICAL_PHRASE_ID_LABEL'),
			'g.name' => JText::_('COM_OFRS_PHRASE_VERTICAL_VERTICAL_ID_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getThePhrase_idSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('phrase_id'));
		$query->from($db->quoteName('#__ofrs_phrase_vertical'));
		$query->order($db->quoteName('phrase_id') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $phrase_id)
			{
				// Now add the phrase_id and its text to the options array
				$_filter[] = JHtml::_('select.option', $phrase_id, $phrase_id);
			}
			return $_filter;
		}
		return false;
	}
}
