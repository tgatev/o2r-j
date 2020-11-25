<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2020 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework;

use NRFramework\Cache;

defined('_JEXEC') or die('Restricted access');

class User
{
    /**
     * Return the user object
     *
     * @param  mixed $id  The primary key of the user
     *
     * @return mixed object on success, null on failure
     */
    public static function get($id = null)
    {
        // Return current active user
        if (is_null($id))
        {
            return \JFactory::getUser();
        }

        // Prevent Joomla from displaying a warning from missing user by checking if the user exists first
        if (!self::exists($id))
        {
            return;
        }
        
        return \JFactory::getUser($id);
    }

    /**
     * Checks whether the user does exist in the database
     *
     * @param  integer $id  The primary key of the user
     *
     * @return bool
     */
    public static function exists($id)
    {
        $hash = 'user' . $id;

        if (Cache::has($hash))
        {
            return Cache::get($hash);
        }

        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('count(id)')
            ->from('#__users')
            ->where('id = ' . $id);
        $db->setQuery($query);

        // Cache result
        return Cache::set($hash, $db->loadResult());
    }
}