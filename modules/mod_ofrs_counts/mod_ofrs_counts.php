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

$adNetCnt = ModOfrsCountsHelper::getAdNetworksCount();
$offerCnt = ModOfrsCountsHelper::getOffersCount();

require JModuleHelper::getLayoutPath('mod_ofrs_counts');