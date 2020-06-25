<?php
/*
 * @version 6.2.1
 * @package JotCache
 * @category Joomla 3.8
 * @copyright (C) 2010-2018 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JToolBarHelper::title(JText::_('JOTCACHE_DEBUG_TITLE'), 'jotcache-logo.gif');
$site_url = JURI::root();
$bar = JToolBar::getInstance('toolbar');
$msg = JText::_('JOTCACHE_RS_REFRESH_DESC');
JToolBarHelper::cancel('display', JText::_('CLOSE'));
JToolBarHelper::spacer();
JToolbarHelper::help('Help', false, $this->url . 'check');
$msg = ($this->data->error) ? '<span style="color:red;"> ' . JText::_('ERROR') . ' </span>' : ' ' . JText::_('JOTCACHE_DEBUG_COUNT') . '[' . $this->data->count . '] ';
$config = JFactory::getConfig();
$cache_dir = $config->get('cache_path', JPATH_ROOT . '/cache');
$data_path = $cache_dir.'/page/'.$this->fnameExt;
$token = JSession::getFormToken();
$data_title = (file_exists($data_path))?'<a href="'.JRoute::_("index.php?option=com_jotcache&view=reset&task=getcachedfile&$token=1&fname=".$this->fnameExt).'">'.$this->data->title.'</a>':$this->data->title;
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
  <h3><?php echo sprintf(JText::_('JOTCACHE_DEBUG_INFO'), $data_title, $msg); ?> : </h3>
  <textarea style="width:100%;" rows="25">
    <?php echo $this->data->content; ?>
  </textarea>
  <?php
  if (isset($this->data->parts)) {
foreach ($this->data->parts as $key => $value) {
echo "<p>$key</p>";
?>
      <textarea style="width:100%;" rows="25">
      <?php echo $value; ?>
      </textarea>
    <?php }
} ?>

<?php ?>
  <input type="hidden" name="option" value="com_jotcache" />
  <input type="hidden" name="view" value="main" />
  <input type="hidden" name="task" value="debug" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="hidemainmenu" value="0" />
</form>
