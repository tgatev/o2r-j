<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemUuredirect extends JPlugin {

    function onAfterRoute() {
        $app = JFactory::getApplication();

        // If in admin panel
        if ($app->isAdmin()) {
            return;
        }
        // If site is offline allow admins to login
        if ((int)$app->getCfg('offline')) {
            return;
        }

        //don't use this plugin if ultimate user registration is not enabled
        require_once JPATH_SITE .'/components/com_uu/helpers/config.php';
        $conf = new UuConfig();
        if (!$conf->get('enable_user_registration')) {
            return;
        }

        $uri = JFactory::getURI();
        $query = $uri->getQuery(true);

        $jinput = JFactory::getApplication()->input;

        $option = $jinput->getString('option',null);
        if (isset($option) && ($option == 'com_users' || $option=='com_user')){
            //this 2 methods need to be set why ?
            $jinput->set('option','com_uu');
            JRequest::setVar('option', 'com_uu');
        }

    }

}

