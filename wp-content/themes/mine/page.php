<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
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
$page_id = get_queried_object_id();
$page_title = get_the_title($page_id);
mine_header_title($page_title);
?>
<div class="container">
    <div class="row">        
        <div id="primary" class="content-area col-sm-8 col-xs-12">
            <main id="main" class="site-main" role="main">
                <?php
                // Start the loop.
                while (have_posts()) : the_post();
                    // Include the page content template.
                    get_template_part('template-parts/content', 'page');
                // End of the loop.
                endwhile;
                mine_paging_nav();
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
                ?>
            </main><!-- .site-main -->            
        </div><!-- .content-area -->
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>