<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (post_password_required()) {
    return;
}
?>
<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            the_comment();
            if (1 == $comments_number) {
                /* translators: %s: post title */
                echo esc_html_e('1 Review', 'mine');
            } else {
                print_r(number_format_i18n( $comments_number ));  echo ' ';
                echo esc_html_e('Reviews', 'mine');
            }
            ?>
        </h2>
        <ol class="comment-list">
            <?php
            $comment_type = get_comment_type();
            if($comment_type == 'pingback' || $comment_type == 'trackback'){
                wp_list_comments(array(
                    'style' => 'ol',
                    'short_ping' => true,
                    'avatar_size' => 75
                ));
            } else {
                wp_list_comments(array(
                    'style' => 'ol',
                    'short_ping' => true,
                    'avatar_size' => 75,
                    'callback' => 'mine_list_comments'
                ));
            }
            ?>
        </ol><!-- .comment-list -->
        <?php
        the_comments_navigation();
    endif; // Check for have_comments().
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'mine'); ?></p>
        <?php
    endif;
    comment_form(array(
        'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after' => '</h2>',
    ));
    ?>
</div><!-- .comments-area -->