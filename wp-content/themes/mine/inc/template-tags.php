<?php
/**
 * Custom Mine Theme template tags
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!function_exists('mine_entry_meta')) :

    /**
     * Prints HTML with meta information for the categories, tags.
     *
     * Create your own mine_entry_meta() function to override in a child theme.
     *
     * @since 1.0
     */
    function mine_entry_meta() {
        if ('post' === get_post_type()) {
            $author_avatar_size = apply_filters('mine_author_avatar_size', 42);
            printf('<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>', get_avatar(get_the_author_meta('user_email'), $author_avatar_size), esc_html_x('Author', 'Used before post author name.', 'mine'), esc_url(get_author_posts_url(get_the_author_meta('ID'))), get_the_author()
            );
        }

        if (in_array(get_post_type(), array('post', 'attachment'))) {
            mine_entry_date();
        }

        $format = get_post_format();
        if (current_theme_supports('post-formats', $format)) {
            printf('<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>', sprintf('<span class="screen-reader-text">%s </span>', esc_html_x('Format', 'Used before post format.', 'mine')), esc_url(get_post_format_link($format)), esc_html(get_post_format_string($format))
            );
        }

        if ('post' === get_post_type()) {
            mine_entry_taxonomies();
        }

        if (!is_singular() && !post_password_required() && ( comments_open() || get_comments_number() )) {
            echo '<span class="comments-link">';
            comments_popup_link(sprintf(__('Leave a comment', 'mine') . '<span class="screen-reader-text"> ' . __('on', 'mine') . ' ' . get_the_title() . '</span>'));
            echo '</span>';
        }
    }

endif;

if (!function_exists('mine_entry_date')) :

    /**
     * Prints HTML with date information for current post.
     *
     * Create your own mine_entry_date() function to override in a child theme.
     *
     * @since 1.0
     */
    function mine_entry_date() {
        $time_string = '<i class="fa fa-clock-o"></i> <time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<i class="fa fa-clock-o"></i> <time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf($time_string, esc_html(get_the_date('c')), get_the_date(), esc_html(get_the_modified_date('c')), get_the_modified_date()
        );

        printf('<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>', esc_html_x('Posted on', 'Used before publish date.', 'mine'), esc_url(get_permalink()), esc_attr($time_string)
        );
    }

endif;

if (!function_exists('mine_entry_taxonomies')) :

    /**
     * Prints HTML with category and tags for current post.
     *
     * Create your own mine_entry_taxonomies() function to override in a child theme.
     *
     * @since 1.0
     */
    function mine_entry_taxonomies() {
        $categories_list = get_the_category_list(', ');
        if ($categories_list && mine_categorized_blog()) {
            printf('<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>', esc_html_x('Categories', 'Used before category names.', 'mine'), esc_attr($categories_list)
            );
        }

        $tags_list = get_the_tag_list('', ', ');
        if ($tags_list) {
            printf('<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>', esc_html_x('Tags', 'Used before tag names.', 'mine'), esc_attr($tags_list)
            );
        }
    }

endif;

if (!function_exists('mine_post_thumbnail')) :

    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     *
     * Create your own mine_post_thumbnail() function to override in a child theme.
     *
     * @since 1.0
     */
    function mine_post_thumbnail() {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
                <?php the_post_thumbnail('post-thumbnail', array('alt' => the_title_attribute('echo=0'))); ?>
            </a>

        <?php
        endif; // End is_singular()
    }

endif;

if (!function_exists('mine_excerpt')) :

    /**
     * Displays the optional excerpt.
     *
     * Wraps the excerpt in a div element.
     *
     * Create your own mine_excerpt() function to override in a child theme.
     *
     * @since 1.0
     *
     * @param string $class Optional. Class string of the div element. Defaults to 'entry-summary'.
     */
    function mine_excerpt($class = 'entry-summary') {
        $class = esc_attr($class);

        if (has_excerpt() || is_search()) :
            ?>
            <div class="<?php echo esc_attr($class); ?>">
                <?php the_excerpt(); ?>
            </div><!-- .<?php echo esc_attr($class); ?> -->
            <?php
        endif;
    }

endif;

if (!function_exists('mine_excerpt_more') && !is_admin()) :

    /**
     * Replaces "[...]" (appended to automatically generated excerpts) with ... and
     * a 'Continue reading' link.
     *
     * Create your own mine_excerpt_more() function to override in a child theme.
     *
     * @since 1.0
     *
     * @return string 'Continue reading' link prepended with an ellipsis.
     */
    function mine_excerpt_more() {
        $link = sprintf('<a href="%1$s" class="more-link">%2$s</a>', esc_url(get_permalink(get_the_ID())),
                /* translators: %s: Name of current post */ sprintf(__('Continue reading', 'mine') . '<span class="screen-reader-text"> "' . get_the_title(get_the_ID()) . '"</span>')
        );
        return ' &hellip; ' . $link;
    }

    add_filter('excerpt_more', 'mine_excerpt_more');
