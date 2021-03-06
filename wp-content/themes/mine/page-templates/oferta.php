<?php
/**
 * Template Name: Oferta
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
<div id="primary" class="content-area fullpage">
    <main id="main" class="site-main" role="main">
        <div class="container border">
            <div class="margines">
            <div class="margines-oferta">
            <div  class="czytaj-wiecej"> <a href=" <?php echo esc_url(get_permalink(67)); ?>"><?php echo get_the_title(67); ?></a></div>
            <div  class="czytaj-wiecej"><a href=" <?php echo esc_url(get_permalink(73)); ?>"><?php echo get_the_title(73); ?></a></div>
            <div  class="czytaj-wiecej"><a href=" <?php echo esc_url(get_permalink(76)); ?>"><?php echo get_the_title(76); ?></a></div>
            <div  class="czytaj-wiecej"><a href=" <?php echo esc_url(get_permalink(79)); ?>"><?php echo get_the_title(79); ?></a></div>
            <div  class="czytaj-wiecej"><a href=" <?php echo esc_url(get_permalink(82)); ?>"><?php echo get_the_title(82); ?></a></div>
            <div  class="czytaj-wiecej"><a href=" <?php echo esc_url(get_permalink(85)); ?>"><?php echo get_the_title(85); ?></a></div>
            <div  class="czytaj-wiecej"><a href=" <?php echo esc_url(get_permalink(111)); ?>"><?php echo get_the_title(111); ?></a></div>
            <div  class="czytaj-wiecej"><a href=" <?php echo esc_url(get_permalink(114)); ?>"><?php echo get_the_title(114); ?></a></div>
            <div  class="czytaj-wiecej"><a href=" <?php echo esc_url(get_permalink(117)); ?>"><?php echo get_the_title(117); ?></a></div>
            <div  class="czytaj-wiecej"><a href=" <?php echo esc_url(get_permalink(105)); ?>"><?php echo get_the_title(105); ?></a></div>
            </div>

                <?php
            // Start the loop.
            while (have_posts()) : the_post();
            // Include the page content template.
            get_template_part('template-parts/content', 'page');
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            // End of the loop.
        endwhile;
        ?>
        </div>
            <div class="text-center">
                <?php
  $nameofkat = $post->post_name;
  query_posts('page_id=16');
 
    while (have_posts()) : the_post();
      the_post_thumbnail();
   endwhile;
?>
            </div>
        </div>
    </main><!-- .site-main -->
</div>
<?php get_footer(); ?>