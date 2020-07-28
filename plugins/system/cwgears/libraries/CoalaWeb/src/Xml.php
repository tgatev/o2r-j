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

use SimpleXMLElement;

jimport('joomla.filesystem.file');

/**
 * Class Xml
 * @package CoalaWeb
 */
class Xml
{
	/**
	 * Get an object filled with data from an xml file
	 *
	 * @param string $url
	 * @param string $root
	 *
	 * @return object
	 */
	public static function toObject($url, $root = '')
	{
		$cache_id = 'xmlToObject_' . $url . '_' . $root;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		if (file_exists($url))
		{
			$xml = @new SimpleXMLElement($url, LIBXML_NONET | LIBXML_NOCDATA, 1);
		}
		else
		{
			$xml = simplexml_load_string($url, "SimpleXMLElement", LIBXML_NONET | LIBXML_NOCDATA);
		}

		if ( ! @count($xml))
		{
			return Cache::set(
				$cache_id,
				(object) []
			);
		}

		if ($root)
		{
			if ( ! isset($xml->{$root}))
			{
				return Cache::set(
					$cache_id,
					(object) []
				);
			}

			$xml = $xml->{$root};
		}

		$json = json_encode($xml);
		$xml  = json_decode($json);
		if (is_null($xml))
		{
			$xml = (object) [];
		}

		if ($root && isset($xml->{$root}))
		{
			$xml = $xml->{$root};
		}

		return Cache::set(
			$cache_id,
			$xml
		);
	}

    public function getXMLFile($folder, $ext_type, $ext_name)
    {
        switch ($ext_type)
        {
            case 'module' :
                return $folder . '/mod_' . $ext_name . '.xml';

            default :
                return $folder . '/' . $ext_name . '.xml';
        }
    }
}
