<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>


<article>
<section><a><b>Offer Alerts</b></a></section>
<section><a>-------------------</a></section>
</article>
 
<?php
    foreach ($this->items as $item):
?> 

        <article class="row Rtable-row vertical-center-sm" id="<?= $id; ?>">
			<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><?= $item->offer_name ?></section>
			<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><?= $item->adnet_name ?></section>
			<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><?= $item->payout_display ?></section>
			<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><?= $item->payout_type ?></section>
			<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><img id="omr<?= $item->offer_id ?>" class="omr" src="/images/icons/delete_button.png"></section>
        </article>
 
<?php 
    endforeach;
?>

<article>
<section><a href="/index.php?option=com_ofrs&view=offers&Itemid=215">Add offer ...</a></section>
</article>