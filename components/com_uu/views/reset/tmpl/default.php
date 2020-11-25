<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div class="reset<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1>
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    </div>
	<?php endif; ?>

	<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_uu&task=reset.request'); ?>" method="post" class="form-validate form-horizontal well">
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
            <fieldset>
                <p><?php echo JText::_($fieldset->label); ?></p>
				<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
					<?php if ($field->hidden === false) : ?>
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
            </fieldset>
		<?php endforeach; ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary validate">
					<?php echo JText::_('JSUBMIT'); ?>
                </button>
            </div>
        </div>
		<?php echo JHtml::_('form.token'); ?>
    </form>
</div>