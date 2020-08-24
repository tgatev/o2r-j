<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */
defined('_JEXEC') or die;

if (UU_J30) {
	class LegacyView extends JViewLegacy {}
} else {
	jimport( 'joomla.application.component.view' );
	class LegacyView extends JView {}
}