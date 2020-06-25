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
 * Class JFormFieldSorting
 */
class JFormFieldSorting extends JFormField {

    protected $type = 'Sorting';

    /**
     * @return string
     */
    protected function getInput() {
        $document = JFactory::getDocument();

        //Include base jQuery
        JHtml::_('jquery.framework');

        $baseurl = JURI::base(true);
        $baseurl = str_replace('administrator', '', $baseurl);
        $scriptname = $this->element['scriptpath'] ? (string) $this->element['scriptpath'] : $baseurl . 'media/coalaweb/modules/generic/js/jquery-sortable-min.js';

        //try to find the script
        if ($scriptname == 'self') {
            $filedir = str_replace(JPATH_SITE . '/', '', dirname(__FILE__));
            $filedir = str_replace('\\', '/', $filedir);
            $scriptname = $baseurl . $filedir . '/jquery-sortable-min.js';
        }

        $document->addScript($scriptname);

        // Now initialize the plugin
        $document->addScriptDeclaration('
            jQuery(document).ready(function($) {
                var group = $("#sortable").sortable({
                    pullPlaceholder: false,
                    onDrop: function(item, container, _super) {
                        $("#' . $this->id . '").val(group.sortable("serialize").get().join("\n"))
                        _super(item, container)
                    },
                    serialize: function(parent, children, isContainer) {
                        return isContainer ? children.join() : parent.attr("id")
                    },
                })
            });
        ');

        // Get possible new options.
        $possibleNew = '';
        if ($this->name === 'jform[params][sorting_tabs]') {
            $default = 'facebook_s,twitter_s,linkedin_s,pinterest_s,reddit_s,email_s,whatsapp_s,facebook_f,twitter_f,linkedin_f,pinterest_f,rss_f,instagram_f,contact_f,customone_c,customtwo_c,customthree_c,customfour_c,customfive_c';
            $possibleNew = explode(',', $default);
        }

        if ($this->name === 'jform[params][sorting_pro]') {
            $default = 'facebook_s,twitter_s,linkedin_s,pinterest_s,reddit_s,email_s,whatsapp_s,facebook_f,twitter_f,linkedin_f,pinterest_f,contact_f,github_f,instagram_f';
            $possibleNew = explode(',', $default);
        }
        // Explode currently stored values.
        $currentItems = explode(',', $this->value);

        //Are there any new options?
        $newItems = array_diff($possibleNew, $currentItems);

        //Append new options to list.
        $items = array_merge((array) $currentItems, (array) $newItems);
        
        //Create a string from our new appended array
        $cvs = join(',', $items);

        $input = '<ul id="sortable">';

        foreach ($items as $item) {
            $parts = explode('_', $item);
            $name = $parts[0];
            $type = $parts[1];
            $input .= '<li class="sort" id="' . $item . '"><span></span>' . ucfirst($name) . ' [' . strtoupper($type) . ']</li>';
        }

        $input .= '</ul>
		<input type="hidden" name="' . $this->name . '" value="' . $cvs . '" id="' . $this->id . '" />';
        return $input;
    }

}
