<?php
/**
 * Mine Theme Customizer functionality
 *
 * @package WordPress
 * @subpackage Mine
 * @since 1.0
 */
/**
 * Sets up the WordPress core custom header and custom background features.
 *
 * @since 1.0
 *
 * @see mine_header_style()
 */
if (!function_exists('mine_custom_header_and_background')) {

    function mine_custom_header_and_background() {
        $color_scheme = mine_get_color_scheme();
        $default_background_color = trim($color_scheme[0], '#');
        $default_text_color = trim($color_scheme[4], '#');

        /**
         * Filter the arguments used when adding 'custom-background' support in Mine Theme.
         *
         * @since 1.0
         *
         * @param array $args {
         *     An array of custom-background support arguments.
         *
         *     @type string $default-color Default color of the background.
         * }
         */

        /**
         * Filter the arguments used when adding 'custom-header' support in Mine Theme.
         *
         * @since 1.0
         *
         * @param array $args {
         *     An array of custom-header support arguments.
         *
         *     @type string $default-text-color Default color of the header text.
         *     @type int      $width            Width in pixels of the custom header image. Default 1200.
         *     @type int      $height           Height in pixels of the custom header image. Default 280.
         *     @type bool     $flex-height      Whether to allow flexible-height header images. Default true.
         *     @type callable $wp-head-callback Callback function used to style the header image and text
         *                                      displayed on the blog.
         * }
         */
        add_theme_support('custom-header', apply_filters('mine_custom_header_args', array(
            'default-text-color'    => '#000000',
            'default-image'         => get_template_directory_uri() . '/images/header-bg.jpg',
            'width'                 => 2000,
            'height'                => 780,
            'flex-height'           => true,
            'wp-head-callback'      => 'mine_header_style',
        )));
        
        register_default_headers( array(
            'header_bg' => array(
                    'url'           => get_template_directory_uri() . '/images/header-bg.jpg',
                    'thumbnail_url' => get_template_directory_uri() . '/images/header-bg.jpg',
                    'description'   => __( 'Default Header Image', 'mine' )
            )
        ) );
    }

}

add_action('after_setup_theme', 'mine_custom_header_and_background');

if (!function_exists('mine_header_style')) :

    /**
     * Styles the header text displayed on the site.
     *
     * Create your own mine_header_style() function to override in a child theme.
     *
     * @since 1.0
     *
     * @see mine_custom_header_and_background().
     */
    function mine_header_style() {

        // If the header text option is untouched, let's bail.
        if (display_header_text()) {
            return;
        }

        // If the header text has been hidden.
        ?>
        <style type="text/css" id="mine-header-css">
            .site-branding {
                margin: 0 auto 0 0;
            }
            .site-branding .site-title,
            .site-description {
                clip: rect(1px, 1px, 1px, 1px);
                position: absolute;
            }
        </style>
        <?php
    }

endif; // mine_header_style

/**
 * Adds postMessage support for site title and description for the Customizer.
 *
 * @since 1.0
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
if (!function_exists('mine_customize_register')) {

    function mine_customize_register($wp_customize) {
        $color_scheme = mine_get_color_scheme();

        $wp_customize->get_setting('blogname')->transport = 'refresh';
        $wp_customize->get_setting('blogdescription')->transport = 'refresh';

        if (isset($wp_customize->selective_refresh)) {
            $wp_customize->selective_refresh->add_partial('blogname', array(
                'selector' => '.site-title a',
                'container_inclusive' => false,
                'render_callback' => 'mine_customize_partial_blogname',
            ));
            $wp_customize->selective_refresh->add_partial('blogdescription', array(
                'selector' => '.site-description',
                'container_inclusive' => false,
                'render_callback' => 'mine_customize_partial_blogdescription',
            ));
        }

        // Add color scheme setting and control.
        $wp_customize->add_setting('color_scheme', array(
            'default' => 'default',
            'sanitize_callback' => 'mine_sanitize_color_scheme',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control('color_scheme', array(
            'label' => __('Base Color Scheme', 'mine'),
            'section' => 'colors',
            'type' => 'select',
            'choices' => mine_get_color_scheme_choices(),
            'priority' => 1,
        ));
        
	// Add Primary color setting and control.
	$wp_customize->add_setting( 'primary_color', array(
		'default'           => $color_scheme[1],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
		'label'       => __( 'Primary Color', 'mine' ),
		'section'     => 'colors',
	) ) );

	// Add Secondary color setting and control.
	$wp_customize->add_setting( 'secondary_color', array(
		'default'           => $color_scheme[2],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
		'label'       => __( 'Secondary Color', 'mine' ),
		'section'     => 'colors',
	) ) );

	// Add content text color setting and control.
	$wp_customize->add_setting( 'content_color', array(
		'default'           => $color_scheme[3],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_color', array(
		'label'       => __( 'Content Color', 'mine' ),
		'section'     => 'colors',
	) ) );
                
        // Add meta color setting and control.
	$wp_customize->add_setting( 'meta_color', array(
		'default'           => $color_scheme[4],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'meta_color', array(
		'label'       => __( 'Meta Color', 'mine' ),
		'section'     => 'colors',
	) ) );
        
        $wp_customize->remove_section("background_image");
        
    }

}

add_action('customize_register', 'mine_customize_register', 11);

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.0
 * @see mine_customize_register()
 *
 * @return void
 */
