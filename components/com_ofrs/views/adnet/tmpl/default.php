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
JFactory::getDocument()->setTitle($this->item->name);

?>
<?php echo $this->toolbar->render(); ?>

<div class="" >
    <section class="col-xs-12 col-sm-8 col-no-gutters " id="adnet-content">
        <div class="adnet-content">
            <span class="row col-xs-12 col-no-gutters ofrs-content-subtitle">Network Details</span>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Number of offers:</div>
                <div class="col-xs-6 col-no-gutters "><b>
                    <a href="<?= JRoute::_('index.php?&option=com_ofrs&view=offers&Itemid=215&filter[ad_network_id][]='.$this->item->id) ?>" >
                        <?= $this->item->offer_count; ?></a></b>
                </div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Minimum Payment:</div>
                <div class="col-xs-6 col-no-gutters "><?= $this->item->currency_symbol.(int) $this->item->min_payment_amt ; ?></div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Payment Frequency:</div>
                <div class="col-xs-6 col-no-gutters "><?= $this->item->payment_frequency; ?></div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Payment Methods:</div>
                <div class="col-xs-6 col-no-gutters "><?= ucfirst(str_replace(',',', ',$this->item->payment_methods) ); ?></div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Payout Types:</div>
                <div class="col-xs-6 col-no-gutters "><?= str_replace(',',', ',$this->item->payout_types); ?></div>
            </div>
            <div class="row" >
                <div class="col-xs-6 col-no-gutters ofrs-content-normal">Tracking Platform:</div>
                <div class="col-xs-6 col-no-gutters "><?= $this->item->tracking_platform_name; ?></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-no-gutters ofrs-content-subtitle">Description</div>
                <div class="col-xs-12 col-no-gutters" style="margin: 0 0 50px 0;"><?= $this->item->description; ?></div>
            </div>
        </div>
    </section>
    <section class="col-xs-12 col-sm-4 text-center " id="adnet-logo-box" style="margin-top: 24px; float: right">
        <div class="adnet-logo">
        <?php
        /** MOCK Image
        $this->item->adnet_logo = 1;
        $this->item->logo_url = "/images/logo-offer-monster-2.svg";
        $this->item->logo_url = "/images/TEST.png";
        $this->item->logo_url = "/images/fav-01.png";
         */


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

        <div id="adnet-join-button" class="text-center" style="margin-top: 5rem">
            <?= OfrsHelper::getNetworkBoxButtonLayout($this->item->id, 'Join Network', 
            	$this->item->join_url,
            	[
                        "adnet_text_color" => $this->item->adnet_text_color,
                        "adnet_background_color" => $this->item->adnet_background_color,

                ]);?>
        </div>

    </section>
</div>
