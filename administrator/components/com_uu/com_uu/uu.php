<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die;

define('UU_J25',version_compare(JVERSION,'2.5.0','>=') ? true : false);
define('UU_J30',version_compare(JVERSION,'3.0.0','>=') ? true : false);

require_once JPATH_ROOT .'/components/com_uu/helpers/config.php';
require_once JPATH_ROOT .'/components/com_uu/helpers/ustring.php';
require_once JPATH_ROOT .'/components/com_uu/helpers/uu.php';
require_once JPATH_ROOT .'/components/com_uu/libraries/uufieldinterface.php';


// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_uu')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

if (!defined('UU_VERSION'))
{
    require_once JPATH_ADMINISTRATOR . '/components/com_uu/version.php';
}


//This is for akeeba release system, it must be executed before any other task
require_once JPATH_COMPONENT_ADMINISTRATOR.'/liveupdate/liveupdate.php';
if(JRequest::getCmd('view','') == 'liveupdate') {
    LiveUpdate::handleRequest();
    return;
}

// Include dependancies
jimport('joomla.application.component.controller');

if (UU_J25){
   $controller	= JControllerLegacy::getInstance('Uu');
} else {
    $controller	= JController::getInstance('Uu');
}
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();


