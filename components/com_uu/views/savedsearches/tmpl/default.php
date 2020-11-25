<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>

<article>
<section><a><b>Saved Searches</b></a></section>
<section><a>-------------------</a></section>
</article>
 
<?php
    foreach ($this->items as $item):
?> 

        <article class="row Rtable-row vertical-center-sm" id="<?= $id; ?>">
			<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><a href="#" onclick="alert('Да се направи.'); return true;"><?= $item->saved_search_name ?></a></section>
			<section class="col-xs-12 col-sm-8 col-md-3 col-no-gutters vertical-center-sm"><img id="ssr<?= $item->saved_search_id ?>" class="ssr" src="/images/icons/delete_button.png"></section>
        </article>
 
<?php 
    endforeach;
?>