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

                <?php
  $nameofkat = $post->post_name;
  query_posts('page_id=114');
 
    while (have_posts()) : the_post();
      echo "<h3 class='entry-title'>";
      echo "<a href='".get_permalink()."'>";
            the_title();
      echo "</a>";
      echo "</h3>";
      
   endwhile;
?>

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
            <div class="text-right">
                <?php
  $nameofkat = $post->post_name;
  query_posts('page_id=14');
 
    while (have_posts()) : the_post();
      the_post_thumbnail();
   endwhile;
?>
            </div>
        </div>
    </main><!-- .site-main -->
</div>
<?php get_footer(); ?>