<?php
if (!defined('ABSPATH')) {
    exit;
}
/* Shortcode Page */
require_once PORT_LITE_PLUGIN_DIR . 'includes/shortcode.php';

/**
 * Pagination
 * @param type $mid_size
 * @return Pagination HTML
 */
if (!function_exists('pdl_paging_nav')) {

    function pdl_paging_nav($mid_size = 1) {
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
            $prev_arrow = '<span class="next-prev-arrow"><i class="dashicons dashicons-arrow-right-alt2"></i></span>';
            $next_arrrow = '<span class="next-prev-arrow"><i class="dashicons dashicons-arrow-left-alt2"></i></span>';
        } else {
            $prev_arrow = '<span class="next-prev-arrow"><i class="dashicons dashicons-arrow-left-alt2"></i></span>';
            $next_arrrow = '<span class="next-prev-arrow"><i class="dashicons dashicons-arrow-right-alt2"></i></span>';
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
                <h1 class="screen-reader-text"><?php _e('Posts navigation', 'portfolio-designer-lite'); ?></h1>
                <div class="pagination loop-pagination">
                    <?php echo $links; ?>
                </div><!-- .pagination -->
            </nav><!-- .navigation -->
            <?php
        endif;
    }

}

/**
 *  Add social share icons in portfolio layout shortcode
 */
if (!function_exists('pdl_get_social_icons')) {

    function pdl_get_social_icons($data_setting, $position = 0, $current_page_id) {

        if (!isset($data_setting['portfolio_enable_social_share_settings'])) {
            return;
        }

        if (isset($data_setting['portfolio_enable_social_share_settings']) && $data_setting['portfolio_enable_social_share_settings'] != 1) {
            return;
        }

        if (isset($data_setting['portfolio_social_icon_display_position']) && $position != $data_setting['portfolio_social_icon_display_position']) {
            return;
        }

        if (($data_setting['portfolio_facebook_link'] == 1) || ($data_setting['portfolio_twitter_link'] == 1) ||
                ($data_setting['portfolio_google_link'] == 1) || ($data_setting['portfolio_linkedin_link'] == 1) ||
                ( $data_setting['portfolio_pinterest_link'] == 1)
        ) {
            ?>
            <div class="social-component <?php
            echo sanitize_title(get_the_title());
            if (isset($data_setting['portfolio_social_style']) && $data_setting['portfolio_social_style'] == 0) {
                if (isset($data_setting['portfolio_social_icon_size']) && $data_setting['portfolio_social_icon_size'] == 0) {
                    echo ' large ';
                }
                if (isset($data_setting['portfolio_social_icon_style']) && $data_setting['portfolio_social_icon_style'] == 0) {
                    echo ' circle ';
                }
            }

            if ($data_setting['portfolio_social_style'] == 1) {
                echo ' default-icon ';
                echo (isset($data_setting['social_icon_theme']) && $data_setting['social_icon_theme'] != '') ? 'social_icon_theme_' . $data_setting['social_icon_theme'] : 'social_icon_theme_2';
            }

            if (isset($data_setting['portfolio_social_icon_alignment'])) {
                echo ' align-' . $data_setting['portfolio_social_icon_alignment'] . ' ';
            }

            if (isset($data_setting['portfolio_social_count_position'])) {
                echo $data_setting['portfolio_social_count_position'];
            }

            if (isset($data_setting['portfolio_social_icon_display_position']) && $data_setting['portfolio_social_icon_display_position'] == 1) {
                echo " after ";
            } else {
                echo " before ";
            }
            ?>">
                     <?php if ($data_setting['portfolio_facebook_link'] == 1) { ?>
                    <div class="social-share">
                        <a href="<?php echo 'https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink($current_page_id); ?>" target="_blank" class="pdl-facebook-share facebook-share social-icon">
                            <i class="fab fa-facebook"></i>
                        </a>
                    </div>
                <?php } ?>

                <?php if ($data_setting['portfolio_google_link'] == 1) { ?>
                    <div class="social-share">
                        <a href="<?php echo 'https://plus.google.com/share?url=' . get_the_permalink($current_page_id); ?>" target="_blank" class="google-share social-icon">
                            <i class="fab fa-google-plus-square"></i>
                        </a>
                    </div>
                <?php } ?>

                <?php if ($data_setting['portfolio_linkedin_link'] == 1) { ?>
                    <div class="social-share">
                        <a href="<?php echo 'https://www.linkedin.com/shareArticle?url=' . get_the_permalink($current_page_id); ?>" target="_blank" class="linkedin-share social-icon">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                <?php } ?>

                <?php if ($data_setting['portfolio_pinterest_link'] == 1) { ?>
                    <div class="social-share">
                        <a href="<?php echo '//pinterest.com/pin/create/button/?url=' . get_the_permalink($current_page_id) . '&description=' . get_the_title(); ?>" target="_blank" class="pinterest-share social-icon">
                            <i class="fab fa-pinterest-square"></i>
                        </a>
                    </div>
                <?php } ?>

                <?php if ($data_setting['portfolio_twitter_link'] == 1) { ?>
                    <div class="social-share">
                        <a href="<?php echo 'https://twitter.com/share?text=' . get_the_title() . '&url=' . get_the_permalink($current_page_id); ?>" target="_blank" class="twitter-share social-icon">
                            <i class="fab fa-twitter-square"></i>
                        </a>
                    </div>
                <?php } ?>
                <div class="clear"></div>
            </div>
            <div class="clear"></div><?php
        }
    }

}



