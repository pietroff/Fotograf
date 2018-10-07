/* global colorScheme, Color */
/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

jQuery(document).ready(function ($) {
    wp.customize.section('sidebar-widgets-skills-sidebar').panel('mine_about_page_settings');
    wp.customize.section('sidebar-widgets-skills-sidebar').priority('10');
});

( function( api ) {
	var cssTemplate = wp.template( 'mine-color-scheme' ),
		colorSchemeKeys = [
			'header_textcolor',
			'primary_color',
			'secondary_color',
                        'content_color',
                        'meta_color',
                        'background_color'
		],
		colorSettings = [
			'header_textcolor',
			'primary_color',
			'secondary_color',
                        'content_color',
                        'meta_color',
                        'background_color'
		];

	api.controlConstructor.select = api.Control.extend( {
		ready: function() {
			if ( 'color_scheme' === this.id ) {
				this.setting.bind( 'change', function( value ) {
					var colors = colorScheme[value].colors;

					// Update Header Color.
					var color = colors[0];
                                        api('header_textcolor').set(color);
                                        api.control('header_textcolor').container.find('.color-picker-hex')
                                                .data('data-default-color', color)
                                                .wpColorPicker('defaultColor', color);
                                        
					
					// Update Primary Color.
					color = colors[1];
					api( 'primary_color' ).set( color );
					api.control( 'primary_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Secondary Text Color.
					color = colors[2];
					api( 'secondary_color' ).set( color );
					api.control( 'secondary_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );
                                                                                
                                        // Update Secondary Text Color.
					color = colors[3];
					api( 'content_color' ).set( color );
					api.control( 'content_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );
                                        
                                        // Update Secondary Text Color.
					color = colors[4];
					api( 'meta_color' ).set( color );
					api.control( 'meta_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );
                                        
                                        // Update Secondary Text Color.
					color = colors[5];
					api( 'background_color' ).set( color );
					api.control( 'background_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

				} );
			}
		}
	} );

	// Generate the CSS for the current Color Scheme.
	function updateCSS() {
		var scheme = api( 'color_scheme' )(),
			css,
			colors = _.object( colorSchemeKeys, colorScheme[ scheme ].colors );

		// Merge in color scheme overrides.
		_.each( colorSettings, function( setting ) {
			colors[ setting ] = api( setting )();
		} );

		// Add additional color.
		// jscs:disable
		colors.border_color = Color( colors.main_text_color ).toCSS( 'rgba', 0.2 );
		// jscs:enable

		css = cssTemplate( colors );

		api.previewer.send( 'update-color-scheme-css', css );
	}

	// Update the CSS whenever a color setting is changed.
	_.each( colorSettings, function( setting ) {
		api( setting, function( setting ) {
			setting.bind( updateCSS );
		} );
	} );
} )( wp.customize );
