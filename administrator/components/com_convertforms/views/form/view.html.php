<?php

/**
 * @package         Convert Forms
 * @version         2.6.0 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2020 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');


include_once JPATH_PLUGINS . "/system/nrframework/helpers/fonts.php";
 
/**
 * Item View
 */
class ConvertFormsViewForm extends JViewLegacy
{
    /**
     * display method of Item view
     * @return void
     */
    public function display($tpl = null) 
    {
		// Access check.
        ConvertForms\Helper::authorise('convertforms.forms.manage', true);

        // Check for errors.
        if (!is_null($this->get('Errors')) && count($errors = $this->get('Errors')))
        {
            JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
            return false;
        }

        // Assign the Data
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->isnew = (!isset($_REQUEST["id"])) ? true : false;
        $this->tabs  = $this->get('Tabs');
        $this->name  = $this->item->name ?: JText::_('COM_CONVERTFORMS_UNTITLED_BOX');

        \JPluginHelper::importPlugin('convertformstools');
		\JFactory::getApplication()->triggerEvent('onConvertFormsBackendEditorDisplay');

        // Display the template
        parent::display($tpl);
    }
}