<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Uu helper.
 */
class UuHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{

		JSubMenuHelper::addEntry(
			JText::_('COM_UU_TITLE_CPANEL'),
			'index.php?option=com_uu&view=cpanel',
			$vName == 'cpanel'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_UU_TITLE_USERS'),
			'index.php?option=com_uu&view=users',
			$vName == 'users'
		);

        JSubMenuHelper::addEntry(
            JText::_('COM_UU_TITLE_FIELDS'),
            'index.php?option=com_uu&view=fields',
            $vName == 'fields'
        );

        JSubMenuHelper::addEntry(
            JText::_('COM_UU_TITLE_CONFIGURATION'),
            'index.php?option=com_uu&view=configuration',
            $vName == 'configuration'
        );

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_uu';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
