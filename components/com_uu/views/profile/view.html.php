<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;

/**
 * Profile view class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class UuViewProfile extends JViewLegacy
{
	protected $data;
	protected $form;
	protected $params;
	protected $state;
	protected $validator_lang;

	/**
	 * Method to display the view.
	 *
	 * @param	string	$tpl	The template file to include
	 * @since	1.6
	 */
	public function display($tpl = null)
	{
		// Get the view data.
		$this->data		= $this->get('Data');
		$this->form		= $this->get('Form');
		$this->state	= $this->get('State');
		$this->params	= $this->state->get('params');

        // Check for layout override
        $active = JFactory::getApplication()->getMenu()->getActive();
        if (isset($active->query['layout'])) {
            $this->setLayout($active->query['layout']);
        }


		//get language for validator
		$this->validator_lang = UuSiteHelper::getValidatorLanguage();

        $model = $this->getModel();

        //on affiche tous les field publiés même les non éditable
        // $filter       = array('core'=>'0','published'=>'1,2', 'editable' => '1,2');
        $filter       = array('published'=>'1,2', 'editable' => '1,2');
        if ($this->getLayout() == 'edit') {
            $extraFields =   $model->getProfileFields($filter,false);
        } else {
            $extraFields =   $model->getProfileFields($filter,true);
        }

        //$this->assignRef('extraFields',$extraFields);
        $this->extraFields = $extraFields;

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Check if a user was found.
		if (!$this->data->id) {
			JError::raiseError(404, JText::_('JERROR_USERS_PROFILE_NOT_FOUND'));
			return false;
		}


		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

		$this->prepareDocument();
		
		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @since	1.6
	 */
	protected function prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$user		= JFactory::getUser();
		$login		= $user->get('guest') ? true : false;
		$title 		= null;


        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::root().'/components/com_uu/assets/css/uu.css');
		if ($this->getLayout() == 'edit') {
			JHtml::_('jquery.framework');
			$doc->addScript(JURI::root().'/components/com_uu/assets/js/jquery.form-validator.min.js');
			$doc->addScript(JURI::root().'/components/com_uu/assets/js/security.js');
			$doc->addScript(JURI::root().'/components/com_uu/assets/js/date.js');
			$doc->addScript(JURI::root().'/components/com_uu/assets/js/menu.js');
		}

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $user->name));
		} else {
			$this->params->def('page_heading', JText::_('COM_USERS_PROFILE'));
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
