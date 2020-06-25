<?php

// No direct access
defined('_JEXEC') or die();
?>

<div class="hidden-xs" style="margin: 15px 0 32px 0; font-weight: bold;">
    <span style="color: #afb5bb; font-weight: normal;" > Search in: </span>
	<a href="<?= JRoute::_('index.php?option=com_ofrs&view=offers&Itemid=215'); ?>"><?php echo($offerCnt." OFFERS"); ?></a>
	&nbsp;&nbsp; 
	<a href="<?= JRoute::_('index.php?option=com_ofrs&view=adnets&Itemid=1116'); ?>"><?php echo($adNetCnt." NETWORKS"); ?></a>
</div>