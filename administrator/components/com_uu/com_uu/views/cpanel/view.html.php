<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2015. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


/**
 * HTML View class for the Languages component
 *
 * @package		Joomla.Administrator
 * @subpackage	com_languages
 * @since		1.5
 */
class UuViewCpanel extends JViewLegacy
{
    protected $statistics = array();
    /**
     * Display the view
     */
    public function display($tpl = null)
    {

        //update downloadid
        $this->getModel()->updateDownloadId();

        JLoader::import('joomla.application.component.model');
        $usersModel = JModelLegacy::getInstance('users', 'UuModel');
        require_once JPATH_ADMINISTRATOR . '/components/com_uu/models/users.php';

        $usermissing = $usersModel->checkSyncUserMissing();
        $userextra = $usersModel->checkSyncUserExtra();

        if ($usermissing > 0 || $userextra > 0) {
            $url = 'index.php?option=com_uu&task=users.sync';
            JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_UU_USERS_NOT_SYNCHRONIZED',$url));
        }

        //check plugin redirect activation
        $uuRedirect = JPluginHelper::isEnabled("system","uuredirect");
        if (!$uuRedirect){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_UU_PLUGIN_REDIRECT_NOT_PUBLISHED'),'warning');
        }

        //check plugin user synchro
        $uuUser = JPluginHelper::isEnabled("user","ultimateuser");
        if (!$uuUser){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_UU_PLUGIN_ULTIMATEUSER_NOT_PUBLISHED'),'warning');
        }

	    $params = JComponentHelper::getParams('com_uu');
	    $updateCaching = $params->get('update_caching',false);
        //check new version
        require_once(JPATH_ADMINISTRATOR.'/components/com_uu/liveupdate/liveupdate.php');
        $updateInfo = LiveUpdate::getUpdateInformation(!$updateCaching);
        if ($updateInfo->hasUpdates) {
            JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_UU_NEW_VERSION_RELEASED',$updateInfo->version),'warning');
        }

        // Get current version available
        $this->currentVersion = $updateInfo->extInfo->version;

        //get latest version
        $this->latestVersion = $updateInfo->version;

        $this->updateInfo = $updateInfo;


        $this->getDataUsers();
        $this->getDataUsersGroups();
        $this->getStatitcs();

        $doc = JFactory::getDocument();
        $doc->addScript('components/com_uu/assets/js/Chart.min.js');

        $js = "var progress_msg = '".JText::_('COM_UU_CPANEL_CHECK_PROGRESS')."';";

        $doc->addScriptDeclaration($js);
        $doc->addScript('components/com_uu/assets/js/cpanel.js');


        $this->addToolbar();

        $input = JFactory::getApplication()->input;
        $view = $input->getCmd('view', '');
        UuHelper::addSubmenu($view);

        parent::display($tpl);

    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar()
    {
        require_once JPATH_COMPONENT.'/helpers/uu.php';

        $canDo	= UuHelper::getActions();

        //get component version
        JLoader::import('joomla.filesystem.file');
        $version = 'n/a';
        if (JFile::exists(JPATH_ADMINISTRATOR .'/components/com_uu/uu.xml')) {
            $xml = simplexml_load_file(JPATH_ADMINISTRATOR .'/components/com_uu/uu.xml');
            $version = (string)$xml->version;
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_uu');
        }


        JToolBarHelper::title(JText::_('COM_UU_VIEW_CPANEL_TITLE').' <small>'.$version.'</small>', 'config.png');

    }

    protected function getDataUsers(){
        $doc = JFactory::getDocument();
        $dataUsers = array();
        //$dataUsersLabels = array("'".JText::_('JANUARY')."'", 'February', "March", "April", "May", "June", "July");
        $dataUsersLabels = array("'".JText::_('JANUARY')."'",
                                 "'".JText::_('FEBRUARY')."'",
                                 "'".JText::_('MARCH')."'",
                                 "'".JText::_('APRIL')."'",
                                 "'".JText::_('MAY')."'",
                                 "'".JText::_('JUNE')."'",
                                 "'".JText::_('JULY')."'",
                                 "'".JText::_('AUGUST')."'",
                                 "'".JText::_('SEPTEMBER')."'",
                                 "'".JText::_('OCTOBER')."'",
                                 "'".JText::_('NOVEMBER')."'",
                                 "'".JText::_('DECEMBER')."'");

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $current_year = date("Y");

        $query->select('MONTH(registerDate) MONTH, COUNT(*) COUNT')
              ->from('#__users')
              ->where('YEAR(registerDate)='.$current_year)
              ->group('MONTH(registerDate)')
              ->order('MONTH');

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        foreach ($rows as $row){
            $dataUsers[] = $row->COUNT;
        }


        $js = '    var datausers = {
        labels: ['.implode(",",$dataUsersLabels).'],
        datasets: [
            {
                label: "Users registration",
                fillColor: "rgba(220,220,220,0.5)",
                strokeColor: "rgba(220,220,220,0.8)",
                highlightFill: "rgba(220,220,220,0.75)",
                highlightStroke: "rgba(220,220,220,1)",
                data: ['.implode(",",$dataUsers).']
            }
        ]
    };

       ';
        $doc->addScriptDeclaration($js);

    }

