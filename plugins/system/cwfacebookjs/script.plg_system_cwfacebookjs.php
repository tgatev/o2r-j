<?php
defined('_JEXEC') or die('Restricted access');
/**
 * @package     Joomla
 * @subpackage  plg_system_cwfacebookjs
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL, see /assets/en-GB.license.txt
 * @copyright   Copyright (c) 2018 Steven Palmer All rights reserved.
 *
 * This extension is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/gpl.html>.
 */
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.log.log');

class plgSystemCwfacebookjsInstallerScript
{
    /**
     * @var string The extension name
     */
    private $coalaweb_extension = 'plg_system_cwfacebookjs';

    /**
     * @var string The Gears version that is being installed
     */
    private $gears_version = '0.5.8';

    /**
     * @var string Possible duplicate update info
     */
    private $update_remove = array(
        'http://cdn.coalaweb.com/updates/cw-facebookjs-core.xml',
        'http://cdn.coalaweb.com/updates/cw-facebookjs-pro.xml',
        'https://coalaweb.com/index.php?option=com_rdsubs&view=updater&format=xml&cat=&type=.xml',
    );

    /**
     * Joomla! pre-flight event
     *
     * @param string $type Installation type (install, update, discover_install)
     * @param JInstaller $parent
     * @return bool
     */
    public function preflight($type, $parent)
    {
        if ( strtolower($type) === 'update' && $this->coalaweb_extension == 'plg_system_cwgears' && $this->onBeforeInstall($parent) === false) {
            $msg = "<p>You already have a <strong>newer</strong> version of <strong>CoalaWeb Gears</strong> installed. If you would like to <strong>downgrade</strong> please uninstall it first and then install the older version.</p>";
            JLog::add($msg, JLog::WARNING, 'jerror');
            return false;
        }

        //Are we trying to uninstall CoalaWeb Gears? Lets check for dependencies first
        if (strtolower($type) === 'uninstall' && $this->coalaweb_extension == 'plg_system_cwgears') {
            $dependencyCount = count($this->getDependencies('gears'));
            if ($dependencyCount) {
                $msg = '<p>You have ' . $dependencyCount . ' extension(s) depending on this version of <strong>CoalaWeb Gears</strong>. It cannot be uninstalled unless the other extension(s) are uninstalled first.</p>';
                JLog::add($msg, JLog::WARNING, 'jerror');
                return false;
            }
        }

        return true;
    }

    /**
     * Runs after install, update or discover_update
     *
     * @param string $type install, update or discover_update
     * @param JInstaller $parent
     * @return bool
     */
    public function postflight($type, $parent)
    {
        // Remove duplicate update info
        $this->removeUpdateSite();

        // Add ourselves to the list of extensions depending on CoalaWeb Gears
        if ($this->coalaweb_extension != 'plg_system_cwgears') {
            $this->addDependency('gears', $this->coalaweb_extension);
        }

        JFactory::getCache()->clean('com_plugins');
        JFactory::getCache()->clean('_system');

        return true;
    }

    /**
     * Runs on uninstallation
     *
     * @param JInstaller $parent
     * @return bool
     */
    function uninstall($parent)
    {
        // Lets remove this extension from the dependency table
        if ($this->coalaweb_extension != 'plg_system_cwgears') {
            $this->removeDependency('gears', $this->coalaweb_extension);
        }
    }

    /**
     * Should we continue install?
     * @param $parent
     * @return bool
     */
    public function onBeforeInstall($parent)
    {
        if (!$this->gearsNewer()) {
            return false;
        }
        return true;
    }

    /**
     * Check if installed Gears is newer that current attempt
     *
     * @return bool
     */
    private function gearsNewer()
    {
        $gearsInstalled = $this->getExtensionData('cwgears');
        $manifest = json_decode($gearsInstalled->manifest_cache);

        if ($manifest->version >= $this->gears_version) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get a variety of extention data
     *
     * @param null $element
     * @return mixed
     */
    private function getExtensionData($element = null)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select([
                'e.extension_id', 'e.type', 'e.name', 'e.manifest_cache', 'us.update_site_id', 'us.enabled', 'us.extra_query',
                'u.version as new_version',
            ])
            ->from($db->quoteName('#__extensions', 'e'))
            ->join('LEFT OUTER', $db->quoteName('#__update_sites_extensions', 'use') . ' ON (' . $db->quoteName('e.extension_id') . ' = ' . $db->quoteName('use.extension_id') . ')')
            ->join('LEFT OUTER', $db->quoteName('#__update_sites', 'us') . ' ON (' . $db->quoteName('us.update_site_id') . ' = ' . $db->quoteName('use.update_site_id') . ')')
            ->join('LEFT OUTER', $db->quoteName('#__updates', 'u') . ' ON (' . $db->quoteName('u.update_site_id') . ' = ' . $db->quoteName('use.update_site_id') . ')')
            ->where($db->quoteName('e.element') . ' = ' . $db->quote($element));

        $db->setQuery($query);

        return $db->loadObject();
    }

