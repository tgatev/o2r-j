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

defined('_JEXEC') or die;

require_once __DIR__ . '/script.install.helper.php';

class Com_ConvertFormsInstallerScript extends Com_ConvertFormsInstallerScriptHelper
{
	public $name = 'CONVERTFORMS';
	public $alias = 'convertforms';
	public $extension_type = 'component';

	public function onAfterInstall()
	{
		$this->moveFrontEndImages();

		if ($this->install_type == 'update') 
		{
			require_once __DIR__ . '/autoload.php';

			try {
				(new ConvertForms\Migrator($this->installedVersion))->start();
			} catch (\Throwable $th)
			{
			}

			$this->dropIndex('convertforms_conversions', 'email_campaign_id');

			// Remove convertforms field from the framework
			$this->deleteFiles([JPATH_SITE . '/plugins/system/nrframework/fields/convertforms.php']);
        }
    }

	/**
	 *  Moves front-end based images from /media/ folder to /images/
	 *
	 *  @return  void
	 */
	private function moveFrontEndImages()
	{
		$source      = JPATH_SITE . '/media/com_convertforms/img/convertforms';
		$destination = JPATH_SITE . '/images/convertforms';

		if (!JFolder::exists($source))
		{
			return;
		}

		if (!JFolder::copy($source, $destination, null, true))
		{
			return;
		}

		JFolder::delete($source);
	}
}