<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			29th January, 2020
	@created		5th July, 2019
	@package		Offer Monster Backend
	@subpackage		default.php
	@author			SMIG <http://fuckitall.info>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>

/***[JCBGUI.joomla_module.default.1.$$$$]***/
<style>
.search_offer_name{
    width: 100%;
}
</style>

<?php
    $form = JForm::getInstance('ALABALA DIPSPALY ME', __DIR__. '/../forms/offerssearchform.xml');
    $fieldset = $form->getFieldset('offerssearch');
// "offerssearch_name"  "offerssearch_ad_network_id" "offerssearch_geo_targeting" "offerssearch_verticals"  "offerssearch_payout_type" "offerssearch_adnet_id"
?>
<hl></hl>

<form class="ofrs-srch-form" action="<?php echo JRoute::_('index.php?option=com_ofrs'); ?>" method="get">
    <div class="row row-no-gutters">
        <div class="col-xs-12 col-no-gutters">
            <?= $fieldset["offerssearch_name"]->renderField(); ?>
            <span aria-hidden="true" class="btn-search" type="submit" onclick="submitOffersForm()">
                <i class="fa fa-search"></i>
            </span>
        </div>
    </div>

    <div class="row row-no-gutters " id="dropdowns-container">
        <!--                dropdown-button ddb-left -->
        <div id="button_filter_ad_network_id" class="col-xs-12 col-sm-6 col-md-3 col-no-gutters dropdown-button" >
            <div class="col-xs-12 col-no-gutters">
                <?= $fieldset["offerssearch_ad_network_id"]->renderField(); ?>
            </div>
        </div>

        <div id="button_filter_geo_targeting" class="col-xs-12 col-sm-6 col-md-3 col-no-gutters dropdown-button">
            <div class="col-xs-12 col-no-gutters">
                <?= $fieldset["offerssearch_geo_targeting"]->renderField(); ?>
            </div>
        </div>

        <div id="button_filter_verticals" class="col-xs-12 col-sm-6 col-md-3 col-no-gutters dropdown-button">
            <div class="col-xs-12 col-no-gutters">
                <?= $fieldset["offerssearch_verticals"]->renderField(); ?>
            </div>
        </div>

        <div id="button_filter_payout_type" class="col-xs-12 col-sm-6 col-md-3 col-no-gutters dropdown-button">
            <div class="col-xs-12 col-no-gutters">
                <?= $fieldset["offerssearch_payout_type"]->renderField(); ?>
            </div>
        </div>
    </div>
</form>/***[/JCBGUI$$$$]***/

