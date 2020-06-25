<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Social Links
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Social Links is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die;

if ($displayData['state'] == '' || $displayData['state'] == 0)
{
	$state = '<span class="label label-danger label-warning">' . JTEXT::_('JOFF') . '</span>';
}
else
{
	$state = '<span class="label label-success">' . JTEXT::_('JON') . '</span>';
}
?>

<tr>
	<td class="left">
		<?php echo $displayData['extension']; ?>
	</td>
	<td class="left">
		<?php echo ucfirst($displayData['type']); ?>
	</td>
	<td class="center nowrap">
		<?php echo $displayData['version']; ?>
	</td>
	<td class="center nowrap">
		<?php echo $displayData['available']; ?>
	</td>
	<td class="center nowrap">
		<?php echo $state; ?>
	</td>
</tr>