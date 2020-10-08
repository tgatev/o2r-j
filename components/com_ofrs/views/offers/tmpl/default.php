<?php

/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			26th November, 2019
	@created		5th July, 2019
	@package		Offers
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

$search_filters_data = OfrsHelper::parseOfrsParams('ofrs.filter_parsed');

$app = JFactory::getApplication();

// Calculate pagination results to be displayed
$offset = $app->input->get('limitstart');
$first_element_number = 1 + $offset;
$last_element_number = $offset + $search_filters_data["count_per_page"];
if ($last_element_number == 0) $first_element_number = 0;

$results_count = $this->get('CountsOfFilterResults');

/***[JCBGUI.site_view.php_view.27.$$$$]***/

if (isset($this->items) && isset($this->pagination) && isset($this->pagination->pagesTotal) && $this->pagination->pagesTotal > 1) {
    $this->pagination->displayedPages = 5;
    $this->pagination->limitstart = $offset;
    $pagination_html = $this->pagination->getPagesLinks();
}
?>

<script type="text/javaScript">
    jQuery(document).ready(function () {
        for (id in DropdownsMap2) { // DropdownMap2 defuned in offers.js
            dropdownGenerator(id, DropdownsMap2[id]);
        }

        // Fill filters with data of previous request
        const init_form_data = JSON.parse('<?= json_encode($search_filters_data); ?>');

        for (let index in init_form_data) {
            let id = "dd_" + index;
            if (id in DropdownsMap2) {
                jQuery('#' + id).multiselect('select', init_form_data[index]);
                jQuery('#' + id).multiselect('refresh');
                change(null, null, null, id, null)
            }
        }

    });

