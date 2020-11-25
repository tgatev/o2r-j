<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');


class UuModelCpanel extends JModelAdmin
{

    /**
     * Method to get the record form.
     *
     * @param	array	$data		An optional array of data for the form to interogate.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	JForm	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Initialise variables.
        $app	= JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_uu.cpanel', 'cpanel', array('control' => 'jform', 'load_data' => $loadData));


        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function updateDownloadId(){

        // For joomla versions < 3.1 (no extra query available)
        if (version_compare(JVERSION, '3.1', 'lt')) {
            return;
        }

        $db = $this->getDbo();
        // Get current extension ID
        $extension_id = $this->getExtensionId();
        if (!$extension_id)
        {
            return;
        }

        $component = JComponentHelper::getComponent('com_uu');
        $dlid = $component->params->get('downloadid', '');

        if (empty($dlid)) return;

        // store only valid downloadid
        if (!preg_match('/^([0-9]{1,}:)?[0-9a-f]{32}$/i', $dlid)) return;

        $extra_query = "'dlid=$dlid'";

        // Get the update sites for current extension
        $query = $db->getQuery(true)
            ->select($db->qn('update_site_id'))
            ->from($db->qn('#__update_sites_extensions'))
            ->where($db->qn('extension_id') . ' = ' . $db->q($extension_id));
        $db->setQuery($query);
        $updateSiteIDs = $db->loadColumn(0);

        // Loop through all update sites
        foreach ($updateSiteIDs as $id)
        {
            $query = $db->getQuery(true)
                ->update('#__update_sites')
                ->set('extra_query = '.$extra_query)
                ->where('update_site_id = "'.$id.'"');
            $db->setQuery($query);
            $db->execute();
        }
    }

    /**
     * Get extension Id
     *
     * @params void
     *
     * @return  extension id
     *
     * @since 1.1.7
     *
     */
    public function getExtensionId()
    {
        $db = $this->getDbo();
        $extensionType = 'package';
        $extensionElement = 'pkg_uu';
        // Get current extension ID
        $query = $db->getQuery(true)
            ->select($db->qn('extension_id'))
            ->from($db->qn('#__extensions'))
            ->where($db->qn('type') . ' = ' . $db->q($extensionType))
            ->where($db->qn('element') . ' = ' . $db->q($extensionElement));
        $db->setQuery($query);
        $extension_id = $db->loadResult();
        if (empty($extension_id))
        {
            return 0;
        }
        else
        {
            return $extension_id;
        }
    }
}