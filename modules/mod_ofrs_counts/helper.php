<?php

/**
 * Helper class for Count module.
 * 
 * @package    SMIG Offers
 * @subpackage Modules
 */
class ModOfrsCountsHelper
{
    
    /**
     * Retrieve and return count of published networks
     * @return unknown
     */
    public static function getAdNetworksCount()
    {
        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // // Get data
        $query->select('COUNT(*) AS adnets_count');
        $query->from($db->quoteName('#__ofrs_ad_network', 'a'));
        $query->where("a.published = 1");
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results[0]->adnets_count;
    }
    
    
    /**
     * Retrieve and returns count of published offers
     * @return unknown
     */
    public static function getOffersCount() {
        // Get a db connection.
        $db = JFactory::getDbo();
        
        // Create a new query object.
        $query = $db->getQuery(true);
        
        // // Get data
        $query->select('COUNT(*) AS offers_count');
        $query->from($db->quoteName('#__ofrs_offer', 'a'));
        $query->where("a.published = 1");
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results[0]->offers_count;
    }
}