    protected function getDataUsersGroups(){
        $doc = JFactory::getDocument();
        $dataUsersGroups = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $current_year = date("Y");

        $query->select('COUNT(uum.group_id) COUNT,uum.group_id,ug.title')
            ->from($db->quoteName('#__users','u'))
            ->innerJoin($db->quoteName('#__user_usergroup_map','uum').' ON uum.user_id = u.id')
            ->innerJoin($db->quoteName('#__usergroups','ug').' ON ug.id = uum.group_id')
            ->where('YEAR(registerDate)='.(int)$current_year)
            ->group('uum.group_id')
            ->order('uum.group_id');

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        $js = '    var datausersgroups = [';

        $color = array('#C0392B','#9B59B6','#2980B9','#1ABC9C','#27AE60','#F1C40F','#46BFBD','#F7464A','#FDB45C');
        $highlight = array('#E74C3C','#8E44AD','#3498DB','#16A085','#2ECC71','#F39C12','#5AD3D1','#FF5A5E','#FFC870');

        foreach ($rows as $key => $row){
            if ($key > 0){$js .= ',';}
            $js .= '{';
            $js .= 'color: "'.$color[$key % 9].'",';
            $js .= 'highlight: "'.$highlight[$key % 9].'",';
            $js .= 'value:'.$row->COUNT.',';
            $js .= 'label:"'.$row->title.'"';
            $js .= '}';
        }

        $js .= '];';
        $doc->addScriptDeclaration($js);

    }

    protected function getStatitcs(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        //lastyear
        $query->select('count(id)')->from('#__users')->where('YEAR(registerDate) = ' .(int)(date("Y")-1));
        $db->setQuery($query);
        $this->statistics['lastyear'] =$db->loadResult();
        //thisyear
        $query = $db->getQuery(true);
        $query->select('count(id)')->from('#__users')->where('YEAR(registerDate) = ' .(int)date("Y"));
        $db->setQuery($query);
        $this->statistics['thisyear'] =$db->loadResult();
        //last month
        $query = $db->getQuery(true);
        $query->select('count(id)')->from('#__users')->where(' EXTRACT(YEAR_MONTH FROM registerDate ) = EXTRACT(YEAR_MONTH FROM CURDATE( ) - INTERVAL 1 MONTH )');
        $db->setQuery($query);
        $this->statistics['lastmonth'] = $db->loadResult();
        //this month
        $query = $db->getQuery(true);
        $query->select('count(id)')->from('#__users')->where(' EXTRACT(YEAR_MONTH FROM registerDate ) = EXTRACT(YEAR_MONTH FROM CURDATE())');
        $db->setQuery($query);
        $this->statistics['thismonth'] = $db->loadResult();
        //last 7 days
        $query = $db->getQuery(true);
        $query->select('count(id)')->from('#__users')->where('registerDate >= date_sub(curdate(),interval 7 day)');
        $db->setQuery($query);
        $this->statistics['last7days'] = $db->loadResult();
        //yesterday
        $query = $db->getQuery(true);
        $query->select('count(id)')->from('#__users')->where('registerDate between date_sub(curdate(),interval 1 day) and curdate()');
        $db->setQuery($query);
        $this->statistics['yesterday'] = $db->loadResult();
        //today
        $query = $db->getQuery(true);
        $query->select('count(id)')->from('#__users')->where('registerDate >= curdate()');
        $db->setQuery($query);
        $this->statistics['today'] = $db->loadResult();


    }
}