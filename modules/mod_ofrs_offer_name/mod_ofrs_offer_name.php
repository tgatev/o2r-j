<?php

/**
 * SMIG Offers Count module. Entry point
 * 
 * @package    SMIG Offers
 * @subpackage Modules
 */

// No direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';
use Joomla\CMS\Factory;
$input = Factory::getApplication()->input;
$view = $input->get('view', null);
$name = '';
switch ($view){
    case 'offer' : {
        $name = ModOfrsItemName::getOfferName( $input->get('id'));
        break;
    }
    case 'adnet' : {
        $name = ModOfrsItemName::getAdnetName( $input->get('id'));
        break;
    }
    case 'profile' : {
        $name = "Edit Profile";
        break;
    }
    case 'offeralerts' : {
        $name = "Offer Alerts";
        break;
    }
    case 'subscriptions' : {
        $name = "Subscriptions";
        break;
    }
    case 'article' : {
//      http://134.122.77.73/index.php?option=com_content&view=article&id=6&Itemid=1118
        $name = ModOfrsItemName::getArticleName( $input->get('id'));

        break;
    }
}

require JModuleHelper::getLayoutPath('mod_ofrs_offer_name');