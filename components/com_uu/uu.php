<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);


require_once JPATH_COMPONENT.'/helpers/route.php';
require_once JPATH_COMPONENT .'/helpers/config.php';
require_once JPATH_COMPONENT .'/helpers/ustring.php';
require_once JPATH_COMPONENT .'/helpers/uu.php';
require_once JPATH_COMPONENT .'/helpers/jaxuuresponse.php';
require_once JPATH_COMPONENT .'/libraries/uufieldinterface.php';

$conf = new UuConfig();
if (!$conf->get('enable_user_registration')) {
    $app = JFactory::getApplication();
    $app->redirect('index.php',JText::_('COM_UU_USER_REGISTRATION_NOT_ENABLED'));
    jexit();
}
// Set the component css/js
$document = JFactory::getDocument();
//$document->addStyleSheet('components/com_uu/assets/css/site.css');
$document->addScript('components/com_uu/assets/js/site.js');

// Execute the task.
$controller	= JControllerLegacy::getInstance('Uu');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
