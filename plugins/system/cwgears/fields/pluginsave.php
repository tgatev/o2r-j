<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Gears
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Gears is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;


/**
 * Class JFormFieldPluginSave
 */
class JFormFieldPluginSave extends JFormField
{
	protected $type = 'pluginsave';

	protected function getInput()
	{
		$html = '<button onclick="Joomla.submitbutton(\'plugin.apply\');" class="btn button-apply btn-danger"><span class="icon-apply icon-white" aria-hidden="true"></span>' . JText::_('PLG_CWGEARS_APPLY') . '</button>';
		return $html;
	}

	protected function getLabel()
	{
		return '';
	}
}
