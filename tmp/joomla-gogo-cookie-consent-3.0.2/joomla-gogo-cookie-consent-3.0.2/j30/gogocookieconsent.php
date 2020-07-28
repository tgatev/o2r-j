<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @email info@gogodigital.it
 * @license GNU GENERAL PUBLIC LICENSE VERSION 3
 * @package Joomla Gogodigital Cookie Consent
 * @version 3.0.2
 */

// no direct access
defined( '_JEXEC' ) or die;

// Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');

class plgSystemGogocookieconsent extends JPlugin 
{
	public function __construct( &$subject, $config ) 
	{	
		parent::__construct( $subject, $config );	
	}
	
	function onBeforeRender() 
	{
		$app = JFactory::getApplication();		
		$doc = JFactory::getDocument();
		
		if ($app->isAdmin()) {
			return;
		}
		
		$cookieScript = 'window.cookieconsent_options = {
			"message":"'.$this->params->get("cookieMessage","This website uses cookies to ensure you get the best experience on our website").'",
			"dismiss":"'.$this->params->get("cookieDismiss","Got It!").'",
			"learnMore":"'.$this->params->get("cookieMore","More Info").'",';

		if(!$this->params->get("cookieLink")) {
			$cookieScript .= '
				"link": null,';
		} else {
			$cookieScript .= '
				"link":"'.$this->params->get("cookieLink").'",';
		}

		$cookieScript .= '
			"theme":"'.$this->params->get("cookieTheme","dark-bottom").'"
		};';

		switch($this->params->get("cookieLoad"))
        {
            case 0:
                break;
            case 1:
                $doc->addScript('//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js');
                $doc->addScriptDeclaration($cookieScript);
                break;
            case 2:
                $doc->addScript('media/gogodigital/js/cookieconsent.min.js');
                $doc->addScriptDeclaration($cookieScript);
                break;
        }
	}	
	
}
