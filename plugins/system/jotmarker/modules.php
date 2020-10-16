<?php
/*
 * @package     Joomla.Platform
 * @subpackage  Document
 * Modified JDocument Modules renderer which triggers onAfterRenderModules event
 * used for exclusion of JotCache template positions
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('JPATH_PLATFORM') or die;
class JDocumentRendererHtmlModules extends JDocumentRenderer {
public function render($position, $params = array(), $content = null) {
$renderer = $this->_doc->loadRenderer('module');
$buffer = '';
$app = JFactory::getApplication();
$user = JFactory::getUser();
$frontediting = ($app->isClient('site') && $app->get('frontediting', 1) && !$user->guest);
$menusEditing = ($app->get('frontediting', 1) == 2) && $user->authorise('core.edit', 'com_menus');
foreach (JModuleHelper::getModules($position) as $mod) {
$moduleHtml = $renderer->render($mod, $params, $content);
if ($frontediting && trim($moduleHtml) != '' && $user->authorise('module.edit.frontend', 'com_modules.module.' . $mod->id)) {
$displayData = array('moduleHtml' => &$moduleHtml, 'module' => $mod, 'position' => $position, 'menusediting' => $menusEditing);
JLayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
}$buffer .= $moduleHtml;
}JEventDispatcher::getInstance()->trigger('onAfterRenderModules', array(&$buffer, &$params));
return $buffer;
}}class JDocumentRendererModules extends JDocumentRenderer {
public function render($position, $params = array(), $content = null){
$renderer = $this->_doc->loadRenderer('module');
$buffer = '';
$app = JFactory::getApplication();
$user = JFactory::getUser();
$frontediting = ($app->isClient('site') && $app->get('frontediting', 1) && !$user->guest);
$menusEditing = ($app->get('frontediting', 1) == 2) && $user->authorise('core.edit', 'com_menus');
foreach (JModuleHelper::getModules($position) as $mod){
$moduleHtml = $renderer->render($mod, $params, $content);
if ($frontediting && trim($moduleHtml) != '' && $user->authorise('module.edit.frontend', 'com_modules.module.' . $mod->id)){
$displayData = array('moduleHtml' => &$moduleHtml, 'module' => $mod, 'position' => $position, 'menusediting' => $menusEditing);
JLayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
}$buffer .= $moduleHtml;
}JEventDispatcher::getInstance()->trigger('onAfterRenderModules', array(&$buffer, &$params));
return $buffer;
}}