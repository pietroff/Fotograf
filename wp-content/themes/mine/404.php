<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header();
$page_title = esc_html__('404 Page not found', 'mine');
mine_header_title($page_title);
?>
<div class="container">
    <div class="row">        
        <div id="primary" class="content-area col-sm-8 col-xs-12">
            <section class="error-404 not-found text-center">
                <header class="entry-header">
                    <h3 class="title-404">404</h3>
                    <h4 class="entry-title"><?php esc_html_e('Oops! That page can\'t be found.', 'mine'); ?></h4>
                </header>
                <div class="page-content">
                    <p><?php esc_html_e('It looks like nothing was found at this location. Please try again.', 'mine'); ?></p>
                    <?php get_search_form(); ?>
                </div><!-- .page-content -->
            </section><!-- .error-404 -->          
        </div><!-- .content-area -->
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>