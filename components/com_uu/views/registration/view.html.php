<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class UuViewRegistration extends JViewLegacy
{

	protected $validator_lang;

    function display($tpl = null)
    {
        // Get the view data.
        $this->data		= $this->get('Data');
        $this->form		= $this->get('Form');
        $this->state	= $this->get('State');
        $this->params	= $this->state->get('params');

        $model = $this->getModel();
        $filter       = array('published'=>'1,2', 'registration' => '1,2');
        $registrationFields =   $model->getRegistrationFields($filter);

        $this->registrationFields = $registrationFields;

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Check for layout override
        $active = JFactory::getApplication()->getMenu()->getActive();
        if (isset($active->query['layout'])) {
            $this->setLayout($active->query['layout']);
        }

        //get language for validator
	    $this->validator_lang = UuSiteHelper::getValidatorLanguage();

        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

        $this->prepareDocument();

        // Display the view
        parent::display($tpl);
    }

    protected function prepareDocument()
    {
        $app		= JFactory::getApplication();
        $menus		= $app->getMenu();
        $title 		= null;

        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::root().'/components/com_uu/assets/css/uu.css');
        //load jQuery in no conflict mode
        JHtml::_('jquery.framework');
        $doc->addScript(JURI::root().'/components/com_uu/assets/js/jquery.form-validator.min.js');
        $doc->addScript(JURI::root().'/components/com_uu/assets/js/security.js');
        $doc->addScript(JURI::root().'/components/com_uu/assets/js/date.js');

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_UU_REGISTRATION'));
        }

        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description'))
        {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords'))
        {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots'))
        {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

}