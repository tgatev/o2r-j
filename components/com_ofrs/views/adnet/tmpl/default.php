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
$this->item->logo_url = $_SERVER['REQUEST_URI'] . '&task=getImage';
?>
<?php echo $this->toolbar->render(); ?>
<div class="row" >
    <section class="col-xs-12 col-no-gutters">
        <div class="ofrs-title-space">
            <div class="ofrs-content-title"><?php echo $this->item->name; ?></div>
        </div>
    </section>
</div>

<div class="row" >
    <section class="col-xs-12 col-sm-8 col-no-gutters">
        <span class="row ofrs-content-subtitle">Network Details</span>
        <section class="col-xs-12" >
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Number of offers:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold">
                    <a href="<?= JRoute::_('index.php?&option=com_ofrs&view=offers&Itemid=215&filter[ad_network_id][]='.$this->item->id) ?>" >
                        <?= $this->item->offer_count; ?></a>
                </div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Minimum Payment:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold"><?= $this->item->min_payment_amt; ?></div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Payment Frequency:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold"><?= $this->item->payment_frequency; ?></div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Payment Methods:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold"><?= $this->item->payment_methods; ?></div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Payout Types:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold"><?= $this->item->payout_types; ?></div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Tracking Platform:</div>
                <div class="col-xs-6 col-no-gutters ofrs-content-bold"><?= $this->item->tracking_platform_name; ?></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-no-gutters ofrs-content-subtitle">Description</div>
                <div class="col-xs-12 col-no-gutters ofrs-content-normal" style="margin: 0 0 50px 0;"><?= $this->item->description; ?></div>
            </div>
        </section>
    </section>
    <section class="col-xs-12 col-sm-4 text-center" style="margin: 30px 0px;">
        <div class="adnet-logo">
        <?php
        if ($this->item->adnet_logo) {
            ?>

            <img id="<?= $this->item->id ?>" src="<?= $this->escape($this->item->logo_url); ?>"
                 alt="Landing Page"
                 onerror="jQuery('img#<?= $this->item->id ?>').replaceWith(`<?= htmlspecialchars(OfrsHelper::getPreviewNotFound()) ?>`);" align="middle" style="display: inline">

            <?php
        }else{
            echo OfrsHelper::getPreviewNotFound("Logo not found.");
        }
        ?>
        </div>
        <div style="margin-top: 4rem">
            <a onclick="window.open('<?= $this->item->join_url; ?>')"
                    class="adnet-button">
                <span class="icon-joomla" aria-hidden="true"></span> JOIN NETWORK
            </a>
        </div>
    </section>
</div>
