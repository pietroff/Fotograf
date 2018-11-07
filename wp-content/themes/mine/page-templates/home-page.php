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
    <div class="container" style="margin-top: -12px;">
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
                        <?php easyrotator_display_rotator('erc_91_1541571234');?>

                    </div><?php
                }
            }
            ?>
        </div>
    </div>
</div>

<!-- Funkcja obcinająca do 20 słów  the_excerpt();--> 
<?php
function wp_example_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'wp_example_excerpt_length');
?>
<div id="primary" class="content-area home-content-area">
    <main id="main" class="site-main" role="main">  
        <div class="container">
            <div class="row">
            <div class="col-sm-8 col-xs-12 text-center ustawienia-menu-obrazkowego">
            <h2 id="related_post_title" class="widget-title text-center">Fotografia Ślubna</h2>
            <div class="line_title text-center"></div>
                    <div class="line-first col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(105); ?></div>
                    <div class="tekst-obciety"><?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=105');
                     while (have_posts()) : the_post();
                     the_excerpt();
                     endwhile
                    ?></div>
                    <figure class="gallery_hover">
                    <?php the_post_thumbnail(105); ?>
                    <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                        <a href=" <?php echo esc_url(get_permalink(105)); ?>"></a>
                    </figure>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(111); ?></div>
                    <div class="tekst-obciety"><?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=111');
                     while (have_posts()) : the_post();
                     the_excerpt();
                     endwhile
                    ?></div>
                    <figure class="gallery_hover">
                    <?php the_post_thumbnail(111); ?>
                    <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                        <a href="<?php echo esc_url(get_permalink(111)); ?>"></a>
                    </figure>
                    </div>
                    </div>  
                    <div class="line-second col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(114); ?></div>
                    <div class="tekst-obciety"><?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=114');
                     while (have_posts()) : the_post();
                     the_excerpt();
                     endwhile
                    ?></div>
                    <figure class="gallery_hover">
                    <?php the_post_thumbnail(114); ?>
                            <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                        <a href=" <?php echo esc_url(get_permalink(114)); ?>"></a>
                    </figure>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(117); ?></div>
                    <div class="tekst-obciety"><?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=117');
                     while (have_posts()) : the_post();
                     the_excerpt();
                     endwhile
                    ?></div>
                    <figure class="gallery_hover">
                    <?php the_post_thumbnail(117); ?>
                            <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                        <a href=" <?php echo esc_url(get_permalink(117)); ?>"></a>
                    </figure>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="margin-bottom:-15px; margin-top:20px;">
                    <h2 id="related_post_title" class="widget-title-down text-center">Pozostałe usługi</h2>
                    <div class="line_title_down text-center"></div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 szerokosc-galerii">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(67); ?></div>
                    <figure class="gallery_hover">
                    <?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=67');
                     while (have_posts()) : the_post();
                     the_post_thumbnail();
                     endwhile
                    ?>
                    <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                            <a href=" <?php echo esc_url(get_permalink(67)); ?>"></a>
                    </figure>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(73); ?></div>
                    <figure class="gallery_hover">
                    <?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=73');
                     while (have_posts()) : the_post();
                     the_post_thumbnail();
                     endwhile
                    ?>
                    <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                            <a href=" <?php echo esc_url(get_permalink(73)); ?>"></a>
                    </figure>
                    </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 szerokosc-galerii">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(76); ?></div>
                    <figure class="gallery_hover">
                    <?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=76');
                     while (have_posts()) : the_post();
                     the_post_thumbnail();
                     endwhile
                    ?>
                    <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                            <a href=" <?php echo esc_url(get_permalink(76)); ?>"></a>
                    </figure>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(79); ?></div>
                    <figure class="gallery_hover">
                    <?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=79');
                     while (have_posts()) : the_post();
                     the_post_thumbnail();
                     endwhile
                    ?>
                    <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                            <a href=" <?php echo esc_url(get_permalink(79)); ?>"></a>
                    </figure>
                    </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 szerokosc-galerii">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(82); ?></div>
                    <figure class="gallery_hover">
                    <?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=82');
                     while (have_posts()) : the_post();
                     the_post_thumbnail();
                     endwhile
                    ?>
                    <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                            <a href=" <?php echo esc_url(get_permalink(82)); ?>"></a>
                    </figure>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="tytul-obrazka"><?php echo get_the_title(85); ?></div>
                    <figure class="gallery_hover">
                    <?php 
                     $nameofkat = $post->post_name;
                     query_posts('page_id=85');
                     while (have_posts()) : the_post();
                     the_post_thumbnail();
                     endwhile
                    ?>
                    <figcaption>
                                <h3>Przejdź do galerii</h3>
                            </figcaption>
                            <a href=" <?php echo esc_url(get_permalink(85)); ?>"></a>
                    </figure>
                    </div>
                    </div>
                    </div> 
                    </div><!-- .content-area --> 
                    <div id="secondary" class="sidebar widget-area col-sm-4 col-xs-12">
                        <div class="text-center">
                        <h3>Ostatnio na blogu</h3>
                        <img src="http://slawomirkmiecik.pl/wp-content/uploads/2018/10/circle-black-header.png"  alt="">
                        </div>
                        <?php
  $nameofkat = $post->post_name;
  query_posts('posts_per_page=2');
    while (have_posts()) : the_post();
      echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 margines'>";
      echo "<a href='".get_permalink()."'>";
      the_post_thumbnail();
      echo "</a>";
      echo "<h3 class='entry-title'>";
      echo "<a href='".get_permalink()."'>";
            the_title();
      echo "</a>";
      echo "</h3>";
      echo "<div class='wiecej'>";
        the_excerpt();
      echo "</div>";
      echo "<div class='czytaj-wiecej'>";
      echo "<a href='".get_permalink()."'> Czytaj więcej";
      echo "</a>";
      echo "</div>";
      echo "</div>";
   endwhile;
?>
                        <?php dynamic_sidebar('primary-sidebar'); ?>
                    </div>                                                                   
            </div>
        </div>
    </main>
</div>

<?php
get_footer();