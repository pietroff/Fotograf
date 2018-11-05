<?php 
$themeDetails['name'] 			= 'Lhotse';
$themeDetails['folder_name'] 	= 'lhotse';
$themeDetails['demo_url'] 		= 'http://www.masonrylayout.com/masonry-theme-demo/lhotse/';
$themeDetails['movefiles'] 		= array('style.php','layout.php','twitter.png','pinterest.png','linkedin.png','gplus.png','facebook.png');
$themeDetails['settings'] 		= array(
										'show_title' => array(
																	   'label' 	 	 => 'Show Title',
																	   'input_field' => 'radio',
																	   'options'	 => array('yes'=>'Yes', 'no'=>'No'),
																	   'default'	 => 'yes'
																	   ),
										
										
										'image_gap' => array(
																	   'label' 	 	 => 'Space Between the images',
																	   'input_field' => 'input',
																	   'default'	 => '2px'
																	   ),
										'image_opacity_onhover' => array(
																	   'label' 	 	 => 'Image Opacity On Mouse Over',
																	   'input_field' => 'input',
																	   'default'	 => '0.4'
																	   ),
										'title_font_size' => array(
																	   'label' 	 	 => 'Title Font Size',
																	   'input_field' => 'input',
																	   'default'	 => '13px'
																	   ),
										'title_font_color' => array(
																	   'label' 	 	 => 'Title Font Color',
																	   'input_field' => 'color',
																	   'default'	 => '#ffffff'
																	   ),
										'title_text_shadow' => array(
																	   'label' 	 	 => 'Title Text Shadow',
																	   'input_field' => 'text_shadow',
																	   'default'	 => array('0px','0px','5px','#000000')
																	   )
										);
?>