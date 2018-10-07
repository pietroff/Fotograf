<?php
/**
 * Template Name: Home Page Template
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

$blog_default_image = get_theme_mod('mine_blog_default_image');

$default_content = __('I am here to help you become a better blogger. I share proven and tested techniques on my blogs to help you achieve the success. Lorem  Ipsum is simply dummy text of the printing and typesetting industry.','mine');

$mine_disable_intro = get_theme_mod('mine_disable_intro', '0');
$mine_intro_name = get_theme_mod('mine_about_name', 'Hello, Albert here&hellip;');
$mine_about_position = get_theme_mod('mine_about_position', 'Passionate Blogger');
$mine_about_content = get_theme_mod('mine_about_content',$default_content);
$mine_read_more_text = get_theme_mod('mine_read_more_text', 'Know More');
$mine_read_more_link = get_theme_mod('mine_read_more_link','#');
$mine_disable_read_more = get_theme_mod('disable_read_more_button', '0');
$mine_about_avatar = get_theme_mod('mine_about_avatar', get_template_directory_uri() . '/images/about.png');
$mine_social_facebook = get_theme_mod('mine_social_facebook');
$mine_social_twitter = get_theme_mod('mine_social_twitter');
$mine_social_google_plus = get_theme_mod('mine_social_google_plus');
$mine_social_instagram = get_theme_mod('mine_social_instagram');
$mine_social_linkedin = get_theme_mod('mine_social_linkedin');

$mine_disable_blogposts = get_theme_mod('mine_disable_blogposts', '0');
$mine_blogposts_title = get_theme_mod('mine_blogposts_title', 'Latest from the blog');
$mine_blogposts_items = get_theme_mod('mine_blogposts_items', '4');
$mine_blogposts_orderby = get_theme_mod('mine_blogposts_orderby', 'date');
$mine_blogposts_order = get_theme_mod('mine_blogposts_order', 'DESC');
$mine_blogposts_category = get_theme_mod('mine_blogposts_category', '0');


$mine_disable_blog_posts = get_theme_mod('mine_disable_blog_posts', '0');
$mine_grid_blogposts_title = get_theme_mod('mine_grid_blogposts_title');
$mine_blog_posts_items = get_theme_mod('mine_blog_posts_items', '4');
$mine_blog_posts_orderby = get_theme_mod('mine_blog_posts_orderby', 'date');
$mine_blog_posts_order = get_theme_mod('mine_blog_posts_order', 'DESC');
$mine_blog_posts_category = get_theme_mod('mine_blog_posts_category', '0');

$class = "col-sm-12 col-md-12";
if(isset($mine_about_avatar) && $mine_about_avatar != ''){
    $class = "col-sm-7 col-md-7";
}
?>
<div class="page-header about_author">
    <div class="container">
        <div class="row">
            <?php if ($mine_disable_intro != '1') { ?>
                <div class="col-xs-12 col-sm-4 col-md-4 duze-logo-position">
                <img src="<?php echo esc_attr($mine_about_avatar); ?>" class="duze-logo" />
                    <span class="user_designation set-color"><?php print_r($mine_about_position); ?></span>
                    <h1 class="page-title set-color"><?php print_r($mine_intro_name); ?></h1>
                    <div class="author-bio">
                        <?php
                        print_r($mine_about_content);
                        if ($mine_disable_read_more != '1') {
                            ?>
                            <div class="read_more">
                                <a href="<?php print_r($mine_read_more_link); ?>"><?php print_r($mine_read_more_text); ?></a>
                            </div><?php
                        }
                        ?>
                        <div class="author_socialicon_block">
                            <ul class="social-media">
                                <?php 
                                if ($mine_social_facebook != '') { ?>
                                    <li class="circle facebook">
                                        <a href="<?php echo esc_html($mine_social_facebook); ?>" target="_blank"><i class="fa text-center fa-facebook"></i></a>
                                    </li><?php
                                }
                                if ($mine_social_twitter != '') {
                                    ?>
                                    <li class="circle twitter">
                                        <a href="<?php echo esc_html($mine_social_twitter); ?>" target="_blank"><i class="fa text-center fa-twitter"></i></a>
                                    </li><?php
                                }
                                if ($mine_social_google_plus != '') {
                                    ?>
                                    <li class="circle google-plus">
                                        <a href="<?php echo esc_html($mine_social_google_plus); ?>" target="_blank"><i class="fa text-center fa-google-plus"></i></a>
                                    </li><?php
                                }
                                if ($mine_social_instagram != '') {
                                    ?>
                                    <li class="circle instagram">
                                        <a href="<?php echo esc_html($mine_social_instagram); ?>" target="_blank"><i class="fa text-center fa-instagram"></i></a>
                                    </li><?php
                                }
                                if ($mine_social_linkedin != '') {
                                    ?>
                                    <li class="circle linkedin">
                                        <a href="<?php echo esc_html($mine_social_linkedin); ?>" target="_blank"><i class="fa text-center fa-linkedin"></i></a>
                                    </li><?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>                
                </div>
                <?php
                if(isset($mine_about_avatar) && $mine_about_avatar != ''){ ?>
                    <div class="col-xs-12 col-sm-8 col-md-8 author_image">
                        <?php easyrotator_display_rotator('erc_21_1538922273');?>
                    </div><?php
                }
            }
            ?>
        </div>
    </div>
</div>
<div id="primary" class="content-area home-content-area">
    <main id="main" class="site-main" role="main">  
        <div class="container">
            <?php if ($mine_disable_blogposts != '1') { ?>
                <div class="widget widget_related_post text-center">
                    <?php 
                    if(isset($mine_blogposts_title) && $mine_blogposts_title != ''){ ?>
                        <h2 id="related_post_title" class="widget-title"><?php print_r($mine_blogposts_title); ?></h2><?php
                    }
                    ?>
                    <div class="row text-left">
                        <?php
                        $post_perpage = $mine_blogposts_items;
                        $block_class = 'col-sm-4 col-md-4';
                        $clear_count = '3';
                        $i = 0;

                        if ($post_perpage % 3 == 0) {
                            $block_class = 'col-sm-4 col-md-4';
                            $clear_count = '3';
                        } elseif ($post_perpage % 2 == 0) {
                            if ($post_perpage == 2) {
                                $block_class = 'col-sm-6 col-md-6';
                                $clear_count = '2';
                            } else {
                                $block_class = 'col-sm-3 col-md-3';
                                $clear_count = '4';
                            }
                        }

                        $args = array();
                        global$wp_query, $post;
                        $args = array(
                            'post_type' => 'post',
                            'orderby' => $mine_blogposts_orderby,
                            'order' => $mine_blogposts_order,
                            'post_status' => 'publish',
                            'ignore_sticky_posts' => true,
                            'posts_per_page' => $post_perpage
                        );
                        if ($mine_blogposts_category != 0) {
                            $args['category__in'] = array($mine_blogposts_category);
                        }
                        $my_query = new wp_query($args);
                        if ($my_query->have_posts()) {
                            while ($my_query->have_posts()) {
                                $my_query->the_post();
                                $clear_class = ($i == $clear_count) ? 'clearleft' : '';
                                $i++;
                                ?>
                                <div class="col-xs-12 text-center <?php echo esc_html($block_class) . ' ' . esc_html($clear_class); ?>">
                                    <div class="related_post">
                                        <?php 
                                        if (!post_password_required() && has_post_thumbnail()) { ?>
                                            <div class="post-thumbnail">
                                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                    <?php the_post_thumbnail('mine-blog-thumbnail'); ?>
                                                </a>           
                                            </div><?php
                                        } elseif(isset($blog_default_image) && $blog_default_image != ""){ ?>
                                            <div class="post-thumbnail">
                                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php 
                                                    echo "<img src='".esc_html($blog_default_image)."' alt='". get_the_title()."'>"; ?>
                                                </a>           
                                            </div><?php
                                        }
                                        if (get_the_title()) { ?>
                                            <header class="entry-header">
                                                <?php the_title(sprintf('<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>'); ?>
                                            </header><?php
                                        } ?>
                                        <footer class="entry-footer">
                                            <div class="post-meta">
                                                <?php mine_blog_posted_on(); ?>
                                                <?php mine_get_blog_post_meta(); ?>
                                            </div>
                                        </footer>
                                    </div>
                                </div>
                                <?php
                            }
                            wp_reset_postdata();
                        }
                        ?>
                    </div>
                </div><?php
            }
            if ($mine_disable_blog_posts != '1') {
                ?>
                <div class="row">
                    <?php 
                    if(isset($mine_grid_blogposts_title) && $mine_grid_blogposts_title != ''){ ?>
                        <div class="widget widget_latest_post text-center">
                            <h2 class="widget-title"><?php print_r($mine_grid_blogposts_title); ?></h2>
                        </div><?php
                    }
                    ?>
                    <div class="col-sm-8 col-xs-12">
                        <?php
                        global $wp_query, $post;
                        $paged = get_query_var('page') ? intval(get_query_var('page')) : 1;
                        $posts_per_page = get_option('posts_per_page');
                        $offset = ( $paged - 1) * $posts_per_page;
                        
                        $args = array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'ignore_sticky_posts' => true,
                            'orderby' => $mine_blog_posts_orderby,
                            'order' => $mine_blog_posts_order,
                            'paged' => $paged,
                            'offset' => $offset,
                            'posts_per_page' => $posts_per_page
                        );
                        
                        if ($mine_blog_posts_category != 0) {
                            $args['category__in'] = array($mine_blog_posts_category);
                        }

                        $my_query = new wp_query($args);
                        if ($my_query->have_posts()) :
                            while ($my_query->have_posts()) :
                                $my_query->the_post();

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
                    <div id="secondary" class="sidebar widget-area col-sm-4 col-xs-12">
                        <?php dynamic_sidebar('primary-sidebar'); ?>
                    </div>
                </div><?php
            }
            ?>
        </div>
    </main>
</div>
<?php
get_footer();