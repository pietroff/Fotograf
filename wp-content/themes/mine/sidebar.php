<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div id="secondary" class="sidebar widget-area col-sm-4 col-xs-12">
    <?php dynamic_sidebar('primary-sidebar'); ?>
</div><!-- #secondary -->