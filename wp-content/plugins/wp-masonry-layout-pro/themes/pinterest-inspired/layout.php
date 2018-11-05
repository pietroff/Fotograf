<?php $mediaUrl = wmlp_layout_url(__FILE__); // Donot remove this ?>
<div class="wmle_item_holder <?php echo $shortcodeData['wmlo_columns'] ?>" style="display:none;">
    <div <?php post_class( 'wmle_item' ); ?>>
		<?php if ($layoutSettings['show_featured_image'] == 'yes'): ?>
			<?php if ( has_post_thumbnail() ) :?>
                <div class="wpme_image">
                    <?php echo wmlp_featured_image($shortcodeData); ?>
                    
                    <?php if ($layoutSettings['show_social_share'] == 'yes'): ?>
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
                    <?php endif; ?>
                    
                </div>
                
            <?php endif; ?>
        <?php endif; ?>
		
        <?php if ($layoutSettings['show_title'] == 'yes'): ?>
            <div class="wmle_post_title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <a href="<?php comments_link(); ?>" class="commentLink"><span class="icon-bubble icons"></span>&nbsp;<?php comments_number('0', '1', '%' ); ?></a>
            </div>
        <?php endif; ?>
        
		<?php if ($layoutSettings['show_author'] == 'yes'): ?>
            <div class="wmle_post_meta">
               <div class="wmle_avatar">
			   		<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?></a>
               </div>
               
               <div class="wmle_meta_content">
               		<div class="wmle_author_name">
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a>
                    </div>
					
					<div class="wmpe_categories"><?php the_category(','); ?></div>
               </div>
               <div style="clear:both;"></div>
            </div>
        <?php endif; ?>
    </div><!-- EOF wmle_item_holder -->
</div><!-- EOF wmle_item_holder -->


