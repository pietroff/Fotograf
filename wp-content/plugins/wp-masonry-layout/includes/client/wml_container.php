<div class="wmle_container <?php echo $shortcodeData['wmlo_responsive'] == 'yes'?'responsive':''; ?>" id="wmle_container" data-load-status="ready" data-seed="<?php echo rand(); ?>">
	<div class="wmle_item_holder <?php echo $shortcodeData['wmlo_columns'] ?>"></div>
</div>

<?php
	$link 				= admin_url('admin-ajax.php?action=wml_load_posts&shortcodeId='.$shortcodeId);
	$containerDivId		= 'wmle_container';
?>


<div class="wmle_loadmore" class="wmle_loadmore_<?php echo $shortcodeId; ?>">
	<a href="<?php echo $link; ?>" class="wmle_loadmore_btn" rel="<?php echo $containerDivId; ?>">Load More</a>
</div>

<script> // AUTO TRIGGER FOR 1st Load
jQuery(document).ready( function() {
	jQuery('.wmle_loadmore_btn').trigger('click');
});
</script>