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
<?php if ($myparams['moduleClassSfx']) : ?>
    <div class="custom<?php echo $myparams['moduleClassSfx'] ?>">
    <?php endif ?>
    <?php if (!$myparams['twitterUser']) : ?>
        <div id="cw-mod-tweet-<?php echo $uniqueId ?>">
            <div class="cw-tweet-msg">
                <span class="error">
                    <?php echo JTEXT::_('MOD_CWTWEET_NO_USER_MSG') ?>
                </span>
            </div>
        </div>
    <?php else : ?>
        <div class="cwt" id="<?php echo $uniqueId ?>">
            <div class="<?php echo $myparams['uikitPrefix']; ?>-grid" data-<?php echo $myparams['uikitPrefix']; ?>-grid-margin="">
                <ul id="<?php echo $uniqueId ?>Set" class="<?php echo $myparams['uikitPrefix']; ?>-width-1-1"></ul>
            </div>
        </div>
    <?php endif ?>
    <?php if ($myparams['moduleClassSfx']) : ?>
    </div>
<?php endif ?>
<?php if ($myparams['twitterUser']) : ?>
<script>
    var <?php echo $uniqueId ?> = {
        "profile": {"screenName": '<?php echo $myparams['twitterUser'] ?>'},
        "dataOnly": true,
        "maxTweets": <?php echo $myparams['maxTweets'] ?>,
        "customCallback": <?php echo $uniqueId ?>Get
    }

    twitterFetcher.fetch(<?php echo $uniqueId ?>);

    function <?php echo $uniqueId ?>Get(tweets) {
        var element = document.getElementById('<?php echo $uniqueId ?>Set');
        var html = '';

        for (var i = 0, lgth = tweets.length; i < lgth; i++) {
            var tweetObject = tweets[i];
            var showTitle = <?php echo $myparams['showTitle']; ?>;

            html += '<li class="<?php echo $myparams['uikitPrefix']; ?>-panel <?php echo $myparams['panelStyle']; ?>">'
                    + (showTitle ? '<?php echo $myparams['titleOpen']; ?>' + tweetObject.permalinkURL + '<?php echo $myparams['titleClose']; ?>' : '')
                    + '<?php echo $myparams['conOpen']; ?>' + tweetObject.tweet + '<?php echo $myparams['conClose']; ?>';

            html += '</li>';
        }
        // Injects our newly created HTML
        jQuery(element).prepend(html);
        // Removes unneeded spans tags that mess with formatting
        jQuery(element).find('span.u-hiddenVisually').remove();
    }
</script>
<?php endif ?>


