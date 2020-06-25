<?php
defined('_JEXEC') or die('Restricted access');
/**
 * @package     Joomla
 * @subpackage  com_coalawebsociallinks
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

class com_coalawebsociallinksInstallerScript
{
    /**
     * @var string The extension name
     */
    private $coalaweb_extension = 'com_coalawebsociallinks';

    /**
     * @var string The Gears version that is being installed
     */
    private $gears_version = '0.5.9';

    /**
     * @var string Possible duplicate update info
     */
    private $update_remove = array(
        'http://cdn.coalaweb.com/updates/cw-sociallinks-core.xml',
        'http://cdn.coalaweb.com/updates/cw-sociallinks-pro.xml',
        'https://coalaweb.com/index.php?option=com_rdsubs&view=updater&format=xml&cat=1&type=.xml',
    );

    /**
     * @var array The list of extra modules and plugins to install
     */
    private $installation_queue = array(
        'modules' => array(
            'admin' => array(
            ),
            'site' => array('coalawebsociallinks' => array('left', 0),'coalawebpage' => array('left', 0),'coalawebtweet' => array('left', 0)
            )
        ),
        'plugins' => array(
            'system' => array('cwfacebookjs' => 1, 'cwgears' => 1
            ),
            'content' => array(
            ),
            'editors-xtd' => array(
            )
        ),
        'libraries' => array(
        )
    );

    /**
     * @var array The list of extra modules and plugins to uninstall
     */
    private $uninstallation_queue = array(
        'modules' => array(
            'admin' => array('cwquickicons'
            ),
            'site' => array('coalawebsociallinks','coalawebpage','coalawebtweet','coalaweblikebox','coalawebgplus'
            )
        ),
        'plugins' => array(
            'system' => array('cwzooelements','cwmenumeta'
            ),
            'content' => array('cwsociallikes','cwsocialpanel','cwsocialshare','cwsharecount','cwopengraph','cwmetafields'
            ),
            'editors-xtd' => array(
            ),
            'installer' => array('cwslupdate'
            ),
            'quickicon' => array('cwslquickicon'
            )
        ),
        'libraries' => array(
        )
    );

    /**
     * @var array The list of pro or obsolete plugins to remove
     */
    private $removeProObsoletePlugins = array(
        'plugins' => array(
            'system' => array('cwzooelements','cwmenumeta'
            ),
            'content' => array('cwsociallikes','cwsocialpanel','cwsocialshare','cwsharecount','cwopengraph','cwmetafields'
            ),
            'editors-xtd' => array(
            )
        )
    );

    /**
     * @var array The list of pro or obsolete modules to remove
     */
    private $removeProObsoleteModules = array(
        'modules' => array(
            'admin' => array(
            ),
            'site' => array('coalawebgplus','coalawebsocialtabs','coalaweblikebox'
            )
        )
    );

    /**
     * @var array Plugins that should activated automatically
     */
    private $activatePlugins = array(
        'plugins' => array(
            'system' => array(
            ),
            'content' => array(
            ),
            'editors-xtd' => array(
            )
        )
    );

    /**
     * Folders to be moved in format 'from' => 'to'
     * @var array
     */
    private $moveFolders = array(
        'folders' => array('media/coalawebsocial' => 'media/coalawebsociallinks'
        )
    );

    /**
     * @var array New files and folders to add
     */
    private $addFiles = array(
        'files' => array(
        ),
        'folders' => array(
        )
    );

    /**
     * @var array Obsolete files and folders to remove
     */
    private $removeFiles = array(
        'files' => array('modules/mod_coalawebsociallinks/tmpl/horizontal.php','administrator/components/com_coalawebsociallinks/controllers/count.php','administrator/components/com_coalawebsociallinks/controllers/counts.php','administrator/components/com_coalawebsociallinks/models/count.php','administrator/components/com_coalawebsociallinks/models/counts.php','administrator/components/com_coalawebsociallinks/models/forms/filter_counts.xml','administrator/components/com_coalawebsociallinks/models/forms/counts.xml','administrator/components/com_coalawebsociallinks/tables/counts.php','administrator/components/com_coalawebsociallinks/helpers/socialsharecount.php','administrator/components/com_coalawebsociallinks/helpers/cwurlshortener.php','administrator/components/com_coalawebsociallinks/tables/counts.php','administrator/components/com_coalawebsociallinks/script.coalawebsociallinks.php'
        ),
        'folders' => array('administrator/components/com_coalawebsociallinks/views/count','administrator/components/com_coalawebsociallinks/views/counts','media/coalawebsocial/modules/socialtabs','media/coalawebsocial/modules/gplus','media/coalawebsociallinks/modules/socialtabs','media/coalawebsociallinks/modules/gplus','media/com_coalawebsociallinks','media/mod_coalawebsociallinks','media/mod_coalaweblikebox','media/mod_cwquickicons','media/plg_cwopengraph','media/plg_cwslquickicon','media/plg_cwsociallikes','media/plg_cwzooelements','media/coalawebsocial/modules/sociallinks/themes-icon','media/coalawebsociallinks/modules/sociallinks/themes-icon','media/coalawebsociallinks/components/sociallinks/themes-icon/cws-color-ring','media/coalawebsociallinks/components/sociallinks/themes-icon/cws-circle-fadein','media/coalawebsociallinks/components/sociallinks/themes-icon/cws-circle-fadeout','media/coalawebsociallinks/components/sociallinks/themes-icon/cws-square-fadein','media/coalawebsociallinks/components/sociallinks/themes-icon/cws-square-fadeout','media/coalawebsociallinks/components/sociallinks/themes-icon/cws-square-rc-fadein','media/coalawebsociallinks/components/sociallinks/themes-icon/cws-square-rc-fadeout'
        )
    );

    /**
     * @var array CLI Scripts to install
     */
    private $cliScripts = array(
    );

    /**
     * Transfer the current parameter settings from one extension to another
     * Example 'old' => 'new'
     *
     * @var array old and new extension names for param transfer
     */
    private $copyParams = array(
        'extensions' => array(
        )
    );

    /**
     * List of old plugin names that don't include a type to fix language string display
     *
     * @var array of old plugin names
     */
    private $oldPluginNames = array('cwgears','cwfacebookjs','cwmetafields','cwopengraph','cwsharecount','cwsociallikes','cwsocialshare','cwzooelements','cwmenumeta'
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
        // Only allow to install on Joomla! 3.9 or later with PHP 5.6 or later
        if (defined('PHP_VERSION')) {
            $version = PHP_VERSION;
        } elseif (function_exists('phpversion')) {
            $version = phpversion();
        } else {
            $version = '5.0.0'; // all bets are off!
        }

        if (!version_compare(JVERSION, '3.9', 'ge')) {
            $msg = "<p>Sorry, you need Joomla! 3.9 or later to install this extension!</p>";
            JLog::add($msg, JLog::WARNING, 'jerror');
            return false;
        }

        if (!version_compare($version, '5.6', 'ge')) {
            $msg = "<p>Sorry, you need PHP 5.6 or later to install this extension!</p>";
            JLog::add($msg, JLog::WARNING, 'jerror');
            return false;
        }

        if ( strtolower($type) === 'update' && $this->coalaweb_extension == 'plg_system_cwgears' && $this->onBeforeInstall($parent) === false) {
            $msg = "<p>You already have a <strong>newer</strong> version of <strong>CoalaWeb Gears</strong> installed. If you would like to <strong>downgrade</strong> please uninstall it first and then install the older version.</p>";
            JLog::add($msg, JLog::WARNING, 'jerror');
            return false;
        }

        // Move folders if needed and it's an update
        if (strtolower($type) === 'update') {
            $this->moveFolders();
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
        // Install subextensions
        $status = $this->installSubextensions($parent);

        // Remove obsolete files and folders
        $this->removeObsoleteFilesAndFolders($this->removeFiles);

        // Add new files and folders
        $this->addNewFilesAndFolders($parent);

        // Copy any included CLI scripts into Joomla!'s cli directory
        $this->copyCliFiles($parent);

        //Activate main plugin and copy params only on install
        if (strtolower($type) === 'install') {
            $this->activatePlugin($parent);
            $this->copyParams($parent);
        }

        // Remove Pro or Obsolete extensions
        $this->removeProObsoletePlugins($parent);
        $this->removeProObsoleteModules($parent);

        // Remove duplicate update info
        $this->removeUpdateSite();

        // Add ourselves to the list of extensions depending on CoalaWeb Gears
        if ($this->coalaweb_extension != 'plg_system_cwgears') {
            $this->addDependency('gears', $this->coalaweb_extension);
        }

        // Show the post-installation page
        $this->renderPostInstallation($status, $parent, $type);

        JFactory::getCache()->clean('com_plugins');
        JFactory::getCache()->clean('_system');

        return true;
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

        // Uninstall subextensions
        $status = $this->uninstallSubextensions($parent);

        // Show the post-uninstallation page
        $this->renderPostUninstallation($status, $parent);
    }

    /**
     * Renders the post-installation message
     *
     * @param $status
     * @param JInstaller $parent
     * @param string $type
     */
    private function renderPostInstallation($status, $parent, $type)
    {
        ?>

        <?php
        $rows = 1;
        $typeUpper = strtoupper('core');
        ?>
        <style type="text/css">
            .coalaweb {
                font-family: "Trebuchet MS", Helvetica, sans-serif;
                font-size: 13px !important;
                font-weight: 400 !important;
                color: #4D4D4D;
                border: solid #ccc 1px;
                background: #fff;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                border-radius: 3px;
                *border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                margin-bottom: 15px !important
            }

            .coalaweb tr:hover {
                background: #E8F6FE;
                -o-transition: all .1s ease-in-out;
                -webkit-transition: all .1s ease-in-out;
                -moz-transition: all .1s ease-in-out;
                -ms-transition: all .1s ease-in-out;
                transition: all .1s ease-in-out
            }

            .coalaweb tr.row1 {
                background-color: #F0F0EE
            }

            .coalaweb td, .coalaweb th {
                border-left: 1px solid #ccc;
                border-top: 1px solid #ccc;
                padding: 10px !important;
                text-align: left
            }

            .coalaweb th {
                border-top: none;
                color: #333 !important;
                text-shadow: 0 1px 1px #FFF;
                border-bottom: 4px solid #1272a5 !important
            }

            .coalaweb td:first-child, .coalaweb th:first-child {
                border-left: none
            }

            .coalaweb th:first-child {
                -moz-border-radius: 3px 0 0;
                -webkit-border-radius: 3px 0 0 0;
                border-radius: 3px 0 0 0
            }

            .coalaweb th:last-child {
                -moz-border-radius: 0 3px 0 0;
                -webkit-border-radius: 0 3px 0 0;
                border-radius: 0 3px 0 0
            }

            .coalaweb th:only-child {
                -moz-border-radius: 6px 6px 0 0;
                -webkit-border-radius: 6px 6px 0 0;
                border-radius: 6px 6px 0 0
            }

            .coalaweb tr:last-child td:first-child {
                -moz-border-radius: 0 0 0 3px;
                -webkit-border-radius: 0 0 0 3px;
                border-radius: 0 0 0 3px
            }

            .coalaweb tr:last-child td:last-child {
                -moz-border-radius: 0 0 3px;
                -webkit-border-radius: 0 0 3px 0;
                border-radius: 0 0 3px 0
            }

            .coalaweb em, .coalaweb strong {
                color: #1272A5;
                font-weight: 700
            }
        </style>
        <link rel="stylesheet" href="../media/coalaweb/components/generic/css/com-coalaweb-base-v2.css" type="text/css">
        <div class="well well-lg">
            <h3><?php echo JText::_('COM_CWSOCIALLINKS_POST_INSTALL_TITLE'); ?></h3>
            <?php if ($type == 'update') : ?>
                
            <?php endif; ?>
            <div class="alert alert-danger">
                <span class="icon-notification"></span>
                <?php echo JText::_('COM_CWSOCIALLINKS_POST_INSTALL_MSG'); ?>
            </div>
            <?php if ($status->gears) : ?>
                <div class="alert alert-danger">
                    <span class="icon-notification"></span>
                    <?php echo JText::_('COM_CWSOCIALLINKS_GEARS_CURRENT_NEWER_MSG'); ?>
                </div>
            <?php endif; ?>
            <h3><?php echo JText::_('COM_CWSOCIALLINKS_INSTALL_DETAILS_TITLE'); ?></h3>
            <table class="coalaweb">
                <thead align="left">
                <tr>
                    <th class="title" align="left">Main</th>
                    <th width="25%">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr class="row0">
                    <td class="key">
                        <?php echo JText::_('COM_CWSOCIALLINKS_TITLE_' . $typeUpper . ''); ?>
                    </td>
                    <td>
                        <strong style="color: green">Installed</strong>
                    </td>
                </tr>
                </tbody>
            </table>

            <?php if (count($status->modules)) : ?>
                <table class="coalaweb">
                    <thead align="left">
                    <tr>
                        <th class="title" align="left">Modules</th>
                        <th width="25%">Client</th>
                        <th width="25%">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($status->modules as $module) : ?>
                        <tr class="row<?php echo($rows++ % 2); ?>">
                            <td class="key"><?php echo JText::_($module['name']); ?></td>
                            <td class="key"><?php echo ucfirst($module['client']); ?></td>
                            <td>
                                <strong style="color: <?php echo ($module['result']) ? "green" : "red" ?>"><?php echo ($module['result']) ? 'Installed' : 'Not installed'; ?></strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if (count($status->plugins)) : ?>
            <table class="coalaweb">
                <thead align="left">
                <tr>
                    <th class="title" align="left">Plugins</th>
                    <th width="25%">Group</th>
                    <th width="25%">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($status->plugins as $plugin) : ?>
                    <tr class="row<?php echo($rows++ % 2); ?>">
                        <td class="key"><?php echo JText::_($plugin['name']); ?></td>
                        <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
                        <td>
                            <strong style="color: <?php echo ($plugin['result']) ? "green" : "red" ?>"><?php echo ($plugin['result']) ? 'Installed' : 'Not installed'; ?></strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Renders the post-uninstallation message
     *
     * @param $status
     * @param JInstaller $parent
     */
    private function renderPostUninstallation($status, $parent)
    {
        ?>
        <?php
        $rows = 0;
        $typeUpper = strtoupper('core');
        ?>
        <style type="text/css">
            .coalaweb {
                font-family: "Trebuchet MS", Helvetica, sans-serif;
                font-size: 13px !important;
                font-weight: 400 !important;
                color: #4D4D4D;
                border: solid #ccc 1px;
                background: #fff;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                border-radius: 3px;
                *border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                margin-bottom: 15px !important
            }

            .coalaweb tr:hover {
                background: #E8F6FE;
                -o-transition: all .1s ease-in-out;
                -webkit-transition: all .1s ease-in-out;
                -moz-transition: all .1s ease-in-out;
                -ms-transition: all .1s ease-in-out;
                transition: all .1s ease-in-out
            }

            .coalaweb tr.row1 {
                background-color: #F0F0EE
            }

            .coalaweb td, .coalaweb th {
                border-left: 1px solid #ccc;
                border-top: 1px solid #ccc;
                padding: 10px !important;
                text-align: left
            }

            .coalaweb th {
                border-top: none;
                color: #333 !important;
                text-shadow: 0 1px 1px #FFF;
                border-bottom: 4px solid #1272a5 !important
            }

            .coalaweb td:first-child, .coalaweb th:first-child {
                border-left: none
            }

            .coalaweb th:first-child {
                -moz-border-radius: 3px 0 0;
                -webkit-border-radius: 3px 0 0 0;
                border-radius: 3px 0 0 0
            }

            .coalaweb th:last-child {
                -moz-border-radius: 0 3px 0 0;
                -webkit-border-radius: 0 3px 0 0;
                border-radius: 0 3px 0 0
            }

            .coalaweb th:only-child {
                -moz-border-radius: 6px 6px 0 0;
                -webkit-border-radius: 6px 6px 0 0;
                border-radius: 6px 6px 0 0
            }

            .coalaweb tr:last-child td:first-child {
                -moz-border-radius: 0 0 0 3px;
                -webkit-border-radius: 0 0 0 3px;
                border-radius: 0 0 0 3px
            }

            .coalaweb tr:last-child td:last-child {
                -moz-border-radius: 0 0 3px;
                -webkit-border-radius: 0 0 3px 0;
                border-radius: 0 0 3px 0
            }

            .coalaweb em, .coalaweb strong {
                color: #1272A5;
                font-weight: 700
            }
        </style>
        <div class="well well-lg">
            <h3><?php echo JText::_('COM_CWSOCIALLINKS_TITLE_' . $typeUpper . '') . ' - ' . JText::_('COM_CWSOCIALLINKS_UNINSTALL_STATUS_MSG') ?></h3>
            <table class="coalaweb">
                <thead align="left">
                <tr>
                    <th class="title" align="left">Main</th>
                    <th width="25%">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr class="row0">
                    <td class="key">
                        <?php echo JText::_('COM_CWSOCIALLINKS_TITLE_' . $typeUpper . ''); ?>
                    </td>
                    <td>
                        <strong style="color: green">Uninstalled</strong>
                    </td>
                </tr>
                </tbody>
            </table>

            <?php if (count($status->modules)) : ?>
                <table class="coalaweb">
                    <thead align="left">
                    <tr>
                        <th class="title" align="left">Modules</th>
                        <th width="25%">Client</th>
                        <th width="25%">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($status->modules as $module) : ?>
                        <tr class="row<?php echo($rows++ % 2); ?>">
                            <td class="key"><?php echo JText::_($module['name']); ?></td>
                            <td class="key"><?php echo ucfirst($module['client']); ?></td>
                            <td>
                                <strong style="color: <?php echo ($module['result']) ? "green" : "red" ?>"><?php echo ($module['result']) ? 'Uninstalled' : 'Not uninstalled'; ?></strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if (count($status->plugins)) : ?>
            <table class="coalaweb">
                <thead align="left">
                <tr>
                    <th class="title" align="left">Plugins</th>
                    <th width="25%">Group</th>
                    <th width="25%">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($status->plugins as $plugin) : ?>
                    <tr class="row<?php echo($rows++ % 2); ?>">
                        <td class="key"><?php echo JText::_($plugin['name']); ?></td>
                        <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
                        <td>
                            <strong style="color: <?php echo ($plugin['result']) ? "green" : "red" ?>"><?php echo ($plugin['result']) ? 'Uninstalled' : 'Not uninstalled'; ?></strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Removes Pro or Obsolete plugins
     *
     * @param JInstaller $parent
     */
    private function removeProObsoletePlugins($parent)
    {
        $db = JFactory::getDbo();

        if (count($this->removeProObsoletePlugins['plugins'])) {
            foreach ($this->removeProObsoletePlugins['plugins'] as $folder => $plugins) {
                foreach ($plugins as $plugin) {

                    // Find the plugin ID
                    $query = $db->getQuery(true);

                    $query
                        ->select($db->qn('extension_id'))
                        ->from($db->qn('#__extensions'))
                        ->where($db->qn('type') . ' = ' . $db->q('plugin'))
                        ->where($db->qn('element') . ' = ' . $db->q($plugin))
                        ->where($db->qn('folder') . ' = ' . $db->q($folder));

                    $db->setQuery($query);
                    $id = $db->loadResult();

                    if ($id) {
                        $installer = new JInstaller;
                        $result = $installer->uninstall('plugin', $id, 1);
                    }
                }
            }
        }
    }

    /**
     * Removes Pro or Obsolete modules
     *
     * @param JInstaller $parent
     */
    private function removeProObsoleteModules($parent)
    {
        $src = $parent->getParent()->getPath('source');
        $db = JFactory::getDbo();

        if (count($this->removeProObsoleteModules['modules'])) {
            foreach ($this->removeProObsoleteModules['modules'] as $folder => $modules) {
                foreach ($modules as $module) {

                    if (empty($folder)) {
                        $folder = 'site';
                    }

                    // Find the module ID
                    $query = $db->getQuery(true);

                    $query
                        ->select($db->qn('extension_id'))
                        ->from($db->qn('#__extensions'))
                        ->where($db->qn('element') . ' = ' . $db->q('mod_' . $module))
                        ->where($db->qn('type') . ' = ' . $db->q('module'));

                    $db->setQuery($query);
                    $id = $db->loadResult();

                    // Uninstall the module
                    if ($id) {
                        $installer = new JInstaller;
                        $result = $installer->uninstall('module', $id, 1);
                    }
                }
            }
        }
    }

    /**
     * Copies any CLI scripts into Joomla!'s cli directory
     *
     * @param JInstaller $parent
     */
    private function copyCliFiles($parent)
    {
        if (!count($this->cliScripts)) {
            return;
        }
        $src = $parent->getParent()->getPath('source');

        jimport("joomla.filesystem.file");
        jimport("joomla.filesystem.folder");

        foreach ($this->cliScripts as $script) {
            if (JFile::exists(JPATH_ROOT . '/cli/' . $script)) {
                JFile::delete(JPATH_ROOT . '/cli/' . $script);
            }
            if (JFile::exists($src . '/cli/' . $script)) {
                JFile::move($src . '/cli/' . $script, JPATH_ROOT . '/cli/' . $script);
            }
        }
    }

    /**
     * Installs subextensions (modules, plugins) bundled with the main extension
     *
     * @param JInstaller $parent
     * @return JObject The subextension installation status
     */
    private function installSubextensions($parent)
    {
        $src = $parent->getParent()->getPath('source');

        $db = JFactory::getDbo();

        $status = new JObject();
        $status->modules = array();
        $status->plugins = array();
        $status->gears = '';

        // Modules installation
        if (count($this->installation_queue['modules'])) {
            foreach ($this->installation_queue['modules'] as $folder => $modules) {
                if (count($modules)) {
                    foreach ($modules as $module => $modulePreferences) {
                        // Install the module
                        if (empty($folder)) {
                            $folder = 'site';
                        }

                        $path = "$src/modules/$folder/$module";

                        if (!is_dir($path)) {
                            $path = "$src/modules/$folder/mod_$module";
                        }

                        if (!is_dir($path)) {
                            $path = "$src/modules/$module";
                        }

                        if (!is_dir($path)) {
                            $path = "$src/modules/mod_$module";
                        }

                        if (!is_dir($path)) {
                            continue;
                        }

                        // Was the module already installed?
                        $query = $db->getQuery(true);
                        $query
                            ->select('COUNT(*)')
                            ->from('#__modules')
                            ->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));

                        $db->setQuery($query);

                        try {
                            $count = $db->loadResult();
                        } catch (Exception $exc) {
                            $count = 0;
                        }

                        $installer = new JInstaller;
                        $result = $installer->install($path);
                        $status->modules[] = array(
                            'name' => 'mod_' . $module,
                            'client' => $folder,
                            'result' => $result
                        );

                        // Modify where it's published and its published state
                        if (!$count) {
                            // A. Position and state
                            list($modulePosition, $modulePublished) = $modulePreferences;

                            if ($modulePosition == 'cpanel') {
                                $modulePosition = 'icon';
                            }

                            $query = $db->getQuery(true);
                            $query
                                ->update($db->qn('#__modules'))
                                ->set($db->qn('position') . ' = ' . $db->q($modulePosition))
                                ->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));

                            if ($modulePublished) {
                                $query->set($db->qn('published') . ' = ' . $db->q('1'));
                            }

                            $db->setQuery($query);

                            try {
                                $db->execute();
                            } catch (Exception $exc) {
                                // Nothing
                            }

                            // B. Change the ordering of back-end modules to 1 + max ordering
                            if ($folder == 'admin') {
                                try {
                                    $query = $db->getQuery(true);
                                    $query
                                        ->select('MAX(' . $db->qn('ordering') . ')')
                                        ->from($db->qn('#__modules'))
                                        ->where($db->qn('position') . '=' . $db->q($modulePosition));

                                    $db->setQuery($query);
                                    $position = $db->loadResult();

                                    $position++;

                                    $query = $db->getQuery(true);
                                    $query
                                        ->update($db->qn('#__modules'))
                                        ->set($db->qn('ordering') . ' = ' . $db->q($position))
                                        ->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));

                                    $db->setQuery($query);
                                    $db->execute();
                                } catch (Exception $exc) {
                                    // Nothing
                                }
                            }

                            // C. Link to all pages
                            try {
                                $query = $db->getQuery(true);
                                $query
                                    ->select('id')->from($db->qn('#__modules'))
                                    ->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));

                                $db->setQuery($query);
                                $moduleid = $db->loadResult();

                                $query = $db->getQuery(true);
                                $query
                                    ->select('*')->from($db->qn('#__modules_menu'))
                                    ->where($db->qn('moduleid') . ' = ' . $db->q($moduleid));

                                $db->setQuery($query);
                                $assignments = $db->loadObjectList();

                                $isAssigned = !empty($assignments);
                                if (!$isAssigned) {
                                    $o = (object)array(
                                        'moduleid' => $moduleid,
                                        'menuid' => 0
                                    );
                                    $db->insertObject('#__modules_menu', $o);
                                }
                            } catch (Exception $exc) {
                                // Nothing
                            }
                        }
                    }
                }
            }
        }

        if (array_key_exists('cwgears', $this->installation_queue['plugins']['system'])) {

            // Was the plugin already installed?
            $query = $db->getQuery(true)
                ->select('COUNT(*)')
                ->from($db->qn('#__extensions'))
                ->where($db->qn('element') . ' = ' . $db->q('cwgears'))
                ->where($db->qn('folder') . ' = ' . $db->q('system'));
            $db->setQuery($query);

            try {
                $count = $db->loadResult();
            } catch (Exception $exc) {
                $count = 0;
            }

            // If count(already installed) and name gears check gears version variable against current manifest cache.
            if ($count) {
                if (!$this->gearsNewer()) {
                    unset($this->installation_queue['plugins']['system']['cwgears']);
                    // Installed version is newer so lets tell the user
                    $status->gears = '1';

                }
            }
        }

        // Plugins installation
        if (count($this->installation_queue['plugins'])) {
            foreach ($this->installation_queue['plugins'] as $folder => $plugins) {
                if (count($plugins)) {
                    foreach ($plugins as $plugin => $published) {
                        $path = "$src/plugins/$folder/$plugin";

                        if (!is_dir($path)) {
                            $path = "$src/plugins/$folder/plg_$plugin";
                        }

                        if (!is_dir($path)) {
                            $path = "$src/plugins/$plugin";
                        }

                        if (!is_dir($path)) {
                            $path = "$src/plugins/plg_$plugin";
                        }

                        if (!is_dir($path)) {
                            continue;
                        }

                        // Was the plugin already installed?
                        $query = $db->getQuery(true)
                            ->select('COUNT(*)')
                            ->from($db->qn('#__extensions'))
                            ->where($db->qn('element') . ' = ' . $db->q($plugin))
                            ->where($db->qn('folder') . ' = ' . $db->q($folder));
                        $db->setQuery($query);

                        try {
                            $count = $db->loadResult();
                        } catch (Exception $exc) {
                            $count = 0;
                        }

                        $installer = new JInstaller;
                        $result = $installer->install($path);

                        // Fix the display names for new inclusion of type style
                        if (in_array($plugin, $this->oldPluginNames)) {
                            $status->plugins[] = array('name' => 'plg_' . $plugin, 'group' => $folder, 'result' => $result);
                        } else {
                            $status->plugins[] = array('name' => 'plg_' . $folder . '_' . $plugin, 'group' => $folder, 'result' => $result);
                        }

                        if ($published && !$count) {
                            $query = $db->getQuery(true)
                                ->update($db->qn('#__extensions'))
                                ->set($db->qn('enabled') . ' = ' . $db->q('1'))
                                ->where($db->qn('element') . ' = ' . $db->q($plugin))
                                ->where($db->qn('folder') . ' = ' . $db->q($folder));
                            $db->setQuery($query);

                            try {
                                $db->execute();
                            } catch (Exception $exc) {
                                // Nothing
                            }
                        }
                    }
                }
            }
        }

        return $status;
    }

    /**
     * Uninstalls subextensions (modules, plugins) bundled with the main extension
     *
     * @param JInstaller $parent
     * @return JObject The subextension uninstallation status
     */
    private function uninstallSubextensions($parent)
    {
        jimport('joomla.installer.installer');

        $db = JFactory::getDBO();

        $status = new JObject();
        $status->modules = array();
        $status->plugins = array();

        // Modules uninstallation
        if (count($this->uninstallation_queue['modules'])) {
            foreach ($this->uninstallation_queue['modules'] as $folder => $modules) {
                if (count($modules)) {
                    foreach ($modules as $module) {
                        if (empty($folder)) {
                            $folder = 'site';
                        }
                        // Find the module ID
                        $query = $db->getQuery(true);
                        $query
                            ->select($db->qn('extension_id'))
                            ->from($db->qn('#__extensions'))
                            ->where($db->qn('element') . ' = ' . $db->q('mod_' . $module))
                            ->where($db->qn('type') . ' = ' . $db->q('module'));

                        $db->setQuery($query);
                        $id = $db->loadResult();

                        // Uninstall the module
                        if ($id) {
                            $installer = new JInstaller;
                            $result = $installer->uninstall('module', $id, 1);
                            $status->modules[] = array(
                                'name' => 'mod_' . $module,
                                'client' => $folder,
                                'result' => $result
                            );
                        }
                    }
                }
            }
        }

        // Plugins uninstallation
        if (count($this->uninstallation_queue['plugins'])) {
            foreach ($this->uninstallation_queue['plugins'] as $folder => $plugins) {
                if (count($plugins)) {
                    foreach ($plugins as $plugin) {
                        $query = $db->getQuery(true);
                        $query
                            ->select($db->qn('extension_id'))
                            ->from($db->qn('#__extensions'))
                            ->where($db->qn('type') . ' = ' . $db->q('plugin'))
                            ->where($db->qn('element') . ' = ' . $db->q($plugin))
                            ->where($db->qn('folder') . ' = ' . $db->q($folder));

                        $db->setQuery($query);
                        $id = $db->loadResult();

                        if ($id) {
                            $installer = new JInstaller;
                            $result = $installer->uninstall('plugin', $id, 1);

                            // Fix the display names for new inclusion of type style
                            if (in_array($plugin, $this->oldPluginNames)) {
                                $status->plugins[] = array(
                                    'name' => 'plg_' . $plugin,
                                    'group' => $folder,
                                    'result' => $result
                                );
                            } else {
                                $status->plugins[] = array(
                                    'name' => 'plg_' . $folder . '_' . $plugin,
                                    'group' => $folder,
                                    'result' => $result
                                );
                            }
                        }
                    }
                }
            }
        }

        return $status;
    }

    /**
     * Removes obsolete files and folders before install
     *
     * @param array $removeFiles
     */
    private function removeObsoleteFilesAndFolders($removeFiles)
    {
        // Remove files
        jimport('joomla.filesystem.file');
        if (!empty($removeFiles['files'])) {
            foreach ($removeFiles['files'] as $file) {
                $f = JPATH_ROOT . '/' . $file;
                if (!JFile::exists($f)) {
                    continue;
                }
                JFile::delete($f);
            }
        }
        // Remove folders
        jimport('joomla.filesystem.folder');
        if (!empty($removeFiles['folders'])) {
            foreach ($removeFiles['folders'] as $folder) {
                $f = JPATH_ROOT . '/' . $folder;
                if (!JFolder::exists($f)) {
                    continue;
                }
                JFolder::delete($f);
            }
        }
    }

    /**
     * Add new files and folders
     *
     * @param $parent
     */
    private function addNewFilesAndFolders($parent)
    {
        $src = $parent->getParent()->getPath('source');

        // Add files
        jimport('joomla.filesystem.file');
        if (!empty($this->addFiles['files'])) {
            foreach ($this->addFiles['files'] as $file) {
                if (JFile::exists(JPATH_ROOT . '/' . $file)) {
                    JFile::delete(JPATH_ROOT . '/' . $file);
                }
                if (JFile::exists($src . '/' . $file)) {
                    JFile::move($src . '/' . $file, JPATH_ROOT . '/' . $file);
                }
            }
        }
        // Add folders
        jimport('joomla.filesystem.folder');
        if (!empty($this->addFiles['folders'])) {
            foreach ($this->addFiles['folders'] as $folder) {
                $f = $src . '/' . $folder;
                if (JFolder::exists($f)) {
                    continue;
                }
                JFolder::create($f);
            }
        }
    }

    /**
     * move existing folders
     * @return null
     */
    private function moveFolders()
    {
        // Move folders
        jimport('joomla.filesystem.folder');
        if (count($this->moveFolders['folders'])) {
            foreach ($this->moveFolders['folders'] as $from => $to) {
                if (JFolder::exists(JPATH_ROOT . '/' . $to)) {
                    continue;
                }
                if (JFolder::exists(JPATH_ROOT . '/' . $from)) {
                    try {
                        JFolder::move(JPATH_ROOT . '/' . $from, JPATH_ROOT . '/' . $to);
                    } catch (Exception $e) {
                        // Nothing
                        continue;
                    }
                }
            }
        } else {
            return null;
        }
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

        $extType = 'component';

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

            $db->setQuery($query);

            $oResult = $db->loadObject();

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
     * Activate if main extension is a plugin on install
     *
     * @param JInstaller $parent
     */
    private function activatePlugin($parent)
    {
        $db = JFactory::getDbo();

        if (count($this->activatePlugins['plugins'])) {
            foreach ($this->activatePlugins['plugins'] as $folder => $plugins) {
                if (count($plugins)) {
                    foreach ($plugins as $plugin => $published) {

                        if ($published) {
                            $query = $db->getQuery(true)
                                ->update($db->qn('#__extensions'))
                                ->set($db->qn('enabled') . ' = ' . $db->q('1'))
                                ->where($db->qn('element') . ' = ' . $db->q($plugin))
                                ->where($db->qn('folder') . ' = ' . $db->q($folder));
                            $db->setQuery($query);

                            try {
                                $db->execute();
                            } catch (Exception $e) {
                                // Nothing
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Copy params from old extension to newly named extension
     *
     */
    private function copyParams()
    {
        $db = JFactory::getDbo();

        if (count($this->copyParams['extensions'])) {
            foreach ($this->copyParams['extensions'] as $extensions) {
                if (count($extensions)) {
                    foreach ($extensions as $old => $new) {
                        $query = $db->getQuery(true);
                        $query
                            ->select(array('params'))
                            ->from($db->quoteName('#__extensions'))
                            ->where($db->quoteName('element') . ' = ' . $db->q($old));

                        $db->setQuery($query);

                        $result = $db->loadRow();

                        if ($result) {

                            $query = $db->getQuery(true)
                                ->update($db->qn('#__extensions'))
                                ->set($db->qn('params') . ' = ' . $db->q($result[0]))
                                ->where($db->qn('element') . ' = ' . $db->q($new));

                            $db->setQuery($query);

                            try {
                                $db->execute();
                            } catch (Exception $exc) {
                                // Nothing
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Executes the cleaning process directly in the corresponding cache component
     *
     * @return bool
     */
    private function cleanCache()
    {
        // Get model from cache component to use its functions
        JLoader::import('cache', JPATH_ADMINISTRATOR . '/components/com_cache/models');
        $model = JModelLegacy::getInstance('cache', 'CacheModel');

        $clients = array(1, 0);

        foreach ($clients as $client) {
            $cache = $model->getCache($client);

            foreach ($cache->getAll() as $cache_item) {
                if ($model->getCache($client)->clean($cache_item->group) == false) {
                    return false;
                }
            }
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

        if ($manifest->version > $this->gears_version) {
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
