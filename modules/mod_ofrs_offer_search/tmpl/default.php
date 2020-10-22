<?php

// No direct access
defined('_JEXEC') or die();
//
$search_filters_data = OfrsHelper::parseOfrsParams('ofrs.filter_parsed');
/* @var JForm $form provided by helper.php from xml definition */
$form = getSearchForm();
$form->setValue('search', '', $search_filters_data['search']);

require_once(JPATH_SITE.'/components/com_uu/models/subscriptions.php');
require_once(JPATH_SITE.'/components/com_uu/helpers/ustring.php');
require_once(JPATH_SITE.'/components/com_uu/libraries/uufieldinterface.php');

?>

<script>
    let initial_display_type = getDisplayType();
    const init_form_data =  JSON.parse('<?= json_encode($search_filters_data); ?>');
    // console.log(init_form_data);

    // Dropdown multiselect attach functionality
    const DropdownsMap = {
        filter_ad_network_id: {
            nonSelectedText: 'Networks' ,
        },
        filter_geo_targeting: {
            nonSelectedText: 'Countries',
        },
        filter_verticals: {
            nonSelectedText: 'Verticals',

        },
        filter_payout_type: {
            nonSelectedText: 'Payout Type',
        },
        modal_count_per_page: {
            enableFiltering: false,
            enableClickableOptGroups: false,
            enableCaseInsensitiveFiltering: false,
            includeResetOption: false,
            onChange: function(option, checked, select){
                jQuery('input#count_per_page[type=hidden]').val( jQuery(option).val() ); // sync values
            }
        },
        modal_sort_by: {
            nonSelectedText: 'Sort By ...',
            enableFiltering: false,
            enableClickableOptGroups: false,
            enableCaseInsensitiveFiltering: false,
            includeResetOption: false,
            enableHTML: true,
            onChange: function(option, checked, select){
                jQuery('input#sort_by[type=hidden]').val( jQuery(option).val() ); // sync values
            },
            optionLabel: function(element){
                let el = jQuery(element);
                let direction = jQuery(el).attr('direction')
                if(direction){
                    el.append(order_icons[direction]);
                } ;
                return  el.attr('label') || el.html();
            },
        },
    };

    // on Resize filters moving
    jQuery( window ).resize(function() {
        // Fix form elements displaying for different resolutions
        let display_type = getDisplayType()
        if(display_type !== initial_display_type ){
            switch (initial_display_type+display_type) {
                case "mdsm": // do nothing
                    break;
                case "mdxs": // Move filters to modal
                    jQuery( "form.ofrs-srch-form > #dropdowns-container > div" ).detach().prependTo('.modal-body > #dropdowns-container ');
                    break;
                case "smxs": // Move filters to modal
                    jQuery( "form.ofrs-srch-form > #dropdowns-container > div" ).detach().prependTo('.modal-body > #dropdowns-container ');
                    break;
                case "xsmd":  // Move filters out of modal
                    jQuery( ".modal-body > #dropdowns-container > div" ).detach().prependTo('form.ofrs-srch-form > #dropdowns-container');
                    break;
                case "xssm": // Move filters out of modal
                    jQuery( ".modal-body > #dropdowns-container > div" ).detach().prependTo('form.ofrs-srch-form > #dropdowns-container');
                    break;
                case "smmd": // do nothing
                    break;
            }
            // At the end update initial display type
            initial_display_type = display_type;
        }
    });

    jQuery(document).ready(function () {
        // Create Dropdowns
        for(id in DropdownsMap){
            dropdownGenerator( id, DropdownsMap[id] );
        }

        // Mobile View Move filters to modal
        if(getDisplayType() == "xs"){
            jQuery( "form.ofrs-srch-form > #dropdowns-container > div" ).detach().prependTo('.modal-body > #dropdowns-container '); // Filters
            // jQuery('#order_definitions').detach().appendTo('#dropdowns-container'); // Filters
        }

        // Initialize selected element Dropdown
        // console.log(init_form_data);

        // Set Values from request
        for( let index in init_form_data ){

            let id = "filter_" + index;

            if(id in DropdownsMap){
                for(let i_val in init_form_data[index]) {
                    jQuery('#'+id).multiselect('select', init_form_data[index][i_val] );
                }
                jQuery('#'+id).multiselect('refresh');
                change(null, null, null, id);
            }
        }

        // Init Drop downs
        let keys = ['ad_network_id', 'geo_targeting', 'payout_type' , 'verticals'];
        let filters_counter = 0 ;
        for( let key of keys ){
            if(init_form_data[key].length >0 ) filters_counter++
        }

        if( filters_counter > 0 ){
            jQuery('#btn-modal-filter > span').text( 'Filters (' + filters_counter+ ')' );
            jQuery('#btn-modal-filter').css('font-weight', 'bold');
        }
        // Init Modal Dropdown values
        if(init_form_data['sort_by'] ) {
            jQuery('#modal_sort_by').multiselect('select', init_form_data['sort_by']);
            jQuery('#modal_sort_by').multiselect('refresh');

            // sync values with button
            let text = jQuery("#modal_sort_by option:selected" ).text();
            jQuery('#btn-modal-sort > span').text( text );
            jQuery('#ddb_modal_sort_by > span').text( text );

            jQuery('#btn-modal-sort').css('font-weight', 'bold');
            boldManager('modal_sort_by');
        }

        // append with filter apply link
        jQuery('li.multiselect-reset > div').each(function( index ) {
            jQuery(this).append(
                jQuery('<a>').attr({
                    class: "filter-btns clear col-xs-3 visible-xs",
                }).click(function () {
                    let btn_id = jQuery( this ).closest('.multiselect-native-select > div').attr('id');
                    jQuery('#'+btn_id).toggleClass('open');
                }).text('Apply')
            );
        });

        // Attach add to filter -> apply action to verticals links
        jQuery('a.vertical-id-add-to-filter').each( function(index){
            jQuery(this).click(function(event) {
                event.preventDefault();
                jQuery('#filter_verticals').multiselect('select', jQuery(this).attr('filter') );
                jQuery('#filter_verticals').multiselect('refresh');;
                boldManager('filter_verticals');
                submitOffersForm('ofrs-srch-form');
            });
        } )
    });

