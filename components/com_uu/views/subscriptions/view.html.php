<?php

defined('_JEXEC') or die;

class UuViewSubscriptions extends JViewLegacy
{
    // Overwriting JView display method
    function display($tpl = null) {
        // get combined params of both component and menu
        $this->app = JFactory::getApplication();
        // Initialise variables.
        /* var $item UuModelSubscriptions */
        $model = $this->getModel();
        $this->verticalsMonitored = $model->getVerticalsMonitored();
        $this->adnetsMonitored = $model->getAdnetsMonitored();
        $this->savedSearchesMonitored = $model->getSavedSearchesMonitored();
        
        $this->verticalsSelect = $model->getVerticalsSelect();
        $this->adnetsSelect = $model->getAdnetsSelect();
        $this->savedSearchesSelect = $model->getSavedSearchesSelect();
        $this->_prepareDocument();
        
        // Check for errors.
        //         if (count($errors = $this->get('Errors')))
        //         {
        //             throw new Exception(implode(PHP_EOL, $errors), 500);
        //         }
        
        parent::display($tpl);
    }
    
    
    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        JHtml::_('jquery.framework');
        $this->document->addScript("components/com_uu/assets/js/ofrs_subscriptions.js");
        $this->document->addScript("components/com_uu/assets/js/menu.js");
    }
}