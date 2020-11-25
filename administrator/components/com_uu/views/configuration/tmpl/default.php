    <?php
    /**
     * @package     UltimateUser for Joomla!
     * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
     * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
     * @copyright   Copyright (C) 2012-2015. All rights reserved.
     */

// no direct access
defined('_JEXEC') or die;

// Load tooltips behavior
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.switcher');

// Load submenu template, using element id 'submenu' as needed by behavior.switcher
//$this->document->setBuffer($this->loadTemplate('navigation'), 'modules', 'submenu');
$options = array('userCookie'=>true);
?>

<style type="text/css">
    div.current fieldset.confmail {border: none;}
    fieldset.adminform textarea.inputbox_owner {width:auto;}
        .form-horizontal .control-label {width:180px;}
        .form-horizontal .controls {float:left;margin-left:0}
        .form-horizontal .controls-textbox{width:auto}
        div.faux-label{float:left;margin-left:15px;width: 300px}
        span.faux-label {margin-left:20px}
    /*joomla 3.0*/
</style>
<form action="<?php echo JRoute::_('index.php?option=com_uu'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row-fluid">
        <!-- Begin Content -->
        <div class="span12">
            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_UU_CONFIGURATION_GENERAL', true)); ?>
            <?php echo $this->loadTemplate('general'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'mail', JText::_('COM_UU_CONFIGURATION_MAIL', true)); ?>
            <?php echo $this->loadTemplate('mail'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'redirection', JText::_('COM_UU_CONFIGURATION_REDIRECTION', true)); ?>
            <?php echo $this->loadTemplate('redirection'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.endTabSet'); ?>

            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>

        </div>
        <!-- End Content -->
    </div>
</form>
