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
        // $query->select('COUNT(a.id) AS adnets_count');
        // $query->from($db->quoteName('#__ofrs_ad_network', 'a'));
        // $query->where("a.published = 1");
        // // Join to offers
        // $query->join('LEFT OUTER', ($db->quoteName('#__ofrs_offer', 'b')) . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.ad_network_id') . ')');
        // $query->where("b.published = 1");
        // $query->group('a.id');
        $query->select('network_count AS adnets_count FROM ofrs_statistics');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return count($results);
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
        
        // Get data
        // $query->select('COUNT(*) AS offers_count');
        // $query->from($db->quoteName('#__ofrs_offer', 'a'));
        // $query->where("a.published = 1");
        $query->select('offer_count AS offers_count FROM ofrs_statistics');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results[0]->offers_count;
    }
}