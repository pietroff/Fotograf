<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header();
$archive_title = get_the_archive_title();
mine_header_title($archive_title);
?>
<div class="container">
    <div class="row">
        <div id="primary" class="content-area col-sm-8 col-xs-12">
            <?php
            if (is_author() && get_the_author_meta('description') !== '') {
                get_template_part('template-parts/biography');
                echo '<div class="content-area-wrap">';
            }
            if (have_posts()) :
                // Start the Loop.
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
            if (is_author() && get_the_author_meta('description') !== '') {
                echo '</div>';
            }
            ?>
        </div><!-- .content-area -->
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>