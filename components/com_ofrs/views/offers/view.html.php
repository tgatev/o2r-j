<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			26th November, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		view.html.php
	@author			SMIG <http://fuckitall.info>	
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
 * Ofrs View class for the Offers
 */
class OfrsViewOffers extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// get combined params of both component and menu
		$this->app = JFactory::getApplication();
		$this->params = $this->app->getParams();
		$this->menu = $this->app->getMenu()->getActive();
		// get the user object
		$this->user = JFactory::getUser();
		// Initialise variables.
        $this->items = $this->get('Items');
        // load verticals map
		$this->verticals_map = $this->get('VerticalsMap');

		$this->pagination = $this->get('Pagination');

		/***[JCBGUI.site_view.php_jview_display.27.$$$$]***/
		$this->filterForm = $this->get('FilterForm'); // ??
		$this->activeFilters = $this->get('ActiveFilters'); // ??

        $filters = $this->app->getUserStateFromRequest('ofrs.filter', 'filter', array());

		$state = $this->get('State');
		$state->set('list.ordering', OfrsModelOffers::ORDER_MAP[$filters['sort_by']]) ;
        // Only ASC for now hardcoded
		$state->set('list.direction', 'ASC') ;


        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn =  $state->get('list.ordering'); /***[/JCBGUI$$$$]***/

		// Set the toolbar
		$this->addToolBar();

		// set the document
		$this->_prepareDocument();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode(PHP_EOL, $errors), 500);
		}

		parent::display($tpl);
	}


/***[JCBGUI.site_view.php_jview.27.$$$$]***/
protected function getSortFields()
	{
	    return array(
	        'a.name' => 'Name',
	        'b.name' => 'Network',
	        'c.payout_usd' => 'Payout',
	        'd.name' => 'Payout Type',
	        'a.modified' => 'Updated',
	    );
	}

/***[/JCBGUI$$$$]***/


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{

		// always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
		// Initialize the header checker.
		$HeaderCheck = new ofrsHeaderCheck;

	    // add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/components/com_ofrs/assets/css/offers.css', (OfrsHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
        // Set the Custom JS script to view
          // always load these files. for component offers page
        $this->document->addScript(JURI::root(true) . "/assets/js/bootstrap-multiselect.js", (OfrsHelper::jVersion()->isCompatible("3.8.0")) ? array("version" => "auto") : "text/javascript");
        $this->document->addStyleSheet(JURI::root(true) . "/assets/css/bootstrap-multiselect.css", (OfrsHelper::jVersion()->isCompatible("3.8.0")) ? array("version" => "auto") : "text/css");

    }

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// adding the joomla toolbar to the front
		JLoader::register('JToolbarHelper', JPATH_ADMINISTRATOR.'/includes/toolbar.php');
		
		// set help url for this view if found
		$help_url = OfrsHelper::getHelpUrl('offers');
		if (OfrsHelper::checkString($help_url))
		{
			JToolbarHelper::help('COM_OFRS_HELP_MANAGER', false, $help_url);
		}
		// now initiate the toolbar
		$this->toolbar = JToolbar::getInstance();
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var, $sorten = false, $length = 40)
	{
		// use the helper htmlEscape method instead.
		return OfrsHelper::htmlEscape($var, $this->_charset, $sorten, $length);
	}
}
