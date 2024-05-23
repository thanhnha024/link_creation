<?php
/**
 * Override Theme Options on a posts and pages
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.29
 */


// -----------------------------------------------------------------
// -- Override Theme Options
// -----------------------------------------------------------------

if ( ! function_exists( 'crafti_options_override_init' ) ) {
	add_action( 'after_setup_theme', 'crafti_options_override_init' );
	function crafti_options_override_init() {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', 'crafti_options_override_add_scripts' );
			add_action( 'save_post', 'crafti_options_override_save_options' );
			add_filter( 'crafti_filter_override_options', 'crafti_options_override_add_options' );
		}
	}
}


// Check if override options is allowed for specified post type
if ( ! function_exists( 'crafti_options_allow_override' ) ) {
	function crafti_options_allow_override( $post_type ) {
		return apply_filters( 'crafti_filter_allow_override_options', in_array( $post_type, array( 'page', 'post' ) ), $post_type );
	}
}

// Load required styles and scripts for admin mode
if ( ! function_exists( 'crafti_options_override_add_scripts' ) ) {
	//Handler of the add_action("admin_enqueue_scripts", 'crafti_options_override_add_scripts');
	function crafti_options_override_add_scripts() {
		// If current screen is 'Edit Page' - load font icons
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		if ( is_object( $screen ) && crafti_options_allow_override( ! empty( $screen->post_type ) ? $screen->post_type : $screen->id ) ) {
			wp_enqueue_style( 'crafti-fontello', crafti_get_file_url( 'css/font-icons/css/fontello.css' ), array(), null );
			wp_enqueue_script( 'jquery-ui-tabs', false, array( 'jquery', 'jquery-ui-core' ), null, true );
			wp_enqueue_script( 'jquery-ui-accordion', false, array( 'jquery', 'jquery-ui-core' ), null, true );
			wp_enqueue_script( 'crafti-options', crafti_get_file_url( 'theme-options/theme-options.js' ), array( 'jquery' ), null, true );
			wp_localize_script( 'crafti-options', 'crafti_dependencies', crafti_get_theme_dependencies() );
		}
	}
}

// Add overriden options
if ( ! function_exists( 'crafti_options_override_add_options' ) ) {
	//Handler of the add_filter('crafti_filter_override_options', 'crafti_options_override_add_options');
	function crafti_options_override_add_options( $list ) {
		global $post_type;
		if ( crafti_options_allow_override( $post_type ) ) {
			$list[] = array(
				sprintf( 'crafti_override_options_%s', $post_type ),
				esc_html__( 'Theme Options', 'crafti' ),
				'crafti_options_override_show',
				$post_type,
				'advanced',
				'default',
			);
		}
		return $list;
	}
}

