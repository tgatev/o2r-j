<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2015. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


/**
 * HTML View class for the Languages component
 *
 * @package		Joomla.Administrator
 * @subpackage	com_languages
 * @since		1.5
 */
class UuViewConfiguration extends JViewLegacy
{
	public $item;
	public $form;
	public $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->item	= $this->get('Item');
		$this->form	= $this->get('Form');
		$this->state	= $this->get('State');

        //load com_users language file
        //use to show com_users params
        $lang =JFactory::getLanguage();
        $lang->load('com_users', JPATH_ADMINISTRATOR, 'en-GB', false);
        $lang->load('com_content',  JPATH_ADMINISTRATOR, 'en-GB', false);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
        require_once JPATH_COMPONENT.'/helpers/uu.php';

		JRequest::setVar('hidemainmenu', 1);
		$canDo	= UuHelper::getActions();

		JToolBarHelper::title(JText::_('COM_UU_VIEW_CONFIGURATION_TITLE'), 'config.png');

		//If an existing item, allow to Apply and Save.
		if ($canDo->get('core.edit')) {
			JToolBarHelper::apply('configuration.apply','JTOOLBAR_APPLY');
			JToolBarHelper::save('configuration.save','JTOOLBAR_SAVE');
		}

		JToolBarHelper::cancel('configuration.cancel', 'JTOOLBAR_CLOSE');

        }
}
