<?php
/**
 * Template Name: Contact Us Template
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Mine
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
        <?php
        $divclass = 'col-md-8 col-sm-8 col-xs-12';
        $sidebarclass = 'col-md-4 col-sm-4 col-xs-12';
        ?>
        <div id="primary" class="content-area <?php echo esc_attr($divclass); ?>">
            <main id="main" class="site-main" role="main">
                <?php
                while (have_posts()) : the_post();
                    get_template_part('template-parts/content', 'page');
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                endwhile; // End of the loop.
                ?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <aside id="secondary" class="sidebar widget-area <?php echo esc_attr($sidebarclass); ?>" role="complementary">
            <?php dynamic_sidebar('contact-us-sidebar'); ?>
        </aside>        
    </div>
</div>
<?php get_footer(); ?>