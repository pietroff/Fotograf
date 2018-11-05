<?php
function wmlp_featured_image($shortcodeData){
	$useLightBox  =  $shortcodeData['wmlo_use_lightbox'];
	$useLazyLoad  =  $shortcodeData['wmlo_use_lazyload'];
	
	if ($useLightBox == 'yes'):
		$largeImageSrc  = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large', false, '');
		$imageLink		= $largeImageSrc[0]; //Send image path
	else:
		$imageLink	  =  get_permalink();
	endif;
	
	if ($useLazyLoad == 'yes'):
		$imageData  	= wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $shortcodeData['wmlo_image_size'], false, '');
		$imageSrc		= $imageData[0];
		$width			= $imageData[1];
		$height			= $imageData[2];
		$imageDOM		= '<img class="wpml_lazy_load" src="'.plugins_url( 'images/lazy_load_dummy.jpg', __FILE__ ).'" width="'.$width.'px" height="'.$height.'px" data-width="'.$width.'" data-height="'.$height.'"  alt="'.get_the_title().'" data-original="'.$imageSrc.'" />';
	else:
		$imageDOM	=	get_the_post_thumbnail(null, $shortcodeData['wmlo_image_size']);
	endif;
	
	return '<a href="'.$imageLink.'">'.$imageDOM.'</a>';
}