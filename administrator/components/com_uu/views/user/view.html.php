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
 * View to edit
 */
class UuViewUser extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
    protected $grouplist;
    protected $groups;
    protected $customFields;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
        $this->item		= $this->get('Item');
        $this->grouplist	= $this->get('Groups');
        $this->groups		= $this->get('AssignedGroups');


        $this->customFields = $this->get('CustomFields');

        //load language message com_user
        $lang =JFactory::getLanguage();
        $lang->load('com_users', JPATH_ADMINISTRATOR, 'en-GB', false);

        //prevent from saving password
        $this->form->setValue('password',	null);
        $this->form->setValue('password2',	null);

		$doc = JFactory::getDocument();
		JHtml::_('jquery.framework');
		$doc->addScript(JURI::root().'/components/com_uu/assets/js/jquery.form-validator.min.js');
		$doc->addScript(JURI::root().'/components/com_uu/assets/js/security.js');


		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();
        parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->user_id == 0);
        if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		$canDo		= UuHelper::getActions();

		JToolBarHelper::title(JText::_('COM_UU_TITLE_USER'), 'user.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('user.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('user.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			//JToolBarHelper::custom('user.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			//JToolBarHelper::custom('user.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('user.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('user.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