</script>
<form class="ofrs-srch-form" action="<?php echo JRoute::_('index.php?option=com_ofrs'); ?>" method="get">
        <input type="hidden" id="sort_by" name="filter[sort_by]" value="<?= $search_filters_data["sort_by"] ?>">
        <input type="hidden" id="sort_direction" name="filter[sort_direction]" value="<?= $search_filters_data["sort_direction"] ?: ''  ?>">
        <input type="hidden" id="count_per_page" name="filter[count_per_page]" value="<?= $search_filters_data["count_per_page"] ?: 20 ?>">
        <input type="hidden" id="limitstart" name="limitstart" value="0">

        <div class="row row-no-gutters" >
            <div class="col-xs-12 col-no-gutters">
                <?= $form->getInput('search'); ?>
                <span aria-hidden="true" class="btn-search" type="submit" onclick="submitOffersForm('ofrs-srch-form')">
                    <i class="fa fa-search"></i>
                </span>
            </div>
        </div>
        <div id="dropdowns-container" class="row row-no-gutters hidden-xs">
            <div id="button_filter_ad_network_id" class="col-xs-12 col-sm-6 col-md-3 col-no-gutters dropdown-button" >
                <div class="col-xs-12 col-no-gutters">
                    <?= $form->getInput('ad_network_id'); ?>
                </div>
            </div>
            <!--  dropdown-button ddb-right -->
            <div id="button_filter_geo_targeting" class="col-xs-12 col-sm-6 col-md-3 col-no-gutters dropdown-button">
                <div class="col-xs-12 col-no-gutters">
                    <?= $form->getInput('geo_targeting'); ?>
                </div>
            </div>
<!--             dropdown-button ddb-left -->
            <div id="button_filter_verticals" class="col-xs-12 col-sm-6 col-md-3 col-no-gutters dropdown-button">
                <div class="col-xs-12 col-no-gutters">
                    <?= $form->getInput('verticals'); ?>
                </div>
            </div>
