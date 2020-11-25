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
class UuViewField extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

        //TODO le type ici pour les groupes n'set pas juste
        if ($this->item->type != null) {
            $xmlPath = JPATH_ROOT . '/components/com_uu/libraries/fields/'. $this->item->type.'.xml';

            //Import filesystem libraries. necessary with joomla 3.0
            jimport('joomla.filesystem.file');
            if( JFile::exists($xmlPath) )
            {
                require_once (JPATH_ROOT .'/components/com_uu/libraries/parameter.php');
                $params = new UuParameter($this->item->params, $xmlPath);
                $htmlparams   = $params->render();
                $this->assign('htmlparams', $htmlparams);
            }
            if ($this->item->type == 'select' || $this->item->type == 'checkbox' || $this->item->type == 'radio' ) {
                $values = $this->get('Values');
                $this->assign('values', $values);
            }

        }


        $doc = JFactory::getDocument();

        if ($this->getLayout() == 'group') {
          $this->setLayout('default_group');
        }

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
		$isNew		= ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		$canDo		= UuHelper::getActions();

        if ($this->getLayout() == 'default_group') {
            if ($isNew){
                JToolBarHelper::title(JText::_('COM_UU_TITLE_FIELD_NEW_GROUP'));
            } else {
                JToolBarHelper::title(JText::_('COM_UU_TITLE_FIELD_EDIT_GROUP'));
            }
        } else {
            if ($isNew){
                JToolBarHelper::title(JText::_('COM_UU_TITLE_FIELD_NEW_FIELD'));
            } else {
                JToolBarHelper::title(JText::_('COM_UU_TITLE_FIELD_EDIT_FIELD'));
            }
        }

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('field.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('field.save', 'JTOOLBAR_SAVE');
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('field.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('field.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
