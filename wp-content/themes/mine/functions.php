<?php
/**
 * Mine Theme functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!function_exists('mine_setup')) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     *
     * Create your own mine_setup() function to override in a child theme.
     *
     * @since 1.0
     */
    function mine_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Mine Theme, use a find and replace
         * to change 'mine' to the name of your theme in all the template files
         */
        load_theme_textdomain('mine', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');
        
        add_theme_support('custom-background', array('default-color' => 'ffffff' ));
        
        /*
         * Enable support for custom logo.
         *
         *  @since 1.0
         */
        add_theme_support('custom-logo', array(
            'height' => 240,
            'width' => 240,
            'flex-height' => true,
            'flex-width' => true,
        ));

        /*
         * Enable support for widget customizer.
         *
         *  @since 1.0
         */
        add_theme_support('widget-customizer');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'mine'),
            'footer_menu' => __('Footer Menu', 'mine')
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'status',
            'audio',
            'chat',
        ));

        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array('css/editor-style.css', mine_fonts_url()));

        // Indicate widget sidebars can use selective refresh in the Customizer.
        add_theme_support('customize-selective-refresh-widgets');

        add_image_size('mine-latest-blog-thumbnail', 70, 70,true);
        add_image_size('mine-related-blog-thumbnail', 262, 175,true);
        add_image_size('mine-blog-thumbnail', 750, 350,true);

        // Register the new dashboard widget for solwin infotech feed

        if (function_exists('solwin_latest_news_with_product_details')) {
            add_action('wp_dashboard_setup', 'solwin_latest_news_with_product_details');
        }
        if (!function_exists('solwin_latest_news_with_product_details')) {

            /**
             * Add meta box to display solwin news.
             */
            function solwin_latest_news_with_product_details() {
                add_screen_option('layout_columns', array('max' => 3, 'default' => 2));
                add_meta_box('mine_dashboard_widget', __('News From Solwin Infotech', 'mine'), 'solwin_dashboard_widget_news', 'dashboard', 'normal', 'high');
            }

        }
        if (!function_exists('solwin_dashboard_widget_news')) {

            /**
             * display Solwin infotech feed from the live.
             */
            function solwin_dashboard_widget_news() {
                echo '<div class="rss-widget">' .
                '<div class="solwin-news">'
                . '<p><strong>' . esc_html_e('Solwin Infotech News', 'mine') . '</strong></p>';
                wp_widget_rss_output(array(
                    'url' => 'https://www.solwininfotech.com/feed/',
                    'title' => __('News From Solwin Infotech', 'mine'),
                    'items' => 5,
                    'show_summary' => 0,
                    'show_author' => 0,
                    'show_date' => 1
                ));
                echo '</div>';
                $title = $link = $thumbnail = "";
                //get Latest product detail from xml file

                $file = 'https://www.solwininfotech.com/documents/assets/latest_product.xml';
                define('LATEST_PRODUCT_FILE', $file);
                echo '<div class="display-product">'
                . '<div class="product-detail">'
                . '<p><strong>' . esc_html_e('Latest Product', 'mine') . '</strong></p>';
                $response = wp_remote_post(LATEST_PRODUCT_FILE);
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                    echo "<p>" . esc_html_e('Something went wrong:', 'mine') . esc_attr($error_message) . "</p>";
                } else {
                    $body = wp_remote_retrieve_body($response);
                    $xml = simplexml_load_string($body);
                    $title = $xml->item->name;
                    $thumbnail = $xml->item->img;
                    $link = $xml->item->link;
                    $allProducttext = $xml->item->viewalltext;
                    $allProductlink = $xml->item->viewalllink;
                    $moretext = $xml->item->moretext;
                    $needsupporttext = $xml->item->needsupporttext;
                    $needsupportlink = $xml->item->needsupportlink;
                    $customservicetext = $xml->item->customservicetext;
                    $customservicelink = $xml->item->customservicelink;
                    $joinproductclubtext = $xml->item->joinproductclubtext;
                    $joinproductclublink = $xml->item->joinproductclublink;

                    echo '<div class="product-name"><a href="' . esc_attr($link) . '" target="_blank">'
                    . '<img style="max-width:100%;" alt="' . esc_attr($title) . '" src="' . esc_attr($thumbnail) . '"> </a>'
                    . '<a href="' . esc_attr($link) . '" target="_blank">' . esc_attr($title) . '</a>'
                    . '<p><a href="' . esc_attr($allProductlink) . '" target="_blank" class="button button-default">' . esc_attr($allProducttext) . ' &RightArrow;</a></p>'
                    . '<hr>'
                    . '<p><strong>' . esc_attr($moretext) . '</strong></p>'
                    . '<ul>'
                    . '<li><a href="' . esc_attr($needsupportlink) . '" target="_blank">' . esc_attr($needsupporttext) . '</a></li>'
                    . '<li><a href="' . esc_attr($customservicelink) . '" target="_blank">' . esc_attr($customservicetext) . '</a></li>'
                    . '<li><a href="' . esc_attr($joinproductclublink) . '" target="_blank">' . esc_attr($joinproductclubtext) . '</a></li>'
                    . '</ul>'
                    . '</div>';
                }
                echo '</div>'
                . '</div>'
                . '<div class="clear"></div>'
                . '</div>';
            }

        }
    }

endif; // mine_setup

add_action('after_setup_theme', 'mine_setup');

if (!function_exists('mine_content_width')) :

    /**
     * Sets the content width in pixels, based on the theme's design and stylesheet.
     *
     * Priority 0 to make it available to lower priority callbacks.
     *
     * @global int $content_width
     *
     * @since 1.0
     */
    function mine_content_width() {
        $GLOBALS['content_width'] = apply_filters('mine_content_width', 840);
    }

endif;

add_action('after_setup_theme', 'mine_content_width', 0);

