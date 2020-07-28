<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Library
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Library is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

namespace CoalaWeb;

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Class UpdateKey
 * @package CoalaWeb
 */
class UpdateKey
{
    /**
     * @var \JDatabaseDriver
     * @since 2.9.2
     */
    private $db;

    public function __construct()
    {
        $this->db = \JFactory::getDbo();
    }

    /**
     * Showing an overview of installed products/extensions and their update state.
     *
     * @param null $element
     *
     * @return bool
     *
     * @since 2.9.2
     */
    public function preCheckUpdateKey($element = null, $name = null)
    {
        if (!$element) {
            return false;
        }

        $component = $this->getExtensionData($element);
        $extension = empty($name) ? $component->name : $name;
        $manifest = json_decode($component->manifest_cache);

        $layout = new \JLayoutFile('download_key_check');

        echo $layout->render([
            'extension' => $extension,
            'type' => $component->type,
            'version' => $manifest->version,
            'state' => !empty($component->enabled) ? $component->enabled : null,
            'downloadid' => !empty($component->extra_query) ? $component->extra_query : null,
            'available' => !empty($component->new_version) ? $component->new_version : null,
        ]);
    }

    /**
     * Getting the update server from the XML manifest file.
     *
     * @param null $path
     *
     * @return null|\SimpleXMLElement
     *
     * @since 2.9.2
     */
    public function getUpdaterURLFromXMLManifest($path = null)
    {
        $object = simplexml_load_file($path);
        $result = json_decode(json_encode($object));

        return !empty($result->updateservers->server) ? $result->updateservers->server : null;
    }

    /**
     * @param null $element
     * @param null $key
     * @param null $url
     * @param string $type
     *
     * @return bool
     *
     * @since 2.9.2
     */
    public function updateOrAddDownloadId($element = null, $key = null, $url = null, $type = 'extension')
    {
        // Getting the extension data.
        $component = $this->getExtensionData($element);

        $extension = new \stdClass;
        $extension->update_site_id = $component->update_site_id;
        $extension->name = $component->name;
        $extension->type = $type;
        $extension->location = $url;
        $extension->enabled = 1;
        $extension->last_check_timestamp = 0;
        $extension->extra_query = 'key=' . $key;

        if ($component->update_site_id) {
            return $this->db->updateObject('#__update_sites', $extension, 'update_site_id');
        }

        if (!$this->db->insertObject('#__update_sites', $extension)) {
            return false;
        }

        $update = new \stdClass;
        $update->update_site_id = $this->db->insertid();
        $update->extension_id = $component->extension_id;

        // Insert the object to the database.
        return $this->db->insertObject('#__update_sites_extensions', $update);
    }

    /**
     * Getting extension information from the update tables based on the given element.
     *
     * @param null $element
     *
     * @return mixed
     *
     * @since 2.9.2
     */
    public function getExtensionData($element = null)
    {
        $query = $this->db->getQuery(true)
            ->select([
                'e.extension_id', 'e.type', 'e.name', 'e.manifest_cache', 'us.update_site_id', 'us.enabled', 'us.extra_query',
                'u.version as new_version',
            ])
            ->from($this->db->quoteName('#__extensions', 'e'))
            ->join('LEFT OUTER', $this->db->quoteName('#__update_sites_extensions', 'use') . ' ON (' . $this->db->quoteName('e.extension_id') . ' = ' . $this->db->quoteName('use.extension_id') . ')')
            ->join('LEFT OUTER', $this->db->quoteName('#__update_sites', 'us') . ' ON (' . $this->db->quoteName('us.update_site_id') . ' = ' . $this->db->quoteName('use.update_site_id') . ')')
            ->join('LEFT OUTER', $this->db->quoteName('#__updates', 'u') . ' ON (' . $this->db->quoteName('u.update_site_id') . ' = ' . $this->db->quoteName('use.update_site_id') . ')')
            ->where($this->db->quoteName('e.element') . ' = ' . $this->db->quote($element));

        $this->db->setQuery($query);

        return $this->db->loadObject();
    }
}