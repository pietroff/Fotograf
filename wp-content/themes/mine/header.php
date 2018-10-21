<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
$mine_social_facebook = get_theme_mod('mine_social_facebook');
$mine_social_twitter = get_theme_mod('mine_social_twitter');
$mine_social_google_plus = get_theme_mod('mine_social_google_plus');
$mine_social_instagram = get_theme_mod('mine_social_instagram');
$mine_social_linkedin = get_theme_mod('mine_social_linkedin');
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <?php 
        if (is_singular() && pings_open(get_queried_object())) { ?>
            <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"><?php
        }
        wp_head();
        ?>
    </head>
    <body <?php body_class(); ?>>
        <?php
        $mine_preloader_display = get_theme_mod('mine_preloader_display', '0');
        $mine_preloader = get_theme_mod('mine_preloader');
        if (($mine_preloader_display != 1) && (isset($mine_preloader) && ($mine_preloader != ''))) { ?>
            <div class="loader"><span class="rotating"></span></div><?php
        }
        ?>
        <div id="page" class="site">
            <div class="site-inner">
                <header id="masthead" class="site-header" role="banner">
                    <div class="site-header-main">
                        <div class="container">
                            <div class="header_wrap">
                            <div class="row">
                                <div class="site-header-logo pull-left col-xs-12 col-sm-2 col-md-2">
                                    <div class="site-branding">
                                        <div class="header-image main-logo">
                                            <?php
                                            $site_titile = get_bloginfo('name');
                                            $logo = esc_attr(get_theme_mod('custom_logo'));
                                            if ($logo != '' && function_exists('the_custom_logo')) { ?>
                                                <h1 class="site-title"><?php mine_the_custom_logo(); ?></h1><?php
                                            } else { ?>
                                                <h2 class="site-title <?php echo (!empty($site_titile)) ? "tagline" : "notagline"; ?>"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h2><?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        $description = get_bloginfo('description', 'display');
                                        if ($description || is_customize_preview()) : ?>
                                            <p class="site-description"><?php echo esc_attr($description); ?></p><?php
                                        endif;
                                        ?>
                                    </div><!-- .site-branding -->
                                </div>
                                <div class="col-xs-12 col-sm-2 col-md-2 pull-left social-media-padding">
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
                                
                                <div class="site-header-menu col-xs-12 col-sm-4 col-md-4 ustawienia-menu">
                                    <div class="site-menu-container">
                                        <nav id="site-navigation" class="primary-navigation navbar main-navigation" role="navigation">
                                            <?php
                                                wp_nav_menu(array('theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'nav navbar-nav'));
                                            ?>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </header><!-- .site-header -->
                <div id="content" class="site-content">
