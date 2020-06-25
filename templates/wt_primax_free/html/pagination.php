<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

function pagination_list_render($list) {
	// Initialize variables
	$html = '<ul class="pagination">';

	if ($list['start']['active']==1)   $html .= $list['start']['data'];
	if ($list['previous']['active']==1) $html .= $list['previous']['data'];

	foreach ($list['pages'] as $page) {
		$html .= $page['data'];
	}

	if ($list['next']['active']==1) $html .= $list['next']['data'];
	if ($list['end']['active']==1)  $html .= $list['end']['data'];

	$html .= '</ul>';

	return $html;
}

function pagination_item_active(&$item) {

	$cls = '';

    if ($item->text == JText::_('Next')) { $item->text = '&rsaquo;'; $cls = "next";}
    if ($item->text == JText::_('Prev')) { $item->text = '&lsaquo;'; $cls = "previous";}
	
	if ($item->text == "Start" ) {   $item->text = '&laquo;'; $cls = "first";}
    if ($item->text == "End" ) { $item->text = '&raquo;';  $cls = "last";}

    return '<li><a class="' . $cls . '" href="' . $item->link . '" title="' . $cls . '">' . $item->text . '</a></li>';
}

function pagination_item_inactive( &$item ) {
	$cls = (int)$item->text > 0 ? 'active': 'disabled';
	return '<li class="' . $cls . '"><a>' . $item->text . '</a></li>';
}
