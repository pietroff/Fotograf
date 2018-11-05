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
		
        <?php if ($layoutSettings['show_title'] == 'yes'): ?>
            <div class="wmle_post_title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </div>
        <?php endif; ?>
        
		<?php if ($layoutSettings['show_social_bar'] == 'yes'): ?>
            <div class="wmle_post_meta">
               <div class="wmle_social_share">
                   <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="fb">Facebook</a>
                   <a target="_blank"  href="https://twitter.com/home?status=<?php the_permalink(); ?>" class="tw">Twitter</a>
                   <a target="_blank"  href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=&source=" class="in">Linkedin</a>                   
                   <?php if (has_post_thumbnail( get_the_ID()) ): // Don't show pinterest if no image
				   $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
                   		<a target="_blank"  href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo $image[0]; ?>&description=<?php the_title(); ?>" class="pi">Pinterest</a>
				   <?php endif; ?>
                   <a target="_blank"  href="https://plus.google.com/share?url=<?php the_permalink(); ?>" class="gp">Google+</a>
               </div>
               
               <?php if ($layoutSettings['show_comment_count'] != 'no'): ?>
                   <div class="wmle_comment_count">
                    <a href="<?php comments_link(); ?>"><span class="icon-bubbles3 icons"></span>&nbsp;<?php comments_number('0', '1', '%' ); ?></a>
                   </div>
               <?php endif; ?>
               <div style="clear:both;" />
            </div>
        <?php endif; ?>
    </div><!-- EOF wmle_item_holder -->
</div><!-- EOF wmle_item_holder -->