    /**
     * Remove any conflicting update sites
     */
    private function removeUpdateSite()
    {
        //Do we have anything to remove?
        if (empty($this->update_remove)) {
            return;
        }

        // We only need the last part of the name(element) for plugins
        $extRoot = explode('_', $this->coalaweb_extension);
        if ($extRoot !== null && array_key_exists(2, $extRoot)) {
            $element = $extRoot[2];
        } else {
            $element = $this->coalaweb_extension;
        }

        $extType = 'plugin';

        $db = JFactory::getDbo();

        foreach ($this->update_remove as $url) {

            // Get some info on all the stuff we've gotta delete
            $query = $db->getQuery(true);

            $query
                ->select(array(
                    $db->qn('s') . '.' . $db->qn('update_site_id'),
                    $db->qn('e') . '.' . $db->qn('extension_id'),
                    $db->qn('e') . '.' . $db->qn('element'),
                    $db->qn('s') . '.' . $db->qn('location'),
                ))
                ->from($db->qn('#__update_sites') . ' AS ' . $db->qn('s'))
                ->join('INNER', $db->qn('#__update_sites_extensions') . ' AS ' . $db->qn('se') . ' ON(' .
                    $db->qn('se') . '.' . $db->qn('update_site_id') . ' = ' .
                    $db->qn('s') . '.' . $db->qn('update_site_id')
                    . ')')
                ->join('INNER', $db->qn('#__extensions') . ' AS ' . $db->qn('e') . ' ON(' .
                    $db->qn('e') . '.' . $db->qn('extension_id') . ' = ' .
                    $db->qn('se') . '.' . $db->qn('extension_id')
                    . ')')
                ->where($db->qn('s') . '.' . $db->qn('type') . ' = ' . $db->q('extension'))
                ->where($db->qn('e') . '.' . $db->qn('type') . ' = ' . $db->q($extType))
                ->where($db->qn('e') . '.' . $db->qn('element') . ' = ' . $db->q($element))
                ->where($db->qn('s') . '.' . $db->qn('location') . ' = ' . $db->q($url));

            try {
                $db->setQuery($query);
                $oResult = $db->loadObject();
            } catch (Exception $e) {
                $oResult = '';
            }

            // If no record is found, do nothing. We've already killed the monster!
            if (is_null($oResult)) {
                continue;
            }

            // Delete the #__update_sites record
            $query = $db->getQuery(true);

            $query
                ->delete($db->qn('#__update_sites'))
                ->where($db->qn('update_site_id') . ' = ' . $db->q($oResult->update_site_id));

            $db->setQuery($query);

            try {
                $db->execute();
            } catch (Exception $exc) {
                // If the query fails, don't sweat about it
            }

            // Delete the #__update_sites_extensions record
            $query = $db->getQuery(true);
            $query
                ->delete($db->qn('#__update_sites_extensions'))
                ->where($db->qn('update_site_id') . ' = ' . $db->q($oResult->update_site_id));

            $db->setQuery($query);

            try {
                $db->execute();
            } catch (Exception $exc) {
                // If the query fails, don't sweat about it
            }

            // Delete the #__updates records
            $query = $db->getQuery(true);

            $query
                ->delete($db->qn('#__updates'))
                ->where($db->qn('update_site_id') . ' = ' . $db->q($oResult->update_site_id));

            $db->setQuery($query);

            try {
                $db->execute();
            } catch (Exception $exc) {
                // If the query fails, don't sweat about it
            }
        }
    }

    /**
     * Get the dependencies for a package from the #__coalaweb_common table
     *
     * @param   string  $package  The package
     *
     * @return  array  The dependencies
     */
    protected function getDependencies($package)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select($db->qn('value'))
            ->from($db->qn('#__coalaweb_common'))
            ->where($db->qn('key') . ' = ' . $db->q($package));

        try
        {
            $dependencies = $db->setQuery($query)->loadResult();
            $dependencies = json_decode($dependencies, true);

            if (empty($dependencies))
            {
                $dependencies = array();
            }
        }
        catch (Exception $e)
        {
            $dependencies = array();
        }

        return $dependencies;
    }

    /**
     * Sets the dependencies for a package into the #__coalaweb_common table
     *
     * @param   string  $package       The package
     * @param   array   $dependencies  The dependencies list
     */
    protected function setDependencies($package, array $dependencies)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->delete('#__coalaweb_common')
            ->where($db->qn('key') . ' = ' . $db->q($package));

        try
        {
            $db->setQuery($query)->execute();
        }
        catch (Exception $e)
        {
            // Do nothing if the old key wasn't found
        }

        $object = (object)array(
            'key' => $package,
            'value' => json_encode($dependencies)
        );

        try
        {
            $db->insertObject('#__coalaweb_common', $object, 'key');
        }
        catch (Exception $e)
        {
            // Do nothing if the old key wasn't found
        }
    }

    /**
     * Adds a package dependency to #__coalaweb_common
     *
     * @param   string  $package     The package
     * @param   string  $dependency  The dependency to add
     */
    protected function addDependency($package, $dependency)
    {
        $dependencies = $this->getDependencies($package);

        if (!in_array($dependency, $dependencies))
        {
            $dependencies[] = $dependency;

            $this->setDependencies($package, $dependencies);
        }
    }

    /**
     * Removes a package dependency from #__coalaweb_common
     *
     * @param   string  $package     The package
     * @param   string  $dependency  The dependency to remove
     */
    protected function removeDependency($package, $dependency)
    {
        $dependencies = $this->getDependencies($package);

        if (in_array($dependency, $dependencies))
        {
            $index = array_search($dependency, $dependencies);
            unset($dependencies[$index]);

            $this->setDependencies($package, $dependencies);
        }
    }

    /**
     * Do I have a dependency for a package in #__coalaweb_common
     *
     * @param   string  $package     The package
     * @param   string  $dependency  The dependency to check for
     *
     * @return bool
     */
    protected function hasDependency($package, $dependency)
    {
        $dependencies = $this->getDependencies($package);

        return in_array($dependency, $dependencies);
    }
}
