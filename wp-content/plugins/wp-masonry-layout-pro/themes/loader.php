<?php
// LAYOUT SETTINGS
$layoutSlug 	= $shortcodeData['wmlo_layout_theme'];
$layoutSettings	= get_option('wmlo_theme_settings_'.$layoutSlug);
$layoutSettings	= json_decode($layoutSettings, true);
global $wp_query;
query_posts($query_arg);
if (have_posts()):
	ob_start();
	while (have_posts()) : the_post(); 
		include($themeLayout.'/layout.php');
	endwhile; 
	$returnData['status']			= 'ok';
	$returnData['elements'] 		= ob_get_contents();
	$returnData['max_pages']		= $wp_query->max_num_pages;
	ob_end_clean();
	wp_reset_query();
else :
	$returnData['status']			= 'no_posts';
	$returnData['message'] 			= "WP Masonry Posts : No More Posts.";
endif;
?>