endif;

/**
 * Determines whether blog/site has more than one category.
 *
 * Create your own mine_categorized_blog() function to override in a child theme.
 *
 * @since 1.0
 *
 * @return bool True if there is more than one category, false otherwise.
 */
function mine_categorized_blog() {
    if (false === ( $all_the_cool_cats = get_transient('mine_categories') )) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories(array(
            'fields' => 'ids',
            // We only need to know if there is more than one category.
            'number' => 2,
        ));

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count($all_the_cool_cats);

        set_transient('mine_categories', $all_the_cool_cats);
    }

    if ($all_the_cool_cats > 1) {
        // This blog has more than 1 category so mine_categorized_blog should return true.
        return true;
    } else {
        // This blog has only 1 category so mine_categorized_blog should return false.
        return false;
    }
}

/**
 * Flushes out the transients used in mine_categorized_blog().
 *
 * @since 1.0
 */
function mine_category_transient_flusher() {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient('mine_categories');
}

add_action('edit_category', 'mine_category_transient_flusher');
add_action('save_post', 'mine_category_transient_flusher');

if (!function_exists('mine_the_custom_logo')) :

    /**
     * Displays the optional custom logo.
     *
     * Does nothing if the custom logo is not available.
     *
     * @since 1.0
     */
    function mine_the_custom_logo() {
        if (function_exists('the_custom_logo')) {
            the_custom_logo();
        }
    }

endif;