if (!function_exists('mine_customize_partial_blogname')) {

    function mine_customize_partial_blogname() {
        bloginfo('name');
    }

}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since 1.0
 * @see mine_customize_register()
 *
 * @return void
 */
if (!function_exists('mine_customize_partial_blogdescription')) {

    function mine_customize_partial_blogdescription() {
        bloginfo('description');
    }

}

/**
 * Registers color schemes for Mine Theme.
 *
 * Can be filtered with {@see 'mine_color_schemes'}.
 *
 * The order of colors in a colors array:
 * 1. Main Background Color.
 * 2. Page Background Color.
 * 3. Primary Color.
 * 4. Content Color.
 * 5. Secondary Color.
 *
 * @since 1.0
 *
 * @return array An associative array of color scheme options.
 */
if (!function_exists('mine_get_color_schemes')) {

    function mine_get_color_schemes() {
        /**
         * Filter the color schemes registered for use with Mine Theme.
         *
         * The default schemes include 'default', 'blue', 'mandalay', 'bronzetone', and 'claret'.
         *
         * @since 1.0
         *
         * @param array $schemes {
         *     Associative array of color schemes data.
         *
         *     @type array $slug {
         *         Associative array of information for setting up the color scheme.
         *
         *         @type string $label  Color scheme label.
         *         @type array  $colors HEX codes for default colors prepended with a hash symbol ('#').
         *                              Colors are defined in the following order: Main background, page
         *                              background, link, main text, secondary text.
         *     }
         * }
         */
        return apply_filters('mine_color_schemes', array(
            'default' => array(
                'label' => __('Default', 'mine'),
                'colors' => array(
                    '#000000',
                    '#000000',
                    '#4d4d4d',
                    '#363636',
                    '#989898',
                    '#ffffff'
                ),
            ),
            'blue' => array(
                'label' => __('Blue', 'mine'),
                'colors' => array(
                    '#14375f',
                    '#14375f',
                    '#435f7f',
                    '#555555',
                    '#999999',
                    '#ffffff'
                ),
            ),
            'mandalay' => array(
                'label' => __('Mandalay', 'mine'),
                'colors' => array(
                    '#b1732a',
                    '#b1732a',
                    '#c18f55',
                    '#555555',
                    '#999999',
                    '#ffffff'
                ),
            ),
            'bronzetone' => array(
                'label' => __('Bronzetone', 'mine'),
                'colors' => array(
                    '#414c22',
                    '#414c22',
                    '#67704e',
                    '#555555',
                    '#999999',
                    '#ffffff'
                ),
            ),
            'claret' => array(
                'label' => __('Claret', 'mine'),
                'colors' => array(
                    '#781335',
                    '#781335',
                    '#93425d',
                    '#555555',
                    '#999999',
                    '#ffffff'
                ),
            ),
        ));
    }

}

if (!function_exists('mine_get_color_scheme')) :

    /**
     * Retrieves the current Mine Theme color scheme.
     *
     * Create your own mine_get_color_scheme() function to override in a child theme.
     *
     * @since 1.0
     *
     * @return array An associative array of either the current or default color scheme HEX values.
     */
    function mine_get_color_scheme() {
        $color_scheme_option = get_theme_mod('color_scheme', 'default');
        $color_schemes = mine_get_color_schemes();

        if (array_key_exists($color_scheme_option, $color_schemes)) {
            return $color_schemes[$color_scheme_option]['colors'];
        }

        return $color_schemes['default']['colors'];
    }

endif; // mine_get_color_scheme

if (!function_exists('mine_get_color_scheme_choices')) :

    /**
     * Retrieves an array of color scheme choices registered for Mine Theme.
     *
     * Create your own mine_get_color_scheme_choices() function to override
     * in a child theme.
     *
     * @since 1.0
     *
     * @return array Array of color schemes.
     */
    function mine_get_color_scheme_choices() {
        $color_schemes = mine_get_color_schemes();
        $color_scheme_control_options = array();

        foreach ($color_schemes as $color_scheme => $value) {
            $color_scheme_control_options[$color_scheme] = $value['label'];
        }

        return $color_scheme_control_options;
    }

endif; // mine_get_color_scheme_choices


if (!function_exists('mine_sanitize_color_scheme')) :

    /**
     * Handles sanitization for Mine Theme color schemes.
     *
     * Create your own mine_sanitize_color_scheme() function to override
     * in a child theme.
     *
     * @since 1.0
     *
     * @param string $value Color scheme name value.
     * @return string Color scheme name.
     */
    function mine_sanitize_color_scheme($value) {
        $color_schemes = mine_get_color_scheme_choices();

        if (!array_key_exists($value, $color_schemes)) {
            return 'default';
        }

        return $value;
    }

endif; // mine_sanitize_color_scheme

/**
 * Enqueues front-end CSS for color scheme.
 *
 * @since 1.0
 *
 * @see wp_add_inline_style()
 */
