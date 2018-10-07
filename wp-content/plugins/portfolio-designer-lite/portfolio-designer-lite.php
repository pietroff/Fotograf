<?php
/*
 * Plugin Name: Portfolio Designer Lite
 * Plugin URI: https://wordpress.org/plugins/portfolio-designer-lite/
 * Description: It allows you to create, manage and design your portfolio and showcase with few clicks.
 * Version: 1.0.3
 * Author: Solwin Infotech
 * Author URI: https://www.solwininfotech.com/
 * Requires at least: 4.0
 * Tested up to: 4.9.6
 *
 * Text Domain: portfolio-designer-lite
 * Domain Path: /languages/
 *
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('PORT_LITE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PORT_LITE_PLUGIN_DIR', plugin_dir_path(__FILE__));

class PortfolioDesignerLite {

    protected static $plugin_version = '1.0.3';
    protected $plugin_slug = 'portfolio-designer-lite';
    protected static $instance = null;

    /*
     * Add menu to admin panel for portfolio designer lite settings
     */

    public function __construct() {

        require_once PORT_LITE_PLUGIN_DIR . 'includes/functions.php';

        register_activation_hook(__FILE__, array($this, 'pdl_plugin_activate'));
        register_deactivation_hook(__FILE__,array($this, 'pdl_plugin_update_optin'));

        /* Add Textdomain */
        add_action('plugins_loaded', array($this, 'pdl_load_language_files'));

        /* Add the options page and menu item */
        add_action('admin_menu', array($this, 'pdl_add_menu'));

        /* add default value fro plugin */
        add_action('admin_init', array($this, 'pdl_reg_function'), 5);

        /* Change Footer */
        add_action('admin_init', array($this, 'pdl_footer'), 2);
        
        add_action('activated_plugin', array($this, 'pdl_redirection'), 1);

        /* Add Plugin path In JS */
        add_action('admin_head', array(&$this, 'pdl_js'), 3);
        add_action('init', array($this, 'pdl_create_portfolio'));
        /* Load admin styles */
        add_action('admin_enqueue_scripts', array($this, 'pdl_enqueue_admin_styles'), 4);
        /* Load admin js */
        add_action('admin_enqueue_scripts', array($this, 'pdl_enqueue_admin_scripts'), 5);
        /* Load  styles */
        add_action('wp_enqueue_scripts', array($this, 'pdl_enqueue_styles'), 6);

        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'pdl_action_links'), 32);

        /* Load  JS */
        add_action('wp_enqueue_scripts', array($this, 'pdl_enqueue_scripts'), 7);

        /* Add Metaboxes */
        add_action('add_meta_boxes', array($this, 'pdl_add_metaboxes'), 13);

        /* Save Metaboxes */
        add_action('save_post', array($this, 'pdl_save_metadata'), 14, 2);

        /* Get Portfolio Taxonomy */
        add_action('wp_ajax_get_portfolio_taxonomy', array($this, 'pdl_get_portfolio_taxonomy'), 20);

        /* Show Selected post */
        add_action('wp_ajax_pd_show_selected_tab', array($this, 'pdl_show_selected_tab'), 20);

        /* Get Portfolio Terms */
        add_action('wp_ajax_get_portfolio_terms', array($this, 'pdl_get_portfolio_terms'), 21);

        /* Get Portfolio Posts */
        add_action('wp_ajax_get_portfolio_posts', array($this, 'pdl_get_portfolio_posts'), 22);

        /* Get Portfolio post type */
        add_action('wp_ajax_get_portfolio_custom_post', array($this, 'pdl_get_portfolio_custom_post'), 52);

        /* Get Portfolio Terms from posts type */
        add_action('wp_ajax_get_portfolio_terms_from_posts', array($this, 'pdl_get_portfolio_terms_from_posts'), 22);

        /* Save portfolio settings */
        add_action('admin_init', array($this, 'pdl_save_settings'), 10);

        /* Required Fields Alert */
        add_action('wp_ajax_required_fields_alert', array(&$this, 'pdl_required_fields_alert'), 33);

        /* Add Image Size */
        add_filter('manage_posts_columns', array(&$this, 'pdl_featured_image_columns_head'), 37, 10);
        add_action('manage_posts_custom_column', array($this, 'pdl_featured_image_columns_content'), 10, 2);

        add_action('admin_enqueue_scripts', function () {
            if (is_admin()) {
                wp_enqueue_media();
            }
        });

        add_action('vc_before_init', array(&$this, 'pdl_add_vc_support'));
        
        add_action('admin_head', array(&$this, 'pdl_subscribe_mail'), 10);
    }

    /**
     * Load Language files
     * @return Loads plugin textdomain
     */
    function pdl_load_language_files() {
        load_plugin_textdomain('portfolio-designer-lite', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Add support for visual composer
     */
    function pdl_add_vc_support() {
        global $wpdb;
        $table_name = $wpdb->prefix . "portfolio_designer";
        $pd_ids = $wpdb->get_results("SELECT id,name FROM $table_name");
        $pd_array = array("Select Layout");
        if (!empty($pd_ids) && is_array($pd_ids)) {
            foreach ($pd_ids as $pd_id) {
                $pd_array[$pd_id->name] = $pd_id->id;
            }
        }
        vc_map(array(
            "name" => esc_html__("Portfolio Designer Lite", 'portfolio-designer-lite'),
            "base" => "wp_portfolio_designer_lite",
            "class" => "portfolio_designer_section",
            'show_settings_on_create' => false,
            "category" => esc_html__('Content', 'portfolio-designer-lite'),
            "icon" => 'portfolio_designer_icon',
            "description" => esc_html__("Add Portfolio Designer Shortcode", 'portfolio-designer-lite'),
        ));
    }

    /*
     * Add menu protfolio lite to admin menu
     */

    public function pdl_add_menu() {
        $pdl_is_optin = get_option('pdl_is_optin');
        if($pdl_is_optin == 'yes' || $pdl_is_optin == 'no') {
            add_menu_page(__('Portfolio Designer Lite', 'portfolio-designer-lite'), __('Portfolio Designer Lite', 'portfolio-designer-lite'), 'administrator', 'portfolio_lite_settings', array($this, 'pdl_menu_function'), PORT_LITE_PLUGIN_URL . 'images/portfolio.png');
        }
        else {
            add_menu_page(__('Portfolio Designer Lite', 'portfolio-designer-lite'), __('Portfolio Designer Lite', 'portfolio-designer-lite'), 'administrator', 'portfolio_lite_welcome', array($this, 'pdl_menu_welcome_callback'), PORT_LITE_PLUGIN_URL . 'images/portfolio.png');
        }
        add_submenu_page('portfolio_lite_settings', __('Portfolio designer Lite Settings', 'portfolio-designer-lite'), __('Portfolio Designer Lite Settings', 'portfolio-designer-lite'), 'manage_options', 'portfolio_lite_settings', array($this, 'pdl_add_menu'));
    }

    /*
     * Add protfolio settings page callback function
     */

    public function pdl_menu_function() {
        include_once( PORT_LITE_PLUGIN_DIR . 'includes/portfolio_settings.php' );
    }
    
    public function pdl_menu_welcome_callback() {
        global $wpdb;
        $pdl_admin_email = get_option('admin_email');
        ?>
        <div class='pdl_header_wizard'>
            <p><?php echo esc_attr(__('Hi there!', 'portfolio-designer-lite')); ?></p>
            <p><?php echo esc_attr(__("Don't ever miss an opportunity to opt in for Email Notifications / Announcements about exciting New Features and Update Releases.", 'portfolio-designer-lite')); ?></p>
            <p><?php echo esc_attr(__('Contribute in helping us making our plugin compatible with most plugins and themes by allowing to share non-sensitive information about your website.', 'portfolio-designer-lite')); ?></p>
            <p><b><?php echo esc_attr(__('Email Address for Notifications', 'portfolio-designer-lite')); ?> :</b></p>
            <p><input type='email' value='<?php echo $pdl_admin_email; ?>' id='pdl_admin_email' /></p>
            <p><?php echo esc_attr(__("If you're not ready to Opt-In, that's ok too!", 'portfolio-designer-lite')); ?></p>
            <p><b><?php echo esc_attr(__('Portfolio Designer will still work fine.', 'portfolio-designer-lite')); ?> :</b></p>
            <p onclick="pdl_show_hide_permission()" class='pdl_permission'><b><?php echo esc_attr(__('What permissions are being granted?', 'portfolio-designer-lite')); ?></b></p>
            <div class='pdl_permission_cover' style='display:none'>
                <div class='pdl_permission_row'>
                    <div class='pdl_50'>
                        <i class='dashicons dashicons-admin-users gb-dashicons-admin-users'></i>
                        <div class='pdl_50_inner'>
                            <label><?php echo esc_attr(__('User Details', 'portfolio-designer-lite')); ?></label>
                            <label><?php echo esc_attr(__('Name and Email Address', 'portfolio-designer-lite')); ?></label>
                        </div>
                    </div>
                    <div class='pdl_50'>
                        <i class='dashicons dashicons-admin-plugins gb-dashicons-admin-plugins'></i>
                        <div class='pdl_50_inner'>
                            <label><?php echo esc_attr(__('Current Plugin Status', 'portfolio-designer-lite')); ?></label>
                            <label><?php echo esc_attr(__('Activation, Deactivation and Uninstall', 'portfolio-designer-lite')); ?></label>
                        </div>
                    </div>
                </div>
                <div class='pdl_permission_row'>
                    <div class='pdl_50'>
                        <i class='dashicons dashicons-testimonial gb-dashicons-testimonial'></i>
                        <div class='pdl_50_inner'>
                            <label><?php echo esc_attr(__('Notifications', 'portfolio-designer-lite')); ?></label>
                            <label><?php echo esc_attr(__('Updates & Announcements', 'portfolio-designer-lite')); ?></label>
                        </div>
                    </div>
                    <div class='pdl_50'>
                        <i class='dashicons dashicons-welcome-view-site gb-dashicons-welcome-view-site'></i>
                        <div class='pdl_50_inner'>
                            <label><?php echo esc_attr(__('Website Overview', 'portfolio-designer-lite')); ?></label>
                            <label><?php echo esc_attr(__('Site URL, WP Version, PHP Info, Plugins & Themes Info', 'portfolio-designer-lite')); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <p>
                <input type='checkbox' class='pdl_agree' id='pdl_agree_gdpr' value='1' />
                <label for='pdl_agree_gdpr' class='pdl_agree_gdpr_lbl'><?php echo esc_attr(__('By clicking this button, you agree with the storage and handling of your data as mentioned above by this website. (GDPR Compliance)', 'portfolio-designer-lite')); ?></label>
            </p>
            <p class='pdl_buttons'>
                <a href="javascript:void(0)" class='button button-secondary' onclick="pdl_submit_optin('cancel')"><?php echo esc_attr(__('Skip', 'portfolio-designer-lite')); echo ' &amp; '; echo esc_attr(__('Continue', 'portfolio-designer-lite')); ?></a>
                <a href="javascript:void(0)" class='button button-primary' onclick="pdl_submit_optin('submit')"><?php echo esc_attr(__('Opt-In', 'portfolio-designer-lite')); echo ' &amp; '; echo esc_attr(__('Continue', 'portfolio-designer-lite')); ?></a>
            </p>
        </div>
        <?php
    }

    /*
     * Create custom post type sol_portfolio
     */

    public function pdl_create_portfolio() {
        $cpts_labels = array(
            'name' => __('Portfolios', 'portfolio-designer-lite'),
            'singular_name' => __('Portfolio', 'portfolio-designer-lite'),
            'menu_name' => __('Portfolios', 'portfolio-designer-lite'),
            'name_admin_bar' => __('Portfolio', 'portfolio-designer-lite'),
            'add_new' => __('Add New', 'portfolio-designer-lite'),
            'add_new_item' => __('Add New Portfolio', 'portfolio-designer-lite'),
            'new_item' => __('New Portfolio', 'portfolio-designer-lite'),
            'edit_item' => __('Edit Portfolio', 'portfolio-designer-lite'),
            'view_item' => __('View Portfolio', 'portfolio-designer-lite'),
            'all_items' => __('All Portfolios', 'portfolio-designer-lite'),
            'search_items' => __('Search Portfolio', 'portfolio-designer-lite'),
            'parent_item_colon' => __('Parent ', 'portfolio-designer-lite'),
            'not_found' => __('No Portfolio found', 'portfolio-designer-lite'),
            'not_found_in_trash' => __('No Portfolio found in Trash', 'portfolio-designer-lite'),
        );
        $cpt_ico = 'dashicons-portfolio';

        $cpts_args = array(
            'labels' => $cpts_labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => $cpt_ico,
            'query_var' => true,
            'rewrite' => array('slug' => 'sol-portfolio'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'page-attributes', 'excerpt', 'revisions'),
        );

        register_post_type('sol_portfolio', $cpts_args);

        $args = array(
            'label' => __('Portfolio Categories', 'portfolio-designer-lite'),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'portfolio-category'),
        );
        register_taxonomy('portfolio-category', array('sol_portfolio'), $args);
        $args = array(
            'label' => __('Portfolio Tags', 'portfolio-designer-lite'),
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'portfolio-tag'),
        );
        register_taxonomy('portfolio-tag', array('sol_portfolio'), $args);
    }

    /**
     * Register and enqueue admin styles
     */
    public function pdl_enqueue_admin_styles($hook_suffix) {
        if ( (isset($_GET['page']) && ($_GET['page'] == 'portfolio_lite_settings' || $_GET['page'] == 'about_portfolio_designer_lite' || $_GET['page'] == 'portfolio_lite_welcome' )) || $hook_suffix == 'plugins.php' ) {
            wp_enqueue_style('portdesignerlite_chosen_styles', PORT_LITE_PLUGIN_URL . 'includes/css/chosen.min.css');
            wp_enqueue_style('portdesignerlite_aristo_styles', PORT_LITE_PLUGIN_URL . 'includes/css/aristo.css');
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style('pdl_font_awesome_css', PORT_LITE_PLUGIN_URL . 'css/fontawesome-all.min.css', array(), self::$plugin_version);
            wp_enqueue_style('portdesignerlite_admin_styles', PORT_LITE_PLUGIN_URL . 'includes/css/admin_style.css', array(), self::$plugin_version);
            wp_deregister_style('wp-jquery-ui-dialog');
        }
        $adminvcURL = PORT_LITE_PLUGIN_URL . 'includes/css/vc_style.css';
        wp_register_style('pdl-admin-vc', $adminvcURL);
        wp_enqueue_style('pdl-admin-vc');
    }

    /**
     * Register and enqueue admin js
     */
    public function pdl_enqueue_admin_scripts($hook_suffix) {

        $screen = get_current_screen();
        if ( (isset($_GET['page']) && ($_GET['page'] == 'portfolio_lite_settings' || $_GET['page'] == 'about_portfolio_designer_lite' || $_GET['page'] == 'portfolio_lite_welcome' )) ||  $hook_suffix == 'plugins.php') {
            wp_enqueue_script('portdesignerlite_chosen_script', PORT_LITE_PLUGIN_URL . 'includes/js/chosen.jquery.js', array('jquery'));
            wp_enqueue_script('portdesignerlite_admin_script', PORT_LITE_PLUGIN_URL . 'includes/js/admin_script.js', array('wp-color-picker', 'jquery-ui-core', 'jquery-ui-dialog'), false, true);
            wp_enqueue_script('portdesignerlite_admin_isotope_script', PORT_LITE_PLUGIN_URL . 'js/isotope.pkgd.min.js', array('jquery'));
            wp_enqueue_script('portdesignerlite_admincolor_alpha', PORT_LITE_PLUGIN_URL . 'includes/js/wp-color-picker-alpha.min.js', array('wp-color-picker'));

            $portdesigner_admin_translations = array(
                'no_found' => __('Oops, nothing found!', 'portfolio-designer-lite'),
                'portfolio_layout_delete' => __('The Portfolio Layout will be deleted. Are you sure?', 'portfolio-designer-lite'),
                'portfolio_layout_duplicate' => __('The Portfolio Layout will be duplicated. Are you sure?', 'portfolio-designer-lite'),
                'reset_data' => __('Do you want to reset data?', 'portfolio-designer-lite'),
            );
            wp_localize_script('portdesignerlite_admin_script', 'portdesigner_admin_translations', $portdesigner_admin_translations);
        }
    }

    /**
     * Deactive pro version when lite version is activated
     */
    public function pdl_plugin_activate() {
        if (is_plugin_active('portfolio-designer/portfolio-designer.php')) {
            deactivate_plugins('/portfolio-designer/portfolio-designer.php');
        }
    }
    
    // Delete optin on deactivation of plugin

     public function pdl_plugin_update_optin() {
        update_option('pdl_is_optin','');
    }

    /**
     * Get Portfolio Taxonomy
     * @return Taxonomy
     */
    function pdl_get_portfolio_taxonomy() {
        ob_start();
        if (isset($_REQUEST['posttype']) && $_REQUEST['posttype'] != '') {
            $portfolio_post = sanitize_text_field($_REQUEST['posttype']);
        } else {
            $portfolio_post = 'post';
        }
        $portfolio_taxonomy = get_object_taxonomies($portfolio_post, 'objects');
        if (!empty($portfolio_taxonomy)) {
            ?>
            <td>
                <label for="portfolio_taxonomy"><?php _e('Select Taxonomy', 'portfolio-designer-lite'); ?></label>
            </td>
            <td>
                <div class="select-cover">
                    <select id="portfolio_taxonomy" name="portfolio_taxonomy">
                        <?php
                        foreach ($portfolio_taxonomy as $slug => $name) {
                            if ($slug != 'post_format') {
                                ?>
                                <option value="<?php echo $slug ?>"><?php echo $name->labels->name; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <p><?php _e('Select the taxonomy that you would like use in post query and for filtering.', 'portfolio-designer-lite'); ?></p>
            </td>
            <?php
        }
        $data = ob_get_clean();
        echo $data;
        exit;
    }

    /**
     * Show Selected tab
     */
    function pdl_show_selected_tab() {

        $closed = isset($_POST['closed']) ? $_POST['closed'] : '';

        $page = isset($_POST['page']) ? $_POST['page'] : '';

        if ($page != sanitize_key($page)) {
            wp_die(0);
        }

        if (!$user = wp_get_current_user()) {
            wp_die(-1);
        }

        if ($closed != '') {
            update_user_option($user->ID, "pdselectedtab_$page", $closed, true);
        }

        wp_die(1);
    }

    /**
     * Get Portfolio Terms
     */
    function pdl_get_portfolio_terms() {
        ob_start();
        if (isset($_POST['posttaxonomy']) && $_POST['posttaxonomy'] != '') {
            $portfolio_terms = sanitize_text_field($_POST['posttaxonomy']);
        }
        $portfolio_terms = get_terms($portfolio_terms, array('hide_empty' => false));

        if (!empty($portfolio_terms)) {
            ?>
            <td>
                <label for="portfolio_categories"><?php _e('Terms', 'portfolio-designer-lite'); ?></label>
            </td>
            <td>
                <select id="portfolio_categories" name="portfolio_categories[]" class="chosen-select" multiple="multiple" data-placeholder="<?php esc_attr_e('Choose Terms', 'portfolio-designer-lite'); ?>" style="width:100%;">
                    <?php
                    foreach ($portfolio_terms as $value) {
                        ?>
                        <option value="<?php echo $value->slug; ?>"><?php echo $value->name; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <p><?php _e('Select the terms that you would like to use in post query and for filtering (if the portfolio is filterable).', 'portfolio-designer-lite'); ?></p>
            </td>
            <?php
        }
        $data = ob_get_clean();
        echo $data;
    }

    /**
     * Get Portfolio Posts
     */
    function pdl_get_portfolio_posts() {
        ob_start();
        ?>
        <div class="select-cover">
            <select id="portfolio_post" name="portfolio_post[]" class="chosen-select" multiple="multiple" data-placeholder="Choose Posts" style="width:100%;">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => -1,
                );
                if (isset($_POST['post_type']) && $_POST['post_type'] != '') {
                    $args['post_type'] = sanitize_text_field($_POST['post_type']);
                }

                if (isset($_POST['categories']) && !empty($_POST['categories'])) {                    
                    $categories = $_POST['categories'];
                    $args['tax_query']['relation'] = 'or';
                    foreach ($categories as $category) {
                        $args['tax_query'][] = array(
                            'taxonomy' => sanitize_text_field($_POST['taxonomy']),
                            'field' => 'slug',
                            'terms' => $category,
                        );
                    }
                }
                $the_query = new WP_Query($args);
                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post();
                        ?> <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option> <?php
                    }
                }
                ?>
            </select>
        </div>
        <?php
        $data = ob_get_clean();
        echo $data;
        die();
    }

    /**
     * Get post type from selection
     * @return posttype
     */
    function pdl_get_portfolio_custom_post() {
        global $wpdb;
        $portfolio_custom_post = array();
        if (isset($_REQUEST['posttype']) && $_REQUEST['posttype'] != '') {
            $portfolio_post = $_REQUEST['posttype'];
        } else {
            $portfolio_post = 'post';
        }
        if($portfolio_post == 'sol_portfolio'){
            echo "1";
        }else{
            echo "0";
        }
        exit;
    }

    /**
     * Get Portfolio Terms from posts type
     * @return Terms
     */
    function pdl_get_portfolio_terms_from_posts() {
        ob_start();
        if (isset($_POST['posttype']) && $_POST['posttype'] != '') {
            $portfolio_posttype = sanitize_text_field($_POST['posttype']);
        }
        $portfolio_taxonomy = get_object_taxonomies($portfolio_posttype, 'objects');
        foreach ($portfolio_taxonomy as $slug => $val) {
            $portfoliotaxonomy[] = $slug;
        }
        $portfolio_terms = get_terms($portfoliotaxonomy, array('hide_empty' => false));
        if (!empty($portfolio_terms)) {
            ?>
            <td>
                <label for="portfolio_categories"><?php _e('Terms', 'portfolio-designer-lite'); ?></label>
            </td>
            <td>
                <select id="portfolio_categories" name="portfolio_categories[]" class="chosen-select" multiple="multiple" data-placeholder="<?php esc_attr_e('Choose Categories', 'portfolio-designer-lite'); ?>" style="width:100%;">
                    <?php
                    foreach ($portfolio_terms as $value) {
                        ?>
                        <option value="<?php echo $value->slug; ?>"><?php echo $value->name; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <p> <?php _e('Select the terms that you would like use in post query and for filtering (if the porfolio is filterable).', 'portfolio-designer-lite'); ?></p>
            </td>
            <?php
        }
        $data = ob_get_clean();
        echo $data;
    }

    /**
     *  @global $post
     *  @return Portfolio URL metabox html.
     */
    function pdl_portfolio_link($post) {
        $portdesign_url = get_post_meta($post->ID, 'portfolio_lite_url', true);
        $portdesign_label = get_post_meta($post->ID, 'portfolio_lite_label', true);
        ?>
        <p>
            <label for="portdesign_label"><strong><?php _e('Label of URL', 'portfolio-designer-lite'); ?></strong></label>
            <input id="portdesign_label" type="text" name="portdesign_label" id="portdesign_label" value="<?php echo $portdesign_label; ?>" placeholder="<?php _e('Website', 'portfolio-designer-lite'); ?>"/>
        </p>
        <p>
            <label for="portdesign_url"><strong><?php _e('Provide URL', 'portfolio-designer-lite'); ?></strong></label>
            <input id="portdesign_url" type="text" name="portdesign_url" id="portdesign_url" value="<?php echo $portdesign_url; ?>" placeholder="<?php echo esc_url('https://www.solwininfotech.com/'); ?>"/>
        </p>
        <?php
    }

    /**
     * Add metaboxes
     */
    function pdl_add_metaboxes() {
        add_meta_box('portfolio_lite_url', __('Project URL', 'portfolio-designer-lite'), array($this, 'pdl_portfolio_link'), 'sol_portfolio', 'side');
    }

    /**
     *  Save the Metabox Data
     *  @global $post_id, $post
     *  @return NULL
     */
    function pdl_save_metadata($post_id, $post) {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verification of User
        if (!current_user_can('edit_post', $post->ID)) {
            return;
        }

        if (isset($_POST['portdesign_url'])) {
            $portdesign_url = filter_var($_POST['portdesign_url'], FILTER_SANITIZE_URL);
            $portdesign_url = sanitize_text_field($portdesign_url);
            update_post_meta($post_id, 'portfolio_lite_url', $portdesign_url);
        }

        if (isset($_POST['portdesign_label'])) {
            $portdesign_label = sanitize_text_field($_POST['portdesign_label']);
            update_post_meta($post_id, 'portfolio_lite_label', $portdesign_label);
        }
    }

    /*
     * Save portfolio settings
     */

    public function pdl_save_settings() {
        if (isset($_REQUEST['addPortfolioDesigner']) && isset($_REQUEST['page']) && $_REQUEST['page'] === 'portfolio_lite_settings' && isset($_REQUEST['action']) && $_REQUEST['action'] === 'save') {
            $settings = $_POST;
            if (!isset($_POST['portfolio_enable_filter'])) {
                $settings['portfolio_enable_filter'] = '0';
            }
            if (!isset($_POST['portfolio_enable_pagination'])) {
                $settings['portfolio_enable_pagination'] = '0';
            }
            //add short code to template selected
            $templates = array();
            $templates['ID'] = intval($_POST['portfolio_page_display']);
            $templates['post_content'] = '[wp_portfolio_designer_lite]';
            wp_update_post($templates);
            $settings = is_array($settings) ? serialize($settings) : $settings;
            update_option("portfolio_designer_lite_settings", $settings);
        }
    }

    /**
     * Register and enqueue style
     */
    function pdl_enqueue_styles() {
        if (!wp_style_is('dashicons')) {
            wp_enqueue_style('dashicons');
        }
        wp_enqueue_style('pdl_fancybox_css', PORT_LITE_PLUGIN_URL . 'css/jquery.fancybox.css', array(), self::$plugin_version);
        wp_enqueue_style('pdl_font_awesome_css', PORT_LITE_PLUGIN_URL . 'css/fontawesome-all.min.css', array(), self::$plugin_version);
        wp_enqueue_style('pdl_style_css', PORT_LITE_PLUGIN_URL . 'css/style.css', array(), self::$plugin_version);
    }

    /**
     * Register and enqueue js
     */
    function pdl_enqueue_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('pdl_fancybox_script', PORT_LITE_PLUGIN_URL . 'js/jquery.fancybox.pack.js', array('jquery'));
        wp_enqueue_script('pdl_isotope_script', PORT_LITE_PLUGIN_URL . 'js/isotope.pkgd.min.js', array('jquery'));
        wp_enqueue_script('jquery-masonry', array('jquery'));
        wp_enqueue_script('pdl_less_script', PORT_LITE_PLUGIN_URL . 'less/less.min.js');
        wp_enqueue_script('pdl_front_script', PORT_LITE_PLUGIN_URL . 'js/script.js', array('jquery'));
        $portdesigner_front_translations = array(
            'whatsapp_mobile_alert' => __('Please share this article in mobile device', 'portfolio-designer-lite')
        );
        wp_localize_script('pdl_front_script', 'portdesigner_script_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_localize_script('pdl_front_script', 'portdesigner_front_translations', $portdesigner_front_translations);
    }

    /**
     * Add footer strip for plugin
     */
    function pdl_footer() {
        if (isset($_GET['page']) && ($_GET['page'] == 'portfolio_lite_settings' || $_GET['page'] == 'about_portfolio_designer_lite' )) {
            add_filter('admin_footer_text', 'pdl_remove_footer_admin'); //change admin footer text

            if (!function_exists('pdl_remove_footer_admin')) {

                function pdl_remove_footer_admin() {
                    ?>
                    <p id="footer-left" class="alignleft">
                        <?php _e('If you like ', 'portfolio-designer-lite'); ?>
                        <strong><a target="_blank" href="<?php echo esc_url('https://www.solwininfotech.com/product/wordpress-plugins/portfolio-designer-lite/'); ?>"><?php _e('Portfolio Designer Lite', 'portfolio-designer-lite'); ?></a></strong>
                        <?php _e('please leave us a', 'portfolio-designer-lite'); ?>
                        <a class="port-rating-link" data-rated="Thanks :)" target="_blank" href="<?php echo esc_url('https://wordpress.org/support/plugin/portfolio-designer-lite/reviews?filter=5#new-post'); ?>">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</a>
                        <?php _e('rating. A huge thank you from Solwin Infotech in advance!', 'portfolio-designer-lite'); ?>
                    </p>
                    <?php
                }

            }
        }
    }

    /*
     * set plugin path for js plugin_path_js
     */

    function pdl_js() {
        ?>
        <script type="text/javascript">
            var portdesign_plugin_path = '<?php echo PORT_LITE_PLUGIN_URL; ?>';
            var style_path = '<?php echo bloginfo('stylesheet_url'); ?>';
            var home_path = '<?php echo get_home_url(); ?>';
        </script>
        <?php
    }

    /**
     * Plugin Action Links
     */
    function pdl_action_links($links) {
        $pdl_is_optin = get_option('pdl_is_optin');
        if($pdl_is_optin == 'yes' || $pdl_is_optin == 'no') {
            $start_page = 'portfolio_lite_settings';
        }
        else {
            $start_page = 'portfolio_lite_welcome';
        }
        $action_links = array(
            'settings' => '<a href="' . admin_url("admin.php?page=$start_page") . '" title="' . esc_attr(__('View Portfolio Designer Lite Settings', 'portfolio-designer-lite')) . '">' . __('Settings', 'portfolio-designer-lite') . '</a>'
        );
        $links = array_merge($action_links, $links);
        $links['documents'] = '<a target="_blank" class="documentation_pdl_plugin" href="' . esc_url('https://www.solwininfotech.com/documents/wordpress/portfolio-designer-lite/') . '">' . __('Documentation', 'portfolio-designer-lite') . '</a>';
        $links['upgrade'] = '<a target="_blank" href="' . esc_url('https://www.solwininfotech.com/product/wordpress-plugins/portfolio-designer/') . '" class="pdl_upgrade_link" style="color: #4caf50;">' . __('Upgrade', 'portfolio-designer-lite') . '</a>';
        return $links;
    }

    function pdl_required_fields_alert() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Please fill up all requred fields.', 'portfolio-designer-lite'); ?></p>
        </div>
        <?php
        die();
    }

    /**
     *  Add new column to custom post type listing page
     *  @param array $defaults
     */
    function pdl_featured_image_columns_head($defaults) {
        global $post, $wpdb;

        $newColumnOrder = array();
        if ('sol_portfolio' == $post->post_type) {
            $defaults['featured_image'] = __('Featured Image', 'portfolio-designer-lite');
            foreach ($defaults as $key => $title) {

                if ($key == 'title') {
                    $newColumnOrder['featured_image'] = __('Featured Image', 'portfolio-designer-lite');
                }

                $newColumnOrder[$key] = $title;
            }

            return $newColumnOrder;
        }

        return $defaults;
    }

    /**
     * Show featured image to custom post type listing page
     * @param string $column_name
     * @param int $post_ID
     */
    function pdl_featured_image_columns_content($column_name, $post_ID) {
        if ($column_name == 'featured_image') {

            $post_thumbnail_id = get_post_thumbnail_id($post_ID);

            if ($post_thumbnail_id) {

                $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
                $post_featured_image = $post_thumbnail_img[0];

                if ($post_featured_image) {
                    echo '<img src="' . $post_featured_image . '" height="60px" width="60px" />';
                }
            } else {
                echo '<img src="' . PORT_LITE_PLUGIN_URL . 'images/no_image.jpg" alt="' . get_the_title() . '" height="60px" width="60px" />';
            }
        }
    }

    public function pdl_reg_function() {
        $settings = get_option("portfolio_designer_lite_settings");
        if (empty($settings)) {
            $settings = array(
                'portfolio_layout_post' => 'sol_portfolio',
                'portfolio_taxonomy' => 'portfolio-category',
                'portfolio_number_post' => 10,
                'portfolio_order' => 'ASC',
                'portfolio_order_by' => 'date',
                'portfolio_layout_type' => 'masonary',
                'portfolio_column_space' => 5,
                'portfolio_row_space' => 5,
                'portfolio_border_radius' => 0,
                'portfolio_thumb_size' => 'full',
                'portfolio_enable_overlay' => 1,
                'portfolio_content_position' => 'overlay_image',
                'portfolio_image_effect' => 'effect_1',
                'portfolio_summary' => 0,
                'portfolio_enable_popup_link' => 1,
                'portfolio_image_link' => 'new_tab',
                'portfolio_show_all_txt' => 'Show All',
                'portfolio_enable_pagination' => 1,
                'portfolio_enable_social_share_settings' => 1,
                'portfolio_social_icon_display_position' => 1,
                'portfolio_social_icon_alignment' => 'left',
                'portfolio_social_style' => 0,
                'social_icon_theme' => 2,
                'portfolio_social_icon_style' => 1,
                'portfolio_social_icon_size' => 0,
                'portfolio_facebook_link' => 1,
                'portfolio_twitter_link' => 1,
                'portfolio_google_link' => 1,
                'portfolio_linkedin_link' => 0,
                'portfolio_pinterest_link' => 0,
                'portfolio_title_font_color' => '',
                'portfolio_title_font' => '',
                'portfolio_title_font_size' => 0,
                'portfolio_title_font_text_decoration' => 'none',
                'portfolio_content_font_color' => '',
                'portfolio_content_font_type' => '',
                'portfolio_content_font_size' => 0,
                'portfolio_content_font_text_transform' => 'none',
                'portfolio_meta_font_color' => '#222',
                'portfolio_meta_font_type' => '',
                'portfolio_filter_padding_top' => 5,
                'portfolio_filter_padding_right' => 15,
                'portfolio_filter_padding_bottom' => 5,
                'portfolio_filter_padding_left' => 15,
                'portfolio_filter_border_width' => 1,
                'portfolio_filter_border_style' => 'solid',
                'portfolio_filter_border_color' => ' #000000',
                'portfolio_filter_text_border_color' => '#3BC391',
                'portfolio_filter_text_back_color' => '',
                'portfolio_filter_text_back_hover_color' => '',
                'portfolio_button_font_color' => '',
                'portfolio_button_background_color' => '#000000',
                'portfolio_button_font_type' => '',
                'portfolio_button_type' => 'rectangle',
                'content_background_color' => '',
                'portfolio_overlay_background_color' => '',
                'portfolio_custom_css' => '',
                'portfolio_enable_filter' => 0,
                'portfolio_default_image_id' => '',
                'portfolio_default_image_src' => '',
            );
            $settings = is_array($settings) ? serialize($settings) : $settings;
            update_option("portfolio_designer_lite_settings", $settings);
        }
    }
        
    public function pdl_redirection($plugin) {
        if( $plugin == plugin_basename( __FILE__ ) ) {
            if (!isset($_GET['activate-multi'])) {
                $pdl_is_optin = get_option('pdl_is_optin');
                if($pdl_is_optin == 'yes' || $pdl_is_optin == 'no') {
                    exit( wp_redirect( admin_url( 'admin.php?page=portfolio_lite_settings' ) ) );
                }
                else {
                    exit( wp_redirect( admin_url( 'admin.php?page=portfolio_lite_welcome' ) ) );
                }
            }
        }
    }

    /**
    * Deactivate email form
    */
    public function pdl_subscribe_mail() {
        ?>
        <div id="sol_deactivation_widget_cover_pdl" style="display:none;">
            <div class="sol_deactivation_widget">
                <h3><?php _e('If you have a moment, please let us know why you are deactivating. We would like to help you in fixing the issue.', 'portfolio-designer-lite'); ?></h3>
                <form id="frmDeactivationpdl" name="frmDeactivation" method="post" action="">
                    <ul class="sol_deactivation_reasons_ul">
                        <?php $i = 1; ?>
                        <li>
                            <input class="sol_deactivation_reasons" checked="checked" name="sol_deactivation_reasons_pdl" type="radio" value="<?php echo $i; ?>" id="pdl_reason_<?php echo $i; ?>">
                            <label for="pdl_reason_<?php echo $i; ?>"><?php _e('I am going to upgrade to PRO version', 'portfolio-designer-lite'); ?></label>
                        </li>
                        <?php $i++; ?>
                        <li>
                            <input class="sol_deactivation_reasons" name="sol_deactivation_reasons_pdl" type="radio" value="<?php echo $i; ?>" id="pdl_reason_<?php echo $i; ?>">
                            <label for="pdl_reason_<?php echo $i; ?>"><?php _e('The plugin suddenly stopped working', 'portfolio-designer-lite'); ?></label>
                        </li>
                        <?php $i++; ?>
                        <li>
                            <input class="sol_deactivation_reasons" name="sol_deactivation_reasons_pdl" type="radio" value="<?php echo $i; ?>" id="pdl_reason_<?php echo $i; ?>">
                            <label for="pdl_reason_<?php echo $i; ?>"><?php _e('The plugin was not working', 'portfolio-designer-lite'); ?></label>
                        </li>
                        <?php $i++; ?>
                        <li>
                            <input class="sol_deactivation_reasons" name="sol_deactivation_reasons_pdl" type="radio" value="<?php echo $i; ?>" id="pdl_reason_<?php echo $i; ?>">
                            <label for="pdl_reason_<?php echo $i; ?>"><?php _e('Installed & configured well but disturbed my portfolio page design', 'portfolio-designer-lite'); ?></label>
                        </li>
                        <?php $i++; ?>
                        <li>
                            <input class="sol_deactivation_reasons" name="sol_deactivation_reasons_pdl" type="radio" value="<?php echo $i; ?>" id="pdl_reason_<?php echo $i; ?>">
                            <label for="pdl_reason_<?php echo $i; ?>"><?php _e("My theme's portfolio page is better than plugin's portfolio page design", 'portfolio-designer-lite'); ?></label>
                        </li>
                        <?php $i++; ?>
                        <li>
                            <input class="sol_deactivation_reasons" name="sol_deactivation_reasons_pdl" type="radio" value="<?php echo $i; ?>" id="pdl_reason_<?php echo $i; ?>">
                            <label for="pdl_reason_<?php echo $i; ?>"><?php _e('The plugin broke my site completely', 'portfolio-designer-lite'); ?></label>
                        </li>
                        <?php $i++; ?>
                        <li>
                            <input class="sol_deactivation_reasons" name="sol_deactivation_reasons_pdl" type="radio" value="<?php echo $i; ?>" id="pdl_reason_<?php echo $i; ?>">
                            <label for="pdl_reason_<?php echo $i; ?>"><?php _e('No any reason', 'portfolio-designer-lite'); ?></label>
                        </li>
                        <?php $i++; ?>
                        <li>
                            <input class="sol_deactivation_reasons" name="sol_deactivation_reasons_pdl" type="radio" value="<?php echo $i; ?>" id="pdl_reason_<?php echo $i; ?>">
                            <label for="pdl_reason_<?php echo $i; ?>"><?php _e('Other', 'portfolio-designer-lite'); ?></label><br/>
                            <input style="display:none;width: 90%" value="" type="text" name="sol_deactivation_reason_other_pdl" class="sol_deactivation_reason_other_pdl" />
                        </li>
                    </ul>
                    <p>
                        <input type='checkbox' class='pdl_agree' id='pdl_agree_gdpr_deactivate' value='1' />
                        <label for='pdl_agree_gdpr_deactivate' class='pdl_agree_gdpr_lbl'><?php echo esc_attr(__('By clicking this button, you agree with the storage and handling of your data as mentioned above by this website. (GDPR Compliance)', 'portfolio-designer-lite')); ?></label>
                    </p>
                    <a onclick='pdl_submit_optin("deactivate")' class="button button-secondary"><?php _e('Submit', 'portfolio-designer-lite'); echo ' &amp; '; _e('Deactivate', 'portfolio-designer-lite'); ?></a>
                    <input type="submit" name="sbtDeactivationFormClose" id="sbtDeactivationFormClosepdl" class="button button-primary" value="<?php _e('Cancel', 'portfolio-designer-lite'); ?>" />
                    <a href="javascript:void(0)" class="pdl-deactivation" aria-label="<?php _e('Deactivate Portfolio Designer','portfolio-designer-lite'); ?>"><?php _e('Skip', 'portfolio-designer-lite'); echo ' &amp; '; _e('Deactivate', 'portfolio-designer-lite'); ?></a>
                </form>
                <div class="support-ticket-section">
                    <h3><?php _e('Would you like to give us a chance to help you?', 'portfolio-designer-lite'); ?></h3>
                    <img src="<?php echo PORT_LITE_PLUGIN_URL . '/images/support-ticket.png'; ?>">
                    <a href="<?php echo esc_url('http://support.solwininfotech.com/')?>"><?php _e('Create a support ticket', 'portfolio-designer-lite'); ?></a>
                </div>
            </div>

        </div>
        <a style="display:none" href="#TB_inline?height=800&inlineId=sol_deactivation_widget_cover_pdl" class="thickbox" id="deactivation_thickbox_pdl"></a>
        <?php
    }
}

