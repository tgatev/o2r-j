<?php
/*
 * @version 6.2.1
 * @package JotCache
 * @category Joomla 3.8
 * @copyright (C) 2010-2018 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
$site_url = JURI::root();
$scope = 'all';
if ($this->filter['search'] || $this->filter['com'] || $this->filter['view'] || $this->filter['mark']) {
$scope = 'sel';
}if ($this->filter['chck']) {
$scope = 'chck';
}?>
<script language="javascript" type="text/javascript">
  var jotcachereq = "<?php echo JRoute::_($site_url . 'index.php?option=com_jotcache&view=recache&task=ajax.status&format=raw') ?>";
  var jotcacheflag = 1;
  var jotcacheform = "adminForm";
  Joomla.submitbutton = function(task) {
    if (task === 'close') {
      self.close();
    } else {
      jotcacheform = "adminForm_" + document.id('myTabTabs').getElement('li.active a').get('text');
      if (task === 'recache.start') {
        jotcacheajax.again();
      }
      if (task === 'recache.stop') {
        jotcacheflag = 0;
        return;
      }
      Joomla.submitform(task, document.getElementById(jotcacheform));
    }
  };
</script>
<table class="statuslist"><tr><td class="status-title"><?php echo JText::_('JOTCACHE_RECACHE_STATUS'); ?></td><td >&nbsp;</td>
    <td ><span><img src="/administrator/components/com_jotcache/assets/images/loader.gif" id="spinner-here" style="display:none;
  margin-right:5px;"></img></span><span id="message-here"></span></td>
  </tr></table>
<hr/>
<?php
if (count($this->plugins) > 0) {
echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => $this->plugins[0]->element));
foreach ($this->plugins as $plugin) {
$pluginTitle = ucfirst($plugin->element);
echo JHtml::_('bootstrap.addTab', 'myTab', $plugin->element, $pluginTitle);
include JPATH_PLUGINS . '/jotcacheplugins/' . $plugin->element . '/' . $plugin->element . '_form.php';
echo JHtml::_('bootstrap.endTab');
}echo JHtml::_('bootstrap.endTabSet');
} else {
?> 
  <div style="color:red;"><?php echo JText::_('JOTCACHE_RECACHE_NO_PLUGINS'); ?></div>
<?php } ?>