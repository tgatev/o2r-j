<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die;

//JHtml::_('behavior.tooltip');//load mootools
JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');//joomla 3.4 and previous JHtml::_('behavior.formvalidation')
JHtml::_('behavior.keepalive');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_uu/assets/css/uu.css');

$isNew	= ($this->item->id == 0);

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'field.cancel' || document.formvalidator.isValid(document.getElementById('field-form'))) {
			Joomla.submitform(task, document.getElementById('field-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

    function changeType(t) {
        var selected_value = t.options[t.selectedIndex].value;
        jQuery.get('<?php echo JRoute::_('index.php', false);?>', {
            "option": "com_uu",
            "task": "field.getFieldParams",
            "format": "raw",
            "id": <?php echo (int)$this->item->id; ?>,
            "type": selected_value
        }, function(data) {
            jQuery('#paramsholder').html(data);
        });

        //TODO change this to hasOptions
        if (selected_value == 'select' || selected_value == 'checkbox' || selected_value == 'radio') {
            jQuery('#optionsholder').css("display", "block");
        } else {
            jQuery('#optionsholder').css("display", "none");
        }

        //if type captcha hide some field
        if (selected_value == 'captcha') {
            jQuery('#jform_required-lbl').hide();
            jQuery('#jform_required').hide();
            jQuery('#jform_registration-lbl').hide();
            jQuery('#jform_registration').hide();
            jQuery('#jform_editable-lbl').hide();
            jQuery('#jform_editable').hide();
        } else {
            jQuery('#jform_required-lbl').show();
            jQuery('#jform_required').show();
            jQuery('#jform_registration-lbl').show();
            jQuery('#jform_registration').show();
            jQuery('#jform_editable-lbl').show();
            jQuery('#jform_editable').show();
        }

    }

    function addValue() {
        var myList=window.document.getElementById("tablevalues");
        var newline = document.createElement('tr');
        var column = document.createElement('td');
        var column2 = document.createElement('td');
        var column3 = document.createElement('td');
        var column4 = document.createElement('td');
        var input = document.createElement('input');
        var input2 = document.createElement('input');
        var input3 = document.createElement('select');
        var input4 = document.createElement('input');
        var option1 = document.createElement('option');
        var option2 = document.createElement('option');
        input.type = 'text';
        input2.type = 'text';
        input4.type = 'text';
        option1.value= '1';
        option2.value= '0';
        input.name = 'field_values[value][]';
        input2.name = 'field_values[title][]';
        input3.name = 'field_values[published][]';
        input4.name = 'field_values[ordering][]';
        input3.setAttribute('default','1');
        option1.text= '<?php echo JText::_('YES',true)?>';
        option2.text= '<?php echo JText::_('NO',true)?>';
        try { input3.add(option1, null); } catch(ex) { input3.add(option1); }
        try { input3.add(option2, null); } catch(ex) { input3.add(option2); }
        column.appendChild(input);
        column2.appendChild(input2);
        column3.appendChild(input3);
        column4.appendChild(input4);
        newline.appendChild(column);
        newline.appendChild(column2);
        newline.appendChild(column3);
        newline.appendChild(column4);
        myList.appendChild(newline);
    } //end addOption


    jQuery(document).ready(function() {

        document.formvalidator.setHandler('fieldcode',
            function (value) {
                regex=/^[a-zA-Z0-9]+$/;
                return regex.test(value);
            });

        var selected_value = jQuery("#jform_type").val();
        //TODO change this to hadOptions
        if(selected_value=='select' || selected_value=='checkbox' || selected_value=='radio' ){
            jQuery('#optionsholder').css("display", "block");
        }else{
            jQuery('#optionsholder').css("display", "none");
        }
    })

//    window.addEvent('domready', function() {
//        document.formvalidator.setHandler('fieldcode',
//                function (value) {
//                    regex=/^[a-zA-Z0-9]+$/;
//                    return regex.test(value);
//                });
//    });


</script>

    <form action="<?php echo JRoute::_('index.php?option=com_uu&view=field&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="field-form" class="form-validate">
        <div class="span6">
            <fieldset class="form-horizontal">
            <legend><?php echo JText::_('COM_UU_LEGEND_FIELD'); ?></legend>

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
                        <?php echo $this->form->getLabel('fieldcode'); ?>
                    </div>
                    <div class="controls">
                        <?php if($isNew) {
                            echo $this->form->getInput('fieldcode');
                        } else {
                            ?> <input type="text" name="jform[fieldcode]" id="jform_fieldcode"
                                      value="<?php echo $this->item->fieldcode;?>" class="inputbox readonly"
                                      size="40" readonly="readonly" > <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('type'); ?>
                    </div>
                    <div class="controls">
                        <?php if($isNew) {
                            echo $this->form->getInput('type');
                        } else {
                            ?> <input type="text" name="jform[type]" id="jform_type"
                                      value="<?php echo $this->item->type;?>" class="inputbox readonly"
                                      size="40" readonly="readonly" > <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('groups'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('groups'); ?>
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

                <?php  if ($this->item->core < 1 && $this->item->type != 'captcha') : ?>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('published'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('published'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('required'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('required'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('registration'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('registration'); ?>
                        </div>
                    </div>
                <?php endif ?>

                <?php if ($this->item->fieldcode != "username" &&  $this->item->fieldcode != "email2" &&  $this->item->type != "captcha"   ) :?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('editable'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('editable'); ?>
                            </div>
                        </div>
                <?php endif ?>

                <?php  if ($this->item->type  == 'captcha') : ?>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('published'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('published'); ?>
                        </div>
                    </div>
                <?php endif ?>


        </fieldset>

            <div id="optionsholder"  style="display: none;">
                <fieldset class="form-horizontal" >
                    <legend><?php echo JText::_('COM_UU_LEGEND_FIELD_VALUES'); ?></legend>
                    <table id="field_values_table" class="table table-striped table-hover">
                        <tbody  id="tablevalues">
                        <tr>
                            <td><?php echo JText::_('COM_UU_LEGEND_FIELD_VALUE_TITLE_VALUE')?></td>
                            <td><?php echo JText::_('COM_UU_LEGEND_FIELD_VALUE_TITLE_TITLE'); ?></td>
                            <td><?php echo JText::_('COM_UU_LEGEND_FIELD_VALUE_TITLE_PUBLISHED'); ?></td>
                            <td><?php echo JText::_('COM_UU_LEGEND_FIELD_VALUE_TITLE_ORDER'); ?></td>
                        </tr>
                        <?php if(!empty($this->values) AND is_array($this->values)) {
                            foreach($this->values as $field_value){
                                $no_selected = 'selected="selected"';
                                $yes_selected = '';
                                if((int)$field_value->published){
                                    $no_selected = '';
                                    $yes_selected = 'selected="selected"';
                                }
                                ?>
                                <tr>
                                    <td><input type="text" name="field_values[value][]" value="<?php echo $field_value->value; ?>" /></td>
                                    <td><input type="text" name="field_values[title][]" value="<?php echo $field_value->title; ?>" /></td>
                                    <td>
                                        <select name="field_values[published][]" class="inputbox" default="1">
                                            <option <?php echo $no_selected; ?> value="0"><?php echo JText::_('NO'); ?></option>
                                            <option <?php echo $yes_selected; ?> value="1"><?php echo JText::_('YES'); ?></option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="field_values[ordering][]" value="<?php echo $field_value->ordering; ?>" /></td>
                                </tr>
                            <?php
                            }
                        } else { ?>
                            <tr>
                                <td><input type="text" name="field_values[value][]" value="" /></td>
                                <td><input type="text" name="field_values[title][]" value="" /></td>
                                <td>
                                    <select name="field_values[published][]" class="inputbox">
                                        <option selected="selected" value="1"><?php echo JText::_('YES'); ?></option>
                                        <option  value="0"><?php echo JText::_('NO'); ?></option>
                                    </select>
                                </td>
                                <td><input type="text" name="field_values[ordering][]" value="" /></td>
                            </tr>

                        <?php }
                        ?>
                        </tbody>
                    </table>
                    <a onclick="addValue();return false;" href="#" alt="<?php echo JText::_('COM_UU_LEGEND_FIELD_VALUE_NEW_VALUE'); ?>"><?php echo JText::_('COM_UU_LEGEND_FIELD_VALUE_NEW_VALUE'); ?></a>
            </div>
        </div>

      <div class="span6">
        <fieldset class="form-horizontal">
            <legend><?php echo JText::_('COM_UU_LEGEND_FIELD_PARAMS'); ?></legend>
            <div id="paramsholder" >
                <?php if (isset($this->htmlparams) ) {echo $this->htmlparams;}?>
            </div>
        </fieldset>
      </div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

    </form>
