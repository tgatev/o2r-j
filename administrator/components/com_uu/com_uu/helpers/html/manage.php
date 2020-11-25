<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		2.5
 */
abstract class UuHtmlManage
{
	/**
	 * Returns a published state on a grid
	 *
	 * @param   integer       $value			The state value.
	 * @param   integer       $i				The row index
	 * @param   boolean       $enabled			An optional setting for access control on the action.
	 * @param   string        $checkbox			An optional prefix for checkboxes.
	 *
	 * @return  string        The Html code
	 *
	 * @see JHtmlJGrid::state
	 *
	 * @since   2.5
	 */
	public static function publish($value, $i, $enabled = true, $checkbox = 'cb')
	{
		$states	= array(
			2	=> array(
				'',
				'COM_UU_FIELD_PROTECTED',
				'',
				'COM_UU_FIELD_PROTECTED',
				false,
				'protected',
				'protected'
			),
			1	=> array(
				'unpublish',
				'JLIB_HTML_PUBLISH_ITEM',
				'JLIB_HTML_UNPUBLISH_ITEM',
				'PUBLISH',
				false,
				'publish',
				'publish'
			),
			0	=> array(
				'publish',
				'JLIB_HTML_UNPUBLISH_ITEM',
				'JLIB_HTML_PUBLISH_ITEM',
				'JLIB_HTML_UNPUBLISH_ITEM',
				false,
				'unpublish',
				'unpublish'
			),
		);

		return JHtml::_('jgrid.state', $states, $value, $i, 'fields.', $enabled, true, $checkbox);
	}
}
