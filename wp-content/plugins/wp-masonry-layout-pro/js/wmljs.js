// JavaScript Document
jQuery(document).ready( function() {	
	
	$container = jQuery('#wmle_container');		 // START MASONRY
	$container.imagesLoaded(function(){
		$container.masonry();
	})
								 
	jQuery(".wmle_loadmore_btn").click( function(e) {
		e.preventDefault();
		url 					= jQuery(this).attr("href");
		containerDivId  		= jQuery(this).attr("rel")
		pageNumber 				= jQuery("#"+containerDivId).attr("data-page");
		randSeed 				= jQuery("#"+containerDivId).attr("data-seed");
		generic_shortcode_query = jQuery("#generic_shortcode_query").val();
		jQuery("#"+containerDivId).attr("data-load-status",'no');
		if (pageNumber == null){
		  pageNumber = 1;
		}
		url = url + '&pageNumber=' + pageNumber + '&randSeed=' + randSeed ;
		jQuery.ajax({
			dataType : "json",
			url : url,
			method: "POST",
			data: { generic_shortcode_query: generic_shortcode_query},
			beforeSend : function(){
				jQuery("img.loading_icon").show();
				jQuery('.wmle_loadmore_btn').html('Loading...');
			},
			success: function(response) {
				if(response.status == "ok") {
					var newPageNumber = parseInt(pageNumber) + 1;
					$boxes = jQuery(response.elements);
					$container.append( $boxes ).imagesLoaded( function(){
						jQuery('.wmle_item_holder').show();
						
						// ONLY FOR LAZY LOAD
						jQuery('img.wpml_lazy_load').each(function(){
							newImageWidth 	= jQuery(this).width();
							imageWidth		= jQuery(this).attr('data-width');
							imageHeight		= jQuery(this).attr('data-height');
							aspect 			= imageWidth / imageHeight;
							newImageHeight	= newImageWidth / aspect;
							jQuery(this).height(newImageHeight);
						});
						jQuery('img.wpml_lazy_load').lazyload({
							effect: 'fadeIn',
							load:function(){
								jQuery(this).css('height', 'auto');	
								jQuery(this).removeClass('wpml_lazy_load');
							}
						});
						// EOF ONLY FOR LAZY LOAD
						
						$container.masonry( 'appended', $boxes);
						jQuery("img.loading_icon").hide();
						jQuery("#"+containerDivId).attr("data-page", newPageNumber);
						jQuery('.wmle_loadmore_btn').html('Load More');
						jQuery("#"+containerDivId).attr("data-load-status",'ready');
						if (parseInt(newPageNumber) > parseInt(response.max_pages)){ // Hide load more btn if no more pages.
							jQuery('.wmle_loadmore').remove();
						}						
					});
					wmlp_colorbox_init();
				}
				else {
				   jQuery('.wmle_loadmore_btn').html(response.message);
				}
			}
		})
	})   
})