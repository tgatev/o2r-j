<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Gears
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Gears is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die;

use CoalaWeb\Xml as CW_Xml;
use CoalaWeb\UpdateKey as CW_UpdateKey;

require_once(JPATH_SITE . '/plugins/system/cwgears/fields/base.php');

// Required autoloader for the upcoming namespaces.
if (!is_file(JPATH_PLUGINS . '/system/cwgears/libraries/CoalaWeb/vendor/autoload.php')) {
    return;
}
require_once JPATH_PLUGINS . '/system/cwgears/libraries/CoalaWeb/vendor/autoload.php';

/**
 * Class CWElementVersion
 */
class CWElementVersionCheck extends CWElement
{

    /**
     * @param $name
     * @param $value
     * @param $node
     * @param $control_name
     * @return null
     */
    public function fetchElement($name, $value, &$node, $control_name)
    {
        return NULL;
    }

    /**
     * @param $label
     * @param $description
     * @param $node
     * @param $control_name
     * @param $name
     * @return string
     */
    public function fetchTooltip($label, $description, &$node, $control_name, $name)
    {
        // Load version.php
        jimport('joomla.filesystem.file');
        $arr = explode("_", $label);

        //initiate variables
        $version_php = $current = $version = $ispro = $date = '';

        if (array_key_exists(0, $arr)) {
            switch ($arr[0]) {
                case "com":
                    $version_php = JPATH_ADMINISTRATOR . '/' . 'components/' . $label . '/' . $arr[1] . '.xml';
                    $manifest = (new CW_UpdateKey)->getExtensionData($label);
                    break;
                case "mod":
                    $version_php = JPATH_SITE . '/' . 'modules/' . $label . '/' . $label . '.xml';
                    $manifest = (new CW_UpdateKey)->getExtensionData($label);
                    break;
                case "plg":
                    if (array_key_exists(1, $arr)) {
                        $version_php = JPATH_SITE . '/' . 'plugins/' . $arr[1] . '/' . $arr[2] . '/' . $arr[2] . '.xml';
                        $manifest = (new CW_UpdateKey)->getExtensionData($arr[2]);
                    }
                    break;
            }

        }

        if (JFile::exists($version_php)) {
            $extXml = (new CW_Xml)->toObject($version_php);
            $version = $extXml->version;
            $date = $extXml->creationDate;
            $ispro = $extXml->level;
        }else{
            $version = JText::_('PLG_CWGEARS_RELEASE_UNKOWN');
        }

        // Load extension data such as the manifest cache
        if($manifest) {
            $current = $manifest->new_version;
        }else{
            $current = null;
        }

        //No current use default
        if ($current) {
            $latest = [
                'remote' => $current,
                'update' => 'index.php?option=com_installer&view=update',
                'type' => 'warning'
            ];
        } else {
            $latest = [
                'remote' => $version,
                'update' => '#',
                'type' => 'success'
            ];
        }

        return '<div class="cw-message-block well">'
            . '<h3>' . JText::_('PLG_CWGEARS_RELEASE_TITLE') . '</h3>'
            . '<ul class="cw_module">'
            . '<li><strong>' . JText::_('PLG_CWGEARS_FIELD_RELEASE_TYPE_LABEL') . '</strong> <span class="badge badge-info">' . $ispro . '</span></li>'
            . '<li><strong>' . JText::_('PLG_CWGEARS_FIELD_RELEASE_VERSION_LABEL') . ' </strong><span class="badge badge-info">' . $version . '</span></li>'
            . '<li><strong>' . JText::_('PLG_CWGEARS_FIELD_RELEASE_DATE_LABEL') . ' </strong><span class="badge badge-info">' . $date . '</span></li>'
            . '</ul>'
            . '<h3>' . JText::_('PLG_CWGEARS_LATEST_RELEASE_TITLE') . '</h3>'
            . '<ul class="cw_module">'
            . '<li><strong>' . JText::_('PLG_CWGEARS_FIELD_RELEASE_VERSION_LABEL') . ' </strong><a href="' . $latest['update'] . '"><span class="badge badge-' . $latest['type'] . '">' . $latest['remote'] . '</span></a></li>'
            . '</ul>'
            . '</div>';
    }

}

/**
 * Class JFormFieldVersionCheck
 */
class JFormFieldVersionCheck extends CWElementVersionCheck
{

    var $type = 'versioncheck';

}
