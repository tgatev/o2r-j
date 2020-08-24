<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */
    defined('_JEXEC') or die;

class UuConfig
{
    var $conf = null;

    function get($key){
        if (!$this->conf) {
            $this->loadConf();
        }
        return $this->conf[$key]['value'];
    }

    private function loadConf() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        if (!$this->conf) {
            $query->select('*');
            $query->from($db->quoteName('#__uu_configuration'));
            $db->setQuery($query);

            $this->conf = $db->loadAssocList('key');
            return (boolean)$this->conf;
        }
        return true;
    }

}