<?php
//add Preloader
if ($this['config']->get('preloader', false)) {
	if (function_exists("get_template_directory_uri")){
		//wordpress
		$physicpath = get_template_directory_uri().'/';
	}else {
		//joomla
		$app    = JFactory::getApplication();
		$physicpath = 'templates/'.$app->getTemplate().'/';
	}
	//add JS
	$loader = "window.onload = function(){
		setTimeout(function () {
			jQuery('.sp-pre-loader').fadeOut();}, 1000); };";
	printf("<script>%s</script>\n", $loader);

	//add loader
	$output="";
	$output .= '<div class="sp-pre-loader">';
	$animation = $this['config']->get('preloader_animation');
	if ($animation == 'double-loop') {
		// Bubble Loop loader
		$output .= '<div class="sp-loader-bubble-loop"></div>';
	} elseif ($animation == 'wave-two') {
		// Audio Wave 2 loader
		$output .= '<div class="wave-two-wrap">';
		$output .= '<ul class="wave-two">';
		$output .= '<li></li>';
		$output .= '<li></li>';
		$output .= '<li></li>';
		$output .= '<li></li>';
		$output .= '<li></li>';
		$output .= '<li></li>';
		$output .= '</ul>'; //<!-- /.Audio Wave 2 loader -->
		$output .= '</div>'; // <!-- /.wave-two-wrap -->

	} elseif ($animation == 'audio-wave') {
		// Audio Wave loader
		$output .= '<div class="sp-loader-audio-wave"> </div>';
	} elseif ($animation == 'circle-two') {
		// Circle two Loader
		$output .= '<div class="circle-two">';
		$output .= '<span></span>';
		$output .= '</div>'; // /.Circle two loader
	} elseif ($animation == 'clock') {
		//Clock loader
		$output .= '<div class="sp-loader-clock"></div>';
	} elseif ($animation == 'logo') {
		//removed
	} else {
		// Circle loader
		$output .= '<div class="sp-loader-circle"></div>'; // /.Circular loader
	}
	$output .= '</div>'; // /.Pre-loader
	echo $output;
}

?>
