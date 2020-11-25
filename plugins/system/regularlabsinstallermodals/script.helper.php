<?php
/**
 * @package         Regular Labs Installer
 * @version         20.10.18795
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\Installer as JInstaller;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class RegularLabsInstaller
{
	private $min_joomla_version      = [3 => '3.9.0', 4 => '3.9999'];
	private $min_php_version         = [3 => '5.6', 4 => '7.2'];
	private $name                    = '';
	private $extname                 = '';
	private $previous_version        = '';
	private $previous_version_simple = '';

	public function preflight($route, $installer)
	{
		// To prevent installer from running twice if installing multiple extensions
		if ( ! file_exists($this->dir . '/' . $this->installerName . '.xml'))
		{
			return true;
		}

		JFactory::getLanguage()->load('plg_system_regularlabsinstaller', $this->dir);

		$this->name = $this->getNameFromChangelog();

		if ( ! $this->passMinimumJoomlaVersion())
		{
			$this->uninstallInstaller();

			return false;
		}

		if ( ! $this->passMinimumPHPVersion())
		{
			$this->uninstallInstaller();

			return false;
		}

		// To prevent XML not found error
		$this->createExtensionRoot();

		return true;
	}

	public function postflight($route, $installer)
	{
		if ( ! in_array($route, ['install', 'update']))
		{
			return true;
		}

		// To prevent installer from running twice if installing multiple extensions
		if ( ! file_exists($this->dir . '/' . $this->installerName . '.xml'))
		{
			return true;
		}

		// First install the Regular Labs Library
		if ( ! $this->installLibrary())
		{
			// Uninstall this installer
			$this->uninstallInstaller();

			return false;
		}

		// Then install the rest of the packages
		if ( ! $this->installPackages())
		{
			// Uninstall this installer
			$this->uninstallInstaller();

			return false;
		}

		$changelog = $this->getChangelog();

		JFactory::getApplication()->enqueueMessage($changelog, 'notice');

		// Uninstall this installer
		$this->uninstallInstaller();

		return true;
	}

	public function getMainFolder()
	{
		switch ($this->extension_type)
		{
			case 'plugin' :
				return JPATH_PLUGINS . '/' . $this->plugin_folder . '/' . $this->extname;

			case 'component' :
				return JPATH_ADMINISTRATOR . '/components/com_' . $this->extname;

			case 'module' :
				return JPATH_ADMINISTRATOR . '/modules/mod_' . $this->extname;

			case 'library' :
				return JPATH_SITE . '/libraries/' . $this->extname;
		}
	}

	private function getNameFromChangelog()
	{
		$changelog = file_get_contents($this->dir . '/CHANGELOG.txt');

		if ( ! preg_match('# \* @package\s*(.*)#', $changelog, $match))
		{
			return JText::_('RLI_THIS_EXTENSION');
		}

		return $match[1];
	}

	private function getChangelog()
	{
		$changelog = file_get_contents($this->dir . '/CHANGELOG.txt');

		$changelog = "\n" . trim(preg_replace('#^.* \*/#s', '', $changelog));
		$changelog = preg_replace("#\r#s", '', $changelog);

		$parts = explode("\n\n", $changelog);

		if (empty($parts))
		{
			return '';
		}

		$changelog = [];

		// Add first entry to the changelog
		$changelog[] = array_shift($parts);

		$this_version = '';

		if (preg_match('#^[0-9]+-[a-z]+-[0-9]+ : v([0-9\.]+(?:-dev[0-9]+)?)\n#i', trim($changelog[0]), $match))
		{
			$this_version = $match[1];
		}

		// Add extra older entries if this is an upgrade based on previous installed version
		if ($this->previous_version_simple)
		{
			foreach ($parts as $part)
			{
				$part = trim($part);

				if ( ! preg_match('#^[0-9]+-[a-z]+-[0-9]+ : v([0-9\.]+(?:-dev[0-9]+)?)\n#i', $part, $match))
				{
					continue;
				}

				$changelog_version = $match[1];

				if (version_compare($changelog_version, $this->previous_version_simple, '<='))
				{
					break;
				}

				$changelog[] = $part;
			}
		}

		$badge_classes = [
			'default' => $this->jversion == 3 ? 'label label-sm label-default' : 'badge badge-secondary',
			'success' => $this->jversion == 3 ? 'label label-sm label-success' : 'badge text-white bg-success',
			'info'    => $this->jversion == 3 ? 'label label-sm label-info' : 'badge text-white bg-info',
			'warning' => $this->jversion == 3 ? 'label label-sm label-warning' : 'badge text-white bg-warning',
			'danger'  => $this->jversion == 3 ? 'label label-sm label-important' : 'badge text-white bg-danger',
		];

		$changelog = implode("\n\n", $changelog);

		//  + Added   ! Removed   ^ Changed   # Fixed
		$change_types = [
			'+' => ['title' => 'Added', 'class' => $badge_classes['success']],
			'^' => ['title' => 'Changed', 'class' => $badge_classes['info']],
			'#' => ['title' => 'Fixed', 'class' => $badge_classes['warning']],
			'!' => ['title' => 'Removed', 'class' => $badge_classes['danger']],
		];
		foreach ($change_types as $char => $type)
		{
			$changelog = preg_replace(
				'#\n ' . preg_quote($char, '#') . ' #',
				"\n" . '<span class="' . $type['class'] . '" title="' . $type['title'] . '">' . $char . '</span> ',
				$changelog
			);
		}

		// Extract note
		$note = '';
		if (preg_match('#\n > (.*?)\n#s', $changelog, $match))
		{
			$note      = $match[1];
			$changelog = str_replace($match[0], "\n", $changelog);
		}

		$changelog = preg_replace('#see: (https://www\.regularlabs\.com[^ \)]*)#s', '<a href="\1" target="_blank">see documentation</a>', $changelog);

		$changelog = preg_replace(
				"#(\n+)([0-9]+.*?) : v([0-9\.]+(?:-dev[0-9]*)?)([^\n]*?\n+)#",
				'\1'
				. '<h5>v\3 <small>\2</small></h5>'
				. '\4<pre>',
				$changelog
			) . '</pre>';

		$changelog = str_replace(
			[
				'<pre>',
				'[FREE]',
				'[PRO]',
			],
			[
				'<pre class="border bg-light p-2" style="line-height: 1.6em;max-height: 120px;overflow: auto;">',
				'<span class="' . $badge_classes['success'] . '">FREE</span>',
				'<span class="' . $badge_classes['info'] . '">PRO</span>',
			],
			$changelog
		);

		$changelog = preg_replace(
			'#\[J([1-9][\.0-9]*)\]#',
			'<span class="' . $badge_classes['default'] . '">J\1</span>',
			$changelog
		);

		$title1 = JText::sprintf('RLI_EXTENSION_INSTALLED', JText::_($this->name), $this_version);
		$title2 = JText::_('RLI_LATEST_CHANGES');

		if ($this->previous_version_simple && version_compare($this->previous_version_simple, $this_version, '<'))
		{
			$title1 = JText::sprintf('RLI_EXTENSION_UPDATED', JText::_($this->name), $this_version);
			$title2 = JText::sprintf('RLI_LATEST_CHANGES_SINCE', $this->previous_version_simple);
		}

		if ($this->previous_version_simple
			&& $this->getMajorVersionPart($this->previous_version_simple) < $this->getMajorVersionPart($this_version)
			&& ! $this->hasMessagesOfType('warning')
		)
		{
			JFactory::getApplication()->enqueueMessage(JText::sprintf('RLI_MAJOR_UPGRADE', JText::_($this->name)), 'warning');
		}

		if (strpos($this_version, 'dev') !== false)
		{
			$note = '';
		}

		return '<h3>' . $title1 . '</h3>'
			. '<h4>' . $title2 . '</h4>'
			. ($note ? '<div class="alert alert-warning">' . $note . '</div>' : '')
			. $changelog;
	}

	private function hasMessagesOfType($type)
	{
		$queue = JFactory::getApplication()->getMessageQueue();

		foreach ($queue as $message)
		{
			if ($message['type'] == $type)
			{
				return true;
			}
		}

		return false;
	}

	private function getMajorVersionPart($string)
	{
		return preg_replace('#^([0-9]+)\..*$#', '\1', $string);
	}

	private function createExtensionRoot()
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		$destination = JPATH_PLUGINS . '/system/' . $this->installerName;

		JFolder::create($destination);

		JFile::copy(
			$this->dir . '/' . $this->installerName . '.xml',
			$destination . '/' . $this->installerName . '.xml'
		);
	}

	// Check if Joomla version passes minimum requirement
	private function passMinimumJoomlaVersion()
	{
		if ( ! file_exists($this->packages_dir))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::sprintf(
					'RLI_NOT_COMPATIBLE_JOOMLA',
					$this->name,
					$this->jversion
				),
				'error'
			);

			return false;
		}

		if (version_compare(JVERSION, $this->min_joomla_version[$this->jversion], '<'))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::sprintf(
					'RLI_NOT_COMPATIBLE_UPDATE',
					$this->name,
					JVERSION,
					$this->min_joomla_version[$this->jversion]
				),
				'error'
			);

			return false;
		}

		return true;
	}

	// Check if PHP version passes minimum requirement
	private function passMinimumPHPVersion()
	{
		if (version_compare(PHP_VERSION, $this->min_php_version[$this->jversion], 'l'))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::sprintf(
					'RLI_NOT_COMPATIBLE_PHP',
					$this->name,
					PHP_VERSION,
					$this->min_php_version
				),
				'error'
			);

			return false;
		}

		return true;
	}

	private function installPackages()
	{
		$packages = JFolder::folders($this->packages_dir);

		$packages = array_diff($packages, ['library_regularlabs', 'plg_system_regularlabs', 'plg_system_nnframework']);

		foreach ($packages as $package)
		{
			if ( ! $this->installPackage($package))
			{
				return false;
			}
		}

		return true;
	}

	private function installPackage($package)
	{
		$tmpInstaller = new RLInstaller;

		$installed = $tmpInstaller->install($this->packages_dir . '/' . $package);

		if ($tmpInstaller->manifestClass->extname != 'regularlabs')
		{
			$this->name    = $tmpInstaller->manifestClass->name;
			$this->extname = $tmpInstaller->manifestClass->extname;
		}

		if ($tmpInstaller->manifestClass->extname != 'regularlabs' && $tmpInstaller->manifestClass->installed_version)
		{
			$this->previous_version        = $tmpInstaller->manifestClass->installed_version;
			$this->previous_version_simple = str_replace('PRO', '', $this->previous_version);
		}

		if ( ! empty($tmpInstaller->manifestClass->softbreak))
		{
			return true;
		}

		return $installed;
	}

	private function installLibrary()
	{
		if (
			! $this->installPackage('library_regularlabs')
			|| ! $this->installPackage('plg_system_regularlabs')
		)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('RLI_ERROR_INSTALLATION_LIBRARY_FAILED'), 'error');

			return false;
		}

		if (is_file(JPATH_PLUGINS . '/system/nnframework/nnframework.xml'))
		{
			$this->installPackage('plg_system_nnframework');
		}

		JFactory::getCache()->clean('_system');

		return true;
	}

	private function uninstallInstaller()
	{
		if ( ! JFolder::exists(JPATH_PLUGINS . '/system/' . $this->installerName))
		{
			return;
		}

		$this->delete([
			JPATH_PLUGINS . '/system/' . $this->installerName . '/language',
			JPATH_PLUGINS . '/system/' . $this->installerName,
		]);

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->delete('#__extensions')
			->where($db->quoteName('element') . ' = ' . $db->quote($this->installerName))
			->where($db->quoteName('folder') . ' = ' . $db->quote('system'))
			->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
		$db->setQuery($query);
		$db->execute();

		JFactory::getCache()->clean('_system');
	}

	public function delete($files = [])
	{
		foreach ($files as $file)
		{
			if (is_dir($file))
			{
				JFolder::delete($file);
			}

			if (is_file($file))
			{
				JFile::delete($file);
			}
		}
	}
}

