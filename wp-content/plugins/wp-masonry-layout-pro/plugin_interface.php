<?php 
add_action('admin_menu', 'wmlp_create_menu');
add_action("admin_print_scripts", 'wmlp_adminjslibs');
add_action("admin_print_styles", 'wmlp_adminCsslibs');
add_action('wp_enqueue_scripts', 'wmlp_client_js_css');
add_action('plugins_loaded', 'wmlp_update_check');

function wmlp_create_menu() { // Create menu for the plugin
	add_menu_page( 'WP Masonry Posts', 'WP Masonry', 'manage_options', 'wmlp_shortcodes', 'wmlp_shortcodes', '');
	add_submenu_page( 'wmlp_shortcodes', 'Shortcodes', 'Shortcodes', 'manage_options', 'wmlp_shortcodes', 'wmlp_shortcodes');
	add_submenu_page( 'wmlp_shortcodes', 'Layout Themes', 'Layout Themes', 'manage_options', 'wmlp_layout_themes', 'wmlp_layout_themes');
	add_submenu_page( 'wmlp_shortcodes', 'Documentation', 'Documentation', 'manage_options', 'wmlp_documentation', 'wmlp_documentation');
	add_submenu_page( 'wmlp_shortcodes', 'License', 'License', 'manage_options', 'wmlp_license', 'wmlp_license');
	add_submenu_page( '', 'Layout Settings', 'Layout Settings', 'manage_options', 'wmlp_layout_settings', 'wmlp_layout_settings');
}

function wmlp_adminjslibs(){ // Load needed js 
	wp_register_script('wmlp_validate_js',plugins_url("wp-masonry-layout-pro/js/jquery.validate.min.js"));
	wp_enqueue_script('wmlp_validate_js');
	
	wp_register_script('wmlp_color_js',plugins_url("wp-masonry-layout-pro/js/colorpicker/jscolor.js"));		
	wp_enqueue_script('wmlp_color_js');
}

function wmlp_adminCsslibs(){ // Load needed css
	wp_register_style('wmlp_admin_style', plugins_url('wp-masonry-layout-pro/css/wmlc_admin.css'));
    wp_enqueue_style('wmlp_admin_style');	
}

function wmlp_client_js_css(){
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'masonry' );
	wp_register_script('wmljs',plugins_url("wp-masonry-layout-pro/js/wmljs.js"));		
	wp_enqueue_script('wmljs');
	
	
	/* FOR PRO VERSION ONLY */
	wp_register_script('wmlp_infinity_scroll',plugins_url("wp-masonry-layout-pro/js/jquery.infinitescroll.min.js"));
	wp_enqueue_script('wmlp_infinity_scroll');
	
	wp_register_script('wmlp_lightbox_js',plugins_url("wp-masonry-layout-pro/js/colorbox/jquery.colorbox-min.js"));
	wp_enqueue_script('wmlp_lightbox_js');
	
	wp_register_style('wmlp_lightbox_css', plugins_url('wp-masonry-layout-pro/js/colorbox/colorbox.css'));
    wp_enqueue_style('wmlp_lightbox_css');
	
	wp_register_script('wmlp_lazy_load_js',plugins_url("wp-masonry-layout-pro/js/jquery.lazyload.min.js"));
	wp_enqueue_script('wmlp_lazy_load_js');
	
	/* EOF FOR PRO VERSION ONLY */
	
	wp_register_style('wmlp_client_style', plugins_url('wp-masonry-layout-pro/css/wmlc_client.css'));
    wp_enqueue_style('wmlp_client_style');
	
	wp_register_style('wmlp_icon_style', plugins_url('wp-masonry-layout-pro/icons/style.css'));
    wp_enqueue_style('wmlp_icon_style');
}

function wmlp_activate(){ // When plugin gets activated or updated.
	$primaryKey = get_option('wmlo_primary_key');
	if (empty($primaryKey)){
		update_option('wmlo_primary_key','1');
	}
	wmlp_write_theme_settings();
	wmlp_rewrite_taxonomy_data();
}

function wmlp_fill_up_form($fillUpData, $fieldkey, $selectOptionValue = ''){
	if (!empty($fillUpData)){
		if (array_key_exists($fieldkey,$fillUpData)){
			if (empty($selectOptionValue)){
				return $fillUpData[$fieldkey];
			} else {
				if ($fillUpData[$fieldkey] == $selectOptionValue){
					return 'selected="selected"';
				} else {
					return '';
				}
			}
		}
	}
	return '';
}

function wmlp_shortcodes(){ // Main page
	include('includes/admin/common/wmlp_header.php');
	include('includes/admin/shortcode/wmlp_shortcodes.php');
	include('includes/admin/shortcode/wmlp_add_edit_shortcode.php');
	include('includes/admin/common/wmlp_footer.php');
}

function wmlp_documentation(){ // Main page
	include('includes/admin/common/wmlp_header.php');
	include('includes/admin/documentation/wmlp_documentation.php');
	include('includes/admin/common/wmlp_footer.php');
}

