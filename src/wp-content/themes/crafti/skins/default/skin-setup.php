<?php
/**
 * Skin Setup
 *
 * @package CRAFTI
 * @since CRAFTI 1.76.0
 */


//--------------------------------------------
// SKIN DEFAULTS
//--------------------------------------------

// Return theme's (skin's) default value for the specified parameter
if ( ! function_exists( 'crafti_theme_defaults' ) ) {
	function crafti_theme_defaults( $name='', $value='' ) {
		$defaults = array(
			'page_width'          => 1290,
			'page_boxed_extra'  => 60,
			'page_fullwide_max' => 1920,
			'page_fullwide_extra' => 60,
			'sidebar_width'       => 410,
			'sidebar_gap'       => 40,
			'grid_gap'          => 30,
			'rad'               => 0,
		);
		if ( empty( $name ) ) {
			return $defaults;
		} else {
			if ( empty( $value ) && isset( $defaults[ $name ] ) ) {
				$value = $defaults[ $name ];
			}
			return $value;
		}
	}
}


// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)


//--------------------------------------------
// SKIN SETTINGS
//--------------------------------------------
if ( ! function_exists( 'crafti_skin_setup' ) ) {
	add_action( 'after_setup_theme', 'crafti_skin_setup', 1 );
	function crafti_skin_setup() {

		$GLOBALS['CRAFTI_STORAGE'] = array_merge( $GLOBALS['CRAFTI_STORAGE'], array(

			// Key validator: market[env|loc]-vendor[axiom|ancora|themerex]
			'theme_pro_key'       => 'env-axiom',

			'theme_doc_url'       => '//crafti.axiomthemes.com/doc',

			'theme_demofiles_url' => '//demofiles.axiomthemes.com/crafti/',
			
			'theme_rate_url'      => '//themeforest.net/download',

			'theme_custom_url'    => '//themerex.net/offers/?utm_source=offers&utm_medium=click&utm_campaign=themeinstall',

			'theme_support_url'   => '//themerex.net/support/',

			'theme_download_url'  => '//themeforest.net/user/axiomthemes/portfolio',         // Axiom

			'theme_video_url'     => '//www.youtube.com/channel/UCBjqhuwKj3MfE3B6Hg2oA8Q',   // Axiom

			'theme_privacy_url'   => '//axiomthemes.com/privacy-policy/',                    // Axiom

			'portfolio_url'       => '//themeforest.net/user/axiomthemes/portfolio',         // Axiom

			// Comma separated slugs of theme-specific categories (for get relevant news in the dashboard widget)
			// (i.e. 'children,kindergarten')
			'theme_categories'    => '',
		) );
	}
}


// Add/remove/change Theme Settings
if ( ! function_exists( 'crafti_skin_setup_settings' ) ) {
	add_action( 'after_setup_theme', 'crafti_skin_setup_settings', 1 );
	function crafti_skin_setup_settings() {
		// Example: enable (true) / disable (false) thumbs in the prev/next navigation
		crafti_storage_set_array( 'settings', 'thumbs_in_navigation', false );
	}
}


// add/remove Theme Options elements
if ( ! function_exists( 'crafti_skin_options_theme_setup2' ) ) {
	add_action( 'after_setup_theme', 'crafti_skin_options_theme_setup2', 2 );
	function crafti_skin_options_theme_setup2() {
		crafti_storage_set_array2( 'options', 'use_pattern', 'std', 0);
	}
}


