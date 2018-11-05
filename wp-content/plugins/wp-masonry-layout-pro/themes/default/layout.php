<div class="wmle_item_holder <?php echo $shortcodeData['wmlo_columns'] ?>" style="display:none;">
    <div <?php post_class( 'wmle_item' ); ?>>        
		<?php if ($layoutSettings['show_featured_image'] == 'yes'): ?>
			<?php if ( has_post_thumbnail() ) :?>
                <div class="wpme_image">
                    <?php echo wmlp_featured_image($shortcodeData); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
		<?php if ($layoutSettings['show_post_meta'] == 'yes'): ?>
            <div class="wmle_post_meta">
               <a href="<?php comments_link(); ?>"><?php comments_number('0 Response', '1 Response', '% Responses' ); ?></a>
            </div>
        <?php endif; ?>
        
		<?php if ($layoutSettings['show_title'] == 'yes'): ?>
            <div class="wmle_post_title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </div>
        <?php endif; ?>
        
		<?php if ($layoutSettings['show_excerpt'] == 'yes'): ?>
            <div class="wmle_post_excerpt">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
        
    </div><!-- EOF wmle_item_holder -->
</div><!-- EOF wmle_item_holder -->