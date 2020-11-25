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
?>
<script>
    // jQuery(document).ready(function(){
    //     jQuery('#sp-main-body').css('min-height', 'calc(100vh - 250px)');
    // })
</script>
<div class="login<?php echo $this->pageclass_sfx?> col-xs-12 col-md-6 col-md-offset-3">
	<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1>
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    </div>
	<?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_uu&task=user.login'); ?>" method="post" class="form-validate form-horizontal well">
		<fieldset>
			<?php foreach ($this->form->getFieldset('credentials') as $field): ?>
				<?php if (!$field->hidden): ?>
                    <div class="control-group rpw">
                        <div class="col-xs-4">
							<?php echo $field->label; ?>
                        </div>
                        <div class="controls col-xs-8">
							<?php echo $field->input; ?>
                        </div>
                    </div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                <div class="row">
                    <div class="col-xs-4">
                        <label for="remember">
							<?php echo JText::_('JGLOBAL_REMEMBER_ME'); ?>
                        </label>
                    </div>
                    <div class="col-xs-1">
                        <input id="remember" type="checkbox" name="remember" class="inputbox" value="yes" />
                    </div>
                    <div class="controls col-xs-7">
                        <button type="submit" class="btn btn-primary login-button">
                            <?php echo JText::_('JLOGIN'); ?>
                        </button>
                    </div>
                </div>
			<?php endif; ?>
            <div class="control-group">
                <!--                    Previous button place -->
            </div>
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <a href="<?php echo JRoute::_('index.php?option=com_uu&view=reset&Itemid=3427'); ?>">
                        <?php echo JText::_('COM_UU_LOGIN_RESET'); ?></a>
                </div>
                <?php
                    $usersConfig = JComponentHelper::getParams('com_users');
                    if ($usersConfig->get('allowUserRegistration')) : ?>
                    <div "col-xs-6">
                        <a href="<?php echo JRoute::_('index.php?option=com_uu&view=registration&Itemid=3428'); ?>">
                            <?php echo JText::_('COM_UU_LOGIN_REGISTER'); ?></a>
                    </div>
                <?php endif; ?>
            </div>
			<input type="hidden" name="return" value="<?php echo base64_encode($this->login_redirect_url); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>

