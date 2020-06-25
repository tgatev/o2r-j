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

use Joomla\CMS\Factory as JFactory;

/**
 * Class Cache
 * @package CoalaWeb
 */
class Cache
{
	static $group = 'coalaweb';
	static $cache = [];

	// Is the cached object in the cache memory?
	public static function has($id)
	{
		return isset(self::$cache[md5($id)]);
	}

	// Get the cached object from the cache memory
	public static function get($id)
	{
		$hash = md5($id);

		if ( ! isset(self::$cache[$hash]))
		{
			return false;
		}

		return is_object(self::$cache[$hash]) ? clone self::$cache[$hash] : self::$cache[$hash];
	}

	// Save the cached object to the cache memory
	public static function set($id, $data)
	{
		self::$cache[md5($id)] = $data;

		return $data;
	}

	// Get the cached object from the Joomla cache
	public static function read($id)
	{
		if (JFactory::getApplication()->get('debug'))
		{
			return false;
		}

		$hash = md5($id);

		if (isset(self::$cache[$hash]))
		{
			return self::$cache[$hash];
		}

		$cache = JFactory::getCache(self::$group, 'output');

		return $cache->get($hash);
	}

	// Save the cached object to the Joomla cache
	public static function write($id, $data, $time_to_life_in_minutes = 0, $force_caching = true)
	{
		if (JFactory::getApplication()->get('debug'))
		{
			return false;
		}

		$hash = md5($id);

		self::$cache[$hash] = $data;

		$cache = JFactory::getCache(self::$group, 'output');

		if ($time_to_life_in_minutes)
		{
			// convert ttl to minutes
			$cache->setLifeTime($time_to_life_in_minutes * 60);
		}

		if ($force_caching)
		{
			$cache->setCaching(true);
		}

		$cache->store($data, $hash);

		self::set($hash, $data);

		return $data;
	}
}
