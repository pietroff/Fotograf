<?php
/**
 * The template part for displaying an Author biography
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */

$mine_disable_author_bio = get_theme_mod('mine_display_bio', '0');

if($mine_disable_author_bio != '1') { ?>
    <div class="author-info">
        <div class="author-description">
            <h2 class="entry-title"><?php esc_html_e('About The Author','mine'); ?></h2>
            <div class="author-avatar">
                <?php
                /**
                 * Filter the Mine Theme author bio avatar size.
                 *
                 * @since 1.0
                 *
                 * @param int $size The avatar height and width size in pixels.
                 */
                $author_bio_avatar_size = apply_filters('mine_author_bio_avatar_size', 120);

                echo get_avatar(get_the_author_meta('user_email'), $author_bio_avatar_size, $default = '', $alt = get_the_author_meta('display_name'));
                ?>
            </div><!-- .author-avatar -->
            <div class="author-cover">
                <h4 class="author-title text-capitalize"><?php echo get_the_author(); ?></h4>
                <p class="author-bio">
                    <?php 
                    the_author_meta('description');
                    if (!is_archive()) { ?>
                        <span class="author-link-wrap">
                            <?php esc_html_e('View all posts by', 'mine'); ?>
                            <a class="author-link text-capitalize" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author">
                                <?php echo get_the_author(); ?>
                            </a>
                        </span><?php
                    } ?>
                </p><!-- .author-bio -->
            </div>
        </div><!-- .author-description -->
    </div><!-- .author-info --><?php
}