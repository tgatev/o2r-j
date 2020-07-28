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

jimport('joomla.form.formfield');
jimport('joomla.form.helper');

JFormHelper::loadFieldClass('text');

// Example: <field name="dlid" type="downloadid" label="" description="" default="" extension="See Below" key="key="/>
// Extension = Must be the same as the name attribute of update <server> as entered in the extension xml file

class JFormFieldDownloadID extends JFormFieldText {

    //The field class must know its own type through the variable $type.
    protected $type = 'DownloadID';

    public function getInput() {

        if ($this->value) {
            $extra_query = "'{$this->element['key']}{$this->value}'";

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->update('#__update_sites')
                ->set('extra_query = ' . $extra_query)
                ->where('name = "'.$this->element['extension'].'"');
            $db->setQuery($query);
            $db->execute();
        }
        // code that returns HTML that will be shown as the form field
        return parent::getInput();
    }

}
