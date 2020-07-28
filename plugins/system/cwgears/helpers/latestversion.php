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

defined('_JEXEC') or die('Restricted access');

/**
 * Class CwGearsLatestversion
 */
class CwGearsLatestversion
{

    /**
     * Get latest version of an extension and compare to currently installed
     *
     * @param string $ext
     * @param datetime $installed
     * @return array
     */
    public static function getCurrent($ext, $installed) {

        $xmlfile = 'http://cdn.coalaweb.com/updates/' . $ext . '.xml';
        $xmlcheck = @simplexml_load_file($xmlfile);
        if (!$xmlcheck) {
            $remote = JText::_('PLG_CWGEARS_NO_FILE');
            $exist = false;
        } else {
            $xml = new SimpleXMLElement(file_get_contents($xmlfile));
            $remote = (string) $xml->update[0]->version;
            $exist = true;
        }
        
        if ($exist){
        $update = $remote > $installed ? '<a class="btn btn-mini btn-success" href="https://coalaweb.com/downloads/joomla-extensions/latest-releases" target="_blank"><span class="icon-upload"></span>' . JText::_('PLG_CWGEARS_UPDATE_BTN') . '</a>' : '';
        } else {
            $update = '';
        }
        
        $info = [
            'remote' => $remote,
            'update' => $update
        ]; 

        return $info;
    }

}
