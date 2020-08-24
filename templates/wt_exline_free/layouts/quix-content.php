<?php if ($this['config']->get('system_output', true)) : ?>
<main class="tm-content">
	<?php echo $this['template']->render('content'); ?>
</main>
<?php endif; ?>