// Callback function to show override options
if ( ! function_exists( 'crafti_options_override_show' ) ) {
	function crafti_options_override_show( $post = false, $args = false ) {
		if ( empty( $post ) || ! is_object( $post ) || empty( $post->ID ) ) {
			global $post, $post_type;
			$mb_post_id   = $post->ID;
			$mb_post_type = $post_type;
		} else {
			$mb_post_id   = $post->ID;
			$mb_post_type = $post->post_type;
		}
		if ( crafti_options_allow_override( $mb_post_type ) ) {
			// Load saved options
			$meta         = get_post_meta( $mb_post_id, 'crafti_options', true );
			$tabs_titles  = array();
			$tabs_content = array();
			global $CRAFTI_STORAGE;
			// Refresh linked data if this field is controller for the another (linked) field
			// Do this before show fields to refresh data in the $CRAFTI_STORAGE
			foreach ( $CRAFTI_STORAGE['options'] as $k => $v ) {
				if ( ! isset( $v['override'] ) || strpos( $v['override']['mode'], $mb_post_type ) === false ) {
					continue;
				}
				if ( ! empty( $v['linked'] ) ) {
					$v['val'] = isset( $meta[ $k ] ) ? $meta[ $k ] : 'inherit';
					if ( ! empty( $v['val'] ) && ! crafti_is_inherit( $v['val'] ) ) {
						crafti_refresh_linked_data( $v['val'], $v['linked'] );
					}
				}
			}
			// Show fields
			foreach ( $CRAFTI_STORAGE['options'] as $k => $v ) {
				if ( ! isset( $v['override'] ) || strpos( $v['override']['mode'], $mb_post_type ) === false || 'hidden' == $v['type'] ) {
					continue;
				}
				if ( empty( $v['override']['section'] ) ) {
					$v['override']['section'] = esc_html__( 'General', 'crafti' );
				}
				if ( ! isset( $tabs_titles[ $v['override']['section'] ] ) ) {
					$tabs_titles[ $v['override']['section'] ]  = $v['override']['section'];
					$tabs_content[ $v['override']['section'] ] = '';
				}
				$v['val'] = isset( $meta[ $k ] ) ? $meta[ $k ] : 'inherit';
				if ( 'group' == $v['type'] ) {
					// Fields set (group)
					if ( count( $v['fields'] ) > 0 ) {
						$tabs_content[ $v['override']['section'] ] .= crafti_options_show_group( $k, $v, $mb_post_type );
					}
				} else {
					// Regular field
					$tabs_content[ $v['override']['section'] ] .= crafti_options_show_field( $k, $v, $mb_post_type );
				}
			}

			// Display options
			if ( count( $tabs_titles ) > 0 ) {
				// Add Options presets
				$tabs_titles[ 'presets' ]  = esc_html__( 'Options presets', 'crafti' );
				$tabs_content[ 'presets' ] = crafti_options_show_field( 'presets', array(
												'title' => esc_html__( 'Options Presets', 'crafti' ),
												'desc'  => esc_html__( 'Select a preset to override options of the current page or save current options as a new preset', 'crafti' ),
												'type'  => 'presets',
											), $mb_post_type );
				?>
				<div class="crafti_options crafti_options_override">
					<input type="hidden" name="override_options_nonce" value="<?php echo esc_attr( wp_create_nonce( admin_url() ) ); ?>" />
					<input type="hidden" name="override_options_post_type" value="<?php echo esc_attr( $mb_post_type ); ?>" />
					<div id="crafti_options_tabs" class="crafti_tabs crafti_tabs_vertical">
						<ul>
							<?php
							$cnt = 0;
							foreach ( $tabs_titles as $k => $v ) {
								$cnt++;
								?>
								<li><a href="#crafti_options_<?php echo esc_attr( $cnt ); ?>"><?php echo esc_html( $v ); ?></a></li>
								<?php
							}
							?>
						</ul>
						<?php
						$cnt = 0;
						foreach ( $tabs_content as $k => $v ) {
							$cnt++;
							?>
							<div id="crafti_options_<?php echo esc_attr( $cnt ); ?>" class="crafti_tabs_section crafti_options_section">
								<?php crafti_show_layout( $v ); ?>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
		}
	}
}


// Save overriden options
if ( ! function_exists( 'crafti_options_override_save_options' ) ) {
	//Handler of the add_action('save_post', 'crafti_options_override_save_options');
	function crafti_options_override_save_options( $post_id ) {
		// verify nonce
		if ( ! wp_verify_nonce( crafti_get_value_gp( 'override_options_nonce' ), admin_url() ) ) {
			return $post_id;
		}

		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$post_type = wp_kses_data( wp_unslash( isset( $_POST['override_options_post_type'] ) ? $_POST['override_options_post_type'] : $_POST['post_type'] ) );

		// Check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type ), 'objects' );
		if ( ! empty( $post_types ) && is_array( $post_types ) ) {
			foreach ( $post_types  as $type ) {
				$capability = $type->capability_type;
				break;
			}
		}
		if ( ! current_user_can( 'edit_' . ( $capability ), $post_id ) ) {
			return $post_id;
		}

		// Save options
		$meta    = array();
		$options = crafti_storage_get( 'options' );
		foreach ( $options as $k => $v ) {
			// Skip not overriden options
			if ( ! isset( $v['override'] ) || strpos( $v['override']['mode'], $post_type ) === false ) {
				continue;
			}
			// Skip inherited options
			if ( ! empty( $_POST[ "crafti_options_inherit_{$k}" ] ) ) {
				continue;
			}
			// Skip hidden options
			if ( ! isset( $_POST[ "crafti_options_field_{$k}" ] ) && 'hidden' == $v['type'] ) {
				continue;
			}
			// Get option value from POST
			$meta[ $k ] = isset( $_POST[ "crafti_options_field_{$k}" ] )
							? crafti_get_value_gp( "crafti_options_field_{$k}" )
							: ( 'checkbox' == $v['type'] ? 0 : '' );
		}
		$meta = apply_filters( 'crafti_filter_update_post_options', $meta, $post_id, $post_type );

		update_post_meta( $post_id, 'crafti_options', $meta );

		// Save separate meta options to search template pages
		if ( 'page' == $post_type ) {
			$page_template = isset( $_POST['page_template'] )
								? $_POST['page_template']
								: get_post_meta( $post_id, '_wp_page_template', true );
			if ( 'blog.php' == $page_template ) {
				update_post_meta( $post_id, 'crafti_options_post_type', isset( $meta['post_type'] ) ? $meta['post_type'] : 'post' );
				update_post_meta( $post_id, 'crafti_options_parent_cat', isset( $meta['parent_cat'] ) ? $meta['parent_cat'] : 0 );
			}
		}
	}
}


//------------------------------------------------------
// Extra column for posts/pages lists
// with overriden options
//------------------------------------------------------

// Create additional column
if ( ! function_exists( 'crafti_add_options_column' ) ) {
	add_filter( 'manage_edit-post_columns', 'crafti_add_options_column', 9 );
	add_filter( 'manage_edit-page_columns', 'crafti_add_options_column', 9 );
	function crafti_add_options_column( $columns ) {
		$columns['theme_options'] = esc_html__( 'Theme Options', 'crafti' );
		return $columns;
	}
}

// Fill column with data
if ( ! function_exists( 'crafti_fill_options_column' ) ) {
	add_filter( 'manage_post_posts_custom_column', 'crafti_fill_options_column', 9, 2 );
	add_filter( 'manage_page_posts_custom_column', 'crafti_fill_options_column', 9, 2 );
	function crafti_fill_options_column( $column_name = '', $post_id = 0 ) {
		if ( 'theme_options' != $column_name ) {
			return;
		}
		$options = '';
		$props = get_post_meta( $post_id, 'crafti_options', true);
		if ( $props ) {
			if ( is_array( $props ) && count( $props ) > 0 ) {
				foreach ( $props as $prop_name => $prop_value ) {
					if ( ! crafti_is_inherit( $prop_value ) && crafti_storage_get_array( 'options', $prop_name, 'type' ) != 'hidden' ) {
						$prop_title = crafti_storage_get_array( 'options', $prop_name, 'title' );
						if ( empty( $prop_title ) ) {
							$prop_title = $prop_name;
						}
						$options .= '<div class="crafti_options_prop_row">'
										. '<span class="crafti_options_prop_name">' . esc_html( $prop_title ) . '</span>'
										. '&nbsp;=&nbsp;'
										. '<span class="crafti_options_prop_value">'
											. ( is_array( $prop_value )
												? esc_html__('[Complex Data]', 'crafti')
												: '"' . esc_html( crafti_strshort( $prop_value, 80 ) ) . '"'
												)
										. '</span>'
									. '</div>';
					}
				}
			}
		}
		crafti_show_layout( $options, '<div class="crafti_options_list">', '</div>' );
	}
}

// Display 'Blog archive' as post state
if ( ! function_exists( 'crafti_display_post_states' ) ) {
	add_filter( 'display_post_states', 'crafti_display_post_states', 9, 2 );
	function crafti_display_post_states( $post_states, $post ) {
		if ( is_object( $post ) && ! empty( $post->post_type ) && 'page' == $post->post_type ) {
			if ( get_post_meta( $post->ID, '_wp_page_template', true ) == 'blog.php' ) {
				$props = get_post_meta( $post->ID, 'crafti_options', true);
				$post_type_and_cat = '';
				if ( empty( $props['post_type'] ) ) {
					if ( ! is_array( $props ) ) {
						$props = array();
					}
					$props['post_type'] = 'post';
				}
				$post_obj = get_post_type_object( $props['post_type'] );
				$post_type_and_cat = is_object( $post_obj )
										? $post_obj->labels->name
										: $props['post_type'];
				if ( ! empty( $props['parent_cat'] ) ) {
					$term = get_term_by( 'id', $props['parent_cat'], crafti_get_post_type_taxonomy( $props['post_type'] ), OBJECT );
					if ( $term ) {
						$post_type_and_cat .= ' -> ' . $term->name;
					}
				}
				$post_states[] = ! empty( $post_type_and_cat )
									// Translators: Add post type and category to the page state
									? sprintf( esc_html__( 'Blog archive for "%s"', 'crafti' ), $post_type_and_cat )
									: esc_html__( 'Blog archive', 'crafti' );
			}
		}
		return $post_states;
	}
}


//------------------------------------------------------
// Options presets
//------------------------------------------------------

// AJAX: Add a new preset
if ( ! function_exists( 'crafti_callback_add_options_preset' ) ) {
	add_action( 'wp_ajax_crafti_add_options_preset', 'crafti_callback_add_options_preset' );
	function crafti_callback_add_options_preset() {
		crafti_verify_nonce();
		if ( ! current_user_can( 'manage_options' ) ) {
			crafti_forbidden( esc_html__( 'Sorry, you are not allowed to manage options.', 'crafti' ) );
		}
		$response  = array( 'error' => '', 'success' => '' );
		if ( ! empty( $_REQUEST['preset_name'] ) && ! empty( $_REQUEST['preset_data'] ) ) {
			$preset_name = wp_kses_data( wp_unslash( $_REQUEST['preset_name'] ) );
			$preset_data = wp_kses_data( wp_unslash( $_REQUEST['preset_data'] ) );
			$preset_type = wp_kses_data( wp_unslash( $_REQUEST['preset_type'] ) );
			if ( empty( $preset_type ) ) {
				$preset_type = '#';
			}
			$presets = get_option( 'crafti_options_presets' );
			if ( empty( $presets ) || ! is_array( $presets ) ) {
				$presets = array();
			}
			if ( empty( $presets[ $preset_type ] ) || ! is_array( $presets[ $preset_type ] ) ) {
				$presets[ $preset_type ] = array();
			}
			$presets[ $preset_type ][ $preset_name ] = $preset_data;
			update_option( 'crafti_options_presets', $presets );
			// Translators: Add preset name to the message
			$response['success'] = esc_html( sprintf( __( 'Preset "%s" is added!', 'crafti' ), $preset_name ) );
		} else {
			$response['error'] = esc_html__( 'Wrong preset name or options data is received! Preset is not added!', 'crafti' );
		}
		crafti_ajax_response( $response );
	}
}

// AJAX: Delete a new preset
if ( ! function_exists( 'crafti_callback_delete_options_preset' ) ) {
	add_action( 'wp_ajax_crafti_delete_options_preset', 'crafti_callback_delete_options_preset' );
	function crafti_callback_delete_options_preset() {
		crafti_verify_nonce();
		if ( ! current_user_can( 'manage_options' ) ) {
			crafti_forbidden( esc_html__( 'Sorry, you are not allowed to manage options.', 'crafti' ) );
		}
		$response  = array( 'error' => '', 'success' => '' );
		if ( ! empty( $_REQUEST['preset_name'] ) ) {
			$preset_name = wp_kses_data( wp_unslash( $_REQUEST['preset_name'] ) );
			$preset_type = wp_kses_data( wp_unslash( $_REQUEST['preset_type'] ) );
			if ( empty( $preset_type ) ) {
				$preset_type = '#';
			}
			$presets = get_option( 'crafti_options_presets' );
			if ( isset( $presets[ $preset_type ][ $preset_name ] ) ) {
				unset( $presets[ $preset_type ][ $preset_name ] );
				update_option( 'crafti_options_presets', $presets );
			}
			// Translators: Add preset name to the message
			$response['success'] = esc_html( sprintf( __( 'Preset "%s" is deleted!', 'crafti' ), $preset_name ) );
		} else {
			$response['error'] = esc_html__( 'Wrong preset name is received! Preset is not deleted!', 'crafti' );
		}
		crafti_ajax_response( $response );
	}
}
