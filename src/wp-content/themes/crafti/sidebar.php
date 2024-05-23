<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

if ( crafti_sidebar_present() ) {
	
	$crafti_sidebar_type = crafti_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $crafti_sidebar_type && ! crafti_is_layouts_available() ) {
		$crafti_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $crafti_sidebar_type ) {
		// Default sidebar with widgets
		$crafti_sidebar_name = crafti_get_theme_option( 'sidebar_widgets' );
		crafti_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $crafti_sidebar_name ) ) {
			dynamic_sidebar( $crafti_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$crafti_sidebar_id = crafti_get_custom_sidebar_id();
		do_action( 'crafti_action_show_layout', $crafti_sidebar_id );
	}
	$crafti_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $crafti_out ) ) {
		$crafti_sidebar_position    = crafti_get_theme_option( 'sidebar_position' );
		$crafti_sidebar_position_ss = crafti_get_theme_option( 'sidebar_position_ss' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $crafti_sidebar_position );
			echo ' sidebar_' . esc_attr( $crafti_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $crafti_sidebar_type );

			$crafti_sidebar_scheme = apply_filters( 'crafti_filter_sidebar_scheme', crafti_get_theme_option( 'sidebar_scheme' ) );
			if ( ! empty( $crafti_sidebar_scheme ) && ! crafti_is_inherit( $crafti_sidebar_scheme ) && 'custom' != $crafti_sidebar_type ) {
				echo ' scheme_' . esc_attr( $crafti_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="crafti_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'crafti_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $crafti_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$crafti_title = apply_filters( 'crafti_filter_sidebar_control_title', 'float' == $crafti_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'crafti' ) : '' );
				$crafti_text  = apply_filters( 'crafti_filter_sidebar_control_text', 'above' == $crafti_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'crafti' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $crafti_title ); ?>"><?php echo esc_html( $crafti_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'crafti_action_before_sidebar', 'sidebar' );
				crafti_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $crafti_out ) );
				do_action( 'crafti_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'crafti_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
