<?php

// // No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>

<article>
<section><a><b>Get free weekly updates for offers that interest you.</b></a></section>
<section><a>-------------------</a></section>
<section><a>New offers from selected networks:</a></section>
<article id="adn_list">
<?php
foreach ($this->adnetsMonitored as $adnet):
?> 
        	<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><a><?= $adnet->adnet_name ?></a></section>
        	<section><img id="nmr<?= $adnet->adnet_id ?>" class="nmr" src="/images/icons/delete_button.png"></section>
<?php 
    endforeach;
?>
</article>
<div id="addnetpnl">
	<a id="addnetlnk" href="#" onclick="adnetList.add(); return false;">Add network ...</a>
	<?= $this->adnetsSelect; ?>
	<a id="savenetlnk" href="#" style="display: none;">Save</a>
</div>
<br/><br/>


<section><a>New offers from selected verticals (any network):</a></section>
<article id="mv_list">
<?php
foreach ($this->verticalsMonitored as $vertical):
?> 
        	<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><a><?= $vertical->vertical_name ?></a></section>
        	<section><img id="vmr<?= $vertical->vertical_id ?>" class="vmr" src="/images/icons/delete_button.png"></section>
<?php 
    endforeach;
?>
</article>
<div id="addvertpnl">
	<a id="addvertlnk" href="#" onclick="vertList.add(); return false;">Add vertical ...</a>
	<?= $this->verticalsSelect; ?>
	<a id="savevertlnk" href="#" style="display: none;">Save</a>
</div>
<br/><br/>


<!--<section><a>New offers for saved searches:</a></section>-->
<!--<article id="ss_list"></article>-->
<?php
//foreach ($this->savedSearchesMonitored as $savedSearch):
//?><!-- -->
<!--        	<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><a>-->
    <?php // echo $savedSearch->ss_name ?>
    <!--</a></section>-->
<!--        	<section><img id="smr-->
    <?php // echo $savedSearch->ss_id ?><!--" class="smr" src="/images/icons/delete_button.png"></section>-->
<?php //
//    endforeach;
//?>
<!--</article>-->
<!--<div id="addsspnl">-->
<!--	<a id="addsslnk" href="#" onclick="ssList.add(); return false;">Add saved search ...</a>-->
<!--	-->
    <?php // echo $this->savedSearchesSelect; ?>
<!--	<a id="savesslnk" href="#" onclick="ssList.save(); return false;" style="display: none;">Save</a>-->
<!--</div>-->
<br/><br/>