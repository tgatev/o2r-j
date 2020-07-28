<?php
$search_filters_data = OfrsHelper::parseOfrsParams('adnets.filter');
$form = getSearchForm();
$form->setValue('adnet_search', '', $search_filters_data['adnet_search']);
?>
<form class="adnet_search_form" action="<?php echo JRoute::_('index.php?option=com_ofrs&view=adnets'); ?>" method="get">
    <div class="row row-no-gutters">
        <div class="col-xs-12 col-no-gutters adnet-srch-form">
            <input type="hidden" id="sort_by" name="adnet_filter[sort_by]" value="<?= $search_filters_data["sort_by"] ?>">
            <input type="hidden" id="sort_direction" name="adnet_filter[sort_direction]" value="<?= $search_filters_data["sort_direction"] ?>">
            <input type="hidden" id="count_per_page" name="adnet_filter[count_per_page]" value="<?= $search_filters_data["count_per_page"] ?? 5 ?>">
            <input type="hidden" id="form_view" name="view" value="adnets">
            <input type="hidden" id="form_option" name="option" value="com_ofrs">
            <input type="hidden" id="form_item_id" name="Itemid" value="1116">
            <?= $form->getInput('adnet_search'); ?>
            <span aria-hidden="true" class="btn-search" type="submit" onclick="submitOffersForm('adnet_search_form')">
                <i class="fa fa-search"></i>
            </span>
        </div>
    </div>
</form>
<!--<hr>-->
