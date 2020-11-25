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

?>

<script  type="text/javaScript">

    jQuery(document).ready(function () {
        dropdownGenerator( 'jform_cf_iam',  {
            enableFiltering: false,
            enableClickableOptGroups: false,
            enableCaseInsensitiveFiltering: false,
            includeResetOption: false,
        } );
        jQuery("button#ddb_jform_cf_iam > span").removeClass('col-xs-11') ;
        jQuery("button#ddb_jform_cf_iam > span").addClass('col-xs-10') ;
    });
</script>
<div id="uu-wrap" class="form-horizontal  profile<?php echo $this->pageclass_sfx?> col-xs-12 col-md-6 col-md-offset-3" xmlns="http://www.w3.org/1999/html">

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    </div>
<?php endif; ?>

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
        if ($key > 0 &&  $field->type == 'group') {
            ?>
            </fieldset>
          <fieldset>
        <?php
        }

        if( !$required && $field->required > 0 ) {$required	= true;}
        //don't display email2 password1 password2
        if ($field->fieldcode == 'email2' || $field->fieldcode == 'password1' || $field->fieldcode == 'password2') {continue;}
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

	<?php if (JFactory::getUser()->id == $this->data->id) : ?>
    <fieldset>
        <div class="control-group">
            <div class="controls">
                <a href="<?php echo JRoute::_('index.php?option=com_uu&task=profile.edit&user_id='.(int) $this->data->id);?>">
		            <?php echo JText::_('COM_UU_EDIT_PROFILE'); ?></a>
            </div>
        </div>
    </fieldset>
	<?php endif; ?>

</div>
