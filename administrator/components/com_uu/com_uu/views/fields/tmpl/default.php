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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_uu/assets/css/uu.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_uu');
$saveOrder	= $listOrder == 'a.ordering';

?>

<form action="<?php echo JRoute::_('index.php?option=com_uu&view=fields'); ?>" method="post" name="adminForm" id="adminForm">

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

     <?php if (UU_J30) { ?>
     <table class="table table-striped">
         <?php } else { ?>
         <table class="adminlist">
             <?php } ?>
		<thead>
			<tr>
                <th width="1%">
                    <?php echo JText::_('COM_UU_FIELDS_NUMBER'); ?>
                </th>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>


                <th class='left'>
                    <?php echo JText::_('COM_UU_FIELDS_NAME'); ?>
                </th>

                <th class='left'>
                    <?php echo JText::_('COM_UU_FIELDS_FIELD_CODE'); ?>
                </th>

                <th class='left'>
                    <?php echo JText::_('COM_UU_FIELDS_TYPE'); ?>
                </th>

                <th width="1%">
                    <?php echo JText::_('JPUBLISHED'); ?>
                </th>

                <th width="1%" class='left'>
                    <?php echo JText::_('COM_UU_FIELDS_REQUIRED'); ?>
                </th>
                <th width="1%" class='left'>
                    <?php echo JText::_('COM_UU_FIELDS_REGISTRATION'); ?>
                </th>
                <th width="1%" class='left'>
                    <?php echo JText::_('COM_UU_FIELDS_EDITABLE'); ?>
                </th>

                <?php if (isset($this->items[0]->ordering)) { ?>
				<th width="1%">
                    <?php echo JText::_('JGRID_HEADING_ORDERING'); ?>
					<?php if ($canOrder && $saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'users.saveorder'); ?>
					<?php endif; ?>
				</th>
                <?php } ?>
                <?php if (isset($this->items[0]->id)) { ?>
                <th width="1%" class="nowrap">
                    <?php echo JText::_('JGRID_HEADING_ID'); ?>
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

<?php
        if($item->type == 'group')
        {
?>
        <tr class="row-group">
			<td><?php echo (int) $item->id; ?></td>
            <td>
                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
            </td>
			<td colspan="3">
                <strong><?php echo JText::_('COM_UU_FIELDS_GROUP');?>
                    <span id="name<?php echo $item->id; ?>">
						<?php echo JHTML::_('link', 'index.php?option=com_uu&task=field.edit&layout=group&id='.(int)$item->id, JText::_($item->name)); ?>
					</span>
                </strong>
            </td>
            <td class="center">
                <?php echo JHtml::_('jgrid.published', $item->published , $i, 'fields.'); ?>
            </td>
            <td colspan="3">&nbsp;</td>
            <td class="order">
                <?php if ($listDirn == 'asc') : ?>
                <span><?php echo $this->pagination->orderUpIcon($i, true, 'fields.orderup', 'JLIB_HTML_MOVE_UP', $item->ordering); ?></span>
                <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fields.orderdown', 'JLIB_HTML_MOVE_DOWN', $item->ordering); ?></span>
                <?php elseif ($listDirn == 'desc') : ?>
                <span><?php echo $this->pagination->orderUpIcon($i, true, 'fields.orderdown', 'JLIB_HTML_MOVE_UP', $item->ordering); ?></span>
                <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fields.orderup', 'JLIB_HTML_MOVE_DOWN', $item>ordering); ?></span>
                <?php endif; ?>
            </td>
            <td class="center">
                <?php echo (int) $item->ordering; ?>
            </td>

        </tr>
<?php } else if($item->type != 'group') { ?>

        <tr class="row-field">
            <td class="center">
                <?php echo (int) $item->id; ?>
            </td>

            <td class="center">
                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
            </td>


            <td class="field-name">
                <?php echo JHTML::_('link', 'index.php?option=com_uu&task=field.edit&id='.(int)$item->id, JText::_($item->name)); ?>
            </td>

            <td class='left'>
                <?php echo $item->fieldcode; ?>
            </td>

            <td class='left'>
                <?php echo $item->type; ?>
            </td>

            <td class='center'>
                <?php echo JHtml::_('UuHtml.Manage.publish', $item->published, $i, !empty($item->core)?false:true, 'cb'); ?>
            </td>

            <td class='center'>
                <?php //echo JHtml::_('UuHtml.Manage.required', !empty($item->core)?2:$item->required , $i, !empty($item->core)?false:true, 'cb'); ?>
                <?php echo JHtml::_('fields.required', $item->required, $i, 'fields.'); ?>
            </td>
            <td class='center'>
                <?php echo JHtml::_('fields.registration',$item->registration, $i, 'fields.'); ?>
            </td>
            <td class='center'>
                <?php echo JHtml::_('fields.editable', $item->editable, $i, 'fields.'); ?>
            </td>


            <?php if (isset($this->items[0]->ordering)) { ?>
            <td class="order">
                    <?php if ($listDirn == 'asc') : ?>
                        <span><?php echo $this->pagination->orderUpIcon($i, true, 'fields.orderup', 'JLIB_HTML_MOVE_UP', $item->ordering); ?></span>
                        <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fields.orderdown', 'JLIB_HTML_MOVE_DOWN', $item->ordering); ?></span>
                    <?php elseif ($listDirn == 'desc') : ?>
                        <span><?php echo $this->pagination->orderUpIcon($i, true, 'fields.orderdown', 'JLIB_HTML_MOVE_UP', $item->ordering); ?></span>
                        <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fields.orderup', 'JLIB_HTML_MOVE_DOWN', $item>ordering); ?></span>
                    <?php endif; ?>
            </td>
            <?php } ?>
            <?php if (isset($this->items[0]->id)) { ?>
            <td class="center">
                <?php echo (int) $item->ordering; ?>
            </td>
            <?php } ?>
        </tr>
<?php } ?>



			<?php endforeach; ?>
		</tbody>
	</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>