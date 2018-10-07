<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header();
global $wp_query;
$page_title = "Blogs";
mine_header_title($page_title);
?>
<div class="container">
    <div class="row">        
        <div id="primary" class="content-area col-sm-8 col-xs-12">
            <?php
            if (have_posts()) :
                // Start the loop.
                while (have_posts()) : the_post();

                    /*
                     * Include the Post-Format-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                    get_template_part('template-parts/content', get_post_format());

                // End the loop.
                endwhile;
                // Previous/next page navigation.
                mine_paging_nav();
            // If no content, include the "No posts found" template.
            else :
                get_template_part('template-parts/content', 'none');
            endif;
            ?>
        </div><!-- .content-area -->
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>