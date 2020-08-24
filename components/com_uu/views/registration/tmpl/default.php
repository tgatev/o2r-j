<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.noframes');
//@bug on modal http://joomlacode.org/gf/project/joomla/tracker/?action=TrackerItemEdit&tracker_item_id=27239
//use iframe handler
JHTML::_('behavior.modal', 'a.modal', array('handler' => 'iframe'));

$conf = new UuConfig();
$required	= false;


//load recaptcha api if captca field is present (our onload callback function must be defined before the reCAPTCHA API loads)
foreach ($this->registrationFields as $key => $field) {
    if ($field->type == 'captcha') {
        $params = new UuParameter($field->params);
        /* no more necessary with validate/security
        if ($params->get('version') == '2.0') {
            echo '<script src="https://www.google.com/recaptcha/api.js?onload=UuInitReCaptcha2&render=explicit" async defer></script>';
        }
        */
    }
}

?>
<div id="uu-wrap" class="form-validate form-horizontal registration<?php echo $this->pageclass_sfx?>"
     xmlns="http://www.w3.org/1999/html">
    <?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1 class="componentheading"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    </div>
    <?php endif; ?>

    <?php
    //display registration intro text
    $registration_text_intro = $conf->get('registation_text_intro');
    if (!empty($registration_text_intro)) {
       echo $registration_text_intro;
    }
    ?>

    <form id="uuForm" name="uuForm" action="<?php echo JRoute::_('index.php?option=com_uu&task=registration.register'); ?>" method="post" class="uu-form-validate" role="form">
        <?php
            foreach ($this->registrationFields as $key => $field):?>
            <?php
            //load field param's
            $params	= new UuParameter($field->params);

             //first element
             if ($key == 0) {
                 echo '<fieldset class="'.$params->get('style').'">';
             }
            //not first element and it's a group
            if ($key > 0 &&  $field->type == 'group') {
                ?>
                </fieldset>
                <fieldset class="<?php echo $params->get('style') ?>">
                    <legend><?php echo JText::_($field->name); ?></legend>
                <?php
            }

            if( !$required && $field->required == 1 ) {$required	= true;}

            //don't display username if allow mail as username
            if ($conf->get('email_as_username') && $field->fieldcode == 'username') {continue;}

            if ($field->type != 'group') {
            ?>
                    <div class="control-group">
                        <div class="control-label" id="lblfield<?php echo $field->id;?>" for="jform_<?php echo $field->fieldcode;?>">
	                        <?php if($field->required > 0) echo '*'; ?><?php echo JText::_($field->name); ?>
                        </div>
                        <div class="controls">
                            <?php echo $field->html; ?>
                        </div>
                    </div>
                <?php
            }

            ?>

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

                <?php
                //display terms
                if ($conf->get('enable_terms'))
                {
                    $link_accepted_terms = "index.php?option=com_content&view=article&tmpl=component&id=".(int)$conf->get('enable_terms_url');
                ?>
                    <div class="control-group">
                        <div class="controls" class="form-label" for="jform_accepted_terms">
                            <label class="label-checkbox">
                                <input type="checkbox" name="jform[accepted_terms]" id="jform_accepted_terms" value="1" default="0" class="input checkbox required" data-validation="required" data-validation-error-msg="<?php echo JText::_('COM_UU_REGISTRATION_TERMS_AND_CONDITION_REQUIRED') ?>"/>
                                <?php echo JText::_('COM_UU_REGISTRATION_I_HAVE_READ').' <a  class="modal" href="'. JRoute::_($link_accepted_terms).'">'.JText::_('COM_UU_REGISTRATION_TERMS_AND_CONDITION').'</a>.';?>
                            </label>
                        </div>
                    </div>
                <?php } ?>

            <div class="control-group">
                <?php
                //display registration intro text
                if ($conf->get('registration_text_concluding')) {
                    echo $conf->get('registration_text_concluding');
                }
                ?>
            </div>
            <div class="control-group">

                <div class="controls">
                    <div id="cwin-wait" style="display:none;"></div>
                    <div id="cwin-btn">
                        <input class="cButton cButton-Blue validateSubmit" type="submit" id="btnSubmit" value="<?php echo JText::_('JREGISTER'); ?>" name="submit">
                    </div>
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="option" value="com_uu" />
        <input type="hidden" name="task" value="registration.register" />
        <?php echo JHtml::_('form.token');?>
    </form>
</div>

<script type="text/javascript">

   //add module security to enable server check
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

    jQuery(document).ready(function(){
        jQuery(".tipRight").tooltip({placement : 'right'});
    });


</script>
