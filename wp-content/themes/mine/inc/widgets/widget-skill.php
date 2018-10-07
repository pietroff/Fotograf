<?php
/**
 * Display Skill
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

add_action('widgets_init', 'mine_skill_widget_init');

if (!function_exists('mine_skill_widget_init')) {

    /**
     * Register Widget
     */
    function mine_skill_widget_init() {
        register_widget('mine_skill_widget');
    }

}

class mine_skill_widget extends WP_Widget {

    public function __construct() {

        $widget_ops = array(
            'classname' => 'widget_skill',
            'description' => __("This widget for display skills section.", 'mine'),
            'customize_selective_refresh' => true
        );
        parent::__construct('widget_skill', 'S &rarr; ' . __('Skills Section', 'mine'), $widget_ops);
        $this->alt_option_name = 'widget_skill';

        add_action('save_post', array($this, 'flush_widget_skill'));
        add_action('deleted_post', array($this, 'flush_widget_skill'));
        add_action('switch_theme', array($this, 'flush_widget_skill'));
    }

    /**
     * @since 1.0
     * @param array $args arguments from sidebar
     * @param array $instance instance of widget
     * @return return html for front end display
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        
        $skill_title1 = isset($instance['skill_title']) ? $instance['skill_title'] : '';
        $skill_percentage1 = isset($instance['skill_percentage']) ? $instance['skill_percentage'] : '';
        $skill_bar_bg1 = (isset($instance['skill_bar_bg']) && $instance['skill_bar_bg'] != '') ? $instance['skill_bar_bg'] : '#666666';
        $skill_title_bg1 = (isset($instance['skill_title_bg']) && $instance['skill_title_bg'] != '') ? $instance['skill_title_bg'] : '#333333';
        ?>
        <div class="author_skill_section text-center">
            <?php
            if ($title != '') {
                echo '<h2 class="entry-title">' . esc_html($title) . '</h2>';
            }

            if ($description != '') {
                echo '<span class="skill_description">' . esc_html($description) . '</span>';
            }

            $stream_sources = isset($instance['stream_sources']) ? $instance['stream_sources'] : array();
            $stream_num = count($stream_sources);
            $stream_sources[$stream_num + 1] = '';
            $stream_sources_html = array();
            $stream_counter = 1;
            echo '<div class="skill-contents row">';
            
            for($i = 0; $i < count($skill_title1); $i++) {
                if (isset($skill_title1[$i])) {
                    
                    $title_bg = (isset($skill_title_bg1[$i]) && $skill_title_bg1[$i] != '') ? $skill_title_bg1[$i] : '#333333';
                    $bar_bg = (isset($skill_bar_bg1[$i]) && $skill_bar_bg1[$i] != '') ? $skill_bar_bg1[$i] : '#666666';
                    
                    if(($skill_percentage1[$i] >= 30) && ($skill_percentage1[$i] <= 100)){
                        ?>
                        <div class="col-xs-12 col-sm-6 col-md-6 author_skill">
                            <div class="progressbar">
                                <div class="progress_title" style="background-color:<?php echo esc_html($title_bg); ?>">
                                    <span><?php echo esc_html($skill_title1[$i]); ?></span>
                                </div>
                                <div class="progress_inner" data-width="<?php echo esc_html($skill_percentage1[$i]) . '%'; ?>" style="width:<?php echo esc_html($skill_percentage1[$i]) . '%'; ?>;background-color:<?php echo esc_html($bar_bg); ?>"> </div>
                                <div class="progress_percentage">
                                    <span><?php echo esc_html($skill_percentage1[$i]); ?> % </span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            echo '</div>';
            ?>
        </div>
        
            <?php
        }

    /**
     * @since 1.0
     * @param array $instance instance of widget
     * @return html return html for admin side display
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';

        $skill_title1 = isset($instance['skill_title']) ? $instance['skill_title'] : '';
        $skill_percentage1 = isset($instance['skill_percentage']) ? $instance['skill_percentage'] : '';
        $skill_bar_bg1 = isset($instance['skill_bar_bg']) ? $instance['skill_bar_bg'] : '';
        $skill_title_bg1 = isset($instance['skill_title_bg']) ? $instance['skill_title_bg'] : '';
        
        $id = rand();
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title', 'mine'); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php esc_html_e('Description', 'mine'); ?>:</label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>"><?php echo esc_html($description); ?></textarea>
        </p>
        
        <div id="skill_widget_<?php echo esc_attr($id); ?>" class="skillGroup">
            <script type="text/javascript">
            (function ($) {
                var parent = $('body');
                if ($('body').hasClass('widgets-php')) {
                    parent = $('.widget-liquid-right');
                }
                $(document).ready(function ($) {
                    parent.find('.skill_color_picker').wpColorPicker();
                });
                $(document).on('widget-updated', function (e, widget) {
                    widget.find('.skill_color_picker').wpColorPicker();
                });
            })(jQuery);

            jQuery('#widgets-right .add_skills').live('click', function () {
                var count_hidden_num = jQuery('#widgets-right #skill_widget_<?php echo esc_html($id); ?>.skillGroup .skill-cover').length;
                alert(count_hidden_num);
                count_hidden_num = count_hidden_num + 1;
                var ap = jQuery('#skill_widget_<?php echo esc_html($id); ?>.skillGroup');
                jQuery(ap).append("\
                    <div class='skill-cover'>\n\
                        <p>\n\
                        <label> <?php echo esc_html__('Skill Title', 'mine'); ?> " + count_hidden_num + ":</label>\n\
                        <input type='text' name='<?php echo esc_attr($this->get_field_name('skill_title')); ?>[]' value='' class='widefat'>\n\
                        </p>\n\
                        <p>\n\
                        <label> <?php echo esc_html__('Skill Percentage', 'mine'); ?> " + count_hidden_num + ":</label>\n\
                        <input type='number' name='<?php echo esc_attr($this->get_field_name('skill_percentage')); ?>[]' value='' min='30' max='100' class='widefat digits_only'  />\n\
                        </p>\n\
                        <p>\n\
                        <label> <?php echo esc_html__('Skill Bar Background', 'mine'); ?> " + count_hidden_num + ":</label>\n\
                        <input type='text' name='<?php echo esc_attr($this->get_field_name('skill_bar_bg')); ?>[]' value='' class='widefat skill_color_picker'>\n\
                        </p>\n\
                        <p>\n\
                        <label> <?php echo esc_html__('Skill Title Background', 'mine'); ?> " + count_hidden_num + ":</label>\n\
                        <input type='text' name='<?php echo esc_attr($this->get_field_name('skill_title_bg')); ?>[]' value='' class='widefat skill_color_picker'>\n\
                        </p>\n\
                        <span class='remove-skill button button-secondary button-large'> <?php esc_html_e('Remove','mine'); ?></span><br/><br/>\n\
                    </div>");

                jQuery('.skill_color_picker').wpColorPicker();
                               
                jQuery("#skill_widget_<?php echo esc_html($id); ?> .digits_only").keydown(function (event) {
                    if( !(event.keyCode == 8                                // backspace
                        || event.keyCode == 9                               // tab
                        || event.keyCode == 46                              // delete
                        || (event.keyCode >= 35 && event.keyCode <= 40)     // arrow keys/home/end
                        || (event.keyCode >= 48 && event.keyCode <= 57)     // numbers on keyboard
                        || (event.keyCode >= 96 && event.keyCode <= 105))   // number on keypad
                    ) {
                        event.preventDefault();     // Prevent character input
                        alert("Only numbers are allowed.");
                    }
                });
            });
             
            jQuery("#skill_widget_<?php echo esc_html($id); ?> .digits_only").on('blur',function(){
                var val = jQuery(this).val();
                if(val < 30 || val > 100){
                    alert("Percentage should be in between 30 to 100.");    
                }
            });
            jQuery(".remove-skill").live('click', function () {
                jQuery(this).parent().remove();
            });
        </script>
            <b><?php esc_html_e('Skills','mine'); ?>:</b> <br>
            <p>
                <b><?php esc_html_e('Note','mine'); ?></b> : 
                <?php esc_html_e('Percentage should be in between 30 to 100.','mine'); ?>
            </p>
            <?php
            $skill__title1 = (isset($skill_title1[0])) ? esc_attr($skill_title1[0]) : $skill_title1;
            $skill__percentage1 = isset($skill_percentage1[0]) ? esc_attr($skill_percentage1[0]) : $skill_percentage1;
            $skill__bar_bg1 = isset($skill_bar_bg1[0]) ? esc_attr($skill_bar_bg1[0]) : $skill_bar_bg1;
            $skill__title_bg1 = isset($skill_title_bg1[0]) ? esc_attr($skill_title_bg1[0]) : $skill_title_bg1;
            ?>
            <div class="skill-cover">
                <p>
                    <label><?php esc_html_e('Skill Title','mine'); ?> 1 :</label>
                    <input type='text' name='<?php echo esc_attr($this->get_field_name('skill_title'))."[]"; ?>' value='<?php print_r($skill__title1) ; ?>' class='widefat'>
                </p>
                <p>
                    <label><?php esc_html_e('Skill Percentage','mine'); ?> 1 :</label>
                    <input type='number' min="30" max="100" name='<?php echo esc_attr($this->get_field_name('skill_percentage'))."[]"; ?>' value='<?php print_r($skill__percentage1); ?>' class='widefat digits_only'  />
                </p>
                <p>
                    <label><?php esc_html_e('Skill Bar Background','mine'); ?> 1 :</label>
                    <input type='text' name='<?php echo esc_attr($this->get_field_name('skill_bar_bg'))."[]"; ?>' value='<?php print_r($skill__bar_bg1);  ?>' class='widefat skill_color_picker'>
                </p>
                <p>
                    <label><?php esc_html_e('Skill Title Background','mine'); ?> 1 :</label>
                    <input type='text' name='<?php echo esc_attr($this->get_field_name('skill_title_bg'))."[]"; ?>' value='<?php print_r($skill__title_bg1); ?>' class='widefat skill_color_picker'>
                </p>
            </div>
            <?php
            for ($i = 1; $i < count($skill_title1); $i++) {
                if ($skill_title1[$i] != "") {
                    $j = $i + 1;
                    echo '<div class="skill-cover">';
                    echo '<p>';
                    echo '<label>' . esc_html__('Skill Title', 'mine') . ' ' . esc_html($j);
                    echo ': <input type="text" class="widefat"  name="' . esc_attr($this->get_field_name('skill_title')) . '[]" value="' . esc_attr($skill_title1[$i]) . '">';
                    echo '</label>';
                    echo '</p>';
                    echo '<p>';
                    echo '<label>' . esc_html__('Skill Percentage', 'mine') . ' ' . esc_html($j);
                    echo ': <input type="number" class="widefat digits_only"  name="' . esc_attr($this->get_field_name('skill_percentage')) . '[]" value="' . esc_attr($skill_percentage1[$i]) . '">';
                    echo '</label>';
                    echo '</p>';
                    echo '';
                    echo '<p>' . esc_html__('Skill Bar Background', 'mine') . ' ' . esc_html($j);
                    echo ': <input type="text" class="widefat skill_color_picker"  name="' . esc_attr($this->get_field_name('skill_bar_bg')) . '[]" value="' . esc_attr($skill_bar_bg1[$i]) . '">';
                    echo '';
                    echo '</p>';
                    echo '<p>';
                    echo '' . esc_html__('Skill Title Background', 'mine') . ' ' . esc_html($j);
                    echo ': <input type="text" class="widefat skill_color_picker"  name="' . esc_attr($this->get_field_name('skill_title_bg')) . '[]" value="' . esc_attr($skill_title_bg1[$i]) . '">';
                    echo '';
                    echo '</p>';
                    ?>
                    <span class='remove-skill button button-secondary button-large'><?php esc_html_e('Remove', 'mine'); ?></span>
                    <?php
                    echo '</div>';
                }
            }
            ?>
        </div>
        <input class="button <?php echo esc_attr($this->get_field_id('add_skills')); ?> button-primary button-large add_skills" type="button" value="<?php echo esc_html__('Add new', 'mine'); ?>" /><br/><br/><?php
    }

    /**
     * @since 1.0
     * @param array $new_instance updated array
     * @param array $old_instance old array
     * @return array $instance instance with new value
     */
    public function update($new_instance, $old_instance) {
        return $new_instance;
    }

    public function flush_widget_skill() {
        wp_cache_delete('widget_skill', 'widget');
    }

}