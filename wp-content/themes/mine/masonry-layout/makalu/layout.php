<?php $mediaUrl = wmlp_layout_url(__FILE__); // Donot remove this ?>
<div class="wmle_item_holder <?php echo $shortcodeData['wmlo_columns'] ?>" style="display:none;">
    <div <?php post_class( 'wmle_item' ); ?>>
        <?php if ($layoutSettings['show_featured_image'] == 'yes'): ?>
			<?php if ( has_post_thumbnail() ) :?>
                <div class="wpme_image">
                    <?php echo wmlp_featured_image($shortcodeData); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="wmle_masonary_box">
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
        </div><!-- EOF WMPLE_MASONARY_BOX -->
        
        <?php if ($layoutSettings['show_post_meta'] == 'yes'): ?>
            <div class="wmle_post_meta">
                <div class="wmle_author_name">
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">By: <span><?php the_author_meta( 'display_name' ); ?></span></a>
                </div>
                <div class="wmle_date"><?php the_date(); ?></div>
                <div style="clear:both"></div>
            </div><!-- EOF WMLE_POST_META -->
        <?php endif; ?>
        
        <?php if ($layoutSettings['show_social_share'] == 'yes'): ?>
            <div class="wmle_social_share">
                <a class="fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank">Fb</a>
                <a class="tw" href="https://twitter.com/home?status=<?php the_permalink(); ?>" target="_blank">Tw</a>
                <a class="gp" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="_blank">G+</a>
                <a class="in" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=&source=" target="_blank">ln</a>
                <?php if (has_post_thumbnail( get_the_ID()) ): // Don't show pinterest if no image
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
				<a target="_blank"  href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo $image[0]; ?>&description=<?php the_title(); ?>" class="pi">Pi</a>
				<?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($layoutSettings['show_post_category'] == 'yes'): ?>
            <div class="wmle_post_category">
                <?php the_category(','); ?>
            </div>
        <?php endif; ?>
        
        <div style="clear:both"></div>        
    </div><!-- EOF WMLE_ITEM -->
</div><!-- EOF wmle_item_holder -->