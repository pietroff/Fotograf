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
            <div class="row">
            
            <div class="col-sm-8 col-xs-12 text-center ustawienia-menu-obrazkowego">
            <h2 id="related_post_title" class="widget-title text-center">Fotografia Ślubna</h2>
            <div class="line_title text-center"></div>
                    <div class="line-first col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka">Przygotowania</div>
                    <a href="http://127.0.0.1/Fotografik/?p=52">
                    <img src="http://127.0.0.1/Fotografik/wp-content/uploads/2018/10/SKFotografia0054-1000x668.jpg" alt="Przygotowania" class="obrazek-menu">
                    </a>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka">Ceremonia</div>
                    <a href="http://127.0.0.1/Fotografik/?p=55">
                    <img src="http://127.0.0.1/Fotografik/wp-content/uploads/2018/10/SKFotografia0838-1000x667.jpg" alt="Ceremonia" class="obrazek-menu">
                    </a>
                    </div>	
                    </div>
                    <div class="line-second col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka">Wesela</div>
                    <a href="http://127.0.0.1/Fotografik/?p=57">
                    <img src="http://127.0.0.1/Fotografik/wp-content/uploads/2018/10/SKFotografia0718-1-1000x667.jpg" alt="Wesela" class="obrazek-menu">
                    </a>
                    </div>
                    <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
                    <div class="tytul-obrazka">Sesje</div>
                    <a href="http://127.0.0.1/Fotografik/?p=59">
                    <img src="http://127.0.0.1/Fotografik/wp-content/uploads/2018/10/SKF4700-2-1000x668.jpg" alt="Sesje" class="obrazek-menu">
                    </a>
                    </div>
                    </div>
                    </div><!-- .content-area -->
                    
                    <div id="secondary" class="sidebar widget-area col-sm-4 col-xs-12">
                        <div class="text-center">
                        <img src="http://127.0.0.1/Fotografik/wp-content/uploads/2018/10/circle-black-header.png"  alt="">
                        </div>
                        <?php dynamic_sidebar('primary-sidebar'); ?>
                    </div>
        
        
        
        
            </div>
        </div>
    </main>
</div>












<div id="primary" class="content-area home-content-area">
    <main id="main" class="site-main" role="main">  
        <div class="container">
            <?php if ($mine_disable_blogposts != '1') { ?>
            <div class="row  col-sm-8 col-md-8 text-center">
                <div class="widget widget_related_post text-center ">
                    <?php 
                    if(isset($mine_blogposts_title) && $mine_blogposts_title != ''){ ?>
                        <h2 id="related_post_title" class="widget-title text-center"><?php print_r($mine_blogposts_title); ?></h2><?php
                    }
                    ?>
                    <div class="line_title text-center"></div>
                    
                    </div>
                    
                </div>
                
                <?php
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
                    <div class="col-sm-8 col-xs-12 text-center ustawienia-menu-obrazkowego">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka">Przygotowania</div>
                    <a href="http://127.0.0.1/Fotografik/?p=52">
                    <img src="http://127.0.0.1/Fotografik/wp-content/uploads/2018/10/SKFotografia0054-1000x668.jpg" alt="Przygotowania" class="obrazek-menu">
                    </a>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka">Ceremonia</div>
                    <a href="http://127.0.0.1/Fotografik/?p=55">
                    <img src="http://127.0.0.1/Fotografik/wp-content/uploads/2018/10/SKFotografia0838-1000x667.jpg" alt="Ceremonia" class="obrazek-menu">
                    </a>
                    </div>	
                    </div><!-- .content-area -->

                    <div class="col-sm-8 col-xs-12 text-center ustawienia-menu-obrazkowego">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka">Wesela</div>
                    <a href="http://127.0.0.1/Fotografik/?p=57">
                    <img src="http://127.0.0.1/Fotografik/wp-content/uploads/2018/10/SKFotografia0718-1-1000x667.jpg" alt="Wesela" class="obrazek-menu">
                    </a>
                    </div>
                    <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
                    <div class="tytul-obrazka">Sesje</div>
                    <a href="http://127.0.0.1/Fotografik/?p=59">
                    <img src="http://127.0.0.1/Fotografik/wp-content/uploads/2018/10/SKF4700-2-1000x668.jpg" alt="Sesje" class="obrazek-menu">
                    </a>
                    </div>
							
                    </div><!-- .content-area -->
                   
                    <div id="secondary" class="sidebar widget-area col-sm-4 col-xs-12">
                    <div class="line_title_small text-center"></div>
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