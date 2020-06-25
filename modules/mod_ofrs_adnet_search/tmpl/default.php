<?php
$search_filters_data = OfrsHelper::parseOfrsParams('adnets.filter');
$form = getSearchForm();
$form->setValue('search', '', $search_filters_data['search']);
?>
<form class="adnet_search_form" action="<?php echo JRoute::_('index.php?option=com_ofrs&view=adnets'); ?>" method="post">
    <div class="row row-no-gutters">
        <div class="col-xs-12 col-no-gutters adnet-srch-form">
            <input type="hidden" id="sort_by" name="filter[sort_by]" value="<?= $search_filters_data["sort_by"] ?>">
            <input type="hidden" id="sort_direction" name="filter[sort_direction]" value="<?= $search_filters_data["sort_direction"] ?>">
            <input type="hidden" id="count_per_page" name="filter[count_per_page]" value="<?= $search_filters_data["count_per_page"] ?? 5 ?>">
            <?= $form->getInput('search'); ?>
            <span aria-hidden="true" class="btn-search" type="submit" onclick="submitOffersForm('adnet_search_form')">
                <i class="fa fa-search"></i>
            </span>
        </div>
    </div>
</form>
<!--<hr>-->
