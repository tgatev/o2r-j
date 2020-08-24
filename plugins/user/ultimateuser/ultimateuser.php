<?php
    /**
     * @package     UltimateUser for Joomla!
     * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
     * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
     * @copyright   Copyright (C) 2012-2013. All rights reserved.
     */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');


class plgUserUltimateuser extends JPlugin
{

    function onUserBeforeDelete($user)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->delete();
        $query->from($db->quoteName('#__uu_users')) ;
        $query->where($db->quoteName('user_id').' = '.$db->quote($user['id']));

        $db->setQuery($query);
        $db->query();
        if($db->getErrorNum()){
            JError::raiseError( 500, $db->stderr());
        }

    }

}
