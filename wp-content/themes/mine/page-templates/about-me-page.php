<?php
/**
 * Template Name: About Me Page Template
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

$default_content = __('I am here to help you become a better blogger. I share proven and tested techniques on my blogs to help you achieve the success. Lorem  Ipsum is simply dummy text of the printing and typesetting industry.', 'mine');

$mine_intro_name = get_theme_mod('mine_about_us_name', 'Hello, Albert here&hellip;');
$mine_about_position = get_theme_mod('mine_about_us_position', 'Passionate Blogger');
$mine_about_content = get_theme_mod('mine_about_us_content', $default_content);
$mine_social_facebook = get_theme_mod('mine_social_facebook');
$mine_social_twitter = get_theme_mod('mine_social_twitter');
$mine_social_google_plus = get_theme_mod('mine_social_google_plus');
$mine_social_instagram = get_theme_mod('mine_social_instagram');
$mine_social_linkedin = get_theme_mod('mine_social_linkedin');

$mine_portfolio_title = get_theme_mod('mine_portfolio_title');
$mine_portfolio_content = get_theme_mod('mine_portfolio_content');
$mine_portfolio_shortcode = get_theme_mod('mine_portfolio_shortcode');

$mine_disable_about_us = get_theme_mod('mine_disable_about_us', '0');

?>
<div class="container about-me">
    <?php 
    if ($mine_disable_about_us != '1') { ?>
        <div class="about_author text-center">
            <h2 class="entry-title"><?php print_r($mine_intro_name); ?></h2>
            <span class="user_designation"><?php print_r($mine_about_position); ?></span>
            <div class="author-bio">
                <?php
                print_r($mine_about_content);
                if ($mine_social_facebook != '' || $mine_social_twitter != '' || $mine_social_google_plus != '' || $mine_social_instagram != '' || $mine_social_linkedin != '') {
                    ?>
                    <div class="author_socialicon_block">
                        <ul class="social-media">
                            <?php if ($mine_social_facebook != '') { ?>
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
                    </div><?php
                }
                ?>
            </div>
        </div><?php
    }
    
    if (is_active_sidebar('skills-sidebar')) :
        dynamic_sidebar('skills-sidebar');
    endif;
    ?>
    <div class="about_portfolio text-center">
        <h2 class="entry-title"> <?php print_r($mine_portfolio_title); ?> </h2>
        <span class="user_designation"> <?php print_r($mine_portfolio_content); ?> </span>
        <div class="author-bio">
            <?php echo do_shortcode($mine_portfolio_shortcode); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>