<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				SMIG 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.2
	@build			16th July, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		default.php
	@author			SMIG <http://fuckitall.info>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

if ($this->saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_ofrs&task=import_sessions.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'imp_sessionList', 'adminForm', strtolower($this->listDirn), $saveOrderingUrl);
}

?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $this->listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_ofrs&view=import_sessions'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif; ?>
<?php if (empty($this->items)): ?>
	<?php echo $this->loadTemplate('toolbar');?>
    <div class="alert alert-no-items">
        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
    </div>
<?php else : ?>
		<?php echo $this->loadTemplate('toolbar');?>
		<table class="table table-striped" id="imp_sessionList">
			<thead><?php echo $this->loadTemplate('head');?></thead>
			<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
			<tbody><?php echo $this->loadTemplate('body');?></tbody>
		</table>
		<?php //Load the batch processing form. ?>
        <?php if ($this->canCreate && $this->canEdit) : ?>
            <?php echo JHtml::_(
                'bootstrap.renderModal',
                'collapseModal',
                array(
                    'title' => JText::_('COM_OFRS_IMPORT_SESSIONS_BATCH_OPTIONS'),
                    'footer' => $this->loadTemplate('batch_footer')
                ),
                $this->loadTemplate('batch_body')
            ); ?>
        <?php endif; ?>
		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="boxchecked" value="0" />
	</div>
<?php endif; ?>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>
