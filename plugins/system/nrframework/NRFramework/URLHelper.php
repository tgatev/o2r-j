<?php 

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2020 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework;

use NRFramework\URL;

defined('_JEXEC') or die('Restricted access');

class URLHelper
{
    /**
     * Searches the given HTML for all external links and appends the affiliate paramter aff=id to every link based on an affiliate list.
     *
     * @param   string  $text           The html to search for external links
     * @param   array   $affiliates     A key value array: domain name => affiliate parameter
     *
     * @return  string
     */
    public static function replaceAffiliateLinks($text, $affiliates, $factory = null)
    {
        if (empty($text))
        {
            return $text;
        }

        $factory = $factory ? $factory : new \NRFramework\Factory();

		libxml_use_internal_errors(true);
		$dom = new \DOMDocument;
        $dom->loadHTML($text);
        
        $links = $dom->getElementsByTagName('a');

        foreach ($links as $link)
        {
            $linkHref = $link->getAttribute('href');

            if (empty($linkHref))
            {
                continue;
            }

            $url = new URL($linkHref, $factory);

            if ($url->isInternal())
            {
                continue;
            }

            $domain = $url->getDomainName();

            if (!array_key_exists($domain, $affiliates))
            {
                continue;
            }

            $urlInstance = $url->getInstance();
            $urlQuery = $urlInstance->getQuery();
            $affQuery = $affiliates[$domain];

            // If both queries are the same, skip the link tag
            if ($urlQuery === $affQuery)
            {
                continue;
            }

            if (empty($urlQuery))
            {
                $urlInstance->setQuery($affQuery);
            } else 
            {
                parse_str($urlQuery, $params);
                parse_str($affQuery, $params_);
                $params_new = array_merge($params, $params_);
                $urlInstance->setQuery(http_build_query($params_new));
            }

            $newURL = $urlInstance->toString();

            if ($newURL === $linkHref)
            {
                continue;
            }

            $link->setAttribute('href', $newURL);
        }

        return $dom->saveHtml();
    }
}