<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;
?>

<div class="span6">
    <fieldset class="form-horizontal">
        <legend><?php echo JText::_('COM_UU_CONFIGURATION_EDIT_REGISTRATION'); ?></legend>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('enable_user_registration'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('enable_user_registration'); ?>
            </div>
            <div class="faux-label"><?php echo JText::_($this->form->getFieldAttribute('enable_user_registration','description')); ?></div>

        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('email_as_username'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('email_as_username'); ?>
            </div>
            <div class="faux-label"><?php echo JText::_($this->form->getFieldAttribute('email_as_username','description')); ?></div>
        </div>

        <div class="control-group">
            <div class="control-label">
			    <?php echo $this->form->getLabel('store_ip_address'); ?>
            </div>
            <div class="controls">
			    <?php echo $this->form->getInput('store_ip_address'); ?>
            </div>
            <div class="faux-label"><?php echo JText::_($this->form->getFieldAttribute('store_ip_address','description')); ?></div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('enable_terms'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('enable_terms'); ?>
            </div>
            <div class="faux-label"><?php echo JText::_($this->form->getFieldAttribute('enable_terms','description')); ?></div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('enable_terms_url'); ?>
            </div>
            <div class="controls">
                <div class="input-append">
                    <?php echo $this->form->getInput('enable_terms_url'); ?>
                </div>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('registation_text_intro'); ?>
            </div>
            <div class="controls controls-textbox">
                <?php echo $this->form->getInput('registation_text_intro'); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('registration_text_concluding'); ?>
            </div>
            <div class="controls controls-textbox">
                <?php echo $this->form->getInput('registration_text_concluding'); ?>
            </div>
        </div>
    </fieldset>
</div>

<div class="span6">
    <fieldset class="form-horizontal">
        <legend><?php echo JText::_('COM_UU_CONFIGURATION_EDIT_JOOMLA_REGISTRATION_PARAM'); ?></legend>
            <?php
                jimport('joomla.application.component.helper');
                $params = JComponentHelper::getParams('com_users');
            ?>

            <div class="control-group">
                <div class="control-label">
                <label><?php echo JText::_('COM_USERS_CONFIG_FIELD_ALLOWREGISTRATION_LABEL')?></label>
                </div>
                <div class="controls">
                        <span class="disabled"><?php echo $params->get('allowUserRegistration') == 1 ? JText::_('JYES'): JText::_('JNO') ?></span>
                </div>
                <div class="faux-label"><?php echo JText::_('COM_USERS_CONFIG_FIELD_ALLOWREGISTRATION_DESC'); ?></div>

            </div>

            <div class="control-group">
                <div class="control-label">
                <?php echo JText::_('COM_USERS_CONFIG_FIELD_SENDPASSWORD_LABEL')?></label>
                </div>
                <div class="controls">
                <span class="disabled"><?php echo $params->get('sendpassword')== 1 ? JText::_('JYES'): JText::_('JNO') ?></span>
                </div>
                <div class="faux-label"><?php echo JText::_('COM_USERS_CONFIG_FIELD_SENDPASSWORD_DESC'); ?></div>
            </div>

            <div class="control-group">
                <div class="control-label">
                <label><?php echo JText::_('COM_USERS_CONFIG_FIELD_USERACTIVATION_LABEL')?></label>
                <?php switch ($params->get('useractivation')) {
                    case 0: $value = JText::_('JYES');
                        break;
                    case 1: $value = JText::_('COM_USERS_CONFIG_FIELD_USERACTIVATION_OPTION_SELFACTIVATION');
                        break;
                    case 2: $value = JText::_('COM_USERS_CONFIG_FIELD_USERACTIVATION_OPTION_ADMINACTIVATION');
                        break;
                }
                ?>
                </div>
                <div class="controls">
                <span class="disabled"><?php echo $value;?></span>
                </div>
                <div class="faux-label"><?php echo JText::_('COM_USERS_CONFIG_FIELD_USERACTIVATION_DESC'); ?></div>
            </div>

            <div class="control-group">
                <div class="control-label">
                <label><?php echo JText::_('COM_USERS_CONFIG_FIELD_MAILTOADMIN_LABEL')?></label>
                </div>
                <div class="controls">
                <span class="disabled"><?php echo $params->get('mail_to_admin')== 1 ? JText::_('JYES'): JText::_('JNO') ?></span>
                </div>
                <div class="faux-label"><?php echo JText::_('COM_USERS_CONFIG_FIELD_MAILTOADMIN_DESC'); ?></div>
            </div>

            <div class="control-group">
                <div class="control-label">
                <label><?php echo JText::_('COM_USERS_CONFIG_FIELD_FRONTEND_USERPARAMS_LABEL')?></label>
                </div>
                <div class="controls">
                <span class="disabled"><?php echo $params->get('frontend_userparams') == 1 ? JText::_('JYES'): JText::_('JNO') ?></span>
                </div>
                <div class="faux-label"><?php echo JText::_('COM_USERS_CONFIG_FIELD_FRONTEND_USERPARAMS_DESC'); ?></div>
            </div>

            <div class="control-group">
                <div class="control-label">
                <label><?php echo JText::_('COM_USERS_CONFIG_FIELD_CHANGEUSERNAME_LABEL')?></label>
                </div>
                <div class="controls">
                <span class="disabled"><?php echo $params->get('change_login_name')== 1 ? JText::_('JYES'): JText::_('JNO') ?></span>
                </div>
                <div class="faux-label"><?php echo JText::_('COM_USERS_CONFIG_FIELD_CHANGEUSERNAME_DESC'); ?></div>
            </div>
    </fieldset>
    </div>

    <div class="span6">
            <fieldset class="form-horizontal">

            <legend><?php echo JText::_('COM_UU_CONFIGURATION_EDIT_PASSWORD'); ?></legend>
            <div class="control-group">
                <div class="control-label">
                <?php echo $this->form->getLabel('enable_password_display'); ?>
                </div>
                <div class="controls">
                <?php echo $this->form->getInput('enable_password_display'); ?>
                </div>
                <div class="faux-label"><?php echo JText::_($this->form->getFieldAttribute('enable_password_display','description')); ?></div>
            </div>
    </fieldset>
</div>

<div style="clear: both;"></div>