//end of class
new PortfolioDesignerLite();


add_action('wp_ajax_pdl_submit_optin','pdl_submit_optin');
if(!function_exists('pdl_submit_optin')) {
    function pdl_submit_optin() {
        global $wpdb, $wp_version;
        $pdl_submit_type = '';
        if(isset($_POST['email'])) {
            $pdl_email = $_POST['email'];
        }
        else {
            $pdl_email = get_option('admin_url');
        }
        if(isset($_POST['type'])) {
            $pdl_submit_type = $_POST['type'];
        }
        if($pdl_submit_type == 'submit') {
            $status_type = get_option('pdl_is_optin');
            $theme_details = array();
            if ( $wp_version >= 3.4 ) {
                $active_theme                   = wp_get_theme();
                $theme_details['theme_name']    = strip_tags( $active_theme->name );
                $theme_details['theme_version'] = strip_tags( $active_theme->version );
                $theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
            }
            $active_plugins = (array) get_option( 'active_plugins', array() );
            if (is_multisite()) {
                $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
            }
            $plugins = array();
            if (count($active_plugins) > 0) {
                $get_plugins = array();
                foreach ($active_plugins as $plugin) {
                    $plugin_data = @get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);

                    $get_plugins['plugin_name'] = strip_tags($plugin_data['Name']);
                    $get_plugins['plugin_author'] = strip_tags($plugin_data['Author']);
                    $get_plugins['plugin_version'] = strip_tags($plugin_data['Version']);
                    array_push($plugins, $get_plugins);
                }
            }

            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/portfolio-designer-lite/portfolio-designer-lite.php', $markup = true, $translate = true);
            $current_version = $plugin_data['Version'];

            $plugin_data = array();
            $plugin_data['plugin_name'] = 'Portfolio Designer Lite';
            $plugin_data['plugin_slug'] = 'portfolio-designer-lite';
            $plugin_data['plugin_version'] = $current_version;
            $plugin_data['plugin_status'] = $status_type;
            $plugin_data['site_url'] = home_url();
            $plugin_data['site_language'] = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
            $current_user = wp_get_current_user();
            $f_name = $current_user->user_firstname;
            $l_name = $current_user->user_lastname;
            $plugin_data['site_user_name'] = esc_attr( $f_name ).' '.esc_attr( $l_name );
            $plugin_data['site_email'] = false !== $pdl_email ? $pdl_email : get_option( 'admin_email' );
            $plugin_data['site_wordpress_version'] = $wp_version;
            $plugin_data['site_php_version'] = esc_attr( phpversion() );
            $plugin_data['site_mysql_version'] = $wpdb->db_version();
            $plugin_data['site_max_input_vars'] = ini_get( 'max_input_vars' );
            $plugin_data['site_php_memory_limit'] = ini_get( 'max_input_vars' );
            $plugin_data['site_operating_system'] = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
            $plugin_data['site_extensions']       = get_loaded_extensions();
            $plugin_data['site_activated_plugins'] = $plugins;
            $plugin_data['site_activated_theme'] = $theme_details;
            $url = 'http://analytics.solwininfotech.com/';
            $response = wp_safe_remote_post(
                $url, array(
                    'method'      => 'POST',
                    'timeout'     => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'headers'     => array(),
                    'body'        => array(
                        'data'    => maybe_serialize( $plugin_data ),
                        'action'  => 'plugin_analysis_data',
                    ),
                )
            );
            update_option( 'pdl_is_optin', 'yes' );
        }
        elseif($pdl_submit_type == 'cancel') {
            update_option( 'pdl_is_optin', 'no' );
        }
        elseif($pdl_submit_type == 'deactivate') {
            $status_type = get_option('pdl_is_optin');
            $theme_details = array();
            if ( $wp_version >= 3.4 ) {
                $active_theme                   = wp_get_theme();
                $theme_details['theme_name']    = strip_tags( $active_theme->name );
                $theme_details['theme_version'] = strip_tags( $active_theme->version );
                $theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
            }
            $active_plugins = (array) get_option( 'active_plugins', array() );
            if (is_multisite()) {
                $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
            }
            $plugins = array();
            if (count($active_plugins) > 0) {
                $get_plugins = array();
                foreach ($active_plugins as $plugin) {
                    $plugin_data = @get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
                    $get_plugins['plugin_name'] = strip_tags($plugin_data['Name']);
                    $get_plugins['plugin_author'] = strip_tags($plugin_data['Author']);
                    $get_plugins['plugin_version'] = strip_tags($plugin_data['Version']);
                    array_push($plugins, $get_plugins);
                }
            }

            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/portfolio-designer-lite/portfolio-designer-lite.php', $markup = true, $translate = true);
            $current_version = $plugin_data['Version'];

            $plugin_data = array();
            $plugin_data['plugin_name'] = 'Portfolio Designer Lite';
            $plugin_data['plugin_slug'] = 'portfolio-designer-lite';
            $reason_id = $_POST['selected_option_de'];
            $plugin_data['deactivation_option'] = $reason_id;
            $plugin_data['deactivation_option_text'] = $_POST['selected_option_de_text'];
            if ($reason_id == 8) {
                $plugin_data['deactivation_option_text'] = $_POST['selected_option_de_other'];
            }
            $plugin_data['plugin_version'] = $current_version;
            $plugin_data['plugin_status'] = $status_type;
            $plugin_data['site_url'] = home_url();
            $plugin_data['site_language'] = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
            $current_user = wp_get_current_user();
            $f_name = $current_user->user_firstname;
            $l_name = $current_user->user_lastname;
            $plugin_data['site_user_name'] = esc_attr( $f_name ).' '.esc_attr( $l_name );
            $plugin_data['site_email'] = false !== $pdl_email ? $pdl_email : get_option( 'admin_email' );
            $plugin_data['site_wordpress_version'] = $wp_version;
            $plugin_data['site_php_version'] = esc_attr( phpversion() );
            $plugin_data['site_mysql_version'] = $wpdb->db_version();
            $plugin_data['site_max_input_vars'] = ini_get( 'max_input_vars' );
            $plugin_data['site_php_memory_limit'] = ini_get( 'max_input_vars' );
            $plugin_data['site_operating_system'] = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
            $plugin_data['site_extensions']       = get_loaded_extensions();
            $plugin_data['site_activated_plugins'] = $plugins;
            $plugin_data['site_activated_theme'] = $theme_details;
            $url = 'http://analytics.solwininfotech.com/';
            $response = wp_safe_remote_post(
                $url, array(
                    'method'      => 'POST',
                    'timeout'     => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'headers'     => array(),
                    'body'        => array(
                        'data'    => maybe_serialize( $plugin_data ),
                        'action'  => 'plugin_analysis_data_deactivate',
                    ),
                )
            );
            update_option( 'pdl_is_optin', '' );
        }
        exit();
    }
}