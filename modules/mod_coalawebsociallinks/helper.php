<?php

/**
 * @package     Joomla
 * @subpackage  CoalaWeb Social Links
 * @author      Steven Palmer <support@coalaweb.com>
 * @link        https://coalaweb.com/
 * @license     GNU/GPL V3 or later; https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (c) 2020 Steven Palmer All rights reserved.
 *
 * CoalaWeb Social Links is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');

// Load version.php
$version_php = JPATH_ADMINISTRATOR . '/components/com_coalawebsociallinks/version.php';
if (!defined('COM_CWSOCIALLINKS_VERSION') && JFile::exists($version_php)) {
    include_once $version_php;
}

/**
 * Class ModCoalawebSocialLinksHelper
 */
class ModCoalawebSocialLinksHelper {   

    /* Bookmark functions */

    function getFacebookBookmark($link, $size, $linknofollow, $popup, $appId, $linkText, $shareType) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "Facebook") : '');
        if ($shareType == 'old'){
            $output[] =  '<li>';
            $output[] = '<a class="' . $popup . 'facebook' . $size . '" href="https://www.facebook.com/sharer/sharer.php?u=' . $link . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "Facebook") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
            $output[] = '</li>';
        }else{
            $redirect = JURI::base() . 'media/coalaweb/modules/generic/html/popup-close.html';
            $output[] = '<li>';
            $output[] = '<a class="' . $popup . 'facebook' . $size . '" href="https://www.facebook.com/dialog/share?app_id=' . $appId . '&amp;display=popup&amp;href=' . $link . '&amp;redirect_uri=' . $redirect . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "Facebook") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
            $output[] = '</li>';
        }
        return implode("\n", $output);
    }

    function getTwitterBookmark($title, $link, $size, $linknofollow, $via, $hash, $popup, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "Twitter") : '');
        $tags = $hash ? '&amp;hashtags=' . $hash . '&amp;' : '';
        $output[] = '<li>';
        $output[] = '<a class="' . $popup . 'twitter' . $size . '" href="https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $link . $tags . $via .'" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "Twitter") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getLinkedInBookmark($title, $link, $size, $linknofollow, $popup, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "LinkedIn") : '');
        $output[] = '<li>';
        $output[] = '<a class="' . $popup . 'linkedin' . $size . '" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . $link . '&amp;title=' . $title . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "LinkedIn") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getRedditBookmark($title, $link, $size, $linknofollow, $popup, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "Reddit") : '');
        $output[] = '<li>';
        $output[] = '<a class="' . $popup . 'reddit' . $size . '" href="https://reddit.com/submit?url=' . $link . '&amp;title=' . $title . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "Reddit") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }
    
    function getPinterestBookmark($size, $linknofollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "Pinterest") : '');
        $output[] = '<li>';
        $output[] = '<a class="pinterest' . $size . '" '
            . ' href="//pinterest.com/pin/create/button"'
            . ' data-pin-do="buttonBookmark"'
            . ' data-pin-custom="true"'
            . ' target="_blank" ' . $linknofollow . '>' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getEmailBookmark($title, $link, $size, $linkText, $desc, $siteName) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_EMAIL_BM", "Email") : '');
        
        $body[] = JText::sprintf("COM_CWSOCIALLINKS_MAIL_MSG_SITE", $siteName);
        $body[] = JText::sprintf("COM_CWSOCIALLINKS_MAIL_MSG_TITLE", $title);
        if ($desc && $desc != ''){
            $body[] = JText::sprintf("COM_CWSOCIALLINKS_MAIL_MSG_DESC", $desc);
        }
        $body[] = JText::sprintf("COM_CWSOCIALLINKS_MAIL_MSG_LINK", $link);
        
        $emailBody = implode("%0D%0A", $body);
        
        $output[] = '<li>';
        $output[] = '<a class="gmail' . $size . '" href="mailto:?subject=' . str_replace(' ', '&nbsp;', JText::_("COM_CWSOCIALLINKS_MAIL_MSG_INTRO")) . '&amp;body=' . str_replace(' ', '&nbsp;', $emailBody) . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_EMAIL_BM", "Email") . '" >' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }
    
    function getWhatsappBookmark($title, $link, $size, $linknofollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "WhatsApp") : '');
        $output[] = '<li>';
        $output[] = '<a class="whatsapp' . $size . '" href="whatsapp://send?text='. $title . ': '. $link . '" data-action="share/whatsapp/share" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_BOOKMARK", "WhatsApp") . '" ' . $linknofollow . '>' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    /* Follow Us functions */
    function getFacebookFollow($linkfacebook, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Facebook") : '');
        $output[] = '<li>';
        $output[] = '<a class="'. $popupFollow . 'facebook' . $size . '" href="https://' . $linkfacebook . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Facebook") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getLinkedinFollow($linklinkedin, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Linkedin") : '');
        $output[] = '<li>';
        $output[] = '<a class="' . $popupFollow . 'linkedin' . $size . '" href="https://' . $linklinkedin . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Linkedin") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getTwitterFollow($linktwitter, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Twitter") : '');
        $output[] = '<li>';
        $output[] = '<a class="' . $popupFollow . 'twitter' . $size . '" href="https://' . $linktwitter . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Twitter") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getRssFollow($linkrss, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "RSS") : '');
        $output[] = '<li>';
        $output[] = '<a class="' . $popupFollow . 'rss' . $size . '" href="' . $linkrss . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "RSS") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getMyspaceFollow($linkmyspace, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Myspace") : '');
        $output[] = '<li>';
        $output[] = '<a class="' . $popupFollow . 'myspace' . $size . '" href="https://' . $linkmyspace . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Myspace") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getVimeoFollow($linkvimeo, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Vimeo") : '');
        $output[] = '<li>';
        $output[] = '        <a class="' . $popupFollow . 'vimeo' . $size . '" href="https://' . $linkvimeo . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Vimeo") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getYoutubeFollow($linkyoutube, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Youtube") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'youtube' . $size . '" href="https://' . $linkyoutube . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Youtube") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getDribbleFollow($linkdribbble, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Dribbble") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'dribbble' . $size . '" href="https://' . $linkdribbble . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Dribbble") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getDeviantartFollow($linkdeviantart, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Deviantart") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'deviantart' . $size . '" href="https://' . $linkdeviantart . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Deviantart") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getContactFollow($linkcontact, $linkTargetContact, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_CONTACT_F", "Email") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'gmail' . $size . '" href="' . $linkcontact . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_CONTACT_F", "Email") . '" ' . $linknofollow . ' ' . $linkTargetContact . '>' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getMailFollow($linkmail, $size, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_EMAIL_F", "Email") : '');
        $output[] = '<li style="text-indent: 0px !important;">';
        $output[] = ' <a class="gmail' . $size . '" href="mailto:' . htmlspecialchars($linkmail) . '" title="' . htmlentities(JText::sprintf("MOD_COALAWEBSOCIALLINKS_EMAIL_F", "Email")) . '" rel="nofollow" >' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getEbayFollow($linkebay, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Ebay") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'ebay' . $size . '" href="https://' . $linkebay . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Ebay") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getTuentiFollow($linktuenti, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Tuenti") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'tuenti' . $size . '" href="https://' . $linktuenti . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Tuenti") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getBehanceFollow($linkbehance, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Behance") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'behance' . $size . '" href="https://' . $linkbehance . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Behance") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getDesignmooFollow($linkdesignmoo, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Designmoo") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'designmoo' . $size . '" href="https://' . $linkdesignmoo . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Designmoo") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getFlickrFollow($linkflickr, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Flickr") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'flickr' . $size . '" href="https://' . $linkflickr . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Flickr") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getLastfmFollow($linklastfm, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Last.fm") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'lastfm' . $size . '" href="https://' . $linklastfm . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Last.fm") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getPinterestFollow($linkpinterest, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Pinterest") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'pinterest' . $size . '" href="https://' . $linkpinterest . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Pinterest") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getTumblrFollow($linktumblr, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Tumblr") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'tumblr' . $size . '" href="https://' . $linktumblr . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Tumblr") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getInstagramFollow($linkinstagram, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Instagram") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'instagram' . $size . '" href="https://' . $linkinstagram . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Instagram") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getXingFollow($linkxing, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Xing") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'xing' . $size . '" href="https://' . $linkxing . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Xing") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getSpotifyFollow($linkspotify, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Spotify") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'spotify' . $size . '" href="https://' . $linkspotify . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Spotify") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getBloggerFollow($linkblogger, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Blogger") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'blogger' . $size . '" href="https://' . $linkblogger . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Blogger") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getTripadvisorFollow($linktripadvisor, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Tripadvisor") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'tripadvisor' . $size . '" href="https://' . $linktripadvisor . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Tripadvisor") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getAndroidFollow($linkandroid, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Android") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'android' . $size . '" href="https://' . $linkandroid . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Android") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getGithubFollow($linkgithub, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Github") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'github' . $size . '" href="https://' . $linkgithub . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Github") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getJoomlaFollow($linkjoomla, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Joomla") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'joomla' . $size . '" href="https://' . $linkjoomla . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Joomla") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getYelpFollow($linkyelp, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Yelp") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'yelp' . $size . '" href="https://' . $linkyelp . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Yelp") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getItunesFollow($linkitunes, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Itunes") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'itunes' . $size . '" href="https://' . $linkitunes . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Itunes") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    function getWeiboFollow($linkweibo, $size, $linknofollow, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Weibo") : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'weibo' . $size . '" href="https://' . $linkweibo . '" title="' . JText::sprintf("MOD_COALAWEBSOCIALLINKS_FOLLOW", "Weibo") . '" ' . $linknofollow . ' target="_blank">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    /* Follow Us Custom functions */
    function getCustomoneFollow($linkcustomone, $linkTargetCustomone, $size, $linknofollow, $textcustomone, $popupFollow, $linkText) {
        $linkTextOn = ($linkText ? $textcustomone : '');
        $output[] = '<li>';
        $output[] = ' <a class="' . $popupFollow . 'customone' . $size . '" href="' . $linkcustomone . '" title="' . $textcustomone . '" ' . $linknofollow . ' ' . $linkTargetCustomone . ' style="width: ' . $size . 'px;height: ' . $size . 'px;">' . $linkTextOn . '</a>';
        $output[] = '</li>';
        return implode("\n", $output);
    }

    //Create Custom style
    public static function getCustomoneStyle($themes_icon, $iconcustomone, $size, $module_unique_id) {
        $doc = JFactory::getDocument();
        $styles = "#" . $module_unique_id . " .cw-social-mod-icons-" . $themes_icon . "  a.customone" . $size . " {background:url(" . $iconcustomone . ") 0 0 no-repeat !important;}";
        $styles .= "#" . $module_unique_id . " .cw-social-mod-icons-".$themes_icon ." a.customone" . $size . ":hover {background:url(" . $iconcustomone . ") 0 0 no-repeat !important;}";
        $doc->AddStyledeclaration($styles);
    }

    /**
     * Check dependencies
     *
     * @return array
     */
    public static function checkDependencies() {

        $langRoot = 'MOD_COALAWEBSOCIALLINKS';

        if(!defined('COM_CWSOCIALLINKS_VERSION')){
            $result = [
                'ok' => false,
                'type' => 'warning',
                'msg' => JText::_($langRoot . '_FILE_MISSING_MESSAGE')
            ];

            return $result;

        }

        /**
         * Gears dependencies
         */
        $version = (COM_CWSOCIALLINKS_MIN_GEARS_VERSION); // Minimum version

        // Classes that are needed
        $assets = [
            'mobile' => true,
            'count' => true,
            'tools' => true,
            'latest' => false
        ];

        // Check if Gears dependencies are meet and return result
        $results = self::checkGears($version, $assets, $langRoot);

        if($results['ok'] == false){
            $result = [
                'ok' => $results['ok'],
                'type' => $results['type'],
                'msg' => $results['msg']
            ];

            return $result;
        }


        // Lets use our tools class from Gears
        $tools = new CwGearsHelperTools();

        /**
         * File and folder dependencies
         * Note: JPATH_ROOT . '/' prefix will be added to file and folder names
         */
        $filesAndFolders = array(
            'files' => array(
            ),
            'folders' => array(
            )
        );

        // Check if they are available
        $exists = $tools::checkFilesAndFolders($filesAndFolders, $langRoot);

        // If any of the file/folder dependencies fail return
        if($exists['ok'] == false){
            $result = [
                'ok' => $exists['ok'],
                'type' => $exists['type'],
                'msg' => $exists['msg']
            ];

            return $result;
        }

        /**
         * Extension Dependencies
         * Note: Plugins always need to be entered in the following format plg_type_name
         */
        $extensions = array(
            'components' => array(
                'com_coalawebsociallinks'
            ),
            'modules' => array(
            ),
            'plugins' => array(
                'plg_system_cwfacebookjs'
            )
        );

        // Check if they are available
        $extExists = $tools::checkExtensions($extensions, $langRoot);

        // If any of the extension dependencies fail return
        if($extExists['ok'] == false){
            $result = [
                'ok' => $extExists['ok'],
                'type' => $extExists['type'],
                'msg' => $extExists['msg']
            ];

            return $result;
        }

        // No problems? return all good
        $result = ['ok' => true];

        return $result;
    }

    /**
     * Check Gears dependencies
     *
     * @param $version - minimum version
     * @param array $assets - list of required assets
     * @param $langRoot
     * @return array
     */
    public static function checkGears($version, $assets = array(), $langRoot)
    {
        jimport('joomla.filesystem.file');

        // Load the version.php file for the CW Gears plugin
        $version_gears_php = JPATH_SITE . '/plugins/system/cwgears/version.php';
        if (!defined('PLG_CWGEARS_VERSION') && JFile::exists($version_gears_php)) {
            include_once $version_gears_php;
        }

        // Is Gears installed and the right version and published?
        if (
            JPluginHelper::isEnabled('system', 'cwgears') &&
            JFile::exists($version_gears_php) &&
            version_compare(PLG_CWGEARS_VERSION, $version, 'ge')
        ) {
            // Base helper directory
            $helperDir = JPATH_SITE . '/plugins/system/cwgears/helpers/';

            // Do we need the mobile detect class?
            if ($assets['mobile'] == true && !class_exists('Cwmobiledetect')) {
                $mobiledetect_php = $helperDir . 'cwmobiledetect.php';
                if (JFile::exists($mobiledetect_php)) {
                    JLoader::register('Cwmobiledetect', $mobiledetect_php);
                } else {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_NOGEARSPLUGIN_HELPER_MESSAGE')
                    ];
                    return $result;
                }
            }

            // Do we need the load count class?
            if ($assets['count'] == true && !class_exists('CwGearsHelperLoadcount')) {
                $loadcount_php = $helperDir . 'loadcount.php';
                if (JFile::exists($loadcount_php)) {
                    JLoader::register('CwGearsHelperLoadcount', $loadcount_php);
                } else {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_NOGEARSPLUGIN_HELPER_MESSAGE')
                    ];
                    return $result;
                }
            }

            // Do we need the tools class?
            if ($assets['tools'] == true && !class_exists('CwGearsHelperTools')) {
                $tools_php = $helperDir . 'tools.php';
                if (JFile::exists($tools_php)) {
                    JLoader::register('CwGearsHelperTools', $tools_php);
                } else {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_NOGEARSPLUGIN_HELPER_MESSAGE')
                    ];
                    return $result;
                }
            }

            // Do we need the latest class?
            if ($assets['latest'] == true && !class_exists('CwGearsLatestversion')) {
                $latest_php = $helperDir . 'latestversion.php';
                if (JFile::exists($latest_php)) {
                    JLoader::register('CwGearsLatestversion', $latest_php);
                } else {
                    $result = [
                        'ok' => false,
                        'type' => 'warning',
                        'msg' => JText::_($langRoot . '_NOGEARSPLUGIN_HELPER_MESSAGE')
                    ];
                    return $result;
                }
            }
        } else {
            // Looks like Gears isn't meeting the requirements
            $result = [
                'ok' => false,
                'type' => 'warning',
                'msg' => JText::sprintf($langRoot . '_NOGEARSPLUGIN_CHECK_MESSAGE', $version)
            ];
            return $result;
        }

        // Set up our response array
        $result = [
            'ok' => true,
            'type' => '',
            'msg' => ''
        ];

        // Return our result
        return $result;

    }
}