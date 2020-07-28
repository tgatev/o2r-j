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

?>
<?php if ($moduleClassSfx) : ?>
    <div class="<?php echo $moduleClassSfx ?>">
<?php endif ?>
<div class="cw-sl-width-<?php echo $module_width ?>" id="<?php echo $module_unique_id ?>">
    <?php if ($display_bm_sec) : ?>
        <div class="cw-social-mod">
            <?php if (($display_borders) && ($load_layout_css)) : ?>
                <div class="cw-social-mod-bookmark" style="border:<?php echo $border_width . 'px solid #' . $border_color_bm; ?>" >
                <?php else : ?>
                    <div class="cw-social-mod-bookmark">
                    <?php endif; ?>

                    <?php if ($display_title_bm) : ?>
                        <h<?php echo $title_format ?> style="color:<?php echo '#' . $title_color_bm ?>" class="<?php echo $title_align ?>">
                            <?php echo $title_bm; ?>
                        </h<?php echo $title_format ?>>
                    <?php endif; ?>
                        
                    <?php if ($display_text_bm) : ?>
                            <?php echo $text_bm; ?>
                    <?php endif; ?>
                        
                    <div class="<?php echo $icon_align ?>">

                        <ul class="cw-social-mod-icons-<?php echo $themes_icon ?> <?php echo $iconEffect ?>">
                            <?php

                            if ($params->get("display_facebook_bm")) {
                                echo $help->getFacebookBookmark($link, $size, $linknofollow, $popup, $appId, $linkText, $shareType);
                            }

                            if ($params->get("display_twitter_bm")) {
                                $via = substr($comParams->get('twitter_via', ''), 1);
                                $via = ($via ? '&amp;via=' . $via : '');
                                $hash = $comParams->get('twitter_hash', '');
                                $popupTweet = ($params->get('include_twitter_js', 1) ? '' : $popup);
                                echo $help->getTwitterBookmark($title, $link, $size, $linknofollow, $via, $hash, $popupTweet, $linkText);
                            }
                            if ($params->get("display_linkedin_bm")) {
                                echo $help->getLinkedInBookmark($title, $link, $size, $linknofollow, $popup, $linkText);
                            }
                            if ($params->get("display_reddit_bm")) {
                                echo $help->getRedditBookmark($title, $link, $size, $linknofollow, $popup, $linkText);
                            }
                            if ($params->get("display_pinterest_bm")) {
                                echo $help->getPinterestBookmark($size, $linknofollow, $linkText);
                            }
                            if ($params->get("display_email_bm")) {
                                echo $help->getEmailBookmark($title, $link, $size, $linkText, $desc, $siteName);
                            }
                            if ($mobile && $params->get("display_whatsapp_bm")) {
                                echo $help->getWhatsappBookmark($title, $link, $size, $linknofollow, $linkText);
                            }
                            
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($display_f_sec) : ?>
            <div class="cw-social-mod">
                <?php if (($display_borders) && ($load_layout_css)) : ?>
                    <div class="cw-social-mod-follow" style="border:<?php echo $border_width . 'px solid #' . $border_color_f; ?>" >
                    <?php else : ?>
                        <div class="cw-social-mod-follow">
                        <?php endif; ?>
                            
                        <?php if ($display_title_f): ?>
                            <h<?php echo $title_format ?> style="color:<?php echo '#' . $title_color_f ?>" class="<?php echo $title_align ?>">
                                <?php echo $title_f; ?>
                            </h<?php echo $title_format ?>>
                        <?php endif; ?>
                            
                        <?php if ($display_text_f) : ?>
                            <?php echo $text_f; ?>
                        <?php endif; ?>
                            
                        <div class="<?php echo $icon_align ?>">

                            <ul class="cw-social-mod-icons-<?php echo $themes_icon ?> <?php echo $iconEffect ?>">
                                <?php
                                if ($params->get("display_facebook_f")) {
                                    echo $help->getFacebookFollow($linkfacebook, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_linkedin_f")) {
                                    echo $help->getLinkedinFollow($linklinkedin, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_twitter_f")) {
                                    echo $help->getTwitterFollow($linktwitter, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_rss_f")) {
                                    echo $help->getRssFollow($linkrss, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_myspace_f")) {
                                    echo $help->getMyspaceFollow($linkmyspace, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_vimeo_f")) {
                                    echo $help->getVimeoFollow($linkvimeo, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_youtube_f")) {
                                    echo $help->getYoutubeFollow($linkyoutube, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_dribbble_f")) {
                                    echo $help->getDribbleFollow($linkdribbble, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_deviantart_f")) {
                                    echo $help->getDeviantartFollow($linkdeviantart, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_contact_f")) {
                                    echo $help->getContactFollow($linkcontact, $linkTargetContact, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_mail_f")) {
                                    echo $help->getMailFollow($linkmail, $size, $linknofollow, $linkText);
                                }
                                if ($params->get("display_ebay_f")) {
                                    echo $help->getEbayFollow($linkebay, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_tuenti_f")) {
                                    echo $help->getTuentiFollow($linktuenti, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_behance_f")) {
                                    echo $help->getBehanceFollow($linkbehance, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_designmoo_f")) {
                                    echo $help->getDesignmooFollow($linkdesignmoo, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_flickr_f")) {
                                    echo $help->getFlickrFollow($linkflickr, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_lastfm_f")) {
                                    echo $help->getLastfmFollow($linklastfm, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_pinterest_f")) {
                                    echo $help->getPinterestFollow($linkpinterest, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_tumblr_f")) {
                                    echo $help->getTumblrFollow($linktumblr, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_instagram_f")) {
                                    echo $help->getInstagramFollow($linkinstagram, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_xing_f")) {
                                    echo $help->getXingFollow($linkxing, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_spotify_f")) {
                                    echo $help->getSpotifyFollow($linkspotify, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_tripadvisor_f")) {
                                    echo $help->getTripadvisorFollow($linktripadvisor, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_blogger_f")) {
                                    echo $help->getBloggerFollow($linkblogger, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_android_f")) {
                                    echo $help->getAndroidFollow($linkandroid, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_github_f")) {
                                    echo $help->getGithubFollow($linkgithub, $size, $linknofollow, $popupFollow, $linkText);
                                }
                               if ($params->get("display_yelp_f")) {
                                    echo $help->getYelpFollow($linkyelp, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_joomla_f")) {
                                    echo $help->getJoomlaFollow($linkjoomla, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_weibo_f")) {
                                    echo $help->getWeiboFollow($linkweibo, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_itunes_f")) {
                                    echo $help->getItunesFollow($linkitunes, $size, $linknofollow, $popupFollow, $linkText);
                                }
                                if ($params->get("display_customone_f")) {
                                    echo $help->getCustomoneFollow($linkcustomone, $linkTargetCustomone, $size, $linknofollow, $textcustomone, $popupFollow, $linkText);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
<?php if ($moduleClassSfx) : ?>
    </div>
<?php endif ?>