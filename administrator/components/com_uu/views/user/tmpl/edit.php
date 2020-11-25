<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_uu/assets/css/uu.css');

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
$required = false;
$newgroup = false;

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_uu&layout=edit&user_id='.(int) $this->item->user_id); ?>" method="post" name="adminForm" id="user-form" class="form-validate form-horizontal" enctype="multipart/form-data">

            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_USERS_USER_ACCOUNT_DETAILS', true)); ?>

                        <div class="span6">
                            <fieldset class="form-horizontal">
                                <legend><?php echo JText::_('COM_UU_LEGEND_JOOMLA_USER'); ?></legend>
                                    <?php foreach($this->form->getFieldset('user_details') as $field) :?>
                                        <div class="control-group">
                                            <div class="control-label">
                                                <?php echo $field->label; ?>
                                            </div>
                                            <div class="controls">
                                                <?php echo $field->input; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                            </fieldset>
                        </div>


                        <div class="span6">
                <fieldset class="form-horizontal">
                    <legend><?php echo JText::_('COM_UU_LEGEND_UU_USER'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('ip_address'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('ip_address'); ?>
                        </div>
                    </div>

                        <?php
                        $firstgroup = false;
                        foreach ($this->customFields as $field):?>
                            <?php if ($field->type == 'group') {
                                if (!$firstgroup = true){echo '</div>';}
                                $newgroup = true;
                                ?><div class="ctitle">
                                <h2><?php echo JText::_( $field->name ); ?></h2>
                                </div>

                            <?php } else {
                                if( !$required && $field->required == 1 )
                                    $required	= true;

                                if ($newgroup){echo '<div class="cFormList cFormHorizontal cResetList">';$newgroup=false;}
                                ?>
                                <div class="control-group">
                                    <div class="control-label">
                                        <label id="lblfield<?php echo $field->id;?>" for="field<?php echo $field->id;?>" class="form-label"><?php if($field->required == 1) echo '*'; ?><?php echo JText::_($field->name); ?></label>
                                    </div>
                                    <div class="controls">
                                        <?php echo $field->html; ?>
                                    </div>
                                 </div>
                            <?php
                            }?>

                        <?php endforeach;?>

                    </ul>
                </fieldset>
            </div>

                 <?php echo JHtml::_('bootstrap.endTab'); ?>


            <?php if ($this->grouplist) : ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'groups', JText::_('COM_USERS_ASSIGNED_GROUPS', true)); ?>
                <?php echo $this->loadTemplate('groups'); ?>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php endif; ?>



                        <?php
                        foreach ($fieldsets as $fieldset) :
                            if ($fieldset->name == 'user_details') :
                                continue;
                            endif;
                            ?>
                            <?php echo JHtml::_('bootstrap.addTab', 'myTab', $fieldset->name, JText::_($fieldset->label, true)); ?>
                                <legend><?php echo JText::_('COM_UU_LEGEND_JOOMLA_USER_PARAMS'); ?></legend>

                                <?php foreach ($this->form->getFieldset($fieldset->name) as $field) : ?>
                                    <?php if ($field->hidden) : ?>
                                        <div class="control-group">
                                            <div class="controls">
                                                <?php echo $field->input; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="control-group">
                                            <div class="control-label">
                                                <?php echo $field->label; ?>
                                            </div>
                                            <div class="controls">
                                                <?php echo $field->input; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php echo JHtml::_('bootstrap.endTab'); ?>
                        <?php endforeach; ?>


            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
            </fieldset>

	<input type="hidden" name="task" value="" />
    <?php echo $this->form->getInput('user_id');?>
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>

    <script type="text/javascript">
        jQuery.validate({
            modules: 'security' //add module security to enable server check
        });

    </script>

</form>