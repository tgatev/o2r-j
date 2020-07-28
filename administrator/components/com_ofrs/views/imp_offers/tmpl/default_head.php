<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.27
	@build			5th February, 2020
	@created		5th July, 2019
	@package		Offer Monster
	@subpackage		default_head.php
	@author			Delta Flip Ltd <http://deltaflip.com>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>
<tr>
	<?php if ($this->canEdit&& $this->canState): ?>
		<th width="1%" class="nowrap center hidden-phone">
			<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $this->listDirn, $this->listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
		</th>
		<th width="20" class="nowrap center">
			<?php echo JHtml::_('grid.checkall'); ?>
		</th>
	<?php else: ?>
		<th width="20" class="nowrap center hidden-phone">
			&#9662;
		</th>
		<th width="20" class="nowrap center">
			&#9632;
		</th>
	<?php endif; ?>
	<th class="nowrap hidden-phone" >
			<?php echo JText::_('COM_OFRS_IMP_OFFER_STATUS_LABEL'); ?>
	</th>
	<th class="nowrap hidden-phone" >
			<?php echo JHtml::_('grid.sort', 'COM_OFRS_IMP_OFFER_IMP_SESSION_ID_LABEL', 'imp_session_id_ad_network_id', $this->listDirn, $this->listOrder); ?>
	</th>
	<th class="nowrap" >
			<?php echo JHtml::_('grid.sort', 'COM_OFRS_IMP_OFFER_NAME_LABEL', 'name', $this->listDirn, $this->listOrder); ?>
	</th>
	<th class="nowrap hidden-phone" >
			<?php echo JText::_('COM_OFRS_IMP_OFFER_DESCRIPTION_LABEL'); ?>
	</th>
	<th class="nowrap hidden-phone" >
			<?php echo JHtml::_('grid.sort', 'COM_OFRS_IMP_OFFER_CURRENCY_LABEL', 'currency', $this->listDirn, $this->listOrder); ?>
	</th>
	<th class="nowrap hidden-phone" >
			<?php echo JText::_('COM_OFRS_IMP_OFFER_COUNTRIES_LABEL'); ?>
	</th>
	<th class="nowrap hidden-phone" >
			<?php echo JText::_('COM_OFRS_IMP_OFFER_VERTICALS_LABEL'); ?>
	</th>
	<th class="nowrap hidden-phone" >
			<?php echo JText::_('COM_OFRS_IMP_OFFER_PAYOUT_LABEL'); ?>
	</th>
	<th class="nowrap hidden-phone" >
			<?php echo JText::_('COM_OFRS_IMP_OFFER_PAYOUT_TYPE_LABEL'); ?>
	</th>
	<?php if ($this->canState): ?>
		<th width="10" class="nowrap center" >
			<?php echo JHtml::_('grid.sort', 'COM_OFRS_IMP_OFFER_STATUS', 'published', $this->listDirn, $this->listOrder); ?>
		</th>
	<?php else: ?>
		<th width="10" class="nowrap center" >
			<?php echo JText::_('COM_OFRS_IMP_OFFER_STATUS'); ?>
		</th>
	<?php endif; ?>
	<th width="5" class="nowrap center hidden-phone" >
			<?php echo JHtml::_('grid.sort', 'COM_OFRS_IMP_OFFER_ID', 'id', $this->listDirn, $this->listOrder); ?>
	</th>
</tr>