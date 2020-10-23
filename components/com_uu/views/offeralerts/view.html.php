<?php

defined('_JEXEC') or die;

class UuViewOfferAlerts extends JViewLegacy
{
    // Overwriting JView display method
    function display($tpl = null) {
        // get combined params of both component and menu
        $this->app = JFactory::getApplication();
        $this->items = $this->get('Items');
        // set the document
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
    protected function _prepareDocument() {
        JHtml::_('jquery.framework');
        $this->document->addScript("components/com_uu/assets/js/ofrs_user_profile.js");
        $this->document->addScript("components/com_uu/assets/js/menu.js");

    }
}