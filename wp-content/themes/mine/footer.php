<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
</div><!-- .site-content -->

<footer id="colophon" class="site-footer" role="contentinfo">           
    <div class="container"> 
        <div class="row">
            <div class="footer_menu_social"><?php
                if (has_nav_menu('footer_menu')) : ?>
                    <div class="footer_menu text-center">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer_menu',
                                'menu_class' => 'list-inline',
                                'menu_id' => 'social-media',
                                'depth' => 1
                        ));
                        ?>
                    </div><?php
                endif;
                
                $mine_footer_social_facebook = get_theme_mod('mine_footer_social_facebook');
                $mine_footer_social_twitter = get_theme_mod('mine_footer_social_twitter');
                $mine_footer_social_google_plus = get_theme_mod('mine_footer_social_google_plus');
                $mine_footer_social_instagram = get_theme_mod('mine_footer_social_instagram');
                $mine_footer_social_linkedin = get_theme_mod('mine_footer_social_linkedin');

                if ($mine_footer_social_facebook != '' || $mine_footer_social_twitter != '' || $mine_footer_social_google_plus != '' || $mine_footer_social_instagram != '' || $mine_footer_social_linkedin != '') { ?>
                    <div class="footer_social_share">
                        <ul class="social-media">
                            <?php 
                            if ($mine_footer_social_facebook != '') { ?>
                                <li class="circle facebook"><a href="<?php echo esc_html($mine_footer_social_facebook); ?>" target="_blank"><i class="fa text-center fa-facebook"></i></a></li><?php
                            }
                            if ($mine_footer_social_twitter != '') { ?>
                                <li class="circle twitter"><a href="<?php echo esc_html($mine_footer_social_twitter); ?>" target="_blank"><i class="fa text-center fa-twitter"></i></a></li><?php
                            }
                            if ($mine_footer_social_google_plus != '') { ?>
                                <li class="circle google-plus"><a href="<?php echo esc_html($mine_footer_social_google_plus); ?>" target="_blank"><i class="fa text-center fa-google-plus"></i></a></li><?php
                            }
                            if ($mine_footer_social_instagram != '') { ?>
                                <li class="circle instagram"><a href="<?php echo esc_html($mine_footer_social_instagram); ?>" target="_blank"><i class="fa text-center fa-instagram"></i></a></li><?php
                            }
                            if ($mine_footer_social_linkedin != '') { ?>
                                <li class="circle linkedin"><a href="<?php echo esc_html($mine_footer_social_linkedin); ?>" target="_blank"><i class="fa text-center fa-linkedin"></i></a></li><?php
                            }
                            ?>
                        </ul>
                    </div><?php
                } ?>
            </div>                   
            <div id="txtCopyright" class="copyright col-xs-12 col-sm-12 col-md-12">
                <?php esc_html_e('Proudly powered by WordPress. Designed by', 'mine'); ?> <a target="_blank" href="<?php echo esc_url("http://www.home-tec.pl/"); ?>"><?php esc_html_e('Home-TEC Piotr Nowicki', 'mine'); ?></a>
            </div>       
        </div>       
    </div>       
</footer><!-- .site-footer -->

</div><!-- .site-inner -->
</div><!-- .site -->
<a id="to_top" href="javascript:void(0);" class="arrow-up">
    <i class="fa fa-chevron-circle-up"></i>
</a>
<?php wp_footer(); ?>
</body>
</html>