</script>
<form action="<?php echo JRoute::_('index.php?option=com_ofrs&view=offers&sss=1&limitstart=0'); ?>" method="post"
      id="adminForm">
    <?php echo $this->toolbar->render(); ?>
    <!--[JCBGUI.site_view.default.27.$$$$]-->

    <div class="row pagination-line">
        <div id="order_definitions"
             class="col-sm-6 col-sm-offset-0 col-md-4 col-lg-3 col-no-gutters">
            <div class="results_offset col-xs-12 ofrs-results pager-stats-xs">
                <span class="page-numbers"><?= $first_element_number > $results_count ? $results_count : $first_element_number ?> - <?= $last_element_number < $results_count ? $last_element_number : $results_count ?></span>
                of <span class="page-numbers"><?= number_format($results_count); ?></span> results
            </div>
        </div>
        <div class="col-xs-6 col-md-5 col-md-offset-3 col-lg-4 col-lg-offset-5 col-no-gutters hidden-xs"
             id="order_definitions">
            <div class="col-xs-6 col-sm-5 col-sm-offset-2 col-md-4 col-md-offset-4">
                <select class="" id="dd_sort_by" name="filter[sort_by]">
                    <!--                    <option value="default" selected>Sort by</option>-->
                    <option value="45">Offer name</option>
                    <option value="43">Network</option>
                    <option value="22.asc" direction="asc">Payout</option>
                    <option value="22.desc" direction="desc">Payout</option>
                    <option value="23">Type</option>
                    <option value="49">Updated</option>
                </select>
            </div>
            <div class="col-xs-6 col-sm-5 col-md-4">
                <select class="" id="dd_count_per_page" name="filter[count_per_page]">
                    <option value="5">5 per page</option>
                    <option value="10">10 per page</option>
                    <option value="20" selected>20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                    <option value="200">200 per page</option>
                </select>
            </div>
        </div>

    </div>
    <div class="row pagination-content">
        <div class="row ofrs-table-header hidden-xs hidden-sm " id="offers-table-header">
            <section class="col-md-4 col-no-gutters vertical-center-sm">
                <div class="col-md-2 hidden-xs hidden-sm table-header">
                    <!-- Eye Column --></div>
                <div class="col-md-10 hidden-xs hidden-sm table-header sort-btn"
                     sort_by="45"
                     direction="<?= OfrsHelper::getDirections("45", $search_filters_data) ?>">
                    <i class=" fa fa-sort<?= ($search_filters_data['sort_by'] == 45) ? strtolower("-" . $search_filters_data['sort_direction']) : "" ?>"></i>OFFER
                </div>
            </section>
            <section class="col-md-5 col-no-gutters">
                <div class="col-md-3 hidden-xs hidden-sm table-header sort-btn"
                     sort_by="<?php // Define sorting params Exceptional
                     $parts = explode('.', $search_filters_data["sort_by"]);
                     $payout_direction = null;
                     if ($parts[0] == 22) {
                         echo ($parts[1] == 'asc') ? '22.' . $payout_direction = "desc" : '22.' . $payout_direction = "asc";
                     } else {
                         $payout_direction = "asc";
                         echo "22.$payout_direction";
                     }
                     ?>"
                     direction="<?= $payout_direction ?>">
                    <i class=" fa fa-sort<?= ($search_filters_data['sort_by'] == '22.asc' || $search_filters_data['sort_by'] == '22.desc') ? strtolower("-" . $search_filters_data['sort_direction']) : "" ?>"
                    ></i>PAYOUT
                </div>
                <div class="col-md-2 hidden-xs hidden-sm table-header col-no-gutters sort-btn "
                     sort_by="23"
                     direction="<?= OfrsHelper::getDirections("23", $search_filters_data) ?>">
                    <i class=" fa fa-sort<?= ($search_filters_data['sort_by'] == 23) ? strtolower("-" . $search_filters_data['sort_direction']) : "" ?>">
                    </i>TYPE
                </div>
                <div class="col-md-3 hidden-xs hidden-sm table-header col-no-gutters">COUNTRIES
                </div>
                <div class="col-md-4 hidden-xs hidden-sm table-header">VERTICALS
                </div>
            </section>
            <section class="col-md-3 col-no-gutters">
                <div class="col-md-8 hidden-xs hidden-sm table-header offer-network sort-btn"
                     sort_by="43"
                     direction="<?= OfrsHelper::getDirections("43", $search_filters_data) ?>">
                    <i class=" fa fa-sort<?= ($search_filters_data['sort_by'] == 43) ? strtolower("-" . $search_filters_data['sort_direction']) : "" ?>">
                    </i>NETWORK
                </div>
                <div class="col-md-4 hidden-xs hidden-sm table-header offer-updated sort-btn col-no-gutters text-right "
                     sort_by="49"
                     direction="<?= OfrsHelper::getDirections("49", $search_filters_data) ?>">
                    <i class=" fa fa-sort<?= ($search_filters_data['sort_by'] == 49 || $search_filters_data['sort_by'] == "default") ? strtolower("-" . $search_filters_data['sort_direction']) : "" ?>"> </i>UPDATED
                </div>
            </section>
        </div>
        <?php
        if (count($this->items) == 0) {
            echo '<article class="row Rtable-row text-center text-danger no-results-article" > No results found. Try expanding your search. </article>';
        }

        foreach ($this->items as $item):
            $id = $item->id;
            $network_style = OfrsHelper::generateStyles($item->ad_network_id * 20);
            $target = '_blank';
            // Has Preview check
            if (strlen($item->preview_url) < 3) {
                $this->item->preview_url = '/#';
                $target = '';
            }
            if(!$item->lp_thumbnail) $target = '';
            ?>
            <article class="row Rtable-row vertical-center-sm" id="<?= $id ?>">
                <section class="col-xs-12 col-md-4 col-no-gutters vertical-center-sm">
                        <div class="offer-eye-icon-box col-xs-3 col-no-gutters vertical-center-sm">
                        <!-- Eye Icon -->
                            <?php if ($target) : ?>
                                <a href="<?= $item->preview_url ?>" target="<?= $target ?>" style="display: contents;">
                                    <div class="ofrs-preview-icon " id="ofrs-preview-icon_<?= $id ?>">
                                        <div class="icon om-eyedollar"></div>
                                    </div>
                                </a>
                            <?php else: ?>
                                <div class=" ofrs-preview-no-icon " id="ofrs-preview-icon_<?= $id ?>">
                                    <div class="icon om-eye-invisible" style="color: #757f89"></div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Name -->
                        <div class="offer-name col-xs-9 col-no-gutters vertical-center-sm" id="offer-name_<?= $id ?>">
                            <a href="<?php echo OfrsHelperRoute::getOfferRoute($item->id); ?>">
                                <?php echo $this->escape($item->name); ?>
                            </a>
                        </div>
                </section>
                <section class="col-xs-12 col-md-5 col-no-gutters ofrs-article-content vertical-center-sm">
                    <!-- Payout -->
                    <div class="col-xs-6 col-sm-3 col-no-gutters vertical-center-sm">
                        <span class="col-xs-12 hidden-lg hidden-md ofrs-table-header">
                            <label class="-mini">PAYOUT</label>
                        </span>
                        <span class="col-xs-12 offer-payout" id="offer-payout_<?= $id ?>">
                		<?php echo $item->display; ?>
                	</span>
                    </div>
                    <!-- Type -->
                    <div class="col-xs-6 col-sm-2 col-no-gutters vertical-center-sm">
                        <span class="col-xs-12 hidden-lg hidden-md ofrs-table-header">
                            <label class="-mini">TYPE</label>
                        </span>
                        <span class="col-xs-12 offer-payout-type"
                              id="offer-payout-type_<?= $id ?>"> <?php echo($this->escape($item->payout_type)); ?></span>
                    </div>
                    <!-- Geo Targeting (Countries) -->
                    <div class="col-xs-6 col-sm-3 col-no-gutters vertical-center-sm">
                        <span class="col-xs-12 hidden-lg hidden-md ofrs-table-header">
                            <label class="-mini">COUNTRIES</label>
                        </span>
                        <?php
                        $countries_count = substr_count($item->geo_targeting_full, ',') + 1;
                        if ($countries_count > 5):
                            $parts = explode(',', $item->geo_targeting_full)
                            ?>
                            <span class="col-xs-12 offer-geotargeting" id="offer-geotargeting_<?= $id ?>"
                                  title="<?= $item->geo_targeting_full ?>"><span
                                        class="countriesCircle"><?= $countries_count ?></span> Locations</span>
                        <?php else : ?>
                            <span class="col-xs-12 offer-geotargeting"
                                  id="offer-geotargeting_<?= $id ?>"> <?= $this->escape($item->geo_targeting_full) ?> </span>
                        <?php endif; ?>
                    </div>
                    <!-- Verticals -->
                    <div class="col-xs-6 col-sm-4 col-no-gutters offer-verticals vertical-center-sm">
                        <span class="col-xs-12 hidden-lg hidden-md ofrs-table-header">
                            <label class="-mini">VERTICALS</label>
                        </span>
                        <span class="col-xs-12 offer-verticals"
                              id="offer-verticals_<?= $id ?>"><?php
                            $vert_data = explode(',', $item->verticals_full);
                            foreach ($vert_data as $key => $vertical_id) {
                                if ($this->verticals_map[$vertical_id]) {
                                    echo JHtml::link('', $this->verticals_map[$vertical_id], [ //"index.php?option=com_ofrs&view=offers&Itemid=215&filter[verticals][]=".$vertical_id
                                        'id' => 'vertical-id', 'class' => 'vertical-id-add-to-filter', 'filter' => $vertical_id,
                                    ]);
                                    if ($key !== array_key_last($vert_data)) echo ", ";
                                }
                            }
                            ?>
                            </span>
                    </div>
                </section>

                <section class="col-xs-12 col-md-3 col-no-gutters ofrs-article-content vertical-center-sm ">
                    <!-- NETWORK Tags -->
                    <div class="col-xs-6 col-sm-6 col-md-9 col-no-gutters  vertical-center-sm">
                        <span class="col-xs-12 col-sm-12 hidden-lg hidden-md ofrs-table-header">
                            <label class="-mini">NETWORK </label>
                        </span>
                        <div><?= OfrsHelper::getNetworkBoxButtonLayout($item->adnet_id, $item->adnet_name, null,
                        	[
                                "adnet_text_color" => $item->adnet_text_color,
                                "adnet_background_color" => $item->adnet_background_color,

                            ]); ?> </div>

                    </div>
                    <!-- Updated On -->
                    <div class="col-xs-6 col-sm-4 col-sm-offset-2 col-md-3 col-md-offset-0 col-no-gutters  vertical-center-sm ">
                            <span class="col-xs-12 col-sm-12 hidden-lg hidden-md ofrs-table-header">
                                <label class="-mini">UPDATED</label>
                            </span>
                        <span class="col-xs-12 offer-updated " id="offer-updated_<?= $id ?>">
                            <?php echo date_format(date_create($item->modified), "M j"); ?>
                        </span>
                    </div>
                </section>
            </article>
        <?php endforeach; ?>
    </div>
    <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>"/>
    <!--[/JCBGUI$$$$]-->

    <div class="row" style="margin: 0 -3rem 0 -3rem;">

        <div class="col-xs-12 col-md-6 col-md-offset-6 col-lg-6  col-lg-offset-6 col-no-gutters pagination-align">
            <?= ($pagination_html) ? $pagination_html : "" ?>
        </div>
    </div>
</form>