// SHORTCODE
add_shortcode( 'wmls', 'wmlp_shortcode');
function wmlp_shortcode($atts){
	if (array_key_exists('id',$atts)){ // Check if shortcode ID is passed
		$shortcodeId = $atts['id'];
		if (!empty($shortcodeId)){ // Check if Id is not empty
			$shortcodesRawData 	= get_option('wmlo_shortcodes_data');
			$shortcodesData		= json_decode($shortcodesRawData, true);
			if (array_key_exists($shortcodeId, $shortcodesData)){ // Check if requested shortcode is in our record.
				$shortcodeData	= $shortcodesData[$shortcodeId];
				$themeLayout	= wmlp_check_layout_moved($shortcodeData['wmlo_layout_theme'], 'themes/');
				
				// CHECK IF IT IS GENERIC SHORTCODE
				/*
				if ($shortcodeData['wmlo_is_generic'] == 'yes'){
					global $wp_query;
					$defaultWPQueryForGeneric = $wp_query->query;					
				}*/
				
				// GET THEME SETTINGS
				$layoutSlug 	= $shortcodeData['wmlo_layout_theme'];
				$layoutSettings	= get_option('wmlo_theme_settings_'.$layoutSlug);
				$layoutSettings	= json_decode($layoutSettings, true);
				
				ob_start();
				include($themeLayout.'/style.php');
				include('includes/client/wmlp_container.php');
				$masonryContainerOutput = ob_get_clean();
				return $masonryContainerOutput;
	
			} else {
				echo "WP Masonry Posts : Couldn't find shortcode in our record.";
			}
		} else {
			return 'WP Masonry Posts : Shortcode ID Empty.';	
		}
	} else {
		return 'WP Masonry Posts : Shortcode ID Undefined.';
	}
}

// AJAX HANDELR
add_action("wp_ajax_nopriv_wmlp_load_posts", "wmlp_ajax_load_posts");
add_action("wp_ajax_wmlp_load_posts", "wmlp_ajax_load_posts");
function wmlp_ajax_load_posts(){
	global $randSeed;
	$returnData			= array();
	$shortcodeId 		= $_GET['shortcodeId'];
	$pageNumber 		= $_GET['pageNumber'];
	$randSeed			= $_GET['randSeed'];
	$shortcodesRawData 	= get_option('wmlo_shortcodes_data');
	$shortcodesData		= json_decode($shortcodesRawData, true);
	if (array_key_exists($shortcodeId, $shortcodesData)){ // Check if requested shortcode is in our record.
		$shortcodeData	= $shortcodesData[$shortcodeId];
		$themeLayout	= wmlp_check_layout_moved($shortcodeData['wmlo_layout_theme']);
		
		$query_arg = array(
					   'posts_per_page'	=> $shortcodeData['wmlo_post_count'],
					   'post_status'	=> 'publish'
					   );
		
		if ($shortcodeData['wmlo_is_generic'] != 'yes'){
			$query_arg['post_type']		= $shortcodeData['wmlo_post_type'];

			/*
			if (($shortcodeData['wmlo_post_type'] == 'post') && ($shortcodeData['wmlo_post_category'] > 0)){ // If post type is post and category is selected
				$query_arg['cat']	= wmlp_wpml_icl_object_id($shortcodeData['wmlo_post_category'], 'category');
			}
			
			if (($shortcodeData['wmlo_post_type'] == 'product') && ($shortcodeData['wmlo_product_category'] > 0)){ // If post type is post and category is selected
				$product_cat_term = get_term( $shortcodeData['wmlo_product_category'], 'product_cat' );
				$product_cat_slug = $product_cat_term->slug;
				
				$query_arg['product_cat']		= $product_cat_slug;
			}
			*/
			
			if (($shortcodeData['wmlo_post_type'] == 'page') && ($shortcodeData['wmlo_page_parent'] > 0)){ // If post type is post and category is selected
				$query_arg['post_parent']	= $shortcodeData['wmlo_page_parent'];
			} else {
				$taxonomy_names = get_object_taxonomies( $shortcodeData['wmlo_post_type'], 'names');
				//print_r($taxonomy_names);
				foreach ($taxonomy_names as $taxonomy_name):
					if ($taxonomy_name == 'category'){
						if (!empty($shortcodeData['wmlo_post_category'])){
							$query_arg['cat']			= wmlp_wpml_icl_object_id($shortcodeData['wmlo_post_category'], 'category');
						}
					} else {
						if (!empty($shortcodeData['wmlo_tax_input'][$taxonomy_name])){
							$query_arg['tax_query'][]	= array(
															'taxonomy'	=> $taxonomy_name,
															'field'		=> 'term_id',
															'terms'		=> $shortcodeData['wmlo_tax_input'][$taxonomy_name]
														);
							//wmlp_term_id_to_slug($shortcodeData['wmlo_tax_input'][$taxonomy_name],$taxonomy_name);
						}					
					}
				endforeach;
			}
			
			if ($shortcodeData['wmlo_order_by'] != '0'){
				if ($shortcodeData['wmlo_order_by'] == 'rand'){
					add_filter('posts_orderby', 'wmlp_post_orderby');
				} else {
					$query_arg['orderby']	= $shortcodeData['wmlo_order_by'];
				}
			}
			
			if ($shortcodeData['wmlo_order'] != '0'){
				$query_arg['order']		= $shortcodeData['wmlo_order'];
			}
		} else {
			$generic_shortcode_query = json_decode(stripcslashes($_POST['generic_shortcode_query']), true);
			$query_arg	 = 	$query_arg + $generic_shortcode_query;
		}
		
		if ($pageNumber > 0){
			$query_arg['paged']     = $pageNumber;
		}
		
		if (!empty($shortcodeData['wmlo_custom_query'])){
			parse_str(trim($shortcodeData['wmlo_custom_query']), $custom_query);
			$query_arg	 = 	$query_arg + $custom_query;
		}
		$query_arg = apply_filters('wmlp_query', $query_arg, $shortcodeId);
		include('themes/loader.php');
	} else {
		$returnData['status']			= 'no_shortcode';
		$returnData['message'] 			= "WP Masonry Posts : Coudln't find shortcode in our record.";
	}
	echo json_encode($returnData);
	die();
}

