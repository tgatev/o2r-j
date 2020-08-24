<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Uu.
 */
class UuViewFields extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}

        //check plugin redirect activation
        $uuRedirect = JPluginHelper::isEnabled("system","uuredirect");
        if (!$uuRedirect){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_UU_PLUGIN_REDIRECT_NOT_PUBLISHED'),'warning');
        }

		$this->addToolbar();
        
        $input = JFactory::getApplication()->input;
        $view = $input->getCmd('view', '');
        UuHelper::addSubmenu($view);
        
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/uu.php';

		$state	= $this->get('State');
		$canDo	= UuHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_UU_TITLE_FIELDS'), 'fields.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/field';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('field.addGroup','COM_UU_FIELDS_TOOLBAR_NEW_GROUP');
            }

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('field.add','COM_UU_FIELDS_TOOLBAR_NEW_FIELD');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('field.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit')) {
            if (isset($this->items[0]->published)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('fields.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('fields.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
                JToolBarHelper::divider();
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'fields.delete','JTOOLBAR_DELETE');
                JToolBarHelper::divider();
            }
		}
        

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_uu');
		}


	}

}