<!--             dropdown-button ddb-right-->
            <div id="button_filter_payout_type" class="col-xs-12 col-sm-6 col-md-3 col-no-gutters dropdown-button">
                <div class="col-xs-12 col-no-gutters">
                    <?= $form->getInput('payout_type'); ?>
                </div>
            </div>
        </div>

    <!-- Modal filters for mobile only -->
    <div id="modal-filters" class="modal fade" data-toggle="modal" aria-labelledby="myModalLabel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div id="modal-filters-content" class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Filters</h4>
                </div>
                <div class="modal-body">
                    <div id="dropdowns-container" class="row row-no-gutters dropdown-button">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn" onclick="clearAllFilters()">Reset</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="submitOffersForm('ofrs-srch-form')" >Apply</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal filters for mobile only -->
    <div id="modal-sort" class="modal fade" data-toggle="modal" aria-labelledby="myModalLabel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div id="modal-filters-content" class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Sort</h4>
                </div>
                <div class="modal-body">
                    <div id="dropdowns-container-sorting" class="row row-no-gutters dropdown-button">
                        <div class="col-xs-12 col-no-gutters" style="padding-right: 10px!important;">
                            <select class="" id="modal_sort_by" >
<!--                                <option value="" selected disabled hidden>No Sort</option>-->
                                <option value="45">Offer name</option>
                                <option value="43">Network</option>
                                <option value="22.asc" direction="asc" >Payout</option>
                                <option value="22.desc" direction="desc" >Payout</option>
                                <option value="23">Type</option>
                                <option value="49" selected>Updated</option>
                            </select>
                        </div>
<!--                        <div class="col-xs-6 col-no-gutters dropdown-button" style="padding-left: 10px!important;">-->
<!--                            <select class="" id="modal_count_per_page"  >-->
<!--                                <option value="5">5 per page</option>-->
<!--                                <option value="10">10 per page</option>-->
<!--                                <option value="20" selected >20 per page</option>-->
<!--                                <option value="50" >50 per page</option>-->
<!--                                <option value="100">100 per page</option>-->
<!--                                <option value="200">200 per page</option>-->
<!--                            </select>-->
<!--                        </div>-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn" onclick="clearAllFilters()">Reset</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="submitOffersForm('ofrs-srch-form')" >Apply</button>
                    <input id="saved_search_name1" type="hidden" name="saved_search_name1"/>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row" id="filters-buttons-xs">
    <div class="col-xs-8 col-xs-offset-4 text-right">
        <div class="visible-xs" >
            <button class="btn btn-white drop-down-btn" data-toggle="modal" data-target="#modal-filters" id="btn-modal-filter" href="#">
                <span>No Filter</span>
                <b class="fa fa-angle-down col-xs-1 col-no-gutters dropdown-arrows" style="max-width: 1rem;  font-size: 17px"></b>
            </button>
            <button class="btn btn-white drop-down-btn" data-toggle="modal" data-target="#modal-sort" id="btn-modal-sort" href="#">
                <span>No Sort</span>
                <b class="fa fa-angle-down col-xs-1 col-no-gutters dropdown-arrows" style="max-width: 1rem;  font-size: 17px"></b>
            </button>
        </div>
        <!--        .ofrs-srch-form a.clear-all-filters -->
        <div class="col-xs-6 col-sm-offset-6 col-md-offset-6 col-no-gutters hidden-xs" >
            <a id="apply_filters_lnk" class="filter-btns" onclick="submitOffersForm('ofrs-srch-form');">Apply Filters</a>
            
<!--            <input id="saved_search_name" type="text" name="search_name" value="" class="col-xs-12" placeholder="Name" style="display: none;">-->
<!--        	<a id="save_search_lnk" class="filter-btns" onclick="activateSaveSearch(); return true;">Save Search</a>-->
<!--        	<a id="save_and_apply_lnk" class="filter-btns" style="display: none;" onclick="saveSearch(); return true;">Save & Apply</a>-->
<!---->
<!--        	--><?php
//            	$model = new UuModelSubscriptions();
//            	echo($model->getSavedSearchesSelect());
//        	?>
<!--        	<a id="my_searches_lnk" class="filter-btns" onclick="mySearches(); return true;">My Searches</a>-->
<!--        	<a id="redo_search_lnk" class="filter-btns" style="display: none;" onclick="alert('Да се направи'); return true;">Go</a>-->
<!--			<a id="cancel_lnk" class="filter-btns" style="display: none;" onclick="doCancel(); return true;">Cancel</a>-->
        </div>
    </div>
    <div class="col-xs-12 col-no-gutters">
        <?php
            $modules  = JModuleHelper::getModules('breadcrumb');

            $attribs  = array();
            $attribs['style'] = 'xhtml';

            foreach ($modules as $mod)
            {
                echo JModuleHelper::renderModule($mod, $attribs);
            }
        ?>
    </div>




</div>