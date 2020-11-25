<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

defined('_JEXEC') or die;

class UMailHelper
{

	/*
	 * use array_key_exist due to slow array get data when not exit
	 * */
    public function renderMail($message,$data, $user, $admin = '') {
        $app = JFactory::getApplication();

        $name = $user->name;
        $email = $user->email;
        $username = $user->username;
        $whois = 'http://tools.whois.net/whoisbyip/?host=';
        $ip_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $sitename = $app->getCfg('sitename');
        $uri = JURI::getInstance();
        $siteURL = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
	    $reset_token = array_key_exists('token', $data) ? $data['token'] : null;

        $clear_password  =  array_key_exists('password_clear', $data) ? $data['password_clear'] : null;

        $activation =  array_key_exists('activation', $data) ? $data['activation'] : null;

	    $activate_link = $siteURL . JRoute::_('index.php?option=com_uu&task=users.activate&token='.$activation, false);
        $register_link = $siteURL . JRoute::_('index.php?option=com_uu&task=users.register', false);
        $login_link = $siteURL . JRoute::_('index.php?option=com_uu&task=users.login', false);

        $reset_confirm_link = array_key_exists('reset_link_html', $data) ? $data['reset_link_html'] : null;

        if (preg_match('~^(.*?)/modules/mod_[^/]+(.*?)$~smi', $activate_link, $m)) {
            $activate_link = $m[1] . $m[2];
        }
        if (preg_match('~^(.*?)/modules/mod_[^/]+(.*?)$~smi', $register_link, $m)) {
            $register_link = $m[1] . $m[2];
        }
        if (preg_match('~^(.*?)/modules/mod_[^/]+(.*?)$~smi', $login_link, $m)) {
            $login_link = $m[1] . $m[2];
        }
        if (preg_match('~^(.*?)/modules/mod_[^/]+(.*?)$~smi', $reset_confirm_link, $m)) {
            $reset_confirm_link = $m[1] . $m[2];
        }

        $search = array('{hi_name}', '{name}', '{username}', '{email}', '{sitename}', '{siteurl}', '{activate_link}', '{register_link}', '{login_link}', '{reset_token}', '{reset_confirm_link}','{ip_addr}', '{whois}', '{password}');
        $replace = array(($admin ? $admin : $name), $name, $username, $email, $sitename, $siteURL, $activate_link, $register_link, $login_link, $reset_token, $reset_confirm_link, $ip_addr, $whois, $clear_password);
        $message = str_replace($search, $replace, $message);

        // Replace Custom Fields
        $customFields = '';
        $search = array();
        $replace = array();

        $cf_names = $this->getCustomFieldsInfo();

        if (is_array($cf_names) && count($cf_names)) {
            foreach($cf_names as $cf) {
                $fieldname = $cf->fieldcode;
                $search[] = '{' . $fieldname . '}';
                if (isset($user->$fieldname)) {
                    $userval = $user->$fieldname;
                    if (is_array($userval)) {
                        $userval = implode(', ', $userval);
                    }
                    $customFields .= $cf->name . ': ' . $userval . '<br/>';
                    $replace[] = $userval;
                } else {
                    $replace[] = '';
                }
            }
        }
        $search[] = '{customFields}';
        $replace[] = $customFields;
        $message = str_replace($search, $replace, $message);

        return $message;

    }

    //TODO mettre cette méthode dans le admin models de fields ? et variable de retour en statique ??
    function getCustomFieldsInfo() {
        $cfinfo = array();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id,name,fieldcode')
              ->from('#__uu_fields')
              ->where('published = 1')
              ->where('core = 0')
              ->where('type !='.$db->quote('group'));

        $db->setQuery($query);
        $result = $db->loadObjectList();
            if ($result) {
                foreach ($result as $row) {
                    $cfinfo[(int)$row->id] = $row;
                }
            }
        return $cfinfo;
    }

}