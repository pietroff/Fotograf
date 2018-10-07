<?php
/**
 * The template for displaying search results pages
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header();
$page_title = __('Search Results for', 'mine') . ": " . get_search_query();
mine_header_title($page_title);
?>
<div class="container">
    <div class="row">        
        <div id="primary" class="content-area col-sm-8 col-xs-12">
            <?php
            if (have_posts()) :
                // Start the loop.
                while (have_posts()) : the_post();
                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part('template-parts/content', 'search');
                // End the loop.
                endwhile;
                // Previous/next page navigation.
                mine_paging_nav();
            // If no content, include the "No posts found" template.
            else :
                get_template_part('template-parts/content', 'none');
            endif;
            ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>