if (!function_exists('mine_posted_on')) :

    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function mine_posted_on() {

        $user = sprintf(
                '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        $categories = '';
        $terms = get_the_category(get_the_ID());
        if ($terms) {
            $i = 0;
            $size = sizeof($terms);
            foreach ($terms as $term) {
                $term_link = get_category_link($term);
                if (is_wp_error($term_link)) {
                    continue;
                }
                if ($size == $i + 1) {
                    $categories .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                } else {
                    $categories .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                    $categories .= ', ';
                }
                $i++;
            }
        }

        $tags = '';
        $terms = get_the_tags(get_the_ID());
        if ($terms) {
            $i = 0;
            $size = sizeof($terms);
            foreach ($terms as $term) {
                $term_link = get_category_link($term);
                if (is_wp_error($term_link)) {
                    continue;
                }
                if ($size == $i + 1) {
                    $tags .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                } else {
                    $tags .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                    $tags .= ', ';
                }
                $i++;
            }
        }

        if (is_single()) {
            ?>
            <div class="single-post-meta">
                <span class="user"><i class="fa fa-user"></i><?php echo esc_html_x(' By: ', 'post author', 'mine'); ?><?php echo esc_attr($user); ?></span>
                <?php if ($categories != '') { ?>
                    <span class="cutlery"><i class="fa fa-cutlery"></i><?php echo esc_attr($categories); ?></span>
                    <?php
                }
                if ($tags != '') {
                    ?>
                    <span class="spoon"><i class="fa fa-spoon"></i><?php echo esc_attr($tags); ?></span>
                <?php } ?>
                <span class="comments-o"><i class="fa fa-comments-o"></i><?php esc_html_e(' Comments: ', 'mine'); ?> <a href="<?php comments_link(); ?>"><?php echo esc_html(get_comments_number()); ?> </a></span>
            </div>
        <?php } else {
            ?>
            <div class="post-meta">
                <span class="user"><i class="fa fa-user"></i><?php echo esc_html_x(' By: ', 'post author', 'mine'); ?><?php echo esc_attr($user); ?></span>
                <?php if ($categories != '') { ?>
                    <span class="cutlery"><i class="fa fa-cutlery"></i><?php echo esc_attr($categories); ?></span>
                    <?php
                }
                if ($tags != '') {
                    ?>
                    <span class="spoon"><i class="fa fa-spoon"></i><?php echo esc_attr($tags); ?></span>
                <?php } ?>
                <span class="comments-o"><i class="fa fa-comments-o"></i><?php esc_html_e(' Comments: ', 'mine'); ?> <a href="<?php comments_link(); ?>"><?php echo esc_html(get_comments_number()); ?> </a></span>
            </div>
            <?php
        }
    }

endif;

if (!function_exists('mine_menu_posted_on')) :

    function mine_menu_posted_on() {


        $menu_type = '';
        $terms = get_the_terms(get_the_ID(), 'menu_type');
        if ($terms) {
            $i = 0;
            $size = sizeof($terms);
            foreach ($terms as $term) {
                $term_link = get_category_link($term);
                if (is_wp_error($term_link)) {
                    continue;
                }
                if ($size == $i + 1) {
                    $menu_type .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                } else {
                    $menu_type .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                    $menu_type .= ', ';
                }
                $i++;
            }
        }

        $menu_tag = '';
        $terms = get_the_terms(get_the_ID(), 'menu_tag');
        if ($terms) {
            $i = 0;
            $size = sizeof($terms);
            foreach ($terms as $term) {
                $term_link = get_category_link($term);
                if (is_wp_error($term_link)) {
                    continue;
                }
                if ($size == $i + 1) {
                    $menu_tag .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                } else {
                    $menu_tag .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                    $menu_tag .= ', ';
                }
                $i++;
            }
        }

        ?>
        <div class="entry-meta">
            <div class="single-post-meta">
                <?php if ($menu_type != '') { ?>
                    <span class="cutlery"><i class="fa fa-cutlery"></i><?php echo esc_attr($menu_type); ?></span>
                <?php } if ($menu_tag != '') { ?>
                    <span class="spoon"><i class="fa fa-spoon"></i><?php echo esc_attr($menu_tag); ?></span>
                <?php } ?>
                <span class="comments-o"><i class="fa fa-comments-o"></i><?php esc_html_e(' Comments: ', 'mine'); ?> <a href="<?php comments_link(); ?>"><?php echo esc_html(get_comments_number()); ?> </a></span>                    
            </div>
        </div>
        <?php
    }

endif;

if (!function_exists('mine_portfolio_social_share')) :

    function mine_portfolio_social_share() {
        $single_social_share_label = 'Share';
        $target = 'target=_blank';
        $share_link = get_permalink(get_the_ID());
        ?>
        <div class="post_share sol_shareicondiv <?php echo (is_single()) ? 'single_portfolio_share' : ''; ?>">
            <?php echo (is_single() && $single_social_share_label != '') ? '<span class="social_lbl">' . esc_attr($single_social_share_label) . ':</span>' : ''; ?>
            <a title="facebook" class="sol_shareicon facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($share_link); ?>" <?php echo esc_attr($target); ?>><i class="fa fa-facebook"></i></a>
            <a title="twitter" class="sol_shareicon twitter" href="https://twitter.com/share?url=<?php echo esc_attr($share_link); ?>" <?php echo esc_attr($target); ?>><i class="fa fa-twitter"></i></a>
            <a title="google-plus" class="sol_shareicon google-plus" href="https://plus.google.com/share?url=<?php echo esc_attr($share_link); ?>" <?php echo esc_attr($target); ?>><i class="fa fa-google-plus"></i></a>
            <a title="pinterest" class="sol_shareicon pinterest" href="https://pinterest.com/pin/create/button/?url=<?php echo esc_attr($share_link); ?>" <?php echo esc_attr($target); ?>><i class="fa fa-pinterest"></i></a>
            <a title="linkedin" class="sol_shareicon linkedin" href="http://www.linkedin.com/shareArticle?url=<?php echo esc_attr($share_link); ?>" <?php echo esc_attr($target); ?>><i class="fa fa-linkedin"></i></a>
            <a title="digg" class="sol_shareicon digg" href="http://digg.com/submit?url=<?php echo esc_attr($share_link); ?>" <?php echo esc_attr($target); ?>><i class="fa fa-digg"></i></a>
        </div>
        <?php
    }

endif;

if (!function_exists('mine_entry_footer')) :

    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function mine_entry_footer() {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(', ');
            if ($categories_list && mine_categorized_blog()) {
                printf('<span class="cat-links">' . esc_html__('Posted in', 'mine') . ' ' . $categories_list . '</span>'); // WPCS: XSS OK.
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', ', ');
            if ($tags_list) {
                printf('<span class="tags-links">' . esc_html__('Tagged', 'mine') . ' ' . $tags_list . '</span>'); // WPCS: XSS OK.
            }
        }

        if (!is_single() && !post_password_required() && ( comments_open() || get_comments_number() )) {
            echo '<span class="comments-link">';
            /* translators: %s: post title */
            comments_popup_link(sprintf(wp_kses(__('Leave a Comment', 'mine') . '<span class="screen-reader-text"> ' . __('on', 'mine') . ' ' . get_the_title() . '</span>', array('span' => array('class' => array())))));
            echo '</span>';
        }

        edit_post_link(
                sprintf(
                        /* translators: %s: Name of current post */
                        esc_html__('Edit', 'mine') . ' ' . the_title('<span class="screen-reader-text">"', '"</span>', false)
                ), '<span class="edit-link">', '</span>'
        );
    }








endif;