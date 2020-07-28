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

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller for coalawebsociallinks component
 */
class CoalawebsociallinksController extends JControllerLegacy {

    protected $default_view = 'Controlpanel';

    /**
     * Method to display a view.
     *
     * @param bool $cachable
     * @param bool $urlparams
     * @return CoalawebsociallinksController
     * @since    1.5
     */
    public function display($cachable = false, $urlparams = false)
    {
        require_once JPATH_COMPONENT . '/helpers/coalawebsociallinks.php';

        $view = JFactory::getApplication()->input->get('view', 'Controlpanel');
        $layout = JFactory::getApplication()->input->get('layout', 'default');

        parent::display();
        return $this;
    }

}
