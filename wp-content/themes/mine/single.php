<?php
/**
 * The template for displaying all single posts and attachments
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

$blog_default_image = get_theme_mod('mine_blog_default_image');
$blog_layout = get_theme_mod('mine_blog_sidebar','full');

$blog_alignment = "left-alignment";
if( $blog_layout == 'left' ){
    $blog_class = 'col-md-8 col-sm-8';
} elseif( $blog_layout == 'right' ){
    $blog_class = 'col-md-8 col-sm-8';
} else{
    $blog_alignment = " text-center";
    $blog_class = 'col-md-12 col-sm-12';
}

$post_title = get_the_title();
mine_header_title($post_title);
?>
<div class="container">
    <div class="row">
        <div id="primary" class="content-area <?php echo esc_attr($blog_class).' '.esc_attr($blog_alignment); ?> col-xs-12 <?php if($blog_layout == 'left'){ echo "col-sm-push-4"; } ?>">
            <main id="main" class="site-main" role="main">
                <?php
                // Start the loop.
                while (have_posts()) : the_post();
                    // Include the single post content template.
                    get_template_part('template-parts/content', 'single');
                    if (is_singular('attachment')) {
                        // Parent post navigation.
                        the_post_navigation(array(
                            'prev_text' => '<span class="meta-nav">' . _x('Published in', 'Parent post link', 'mine') . '</span><span class="post-title">%title</span>',
                        ));
                    } elseif (is_singular('post')) {
                        // Previous/next post navigation.
                        the_post_navigation(array(
                            'next_text' => '<span class="post-title" aria-hidden="true">' . __('Następny Post  >>', 'mine') . '</span> <small></small>',
                            'prev_text' => '<span class="post-title" aria-hidden="true">' . __('<<  Poprzedni Post', 'mine') . '</span> <small></small>',
                        ));
                    }
                    // if author detail found then display author details
                    if (get_the_author_meta('description') !== '') {
                        get_template_part('template-parts/biography');
                    }

                    $show_related_post = get_theme_mod('mine_enable_related_post', 0);
                    $post_perpage = 4;
                    $args = array();
                    global $post;
                    $categories = get_the_category($post->ID);
                    if ($categories) {
                        $category_ids = array();
                        foreach ($categories as $individual_category) {
                            $category_ids[] = $individual_category->term_id;
                        }

                        $args = array(
                            'category__in' => $category_ids,
                            'post_type' => 'post',
                            'orderby' => 'date',
                            'order' => 'DECS',
                            'post__not_in' => array($post->ID),
                            'post_status' => 'publish',
                            'ignore_sticky_posts' => true,
                            'posts_per_page' => $post_perpage // Number of related posts that will be displayed. 
                        );
                    }
                    $my_query = new wp_query($args);
                  
                    
                    // If comments are open or we have at least one comment, load up the comment template.
                    /**  if (comments_open() || get_comments_number()) {
                        *?>
                        *<div class="section_seperation"></div>
                        *<?php
                        *comments_template();
                    *}
                    */ 
                // End of the loop.
                endwhile;
                ?>
            </main><!-- .site-main -->        
        </div><!-- .content-area -->
        
    </div>
</div>
<?php get_footer(); ?>