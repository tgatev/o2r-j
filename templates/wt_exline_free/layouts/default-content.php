<?php if ($this['widgets']->count('main-top + main-bottom + sidebar-a + sidebar-b') || $this['config']->get('system_output', true)) : ?>
	<div id="tm-main" class="tm-block-main uk-block <?php echo $classes['block.main']; ?>" <?php echo $styles['block.main']; ?>>

		<div class="uk-container uk-container-center">

			<div class="tm-middle uk-grid" data-uk-grid-match data-uk-grid-margin>

				<?php if ($this['widgets']->count('main-top + main-bottom') || $this['config']->get('system_output', true)) : ?>
					<div class="<?php echo $classes['layout.main'] ?>">

						<?php if ($this['widgets']->count('main-top')) : ?>
							<section id="tm-main-top" class="tm-main-top <?php echo $classes['grid.main-top']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
								<?php echo $this['widgets']->render('main-top', array('layout'=>$this['config']->get('grid.main-top.layout'))); ?>
							</section>
						<?php endif; ?>

						<?php if ($this['config']->get('system_output', true)) : ?>
							<main id="tm-content" class="tm-content">

								<?php if ($this['widgets']->count('breadcrumbs')) : ?>
									<?php echo $this['widgets']->render('breadcrumbs'); ?>
								<?php endif; ?>

								<?php echo $this['template']->render('content'); ?>

							</main>
						<?php endif; ?>

						<?php if ($this['widgets']->count('main-bottom')) : ?>
							<section id="tm-main-bottom" class="tm-main-bottom <?php echo $classes['grid.main-bottom']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
								<?php echo $this['widgets']->render('main-bottom', array('layout'=>$this['config']->get('grid.main-bottom.layout'))); ?>
							</section>
						<?php endif; ?>

					</div>
				<?php endif; ?>

				<?php foreach($sidebars as $name => $sidebar) : ?>
					<aside class="<?php echo $classes["layout.$name"] ?>"><?php echo $this['widgets']->render($name) ?></aside>
				<?php endforeach ?>

			</div>

		</div>

	</div>
<?php endif; ?>
