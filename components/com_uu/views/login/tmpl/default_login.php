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
<div class="login<?php echo $this->pageclass_sfx?>">
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
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="remember">
							<?php echo JText::_('JGLOBAL_REMEMBER_ME'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input id="remember" type="checkbox" name="remember" class="inputbox" value="yes" />
                    </div>
                </div>
			<?php endif; ?>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-primary">
						<?php echo JText::_('JLOGIN'); ?>
                    </button>
                </div>
            </div>
			<input type="hidden" name="return" value="<?php echo base64_encode($this->login_redirect_url); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>
<div>
	<ul>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_uu&view=reset'); ?>">
			<?php echo JText::_('COM_UU_LOGIN_RESET'); ?></a>
		</li>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_uu&view=registration'); ?>">
				<?php echo JText::_('COM_UU_LOGIN_REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul>
</div>
