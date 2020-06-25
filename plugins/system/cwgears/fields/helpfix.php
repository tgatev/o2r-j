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

defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

/**
 * Class JFormFieldHelpfix
 */
class JFormFieldHelpfix extends JFormFieldList
{
	public $type = 'Helpfix';

	
	/*
	 * This has been moved to the form field csscom
	 */
	protected function getInput()
	{
		$html = parent::getInput();
		
		if(JFactory::getApplication()->input->getCmd('option', '') == 'com_config')
		{
			JFactory::getDocument()->addScriptDeclaration('
				jQuery(document).ready(function($) {
					$("label[for=\'jform_help_fix\']").closest(".control-group").remove()
					
				});
			');
		}
		return $html;
	}
}
