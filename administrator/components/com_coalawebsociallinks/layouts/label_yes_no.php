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

?>
<?php if ($displayData['value']): ?>
	<span class="label label-success"><?php echo JText::_(isset($displayData['yes_text']) ? $displayData['yes_text'] : 'JYES'); ?></span>
<?php else: ?>
	<span class="label label-danger label-important"><?php echo JText::_(isset($displayData['no_text']) ? $displayData['no_text'] : 'JNO'); ?></span>
<?php endif; ?>
