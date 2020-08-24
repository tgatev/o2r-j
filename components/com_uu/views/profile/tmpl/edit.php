<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');


JLoader::register('JHtmlUsers', JPATH_COMPONENT . '/helpers/html/users.php');
$fieldsets = $this->form->getFieldsets();

$conf = new UuConfig();
$required = false;
$newgroup = false;

?>
<div id="uu-wrap" class="profile<?php echo $this->pageclass_sfx?>" xmlns="http://www.w3.org/1999/html">
<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    </div>
<?php endif; ?>

<form id="uuForm" name="uuForm" action="<?php echo JRoute::_('index.php?option=com_uu&task=profile.save'); ?>" method="post" class="uu-form-validate form-horizontal" enctype="multipart/form-data" role="form">

    <?php
    foreach ($this->extraFields as $key => $field):?>
    <?php
        //first element
        if ($key == 0) {
	        echo '<fieldset>';
        }
        if ($field->type == 'group'){
            ?>
            <div class="ctitle">
                <h2><?php echo JText::_( $field->name ); ?></h2>
            </div>
            <?php
        }
        //not first element and it's a group
        if ($key > 0 &&  $field->type == 'group') { ?>
            </fieldset>
            <fieldset>
        <?php
        }

        if( !$required && $field->required > 0 ) {$required	= true;}

        //don't display email2
        if ($field->fieldcode == 'email2') {continue;}

        //don't display username if allow mail as username
        if ($conf->get('email_as_username') && $field->fieldcode == 'username') {continue;}

        if ($field->type != 'group') { ?>
                <div class="control-group">
                    <div class="control-label" id="lblfield<?php echo $field->id;?>" for="jform_<?php echo $field->fieldcode;?>">
		                <?php if($field->required > 0) echo '*'; ?><?php echo JText::_($field->name); ?>
                    </div>
                    <div class="controls">
		                <?php echo $field->html; ?>
                    </div>
                </div>
        <?php } ?>

        <?php endforeach;?>

        </fieldset>
        <fieldset>
            <?php

            if( $required )
            {
                ?>
                <div class="control-group">
                    <div class="controls">
                        <span class="form-helper"><?php echo JText::_( 'COM_UU_REGISTRATION_REQUIRED_FILEDS' ); ?></span>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="control-group">
                <div class="controls">
                    <div id="cwin-wait" style="display:none;"></div>
                    <div id="cwin-btn">
                        <input class="cButton cButton-Blue validateSubmit" type="submit" id="btnSubmit" value="<?php echo JText::_('JSUBMIT'); ?>" name="submit">
                    </div>
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="option" value="com_uu" />
        <input type="hidden" name="task" value="profile.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>


<script type="text/javascript">

    jQuery.validate({
        form : '#uuForm',//validate only this form
        lang : '<?php echo $this->validator_lang; ?>',
        modules: 'security',
            onModulesLoaded : function() {
            var optionalConfig = {
                fontSize: '12pt',
                bad : '<?php echo addslashes(JText::_('COM_UU_PASSWORD_STRENGHT_L1')); ?>',
                weak : '<?php echo addslashes(JText::_('COM_UU_PASSWORD_STRENGHT_L2')); ?>',
                good : '<?php echo addslashes(JText::_('COM_UU_PASSWORD_STRENGHT_L3')); ?>',
                strong : '<?php echo addslashes(JText::_('COM_UU_PASSWORD_STRENGHT_L4')); ?>'
            };
            jQuery('input[id="jform_password1"]').displayPasswordStrength(optionalConfig);
        },
        onError : function($form) {
            jQuery('#cwin-wait').hide();
            jQuery('#cwin-btn').show();
        },
        onSuccess : function($form) {
            jQuery('#cwin-btn').hide();
            jQuery('#cwin-wait').show();
            }
    });

    // Validation event listeners

    jQuery( document ).ready( function(){

        //add hidden field on email to check if there are change
        jQuery('#uuForm').append('<input type="hidden" name="emailpass" id="emailpass" value="'+jQuery('#jform_email1').val()+'"/>');

        jQuery('#jform_username')
            .on('beforeValidation', function(value, lang, config) {
                jQuery('#jform_username').attr('data-validation-skipped', 1)
            });


        //don't validate if password is not set
        jQuery('#jform_password1')
            .on('beforeValidation', function(value, lang, config) {
                if (this.value == '') {
                    jQuery(this).attr('data-validation-skipped', 1); //to prevent validation
                } else {
                    jQuery(this).removeAttr('data-validation-skipped'); //to prevent validation
                }
            });

        jQuery('#jform_password2')
            .on('beforeValidation', function(value, lang, config) {
                if (jQuery('#jform_password1').val() == '') {
                    jQuery('#jform_password2').attr('data-validation-skipped', 1); //to prevent validation
                } else {
                    jQuery('#jform_password2').removeAttr('data-validation-skipped'); //to prevent validation
                }
            });

        jQuery('#jform_email1')
            .on('beforeValidation', function(value, lang, config) {
                if (this.value == jQuery('#emailpass').val()) {
                    jQuery(this).attr('data-validation-skipped', 1); //to prevent validation
                }

        });
    });

    //new 1.5.1 //don't work withjoms.jQuery
    jQuery(document).ready(function(){
        jQuery(".tipRight").tooltip({placement : 'right'});

    });


</script>
