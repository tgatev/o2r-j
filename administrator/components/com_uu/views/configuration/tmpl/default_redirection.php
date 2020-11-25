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
    <fieldset>
        <legend><?php echo JText::_('COM_UU_CONFIGURATION_EDIT_REDIRECTION_LOGIN'); ?></legend>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('red_login_success'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->form->getInput('red_login_success'); ?>
                </div>
            </div>
        <!-- user renderField to have showon working-->
         <?php echo $this->form->renderField('red_login_success_custom'); ?>
	    <?php echo $this->form->renderField('red_login_success_custom_menu'); ?>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('red_logout_success'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('red_logout_success'); ?>
            </div>
        </div>

        <!-- user renderField to have showon working-->
	    <?php echo $this->form->renderField('red_logout_success_custom'); ?>
	    <?php echo $this->form->renderField('red_logout_success_custom_menu'); ?>

    </fieldset>
</div>
<div class="span6">
    <fieldset>
        <legend><?php echo JText::_('COM_UU_CONFIGURATION_EDIT_REDIRECTION_REGISTRATION'); ?></legend>

    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('red_registration_success'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('red_registration_success'); ?>
        </div>
    </div>

    <!-- user renderField to have showon working-->
    <?php echo $this->form->renderField('red_registration_success_custom'); ?>
    <?php echo $this->form->renderField('red_registration_success_custom_menu'); ?>



    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('red_registration_with_activation'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('red_registration_with_activation'); ?>
        </div>
    </div>

    <!-- user renderField to have showon working-->
    <?php echo $this->form->renderField('red_registration_with_activation_custom'); ?>
    <?php echo $this->form->renderField('red_registration_with_activation_custom_menu'); ?>


    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('red_registration_with_approval'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('red_registration_with_approval'); ?>
        </div>
    </div>

    <!-- user renderField to have showon working-->
    <?php echo $this->form->renderField('red_registration_with_approval_custom'); ?>
	    <?php echo $this->form->renderField('red_registration_with_approval_custom_menu'); ?>

    </fieldset>
</div>
<div class="span6" style="display: none">
    <fieldset>
        <legend><?php echo JText::_('COM_UU_CONFIGURATION_EDIT_REDIRECTION_ACTIVATION'); ?></legend>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('red_activation_success'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('red_activation_success'); ?>
            </div>
        </div>

        <!-- user renderField to have showon working-->
	    <?php echo $this->form->renderField('red_activation_success_custom'); ?>
	    <?php echo $this->form->renderField('red_activation_success_custom_menu'); ?>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('red_activation_with_approval'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('red_activation_with_approval'); ?>
            </div>
        </div>

        <!-- user renderField to have showon working-->
	    <?php echo $this->form->renderField('red_activation_with_approval_custom'); ?>
	    <?php echo $this->form->renderField('red_activation_with_approval_custom_menu'); ?>

    </fieldset>
</div>

<div style="clear: both;"></div>