if (!function_exists('mine_color_scheme_css')) {

    function mine_color_scheme_css() {
        $color_scheme_option = get_theme_mod('color_scheme', 'default');

        // Don't do anything if the default color scheme is selected.
        if ('default' === $color_scheme_option) {
            return;
        }

        $color_scheme = mine_get_color_scheme();

        // Convert main text hex color to rgba.
        $color_textcolor_rgb = mine_hex2rgb($color_scheme[3]);

        // If the rgba values are empty return early.
        if (empty($color_textcolor_rgb)) {
            return;
        }

        // If we get this far, we have a custom color scheme.
        $colors = array(
            'primary_color' => $color_scheme[1],
            'secondary_color' => $color_scheme[2],
            'content_color' => $color_scheme[3],
            'meta_color' => $color_scheme[4],
            'border_color' => vsprintf('rgba( %1$s, %2$s, %3$s, 0.2)', $color_textcolor_rgb),
        );

        $color_scheme_css = mine_get_color_scheme_css($colors);

        wp_add_inline_style('mine-style', $color_scheme_css);
    }

}

add_action('wp_enqueue_scripts', 'mine_color_scheme_css');

/**
 * Binds the JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 *
 * @since 1.0
 */
if (!function_exists('mine_customize_control_js')) {

    function mine_customize_control_js() {
        wp_enqueue_script('color-scheme-control', get_template_directory_uri() . '/js/color-scheme-control.js', array('customize-controls', 'iris', 'underscore', 'wp-util'), '20160412', true);
        wp_localize_script('color-scheme-control', 'colorScheme', mine_get_color_schemes());
    }

}
add_action('customize_controls_enqueue_scripts', 'mine_customize_control_js');

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since 1.0
 */
