<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2015. All rights reserved.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class UuControllerCpanel extends JControllerForm
{
	function checkUpdates() {
		//force information reload
		$updateInfo = LiveUpdate::getUpdateInformation(true);
		//send json response
		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');

		if ($updateInfo->hasUpdates) {
			$msg = JText::_('COM_UU_CPANEL_OLD_VERSION').'<a href="index.php?option=com_uu&view=liveupdate"/> '.JText::_('COM_UU_CPANEL_UPDATE_LINK').'</a>';
			echo json_encode(array('update' => "true",'version' => $updateInfo->version, 'message' => $msg));
		} else {
			$msg = JText::_('COM_UU_CPANEL_LATEST_VERSION');
			echo json_encode(array('update' => "false",'version' => $updateInfo->version, 'message' => $msg));
		}
		return true;
	}

}