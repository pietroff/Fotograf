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
                                <div class="site-header-logo pull-left">
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
                                <div class="site-header-menu">
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
                </header><!-- .site-header -->
                <div id="content" class="site-content">