if (!function_exists('mine_widgets_init')) :

    /**
     * Registers a widget area.
     *
     * @link https://developer.wordpress.org/reference/functions/register_sidebar/
     *
     * @since 1.0
     */
    function mine_widgets_init() {
        register_sidebar(array(
            'name' => __('Main Sidebar', 'mine'),
            'id' => 'primary-sidebar',
            'description' => __('Add widgets here to appear in your sidebar.', 'mine'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => __('Skill Section', 'mine'),
            'id' => 'skills-sidebar',
            'description' => __('Add widgets here to appear in your about us page template.', 'mine'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '',
            'after_title' => '',
        ));

        register_sidebar(array(
            'name' => esc_html__('Contact Us Page Sidebar', 'mine'),
            'id' => 'contact-us-sidebar',
            'description' => esc_html__('Add widgets here display in Contact Us Page.', 'mine'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }

endif;

add_action('widgets_init', 'mine_widgets_init');

if (!function_exists('mine_fonts_url')) :

    /**
     * Register Google fonts for Mine Theme.
     *
     * Create your own mine_fonts_url() function to override in a child theme.
     *
     * @since 1.0
     *
     * @return string Google fonts URL for the theme.
     */
    function mine_fonts_url() {
        $fonts_url = '';
        $fonts = array();
        $subsets = 'latin,latin-ext';

        /* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Merriweather font: on or off', 'mine')) {
            $fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
        }

        /* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Montserrat font: on or off', 'mine')) {
            $fonts[] = 'Montserrat:400,700';
        }

        /* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Inconsolata font: on or off', 'mine')) {
            $fonts[] = 'Inconsolata:400';
        }

        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urlencode(implode('|', $fonts)),
                'subset' => urlencode($subsets),
                    ), 'https://fonts.googleapis.com/css');
        }

        return $fonts_url;
    }

endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since 1.0
 */
if (!function_exists('mine_javascript_detection')) {

    function mine_javascript_detection() {
        echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
    }

}

add_action('wp_head', 'mine_javascript_detection', 0);

if (!function_exists('mine_scripts')) :

    /**
     * Enqueues scripts and styles.
     *
     * @since 1.0
     */
    function mine_scripts() {

        // Theme stylesheet.
        wp_enqueue_style('mine-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
        wp_enqueue_style('mine-font-awesomes', get_template_directory_uri() . '/css/font-awesome.min.css');
        wp_enqueue_style('mine-flexslider', get_template_directory_uri() . '/css/flexslider.css');
        wp_enqueue_style('mine-meanmenu', get_template_directory_uri() . '/css/meanmenu.css');
        wp_enqueue_style('mine-theme-style', get_stylesheet_uri());

        // Theme js.
        wp_enqueue_script('jquery');
        wp_enqueue_script('mine-bootsrap', get_template_directory_uri() . '/js/bootstrap.min.js');
        wp_enqueue_script('mine-flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js');
        wp_enqueue_script('mine-meanmenu', get_template_directory_uri() . '/js/jquery.meanmenu.js');
        wp_enqueue_script('mine-script-js', get_template_directory_uri() . '/js/script.js');

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        if (is_single() && comments_open()) { 
            wp_enqueue_script('comment-validation','http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js');
        }

        wp_localize_script('mine-script', 'screenReaderText', array(
            'expand' => __('expand child menu', 'mine'),
            'collapse' => __('collapse child menu', 'mine'),
        ));

        wp_localize_script('script-js', 'script_js_string', array(
            'rtl' => is_rtl() ? true : false
        ));
        ?>
        <style>
            .rotating{
                background-image: url("<?php echo esc_html(get_theme_mod('mine_preloader')); ?>");
            }
            .page-header {
                background-image: url("<?php echo esc_html(get_custom_header()->url); ?>");
            }
        </style><?php
        if (!isset(get_custom_header()->url) || get_custom_header()->url == '') {
            ?>
            <style>
                .about_author {
                    padding-bottom: 0;
                }
            </style><?php
        }
    }

endif;

add_action('wp_enqueue_scripts', 'mine_scripts');

if(!function_exists('mine_admin_scripts')) {
    function mine_admin_scripts() {

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'mine-admin-style', get_template_directory_uri() . '/css/admin_style.css' );

        wp_enqueue_script('mine-admin-script', get_template_directory_uri() . '/js/admin_script.js');
        $admin_l10n = array(
            'remove' => __('Remove', 'mine'),
            'skill_title' => __('Skill Title', 'mine'),
            'skill_percentage' => __('Skill Percentage', 'mine'),
            'skill_bar_bg' => __('Skill Bar Background', 'mine'),
            'skill_title_bg' => __('Skill Title Background', 'mine'),
        );
        wp_localize_script('mine-admin-script', 'wpAdminL10n', $admin_l10n);
    }
}

add_action('admin_enqueue_scripts', 'mine_admin_scripts');

if (!function_exists('mine_global_var')) :

    /**
     * make js global variable for site url start
     *
     * @since 1.0
     */
    function mine_global_var() {
        ?>
        <script type="text/javascript">
            var site_url = '<?php echo esc_attr(site_url()); ?>';
            var theme_url = '<?php echo esc_attr(get_template_directory_uri()); ?>';
        </script>
        <?php
    }

endif;

add_action('wp_enqueue_scripts', 'mine_global_var');

if (!function_exists('mine_body_classes')) :

    /**
     * Adds custom classes to the array of body classes.
     *
     * @since 1.0
     *
     * @param array $classes Classes for the body element.
     * @return array (Maybe) filtered body classes.
     */
    function mine_body_classes($classes) {
        // Adds a class of custom-background-image to sites with a custom background image.
        if (get_background_image()) {
            $classes[] = 'custom-background-image';
        }

        // Adds a class of group-blog to sites with more than 1 published author.
        if (is_multi_author()) {
            $classes[] = 'group-blog';
        }

        // Adds a class of no-sidebar to sites without active sidebar.
        if (!is_active_sidebar('primary-sidebar')) {
            $classes[] = 'no-sidebar';
        }

        // Adds a class of hfeed to non-singular pages.
        if (!is_singular()) {
            $classes[] = 'hfeed';
        }

        return $classes;
    }

endif;

add_filter('body_class', 'mine_body_classes');

if (!function_exists('mine_hex2rgb')) :

    /**
     * Converts a HEX value to RGB.
     *
     * @since 1.0
     *
     * @param string $color The original color, in 3- or 6-digit hexadecimal form.
     * @return array Array containing RGB (red, green, and blue) values for the given
     *               HEX code, empty array otherwise.
     */
    function mine_hex2rgb($color) {

        $color = trim($color, '#');

        if (strlen($color) === 3) {
            $r = hexdec(substr($color, 0, 1) . substr($color, 0, 1));
            $g = hexdec(substr($color, 1, 1) . substr($color, 1, 1));
            $b = hexdec(substr($color, 2, 1) . substr($color, 2, 1));
        } else if (strlen($color) === 6) {
            $r = hexdec(substr($color, 0, 2));
            $g = hexdec(substr($color, 2, 2));
            $b = hexdec(substr($color, 4, 2));
        } else {
            return array();
        }

        return array('red' => $r, 'green' => $g, 'blue' => $b);
    }

endif;

add_action('hexToRGBA', 'mine_hex2rgb');

if (!function_exists('minehexToRGBA')) :

    /**
     * @since 1.0
     * Convert hexadecimal to rgba
     */
    function minehexToRGBA($color, $opacity = false) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

endif;

add_action('hexToRGBA', 'minehexToRGBA');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

if (!function_exists('mine_content_image_sizes_attr')) :

    /**
     * Add custom image sizes attribute to enhance responsive image functionality
     * for content images
     *
     * @since 1.0
     *
     * @param string $sizes A source size value for use in a 'sizes' attribute.
     * @param array  $size  Image size. Accepts an array of width and height
     *                      values in pixels (in that order).
     * @return string A source size value for use in a content image 'sizes' attribute.
     */
    function mine_content_image_sizes_attr($sizes, $size) {
        $width = $size[0];

        840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

        if ('page' === get_post_type()) {
            840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
        } else {
            840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
            600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
        }

        return $sizes;
    }

endif;

add_filter('wp_calculate_image_sizes', 'mine_content_image_sizes_attr', 10, 2);

if (!function_exists('mine_post_thumbnail_sizes_attr')) :

    /**
     * Add custom image sizes attribute to enhance responsive image functionality
     * for post thumbnails
     *
     * @since 1.0
     *
     * @param array $attr Attributes for the image markup.
     * @param int   $attachment Image attachment ID.
     * @param array $size Registered image size or flat array of height and width dimensions.
     * @return string A source size value for use in a post thumbnail 'sizes' attribute.
     */
    function mine_post_thumbnail_sizes_attr($attr, $attachment, $size) {
        if ('post-thumbnail' === $size) {
            is_active_sidebar('primary-sidebar') && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
            !is_active_sidebar('primary-sidebar') && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
        }
        return $attr;
    }

endif;

add_filter('wp_get_attachment_image_attributes', 'mine_post_thumbnail_sizes_attr', 10, 3);

if (!function_exists('mine_widget_tag_cloud_args')) :

    /**
     * Modifies tag cloud widget arguments to have all tags in the widget same font size.
     *
     * @since 1.0
     *
     * @param array $args Arguments for tag cloud widget.
     * @return array A new modified arguments.
     */
    function mine_widget_tag_cloud_args($args) {
        $args['largest'] = 1;
        $args['smallest'] = 1;
        $args['unit'] = 'em';
        return $args;
    }

endif;

add_filter('widget_tag_cloud_args', 'mine_widget_tag_cloud_args');

if (!function_exists('mine_paging_nav')) :

    /**
     * @since 1.0
     * blog page pagination.
     */
    function mine_paging_nav($mid_size = 1) {

        // Don't print empty markup if there's only one page.
        if ($GLOBALS['wp_query']->max_num_pages < 2) {
            return;
        }
        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $query_args = array();
        $url_parts = explode('?', $pagenum_link);
        if (isset($url_parts[1])) {
            wp_parse_str($url_parts[1], $query_args);
        }
        $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
        $pagenum_link = trailingslashit($pagenum_link) . '%_%';
        $format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

        if (is_rtl()) {
            $prev_arrow = '<span class="next-prev-arrow"><i class="fa fa-chevron-right"></i></span>';
            $next_arrrow = '<span class="next-prev-arrow"><i class="fa fa-chevron-left"></i></span>';
        } else {
            $prev_arrow = '<span class="next-prev-arrow"><i class="fa fa-chevron-left"></i></span>';
            $next_arrrow = '<span class="next-prev-arrow"><i class="fa fa-chevron-right"></i></span>';
        }
        // Set up paginated links.
        $links = paginate_links(array(
            'base' => $pagenum_link,
            'format' => $format,
            'total' => $GLOBALS['wp_query']->max_num_pages,
            'current' => $paged,
            'mid_size' => $mid_size,
            'add_args' => array_map('urlencode', $query_args),
            'prev_text' => $prev_arrow,
            'next_text' => $next_arrrow,
        ));
        if ($links) :
            ?>
            <nav class="navigation paging-navigation" role="navigation">
                <h1 class="screen-reader-text"><?php esc_html_e('Posts navigation', 'mine'); ?></h1>
                <div class="pagination loop-pagination">
                    <?php print_r($links); ?>
                </div><!-- .pagination -->
            </nav><!-- .navigation -->
            <?php
        endif;
    }

endif;

add_action('wp_footer', 'mine_gallery_format_slider', 7);

if (!function_exists('mine_gallery_format_slider')) {

    function mine_gallery_format_slider() {
        ?>
        <script type="text/javascript">
            jQuery('.flexslider.gallery > ul').flexslider({
                dots: false,
                infinite: true,
                <?php
                if (is_rtl()) {
                    echo 'rtl :true,';
                }
                ?>
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplaySpeed: 3000,
                autoplay: true,
                prevArrow: '<div class="slick-prev"><i class="fa fa-chevron-left"></i></div>',
                nextArrow: '<div class="slick-next"><i class="fa fa-chevron-right"></i></div>'
            });
        </script>
        <?php
    }

}

if (!function_exists('mine_get_video_embed_media')) :

    /**
     * @since 1.0
     * for embed media
     */
    function mine_get_video_embed_media($post_id) {
        $post_format = get_post_format($post_id);
        $post = get_post($post_id);
        $content = $post->post_content;
        $embeds = get_media_embedded_in_content($content);
        if ($post_format == 'gallery') {
            $gallery_images = get_post_gallery_images($post_id);
            ob_start();
            ?>
            <div class="flexslider gallery">
                <ul class="slides">
                    <?php
                    foreach ($gallery_images as $single_gallery_image) {
                        $image_url = str_replace('-150x150', '', $single_gallery_image);
                        ?>
                        <li>
                            <img src="<?php echo esc_html($image_url); ?>" alt="<?php esc_attr_e('gallery image', 'mine'); ?>" />
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <?php
            $gallery_img = ob_get_clean();
            return $gallery_img;
        } elseif (!empty($embeds)) {
            if ($post_format == 'video') {
                if (strpos($embeds[0], 'video') || strpos($embeds[0], 'youtube') || strpos($embeds[0], 'vimeo')) {
                    return $embeds[0];
                }
            } elseif ($post_format == 'audio') {
                if (strpos($embeds[0], 'audio')) {
                    return $embeds[0];
                }
            }
        } else if ($post_format == 'link') {
            if (preg_match('~<a .*?href=[\'"]+(.*?)[\'"]+.*?>(.*?)</a>~ims', $post->post_content, $result)) {
                if (isset($result[0])) {
                    return $result[0];
                }
            }
            return FALSE;
        } else if ($post_format == 'quote') {
            if (preg_match('~<blockquote>([\s\S]+?)</blockquote>~', $content, $matches)) {
                if (isset($matches[0])) {
                    return $matches[0];
                }
            }
            return false;
        } else {
            return false;
        }
    }

endif;

add_filter('mine_video_embed_media', 'mine_get_video_embed_media', 10, 1);

/**
 * Get blog post meta
 */
if (!function_exists('mine_get_blog_post_meta')) :

    function mine_get_blog_post_meta() {

        if ('post' == get_post_type() || 'attachment' == get_post_type()) {

            // for get post format
            $blog_post_format = get_post_format();

            if ($blog_post_format == "") { //standard post
                $post_format_icon = "fa-thumb-tack";
                $blog_post_format = 'standard';
            }
            if ($blog_post_format == "aside") { //aside post
                $post_format_icon = "fa-file-text";
            }
            if ($blog_post_format == "gallery") { //gallery post
                $post_format_icon = "fa-file-image-o";
            }
            if ($blog_post_format == "image") { //image post
                $post_format_icon = "fa-file-image-o";
            }
            if ($blog_post_format == "video") { //video post
                $post_format_icon = "fa-video-camera";
            }
            if ($blog_post_format == "audio") { //audio post
                $post_format_icon = "fa-music";
            }
            if ($blog_post_format == "quote") { //quote post
                $post_format_icon = "fa-quote-left";
            }
            if ($blog_post_format == "link") { //link post
                $post_format_icon = "fa-link";
            }
            if ($blog_post_format == "status") { //status post
                $post_format_icon = "fa-commenting";
            }
            if ($blog_post_format == "chat") { //chat post
                $post_format_icon = "fa-wechat";
            }

            // for get post comment meta            
            if (!post_password_required()) :
                echo '<span class="comments-link"><i class="fa fa-comments"></i> ';
                comments_popup_link('0', '1', '% ');
                echo '</span>';
            endif;
            mine_blog_tags_list();
            if (is_archive()) {
                edit_post_link(__('Edit', 'mine'), '<span class="edit-link"><i class="fa fa-edit"></i> ', '</span>');
            }
        }
    }

endif;

if (!function_exists('mine_blog_posted_on')) :

    /**
     * display post-date/time and author meta information.
     */
    function mine_blog_posted_on() {
        $archive_year = esc_html(get_the_time('Y'));
        $archive_month = esc_html(get_the_time('m'));
        $archive_day = esc_html(get_the_time('d'));
        printf('<span class="entry-date"><i class="fa fa-clock-o"></i> <a href="%1$s" rel="bookmark"><time datetime="%2$s">%3$s</time></a></span> ', esc_url(get_day_link($archive_year, $archive_month, $archive_day)), esc_html(get_the_date('c')), esc_html(get_the_date()));
    }

endif;

if (!function_exists('mine_get_posted_by')) :

    /**
     * display author meta information.
     */
    function mine_get_posted_by() {
        $author_avatar_size = apply_filters('mine_author_avatar_size', 42);
        printf('<span class="author"><i class="fa fa-user"></i> <a class="url fn n" href="%1$s" rel="author">%2$s</a></span>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), get_the_author());
    }

endif;

if (!function_exists('mine_blog_category_list')) :

    /**
     * display post category information.
     */
    function mine_blog_category_list() {
        $post_categories = '';
        // Translators: used between list items, there is a space after the comma.
        $categories_list = get_the_category_list('');
        if ($categories_list != "") {
            $post_categories = '<span class="cat-links">' . ($categories_list) . '</span>';
        }
        print_r($post_categories);
    }

endif;

if (!function_exists('mine_blog_tags_list')) :

    /**
     * display post category information.
     */
    function mine_blog_tags_list() {
        $post_tags = '';
        if ('post' == get_post_type()) {
            $tag_list = get_the_tag_list('', ',');
            $post_tags = '';
            if ($tag_list != "") {
                $post_tags = '<span class="tags"> <i class="fa fa-tags"></i> ' . $tag_list . '</span>';
            }
            print_r($post_tags);
        }
    }

endif;

/*
 * For Breadcrumb
 */
load_template(get_template_directory() . '/inc/breadcrumb.php');

/**
 * @since 1.0
 *
 * @desc Display header title on pages
 * @param page title of page to be displayed
 */
if (!function_exists('mine_header_title')) {

    function mine_header_title($page_title) {
        $header_bg_image = get_theme_mod('header_image', get_template_directory_uri() . '/images/header-bg.jpg');
        $header_bg_class = (!isset($header_bg_image) || $header_bg_image == '' || $header_bg_image == 'remove-header') ? '' : 'header_bg_image'; 
        $site_titile = get_bloginfo('name');
        $logo = esc_attr(get_theme_mod('custom_logo'));
        $header_padding_class = '';
        if ($logo == '' && $site_titile == '') {
            $header_padding_class = 'header_padding_class';
        }
        $mine_disable_breadcrumbs = esc_attr(get_theme_mod('mine_disable_breadcrumbs'));
        ?>
        <div class="page-header <?php print_r($header_bg_class); echo  ' '; print_r($header_padding_class); ?>" style="background-image: url(<?php echo esc_html($header_bg_image); ?>);">
            <div class="container">
            <div class="text-center margines-naglowek"><img src="http://slawomirkmiecik.pl/wp-content/uploads/2018/11/circle-header-down.png"  alt=""></div>
                <h1 class="page-title"><?php print_r($page_title); ?></h1>
                <?php 
                if (!is_front_page() && $mine_disable_breadcrumbs != '1') { ?>
                    <div class="breadcrumbs_block">
                        <?php mine_custom_breadcrumbs(); ?>
                    </div><?php
                }
                ?>
            </div>
        </div>
        <?php
    }

}

if (!function_exists('mine_startsession')) {

    /**
     * action to start session if not started
     *
     * @since 1.0
     */
    function mine_startsession() {
        if (!session_id()) {
            session_start();
        }
    }

}

add_action('init', 'mine_startsession', 1);

get_template_part('inc/widgets/widget', 'blog_post');

get_template_part('inc/widgets/widget', 'skill');

get_template_part('inc/register', 'plugins');

if (!function_exists('mine_list_comments')) {

    /**
     * @since 1.0
     * List comments
     */
    function mine_list_comments($comment, $args, $depth) {
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        ?>
        <<?php echo esc_attr($tag); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent', $comment); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php if (0 != $args['avatar_size']) echo get_avatar($comment, $args['avatar_size'], $default = '', $alt = get_comment_author()); ?>
                    <?php
                    /* translators: %s: comment author link */
                    echo get_comment_author_link($comment) . '</b> <span class="says">' .  esc_html__('says', 'mine') . ':</span>';
                    ?>
                </div><!-- .comment-author -->

                <div class="comment-metadata">
                    <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php
                            echo get_comment_date('', $comment) . ' ' . esc_html_e('at ', 'mine') . ' ' . get_comment_time();
                            ?>
                        </time>
                    </a>
                    <?php edit_comment_link(__('Edit', 'mine'), '<span class="edit-link">', '</span>'); ?>
                </div><!-- .comment-metadata -->

                <?php if ('0' == $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'mine'); ?></p>
                <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <?php
            comment_reply_link(array_merge($args, array(
                'add_below' => 'div-comment',
                'depth' => $depth,
                'max_depth' => $args['max_depth'],
                'before' => '<div class="reply text-left">',
                'after' => '</div>'
            )));
            ?>
        </article><!-- .comment-body -->
        <?php
    }

}

if (!function_exists('mine_add_featured_image_instruction')) {

    /**
     * @since Mine 1.0
     * Customize add Featured Image metabox.
     */
    function mine_add_featured_image_instruction($content) {
        return $content;
    }

    add_filter('admin_post_thumbnail_html', 'mine_add_featured_image_instruction');
}

class mine_description_walker extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $item_output = '';
        global $wp_query;
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';
        $class_names = $value = '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
        $class_names = ' class="' . esc_attr($class_names) . '"';
        $output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        if ($item->object == 'page' && $depth == 0) {
            $varpost = get_post($item->object_id);
            if (is_front_page()) {
                $attributes .= ' href="' . $varpost->post_name . '"';
            } else {
                $attributes .= ' href="' . home_url() . '/' . $varpost->post_name . '"';
            }
        } else {
            $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        }
        if (isset($args->before)) {
            $item_output = $args->before;
        }

        $item_output .= '<a' . $attributes . '>';

        if (isset($args->link_before)) {
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID);
        }
        if (isset($args->link_after)) {
            $item_output .= $args->link_after;
        }
        $item_output .= '</a>';
        if (isset($args->after)) {
            $item_output .= $args->after;
        }
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id);
    }

}

if (!function_exists('mine_nav_menu_list')) :

    /**
     * @since 1.0
     * for display sidebar in admin panel
     */
    function mine_nav_menu_list() {
        $nav_menus = get_terms('nav_menu', array('hide_empty' => true));
        if ($nav_menus) {
            foreach ($nav_menus as $menu) {
                $menu_list[$menu->slug] = ucwords($menu->name);
            }
        }
        return $menu_list;
    }

endif;

if (!function_exists('mine_blog_read_more')) {

    /**
     * for get read more content
     */
    function mine_blog_read_more() {

        $excerpt_length = 50;

        if (has_post_thumbnail()) {
            $excerpt_length = apply_filters('excerpt_length_with_thumbnail', $excerpt_length);
        } else {
            $excerpt_length = apply_filters('excerpt_length_without_thumbnail', $excerpt_length);
        }

        if (!post_password_required()) {
            echo '<div class = "excerpt">';
            $string_s = get_the_excerpt();
            $pos = strrpos($string_s, ".");
            if ($pos !== false) { // note: three equal signs
                $string_s = substr($string_s, 0, $pos + 1);
            }
            if (get_post_format() == 'chat') {
                echo '<div class="entry-content">';
            }
            print_r(strip_tags($string_s));
            if (get_post_format() == 'chat') {
                echo '</div>';
            }
            echo '</div>';
        }

        // read more button display
        if (get_the_excerpt() != "") {
            echo '<div class="read_more">';
            echo '<a href = "' . esc_attr(get_permalink(get_the_ID())) . '">'.esc_html__('Czytaj więcej','mine').'</a>';
            echo '</div>';
        }
    }

}

if (!function_exists('mine_customizer_register')) {

    function mine_customizer_register($wp_customize) {

        class Mine_Required_Area extends WP_Customize_Control {

            public function render_content() {
                echo esc_html__('In order to use the home page of Mine, you need to create a new page from Pages > Add New, in your WordPress Dashboard.', 'mine');
                echo '<br/><br/>';
                echo esc_html__('In the page editing screen, choose the \'Home Page Template\' from the Page templates. After that, set it as your home page from Settings > Reading settings.', 'mine');
                echo '<br/><br/>';
            }

        }

        class Mine_Blogposts_Widgets_Area extends WP_Customize_Control {

            public function render_content() {
                echo esc_html__('The main content of this section is customizable in: Posts > Add New, in your WordPress dashboard.', 'mine');
            }

        }

        class Mine_Category_Dropdown_Control extends WP_Customize_Control {

            private $cats = false;

            public function __construct($manager, $id, $args = array(), $options = array()) {
                $this->cats = get_categories($options);

                parent::__construct($manager, $id, $args);
            }

            /**
             * Render the content of the category dropdown
             *
             * @return HTML
             */
            public function render_content() {
                if (!empty($this->cats)) {
                    ?>
                    <label>
                        <span class="customize-category-select-control"><?php echo esc_html($this->label); ?></span>
                        <select <?php $this->link(); ?>>
                            <option value="0"><?php echo esc_html_e('All Posts', 'mine'); ?></option>
                            <?php
                            foreach ($this->cats as $cat) {
                                printf('<option value="%s" %s>%s</option>', esc_html($cat->term_id), selected($this->value(), esc_html($cat->term_id), false), esc_html($cat->name));
                            }
                            ?>
                        </select>
                    </label>
                    <?php
                }
            }

        }

        class Mine_Orderby_Dropdown_Control extends WP_Customize_Control {

            public function __construct($manager, $id, $args = array(), $options = array()) {
                parent::__construct($manager, $id, $args);
            }

            /**
             * Render the post order by
             *
             * @return HTML
             */
            public function render_content() {
                ?>
                <label>
                    <span class="customize-orderby-select-control"><?php echo esc_html($this->label); ?></span>
                    <select <?php $this->link(); ?>>
                        <option value="date" <?php selected($this->value(), 'date', false); ?>><?php echo esc_html_e('Date', 'mine'); ?></option>
                        <option value="title" <?php selected($this->value(), 'title', false); ?>><?php echo esc_html_e('Title', 'mine'); ?></option>
                        <option value="modified" <?php selected($this->value(), 'modified', false); ?>><?php echo esc_html_e('Modified', 'mine'); ?></option>
                        <option value="rand" <?php selected($this->value(), 'rand', false); ?>><?php echo esc_html_e('Random', 'mine'); ?></option>
                    </select>
                </label>
                <?php
            }

        }

        class Mine_Order_Dropdown_Control extends WP_Customize_Control {

            public function __construct($manager, $id, $args = array(), $options = array()) {
                parent::__construct($manager, $id, $args);
            }

            /**
             * Render the post order by
             *
             * @return HTML
             */
            public function render_content() {
                ?>
                <label>
                    <span class="customize-order-select-control"><?php echo esc_html($this->label); ?></span>
                    <select <?php $this->link(); ?>>
                        <option value="DESC" <?php selected($this->value(), 'DESC', false); ?>><?php echo esc_html_e('Descending', 'mine'); ?></option>
                        <option value="ASC" <?php selected($this->value(), 'ASC', false); ?>><?php echo esc_html_e('Ascending', 'mine'); ?></option>
                    </select>
                </label>
                <?php
            }

        }

        if ('posts' == get_option('show_on_front')):
            $wp_customize->add_section('mine_required_action', array(
                'priority' => 5,
                'title' => __('Configure Your Home page', 'mine')
            ));
        endif;

        $wp_customize->add_setting('mine_required_info', array(
            'sanitize_callback' => 'mine_sanitize_text'
        ));

        $wp_customize->add_control(new Mine_Required_Area($wp_customize, 'mine_required_info', array(
            'section' => 'mine_required_action'
        )));

        $wp_customize->get_section('title_tagline')->panel = 'mine_general_settings';

        $wp_customize->get_section('title_tagline')->priority = 15;

        $wp_customize->get_section('header_image')->panel = 'mine_general_settings';

        $wp_customize->get_section('header_image')->title = __('Header', 'mine');

        $wp_customize->get_section('header_image')->priority = 30;
        
        $wp_customize->get_section('static_front_page')->title = __('Front page settings', 'mine');

        $wp_customize->get_section('colors')->priority = 80;

        $wp_customize->get_section('colors')->transport = 'refresh';

        $wp_customize->get_setting('blogname')->transport = 'refresh';

        $wp_customize->get_setting('blogdescription')->transport = 'refresh';

        $wp_customize->get_setting('header_image')->transport = 'refresh';

        $wp_customize->get_setting('header_image')->default = get_template_directory_uri() . '/images/header-bg.jpg';

        $wp_customize->get_setting('header_image_data')->transport = 'refresh';

        $wp_customize->add_panel('mine_general_settings', array(
            'priority' => 10,
            'capability' => 'edit_theme_options',
            'title' => __('General Settings', 'mine'),
            'description' => __('This section allows you to configure general settings.', 'mine')
        ));
        $wp_customize->add_panel('mine_homepage_settings', array(
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'title' => __('Home Page Settings', 'mine'),
            'description' => __('This section allows you to configure about us page settings.', 'mine')
        ));
        $wp_customize->add_panel('mine_about_page_settings', array(
            'priority' => 30,
            'capability' => 'edit_theme_options',
            'title' => __('About Us Page Settings', 'mine'),
            'description' => __('This section allows you to configure about us page settings.', 'mine')
        ));
        $wp_customize->add_panel('mine_blog_settings', array(
            'priority' => 40,
            'capability' => 'edit_theme_options',
            'title' => __('Blog Settings', 'mine'),
            'description' => __('This section allows you to configure blog settings.', 'mine')
        ));

        $wp_customize->add_section('mine_home_intro_section_setting', array(
            'priority' => 10,
            'capability' => 'edit_theme_options',
            'title' => __('Intro Section', 'mine'),
            'description' => __('This section allows you to configure Intro section.', 'mine'),
            'panel' => 'mine_homepage_settings',
        ));
        $wp_customize->add_section('mine_general_preloader', array(
            'priority' => 20,
            'title' => __('Preloader', 'mine'),
            'panel' => 'mine_general_settings'
        ));
        $wp_customize->add_section('mine_footer_socials', array(
            'priority' => 30,
            'title' => __('Footer Social Icons', 'mine'),
            'panel' => 'mine_general_settings'
        ));
        $wp_customize->add_section('mine_blog_section_setting', array(
            'default' => '#',
            'priority' => 40,
            'capability' => 'edit_theme_options',
            'title' => __('Blog Grid Layout', 'mine'),
            'description' => __('This section allows you to configure grid layout for Blog section.', 'mine'),
            'panel' => 'mine_homepage_settings',
        ));
        $wp_customize->add_section('mine_blogposts_settings', array(
            'priority' => 50,
            'capability' => 'edit_theme_options',
            'title' => __('Blog Column Layout', 'mine'),
            'description' => __('This section allows you to configure column layout for Blog section.', 'mine'),
            'panel' => 'mine_homepage_settings',
        ));        
        $wp_customize->add_section('mine_about_section', array(
            'priority' => 10,
            'capability' => 'edit_theme_options',
            'title' => __('About Us Section', 'mine'),
            'description' => __('This section allows you to configure About Us section.', 'mine'),
            'panel' => 'mine_about_page_settings',
        ));
        $wp_customize->add_section('mine_portfolio_section', array(
            'priority' => 30,
            'capability' => 'edit_theme_options',
            'title' => __('Portfolio Section', 'mine'),
            'description' => __('This section allows you to configure portfolio section.', 'mine'),
            'panel' => 'mine_about_page_settings',
        ));
        $wp_customize->add_section('mine_blog_settings_section', array(
            'priority' => 30,
            'title' => __('Blog Settings', 'mine'),
            'panel' => 'mine_blog_settings'
        ));

        $wp_customize->add_setting('mine_preloader_display', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_checkbox'
        ));

        $wp_customize->add_control('mine_preloader_display', array(
            'type' => 'checkbox',
            'label' => __('Disable Preloader', 'mine'),
            'section' => 'mine_general_preloader'
        ));
        
        $wp_customize->add_setting('mine_disable_breadcrumbs', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_checkbox'
        ));

        $wp_customize->add_control('mine_disable_breadcrumbs', array(
            'type' => 'checkbox',
            'label' => __('Disable Breadcrumbs', 'mine'),
            'section' => 'header_image'
        ));

        $wp_customize->add_setting('mine_preloader', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_url_raw'
        ));

        $wp_customize->add_control(
                new WP_Customize_Image_Control(
                $wp_customize, 'mine_preloader', array(
            'label' => __('Preloader', 'mine'),
            'section' => 'mine_general_preloader',
            'settings' => 'mine_preloader'
        )));

        $wp_customize->add_setting('mine_footer_social_facebook', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_footer_social_facebook', array(
            'label' => __('Facebook URL', 'mine'),
            'description' => __('Enter your Facebook URL.', 'mine'),
            'section' => 'mine_footer_socials',
            'settings' => 'mine_footer_social_facebook'
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_footer_social_facebook', array(
                'selector' => '.footer_social_share',
                'settings' => 'mine_footer_social_facebook',
                'render_callback' => mine_selective_refresh_callback('mine_footer_social_facebook')
            ));
	}
        
        $wp_customize->add_setting('mine_footer_social_twitter', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_footer_social_twitter', array(
            'label' => __('Twitter URL', 'mine'),
            'description' => __('Enter your Twitter URL.', 'mine'),
            'section' => 'mine_footer_socials',
            'settings' => 'mine_footer_social_twitter'
        ));
        
        $wp_customize->add_setting('mine_footer_social_google_plus', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_footer_social_google_plus', array(
            'label' => __('Google Plus URL', 'mine'),
            'description' => __('Enter your Google Plus URL.', 'mine'),
            'section' => 'mine_footer_socials',
            'settings' => 'mine_footer_social_google_plus'
        ));
                
        $wp_customize->add_setting('mine_footer_social_instagram', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_footer_social_instagram', array(
            'label' => __('Instagram URL', 'mine'),
            'description' => __('Enter your Instagram URL.', 'mine'),
            'section' => 'mine_footer_socials',
            'settings' => 'mine_footer_social_instagram'
        ));
        
        $wp_customize->add_setting('mine_footer_social_linkedin', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_footer_social_linkedin', array(
            'label' => __('Linkedin URL', 'mine'),
            'description' => __('Enter your Linkedin URL.', 'mine'),
            'section' => 'mine_footer_socials',
            'settings' => 'mine_footer_social_linkedin'
        ));
        
        $wp_customize->add_setting('mine_disable_intro', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_checkbox',
            'type' => 'theme_mod'
        ));

        $wp_customize->add_control('mine_disable_intro', array(
            'label' => __("Disable Intro Section ?", 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_disable_intro',
            'type' => 'checkbox'
        ));

        $wp_customize->add_setting('mine_about_name', array(
            'default' => esc_html__('Hello, Albert here...', 'mine'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_control('mine_about_name', array(
            'label' => __('Name', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_about_name'
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_about_name', array(
                'selector' => '.page-header.about_author .entry-title',
                'settings' => 'mine_about_name',
                'render_callback' => mine_selective_refresh_callback('mine_about_name')
            ));
	}
        
        $wp_customize->add_setting('mine_about_position', array(
            'default' => esc_html__('Passionate Blogger', 'mine'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_about_position', array(
            'label' => __('Position', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_about_position'
        ));
        
        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_about_position', array(
                'selector' => '.page-header.about_author .user_designation',
                'settings' => 'mine_about_position',
                'render_callback' => mine_selective_refresh_callback('mine_about_position')
            ));
	}

        $wp_customize->add_setting('mine_about_content', array(
            'capability' => 'edit_theme_options',
            'default' => esc_html__('I am here to help you become a better blogger. I share proven and tested techniques on my blogs to help you achieve the success. Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'mine'),
            'sanitize_callback' => 'mine_sanitize_textbox',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_about_content', array(
            'label' => __('Content', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'type' => 'textarea',
            'settings' => 'mine_about_content'
        ));

        if (isset($wp_customize->selective_refresh)) {
            $wp_customize->selective_refresh->add_partial('mine_about_content', array(
                'selector' => '.page-header.about_author .author-bio',
                'settings' => 'mine_about_content',
                'render_callback' => mine_selective_refresh_callback('mine_about_content_callback')
            ));
        }
        
        $wp_customize->add_setting('disable_read_more_button', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_checkbox',
            'type' => 'theme_mod'
        ));

        $wp_customize->add_control('disable_read_more_button', array(
            'label' => __("Disable Read More ?", 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'disable_read_more_button',
            'type' => 'checkbox'
        ));

        if (isset($wp_customize->selective_refresh)) {
            $wp_customize->selective_refresh->add_partial('mine_about_content', array(
                'selector' => '.page-header.about_author .author-bio',
                'settings' => 'mine_about_content',
                'render_callback' => mine_selective_refresh_callback('mine_about_content')
            ));
        }
        
        $wp_customize->add_setting('mine_read_more_text', array(
            'default' => esc_html__('Know more', 'mine'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_read_more_text', array(
            'label' => __('Read More Text', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_read_more_text'
        ));

        if (isset($wp_customize->selective_refresh)) {
            $wp_customize->selective_refresh->add_partial('mine_read_more_text', array(
                'selector' => '.page-header.about_author .read_more a',
                'settings' => 'mine_read_more_text',
                'render_callback' => mine_selective_refresh_callback('mine_read_more_text')
            ));
        }
        
        $wp_customize->add_setting('mine_read_more_link', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_read_more_link', array(
            'label' => __('Read More Link', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_read_more_link'
        ));

        $wp_customize->add_setting('mine_about_avatar', array(
            'default' => get_template_directory_uri() . '/images/about.png',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'mine_about_avatar', array(
            'label' => __('Image', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_about_avatar'
        )));
        
        if (isset($wp_customize->selective_refresh)) {
            $wp_customize->selective_refresh->add_partial('mine_about_avatar', array(
                'selector' => '.author_image',
                'settings' => 'mine_about_avatar',
                'render_callback' => mine_selective_refresh_callback('mine_about_avatar')
            ));
        }
        
        $wp_customize->add_setting('mine_social_facebook', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_social_facebook', array(
            'label' => __('Facebook URL', 'mine'),
            'description' => __('Enter your Facebook URL.', 'mine').' <b>'.__('Note', 'mine').'</b> : '.__('This URL is also used in about us page.', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_social_facebook'
        ));
        
        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_social_facebook', array(
                'selector' => '.author_socialicon_block',
                'settings' => 'mine_social_facebook',
                'render_callback' => mine_selective_refresh_callback('mine_social_facebook')
            ));
	}

        $wp_customize->add_setting('mine_social_twitter', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('mine_social_twitter', array(
            'label' => __('Twitter URL', 'mine'),
            'description' => __('Enter your Twitter URL.', 'mine').' <b>'.__('Note', 'mine').'</b> : '.__('This URL is also used in about us page.', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_social_twitter'
        ));
         
        $wp_customize->add_setting('mine_social_google_plus', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_social_google_plus', array(
            'label' => __('Google Plus URL', 'mine'),
            'description' => __('Enter your Google Plus URL.', 'mine').' <b>'.__('Note', 'mine').'</b> : '.__('This URL is also used in about us page.', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_social_google_plus'
        ));
        
        $wp_customize->add_setting('mine_social_instagram', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_social_instagram', array(
            'label' => __('Instagram URL', 'mine'),
            'description' => __('Enter your Instagram URL.', 'mine').' <b>'.__('Note', 'mine').'</b> : '.__('This URL is also used in about us page.', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_social_instagram'
        ));
        
        $wp_customize->add_setting('mine_social_linkedin', array(
            'default' => '#',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control('mine_social_linkedin', array(
            'label' => __('Linkedin URL', 'mine'),
            'description' => __('Enter your Linkedin URL.', 'mine').' <b>'.__('Note', 'mine').'</b> : '.__('This URL is also used in about us page.', 'mine'),
            'section' => 'mine_home_intro_section_setting',
            'settings' => 'mine_social_linkedin'
        ));
        
        $wp_customize->add_setting('mine_disable_blogposts', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_checkbox'
        ));

        $wp_customize->add_control('mine_disable_blogposts', array(
            'type' => 'checkbox',
            'label' => __('Disable Blog Section', 'mine'),
            'section' => 'mine_blog_section_setting'
        ));
        
        $wp_customize->add_setting('mine_blogposts_title', array(
            'default' => esc_html__('Latest From The Blog', 'mine'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_blogposts_title', array(
            'label' => __('Section Title', 'mine'),
            'section' => 'mine_blog_section_setting',
            'settings' => 'mine_blogposts_title'
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_blogposts_title', array(
                'selector' => '.home-content-area .widget_related_post .widget-title',
                'settings' => 'mine_blogposts_title',
                'render_callback' => mine_selective_refresh_callback('mine_blogposts_title')
            ));
	}
        
        $wp_customize->add_setting('mine_blogposts_items', array(
            'default' => 4,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_text'
        ));

        $wp_customize->add_control('mine_blogposts_items', array(
            'label' => __('Number of Items', 'mine'),
            'section' => 'mine_blog_section_setting',
            'type' => 'number',
            'settings' => 'mine_blogposts_items'
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_blogposts_items', array(
                'selector' => '.home-content-area .widget_related_post .widget-title',
                'settings' => 'mine_blogposts_items',
                'render_callback' => mine_selective_refresh_callback('mine_blogposts_items')
            ));
	}
        
        $wp_customize->add_setting('mine_blogposts_category', array(
            'default' => 0,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_control(new Mine_Category_Dropdown_Control(
                $wp_customize, 'mine_blogposts_category', array(
                'label' => __('Display Posts From(Select post category):', 'mine'),
                'section' => 'mine_blog_section_setting',
                'settings' => 'mine_blogposts_category'
        )));

        $wp_customize->add_setting('mine_blogposts_orderby', array(
            'default' => 'date',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_control(new Mine_Orderby_Dropdown_Control(
                $wp_customize, 'mine_blogposts_orderby', array(
            'label' => __('Sort post order by:', 'mine'),
            'section' => 'mine_blog_section_setting',
            'settings' => 'mine_blogposts_orderby'
        )));

        $wp_customize->add_setting('mine_blogposts_order', array(
            'default' => 'DESC',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_control(new Mine_Order_Dropdown_Control(
                $wp_customize, 'mine_blogposts_order', array(
            'label' => __('Sort post order:', 'mine'),
            'section' => 'mine_blog_section_setting',
            'settings' => 'mine_blogposts_order'
        )));

        $wp_customize->add_setting('mine_disable_blog_posts', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_checkbox'
        ));

        $wp_customize->add_control('mine_disable_blog_posts', array(
            'type' => 'checkbox',
            'label' => __('Disable Blog Section', 'mine'),
            'section' => 'mine_blogposts_settings'
        ));
        
        $wp_customize->add_setting('mine_grid_blogposts_title', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_grid_blogposts_title', array(
            'label' => __('Section Title', 'mine'),
            'section' => 'mine_blogposts_settings',
            'settings' => 'mine_grid_blogposts_title'
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_grid_blogposts_title', array(
                'selector' => '.home-content-area .row .widget_latest_post .widget-title',
                'settings' => 'mine_grid_blogposts_title',
                'render_callback' => mine_selective_refresh_callback('mine_grid_blogposts_title')
            ));
	}

        $wp_customize->add_setting('mine_blog_posts_category', array(
            'default' => 0,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_control(new Mine_Category_Dropdown_Control(
                $wp_customize, 'mine_blog_posts_category', array(
            'label' => __('Display Posts From(Select post category):', 'mine'),
            'section' => 'mine_blogposts_settings',
            'settings' => 'mine_blog_posts_category'
        )));

        $wp_customize->add_setting('mine_blog_posts_orderby', array(
            'default' => 'rand',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_control(new Mine_Orderby_Dropdown_Control(
                $wp_customize, 'mine_blog_posts_orderby', array(
            'label' => __('Sort post order by:', 'mine'),
            'section' => 'mine_blogposts_settings',
            'settings' => 'mine_blog_posts_orderby'
        )));

        $wp_customize->add_setting('mine_blog_posts_order', array(
            'default' => 'DESC',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_control(new Mine_Order_Dropdown_Control(
                $wp_customize, 'mine_blog_posts_order', array(
            'label' => __('Sort post order:', 'mine'),
            'section' => 'mine_blogposts_settings',
            'settings' => 'mine_blog_posts_order'
        )));

        $wp_customize->add_setting('mine_disable_about_us', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_checkbox',
            'type' => 'theme_mod',
            'transport' => 'refresh'
        ));
        
        $wp_customize->add_control('mine_disable_about_us', array(
            'label' => __("Disable About Us Section ?", 'mine'),
            'section' => 'mine_about_section',
            'settings' => 'mine_disable_about_us',
            'type' => 'checkbox'
        ));

        $wp_customize->add_setting('mine_about_us_name', array(
            'default' => esc_html__('Hello, Albert here...', 'mine'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_about_us_name', array(
            'label' => __('Name', 'mine'),
            'section' => 'mine_about_section',
            'settings' => 'mine_about_us_name'
        ));
        
        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_about_us_name', array(
                'selector' => '.about-me .about_author .entry-title',
                'settings' => 'mine_about_us_name',
                'render_callback' => mine_selective_refresh_callback('mine_about_us_name')
            ));
	}
        
        $wp_customize->add_setting('mine_about_us_position', array(
            'default' => esc_html__('Passionate Blogger', 'mine'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_about_us_position', array(
            'label' => __('Position', 'mine'),
            'section' => 'mine_about_section',
            'settings' => 'mine_about_us_position'
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_about_us_position', array(
                'selector' => '.about-me .about_author .user_designation',
                'settings' => 'mine_about_us_position',
                'render_callback' => mine_selective_refresh_callback('mine_about_us_position')
            ));
	}
        
        $wp_customize->add_setting('mine_about_us_content', array(
            'default' => esc_html__('I am here to help you become a better blogger. I share proven and tested techniques on my blogs to help you achieve the success. Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'mine'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_textbox',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_about_us_content', array(
            'label' => __('Content', 'mine'),
            'section' => 'mine_about_section',
            'type' => 'textarea',
            'settings' => 'mine_about_us_content'
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_about_us_content', array(
                'selector' => '.about-me .about_author .author-bio',
                'settings' => 'mine_about_us_content',
                'render_callback' => mine_selective_refresh_callback('mine_about_us_content')
            ));
	}
        $wp_customize->add_setting('mine_portfolio_title', array(
            'default' => esc_html__('Portfolio', 'mine'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_portfolio_title', array(
            'label' => __('Portfolio Title', 'mine'),
            'section' => 'mine_portfolio_section',
            'settings' => 'mine_portfolio_title'
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_portfolio_title', array(
                'selector' => '.about-me .about_portfolio .entry-title',
                'settings' => 'mine_portfolio_title',
                'render_callback' => mine_selective_refresh_callback('mine_portfolio_title')
            ));
	}
        $wp_customize->add_setting('mine_portfolio_content', array(
            'default' => esc_html__('Portfolio Content', 'mine'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_textbox',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_portfolio_content', array(
            'label' => __('Portfolio Content', 'mine'),
            'section' => 'mine_portfolio_section',
            'type' => 'textarea',
            'settings' => 'mine_portfolio_content'
        ));
        
        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_portfolio_content', array(
                'selector' => '.about-me .about_portfolio .user_designation',
                'settings' => 'mine_portfolio_content',
                'render_callback' => mine_selective_refresh_callback('mine_portfolio_content')
            ));
	}
        $wp_customize->add_setting('mine_portfolio_shortcode', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_textbox',
            'transport' => 'refresh'
        ));

        $wp_customize->add_control('mine_portfolio_shortcode', array(
            'label' => __('Portfolio Shortcode', 'mine'),
            'description' => __('Enter your shortcode here, to display portfolios on about us page.', 'mine').' '.__('You can use','mine').' <a href="https://wordpress.org/plugins/portfolio-designer-lite/">'.__('Portfolio Designer Lite','mine').'</a> '.__('plugin to showcase your portfolio section','mine').'. '.__('Shortcode for this plugin is','mine').' : [wp_portfolio_designer_lite]',
            'section' => 'mine_portfolio_section',
            'type' => 'textarea',
            'settings' => 'mine_portfolio_shortcode'
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial('mine_portfolio_shortcode', array(
                'selector' => '.about-me .about_portfolio .author-bio',
                'settings' => 'mine_portfolio_shortcode',
                'render_callback' => mine_selective_refresh_callback('mine_portfolio_shortcode')
            ));
	}
        $wp_customize->add_setting('mine_enable_related_post', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_checkbox'
        ));

        $wp_customize->add_control('mine_enable_related_post', array(
            'type' => 'checkbox',
            'description' => '<b>'.__('Note','mine').': </b>'.__('This option is for single blog page.','mine'),
            'label' => __('Disable Related Posts', 'mine'),
            'section' => 'mine_blog_settings_section'
        ));

        $wp_customize->add_setting('mine_blog_default_image', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'refresh'
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'mine_blog_default_image', array(
            'label' => __('Default Image', 'mine'),
            'section' => 'mine_blog_settings_section',
            'settings' => 'mine_blog_default_image'
        )));
        
        $wp_customize->add_setting('mine_display_bio', array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'mine_sanitize_checkbox'
        ));

        $wp_customize->add_control('mine_display_bio', array(
            'type' => 'checkbox',
            'description' => '<b>'.__('Note','mine').': </b>'.__('Enable/Disable author biography on author page and single post page.', 'mine'),
            'label' => __('Disable Author Biography', 'mine'),
            'section' => 'mine_blog_settings_section'
        ));
        
        $wp_customize->add_setting( 'mine_blog_sidebar', array(
		'default' => 'right',
                'transport' => 'refresh',
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'mine_sanitize_choices'
	));
	$wp_customize->add_control( 'mine_blog_sidebar', array(
		'label' => __('Layout', 'mine'),
		'type' => 'radio',
                'description' => '<b>'.__('Note','mine').': </b>'.__('This layout is apply on single blog page.','mine'),
		'section' => 'mine_blog_settings_section',
		'choices' => array(
			'full' => __('Full Width', 'mine'),
			'left' => __('Left Sidebar', 'mine'),
			'right' => __('Right Sidebar', 'mine'),
		)
	));
    }

    add_action('customize_register', 'mine_customizer_register');
}

if (!function_exists('mine_sanitize_text')) {

    function mine_sanitize_text($input) {
        return $input;
    }

}

if (!function_exists('mine_sanitize_textbox')) {

    function mine_sanitize_textbox($textbox) {
        return wp_kses_post(force_balance_tags($textbox));
    }

}

if (!function_exists('mine_sanitize_checkbox')) {

    function mine_sanitize_checkbox($input) {
        if ($input) {
            $output = '1';
        } else {
            $output = false;
        }
        return $output;
    }

}

if (!function_exists('mine_sanitize_choices')) {

    function mine_sanitize_choices($input, $setting) {
        global $wp_customize;

        $control = $wp_customize->get_control($setting->id);

        if (array_key_exists($input, $control->choices)) {
            return $input;
        } else {
            return $setting->default;
        }
    }

}

if (!function_exists('mine_blog_image')) {

    function mine_blog_image() {
        $blog_default_image = get_theme_mod('mine_blog_default_image');
        if (!post_password_required() && (has_post_thumbnail() || $blog_default_image != '')) {
            ?>
            <div class="post-thumbnail <?php
            if (is_sticky() && (is_home() || is_archive()) && !is_paged()) {
                echo "post-thumbnail-sticky";
            }
            ?>"><?php 
                if (!is_single()) { ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php
                    }
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('mine-blog-thumbnail');
                    } elseif (isset($blog_default_image) && $blog_default_image != "") {
                        echo "<img src='".esc_html($blog_default_image)."' alt='". get_the_title()."' width='360' height='240'>";
                    } 
                if (!is_single()) { ?>
                    </a><?php
                }
                if (is_sticky() && (is_home() || is_archive()) && !is_paged()) :
                    ?>
                    <span class="sticky-post"><?php esc_html_e('Featured', 'mine'); ?></span><?php endif;
                ?>
            </div><?php
        }
    }

}

if(!function_exists('mine_comment_validation')){
    function mine_comment_validation() {
        if (is_single() && comments_open()) { ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#commentform').validate({
                        rules: {
                            author: {
                                required: true,
                                minlength: 2
                            },

                            email: {
                                required: true,
                                email: true
                            },

                            comment: {
                                required: true
                            }
                        },

                        messages: {
                            author: "Please fill the required field",
                            email: "Please enter a valid email address.",
                            comment: "Please fill the required field"
                        },

                        errorElement: "div",
                        errorPlacement: function(error, element) {
                            element.after(error);
                        }

                    });
                });
            </script>
            <?php
        }
    }    
}

add_action('wp_footer', 'mine_comment_validation');

if(!function_exists('mine_selective_refresh_callback')){
    function mine_selective_refresh_callback($arg) {
        return get_theme_mod($arg);
    }    
}

if(!function_exists('mine_post_social_share')){
    function mine_post_social_share() {
        $share_link = get_permalink(get_the_ID()); ?>
        <div class="single-blog-social author_socialicon_block">
            <ul class="social-media">
                <li class="circle facebook">
                    <a title="facebook" class="sol_shareicon facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php print_r($share_link); ?>" target="_blank">
                        <i class="fa fa-facebook"></i>
                    </a>    
                </li>
                <li class="circle twitter">
                    <a title="twitter" class="sol_shareicon twitter" href="https://twitter.com/share?url=<?php print_r($share_link); ?>" target="_blank">
                        <i class="fa fa-twitter"></i>
                    </a>
                </li>
                <li class="circle google-plus">
                    <a title="google-plus" class="sol_shareicon google-plus" href="https://plus.google.com/share?url=<?php print_r($share_link); ?>" target="_blank">
                        <i class="fa fa-google-plus"></i>
                    </a>
                </li>
                <li class="circle linkedin">
                    <a title="linkedin" class="sol_shareicon linkedin" href="http://www.linkedin.com/shareArticle?url=<?php print_r($share_link); ?>" target="_blank">
                        <i class="fa fa-linkedin"></i>
                    </a>
                </li>
            </ul>
        </div><?php
    }    
}
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
    register_nav_menus(
        array(
            'primary-menu' => __( 'Primary Menu' )
        )
    );
}