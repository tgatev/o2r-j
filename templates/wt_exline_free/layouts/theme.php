<?php
/**
* @package   yoo_master2
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// get theme configuration
include($this['path']->path('layouts:theme.config.php'));

?>
<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>"  data-config='<?php echo $this['config']->get('body_config','{}'); ?>'>
<head>
  <?php echo $this['template']->render('head');
  include($this['path']->path('layouts:preloader.php'));
?>
</head>
<body class="<?php echo $this['config']->get('body_classes'); ?>">
  <div class="body-innerwrapper">
  <?php if ($this['widgets']->count('toolbar-l + toolbar-r')) : ?>
    <div class="tm-toolbar uk-clearfix uk-hidden-small">
      <div class="uk-container uk-container-center">
        <?php if ($this['widgets']->count('toolbar-l')) : ?>
          <div class="uk-float-left"><?php echo $this['widgets']->render('toolbar-l'); ?></div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('toolbar-r')) : ?>
          <div class="uk-float-right"><?php echo $this['widgets']->render('toolbar-r'); ?></div>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
  <?php if ($this['widgets']->count('logo + menu + search')) : ?>
    <div class="tm-navbar uk-navbar" <?php if ($this['config']->get('navbar_sticky')) echo 'data-uk-sticky'; ?>>
      <div class="uk-container uk-container-center">

        <nav class="tm-navbar-container">

          <?php if ($this['widgets']->count('logo')) : ?>
            <div class="tm-nav-logo uk-hidden-small">
              <a class="tm-logo uk-navbar-brand uk-responsive-width uk-responsive-height" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo'); ?></a>
            </div>
          <?php endif; ?>

          <?php if ($this['widgets']->count('menu')) : ?>
            <div class="tm-nav uk-hidden-small">
              <?php echo $this['widgets']->render('menu'); ?>
            </div>
          <?php endif; ?>
          <?php if ($this['widgets']->count('search')) : ?>
            <div class="tm-search uk-hidden-small">
              <?php echo $this['widgets']->render('search'); ?>
            </div>
          <?php endif; ?>

          <?php if ($this['widgets']->count('offcanvas')) : ?>
            <a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
          <?php endif; ?>

          <?php if ($this['widgets']->count('logo-small')) : ?>
            <div class="uk-navbar-content uk-navbar-center uk-visible-small">
              <a class="uk-responsive-width uk-responsive-height" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo-small'); ?></a>
            </div>
          <?php endif; ?>

        </nav>

      </div>
    </div>
  <?php endif; ?>

  <?php if ($this['widgets']->count('top-a')) : ?>
    <div id="tm-top-a" class="tm-block-top-a uk-block uk-block-default tm-block-fullwidth" <?php echo $styles['block.top-a']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.top-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('top-a', array('layout'=>$this['config']->get('grid.top-a.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <?php if ($this['widgets']->count('top-b')) : ?>
    <div id="tm-top-b" class="tm-block-top-b uk-block <?php echo $classes['block.top-b']; ?>" <?php echo $styles['block.top-b']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.top-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('top-b', array('layout'=>$this['config']->get('grid.top-b.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <?php if ($this['widgets']->count('top-c')) : ?>
    <div id="tm-top-c" class="tm-block-top-c uk-block <?php echo $classes['block.top-c']; ?>" <?php echo $styles['block.top-c']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.top-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('top-c', array('layout'=>$this['config']->get('grid.top-c.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <?php if ($this['widgets']->count('top-d')) : ?>
    <div id="tm-top-d" class="tm-block-top-d uk-block <?php echo $classes['block.top-d']; ?>" <?php echo $styles['block.top-d']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.top-d']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('top-d', array('layout'=>$this['config']->get('grid.top-d.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <?php
		$input = new JInput();
		$has_quix = $input->getCmd('option') === 'com_quix';
		if ($input->getCmd('option') === 'com_quix') {
			include($this['path']->path('layouts:quix-content.php'));
		} else {
			include($this['path']->path('layouts:default-content.php'));
		}
	?>

  <?php if ($this['widgets']->count('bottom-a')) : ?>
    <div id="tm-bottom-a" class="tm-block-bottom-a uk-block <?php echo $classes['block.bottom-a']; ?>" <?php echo $styles['block.bottom-a']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.bottom-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('bottom-a', array('layout'=>$this['config']->get('grid.bottom-a.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <?php if ($this['widgets']->count('bottom-b')) : ?>
    <div id="tm-bottom-b" class="tm-block-bottom-b uk-block <?php echo $classes['block.bottom-b']; ?>" <?php echo $styles['block.bottom-b']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.bottom-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('bottom-b', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <?php if ($this['widgets']->count('bottom-c')) : ?>
    <div id="tm-bottom-c" class="tm-block-bottom-c uk-block <?php echo $classes['block.bottom-c']; ?>" <?php echo $styles['block.bottom-c']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.bottom-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('bottom-c', array('layout'=>$this['config']->get('grid.bottom-c.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <?php if ($this['widgets']->count('bottom-d')) : ?>
    <div id="tm-bottom-d" class="tm-block-bottom-d uk-block <?php echo $classes['block.bottom-d']; ?>" <?php echo $styles['block.bottom-d']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.bottom-d']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('bottom-d', array('layout'=>$this['config']->get('grid.bottom-d.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <?php if ($this['widgets']->count('bottom-e')) : ?>
    <div id="tm-bottom-e" class="tm-block-bottom-e uk-block <?php echo $classes['block.bottom-e']; ?>" <?php echo $styles['block.bottom-e']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.bottom-e']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('bottom-e', array('layout'=>$this['config']->get('grid.bottom-e.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <?php if ($this['widgets']->count('bottom-f')) : ?>
    <div id="tm-bottom-f" class="tm-block-bottom-f uk-block <?php echo $classes['block.bottom-f']; ?>" <?php echo $styles['block.bottom-f']; ?>>

      <div class="uk-container uk-container-center">

        <section class="<?php echo $classes['grid.bottom-f']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
          <?php echo $this['widgets']->render('bottom-f', array('layout'=>$this['config']->get('grid.bottom-f.layout'))); ?>
        </section>

      </div>

    </div>
  <?php endif; ?>

  <div id="tm-footer" class="tm-block-footer">
    <div class="uk-container uk-container-center">

      <?php if ($this['widgets']->count('footer + debug') || $this['config']->get('warp_branding', true) || $this['config']->get('totop_scroller', true)) : ?>
        <footer class="tm-footer tm-link-muted">

          <?php if ($this['config']->get('totop_scroller', true)) : ?>
            <a id="tm-anchor-bottom" class="tm-totop-scroller" data-uk-smooth-scroll href="#"></a>
          <?php endif; ?>

          <?php
          echo $this['widgets']->render('footer');
          $this->output('warp_branding');
          echo $this['widgets']->render('debug');
          ?>

        </footer>
      <?php endif; ?>

    </div>
  </div>

  <?php if ($this['widgets']->count('offcanvas')) : ?>
    <div id="offcanvas" class="uk-offcanvas">
      <div class="uk-offcanvas-bar"><?php echo $this['widgets']->render('offcanvas'); ?></div>
    </div>
  <?php endif; ?>

  <?php echo $this->render('footer'); ?>
</div>
</body>
</html>
