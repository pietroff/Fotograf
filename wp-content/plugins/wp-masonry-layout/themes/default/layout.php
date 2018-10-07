<div class="wmle_item_holder <?php echo $shortcodeData['wmlo_columns'] ?>" style="display:none;">
    <div class="wmle_item">
        <?php if ( has_post_thumbnail() ) :?>
            <div class="wpme_image">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail($shortcodeData['wmlo_image_size']); ?></a>
            </div>
        <?php endif; ?>
        <div class="wmle_post_meta">
           <a href="<?php comments_link(); ?>"><?php comments_number('0 Response', '1 Response', '% Responses' ); ?></a>
        </div>
        <div class="wmle_post_title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </div>
        <div class="wmle_post_excerpt">
            <?php the_excerpt(); ?>
        </div>
    </div><!-- EOF wmle_item_holder -->
</div><!-- EOF wmle_item_holder -->