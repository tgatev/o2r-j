<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Social Links
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Social Links is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\MVC\View\HtmlView as JView;
use Joomla\CMS\Object\CMSObject as JObject;
use Joomla\CMS\Toolbar\Toolbar as JToolbar;
use CoalaWeb\Parameters as CW_Parameters;
use CoalaWeb\UpdateKey as CW_UpdateKey;
use CoalaWeb\Xml as CW_Xml;

jimport('joomla.application.component.view');

/**
 * View class for control panel
 */
class CoalawebsociallinksViewControlpanel extends JView {

    protected $config;
    protected $version;
    protected $component;
    protected $proCore;
    protected $license;

    /**
     * Display the view
     *
     * @param string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @return void
     * @throws Exception
     */
    function display($tpl = null) {

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }
        
        CoalawebsociallinksHelper::addSubmenu('controlpanel');

        // Load component params
        $this->config    = CW_Parameters::getInstance()->getComponentParams('coalawebsociallinks');

        // Load extension data such as the manifest cache
        $this->component = (new CW_UpdateKey)->getExtensionData('com_coalawebsociallinks');

        // Is this the PRO or CORE version?
        $xml = JPATH_ADMINISTRATOR . '/components/com_coalawebsociallinks/coalawebsociallinks.xml';
        $this->extXml = (new CW_Xml)->toObject($xml);
        $this->proCore = $this->extXml->level;
        $this->license = $this->extXml->license;

         // We don't need toolbar in the modal window.
        if ($this->getLayout() !== 'modal') {
            $this->addToolbar();
        }

        parent::display($tpl);
    }
    
    /**
     * Add the page title and toolbar.
     *
     * @return void
     */
    protected function addToolbar() 
    {
        $canDo = JHelperContent::getActions('com_coalawebsociallinks');

        $title = JText::_('COM_CWSOCIALLINKS_TITLE_' . $this->proCore);
        JToolBarHelper::title($title . ' [ ' . JText::_('COM_CWSOCIALLINKS_TITLE_CPANEL') . ' ]', 'cogs');

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_coalawebsociallinks');
        }

        $help_url = 'https://coalaweb.com/support/documentation/item/coalaweb-social-links-guide';
        JToolBarHelper::help('COM_CWSOCIALLINKS_TITLE_HELP', false, $help_url);
    }

}
