<?php

/**
 * @package         Convert Forms
 * @version         2.7.4 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2020 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace ConvertForms;

use Joomla\Registry\Registry;

defined('_JEXEC') or die('Restricted access');

/**
 *  Convert Forms Migrator
 */
class Migrator
{
    /**
     * The database class
     *
     * @var object
     */
    private $db;

    /**
     * Indicates the current installed version of the extension
     *
     * @var string
     */
    private $installedVersion;

    /**
     * Class constructor
     *
     * @param string $installedVersion  The current extension version
     */
    public function __construct($installedVersion)
    {
        $this->installedVersion = $installedVersion;
        $this->db = \JFactory::getDbo();
    }

	/**
	 *  Start migration process
	 *
	 *  @return  void
	 */
	public function start()
	{
        if (!$items = $this->getItems())
        {
            return;
        }
        
		foreach ($items as $item)
        {   
            $item->params = new Registry($item->params);

            if (version_compare($this->installedVersion, '2.7.3', '<=')) 
            {
				$this->fixUploadFolder($item);
            }
           
            // Update box using id as the primary key.
            $item->params = json_encode($item->params);
            $this->db->updateObject('#__convertforms', $item, 'id');
        }
	}

	private function fixUploadFolder(&$item)
	{
        if (!$fields = $item->params->get('fields'))
        {
            return;
        }

        foreach ($fields as &$field)
        {
            if ($field->type != 'fileupload')
            {
                continue;
            }

            if (isset($field->upload_folder_type))
            {
                continue;
            }

            $field->upload_folder_type = 'custom';
        }
	}

	/**
	 *  Get Forms List
	 *
	 *  @return  object
	 */
	public function getItems()
	{
		$db = $this->db;
		$query = $db->getQuery(true);

		$query
			->select('*')
			->from('#__convertforms');

		$db->setQuery($query);

		return $db->loadObjectList();
	}
}