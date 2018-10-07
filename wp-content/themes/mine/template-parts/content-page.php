<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <!-- for display post image -->
    <?php if (!post_password_required() && has_post_thumbnail()) { ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_post_thumbnail('mine-blog-thumbnail'); ?>
            </a>
        </div>
    <?php } ?>

    <div class="entry-content">
        <?php the_content(); ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
        edit_post_link(
                sprintf(
                        __('Edit', 'mine') . ' <span class="screen-reader-text"> "' . get_the_title() . '"</span>'
                ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->'
        );
        ?>
    </footer>

</article><!-- #post-## -->
