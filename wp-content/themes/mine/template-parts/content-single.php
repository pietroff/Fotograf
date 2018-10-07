<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <!-- for display post image -->
    <?php mine_blog_image(); ?>

    <!-- for display post meta -->
    <footer class="entry-footer">        
        <div class="post-meta postedby_post_meta">
            <?php
            $author_avatar_size = apply_filters('mine_author_avatar_size', 42);
            printf('<span class="author"> %s</span>', get_avatar($author_avatar_size));
            ?>
        </div>             
    </footer>

    <!-- for display post meta -->
    <footer class="entry-footer">
        <div class="post-meta blog_post_meta">
            <span class="author text-capitalize">
                <i class="fa fa-user"></i> 
                <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author"><?php echo get_the_author(); ?></a>
            </span>
            <?php 
            mine_blog_posted_on(); 
            mine_get_blog_post_meta();
            edit_post_link(__('Edit', 'mine'), '<span class="edit-link"><i class="fa fa-edit"></i> ', '</span>');
            ?>
        </div>
    </footer>
    
    <!-- for display post content -->
    <div class="entry-content">
        <?php the_content(); ?>
    </div>

    <!-- for display post meta -->
    <footer class="entry-footer">       
        <div class="post-meta">
            <?php mine_blog_category_list(); ?>
        </div>
        <?php 
        esc_html_e('Share it','mine'); echo ' : ';
        mine_post_social_share();
        ?>
    </footer>
</article><!-- #post-## -->