/*
 * Override core Library Installer to prevent it from uninstalling the library before upgrade
 * We need the files to check for the version to decide whether to install or not.
 */

class RLInstaller extends JInstaller
{
	/*
	 * For Joomla 3
	 */
	public function getAdapter($name, $options = [])
	{
		if ($name == 'library')
		{
			return new RLInstallerAdapterLibrary($this, $this->getDbo(), $options);
		}

		$adapter = $this->loadAdapter($name, $options);

		if ( ! array_key_exists($name, $this->_adapters))
		{
			if ( ! $this->setAdapter($name, $adapter))
			{
				return false;
			}
		}

		return $adapter;
	}

	/*
	 * For Joomla 4
	 */
	public function loadAdapter($adapter, $options = [])
	{
		if ($adapter == 'library')
		{
			// Ensure the adapter type is part of the options array
			$options['type'] = $adapter;

			return new RLInstallerAdapterLibrary($this, $this->getDbo(), $options);
		}

		return parent::loadAdapter($adapter, $options);
	}
}

JLoader::import('joomla.installer.adapter.library');

class RLInstallerAdapterLibrary extends JInstallerAdapterLibrary
{
	/*
	 * For Joomla 3
	 */
	protected function checkExtensionInFilesystem()
	{
		if ( ! $this->currentExtensionId)
		{
			return;
		}

		// Already installed, can we upgrade?
		if ( ! $this->parent->isOverwrite() && ! $this->parent->isUpgrade())
		{
			// Abort the install, no upgrade possible
			throw new RuntimeException(JText::_('JLIB_INSTALLER_ABORT_LIB_INSTALL_ALREADY_INSTALLED'));
		}

		// From this point we'll consider this an update
		$this->setRoute('update');
	}

	/*
	 * For Joomla 4
	 */
	protected function checkExistingExtension()
	{
		if ( ! $this->currentExtensionId)
		{
			return;
		}

		// Already installed, can we upgrade?
		if ( ! $this->parent->isOverwrite() && ! $this->parent->isUpgrade())
		{
			// Abort the install, no upgrade possible
			throw new RuntimeException(JText::_('JLIB_INSTALLER_ABORT_LIB_INSTALL_ALREADY_INSTALLED'));
		}

		// From this point we'll consider this an update
		$this->setRoute('update');
	}

	protected function storeExtension()
	{
		$db    = $this->parent->getDbo();
		$query = $db->getQuery(true)
			->delete($db->quoteName('#__extensions'))
			->where($db->quoteName('type') . ' = ' . $db->quote('library'))
			->where($db->quoteName('element') . ' = ' . $db->quote($this->element));
		$db->setQuery($query);

		$db->execute();

		parent::storeExtension();

		JFactory::getCache()->clean('_system');
	}
}
