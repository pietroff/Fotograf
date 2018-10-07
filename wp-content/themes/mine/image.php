<?php
/**
 * The template for displaying image attachments
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
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-content">
                            <div class="entry-attachment">
                                <?php
                                /**
                                 * Filter the default mine image attachment size.
                                 *
                                 * @since 1.0
                                 *
                                 * @param string $image_size Image size. Default 'large'.
                                 */
                                $image_size = apply_filters('mine_attachment_size', 'large');
                                echo wp_get_attachment_image(get_the_ID(), $image_size);
                                mine_excerpt('entry-caption');
                                ?>
                            </div><!-- .entry-attachment -->
                            <?php
                            the_content();
                            wp_link_pages(array(
                                'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'mine') . '</span>',
                                'after' => '</div>',
                                'link_before' => '<span>',
                                'link_after' => '</span>',
                                'pagelink' => '<span class="screen-reader-text">' . __('Page', 'mine') . ' </span>%',
                                'separator' => '<span class="screen-reader-text">, </span>',
                            ));
                            ?>
                        </div><!-- .entry-content -->
                        <?php
                        // Parent post navigation.
                        the_post_navigation(array(
                            'prev_text' => '<span class="meta-nav">' . _x('Published in', 'Parent post link', 'mine') . '</span><span class="post-title">%title</span>',
                        ));
                        ?>
                        <footer class="entry-footer">
                            <?php
                            // Retrieve attachment metadata.
                            $metadata = wp_get_attachment_metadata();
                            if ($metadata) {
                                printf('<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>', esc_html_x('Full size', 'Used before full size attachment link.', 'mine'), esc_url(wp_get_attachment_url()), absint($metadata['width']), absint($metadata['height'])
                                );
                            }
                            ?>
                            <div class="post-meta">
                                <?php mine_get_blog_post_meta(); ?>
                            </div>                            
                        </footer><!-- .entry-footer -->
                    </article><!-- #post-## -->
                    <nav id="image-navigation" class="navigation image-navigation">
                        <div class="nav-links">
                            <div class="nav-previous">
                                <?php previous_image_link(false, '<span class="meta-nav-arrow fa fa-chevron-left"></span>' . __('Previous Image', 'mine')); ?>
                            </div>
                            <div class="nav-next">
                                <?php next_image_link(false, '<span class="meta-nav-arrow fa fa-chevron-right"></span>' . __('Next Image', 'mine')); ?>
                            </div>
                        </div><!-- .nav-links -->
                    </nav><!-- .image-navigation -->
                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                // End the loop.
                endwhile;
                ?>
            </main><!-- .site-main -->
        </div><!-- .content-area -->
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>