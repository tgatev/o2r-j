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

use CoalaWeb\UpdateKey;
use CoalaWeb\Parameters;
use CoalaWeb\Config;
use Joomla\CMS\MVC\Controller\AdminController as JControllerAdmin;


/**
 * Class CoalawebsociallinksControllerControlpanel
 */
class CoalawebsociallinksControllerControlpanel extends JControllerAdmin
{

    /**
     * @var        string    The prefix to use with controller messages.
     * @since    1.6
     */
    protected $text_prefix = 'COM_CWSOCIALLINKS';

    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @see     JControllerLegacy
     * @since   1.6
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    /**
     * Proxy for getModel
     *
     * @param string $name
     * @param string $prefix
     *
     * @param array $config
     * @return JModel
     */
    public function getModel($name = 'Controlpanel', $prefix = 'CoalawebsociallinksModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}