if (!function_exists('pdl_get_the_thumbnail')) {

    function pdl_get_the_thumbnail($portfolio_settings, $post_thumbnail, $post_thumbnail_id, $portfolio_post_id) {
        $thumbnail = '';
        if ($post_thumbnail == '') {
            $post_thumbnail = 'full';
        }

        if (has_post_thumbnail()) {
            if (isset($portfolio_settings['thumb_size'])) {
                $post_thumbnail = $portfolio_settings['thumb_size'];
                $thumbnail = get_the_post_thumbnail($portfolio_post_id, $post_thumbnail);
            } else {
                $thumbnail = get_the_post_thumbnail($portfolio_post_id, $post_thumbnail);
            }
        } elseif (isset($portfolio_settings['default_image_id']) && $portfolio_settings['default_image_id'] != '') {
            if (isset($portfolio_settings['thumb_size'])) {
                $post_thumbnail = $portfolio_settings['thumb_size'];
                $thumbnail = wp_get_attachment_image($portfolio_settings['default_image_id'], $post_thumbnail);
            } else {
                $thumbnail = get_the_post_thumbnail($portfolio_settings['default_image_id'], $post_thumbnail);
            }
            if(empty($thumbnail)){
                $thumbnail = '<img src="' . PORT_LITE_PLUGIN_URL . 'images/no_image.jpg' . '" alt="<?php echo get_the_title(); ?>" />';
            }
        } else {
            $thumbnail = '<img src="' . PORT_LITE_PLUGIN_URL . 'images/no_image.jpg' . '" alt="<?php echo get_the_title(); ?>" />';
        }
        return $thumbnail;
    }

}
/**
 * @parma $image_url
 * @parma $width
 * @parma $height
 * @parma $corp
 * Resize Images
 */
if (!function_exists('pdl_image_resize')) {

    function pdl_image_resize($img_url = null, $width, $height, $crop = false) {
        // this is an attachment, so we have the ID
        $file_path = '';
        if ($img_url) {
            $file_path = parse_url($img_url);
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
            // Look for Multisite Path
            if (is_multisite()) {
                $img_info = pathinfo($img_url);
                $uploads_dir = wp_upload_dir();
                $file_path = $uploads_dir['path'] . '/' . $img_info['basename'];
            }
            if (!file_exists($file_path)) {
                return;
            }
            $orig_size = getimagesize($file_path);
            $image_src[0] = $img_url;
            $image_src[1] = $orig_size[0];
            $image_src[2] = $orig_size[1];
        }
        $file_info = pathinfo($file_path);
        // check if file exists
        $base_file = $file_info['dirname'] . '/' . $file_info['filename'] . '.' . $file_info['extension'];

        if (!file_exists($base_file)) {
            return;
        }
        $extension = '.' . $file_info['extension'];
        // the image path without the extension
        $no_ext_path = $file_info['dirname'] . '/' . $file_info['filename'];
        $cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
        // checking if the file size is larger than the target size
        // if it is smaller or the same size, stop right here and return
        if ($image_src[1] > $width) {
            // the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
            if (file_exists($cropped_img_path)) {
                $cropped_img_url = str_replace(basename($image_src[0]), basename($cropped_img_path), $image_src[0]);
                $portfolio_images = array(
                    'url' => $cropped_img_url,
                    'width' => $width,
                    'height' => $height
                );
                return $portfolio_images;
            }
            // $crop = false or no height set
            if ($crop == false OR ! $height) {
                // calculate the size proportionaly
                $proportional_size = wp_constrain_dimensions($image_src[1], $image_src[2], $width, $height);
                $resized_img_path = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
                // checking if the file already exists
                if (file_exists($resized_img_path)) {
                    $resized_img_url = str_replace(basename($image_src[0]), basename($resized_img_path), $image_src[0]);
                    $portfolio_images = array(
                        'url' => $resized_img_url,
                        'width' => $proportional_size[0],
                        'height' => $proportional_size[1]
                    );
                    return $portfolio_images;
                }
            }
            // check if image width is smaller than set width
            $img_size = getimagesize($file_path);
            if ($img_size[0] <= $width)
                $width = $img_size[0];
            // Check if GD Library installed
            if (!function_exists('imagecreatetruecolor')) {
                _e('GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library', 'portfolio-designer-lite');
                return;
            }
            // no cache files - let's finally resize it
            $image = wp_get_image_editor($file_path);

            if (!is_wp_error($image)) {
                $new_file_name = $file_info['filename'] . "-" . $width . "x" . $height . '.' . $file_info['extension'];
                $image->resize($width, $height, $crop);
                $image->save($file_info['dirname'] . '/' . $new_file_name);
            }
            $new_img_path = $file_info['dirname'] . '/' . $new_file_name;
            $new_img_size = getimagesize($new_img_path);
            $new_img = str_replace(basename($image_src[0]), basename($new_img_path), $image_src[0]);
            // resized output
            $portfolio_images = array(
                'url' => $new_img,
                'width' => $new_img_size[0],
                'height' => $new_img_size[1]
            );
            return $portfolio_images;
        }
        // default output - without resizing
        $portfolio_images = array(
            'url' => $image_src[0],
            'width' => $width,
            'height' => $height
        );
        return $portfolio_images;
    }

}