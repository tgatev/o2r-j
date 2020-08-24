<?php
/**
 * @package Helix3 Framework
 * @author WarpTheme http://www.warptheme.com
 * @copyright WarpTheme
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

class Helix3FeatureCookie {

	private $helix3;

	public function __construct($helix){
		$this->helix3 = $helix;
		$this->position = 'helixcookie';
	}

	public function renderFeature() {

		$app = JFactory::getApplication();
		//Load Helix
		$helix3_path = JPATH_PLUGINS . '/system/helix3/core/helix3.php';
		if (file_exists($helix3_path)) {
			require_once($helix3_path);
			$getHelix3 = helix3::getInstance();
		} else {
			die('Please install and activate helix plugin');
		}

		$output = '';

    $position = $this->helix3->getParam('cookie_consent_position');
    $layout= $this->helix3->getParam('cookie_consent_layout');
    $message = $this->helix3->getParam('cookie_consent_message');
    $dismiss = $this->helix3->getParam('cookie_consent_dismiss');
    $readmore = $this->helix3->getParam('cookie_consent_readmore');
    $policy = $this->helix3->getParam('cookie_consent_link');

		if ($getHelix3->getParam('cookie')) {
          $output .= '  <script>';
              $output .= '  window.addEventListener("load", function(){';
                $output .= '  window.cookieconsent.initialise({';
                $output .= '    "palette": {';
                $output .= '      "popup": {';
                  $output .= '      "background": "#000"';
                  $output .= '    },';
                  $output .= '    "button": {';
                if ($getHelix3->getParam('cookie_consent_layout') == 'wire') {
                  $output .= '"background": "transparent",';
                  $output .= '  "text": "#f1d600",';
                  $output .= '"border": "#f1d600"';
                } else {
                $output .= '  "background": "#f1d600"';
}
                $output .= '      }';
                $output .= '    },';

                if($this->helix3->getParam('cookie_consent_layout')) $output .= '"theme": "' . $this->helix3->getParam('cookie_consent_layout') . '",';
                if($this->helix3->getParam('cookie_consent_position')) $output .= '"position": "' . $this->helix3->getParam('cookie_consent_position') . '",';
                $output .= '  "content": {';
                if($this->helix3->getParam('cookie_consent_message')) $output .= '"message": "' . $this->helix3->getParam('cookie_consent_message') . '",';
                if($this->helix3->getParam('cookie_consent_dismiss')) $output .= '"dismiss": "' . $this->helix3->getParam('cookie_consent_dismiss') . '",';
                if($this->helix3->getParam('cookie_consent_readmore')) $output .= '"link": "' . $this->helix3->getParam('cookie_consent_readmore') . '",';
                if($this->helix3->getParam('cookie_consent_link')) $output .= '"href": "'. $this->helix3->getParam('cookie_consent_link') . '"';
                $output .= '  }';
                $output .= '  })});</script>';


        } // if enable cookie

        echo $output;
	} //renderFeature
}
