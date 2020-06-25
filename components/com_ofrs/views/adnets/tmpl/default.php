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
$search_filters_data = OfrsHelper::parseOfrsParams('adnets.filter');
// Calculate pagination results to be displayed
$app = JFactory::getApplication();
$offset = $app->input->get('limitstart') ;
$first_element_number = 1+$offset ;
$last_element_number = $offset+ $search_filters_data["count_per_page"]  ;
$results_count = $this->get('CountsOfFilterResults');

/***[JCBGUI.site_view.php_view.26.$$$$]***/
// JHtml::_('formbehavior.chosen', 'select');  // За да работи добре search-формата/***[/JCBGUI$$$$]***/

// generate Pagination HTML view
if(isset($this->items) && isset($this->pagination) && isset($this->pagination->pagesTotal) && $this->pagination->pagesTotal > 1){
    $pagination_html =  $this->pagination->getPagesLinks();
}
?>

<script  type="text/javaScript">

    jQuery(document).ready(function () {
        for(id in DropdownsMap2){
            dropdownGenerator( id, DropdownsMap2[id] );
            dropdownGenerator( id+'1', DropdownsMap2[id] ); // Duplicate dropdowns at the bottom

        }
        // Fill filters with data of previous request
        const init_form_data =  JSON.parse('<?= json_encode($search_filters_data); ?>');
        //console.log(init_form_data);
        for( let index in init_form_data ){
            let id = 'dd_'+ index;
            if(id in DropdownsMap2){
                jQuery('#'+id).multiselect('select', init_form_data[index]);
                jQuery('#'+id).multiselect('refresh')
                change(null,null,null, id, null);
            }
        }
    });


</script>
<form action="<?php echo JRoute::_('index.php?option=com_ofrs&view=adnets'); ?>" method="post" id="adminForm">
    <?php echo $this->toolbar->render(); ?>
    <!--[JCBGUI.site_view.default.26.$$$$]-->
    <div class="visible-xs ofrs-table-header"></div>
    <div class="row pagination-line">

        <div id="order_definitions"  class="col-xs-6 col-md-4 col-lg-3 col-no-gutters">
            <div class="results_offset col-xs-12 ofrs-table-header pager-stats-xs">
                <span class="page-numbers"><?= $first_element_number ?> - <?= $last_element_number < $results_count? $last_element_number : $results_count ?></span> of <span class="page-numbers"><?= number_format($results_count);?></span> results
            </div>
        </div>
        <div class="col-xs-6 col-md-5 col-md-offset-3 col-lg-4 col-lg-offset-5 col-no-gutters " id="order_definitions">
            <div class="col-xs-6 col-sm-5 col-sm-offset-2 col-md-4 col-md-offset-4" >
                <select class="" id="dd_sort_by" name="filter[sort_by]">
                    <option value="default" selected>Sort by</option>
                    <option value="43" selected>Network</option>
                    <option value="24"># Offers</option>
                    <option value="49">Updated</option>
                </select>
            </div>
            <div class="col-xs-6 col-sm-5 col-md-4" >
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
        <div class="row ofrs-table-header hidden-xs">
            <div class="row" id="offers-table-header">
                <section class="col-md-12 ">
                    <section class="col-xs-4 col-md-3  col-no-gutters">
                        <div class="col-md-12 table-header sort-btn"
                                                          sort_by="43"
                                                          direction="<?= OfrsHelper::getDirections("43", $search_filters_data) ?>" >
                            <i class=" fa fa-sort<?= ($search_filters_data['sort_by'] == 43 )? strtolower("-".$search_filters_data['sort_direction']): "" ?>">
                            </i>NETWORK</div>
                    </section>
                    <section class="col-xs-4 col-md-7 hidden-sm col-no-gutters ">DESCRIPTION</section>
                    <section class="col-xs-4 col-sm-offset-4 col-md-2 col-md-offset-0 col-no-gutters">
                        <section class="col-xs-6 col-md-6 col-no-gutters table-header sort-btn "
                                 sort_by="24"
                                 direction="<?= OfrsHelper::getDirections("24", $search_filters_data) ?>" >
                            <i class=" fa fa-sort<?= ($search_filters_data['sort_by'] == 24 )? strtolower("-".$search_filters_data['sort_direction']): "" ?>">
                            </i>#OFFERS</section>
                        <section class="col-xs-6 col-md-6 col-no-gutters table-header sort-btn"
                                 sort_by="49"
                                 direction=dd_sort_by"<?= OfrsHelper::getDirections("49", $search_filters_data) ?>" >
                            <i class=" fa fa-sort<?= ($search_filters_data['sort_by'] == 49 )? strtolower("-".$search_filters_data['sort_direction']): "" ?>">
                            </i>UPDATED</section>
                    </section> <!-- col-xs-pull-4 col-sm-pull-4 -->
                </section>
            </div>
        </div>

        <?php
            if(count($this->items) == 0 ){
                echo '<article class="row Rtable-row text-center text-danger no-results-article" > No results found. Try expanding your search. </article>';
            }

            foreach ($this->items as $item):
                $id = $item->adnet_id;
                 preg_match("/^(.*?\.){3}/", $item->adnet_description, $matches);
                $description_short = $matches[0];
                $network_style = OfrsHelper::generateStyles($item->adnet_id*20);
            ?>
        <article class="row Rtable-row row-no-gutters" id="<?= $id ;?>">
            <section class="col-xs-12 col-sm-8 col-md-3 Rtable-cell col-no-gutters ">
                <div class="" >
                    <a class="offer-network-box"
                       style="<?php echo $network_style['onmouseleave']; ?>"
                       id="offer-network_<?= $id ?>"
                       onmouseover=" jQuery(this).attr('style',' <?php echo $network_style['onmouseover']; ?>') "
                       onmouseleave=" jQuery(this).attr('style',' <?php echo $network_style['onmouseleave']; ?>') "
                       title="<?= $this->escape($item->adnet_name) ?>"
                       href="<?= OfrsHelperRoute::getAdnetRoute($item->adnet_id); ?>"
                    >
                        <i class="fa fa-external-link"
                           aria-hidden="true" style="padding: 5px 5px 5px 0;"></i> <?php echo(substr( $this->escape($item->adnet_name) , 0 , 20)); ?>
                    </a>