function wmlp_check_layout_moved($themeLayout, $folderAddition = ''){
	// CHECK IF LAYOUT IS COPIED WORDPRESS ACTIVE THEME
	$currentWPTheme 		= wp_get_theme();
	$currentWPThemeSlug 	= $currentWPTheme->get( 'TextDomain' );
	$currentWPThemeDir 		= get_theme_root() . '/' . $currentWPThemeSlug;
	$mainfolderName 		= '/masonry-layout';
	$masonryLayoutName 		= '/'.$themeLayout;
	$masonryLayoutFullPath  = $currentWPThemeDir.$mainfolderName.$masonryLayoutName;
	
	if (file_exists($masonryLayoutFullPath.'/layout.php') && file_exists($masonryLayoutFullPath.'/style.php')){
		$themeLayout = $masonryLayoutFullPath;
	} else {
		$themeLayout = $folderAddition.$themeLayout;
	}
	
	return $themeLayout;
}

function wmlp_layout_url($filePath){
   /* $siteUrl              = content_url();
    $realpath             = str_replace('\\', '/', dirname($filePath));
    $explodedRealPath     = explode('wp-content',$realpath);
    $urlpath             = $siteUrl.$explodedRealPath[1];
    return $urlpath;*/
	$siteUrl = content_url();
	$realpath = str_replace('\\', '/', dirname($filePath));
	$parts = explode('/', $siteUrl);
	$delimiter = array_pop($parts);
	$explodedRealPath = explode($delimiter, $realpath);
	$urlpath = $siteUrl.$explodedRealPath[1];
	return $urlpath;
}

// RANDOM UNIQUE POST FILTER
function wmlp_post_orderby($orderby_statement) {
    global $randSeed;
	$orderby_statement = 'RAND('.$randSeed.')';
    return $orderby_statement;
}


function wmlp_wpml_icl_object_id($cat_ids, $type){
	if (function_exists('icl_object_id')){ // Check ig WPML plugin is enabled.
		$returnCats = array();
		foreach ($cat_ids as $cat_id):
			$returnCats[] = icl_object_id($cat_id, $type, true);
		endforeach;
		return $returnCats; // Reference  : http://wpml.org/documentation/support/creating-multilingual-wordpress-themes/language-dependent-ids/
	} else {
		return $cat_ids;
	}
}

function wmlp_term_id_to_slug($ids, $taxonomy){
	$slug = array();
	foreach ($ids as $id):
		$term = get_term( $id, $taxonomy );
		$slug[] = $term->slug;		
	endforeach;
	return $slug;
}

function wmlp_rewrite_taxonomy_data(){  // FOR VERSION 1.7 and above
	$wmlp_rewrite_taxonomy_data		= get_option('wmlp_rewrite_taxonomy_data');
	if ($wmlp_rewrite_taxonomy_data != 'updated'){
		$shortcodesRawData 			= get_option('wmlo_shortcodes_data');
		$shortcodesDatas			= json_decode($shortcodesRawData, true);
		
		foreach ($shortcodesDatas as $key=>$shortcodesData){
			if (!is_array($shortcodesData['wmlo_post_category'])){
				$shortcodesDatas[$key]['wmlo_post_category']	= array($shortcodesData['wmlo_post_category']);
			}
			if (array_key_exists('wmlo_product_category',$shortcodesData)){
				$shortcodesDatas[$key]['wmlo_tax_input']['product_cat']	= array($shortcodesData['wmlo_product_category']);
				unset($shortcodesDatas[$key]['wmlo_product_category']);	
			}		
		}	
		$updateshortcodesData				= json_encode($shortcodesDatas);
		update_option('wmlo_shortcodes_data',$updateshortcodesData);
		update_option('wmlp_rewrite_taxonomy_data','updated');
	}	
}