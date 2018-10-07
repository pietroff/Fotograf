<?php
/**
 * The template for displaying posts in the vedio post format
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <!-- for display post image -->
    <?php
    if (mine_get_video_embed_media($post->ID)) { ?>
        <div class="post-audio post-thumbnail">
            <?php  
            $media_html = mine_get_video_embed_media($post->ID);
            print_r($media_html);
            ?>
        </div><?php
    } else {
        mine_blog_image();
    }
    ?>

    <!-- for display post title -->
    <?php if (get_the_title()) { ?>
        <header class="entry-header">
            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
        </header>
    <?php } ?>

    <!-- for display post meta -->
    <footer class="entry-footer">        
        <div class="post-meta blog_post_meta">
            <?php mine_blog_posted_on(); ?>
            <?php mine_get_blog_post_meta(); ?>
        </div>
        <div class="post-meta">
            <?php mine_blog_category_list(); ?>
        </div>                
    </footer>

    <!-- for display post content -->
    <?php if (!is_single() && !mine_get_video_embed_media($post->ID)) { ?>
        <div class="entry-content">
            <?php mine_blog_read_more(); ?>
            <?php mine_post_social_share(); ?>
        </div>
    <?php } ?>

</article>