<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			26th November, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		ofrs.php
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
JHtml::_('behavior.tabstate');

// Set the component css/js
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_ofrs/assets/css/site.css');
$document->addScript('components/com_ofrs/assets/js/site.js');
$document->addScript('components/com_ofrs/assets/js/offers.js');
$document->addScript('components/com_ofrs/assets/js/dropdowns.js');
$document->addScript('components/com_ofrs/assets/js/user_profile.js');


// Require helper files
JLoader::register('OfrsHelper', __DIR__ . '/helpers/ofrs.php'); 
JLoader::register('OfrsHelperRoute', __DIR__ . '/helpers/route.php');
JLoader::register('ModOfrsCountsHelper', __DIR__.'/../../modules/mod_ofrs_counts/helper.php');
// Get an instance of the controller prefixed by Ofrs
$controller = JControllerLegacy::getInstance('Ofrs');

// Perform the request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
