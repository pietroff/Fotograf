<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<section class="no-results not-found text-center">
    <header class="entry-header">
        <h2 class="entry-title"><?php esc_html_e('Nothing Found', 'mine'); ?></h2>
    </header>
    <div class="entry-content">
        <?php if (is_home() && current_user_can('publish_posts')) : ?>

            <p><?php printf(esc_html_e('Ready to publish your first post?', 'mine') . ' <a href="' . esc_url(admin_url('post-new.php')) . '">Get started here</a>.'); ?></p>

        <?php elseif (is_search()) : ?>

            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'mine'); ?></p>
            <?php get_search_form(); ?>

        <?php else : ?>

            <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'mine'); ?></p>
            <?php get_search_form(); ?>

        <?php endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->
