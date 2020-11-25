<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access.
defined('_JEXEC') or die;

class UuTableConfiguration extends JTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	function __construct(&$_db)
	{
		parent::__construct('#__uu_configuration', 'id', $_db);
		//$this->created = JFactory::getDate()->toMySQL();
	}

	/**
	 * Overloaded bind function
	 *
	 * @param	array		$hash named array
	 * @return	null|string	null is operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = array())
	{
		return parent::bind($array, $ignore);
	}
	/**
	 * method to store a row
	 *
	 * @param boolean $updateNulls True to update fields even if they are null.
	 */
	function store($updateNulls = false)
	{
		if (empty($this->id))
		{
			// Store the row
			parent::store($updateNulls);
		}
		else
		{
                        //		// Get the old row
			$oldrow = JTable::getInstance('Configuration', 'UuTable');
			if (!$oldrow->load($this->id) && $oldrow->getError())
			{
				$this->setError($oldrow->getError());
			}

			// Store the new row
			parent::store($updateNulls);

		}
		return count($this->getErrors())==0;
	}

}

