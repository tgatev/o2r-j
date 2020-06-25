<?php
    use Joomla\CMS\Form\Form;

    function getSearchForm(){
        $form = Form::getInstance("ofrs_offer_search.search", __DIR__ . "/ofrs_offer_search_form.xml", array("control" => "filter"));
        Form::addFieldPath(__DIR__.'/fields');

        return $form;
    }

?>