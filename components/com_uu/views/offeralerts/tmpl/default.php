<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<div class="offer-alerts-body">
    <article class="offer-alerts-head">
        <section id="offer-alerts-text" class="offer-alerts-text">Get an email alert when the payout of a “Favorite Offer” increases!
            To make an offer a "Favorite”, click on its heart icon in the <a href="/index.php">offer list</a> or on the offer profile page. The heart icon will go from <i class="fa fa-heart-o fa-1x"></i> to <i class="fa fa-heart fa-1x"></i> and the offer will appear on this page.</section>
    </article>

    <?php if ( count($this->items) ) : ?>
        <p>Your Favorite Offers:</p>
        <ul>
    <?php endif; ?>

    <?php foreach ($this->items as $item): ?>
            <li><article class="row Rtable-row vertical-center-sm" id="<?= $id; ?>">
                <section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><?= $item->offer_name ?></section>
                <section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><?= $item->adnet_name ?></section>
                <section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><?= $item->payout_display ?></section>
                <section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><?= $item->payout_type ?></section>
                <section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><img id="omr<?= $item->offer_id ?>" class="omr" src="/images/icons/delete_button.png"></section>
            </article></li>

    <?php
        endforeach;
    ?>
    <?php if ( count($this->items) ) : ?>
        </ul>
    <?php endif; ?>

    <article>
    <section><a href="/index.php?option=com_ofrs&view=offers&Itemid=215">Add offer ...</a></section>
    </article>
</div>