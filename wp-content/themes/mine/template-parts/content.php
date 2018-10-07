<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$blog_default_image = get_theme_mod('mine_blog_default_image');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <!-- for display post title -->
    <?php
    /* for display post image */
    if (mine_get_video_embed_media($post->ID)) { ?>
        <div class="post-thumbnail">
            <?php  
            $media_html = mine_get_video_embed_media($post->ID);
            print_r($media_html);
            ?>
        </div><?php
    } else if (!post_password_required() && has_post_thumbnail()) { ?>
        <div class="post-thumbnail <?php
        if (is_sticky() && (is_home() || is_archive()) && !is_paged()) {
            echo "post-thumbnail-sticky";
        }
        ?>">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_post_thumbnail(); ?>
            </a><?php 
            if (is_sticky() && (is_home() || is_archive()) && !is_paged()) : ?>
                <span class="sticky-post"><?php esc_html_e('Featured', 'mine'); ?></span><?php
            endif; ?>
        </div><?php
    }else if (!post_password_required() && has_post_thumbnail()) { ?>
        <div class="post-thumbnail <?php
        if (is_sticky() && (is_home() || is_archive()) && !is_paged()) {
            echo "post-thumbnail-sticky";
        }
        ?>">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php echo "<img src='".esc_html($blog_default_image)."' alt='". get_the_title()."'>"; ?>
            </a><?php 
            if (is_sticky() && (is_home() || is_archive()) && !is_paged()) : ?>
                <span class="sticky-post"><?php esc_html_e('Featured', 'mine'); ?></span><?php
            endif; ?>
        </div><?php
    }
    else {
        mine_blog_image('mine-blog-thumbnail');
    }

    if (get_the_title()) { ?>
        <header class="entry-header">
            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
        </header>  <?php
    } ?>

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
    <div class="entry-content">
        <?php mine_blog_read_more(); ?>
        <?php mine_post_social_share(); ?>
    </div>
</article>