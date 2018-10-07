<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * The Template for displaying Breadcrumb for all page
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!function_exists('mine_custom_breadcrumbs')) {

    /**
     * Display Breadcrumbs
     *
     * @global object $post
     * @global Object $wp_query
     */
    function mine_custom_breadcrumbs() {
        // Settings
        $separator = '&frasl;';
        $breadcrums_id = 'breadcrumbs';
        $breadcrums_class = 'breadcrumbs';
        $home_title = 'Home';
        // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
        $custom_taxonomy = 'product_cat';
        // Get the query & post information
        global $post, $wp_query;

        // Do not display on the home page
        if (!is_front_page()) {
            // Build the breadcrums
            echo '<ul id="' . esc_attr($breadcrums_id) . '" class="' . esc_html($breadcrums_class) . '">';

            // Home page
            echo '<li class="item-home"><a class="bread-link bread-home" href="' . esc_html(get_home_url()) . '" title="' . esc_html($home_title) . '">' . esc_html($home_title) . '</a></li>';
            echo '<li class="separator separator-home"> ' . esc_html($separator) . ' </li>';

            if (is_home()) {
                $page_id = get_queried_object_id();
                echo '<li class="item-current item-' . esc_html($page_id) . '"><div title="' . get_the_title(esc_html($page_id)) . '"> ' . get_the_title(esc_html($page_id)) . '</div></li>';
            }
            if (is_archive() && !is_tax() && !is_category() && !is_tag() && !is_author()) {

                echo '<li class="item-current item-archive"><div class="bread-current bread-archive">';
                if (is_day()) :
                    printf(esc_html_e('Date Archives', 'mine') . ': <span>' . get_the_date() . '</span>');
                elseif (is_month()) :
                    printf(esc_html_e('Monthly Archives', 'mine') . ': <span>' . get_the_date('F Y') . '</span>');
                elseif (is_year()) :
                    printf(esc_html_e('Yearly Archives', 'mine') . ': <span>' . get_the_date('Y') . '</span>');
                else :
                    echo post_type_archive_title($prefix = '', false);
                endif;
                echo '</div></li>';
            }
            else if (is_archive() && is_tax() && !is_category() && !is_tag()) {
                // If post is a custom post type

                $post_type = get_post_type();
                // If it is a custom post type display name and link
                if ($post_type != 'post') {

                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);

                    echo '<li class="item-cat item-custom-post-type-' . esc_html($post_type) . '"><a class="bread-cat bread-custom-post-type-' . esc_html($post_type) . '" href="' . esc_html($post_type_archive) . '" title="' . esc_html($post_type_object->labels->name) . '">' . esc_html($post_type_object->labels->name) . '</a></li>';
                    echo '<li class="separator"> ' . esc_html($separator) . ' </li>';
                }

                $custom_tax_name = get_queried_object()->name;
                echo '<li class="item-current item-archive"><div class="bread-current bread-archive ravi">' . esc_html($custom_tax_name) . '</div></li>';
            } else if (is_single()) {
                // If post is a custom post type
                $post_type = get_post_type();

                // If it is a custom post type display name and link
                if ($post_type != 'post') {
                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);

                    echo '<li class="item-cat item-custom-post-type-' . esc_html($post_type) . '"><a class="bread-cat bread-custom-post-type-' . esc_html($post_type) . '" href="' . esc_html($post_type_archive) . '" title="' . esc_html($post_type_object->labels->name) . '">' . esc_html($post_type_object->labels->name) . '</a></li>';
                    echo '<li class="separator"> ' . esc_html($separator) . ' </li>';
                }

                // Get post category info
                $category = get_the_category();

                if (!empty($category)) {
                    // Get last category post is in
                    $values_of_category = array_values($category);
                    $last_category = end($values_of_category);

                    // Get parent any categories and create array
                    $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','), ',');
                    $cat_parents = explode(',', $get_cat_parents);

                    // Loop through parent categories and store in variable $cat_display
                    $cat_display = '';
                    foreach ($cat_parents as $parents) {
                        $cat_display .= '<li class="item-cat">' . ($parents) . '</li>';
                        $cat_display .= '<li class="separator"> ' . esc_html($separator) . ' </li>';
                    }
                }

                // If it's a custom post type within a custom taxonomy
                $taxonomy_exists = taxonomy_exists($custom_taxonomy);
                $taxonomies = get_taxonomies();

                if (empty($last_category) && !empty($taxonomies) && $taxonomies) {
                    foreach ($taxonomies as $taxonomie) {
                        $taxonomy_terms = get_the_terms($post->ID, $taxonomie);
                        if (!empty($taxonomy_terms)) {
                            if (isset($taxonomy_exists) && $taxonomy_terms == $custom_taxonomy) {
                                $cat_id = $taxonomy_terms[0]->term_id;
                                $cat_nicename = $taxonomy_terms[0]->slug;
                                $cat_link = get_term_link($taxonomy_terms[0]->term_id, $taxonomie);
                                $cat_name = $taxonomy_terms[0]->name;
                            }
                        }
                    }
                }

                // Check if the post is in a category
                if (!empty($last_category)) {
                    print_r($cat_display);
                    echo '<li class="item-current item-' . esc_html($post->ID) . '"><div class="bread-current bread-' . esc_html($post->ID) . '" title="' . get_the_title() . '">' . get_the_title() . '</div></li>';

                    // Else if post is in a custom taxonomy
                } else if (!empty($cat_id)) {
                    echo '<li class="item-cat item-cat-' . esc_html($cat_id) . ' item-cat-' . esc_html($cat_nicename) . '"><a class="bread-cat bread-cat-' . esc_html($cat_id) . ' bread-cat-' . esc_html($cat_nicename) . '" href="' . esc_html($cat_link) . '" title="' . esc_attr($cat_name) . '">' . esc_html($cat_name) . '</a></li>';
                    echo '<li class="separator"> ' . esc_html($separator) . ' </li>';
                    echo '<li class="item-current item-' . esc_html($post->ID) . '"><div class="bread-current bread-' . esc_html($post->ID) . '" title="' . get_the_title() . '">' . get_the_title() . '</div></li>';
                } else {

                    echo '<li class="item-current item-' . esc_html($post->ID) . '"><div class="bread-current bread-' . esc_html($post->ID) . '" title="' . get_the_title() . '">' . get_the_title() . '</div></li>';
                }
            } else if (is_category()) {
                // Category page
                global $cat;
                $return = "";
                $category = get_category($cat);
                if ($category->parent && ( $category->parent != $category->term_id )) {
                    $return .= '<li>' . get_category_parents($category->parent, TRUE, ' &frasl; ', FALSE) . '</li>';
                }
                print_r($return);
                //echo $cat_display;
                echo '<li class="item-current item-cat"><div class="bread-current bread-cat">' . single_cat_title('', false) . '</div></li>';
            } else if (is_page()) {
                // Standard page
                if ($post->post_parent) {

                    // If child page, get parents
                    $anc = get_post_ancestors($post->ID);
                    // Get parents in the right order
                    $anc = array_reverse($anc);
                    $parents = "";
                    // Parent page loop
                    foreach ($anc as $ancestor) {
                        $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                        $parents .= '<li class="separator separator-' . $ancestor . '"> ' . esc_html($separator) . ' </li>';
                    }

                    // Display parent pages
                    print_r($parents);

                    // Current page
                    echo '<li class="item-current item-' . esc_html($post->ID) . '"><div title="' . get_the_title() . '"> ' . get_the_title() . '</div></li>';
                } else {

                    // Just display current page if not parents
                    echo '<li class="item-current item-' . esc_html($post->ID) . '"><div class="bread-current bread-' . esc_html($post->ID) . '"> ' . get_the_title() . '</div></li>';
                }
            } else if (is_tag()) {
                // Tag page
                // Get tag information
                $term_id = get_query_var('tag_id');

                $terms = get_terms(array(
                    'taxonomy' => 'post_tag',
                    'hide_empty' => false
                ));

                $get_term_id = $terms[0]->term_id;
                $get_term_slug = $terms[0]->slug;
                $get_term_name = $terms[0]->name;

                // Display the tag name
                echo '<li class="item-current item-tag-' .esc_html($get_term_id) . ' item-tag-' . esc_html($get_term_slug) . '"><div class="bread-current bread-tag-' . esc_html($get_term_id) . ' bread-tag-' . esc_html($get_term_slug) . '">' . esc_html($get_term_name) . '</div></li>';
            } elseif (is_day()) {

                // Day archive
                // Year link
                echo '<li class="item-year item-year-' . esc_html(get_the_time('Y')) . '"><a class="bread-year bread-year-' . esc_html(get_the_time('Y')) . '" href="' . esc_html(get_year_link(esc_html(get_the_time('Y')))) . '" title="' . esc_html(get_the_time('Y')) . '">' . esc_html(get_the_time('Y')) . ' Archives</a></li>';
                echo '<li class="separator separator-' . esc_html(get_the_time('Y')) . '"> ' . esc_html($separator) . ' </li>';

                // Month link
                echo '<li class="item-month item-month-' . esc_html(get_the_time('m')) . '"><a class="bread-month bread-month-' . esc_html(get_the_time('m')) . '" href="' . esc_html(get_month_link(esc_html(get_the_time('Y'))), esc_html(get_the_time('m'))) . '" title="' . esc_html(get_the_time('M')) . '">' . esc_html(get_the_time('M')) . ' Archives</a></li>';
                echo '<li class="separator separator-' . esc_html(get_the_time('m')) . '"> ' . esc_html($separator) . ' </li>';

                // Day display
                echo '<li class="item-current item-' . esc_html(get_the_time('j')) . '"><div class="bread-current bread-' . esc_html(get_the_time('j')) . '"> ' . esc_html(get_the_time('jS')) . ' ' . esc_html(get_the_time('M')) . ' Archives</div></li>';
            } else if (is_month()) {

                // Month Archive
                // Year link
                echo '<li class="item-year item-year-' . esc_html(get_the_time('Y')) . '"><a class="bread-year bread-year-' . esc_html(get_the_time('Y')) . '" href="' . esc_html(get_year_link(esc_html(get_the_time('Y')))) . '" title="' . esc_html(get_the_time('Y')) . '">' . esc_html(get_the_time('Y')) . ' Archives</a></li>';
                echo '<li class="separator separator-' . esc_html(get_the_time('Y')) . '"> ' . esc_html($separator) . ' </li>';

                // Month display
                echo '<li class="item-month item-month-' . esc_html(get_the_time('m')) . '"><div class="bread-month bread-month-' . esc_html(get_the_time('m')) . '" title="' . esc_html(get_the_time('M')) . '">' . esc_html(get_the_time('M')) . ' Archives</div></li>';
            } else if (is_year()) {
                // Display year archive
                echo '<li class="item-current item-current-' . esc_html(get_the_time('Y')) . '"><div class="bread-current bread-current-' . esc_html(get_the_time('Y')) . '" title="' . esc_html(get_the_time('Y')) . '">' . esc_html(get_the_time('Y')) . ' Archives</div></li>';
            } else if (is_author()) {
                // Auhor archive
                // Get the author information
                global $author;
                $userdata = get_userdata($author);
                // Display author name
                echo '<li class="ravi item-current item-current-' . esc_html($userdata->user_nicename) . '"><div class="bread-current bread-current-' . esc_html($userdata->user_nicename) . '" title="' . esc_html($userdata->display_name) . '">' . ' ' . esc_html($userdata->display_name) . '</div></li>';
            } else if (is_search()) {
                // Search results page
                echo '<li class="item-current item-current-' . get_search_query() . '"><div class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</div></li>';
            } elseif (is_404()) {
                // 404 page
                echo '<li>' . esc_html_e('Error 404', 'mine') . '</li>';
            }
            echo '</ul>';
        }
    }

}