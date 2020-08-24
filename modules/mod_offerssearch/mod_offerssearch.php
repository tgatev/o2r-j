<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			29th January, 2020
	@created		5th July, 2019
	@package		Offer Monster Backend
	@subpackage		mod_offerssearch.php
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
// Include the component helper functions only once
JLoader::register('OfrsHelper', JPATH_ADMINISTRATOR . '/components/com_ofrs/helpers/ofrs.php');

/***[JCBGUI.joomla_module.mod_code.1.$$$$]***/
//get the document object
$document = JFactory::getDocument();

// get the module class sfx (local)
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

// load the default Tmpl
require JModuleHelper::getLayoutPath('mod_offerssearch', $params->get('layout', 'default'));/***[/JCBGUI$$$$]***/