//--------------------------------------------
// SKIN FONTS
//--------------------------------------------
if ( ! function_exists( 'crafti_skin_setup_fonts' ) ) {
	add_action( 'after_setup_theme', 'crafti_skin_setup_fonts', 1 );
	function crafti_skin_setup_fonts() {
		// Fonts to load when theme start
		// It can be:
		// - Google fonts (specify name, family and styles)
		// - Adobe fonts (specify name, family and link URL)
		// - uploaded fonts (specify name, family), placed in the folder css/font-face/font-name inside the skin folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		// example: font name 'TeX Gyre Termes', folder 'TeX-Gyre-Termes'
		crafti_storage_set(
			'load_fonts', array(
				array(
					'name'   => 'Museo',
					'family' => 'serif',
					'link'   => 'https://use.typekit.net/xde1xhi.css',
					'styles' => ''
				),
				// Google font
                array(
                    'name'   => 'DM Sans',
                    'family' => 'sans-serif',
                    'link'   => '',
                    'styles' => 'ital,wght@0,400;0,500;0,700;1,400;1,500;1,700',     // Parameter 'style' used only for the Google fonts
                ),
			)
		);

		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		crafti_storage_set( 'load_fonts_subset', 'latin,latin-ext' );

		// Settings of the main tags.
		// Default value of 'font-family' may be specified as reference to the array $load_fonts (see above)
		// or as comma-separated string.
		// In the second case (if 'font-family' is specified manually as comma-separated string):
		//    1) Font name with spaces in the parameter 'font-family' will be enclosed in the quotes and no spaces after comma!
		//    2) If font-family inherit a value from the 'Main text' - specify 'inherit' as a value
		// example:
		// Correct:   'font-family' => basekit_get_load_fonts_family_string( $load_fonts[0] )
		// Correct:   'font-family' => 'Roboto,sans-serif'
		// Correct:   'font-family' => '"PT Serif",sans-serif'
		// Incorrect: 'font-family' => 'Roboto, sans-serif'
		// Incorrect: 'font-family' => 'PT Serif,sans-serif'

		$font_description = esc_html__( 'Font settings for the %s of the site. To ensure that the elements scale properly on mobile devices, please use only the following units: "rem", "em" or "ex"', 'crafti' );

		crafti_storage_set(
			'theme_fonts', array(
				'p'       => array(
					'title'           => esc_html__( 'Main text', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'main text', 'crafti' ) ),
					'font-family'     => '"DM Sans",sans-serif',
					'font-size'       => '1rem',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.62em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '0em',
					'margin-bottom'   => '1.7em',
				),
				'post'    => array(
					'title'           => esc_html__( 'Article text', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'article text', 'crafti' ) ),
					'font-family'     => '',			// Example: '"PR Serif",serif',
					'font-size'       => '',			// Example: '1.286rem',
					'font-weight'     => '',			// Example: '400',
					'font-style'      => '',			// Example: 'normal',
					'line-height'     => '',			// Example: '1.75em',
					'text-decoration' => '',			// Example: 'none',
					'text-transform'  => '',			// Example: 'none',
					'letter-spacing'  => '',			// Example: '',
					'margin-top'      => '',			// Example: '0em',
					'margin-bottom'   => '',			// Example: '1.4em',
				),
				'h1'      => array(
					'title'           => esc_html__( 'Heading 1', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H1', 'crafti' ) ),
					'font-family'     => 'Museo,serif',
					'font-size'       => '3.353em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '-1.1px',
					'margin-top'      => '1.16em',
					'margin-bottom'   => '0.4em',
				),
				'h2'      => array(
					'title'           => esc_html__( 'Heading 2', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H2', 'crafti' ) ),
					'font-family'     => 'Museo,serif',
					'font-size'       => '2.765em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.021em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '0.82em',
					'margin-bottom'   => '0.45em',
				),
				'h3'      => array(
					'title'           => esc_html__( 'Heading 3', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H3', 'crafti' ) ),
					'font-family'     => 'Museo,serif',
					'font-size'       => '2.059em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.086em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.19em',
					'margin-bottom'   => '0.64em',
				),
				'h4'      => array(
					'title'           => esc_html__( 'Heading 4', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H4', 'crafti' ) ),
					'font-family'     => 'Museo,serif',
					'font-size'       => '1.647em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.143em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.48em',
					'margin-bottom'   => '0.7em',
				),
				'h5'      => array(
					'title'           => esc_html__( 'Heading 5', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H5', 'crafti' ) ),
					'font-family'     => 'Museo,serif',
					'font-size'       => '1.412em',
					'font-weight'     => '500',
					'font-style'      => 'normal',
					'line-height'     => '1.417em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.45em',
					'margin-bottom'   => '0.7em',
				),
				'h6'      => array(
					'title'           => esc_html__( 'Heading 6', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H6', 'crafti' ) ),
					'font-family'     => 'Museo,serif',
					'font-size'       => '1.118em',
					'font-weight'     => '500',
					'font-style'      => 'normal',
					'line-height'     => '1.474em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.75em',
					'margin-bottom'   => '0.75em',
				),
				'logo'    => array(
					'title'           => esc_html__( 'Logo text', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'text of the logo', 'crafti' ) ),
					'font-family'     => 'Museo,serif',
					'font-size'       => '1.7em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.25em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'button'  => array(
					'title'           => esc_html__( 'Buttons', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'buttons', 'crafti' ) ),
					'font-family'     => 'Museo,serif',
					'font-size'       => '13px',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '20px',
					'text-decoration' => 'none',
					'text-transform'  => 'uppercase',
					'letter-spacing'  => '0.8px',
				),
				'input'   => array(
					'title'           => esc_html__( 'Input fields', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'input fields, dropdowns and textareas', 'crafti' ) ),
					'font-family'     => 'inherit',
					'font-size'       => '16px',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',     // Attention! Firefox don't allow line-height less then 1.5em in the select
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'info'    => array(
					'title'           => esc_html__( 'Post meta', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'post meta (author, categories, publish date, counters, share, etc.)', 'crafti' ) ),
					'font-family'     => 'inherit',
					'font-size'       => '14px',  // Old value '13px' don't allow using 'font zoom' in the custom blog items
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '0.4em',
					'margin-bottom'   => '',
				),
				'menu'    => array(
					'title'           => esc_html__( 'Main menu', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'main menu items', 'crafti' ) ),
					'font-family'     => 'Museo,serif',
					'font-size'       => '17px',
					'font-weight'     => '500',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'submenu' => array(
					'title'           => esc_html__( 'Dropdown menu', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'dropdown menu items', 'crafti' ) ),
					'font-family'     => '"DM Sans",sans-serif',
					'font-size'       => '15px',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'other' => array(
					'title'           => esc_html__( 'Other', 'crafti' ),
					'description'     => sprintf( $font_description, esc_html__( 'specific elements', 'crafti' ) ),
					'font-family'     => '"DM Sans",sans-serif',
				),
			)
		);

		// Font presets
		crafti_storage_set(
			'font_presets', array(
				'karla' => array(
								'title'  => esc_html__( 'Karla', 'crafti' ),
								'load_fonts' => array(
													// Google font
													array(
														'name'   => 'Dancing Script',
														'family' => 'fantasy',
														'link'   => '',
														'styles' => '300,400,700',
													),
													// Google font
													array(
														'name'   => 'Sansita Swashed',
														'family' => 'fantasy',
														'link'   => '',
														'styles' => '300,400,700',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Dancing Script",fantasy',
														'font-size'       => '1.25rem',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
														'font-size'       => '4em',
													),
													'h2'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h3'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h4'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h5'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h6'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'logo'    => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'button'  => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'submenu' => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
												),
							),
				'roboto' => array(
								'title'  => esc_html__( 'Roboto', 'crafti' ),
								'load_fonts' => array(
													// Google font
													array(
														'name'   => 'Noto Sans JP',
														'family' => 'serif',
														'link'   => '',
														'styles' => '300,300italic,400,400italic,700,700italic',
													),
													// Google font
													array(
														'name'   => 'Merriweather',
														'family' => 'sans-serif',
														'link'   => '',
														'styles' => '300,300italic,400,400italic,700,700italic',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Noto Sans JP",serif',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h2'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h3'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h4'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h5'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h6'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'logo'    => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'button'  => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'submenu' => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
												),
							),
				'garamond' => array(
								'title'  => esc_html__( 'Garamond', 'crafti' ),
								'load_fonts' => array(
													// Adobe font
													array(
														'name'   => 'Europe',
														'family' => 'sans-serif',
														'link'   => 'https://use.typekit.net/qmj1tmx.css',
														'styles' => '',
													),
													// Adobe font
													array(
														'name'   => 'Sofia Pro',
														'family' => 'sans-serif',
														'link'   => 'https://use.typekit.net/qmj1tmx.css',
														'styles' => '',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Sofia Pro",sans-serif',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h2'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h3'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h4'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h5'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h6'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'logo'    => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'button'  => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'submenu' => array(
														'font-family'     => 'Europe,sans-serif',
													),
												),
							),
			)
		);
	}
}


//--------------------------------------------
// COLOR SCHEMES
//--------------------------------------------
if ( ! function_exists( 'crafti_skin_setup_schemes' ) ) {
	add_action( 'after_setup_theme', 'crafti_skin_setup_schemes', 1 );
	function crafti_skin_setup_schemes() {

		// Theme colors for customizer
		// Attention! Inner scheme must be last in the array below
		crafti_storage_set(
			'scheme_color_groups', array(
				'main'    => array(
					'title'       => esc_html__( 'Main', 'crafti' ),
					'description' => esc_html__( 'Colors of the main content area', 'crafti' ),
				),
				'alter'   => array(
					'title'       => esc_html__( 'Alter', 'crafti' ),
					'description' => esc_html__( 'Colors of the alternative blocks (sidebars, etc.)', 'crafti' ),
				),
				'extra'   => array(
					'title'       => esc_html__( 'Extra', 'crafti' ),
					'description' => esc_html__( 'Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'crafti' ),
				),
				'inverse' => array(
					'title'       => esc_html__( 'Inverse', 'crafti' ),
					'description' => esc_html__( 'Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'crafti' ),
				),
				'input'   => array(
					'title'       => esc_html__( 'Input', 'crafti' ),
					'description' => esc_html__( 'Colors of the form fields (text field, textarea, select, etc.)', 'crafti' ),
				),
			)
		);

		crafti_storage_set(
			'scheme_color_names', array(
				'bg_color'    => array(
					'title'       => esc_html__( 'Background color', 'crafti' ),
					'description' => esc_html__( 'Background color of this block in the normal state', 'crafti' ),
				),
				'bg_hover'    => array(
					'title'       => esc_html__( 'Background hover', 'crafti' ),
					'description' => esc_html__( 'Background color of this block in the hovered state', 'crafti' ),
				),
				'bd_color'    => array(
					'title'       => esc_html__( 'Border color', 'crafti' ),
					'description' => esc_html__( 'Border color of this block in the normal state', 'crafti' ),
				),
				'bd_hover'    => array(
					'title'       => esc_html__( 'Border hover', 'crafti' ),
					'description' => esc_html__( 'Border color of this block in the hovered state', 'crafti' ),
				),
				'text'        => array(
					'title'       => esc_html__( 'Text', 'crafti' ),
					'description' => esc_html__( 'Color of the text inside this block', 'crafti' ),
				),
				'text_dark'   => array(
					'title'       => esc_html__( 'Text dark', 'crafti' ),
					'description' => esc_html__( 'Color of the dark text (bold, header, etc.) inside this block', 'crafti' ),
				),
				'text_light'  => array(
					'title'       => esc_html__( 'Text light', 'crafti' ),
					'description' => esc_html__( 'Color of the light text (post meta, etc.) inside this block', 'crafti' ),
				),
				'text_link'   => array(
					'title'       => esc_html__( 'Link', 'crafti' ),
					'description' => esc_html__( 'Color of the links inside this block', 'crafti' ),
				),
				'text_hover'  => array(
					'title'       => esc_html__( 'Link hover', 'crafti' ),
					'description' => esc_html__( 'Color of the hovered state of links inside this block', 'crafti' ),
				),
				'text_link2'  => array(
					'title'       => esc_html__( 'Accent 2', 'crafti' ),
					'description' => esc_html__( 'Color of the accented texts (areas) inside this block', 'crafti' ),
				),
				'text_hover2' => array(
					'title'       => esc_html__( 'Accent 2 hover', 'crafti' ),
					'description' => esc_html__( 'Color of the hovered state of accented texts (areas) inside this block', 'crafti' ),
				),
				'text_link3'  => array(
					'title'       => esc_html__( 'Accent 3', 'crafti' ),
					'description' => esc_html__( 'Color of the other accented texts (buttons) inside this block', 'crafti' ),
				),
				'text_hover3' => array(
					'title'       => esc_html__( 'Accent 3 hover', 'crafti' ),
					'description' => esc_html__( 'Color of the hovered state of other accented texts (buttons) inside this block', 'crafti' ),
				),
			)
		);

		// Default values for each color scheme
		$schemes = array(

			// Color scheme: 'default'
			'default' => array(
				'title'    => esc_html__( 'Default', 'crafti' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#F3F0E7', //
					'bd_color'         => '#D6D5D2', //

					// Text and links colors
					'text'             => '#767470', //
					'text_light'       => '#898381', //
					'text_dark'        => '#182127', // ok
					'text_link'        => '#BF5415', // ok
					'text_hover'       => '#AA470C', // ok
					'text_link2'       => '#435E74', // ok
					'text_hover2'      => '#385369', // ok
					'text_link3'       => '#C5A48E', //
					'text_hover3'      => '#AB8E7A', //

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#FAF8F4', //
					'alter_bg_hover'   => '#F2EFE6', //
					'alter_bd_color'   => '#D6D5D2', //
					'alter_bd_hover'   => '#C7C6C1', //
					'alter_text'       => '#767470', //
					'alter_light'      => '#898381', //
					'alter_dark'       => '#182127', // ok
					'alter_link'       => '#BF5415', // ok
					'alter_hover'      => '#AA470C', // ok
					'alter_link2'      => '#435E74', // ok
					'alter_hover2'     => '#385369', // ok
					'alter_link3'      => '#C5A48E', //
					'alter_hover3'     => '#AB8E7A', //

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#1F140B', // ok
					'extra_bg_hover'   => '#3f3d47',
					'extra_bd_color'   => '#313131',
					'extra_bd_hover'   => '#575757',
					'extra_text'       => '#898381', //
					'extra_light'      => '#afafaf',
					'extra_dark'       => '#FFFFFF', //
					'extra_link'       => '#BF5415', // ok
					'extra_hover'      => '#FFFFFF', //
					'extra_link2'      => '#80d572',
					'extra_hover2'     => '#8be77c',
					'extra_link3'      => '#ddb837',
					'extra_hover3'     => '#eec432',

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //
					'input_bg_hover'   => 'transparent', //
					'input_bd_color'   => '#D6D5D2', //
					'input_bd_hover'   => '#C7C6C1', //
					'input_text'       => '#767470', //
					'input_light'      => '#898381', //
					'input_dark'       => '#182127', // ok

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#67bcc1',
					'inverse_bd_hover' => '#5aa4a9',
					'inverse_text'     => '#1d1d1d',
					'inverse_light'    => '#333333',
					'inverse_dark'     => '#182127', // ok
					'inverse_link'     => '#FFFFFF', //
					'inverse_hover'    => '#FFFFFF', //

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),

			// Color scheme: 'dark'
			'dark'    => array(
				'title'    => esc_html__( 'Dark', 'crafti' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#22160C', //
					'bd_color'         => '#3C3F47', //

					// Text and links colors
					'text'             => '#D3CECB', //
					'text_light'       => '#C1BBB6', //
					'text_dark'        => '#F3F0E7', //
					'text_link'        => '#BF5415', // ok
					'text_hover'       => '#AA470C', // ok
					'text_link2'       => '#435E74', // ok
					'text_hover2'      => '#385369', // ok
					'text_link3'       => '#C5A48E', //
					'text_hover3'      => '#AB8E7A', //

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#1F140B', // ok
					'alter_bg_hover'   => '#24180F', //
					'alter_bd_color'   => '#433D37', //
					'alter_bd_hover'   => '#4D463F', //
					'alter_text'       => '#D3CECB', //
					'alter_light'      => '#C1BBB6', //
					'alter_dark'       => '#F3F0E7', //
					'alter_link'       => '#BF5415', // ok
					'alter_hover'      => '#AA470C', // ok
					'alter_link2'      => '#435E74', // ok
					'alter_hover2'     => '#385369', // ok
					'alter_link3'      => '#C5A48E', //
					'alter_hover3'     => '#AB8E7A', //

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#1F140B', // ok
					'extra_bg_hover'   => '#3f3d47',
					'extra_bd_color'   => '#313131',
					'extra_bd_hover'   => '#575757',
					'extra_text'       => '#898381',
					'extra_light'      => '#afafaf',
					'extra_dark'       => '#FFFFFF',
					'extra_link'       => '#BF5415', // ok
					'extra_hover'      => '#FFFFFF',
					'extra_link2'      => '#80d572',
					'extra_hover2'     => '#8be77c',
					'extra_link3'      => '#ddb837',
					'extra_hover3'     => '#eec432',

					// Input fields (form's fields and textarea)
					'input_bg_color'   => '#transparent', //
					'input_bg_hover'   => '#transparent', //
					'input_bd_color'   => '#433D37', //
					'input_bd_hover'   => '#4D463F', //
					'input_text'       => '#807369', //
					'input_light'      => '#655649', //
					'input_dark'       => '#F3F0E7',

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#e36650',
					'inverse_bd_hover' => '#cb5b47',
					'inverse_text'     => '#F3F0E7', //
					'inverse_light'    => '#6f6f6f',
					'inverse_dark'     => '#182127', //ok
					'inverse_link'     => '#FFFFFF', //
					'inverse_hover'    => '#182127', //ok

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),

            // Color scheme: 'light'
            'light' => array(
                'title'    => esc_html__( 'Light', 'crafti' ),
                'internal' => true,
                'colors'   => array(

                    // Whole block border and background
                    'bg_color'         => '#FAF8F4', //
                    'bd_color'         => '#D6D5D2', //

                    // Text and links colors
                    'text'             => '#767470', //
                    'text_light'       => '#898381', //
                    'text_dark'        => '#182127', // ok
                    'text_link'        => '#BF5415', // ok
                    'text_hover'       => '#AA470C', // ok
                    'text_link2'       => '#435E74', // ok
                    'text_hover2'      => '#385369', // ok
                    'text_link3'       => '#C5A48E', //
                    'text_hover3'      => '#AB8E7A', //

                    // Alternative blocks (sidebar, tabs, alternative blocks, etc.)
                    'alter_bg_color'   => '#F3F0E7', //
                    'alter_bg_hover'   => '#FAF8F4', //
                    'alter_bd_color'   => '#D6D5D2', //
                    'alter_bd_hover'   => '#C7C6C1', //
                    'alter_text'       => '#767470', //
                    'alter_light'      => '#898381', //
                    'alter_dark'       => '#182127', // ok
                    'alter_link'       => '#BF5415', // ok
                    'alter_hover'      => '#AA470C', // ok
                    'alter_link2'      => '#435E74', // ok
                    'alter_hover2'     => '#385369', // ok
                    'alter_link3'      => '#C5A48E', //
                    'alter_hover3'     => '#AB8E7A', //

                    // Extra blocks (submenu, tabs, color blocks, etc.)
                    'extra_bg_color'   => '#1F140B', //ok
                    'extra_bg_hover'   => '#3f3d47',
                    'extra_bd_color'   => '#313131',
                    'extra_bd_hover'   => '#575757',
                    'extra_text'       => '#898381', //
                    'extra_light'      => '#afafaf',
                    'extra_dark'       => '#FFFFFF', //
                    'extra_link'       => '#BF5415', // ok
                    'extra_hover'      => '#FFFFFF', //
                    'extra_link2'      => '#80d572',
                    'extra_hover2'     => '#8be77c',
                    'extra_link3'      => '#ddb837',
                    'extra_hover3'     => '#eec432',

                    // Input fields (form's fields and textarea)
                    'input_bg_color'   => 'transparent', //
                    'input_bg_hover'   => 'transparent', //
                    'input_bd_color'   => '#D6D5D2', //
                    'input_bd_hover'   => '#C7C6C1', //
                    'input_text'       => '#767470', //
                    'input_light'      => '#898381', //
                    'input_dark'       => '#182127', //ok

                    // Inverse blocks (text and links on the 'text_link' background)
                    'inverse_bd_color' => '#67bcc1',
                    'inverse_bd_hover' => '#5aa4a9',
                    'inverse_text'     => '#1d1d1d',
                    'inverse_light'    => '#333333',
                    'inverse_dark'     => '#182127', //ok
                    'inverse_link'     => '#FFFFFF', //
                    'inverse_hover'    => '#FFFFFF', //

                    // Additional (skin-specific) colors.
                    // Attention! Set of colors must be equal in all color schemes.
                    //---> For example:
                    //---> 'new_color1'         => '#rrggbb',
                    //---> 'alter_new_color1'   => '#rrggbb',
                    //---> 'inverse_new_color1' => '#rrggbb',
                ),
            ),
		);
		crafti_storage_set( 'schemes', $schemes );
		crafti_storage_set( 'schemes_original', $schemes );

		// Add names of additional colors
		//---> For example:
		//---> crafti_storage_set_array( 'scheme_color_names', 'new_color1', array(
		//---> 	'title'       => __( 'New color 1', 'crafti' ),
		//---> 	'description' => __( 'Description of the new color 1', 'crafti' ),
		//---> ) );


		// Additional colors for each scheme
		// Parameters:	'color' - name of the color from the scheme that should be used as source for the transformation
		//				'alpha' - to make color transparent (0.0 - 1.0)
		//				'hue', 'saturation', 'brightness' - inc/dec value for each color's component
		crafti_storage_set(
			'scheme_colors_add', array(
				'bg_color_0'        => array(
					'color' => 'bg_color',
					'alpha' => 0,
				),
				'bg_color_02'       => array(
					'color' => 'bg_color',
					'alpha' => 0.2,
				),
				'bg_color_07'       => array(
					'color' => 'bg_color',
					'alpha' => 0.7,
				),
				'bg_color_08'       => array(
					'color' => 'bg_color',
					'alpha' => 0.8,
				),
				'bg_color_09'       => array(
					'color' => 'bg_color',
					'alpha' => 0.9,
				),
				'alter_bg_color_07' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.7,
				),
				'alter_bg_color_04' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.4,
				),
				'alter_bg_color_00' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0,
				),
				'alter_bg_color_02' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.2,
				),
				'alter_bd_color_02' => array(
					'color' => 'alter_bd_color',
					'alpha' => 0.2,
				),
                'alter_dark_015'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.15,
                ),
                'alter_dark_02'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.2,
                ),
                'alter_dark_05'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.5,
                ),
                'alter_dark_08'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.8,
                ),
				'alter_link_02'     => array(
					'color' => 'alter_link',
					'alpha' => 0.2,
				),
				'alter_link_07'     => array(
					'color' => 'alter_link',
					'alpha' => 0.7,
				),
				'extra_bg_color_05' => array(
					'color' => 'extra_bg_color',
					'alpha' => 0.5,
				),
				'extra_bg_color_07' => array(
					'color' => 'extra_bg_color',
					'alpha' => 0.7,
				),
				'extra_link_02'     => array(
					'color' => 'extra_link',
					'alpha' => 0.2,
				),
				'extra_link_07'     => array(
					'color' => 'extra_link',
					'alpha' => 0.7,
				),
                'text_dark_003'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.03,
                ),
                'text_dark_005'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.05,
                ),
                'text_dark_008'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.08,
                ),
				'text_dark_015'      => array(
					'color' => 'text_dark',
					'alpha' => 0.15,
				),
				'text_dark_02'      => array(
					'color' => 'text_dark',
					'alpha' => 0.2,
				),
                'text_dark_03'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.3,
                ),
                'text_dark_05'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.5,
                ),
				'text_dark_07'      => array(
					'color' => 'text_dark',
					'alpha' => 0.7,
				),
                'text_dark_08'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.8,
                ),
                'text_link_007'      => array(
                    'color' => 'text_link',
                    'alpha' => 0.07,
                ),
				'text_link_02'      => array(
					'color' => 'text_link',
					'alpha' => 0.2,
				),
                'text_link_03'      => array(
                    'color' => 'text_link',
                    'alpha' => 0.3,
                ),
				'text_link_04'      => array(
					'color' => 'text_link',
					'alpha' => 0.4,
				),
				'text_link_07'      => array(
					'color' => 'text_link',
					'alpha' => 0.7,
				),
				'text_link2_08'      => array(
                    'color' => 'text_link2',
                    'alpha' => 0.8,
                ),
                'text_link2_007'      => array(
                    'color' => 'text_link2',
                    'alpha' => 0.07,
                ),
				'text_link2_02'      => array(
					'color' => 'text_link2',
					'alpha' => 0.2,
				),
                'text_link2_03'      => array(
                    'color' => 'text_link2',
                    'alpha' => 0.3,
                ),
				'text_link2_05'      => array(
					'color' => 'text_link2',
					'alpha' => 0.5,
				),
                'text_link3_007'      => array(
                    'color' => 'text_link3',
                    'alpha' => 0.07,
                ),
				'text_link3_02'      => array(
					'color' => 'text_link3',
					'alpha' => 0.2,
				),
                'text_link3_03'      => array(
                    'color' => 'text_link3',
                    'alpha' => 0.3,
                ),
                'inverse_text_03'      => array(
                    'color' => 'inverse_text',
                    'alpha' => 0.3,
                ),
                'inverse_link_08'      => array(
                    'color' => 'inverse_link',
                    'alpha' => 0.8,
                ),
                'inverse_hover_08'      => array(
                    'color' => 'inverse_hover',
                    'alpha' => 0.8,
                ),
				'text_dark_blend'   => array(
					'color'      => 'text_dark',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
				'text_link_blend'   => array(
					'color'      => 'text_link',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
				'alter_link_blend'  => array(
					'color'      => 'alter_link',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
			)
		);

		// Simple scheme editor: lists the colors to edit in the "Simple" mode.
		// For each color you can set the array of 'slave' colors and brightness factors that are used to generate new values,
		// when 'main' color is changed
		// Leave 'slave' arrays empty if your scheme does not have a color dependency
		crafti_storage_set(
			'schemes_simple', array(
				'text_link'        => array(),
				'text_hover'       => array(),
				'text_link2'       => array(),
				'text_hover2'      => array(),
				'text_link3'       => array(),
				'text_hover3'      => array(),
				'alter_link'       => array(),
				'alter_hover'      => array(),
				'alter_link2'      => array(),
				'alter_hover2'     => array(),
				'alter_link3'      => array(),
				'alter_hover3'     => array(),
				'extra_link'       => array(),
				'extra_hover'      => array(),
				'extra_link2'      => array(),
				'extra_hover2'     => array(),
				'extra_link3'      => array(),
				'extra_hover3'     => array(),
			)
		);

		// Parameters to set order of schemes in the css
		crafti_storage_set(
			'schemes_sorted', array(
				'color_scheme',
				'header_scheme',
				'menu_scheme',
				'sidebar_scheme',
				'footer_scheme',
			)
		);

		// Color presets
		crafti_storage_set(
			'color_presets', array(
				'autumn' => array(
								'title'  => esc_html__( 'Autumn', 'crafti' ),
								'colors' => array(
												'default' => array(
																	'text_link'  => '#d83938',
																	'text_hover' => '#f2b232',
																	),
												'dark' => array(
																	'text_link'  => '#d83938',
																	'text_hover' => '#f2b232',
																	)
												)
							),
				'green' => array(
								'title'  => esc_html__( 'Natural Green', 'crafti' ),
								'colors' => array(
												'default' => array(
																	'text_link'  => '#75ac78',
																	'text_hover' => '#378e6d',
																	),
												'dark' => array(
																	'text_link'  => '#75ac78',
																	'text_hover' => '#378e6d',
																	)
												)
							),
			)
		);
	}
}


// Enqueue clone specific style
if ( ! function_exists( 'crafti_clone_frontend_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'crafti_clone_frontend_scripts', 2300 );
	function crafti_clone_frontend_scripts() {
		$crafti_url = crafti_get_file_url( crafti_skins_get_current_skin_dir() . 'extra-style.css' );
		if ( '' != $crafti_url ) {
			wp_enqueue_style( 'crafti-extra-skin-' . esc_attr( crafti_skins_get_current_skin_name() ), $crafti_url, array(), null );
		}
	}
}

// Activation methods
if ( ! function_exists( 'crafti_skin_filter_activation_methods2' ) ) {
    add_filter( 'trx_addons_filter_activation_methods', 'crafti_skin_filter_activation_methods2', 11, 1 );
    function crafti_skin_filter_activation_methods2( $args ) {
        $args['elements_key'] = true;
        return $args;
    }
}