<?php

/**
 * Helper class for Count module.
 * 
 * @package    SMIG Offers
 * @subpackage Modules
 */
class ModOfrsItemName
{

    /**
     * Retrieve and return Article Title
     *
     * @param null|int $id
     * @return mixed
     */
    public static function getArticleName($id = null)
    {
        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        $db->setQuery('SELECT id,title FROM #__content WHERE id ='.$id );
        $results = $db->loadObjectList();
        return $results[0]->title;
    }


    /**
     * Retrieve and return Network Name
     *
     * @param null|int $id
     * @return mixed
     */
    public static function getAdnetName($id = null)
    {
        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // // Get data
        $query->select('name');
        $query->from($db->quoteName('#__ofrs_ad_network', 'a'));
        $query->where("a.published = 1");
        $query->andWhere("a.id = ".$id);
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results[0]->name;
    }
    
    
    /**
     * Retrieve and returns Offer Name
     *
     * @param null|int $id
     * @return mixed
     * @return unknown
     */
    public static function getOfferName($id = null) {
        // Get a db connection.
        $db = JFactory::getDbo();
        
        // Create a new query object.
        $query = $db->getQuery(true);
        
        // // Get data
        $query->select('name');
        $query->from($db->quoteName('#__ofrs_offer', 'a'));
        $query->where("a.published = 1");
        $query->andWhere("a.id = ".$id);
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results[0]->name;
    }
}