if (!function_exists('mine_customize_preview_js')) {

    function mine_customize_preview_js() {
        wp_enqueue_script('mine-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array('customize-preview'), '20160412', true);
    }

}

add_action('customize_preview_init', 'mine_customize_preview_js');

/**
 * Returns CSS for the color schemes.
 *
 * @since 1.0
 *
 * @param array $colors Color scheme colors.
 * @return string Color scheme CSS.
 */
if (!function_exists('mine_get_color_scheme_css')) {

    function mine_get_color_scheme_css($colors) {
        $colors = wp_parse_args($colors, array(
            'primary_color' => '',
            'content_color' => '',
            'secondary_color' => '',
            'meta_color' => '',
            'border_color' => '',
        ));
        
        return <<<CSS
	/* Color Scheme */

	/* Primary Color */
	.menu-toggle:hover,
	.menu-toggle:focus,
	a,
	.main-navigation a:hover,
	.main-navigation a:focus,
	.dropdown-toggle:hover,
	.dropdown-toggle:focus,
	.social-navigation a:hover:before,
	.social-navigation a:focus:before,
	.post-navigation a:hover .post-title,
	.post-navigation a:focus .post-title,
	.tagcloud a:hover,
	.tagcloud a:focus,
	.site-branding .site-title a:hover,
	.site-branding .site-title a:focus,
	.entry-title a:hover,
	.entry-title a:focus,
	.entry-footer a:hover,
	.entry-footer a:focus,
	.comment-metadata a:hover,
	.comment-metadata a:focus,
	.pingback .comment-edit-link:hover,
	.pingback .comment-edit-link:focus,
	.comment-reply-link,
	.comment-reply-link:hover,
	.comment-reply-link:focus,
	.required,
	.site-info a:hover,
	.site-info a:focus {
		color: {$colors['primary_color']};
	}

	mark,
	ins,
	button:hover,
	button:focus,
	input[type="button"]:hover,
	input[type="button"]:focus,
	input[type="reset"]:hover,
	input[type="reset"]:focus,
	input[type="submit"]:hover,
	input[type="submit"]:focus,
	.pagination .prev:hover,
	.pagination .prev:focus,
	.pagination .next:hover,
	.pagination .next:focus,
	.widget_calendar tbody a,
	.page-links a:hover,
	.page-links a:focus {
		background-color: {$colors['primary_color']};
	}

	input[type="text"]:focus,
	input[type="email"]:focus,
	input[type="url"]:focus,
	input[type="password"]:focus,
	input[type="search"]:focus,
	textarea:focus,
	.tagcloud a:hover,
	.tagcloud a:focus,
	.menu-toggle:hover,
	.menu-toggle:focus {
		border-color: {$colors['primary_color']};
	}

	/* Content Color */
	body,
	blockquote cite,
	blockquote small,
	.main-navigation a,
	.menu-toggle,
	.dropdown-toggle,
	.social-navigation a,
	.post-navigation a,
	.pagination a:hover,
	.pagination a:focus,
	.widget-title a,
	.site-branding .site-title a,
	.entry-title a,
	.page-links > .page-links-title,
	.comment-author,
	.comment-reply-title small a:hover,
	.comment-reply-title small a:focus {
		color: {$colors['content_color']};
	}

	blockquote,
	.menu-toggle.toggled-on,
	.menu-toggle.toggled-on:hover,
	.menu-toggle.toggled-on:focus,
	.post-navigation,
	.post-navigation div + div,
	.pagination,
	.widget,
	.page-header,
	.page-links a,
	.comments-title,
	.comment-reply-title {
		border-color: {$colors['content_color']};
	}

	button,
	button[disabled]:hover,
	button[disabled]:focus,
	input[type="button"],
	input[type="button"][disabled]:hover,
	input[type="button"][disabled]:focus,
	input[type="reset"],
	input[type="reset"][disabled]:hover,
	input[type="reset"][disabled]:focus,
	input[type="submit"],
	input[type="submit"][disabled]:hover,
	input[type="submit"][disabled]:focus,
	.menu-toggle.toggled-on,
	.menu-toggle.toggled-on:hover,
	.menu-toggle.toggled-on:focus,
	.pagination:before,
	.pagination:after,
	.pagination .prev,
	.pagination .next,
	.page-links a {
		background-color: {$colors['content_color']};
	}
                
        /* Meta Color */

	body:not(.search-results) .entry-summary {
		color: {$colors['meta_color']};
	}
                
	/* Secondary Color */

	/**
	 * IE8 and earlier will drop any block with CSS3 selectors.
	 * Do not combine these styles with the next block.
	 */
	body:not(.search-results) .entry-summary {
		color: {$colors['secondary_color']};
	}

	blockquote,
	.post-password-form label,
	a:hover,
	a:focus,
	a:active,
	.post-navigation .meta-nav,
	.image-navigation,
	.comment-navigation,
	.widget_recent_entries .post-date,
	.widget_rss .rss-date,
	.widget_rss cite,
	.site-description,
	.author-bio,
	.entry-footer,
	.entry-footer a,
	.sticky-post,
	.taxonomy-description,
	.entry-caption,
	.comment-metadata,
	.pingback .edit-link,
	.comment-metadata a,
	.pingback .comment-edit-link,
	.comment-form label,
	.comment-notes,
	.comment-awaiting-moderation,
	.logged-in-as,
	.form-allowed-tags,
	.site-info,
	.site-info a,
	.wp-caption .wp-caption-text,
	.gallery-caption,
	.widecolumn label,
	.widecolumn .mu_register label {
		color: {$colors['secondary_color']};
	}

	.widget_calendar tbody a:hover,
	.widget_calendar tbody a:focus {
		background-color: {$colors['secondary_color']};
	}

	/* Border Color */
	fieldset,
	pre,
	abbr,
	acronym,
	table,
	th,
	td,
	input[type="text"],
	input[type="email"],
	input[type="url"],
	input[type="password"],
	input[type="search"],
	textarea,
	.main-navigation li,
	.main-navigation .primary-menu,
	.menu-toggle,
	.dropdown-toggle:after,
	.social-navigation a,
	.image-navigation,
	.comment-navigation,
	.tagcloud a,
	.entry-content,
	.entry-summary,
	.page-links a,
	.page-links > span,
	.comment-list article,
	.comment-list .pingback,
	.comment-list .trackback,
	.comment-reply-link,
	.no-comments,
	.widecolumn .mu_register .mu_alert {
		border-color: {$colors['content_color']}; /* Fallback for IE7 and IE8 */
		border-color: {$colors['border_color']};
	}

	hr,
	code {
		background-color: {$colors['content_color']}; /* Fallback for IE7 and IE8 */
		background-color: {$colors['border_color']};
	}

	@media screen and (min-width: 56.875em) {
		.main-navigation li:hover > a,
		.main-navigation li:focus > a {
			color: {$colors['primary_color']};
		}

		.main-navigation ul ul,
		.main-navigation ul ul li {
			border-color: {$colors['border_color']};
		}

		.main-navigation ul ul:before {
			border-top-color: {$colors['border_color']};
			border-bottom-color: {$colors['border_color']};
		}

	}
                        
CSS;
    }

}
add_action('wp_enqueue_scripts', 'mine_get_color_scheme_css', 150);

/**
 * Outputs an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the
 * Customizer preview.
 *
 * @since 1.0
 */
if (!function_exists('mine_color_scheme_css_template')) {

    function mine_color_scheme_css_template() {
        $colors = array(
            'primary_color' => '{{ data.primary_color }}',
            'content_color' => '{{ data.content_color }}',
            'secondary_color' => '{{ data.secondary_color }}',
            'meta_color' => '{{ data.meta_color }}',
            'border_color' => '{{ data.border_color }}',
        );
        ?>
        <script type="text/html" id="tmpl-mine-color-scheme">
            <?php echo esc_attr(mine_get_color_scheme_css($colors)); ?>
        </script>
        <?php
    }

}

add_action('customize_controls_print_footer_scripts', 'mine_color_scheme_css_template');

/**
 * Enqueues front-end CSS for the primary color.
 *
 * @since 1.0
 *
 * @see wp_add_inline_style()
 */
if (!function_exists('mine_primary_color_css')) {

    function mine_primary_color_css() {
        $color_scheme = mine_get_color_scheme();
        $default_color = $color_scheme[2];
        $primary_color = get_theme_mod('primary_color', $default_color);

        // Don't do anything if the current color is the default.
        if ($primary_color === $default_color) {
            return;
        }

        $css = '
		/* Custom primary Color */
		.menu-toggle:hover,
		.menu-toggle:focus,
		a,
		.main-navigation a:hover,
		.main-navigation a:focus,
		.dropdown-toggle:hover,
		.dropdown-toggle:focus,
		.social-navigation a:hover:before,
		.social-navigation a:focus:before,
		.post-navigation a:hover .post-title,
		.post-navigation a:focus .post-title,
		.tagcloud a:hover,
		.tagcloud a:focus,
		.site-branding .site-title a:hover,
		.site-branding .site-title a:focus,
		.entry-title a:hover,
		.entry-title a:focus,
		.entry-footer a:hover,
		.entry-footer a:focus,
		.comment-metadata a:hover,
		.comment-metadata a:focus,
		.pingback .comment-edit-link:hover,
		.pingback .comment-edit-link:focus,
		.comment-reply-link,
		.comment-reply-link:hover,
		.comment-reply-link:focus,
		.required,
		.site-info a:hover,
		.site-info a:focus {
			color: %1$s;
		}

		mark,
		ins,
		button:hover,
		button:focus,
		input[type="button"]:hover,
		input[type="button"]:focus,
		input[type="reset"]:hover,
		input[type="reset"]:focus,
		input[type="submit"]:hover,
		input[type="submit"]:focus,
		.pagination .prev:hover,
		.pagination .prev:focus,
		.pagination .next:hover,
		.pagination .next:focus,
		.widget_calendar tbody a,
		.page-links a:hover,
		.page-links a:focus {
			background-color: %1$s;
		}

		input[type="text"]:focus,
		input[type="email"]:focus,
		input[type="url"]:focus,
		input[type="password"]:focus,
		input[type="search"]:focus,
		textarea:focus,
		.tagcloud a:hover,
		.tagcloud a:focus,
		.menu-toggle:hover,
		.menu-toggle:focus {
			border-color: %1$s;
		}

		@media screen and (min-width: 56.875em) {
			.main-navigation li:hover > a,
			.main-navigation li:focus > a {
				color: %1$s;
			}
		}
	';

        wp_add_inline_style('mine-style', sprintf($css, $primary_color));
    }

}

add_action('wp_enqueue_scripts', 'mine_primary_color_css', 11);

/**
 * Enqueues front-end CSS for the Content color.
 *
 * @since 1.0
 *
 * @see wp_add_inline_style()
 */
if (!function_exists('mine_content_color_css')) {

    function mine_content_color_css() {
        $color_scheme = mine_get_color_scheme();
        $default_color = $color_scheme[4];
        $content_color = get_theme_mod('content_color', $default_color);

        // Don't do anything if the current color is the default.
        if ($content_color === $default_color) {
            return;
        }

        // Convert main text hex color to rgba.
        $content_color_rgb = mine_hex2rgb($content_color);

        // If the rgba values are empty return early.
        if (empty($content_color_rgb)) {
            return;
        }

        // If we get this far, we have a custom color scheme.
        $border_color = vsprintf('rgba( %1$s, %2$s, %3$s, 0.2)', $content_color_rgb);

        $css = '
		/* Custom Content Color */
		body,
		blockquote cite,
		blockquote small,
		.main-navigation a,
		.menu-toggle,
		.dropdown-toggle,
		.social-navigation a,
		.post-navigation a,
		.pagination a:hover,
		.pagination a:focus,
		.widget-title a,
		.site-branding .site-title a,
		.entry-title a,
		.page-links > .page-links-title,
		.comment-author,
		.comment-reply-title small a:hover,
		.comment-reply-title small a:focus {
			color: %1$s
		}

		blockquote,
		.menu-toggle.toggled-on,
		.menu-toggle.toggled-on:hover,
		.menu-toggle.toggled-on:focus,
		.post-navigation,
		.post-navigation div + div,
		.pagination,
		.widget,
		.page-header,
		.page-links a,
		.comments-title,
		.comment-reply-title {
			border-color: %1$s;
		}

		button,
		button[disabled]:hover,
		button[disabled]:focus,
		input[type="button"],
		input[type="button"][disabled]:hover,
		input[type="button"][disabled]:focus,
		input[type="reset"],
		input[type="reset"][disabled]:hover,
		input[type="reset"][disabled]:focus,
		input[type="submit"],
		input[type="submit"][disabled]:hover,
		input[type="submit"][disabled]:focus,
		.menu-toggle.toggled-on,
		.menu-toggle.toggled-on:hover,
		.menu-toggle.toggled-on:focus,
		.pagination:before,
		.pagination:after,
		.pagination .prev,
		.pagination .next,
		.page-links a {
			background-color: %1$s;
		}

		/* Border Color */
		fieldset,
		pre,
		abbr,
		acronym,
		table,
		th,
		td,
		input[type="text"],
		input[type="email"],
		input[type="url"],
		input[type="password"],
		input[type="search"],
		textarea,
		.main-navigation li,
		.main-navigation .primary-menu,
		.menu-toggle,
		.dropdown-toggle:after,
		.social-navigation a,
		.image-navigation,
		.comment-navigation,
		.tagcloud a,
		.entry-content,
		.entry-summary,
		.page-links a,
		.page-links > span,
		.comment-list article,
		.comment-list .pingback,
		.comment-list .trackback,
		.comment-reply-link,
		.no-comments,
		.widecolumn .mu_register .mu_alert {
			border-color: %1$s; /* Fallback for IE7 and IE8 */
			border-color: %2$s;
		}

		hr,
		code {
			background-color: %1$s; /* Fallback for IE7 and IE8 */
			background-color: %2$s;
		}

		@media screen and (min-width: 56.875em) {
			.main-navigation ul ul,
			.main-navigation ul ul li {
				border-color: %2$s;
			}

			.main-navigation ul ul:before {
				border-top-color: %2$s;
				border-bottom-color: %2$s;
			}
		}
	';

        wp_add_inline_style('mine-style', sprintf($css, $content_color, $border_color));
    }

}
add_action('wp_enqueue_scripts', 'mine_content_color_css', 11);

/**
 * Enqueues front-end CSS for the secondary color.
 *
 * @since 1.0
 *
 * @see wp_add_inline_style()
 */
if (!function_exists('mine_secondary_color_css')) {

    function mine_secondary_color_css() {
        $color_scheme = mine_get_color_scheme();
        $default_color = $color_scheme[3];
        $secondary_color = get_theme_mod('secondary_color', $default_color);

        // Don't do anything if the current color is the default.
        if ($secondary_color === $default_color) {
            return;
        }

        $css = '
		/* Custom Secondary Color */

		/**
		 * IE8 and earlier will drop any block with CSS3 selectors.
		 * Do not combine these styles with the next block.
		 */
		body:not(.search-results) .entry-summary {
			color: %1$s;
		}

		blockquote,
		.post-password-form label,
		a:hover,
		a:focus,
		a:active,
		.post-navigation .meta-nav,
		.image-navigation,
		.comment-navigation,
		.widget_recent_entries .post-date,
		.widget_rss .rss-date,
		.widget_rss cite,
		.site-description,
		.author-bio,
		.entry-footer,
		.entry-footer a,
		.sticky-post,
		.taxonomy-description,
		.entry-caption,
		.comment-metadata,
		.pingback .edit-link,
		.comment-metadata a,
		.pingback .comment-edit-link,
		.comment-form label,
		.comment-notes,
		.comment-awaiting-moderation,
		.logged-in-as,
		.form-allowed-tags,
		.site-info,
		.site-info a,
		.wp-caption .wp-caption-text,
		.gallery-caption,
		.widecolumn label,
		.widecolumn .mu_register label {
			color: %1$s;
		}

		.widget_calendar tbody a:hover,
		.widget_calendar tbody a:focus {
			background-color: %1$s;
		}
	';

        wp_add_inline_style('mine-style', sprintf($css, $secondary_color));
    }

}
add_action('wp_enqueue_scripts', 'mine_secondary_color_css', 11);

/**
 * Enqueues front-end CSS for the meta color.
 *
 * @since 1.0
 *
 * @see wp_add_inline_style()
 */
if (!function_exists('mine_meta_color_css')) {

    function mine_meta_color_css() {
        $color_scheme = mine_get_color_scheme();
        $default_color = $color_scheme[4];
        $meta_color = get_theme_mod('meta_color', $default_color);

        // Don't do anything if the current color is the default.
        if ($meta_color === $default_color) {
            return;
        }

        $css = '
		/* Custom Meta Color */

		.widecolumn .mu_register label {
			color: %1$s;
		}
	';

        wp_add_inline_style('mine-style', sprintf($css, $meta_color));
    }

}
add_action('wp_enqueue_scripts', 'mine_meta_color_css', 15);


/**
 * Enqueues front-end CSS for the meta color.
 *
 * @since 1.0
 *
 * @see wp_add_inline_style()
 */
if (!function_exists('mine_header_text_color_css')) {

    function mine_header_text_color_css() {
        $header_text_color = get_header_textcolor();
        $primary_color = get_theme_mod('primary_color');
        $content_color = get_theme_mod('content_color');
        $meta_color = get_theme_mod('meta_color');
        $secondary_color = get_theme_mod('secondary_color');
        $border_color = get_theme_mod('primary_color');
        if(isset($content_color) && $content_color != ''){
            $border_color = sprintf('rgba( %1$s, %1$s, %1$s, 0.2)', $content_color);    
        }
        
        ?>
        <style>
            
            h1.page-title,
            .main-navigation ul ul li,
            .main-navigation ul.nav.navbar-nav li a,
            .main-navigation ul.nav.navbar-nav li:hover > a,
            .main-navigation ul.nav.navbar-nav li.current-menu-item > a,
            .main-navigation ul.nav.navbar-nav li.current-menu-ancestor > a,
            .main-navigation ul.nav.navbar-nav ul {
                border-color : #<?php echo esc_html($header_text_color); ?> !important;
            }
            
            .main-navigation ul.nav.navbar-nav ul {
                border-top-color : #<?php echo esc_html($header_text_color); ?>;
            }
            
            h1.page-title,
            p.site-description,
            .main-navigation ul.nav.navbar-nav li a,
            .main-navigation ul.nav.navbar-nav li:hover > a,
            .main-navigation ul.nav.navbar-nav li.current-menu-item > a,
            .main-navigation ul.nav.navbar-nav li.current-menu-ancestor > a,
            .breadcrumbs_block .breadcrumbs li a:hover,
            .breadcrumbs_block .breadcrumbs .item-current,
            .breadcrumbs_block .breadcrumbs .separator,
            .breadcrumbs_block .breadcrumbs li a {
                color: #<?php echo esc_html($header_text_color); ?>;
            }

            /* Primary Color */

            fieldset a,
            pre a,
            abbr a,
            acronym a,
            table a,
            th a,
            td a,
            blockquote a,
            dl a,
            p a,
            code a,
            figcaption a,
            input[type="submit"]:hover,
            .menu-toggle:hover,
            .menu-toggle:focus,
            .main-navigation a:hover,
            .main-navigation a:focus,
            .breadcrumbs li a:hover,
            .breadcrumbs li a:focus,
            .dropdown-toggle:hover,
            .dropdown-toggle:focus,
            .social-navigation a:hover:before,
            .social-navigation a:focus:before,
            .post-navigation a:hover .post-title,
            .post-navigation a:focus .post-title,
            .tagcloud a:hover,
            .tagcloud a:focus,
            .site-branding .site-title a:hover,
            .site-branding .site-title a:focus,
            .site-info a:hover,
            .site-info a:focus,
            .entry-footer .entry-date a:hover,
            .entry-footer .entry-date a:focus,
            .entry-footer .comments-link a:hover,
            .entry-footer .comments-link a:focus,
            .entry-footer .author a:hover,
            .entry-footer .comments-link a:hover,
            .entry-footer .tags a:hover,
            .entry-footer .tags a:focus,
            .comment-metadata a:hover,
            .comment-metadata a:focus,
            .pingback .comment-edit-link:hover,
            .pingback .comment-edit-link:focus,
            .comment-reply-link:hover,
            .comment-reply-link:focus,
            .edit-link a,
            .widget .related_post .entry-footer a:hover,
            .read_more a:hover,
            .page-title,
            .required,
            .entry-title,
            .entry-title a,
            .comments-title,
            .pingback a,
            .comment-reply-title,
            .widget,
            .widget .widget-title,
            .widget a,
            .textwidget a,
            .widget ul li,
            .widget ol li,
            .widget ul li a,
            .widget ol li a,
            .site-description,
            .home .author-bio,
            .comment-author,
            .logged-in-as,
            .comment-author a,
            .home .about_author .user_designation,
            .author-link-wrap a,
            .comment-form .logged-in-as a,
            .entry-footer .post-meta .cat-links a:hover,
            .entry-footer .post-meta .cat-links a:focus,
            .widget .related_post .entry-footer a:hover,
            .widget .related_post .entry-footer a:focus,
            .paging-navigation .pagination a.page-numbers,
            input[type="submit"]:hover,
            input[type="submit"]:focus {
                color: <?php print_r($primary_color); ?> !important;
            }

            button:hover,
            button:focus,
            input[type="submit"],
            input[type="button"]:hover,
            input[type="button"]:focus,
            input[type="reset"]:hover,
            input[type="reset"]:focus,
            input[type="submit"]:hover,
            input[type="submit"]:focus,
            .pagination .prev:hover,
            .pagination .prev:focus,
            .pagination .next:hover,
            .pagination .next:focus,
            .widget_calendar tbody a,
            .page-links a:hover,
            .page-links a:focus,
            .read_more a,
            a.arrow-up,
            .comment-reply-link,
            .site-branding .site-title.tagline a,
            .entry-footer .post-meta .cat-links a,
            .paging-navigation .pagination .page-numbers.current,
            .home-content-area .widget.widget_related_post .widget-title:before,
            .home-content-area .widget.widget_related_post .widget-title:after,
            .home-content-area .widget.widget_latest_post .widget-title:before,
            .home-content-area .widget.widget_latest_post .widget-title:after,
            .paging-navigation .pagination a.page-numbers:hover,
            .paging-navigation .pagination a.page-numbers:active,
            .widget.widget_tag_cloud .tagcloud a:hover {
                    background-color: <?php print_r($primary_color); ?> !important;
            }

            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="url"]:focus,
            input[type="password"]:focus,
            input[type="search"]:focus,
            input[type="submit"],
            textarea:focus,
            .tagcloud a:hover,
            .tagcloud a:focus,
            .menu-toggle:hover,
            .menu-toggle:focus,
            .read_more a,
            .widget,
            .entry-footer .post-meta .cat-links a,
            .paging-navigation .pagination .page-numbers.current,
            .paging-navigation .pagination .page-numbers,
            .comment-reply-link,
            .widget.widget_tag_cloud .tagcloud a:hover {
                    border-color: <?php print_r($primary_color); ?> !important;
            }

            /* Content Color */
            body,
            blockquote cite,
            blockquote small,
            pre,
            label,
            select,
            textarea,
            h1, h2, h3, h4, h5, h6,
            input[type="text"],
            input[type="email"],
            input[type="url"], 
            input[type="password"],
            input[type="search"], 
            input[type="tel"],
            input[type="number"], 
            .odd td a,
            .even td a,
            .menu-toggle,
            .dropdown-toggle,
            .social-navigation a,
            .post-navigation a,
            .pagination a:hover,
            .pagination a:focus,
            .site-branding .site-title a,
            .page-links > .page-links-title,
            .comment-reply-title small a:hover,
            .comment-reply-title small a:focus,
            .entry-content,
            .comment-content a,
            .about_author .user_designation,
            .author-bio,
            .skill_description,
            .wpcf7 .wpcf7-form label,
            .pingback, .comment-content,
            .widget.widget_calendar caption,
            .widget.widget_calendar td,
            .widget.widget_calendar th {
                    color: <?php print_r($content_color); ?> !important;
            }

            blockquote,
            .menu-toggle.toggled-on,
            .menu-toggle.toggled-on:hover,
            .menu-toggle.toggled-on:focus,
            .post-navigation,
            .post-navigation div + div,
            .pagination,
            .page-header,
            .page-links a,
            .comments-title,
            .comment-reply-title {
                    border-color: <?php print_r($content_color); ?> !important;
            }
            .widget .widget-title {
               border-bottom-color: <?php print_r($content_color); ?> !important;
            }

            button,
            button[disabled]:hover,
            button[disabled]:focus,
            input[type="button"],
            input[type="button"][disabled]:hover,
            input[type="button"][disabled]:focus,
            input[type="reset"],
            input[type="reset"][disabled]:hover,
            input[type="reset"][disabled]:focus,
            input[type="submit"][disabled]:hover,
            input[type="submit"][disabled]:focus,
            .menu-toggle.toggled-on,
            .menu-toggle.toggled-on:hover,
            .menu-toggle.toggled-on:focus,
            .pagination:before,
            .pagination:after,
            .page-links a {
                    background-color: <?php print_r($content_color); ?> !important;
            }

            /* Meta Color */

            body:not(.search-results) .entry-summary,
            .entry-footer .post-meta,
            .entry-footer .entry-date a,
            .entry-footer .comments-link a,
            .entry-footer .tags a,
            .entry-footer .author a,
            .comment-metadata,
            .comment-metadata a,
            .widget.widget_rss .rss-date,
            .widget.widget_rss cite,
            .widget_recent_entries .post-date,
            .entry-footer .post-meta.blog_post_meta > span:after {
                    color: <?php print_r($meta_color); ?> !important;
            }

            .entry-footer .post-meta .cat-links .post-categories li a {
                    color: <?php print_r($meta_color); ?>;
            }

            .entry-footer .post-meta.blog_post_meta > span {
                 border-color: <?php print_r($meta_color); ?> !important;
            }

            /* Secondary Color */

            /**
             * IE8 and earlier will drop any block with CSS3 selectors.
             * Do not combine these styles with the next block.
             */
            body:not(.search-results) .entry-summary {
                    color: <?php print_r($secondary_color); ?> !important;
            }

            blockquote,
            table a:hover,
            blockquote a:hover,
            dl a:hover,
            p a:hover,
            code a:hover,
            figcaption a:hover,
            .post-password-form label,
            .post-navigation .meta-nav,
            .image-navigation,
            .main-navigation li:hover > a,
            .main-navigation li:focus > a,
            .comment-navigation,
            .widget_rss .rss-date,
            .widget_rss cite,
            .entry-footer,
            .sticky-post,
            .taxonomy-description,
            .entry-caption,
            .pingback .edit-link,
            .pingback .comment-edit-link,
            .comment-form label,
            .comment-notes,
            .pingback a:hover,
            .site-footer .copyright a:hover,
            .comment-author a:hover,
            .comment-author a:focus,
            .comment-awaiting-moderation,
            .form-allowed-tags,
            .entry-title a:hover,
            .entry-title a:focus,
            .widget .related_post .entry-title a:hover,
            .widget .related_post .entry-title a:focus,
            .site-info,
            .edit-link a:hover,
            .site-info a,
            .wp-caption .wp-caption-text,
            .gallery-caption,
            .author-link-wrap a:hover,
            .author-link-wrap a:focus,
            .widecolumn label,
            .widecolumn .mu_register label,
            .widget ul li a:hover,
            .widget ul li a:focus,
            .widget a:hover,
            .widget a:focus,
            .textwidget a:hover,
            .textwidget a:focus,
            .comment-form .logged-in-as a:hover,
            .comment-form .logged-in-as a:focus {
                    color: <?php print_r($secondary_color); ?> !important;
            }

            .widget_calendar tbody a:hover,
            .widget_calendar tbody a:focus {
                    background-color: <?php print_r($secondary_color); ?> !important;
            }

            /* Border Color */
            fieldset,
            pre,
            abbr,
            acronym,
            table,
            th,
            td,
            input[type="text"],
            input[type="email"],
            input[type="url"],
            input[type="password"],
            input[type="search"],
            textarea,
            .main-navigation .primary-menu,
            .menu-toggle,
            .dropdown-toggle:after,
            .social-navigation a,
            .image-navigation,
            .comment-navigation,
            .tagcloud a,
            .section_seperation,
            .entry-content,
            .entry-summary,
            .page-links a,
            .page-links > span,
            .comment-list article,
            .comment-list .pingback,
            .comment-list .trackback,
            .no-comments,
            .widecolumn .mu_register .mu_alert {
                    border-color: <?php print_r($content_color); ?> !important; /* Fallback for IE7 and IE8 */
                    border-color: <?php print_r($border_color); ?> !important;
            }

            hr,
            code,
            mark,
            ins,
            kbd {
                    background-color: <?php print_r($content_color); ?> !important; /* Fallback for IE7 and IE8 */
                    border-color: <?php print_r($border_color); ?> !important;
            }

            @media screen and (min-width: 56.875em) {
                    .main-navigation li:hover > a,
                    .main-navigation li:focus > a {
                            color: <?php print_r($secondary_color); ?> !important;
                    }

                    .main-navigation ul ul,
                    .main-navigation ul ul li {
                            border-color: <?php print_r($border_color); ?> !important;
                    }

                    .main-navigation ul ul:before {
                            border-top-color: <?php print_r($border_color); ?> !important;
                            border-bottom-color: <?php print_r($border_color); ?> !important;
                    }

            }
        </style>
        <?php
        
        $css = '
		/* Custom Header Text Color */

		.page-title {
			color: #'.$header_text_color.';
		}
	';

        wp_add_inline_style('mine-style', sprintf($css, '#'.$header_text_color));
    }

}
add_action('wp_enqueue_scripts', 'mine_header_text_color_css', 1000);