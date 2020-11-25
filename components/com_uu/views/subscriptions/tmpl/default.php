<?php

// // No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>
<script  type="text/javaScript">
    jQuery(document).ready(function () {
        for ( let dropdown_id of  [ "jform_adnetlist", "jform_vertlist" ] ){
            console.log(dropdown_id);
            dropdownGenerator( dropdown_id,  {
                enableFiltering: false,
                enableClickableOptGroups: false,
                enableCaseInsensitiveFiltering: false,
                includeResetOption: false,
            } );
            // jQuery("button#"+dropdown_id+" > span").removeClass('col-xs-11') ;
            // jQuery("button#"+dropdown_id+" > span").addClass('col-xs-10') ;
        }

    });
</script>
<div class="subscriptions-body">
    <article id="adn_list" class="adnet-list-view">
        <section class="networks-head" ><p>Subscribe to receive a weekly email with new offers from the networks and verticals you are interested in!<br>
                Add networks and verticals you work with.<br>
                We will compile a list of all new offers from your networks and verticals and will send it to you in one weekly email.</p></section>
        <section class="row">
        	
        	 <?php
        		if ($this->adnetsMonitored != null)
        			echo('<section class="col-xs-12"><p><b>Your networks:</b></p> </section>');
        	 ?>
            
        </section>
    <?php
      foreach ($this->adnetsMonitored as $adnet):
    ?>
      <article class="list-add-network row" >
                <section class=""> <?= OfrsHelper::getNetworkBoxButtonLayout($adnet->adnet_id, $adnet->adnet_name, null,
                        [
                            "name_text_color" => $adnet->name_text_color,
                            "name_background_color" => $adnet->name_background_color,

                        ]);
                    ?> <i id="nmr<?= $adnet->adnet_id ?>" class="nmr om-delete red" style="font-size: 16px;" ></i></section>
      </article>
    <?php
        endforeach;
    ?>
    </article>

    <div id="addnetpnl" class="addnetwork-btn-box">
        <button type="submit" id="addnetlnk" href="#" onclick="adnetList.add(); return false;">Add Networks</button>
        <section class="row">
            <section class="dropdown-box col-xs-8 col-md-3">
                <?= $this->adnetsSelect; ?>
            </section>
            <section class="save-btn col-xs-2 col-md-1" >
                <button type="submit" id="savenetlnk" href="#" style="display: none;">Save</button>
            </section>
        </section>
    </div>
    <br/><br/>
    <article id="mv_list">
        <section class="row">

			<?php
        		if ($this->verticalsMonitored != null)
        			echo('<section class="col-xs-12"><p><b>Your verticals:</b></p> </section>');
        	 ?>

        </section>
    <?php
    foreach ($this->verticalsMonitored as $vertical):
    ?>
        <article class="list-add-vertical row" >
                <section class="">
                    <a href="/index.php?option=com_ofrs&view=offers&Itemid=215&filter[verticals][0]=<?=$vertical->vertical_id?>"><?= $vertical->vertical_name ?></a>
                    <i id="vmr<?= $vertical->vertical_id ?>" class="vmr om-delete red" style="font-size: 16px;"></i>
                </section>
        </article>
    <?php
        endforeach;
    ?>
    </article>
    <div id="addvertpnl" class="addvertical-btn-box">
        <button type="submit" id="addvertlnk" href="#" onclick="vertList.add(); return false;">Add Verticals</button>
        <section class="row">
            <section class="dropdown-box col-xs-8 col-md-3">
            <?= $this->verticalsSelect; ?>
            </section>
            <section class="save-btn col-xs-2 col-md-1" >
                <button type="submit" id="savevertlnk" href="#" style="display: none;">Save</button>
            </section>
        </section>
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

</div>