<!--                    <a href=" <?//= OfrsHelperRoute::getAdnetRoute($item->adnet_id);
                     ?>"><?//= $this->escape($item->adnet_name);
                     ?></a>-->

                </div>
            </section>
            <section class="hidden-xs hidden-sm col-md-7 adnet-description Rtable-cell">
                <?= isset($description_short)? substr($this->escape($description_short), 0 , 300)." ...": ""; ?>
            </section>
            <section class="col-xs-6 col-sm-2 col-md-1 offer-count Rtable-cell">
                <div class="col-xs-12 ofrs-table-header-mini col-no-gutters visible-xs" >#OFFERS</div>
                <a href="<?=JRoute::_('index.php?&option=com_ofrs&view=offers&Itemid=215&filter[ad_network_id][]='.$item->adnet_id) ?>" > <?php echo $this->escape($item->offer_count); ?> </a>
            </section>

            <section class="col-xs-6 col-sm-2 col-md-1 Rtable-cell adnet-updated">
                <div class="col-xs-12 ofrs-table-header-mini col-no-gutters visible-xs" >UPDATED</div>
                <?php echo date_format(date_create($item->adnet_modified), "M d"); ?>
            </section>

            <section class="visible-xs visible-sm col-xs-12 Rtable-cell adnet-description">
                <div class="col-xs-12 ofrs-table-header-mini col-no-gutters visible-xs visible-sm" >DESCRIPTION</div>
                <?= isset($description_short)? substr($this->escape($description_short), 0 , 300)." ...": ""; ?>
            </section>

        </article>
        <?php endforeach; ?>
    </div>

    <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>"/><!--[/JCBGUI$$$$]-->
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>
    <div class="row pagination-line" >

<!--        <div class="col-xs-12 col-md-6 col-no-gutters visible-xs" style="margin: 2rem 0 0 0;" >-->
<!--            <div class="col-xs-6">-->
<!--                <select class="" id="dd_sort_by1" name="filter[sort_by]">-->
<!--                    <option value="default" selected>Sort by</option>-->
<!--                    <option value="43">Network</option>-->
<!--                    <option value="24"># Offers</option>-->
<!--                    <option value="49">Updated</option>-->
<!--                </select>-->
<!--            </div>-->
<!--            <div class="col-xs-6" >-->
<!--                <select class="" id="dd_count_per_page1" name="filter[count_per_page]">-->
<!--                    <option value="5">5 per page</option>-->
<!--                    <option value="10">10 per page</option>-->
<!--                    <option value="20" selected>20 per page</option>-->
<!--                    <option value="50">50 per page</option>-->
<!--                    <option value="100">100 per page</option>-->
<!--                    <option value="200">200 per page</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </div>-->
        <div class="col-xs-12 col-md-6 col-md-offset-6 col-lg-6 col-no-gutters pagination-align">
            <?= ($pagination_html)? $pagination_html : "" ?>
        </div>
    </div>

