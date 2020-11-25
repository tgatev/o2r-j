<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidation');


// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_uu/assets/css/uu.css');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task)
	{
        if (task == 'field.cancel' || document.formvalidator.isValid(document.id('grfield-form'))) {
            Joomla.submitform(task, document.getElementById('grfield-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
    }
</script>

<?php if (UU_J30) { ?>
<form action="<?php echo JRoute::_('index.php?option=com_uu&view=field&layout=group&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="grfield-form" class="form-validate">
	<div class="span6">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('COM_UU_LEGEND_GROUP_FIELD'); ?></legend>

            <!-- use to fix a display problem -->
            <div class="clr"></div>

            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('name'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->form->getInput('name'); ?>
                </div>
            </div>

            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('description'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->form->getInput('description'); ?>
                </div>
            </div>

            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('published'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->form->getInput('published'); ?>
                </div>
            </div>

		</fieldset>
	</div>

<?php

?>
    <div class="span6">
        <fieldset class="form-horizontal">
            <legend><?php echo JText::_('COM_UU_LEGEND_FIELD_PARAMS'); ?></legend>
            <div id="paramsholder" >
                <?php
                foreach ($this->form->getFieldset('options') as $field) : ?>
                    <div class="control-group">
                        <div class="control-label">
                           <?php echo $field->label; ?>
                        </div>
                        <div class="controls">
                            <?php echo $field->input; ?>
                         </div>
                    </div>
                <?php endforeach ?>
            </div>
        </fieldset>
    </div>


    <input type="hidden" name="jform[type]" value="group" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php } else { ?>
    <form action="<?php echo JRoute::_('index.php?option=com_uu&view=field&layout=group&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="grfield-form" class="form-validate">
        <div class="width-60 fltlft">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_UU_LEGEND_GROUP_FIELD'); ?></legend>
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('name'); ?>
                        <?php echo $this->form->getInput('name'); ?></li>
                    <li><?php echo $this->form->getLabel('description'); ?>
                        <?php echo $this->form->getInput('description'); ?></li>
                    <li><?php echo $this->form->getLabel('published'); ?>
                        <?php echo $this->form->getInput('published'); ?></li>
                </ul>
            </fieldset>
        </div>

        <?php

        ?>
        <div class="width-40 fltlft">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_UU_LEGEND_FIELD_PARAMS'); ?></legend>
                <div id="paramsholder" >
                    <?php
                    foreach ($this->form->getFieldset('options') as $field) : ?>
                        <li><?php echo $field->label; ?>
                            <?php echo $field->input; ?></li>
                    <?php endforeach ?>
                </div>
            </fieldset>
        </div>


        <input type="hidden" name="jform[type]" value="group" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
        <div class="clr"></div>

        <style type="text/css">
                /* Temporary fix for drifting editor fields */
            .adminformlist li {
                clear: both;
            }
        </style>
    </form>
<?php } ?>