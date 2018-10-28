<?php
/**
 * Display Recent posts
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

add_action('widgets_init', 'mine_blogpost_widget_init');

if (!function_exists('mine_blogpost_widget_init')) {

    /**
     * Register Widget
     */
    function mine_blogpost_widget_init() {
        register_widget('mine_blogpost_widget');
    }

}

class mine_blogpost_widget extends WP_Widget {

    public function __construct() {

        $widget_ops = array(
            'classname' => 'widget_blogpost',
            'description' => __("This widget for display Blog Posts of category wise and order by date, title etc.", 'mine'),
            'customize_selective_refresh' => true
        );
        parent::__construct('blog_post', 'S &rarr; ' . __('Blog Posts', 'mine'), $widget_ops);
        $this->alt_option_name = 'widget_blogpost';

        add_action('save_post', array($this, 'flush_widget_blogpost'));
        add_action('deleted_post', array($this, 'flush_widget_blogpost'));
        add_action('switch_theme', array($this, 'flush_widget_blogpost'));
    }

    /**
     * @since 1.0
     * @param array $args arguments from sidebar
     * @param array $instance instance of widget
     * @return return html for front end display
     */
    public function widget($args, $instance) {

        // $title = !empty($instance['title']) ? $instance['title'] : '';
        $numberofpost = isset($instance['numberofpost']) ? absint($instance['numberofpost']) : '-1';
        // $display_date = isset($instance['display_date']) ? (bool) $instance['display_date'] : false;

        $before_widget = $args['before_widget'];
        $before_title = $args['before_title'];
        $after_title = $args['after_title'];
        $after_widget = $args['after_widget'];

        
        $mt_rand = mt_rand();
        /**
         * Filter the arguments for the Blog post widget
         * @see WP_Query::get_posts()
         * @param array $args An array of arguments used to retrieve the Clients Testimonial.
         */
        $argc = array(
            'post_type' => 'post',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => $numberofpost,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true
        );
        $the_query = new WP_Query($argc);
        $published_posts = wp_count_posts('post')->publish;

        if ($the_query->have_posts()) :
            
            print_r($before_widget);

            if ($title != '') {
                print_r($before_title);
                print_r($title);
                print_r($after_title);
            }
            ?>
            
            <div class="recent-post-cover text-left">
                <?php 
                while ($the_query->have_posts()) : $the_query->the_post(); ?>
                    <div class="blog-post-wrap">
                        <!-- for display post image -->
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <?php 
                                $blog_default_image = get_theme_mod('mine_blog_default_image');
                                if (has_post_thumbnail()) { 
                                    the_post_thumbnail('mine-latest-blog-thumbnail');
                                } elseif(isset($blog_default_image) && $blog_default_image != ""){
                                    echo "<img src='".esc_html($blog_default_image)."' alt='". get_the_title()."'>";
                                }
                                ?>
                            </a>
                        </div>
                        <div class="post-content">
                            <!-- for display post title -->
                            <?php 
                            if (get_the_title()) { ?>
                                <header class="entry-header">
                                    <?php the_title(sprintf('<h4 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h4>'); ?>
                                </header><?php
                            }
                            if ($display_date) { ?>
                                <!-- for display post meta -->
                                <footer class="entry-footer">
                                    <div class="post-meta blogpost-meta">
                                        <?php
                                        $archive_year = esc_html(get_the_time('Y'));
                                        $archive_month = esc_html(get_the_time('m'));
                                        $archive_day = esc_html(get_the_time('d'));
                                        printf('<span class="entry-date"><i class="fa fa-clock-o"></i> <a href="%1$s" rel="bookmark"><time datetime="%2$s">%3$s</time></a></span> ', esc_url(get_day_link($archive_year, $archive_month, $archive_day)), esc_html(get_the_date('c')), esc_html(get_the_date()));
                                        ?>
                                    </div>
                                </footer><?php
                            } ?>
                        </div>
                    </div><?php 
                endwhile; ?>
            </div>
            <?php 
            wp_reset_postdata();
            print_r($after_widget);
        endif;
    }

    /**
     * @since 1.0
     * @param array $instance instance of widget
     * @return html return html for admin side display
     */
    public function form($instance) {
        $id = rand();
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $numberofpost = isset($instance['numberofpost']) ? absint($instance['numberofpost']) : '5';
        $display_date = isset($instance['display_date']) ? (bool) $instance['display_date'] : true;
        ?>
        <div>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title', 'mine'); ?>:</label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('numberofpost')); ?>"><?php esc_html_e('Number of posts to show', 'mine'); ?>:</label>
                <input class="tiny-text numbersOnly" id="<?php echo esc_attr($this->get_field_id('numberofpost')); ?>" name="<?php echo esc_attr($this->get_field_name('numberofpost')); ?>" type="number" step="1" min="1" value="<?php echo esc_html($numberofpost); ?>" size="3" />
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($display_date); ?> id="<?php echo esc_attr($this->get_field_id('display_date')); ?>" name="<?php echo esc_attr($this->get_field_name('display_date')); ?>" />
                <label for="<?php echo esc_attr($this->get_field_id('display_date')); ?>"><?php esc_html_e('Display post date?', 'mine'); ?></label>
            </p>
        </div>
        <?php
    }

    /**
     * @since 1.0
     * @param array $new_instance updated array
     * @param array $old_instance old array
     * @return array $instance instance with new value
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['numberofpost'] = (int) $new_instance['numberofpost'];
        $instance['display_date'] = isset($new_instance['display_date']) ? (bool) $new_instance['display_date'] : false;
        

        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['widget_blogpost']))
            delete_option('widget_blogpost');

        return $instance;
    }

    public function flush_widget_blogpost() {
        wp_cache_delete('widget_blogpost', 'widget');
    }

}
