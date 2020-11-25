<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<div class="offer-alerts-body">
    <article class="offer-alerts-head">
        <section id="offer-alerts-text" class="offer-alerts-text">Get an email alert when the payout of a “Favorite Offer” increases!
            To make an offer a "Favorite”, click on its heart icon in the <a href="/index.php"><b>offer list</b></a> or on the offer profile page. The heart icon will go from <i class="fa fa-heart-o fa-1x dark-blue"></i> to <i class="fa fa-heart fa-1x red"></i> and the offer will appear on this page.</section>


    <?php if ( count($this->items) ) : ?>
        <p><br><b>Your Favorite Offers:</b></p>
    </article>
    <?php else :?>
    </article>
    <?php endif; ?>

    <?php foreach ($this->items as $item): ?>
            <article class="row vertical-center-sm alerted-offer" id="<?= $id; ?>">
                <section class="col-xs-10 col-md-4 col-no-gutters vertical-center-sm offer-name remove-offer"><b>
                    <a href="/index.php?option=com_ofrs&view=offer&id=<?= $item->offer_id?>&Itemid=2474"><?= $item->offer_name ?></a></b>
                </section>
                <section class="col-xs-2 hidden-md hidden-lg col-no-gutters vertical-center-sm ">
                    <i id="omr<?= $item->offer_id ?>" class="omr om-delete red" style="font-size: 16px " > </i> </section>
                <section class="col-xs-3 col-md-2 col-no-gutters vertical-center-sm"><?= $item->payout_display ?></section>
                <section class="col-xs-2 col-md-2 col-no-gutters vertical-center-sm"><?= $item->payout_type ?></section>
                <section class="col-xs-6 col-md-3 col-no-gutters vertical-center-sm">
                        <?= OfrsHelper::getNetworkBoxButtonLayout($item->adnet_id, $item->adnet_name, null,
                            [
                                "name_text_color" => $item->name_text_color,
                                "name_background_color" => $item->name_background_color,

                            ]);

                        ?>
                </section>
                <i id="omr<?= $item->offer_id ?>" class="omr om-delete red hidden-xs hidden-sm" style="font-size: 16px;"> </i>
            </article>

    <?php
        endforeach;
    ?>

    <article>
    <section class="add-offers text-center">
        <button type="submit" href="/index.php?option=com_ofrs&view=offers&Itemid=215" onclick="window.location.href=this.getAttribute('href')">Add offers</button>
    </section>
    </article>
</div>
