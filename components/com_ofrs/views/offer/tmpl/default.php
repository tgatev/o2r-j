<?php
/*
 * ----------------------------------------------------------------------------------| www.vdm.io |----/
 * Delta Flip
 * /-------------------------------------------------------------------------------------------------------/
 *
 * @version 1.0.21
 * @build 26th November, 2019
 * @created 5th July, 2019
 * @package Offers
 * @subpackage default.php
 * @author SMIG <http://fuckitall.info>
 * @copyright Copyright (C) 2019. All Rights Reserved
 * @license GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 * ____ _____ _____ __ __ __ __ ___ _____ __ __ ____ _____ _ _ ____ _ _ ____
 * (_ _)( _ )( _ )( \/ )( ) /__\ / __)( _ )( \/ )( _ \( _ )( \( )( ___)( \( )(_ _)
 * .-_)( )(_)( )(_)( ) ( )(__ /(__)\ ( (__ )(_)( ) ( )___/ )(_)( ) ( )__) ) ( )(
 * \____) (_____)(_____)(_/\/\_)(____)(__)(__) \___)(_____)(_/\/\_)(__) (_____)(_)\_)(____)(_)\_) (__)
 *
 * /------------------------------------------------------------------------------------------------------
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$this->item->thumbnail_url = $_SERVER['REQUEST_URI'] . '&task=getImage';
// Restrict urls: there is no valid shot then 3 symbols
$target = '_blank';
if (strlen($this->item->preview_url) < 3) {
    $this->item->preview_url = null;
    $target = '';
}
?>
<?php echo $this->toolbar->render(); ?>

<div class="row">
    <section class="col-xs-12 col-no-gutters">
        <div class="ofrs-title-space">
            <div class="ofrs-content-title"><?= $this->item->name; ?></div>
        </div>
    </section>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-8 col-no-gutters">
        <span class="row ofrs-content-subtitle">Offer Details</span>
        <section class="col-xs-12 offer-content">
            <div class="row">
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Last Updated:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold"><?= date("j M Y", strtotime($this->escape($this->item->modified))) ?></div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Payout:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold"><?= $this->item->ofrs_offer_payout_payout_display . ' ' . $this->item->ofrs_payout_type_name; ?></div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Verticals:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold"><?= $this->item->verticals; ?></div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Countries:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold"><?= $this->item->geo_targeting; ?></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-no-gutters ofrs-content-subtitle">Description</div>
                <div class="col-xs-12 col-no-gutters ofrs-content-normal" ><?= $this->item->description; ?></div>
            </div>
            <div class="row hidden">
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Network:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold">
                    <a href="
                <?=
                    JRoute::_('index.php?option=com_ofrs&view=adnet&id=' . $this->item->ad_network_id . '&Itemid=2473')
                    ?>
                ">
                        <?=
                        $this->item->ad_network_name;
                        ?>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="row" style="padding: 0 0 100px 0">
                    <div class="offer-adnet-area" style="padding: 2rem 0 2rem 0">
                        <div class="col-xs-12 col-md-4 ofrs-adnet-area text-center">
                            <a href="<?=JRoute::_('index.php?option=com_ofrs&view=adnet&id=' . $this->item->ad_network_id . '&Itemid=2473')?>">
                                <?=
                                $this->item->ad_network_name;
                                ?>
                            </a>
                        </div>

                        <div class="col-xs-12 col-md-4 text-center" style="padding: 2rem 0 2rem 0;">

                            <a target="_blank" href="<?= $this->item->ad_network_join_url; ?>" class="ofrs-button">
                                <span class="icon-joomla" aria-hidden="true"></span> Join Network</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div id="preview-box" class="hidden-xs col-sm-4 text-center">
        <div id="preview-btn" class="landing-page-link">
          <?php if ($this->item->preview_url) : ?>  <a href="<?= $this->item->preview_url ?>" target="<?= $target ?>">Go To Landing Page</a> <?php endif; ?>
        </div>
        <<?= $this->item->preview_url ? "a": "div" ?> href="<?= $this->item->preview_url; ?>" target="<?= $target ?>">
            <div class="offer-preview">
                <div class="offer-lp">
                    <img id="<?= $this->item->id ?>" src="<?= $this->escape($this->item->thumbnail_url); ?>"
                         alt="Landing Page" width="100%"
                         onerror="jQuery('img#<?= $this->item->id ?>').replaceWith(`<?= htmlspecialchars(OfrsHelper::getPreviewNotFound()) ?>`);">
                </div>
            </div>
        </>
    </div>
</div>

