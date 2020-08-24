<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;

$user_params = JComponentHelper::getParams('com_users');
$user_activation = $user_params->get('useractivation');
$notification_to_admin = $user_params->get('mail_to_admin');

//0->yes 1->self 2->admin
$not_in_use_txt = '<span style="background:none;padding-left:5px;font-size:10px">'.JText::_('COM_UU_FORM_DEFAULT_CONFIGURATION_MAIL_NOT_IN_USE').'</span>';


?>
<div class="span8">

    <?php echo JHtml::_('sliders.start', 'uu-configuration-mail-slider',array('useCookie'=>1)); ?>

    <?php echo JHtml::_('sliders.panel', JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_ADMIN_ACTIVATION').($user_activation != 2 ? $not_in_use_txt: ''), 'mail-activation-slider'); ?>
    <fieldset class="adminform confmail">
        <div><?php echo JText::_('COM_UU_FORM_DEFAULT_CONFIGURATION_MAIL_ADMIN_ACTIVATION_TEXT');?></div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('admin_activation_mail_subject'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('admin_activation_mail_subject',null); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('admin_activation_mail_body'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('admin_activation_mail_body',null); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('admin_activation_mail_body_nopw'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('admin_activation_mail_body_nopw',null); ?>
            </div>
        </div>
    </fieldset>

    <?php echo JHtml::_('sliders.panel', JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_ACTIVATION').($user_activation == 0 ? $not_in_use_txt: ''), 'mail-activation-slider'); ?>

    <fieldset class="adminform confmail">
        <div><?php echo JText::_('COM_UU_FORM_DEFAULT_CONFIGURATION_MAIL_ACTIVATION_TEXT');?></div>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('activation_mail_subject'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('activation_mail_subject',null); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('activation_mail_body'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('activation_mail_body',null); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('activation_mail_body_nopw'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('activation_mail_body_nopw',null); ?>
            </div>
        </div>


    <?php echo JHtml::_('sliders.panel', JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_APPROVAL').($user_activation != 2 ? $not_in_use_txt: ''), 'mail-approval-slider'); ?>
    <fieldset class="adminform confmail">
        <div><?php echo JText::_('COM_UU_FORM_DEFAULT_CONFIGURATION_MAIL_APPROVAL_TEXT');?></div>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('approval_mail_subject'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('approval_mail_subject',null); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('approval_mail_body'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('approval_mail_body',null); ?>
            </div>
        </div>

    </fieldset>

    <?php echo JHtml::_('sliders.panel', JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_REGISTERED'), 'mail-registerd-slider'); ?>
    <fieldset class="adminform confmail">
        <div><?php echo JText::_('COM_UU_FORM_DEFAULT_CONFIGURATION_MAIL_REGISTERED_TEXT');?></div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('registered_mail_subject'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('registered_mail_subject',null); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('registered_mail_body'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('registered_mail_body',null); ?>
            </div>
        </div>

    </fieldset>

    <?php echo JHtml::_('sliders.panel', JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_ACTIVATE_BY_ADMIN_ACTIVATION').($user_activation != 2 ? $not_in_use_txt: ''), 'mail-activated-by-admin-slider'); ?>
    <fieldset class="adminform confmail">
        <div><?php echo JText::_('COM_UU_FORM_DEFAULT_CONFIGURATION_MAIL_ACTIVATE_BY_ADMIN_ACTIVATION_TEXT');?></div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('activate_by_admin_activation_mail_subject'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('activate_by_admin_activation_mail_subject',null); ?>
            </div>

            <div class="control-label">
                <?php echo $this->form->getLabel('activate_by_admin_activation_mail_body'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('activate_by_admin_activation_mail_body',null); ?>
            </div>
        </div>
    </fieldset>

        <?php echo JHtml::_('sliders.panel', JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_ACTIVATE_WITH_ADMIN_ACTIVATION').($user_activation != 2 ? $not_in_use_txt: ''), 'mail-activated-with-admin-slider'); ?>
        <fieldset class="adminform confmail">
            <div><?php echo JText::_('COM_UU_FORM_DEFAULT_CONFIGURATION_MAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_TEXT');?></div>

            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('activate_with_admin_activation_mail_subject'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->form->getInput('activate_with_admin_activation_mail_subject',null); ?>
                </div>

                <div class="control-label">
                    <?php echo $this->form->getLabel('activate_with_admin_activation_mail_body'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->form->getInput('activate_with_admin_activation_mail_body',null); ?>
                </div>
            </div>
        </fieldset>

    <?php echo JHtml::_('sliders.panel', JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_RESET'), 'mail-reset-slider'); ?>
    <fieldset class="adminform confmail">
        <div><?php echo JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_RESET_TEXT');?></div>
        <div class="control-label">
            <?php echo $this->form->getLabel('reset_mail_subject'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('reset_mail_subject',null); ?>
        </div>
        <div class="control-label">
            <?php echo $this->form->getLabel('reset_mail_body'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('reset_mail_body',null); ?>
        </div>
    </fieldset>

    <?php echo JHtml::_('sliders.panel', JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_NOTIFICATION_TO_ADMIN').($notification_to_admin != 1 ? $not_in_use_txt: ''), 'mail-notification-to-admin-slider'); ?>
    <fieldset class="adminform confmail">
        <div><?php echo JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_NOTIFICATION_TO_ADMIN_TEXT');?></div>
        <div class="control-label">
            <?php echo $this->form->getLabel('notification_to_admin_mail_subject'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('notification_to_admin_mail_subject',null); ?>
        </div>
        <div class="control-label">
            <?php echo $this->form->getLabel('notification_to_admin_mail_body'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('notification_to_admin_mail_body',null); ?>
        </div>
    </fieldset>

    <?php echo JHtml::_('sliders.end');?>
</div>

<div class="span4">
    <fieldset class="form-horizontal">
        <legend><?php echo JText::_('COM_UU_CONFIGURATION_EDIT_MAIL_PARAM'); ?></legend>
            {hi_name} - full name of the registered user or admin depending on email purpouse
            <br/>
            {name} - full name of the registered user
            <br/>
            {username} - username of the registered user
            <br/>
            {email} - email of the registered user
            <br/>
            {sitename} - Name of the website
            <br/>
            {siteurl} - URL of the website
            <br/>
            {activate_link} - URL of the website
            <br/>
            {ip_addr} - User's IP address
            <br/>
            {whois} - WHOIS service site
            <br/>
            {customFields} - All custom fields of the user
            <br/>
            {SQL_NAME_OF_CUSTOM_FIELD} - Specific custom field of the user. I.E {cf_mycustomfield}
            <br/>
            {register_link} - Link to the register page
            <br/>
            {login_link} - Link to the login page
            <br/>
            {reset_confirm_link} - Link to reset confirmation page
            <br/>
            {reset_token} - Reset token (only available in 'Reset password' mail)
            <br/>
            {password} - Password (available only in emails that are sent as soon as the user registered)
    </fieldset>
</div>
<div style="clear: both;"></div>
