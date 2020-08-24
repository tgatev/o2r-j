<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;

class UuSiteHelper
{

  public static function getRedirectUrl($url, $custom = 0, $custom_menu = 0) {
      $app    = JFactory::getApplication();
      $result = '';
      switch ($url) {
          case 'home':
              $result = JURI::base();
              break;
          case 'login':
              $result = 'index.php?option=com_uu&view=login';
              break;
          case 'register':
              $result = 'index.php?option=com_uu&view=registration';
              break;
          case 'custom':
              $result = 'index.php?option=com_content&view=article&id='.$custom;
              break;
	      case 'custom_menu':
		      $result = 'index.php?Itemid='.$custom_menu;
		      break;
          case 'previous':
              $return = $app->getUserState('uu.login.form.return');
              if (!isset($return) || empty($return)) {
                  $data = $app->getUserState('uu.login.form.data');
                  $result  = $data['return'];
              } else {
                  $result = $app->getUserState('uu.login.form.return', JURI::base());
              }
              break;
          default:
              $result = JURI::base();
              break;
      }
      return $result;
  }

	public static function getValidatorLanguage() {
		$default_lang_code = 'en';
		$lang = JFactory::getLanguage()->getTag();
		$lang_code = substr($lang,0,2);

		jimport( 'joomla.filesystem.file' );

		if (JFile::exists(JPATH_SITE.'/components/com_uu/assets/js/lang/'.$lang_code.'.js')){
			return $lang_code;
		} else {
			return $default_lang_code;
		}
	}

}

class UValidateHelper
{
    static public function username( $username )
    {
        // Make sure the username is at least 1 char and contain no funny char
        return (!preg_match( "/[<>\"'%;()&]/i" , $username ) && JString::strlen( $username )  > 0 );
    }

}

