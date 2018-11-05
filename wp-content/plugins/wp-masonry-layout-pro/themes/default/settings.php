<?php 
$themeDetails['name'] 			= 'Default';
$themeDetails['folder_name'] 	= 'default';
$themeDetails['demo_url'] 		= 'http://www.masonrylayout.com/masonry-theme-demo/default/';
$themeDetails['movefiles'] 		= array('style.php','layout.php');
$themeDetails['settings'] 		= array(
										'show_featured_image' => array(
																	   'label' 	 	 => 'Show Featured Image',
																	   'input_field' => 'radio',
																	   'options'	 => array('yes'=>'Yes', 'no'=>'No'),
																	   'default'	 => 'yes'
																	   ),
										
										'show_title' => array(
																	   'label' 	 	 => 'Show Title',
																	   'input_field' => 'radio',
																	   'options'	 => array('yes'=>'Yes', 'no'=>'No'),
																	   'default'	 => 'yes'
																	   ),
										
										'show_excerpt' => array(
																	   'label' 	 	 => 'Show Excerpt',
																	   'input_field' => 'radio',
																	   'options'	 => array('yes'=>'Yes', 'no'=>'No'),
																	   'default'	 => 'yes'
																	   ),
										
										'show_post_meta' => array(
																	   'label' 	 	 => 'Show Post Meta',
																	   'input_field' => 'radio',
																	   'options'	 => array('yes'=>'Yes', 'no'=>'No'),
																	   'default'	 => 'yes'
																	   ),
										'box_border' => array(
																	   'label' 	 	 => 'Box Border',
																	   'input_field' => 'border',
																	   'default'	 => array('1px','solid','#e5e5e5')
																	   ),
										'box_shadow' => array(
																	   'label' 	 	 => 'Box Shadow',
																	   'input_field' => 'box_shadow',
																	   'default'	 => array('0px','0px','3px','-1px','#959595')
																	   ),
										'post_meta_font_size' => array(
																	   'label' 	 	 => 'Post Meta Font Size',
																	   'input_field' => 'input',
																	   'default'	 => '11px'
																	   ),
										'post_meta_font_color' => array(
																	   'label' 	 	 => 'Post Meta Text Color',
																	   'input_field' => 'color',
																	   'default'	 => '#a5a4a2'
																	   ),
										'post_title_font_size' => array(
																	   'label' 	 	 => 'Post Title Font Size',
																	   'input_field' => 'input',
																	   'default'	 => '12px'
																	   ),
										'post_title_font_color' => array(
																	   'label' 	 	 => 'Post Title Text Color',
																	   'input_field' => 'color',
																	   'default'	 => '#a5a4a2'
																	   ),
										'post_excerpt_font_size' => array(
																	   'label' 	 	 => 'Post Excerpt Font Size',
																	   'input_field' => 'input',
																	   'default'	 => '12px'
																	   ),
										'post_excerpt_font_color' => array(
																	   'label' 	 	 => 'Post Excerpt Text Color',
																	   'input_field' => 'color',
																	   'default'	 => '#a5a4a2'
																	   )
										
										);
?>