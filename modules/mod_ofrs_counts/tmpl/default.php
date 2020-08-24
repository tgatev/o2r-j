<?php
use Joomla\CMS\Factory;
$input = Factory::getApplication()->input;
$view = $input->get('view');
// No direct access
defined('_JEXEC') or die();
?>

<div class="hidden-xs" style="margin: 15px 0 32px 0; font-weight: bold;">
    <span style="color: #afb5bb; font-weight: normal;" > Search in: </span>
     &nbsp;
	<a class="<?= $view == "offers" ? "hovered" : '' ; ?>" href="<?= JRoute::_('index.php?option=com_ofrs&view=offers&Itemid=215'); ?>"><?php echo($offerCnt." OFFERS"); ?></a>
	&nbsp;&nbsp;
	<a class="<?= $view == "adnets" ? "hovered" : '' ; ?>" href="<?= JRoute::_('index.php?option=com_ofrs&view=adnets&Itemid=1116'); ?>"><?php echo($adNetCnt." NETWORKS"); ?></a>
</div>