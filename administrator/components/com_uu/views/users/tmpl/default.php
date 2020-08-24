<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */


// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);

$user	= JFactory::getUser();
$userId	= $user->get('id');
$canOrder	= $user->authorise('core.edit.state', 'com_uu');

?>



<form action="<?php echo JRoute::_('index.php?option=com_uu&view=users'); ?>" method="post" name="adminForm" id="adminForm">

    <?php if (!empty( $this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php else : ?>
        <div id="j-main-container">
    <?php endif;?>

    <?php if (UU_J30) { ?>
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('COM_USERS_SEARCH_USERS'); ?>" />
            </div>
            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_RESET'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
            </div>
        </div>
        <div class="clearfix"> </div>
    <?php } else { ?>
            <fieldset id="filter-bar">
                <div class="filter-search fltlft">
                    <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                    <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
                    <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                    <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
                </div>
            </fieldset>
        <div class="clr"> </div>
    <?php } ?>
        <table class="adminlist table table-striped" width="100%">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<th class='left'>
                    <?php echo JHtml::_('grid.sort', 'COM_UU_USERS_HEADING_NAME', 'u.name', $this->sortDirection, $this->sortColumn); ?>
				</th>
                <th class='left'>
                    <?php echo JHtml::_('grid.sort', 'COM_UU_USERS_HEADING_USERNAME', 'u.username', $this->sortDirection, $this->sortColumn); ?>
                </th>
                <th class='left'>
                    <?php echo JHtml::_('grid.sort', 'COM_UU_USERS_HEADING_EMAIL', 'u.email', $this->sortDirection, $this->sortColumn); ?>
                </th>
                <?php if (isset($this->items[0]->id)) { ?>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'u.id', $this->sortDirection, $this->sortColumn); ?>
                </th>
                <?php } ?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$canCreate	= $user->authorise('core.create',		'com_uu');
			$canEdit	= $user->authorise('core.edit',			'com_uu');
			$canCheckin	= $user->authorise('core.manage',		'com_uu');
			$canChange	= $user->authorise('core.edit.state',	'com_uu');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
                    <a href="<?php echo JRoute::_('index.php?option=com_uu&task=user.edit&user_id='.(int) $item->id); ?>" title="<?php echo JText::sprintf('COM_UU_EDIT_USER', $this->escape($item->name)); ?>">
					<?php echo $this->escape($item->name); ?>
                    </a>
				</td>
                <td>
                    <?php echo $item->username; ?>
                </td>
                <td>
                    <?php echo $item->email; ?>
                </td>
                <?php if (isset($this->items[0]->id)) { ?>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
                <?php } ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>