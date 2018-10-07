<?php
if (!defined('ABSPATH')) {
    exit();
}
/**
 * Display Portfolio
 * @param string $id portfolio id
 */
if (!class_exists('portdesign_lessc') && !class_exists('portdesign_lessc_parser') && !class_exists('portdesign_lessc_formatter_classic') && !class_exists('portdesign_lessc_formatter_compressed') && !class_exists('portdesign_lessc_formatter_lessjs')) {
    include_once PORT_LITE_PLUGIN_DIR . 'less/lessc.inc.php';
}

class PortfolioDesignerLightShortcode {

    /**
     * Initialize the plugin
     */
    function __construct() {
        add_shortcode('wp_portfolio_designer_lite', array(&$this, 'pdl_shortcode_func'));
    }

    function pdl_shortcode_func($atts) {
        ob_start();

        global $wpdb, $wp_query, $post;
        $setting = unserialize(get_option('portfolio_designer_lite_settings'));

        foreach ($setting as $key => $value) {
            $key = str_replace("portfolio_", "", $key);
            $data_setting[$key] = $value;
        }
        if (!isset($data_setting['enable_overlay'])) {
            $data_setting['enable_overlay'] = 0;
        }
        if (empty($setting)) {
            _e('It is not a valid portfolio shortcode', 'portfolio-designer-lite');
        } else {

            $less_data = array(
                'portfolio_designer_id' => 'portfolio_designer_id',
            );
            $less_data['column_space'] = ($data_setting['column_space'] != '') ? $data_setting['column_space'] . 'px' : '0';
            $less_data['row_space'] = ($data_setting['row_space'] != '') ? $data_setting['row_space'] . 'px' : '0';
            $less_data['border_radius'] = (isset($data_setting['border_radius']) && $data_setting['border_radius'] != '') ? $data_setting['border_radius'] . 'px' : 0;

            $less_data['title_font_size'] = (trim($data_setting['title_font_size'] != '') && $data_setting['title_font_size'] > 0) ? trim($data_setting['title_font_size']) . 'px' : true;
            $less_data['content_font_size'] = (trim($data_setting['content_font_size'] != '') && $data_setting['content_font_size'] > 0) ? trim($data_setting['content_font_size']) . 'px' : true;
            $less_data['meta_font_size'] = (trim($data_setting['meta_font_size'] != '') && $data_setting['meta_font_size'] > 0) ? trim($data_setting['meta_font_size']) . 'px' : true;
            $less_data['button_font_size'] = true;

            $less_data['title_font_color'] = (trim($data_setting['title_font_color']) != '') ? trim($data_setting['title_font_color']) : '#fff';
            $less_data['content_font_color'] = (trim($data_setting['content_font_color']) != '') ? trim($data_setting['content_font_color']) : '#fff';
            $less_data['meta_font_color'] = (trim($data_setting['meta_font_color']) != '') ? trim($data_setting['meta_font_color']) : '#fff';
            $less_data['button_font_color'] = (trim($data_setting['button_font_color']) != '') ? trim($data_setting['button_font_color']) : '#fff';
            $less_data['button_background_color'] = '#fff';
            $less_data['overlay_background_color'] = (trim($data_setting['overlay_background_color']) != '') ? trim($data_setting['overlay_background_color']) : '#000';

            $less_data['title_font_italic_style'] = (isset($data_setting['title_font_italic_style']) && $data_setting['title_font_italic_style'] == 1) ? 'italic' : 'normal';
            $less_data['title_font_weight'] = (isset($data_setting['title_font_weight']) && $data_setting['title_font_weight'] != '') ? $data_setting['title_font_weight'] : 'normal';
            $less_data['title_font_text_transform'] = (isset($data_setting['title_font_text_transform']) && $data_setting['title_font_text_transform'] != '') ? $data_setting['title_font_text_transform'] : 'none';
            $less_data['title_font_text_decoration'] = (isset($data_setting['title_font_text_decoration']) && $data_setting['title_font_text_decoration'] != '') ? $data_setting['title_font_text_decoration'] : 'none';
            $less_data['title_font_line_height'] = (isset($data_setting['title_font_line_height']) && $data_setting['title_font_line_height'] != '') ? $data_setting['title_font_line_height'] : 1.5;
            $less_data['title_font_letter_spacing'] = (isset($data_setting['title_font_letter_spacing']) && $data_setting['title_font_letter_spacing'] != '') ? $data_setting['title_font_letter_spacing'] . 'px' : 0;

            $less_data['content_font_italic_style'] = (isset($data_setting['content_font_italic_style']) && $data_setting['content_font_italic_style'] == 1) ? 'italic' : 'normal';
            $less_data['content_font_weight'] = (isset($data_setting['content_font_weight']) && $data_setting['content_font_weight'] != '') ? $data_setting['content_font_weight'] : 'normal';
            $less_data['content_font_text_transform'] = (isset($data_setting['content_font_text_transform']) && $data_setting['content_font_text_transform'] != '') ? $data_setting['content_font_text_transform'] : 'none';
            $less_data['content_font_text_decoration'] = (isset($data_setting['content_font_text_decoration']) && $data_setting['content_font_text_decoration'] != '') ? $data_setting['content_font_text_decoration'] : 'none';
            $less_data['content_font_line_height'] = (isset($data_setting['content_font_line_height']) && $data_setting['content_font_line_height'] != '') ? $data_setting['content_font_line_height'] : 1.5;
            $less_data['content_font_letter_spacing'] = (isset($data_setting['content_font_letter_spacing']) && $data_setting['content_font_letter_spacing'] != '') ? $data_setting['content_font_letter_spacing'] . 'px' : 0;

            $less_data['meta_font_italic_style'] = (isset($data_setting['meta_font_italic_style']) && $data_setting['meta_font_italic_style'] == 1) ? 'italic' : 'normal';
            $less_data['meta_font_weight'] = (isset($data_setting['meta_font_weight']) && $data_setting['meta_font_weight'] != '') ? $data_setting['meta_font_weight'] : 'normal';
            $less_data['meta_font_text_transform'] = (isset($data_setting['meta_font_text_transform']) && $data_setting['meta_font_text_transform'] != '') ? $data_setting['meta_font_text_transform'] : 'none';
            $less_data['meta_font_text_decoration'] = (isset($data_setting['meta_font_text_decoration']) && $data_setting['meta_font_text_decoration'] != '') ? $data_setting['meta_font_text_decoration'] : 'none';
            $less_data['meta_font_line_height'] = (isset($data_setting['meta_font_line_height']) && $data_setting['meta_font_line_height'] != '') ? $data_setting['meta_font_line_height'] : 1.5;
            $less_data['meta_font_letter_spacing'] = (isset($data_setting['meta_font_letter_spacing']) && $data_setting['meta_font_letter_spacing'] != '') ? $data_setting['meta_font_letter_spacing'] . 'px' : 0;


            $less_data['portfolio_filter_padding_top'] = (isset($data_setting['filter_padding_top']) && $data_setting['filter_padding_top'] != '') ? trim($data_setting['filter_padding_top']) . 'px' : '0';
            $less_data['portfolio_filter_padding_right'] = (isset($data_setting['filter_padding_right']) && $data_setting['filter_padding_right'] != '') ? trim($data_setting['filter_padding_right']) . 'px' : '0';
            $less_data['portfolio_filter_padding_bottom'] = (isset($data_setting['filter_padding_bottom']) && $data_setting['filter_padding_bottom'] != '') ? trim($data_setting['filter_padding_bottom']) . 'px' : '0';
            $less_data['portfolio_filter_padding_left'] = (isset($data_setting['filter_padding_left']) && $data_setting['filter_padding_left'] != '') ? trim($data_setting['filter_padding_left']) . 'px' : '0';
            $less_data['portfolio_filter_border_width'] = (isset($data_setting['filter_border_width']) && $data_setting['filter_border_width'] != '') ? trim($data_setting['filter_border_width']) . 'px' : '0';
            $less_data['portfolio_filter_border_style'] = (isset($data_setting['filter_border_style']) && $data_setting['filter_border_style'] != '') ? trim($data_setting['filter_border_style']) : 'none';
            $less_data['portfolio_filter_border_color'] = (isset($data_setting['filter_border_color']) && $data_setting['filter_border_color'] != '') ? trim($data_setting['filter_border_color']) : 'transparent';
            $less_data['portfolio_filter_text_back_color'] = (isset($data_setting['filter_text_back_color']) && $data_setting['filter_text_back_color'] != '') ? trim($data_setting['filter_text_back_color']) : 'transparent';
            $less_data['portfolio_filter_text_back_hover_color'] = (isset($data_setting['filter_text_back_hover_color']) && $data_setting['filter_text_back_hover_color'] != '') ? trim($data_setting['filter_text_back_hover_color']) : 'transparent';
            $less_data['portfolio_filter_text_border_color'] = (isset($data_setting['filter_text_border_color']) && $data_setting['filter_text_border_color'] != '') ? trim($data_setting['filter_text_border_color']) : '#000000';

            $button_type = (isset($data_setting['button_type']) && $data_setting['button_type'] != '') ? trim($data_setting['button_type']) : 'rectangle';
            if ($button_type == 'oval') {
                $button_radius_unit = (isset($data_setting['button_radius_unit']) && $data_setting['button_radius_unit'] != '') ? trim($data_setting['button_radius_unit']) : 'px';
                $less_data['button_radius'] = (isset($data_setting['button_radius']) && $data_setting['button_radius'] != '') ? trim($data_setting['button_radius']) . $button_radius_unit : '50' . $button_radius_unit;
            } else {
                $less_data['button_radius'] = '0';
            }

            $less_data['content_background_color'] = (isset($data_setting['content_background_color']) && $data_setting['content_background_color'] != '') ? trim($data_setting['content_background_color']) : '#222222';

            $less = new portdesign_lessc();
            $less->setVariables($less_data);
            echo '<style type="text/css" id="dynamic_style">';
            echo $less->compileFile(PORT_LITE_PLUGIN_DIR . 'less/style.less');
            try {
                echo $less->compile($data_setting['custom_css']);
            } catch (exception $e) {
                echo '';
            }

            echo '</style>';
            $data_setting['content_font'] = '';
            $data_setting['meta_font'] = '';
            $data_setting['button_font'] = '';
            $data_setting['pagination_type'] = 'pagination';
            $data_setting['column_layout'] = 3;
            if (!isset($data_setting['taxonomy'])) {
                $data_setting['taxonomy'] = array();
            }
            $post_terms = array();

            $overflow = false;
            $overflow_array = array('flyer_top_left', 'flyer_top_right', 'flyer_bottom_left', 'flyer_bottom_right', 'skate_top', 'skate_bottom');
            if (in_array($data_setting['image_effect'], $overflow_array)) {
                $overflow = true;
            }
            $content_position = (isset($data_setting['content_position'])) ? $data_setting['content_position'] : 'overlay_image';
            $excerpt_null_array = array('skate_top', 'skate_bottom', 'shift_top', 'shift_bottom', 'door_slide', 'reducer', 'retard_top', 'retard_bottom');
            $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
            $args = array(
                'post_type' => $data_setting['layout_post'],
                'order' => $data_setting['order'],
                'orderby' => $data_setting['order_by'],
                'paged' => $paged,
                'ignore_sticky_posts' => 1,
            );
            if (isset($data_setting['post']) && !empty($data_setting['post'])) {
                $args['post__in'] = $data_setting['post'];
            }
            if ($data_setting['enable_filter'] == 1) {
                $args['posts_per_page'] = -1;
            } else {
                $args['posts_per_page'] = $data_setting['number_post'];
            }
            if (!empty($data_setting['categories']) && !empty($data_setting['taxonomy'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $data_setting['taxonomy'],
                        'field' => 'slug',
                        'terms' => $data_setting['categories'],
                    )
                );
            }


            $current_page_id = $post->ID;

            $the_query = new WP_Query($args);
            $wp_query = $the_query;
            if ($the_query->have_posts()) {

                /* social share before loop */
                pdl_get_social_icons($setting, 0, $current_page_id); //0 for before loop
                ?>
                <div class="portfolio_designer_id<?php
                if ($data_setting['enable_filter'] == 1 && $data_setting['layout_type'] != 'slider')
                    echo ' portfolio_filter_class';
                if ($data_setting['layout_type'] == 'grid' && $data_setting['enable_filter'] != 1)
                    echo ' portfolio_grid_class';
                ?>">
                         <?php if ($data_setting['enable_filter'] == 1 && $data_setting['layout_type'] != 'slider') { ?>
                        <div class="portfolio_filter_gallery">
                            <ul id="portfolio_filter_gallery_ul" class="portfolio_filter_gallery_ul" >
                                <li id="portfolio_gallery_menu_Showall" data-filter="*" class="show_all"><a class="portfolio_gallery_selected" href="javascript:void(0)"><?php echo ($data_setting['show_all_txt']) ? $data_setting['show_all_txt'] : __('Show All', 'portfolio-designer-lite') ?></a></li>
                                <?php
                                if (!empty($data_setting['categories'])) {
                                    foreach ($data_setting['categories'] as $category) {
                                        $term = get_term_by('slug', $category, $data_setting['taxonomy']);
                                        if (isset($term->count) && $term->count > 0) {
                                            ?>
                                            <li data-filter=".<?php echo $term->slug; ?>" class="<?php echo $term->slug; ?>">
                                                <a href="javascript:void(0)"><?php echo $term->name; ?></a>
                                            </li>
                                            <?php
                                        }
                                    }
                                } else {
                                    while ($the_query->have_posts()) {
                                        $the_query->the_post();
                                        if (!empty($data_setting['taxonomy'])) {
                                            $terms = wp_get_post_terms(get_the_ID(), $data_setting['taxonomy']);
                                            foreach ($terms as $term) {
                                                $post_terms[$term->slug] = $term->name;
                                            }
                                        }
                                    }
                                    ksort($post_terms);
                                    foreach ($post_terms as $slug => $name) {
                                        ?>
                                        <li data-filter=".<?php echo $slug; ?>" class="<?php echo $slug; ?>">
                                            <a href="javascript:void(0)"><?php echo $name; ?></a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <div class="portfolio_gallery column-layput-<?php echo $data_setting['column_layout']; ?> <?php echo ($data_setting['layout_type'] == 'masonary') ? 'portfolio_gallery_masonary' : ''; ?> ">
                        <?php
                        while ($the_query->have_posts()) {
                            $the_query->the_post();
                            $termslug = '';
                            if (!empty($data_setting['taxonomy'])) {
                                $terms = wp_get_post_terms(get_the_ID(), $data_setting['taxonomy']);
                                foreach ($terms as $term) {
                                    $termslug .= $term->slug . ' ';
                                }
                            }
                            ?>
                            <div class="pdl_single_wrapp <?php echo $content_position; ?> clm-<?php echo $data_setting['column_layout']; ?> <?php echo ($data_setting['enable_overlay'] == 1) ? $data_setting['image_effect'] : ''; ?> <?php echo ($termslug != '') ? $termslug : ''; ?>" style="display: inline-block; height: auto;">
                                <div class="mask-wrapper portfolio-image-content <?php echo ($overflow) ? 'overflow-visable' : ''; ?>">
                                    <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>" >
                                        <?php
                                        $post_thumbnail = 'full';
                                        $thumbnail = pdl_get_the_thumbnail($data_setting, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
                                        if (!empty($thumbnail)) {
                                            echo apply_filters('portfolio_post_thumbnail_filter', $thumbnail, $post->ID);
                                        }
                                        ?>
                                    </a>
                                    <?php
                                    if ($data_setting['enable_overlay'] == 1) {
                                        ?>
                                        <div class="mask_bg"></div>
                                        <div class="mask">
                                            <div class="mask-inner">
                                                <?php if ($content_position == 'overlay_image') { ?>
                                                    <a href="<?php echo get_the_permalink(); ?>">
                                                        <h4><?php the_title(); ?></h4>
                                                    </a>
                                                    <?php
                                                    $excerpt_lenth = isset($data_setting['summary']) ? $data_setting['summary'] : 0;
                                                    if (!in_array($data_setting['image_effect'], $excerpt_null_array) && $excerpt_lenth > 0) {
                                                        if (has_excerpt()) {
                                                            echo '<p>' . wp_trim_words(get_the_excerpt(), $excerpt_lenth, '...') . '</p>';
                                                        } else {
                                                            echo '<p>' . wp_trim_words(get_the_content(), $excerpt_lenth, '...') . '</p>';
                                                        }
                                                    }
                                                }

                                                if ($data_setting['image_link'] != 'disable' || ( isset($data_setting['enable_popup_link']) && $data_setting['enable_popup_link'] == 1 )) {
                                                    ?>
                                                    <div class="info-wrapp">
                                                        <?php
                                                        $project_url = get_post_meta(get_the_ID(), 'portfolio_lite_url', true);
                                                        if (!empty($project_url) && $data_setting['image_link'] != 'disable') {
                                                            ?>
                                                            <a class="info linkbut" href="<?php echo esc_attr($project_url); ?>" title="<?php echo get_the_title(); ?>" <?php echo ($data_setting['image_link'] == 'new_tab') ? 'target="_blank"' : ''; ?> >
                                                                <span class="fas fa-link"></span>
                                                            </a>
                                                            <?php
                                                        }
                                                        if (isset($data_setting['enable_popup_link']) && $data_setting['enable_popup_link'] == 1) {
                                                            if (has_post_thumbnail()) {
                                                                ?>
                                                                <a class="info port_fancybox" href="<?php echo the_post_thumbnail_url('full'); ?>" title="<?php echo get_the_title(); ?>" rel="group">
                                                                    <span class="fas fa-search"></span>
                                                                </a>
                                                                <?php
                                                            } elseif (isset($data_setting['default_image_src']) && $data_setting['default_image_src'] != '') {
                                                                ?>
                                                                <a class="info port_fancybox" href="<?php echo esc_attr($data_setting['default_image_src']); ?>" title="<?php echo get_the_title(); ?>" rel="group">
                                                                    <span class="fas fa-search"></span>
                                                                </a>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a class="info port_fancybox" href="<?php echo PORT_LITE_PLUGIN_URL . 'images/no_image.jpg'; ?>" title="<?php echo get_the_title(); ?>" rel="group">
                                                                    <span class="fas fa-search"></span>
                                                                </a>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php if ($content_position != 'overlay_image') { ?>
                                    <div class="mask-contents portfolio-image-content">
                                        <div class="mask-contents-wrapp">
                                            <a href="<?php echo get_the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
                                            <?php
                                            $excerpt_lenth = isset($data_setting['summary']) ? $data_setting['summary'] : 0;
                                            if ($excerpt_lenth > 0) {
                                                if (has_excerpt()) {
                                                    echo '<p>' . wp_trim_words(get_the_excerpt(), $excerpt_lenth, '...') . '</p>';
                                                } else {
                                                    echo '<p>' . wp_trim_words(get_the_content(), $excerpt_lenth, '...') . '</p>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <?php
                    /* social share after loop */
                    pdl_get_social_icons($setting, 1, $current_page_id); // 1 for after loop

                    if ($data_setting['enable_pagination'] == 1 && $data_setting['enable_filter'] != 1) {
                        if ($data_setting['pagination_type'] == 'pagination') {
                            pdl_paging_nav();
                        }
                    }
                    ?>
                </div>
                <?php
                wp_reset_query();
            } else {
                esc_html_e('No post found.','portfolio-designer-lite');
            }
        }

        $data = ob_get_clean();
        return $data;
    }

}

new PortfolioDesignerLightShortcode();
