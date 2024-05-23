<?php
/**
 * The Header: Logo and main menu
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( crafti_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'crafti_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'crafti_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('crafti_action_body_wrap_attributes'); ?>>

		<?php do_action( 'crafti_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'crafti_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('crafti_action_page_wrap_attributes'); ?>>

			<?php do_action( 'crafti_action_page_wrap_start' ); ?>

			<?php
			$crafti_full_post_loading = ( crafti_is_singular( 'post' ) || crafti_is_singular( 'attachment' ) ) && crafti_get_value_gp( 'action' ) == 'full_post_loading';
			$crafti_prev_post_loading = ( crafti_is_singular( 'post' ) || crafti_is_singular( 'attachment' ) ) && crafti_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $crafti_full_post_loading && ! $crafti_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="crafti_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'crafti_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'crafti' ); ?></a>
				<?php if ( crafti_sidebar_present() ) { ?>
				<a class="crafti_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'crafti_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'crafti' ); ?></a>
				<?php } ?>
				<a class="crafti_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'crafti_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'crafti' ); ?></a>

				<?php
				do_action( 'crafti_action_before_header' );

				// Header
				$crafti_header_type = crafti_get_theme_option( 'header_type' );
				if ( 'custom' == $crafti_header_type && ! crafti_is_layouts_available() ) {
					$crafti_header_type = 'default';
				}
				get_template_part( apply_filters( 'crafti_filter_get_template_part', "templates/header-" . sanitize_file_name( $crafti_header_type ) ) );

				// Side menu
				if ( in_array( crafti_get_theme_option( 'menu_side' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/header-navi-mobile' ) );

				do_action( 'crafti_action_after_header' );

			}
			?>

			<?php do_action( 'crafti_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( crafti_is_off( crafti_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $crafti_header_type ) ) {
						$crafti_header_type = crafti_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $crafti_header_type && crafti_is_layouts_available() ) {
						$crafti_header_id = crafti_get_custom_header_id();
						if ( $crafti_header_id > 0 ) {
							$crafti_header_meta = crafti_get_custom_layout_meta( $crafti_header_id );
							if ( ! empty( $crafti_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$crafti_footer_type = crafti_get_theme_option( 'footer_type' );
					if ( 'custom' == $crafti_footer_type && crafti_is_layouts_available() ) {
						$crafti_footer_id = crafti_get_custom_footer_id();
						if ( $crafti_footer_id ) {
							$crafti_footer_meta = crafti_get_custom_layout_meta( $crafti_footer_id );
							if ( ! empty( $crafti_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'crafti_action_page_content_wrap_class', $crafti_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'crafti_filter_is_prev_post_loading', $crafti_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( crafti_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'crafti_action_page_content_wrap_data', $crafti_prev_post_loading );
			?>>
				<?php
				do_action( 'crafti_action_page_content_wrap', $crafti_full_post_loading || $crafti_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'crafti_filter_single_post_header', crafti_is_singular( 'post' ) || crafti_is_singular( 'attachment' ) ) ) {
					if ( $crafti_prev_post_loading ) {
						if ( crafti_get_theme_option( 'posts_navigation_scroll_which_block' ) != 'article' ) {
							do_action( 'crafti_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$crafti_path = apply_filters( 'crafti_filter_get_template_part', 'templates/single-styles/' . crafti_get_theme_option( 'single_style' ) );
					if ( crafti_get_file_dir( $crafti_path . '.php' ) != '' ) {
						get_template_part( $crafti_path );
					}
				}

				// Widgets area above page
				$crafti_body_style   = crafti_get_theme_option( 'body_style' );
				$crafti_widgets_name = crafti_get_theme_option( 'widgets_above_page' );
				$crafti_show_widgets = ! crafti_is_off( $crafti_widgets_name ) && is_active_sidebar( $crafti_widgets_name );
				if ( $crafti_show_widgets ) {
					if ( 'fullscreen' != $crafti_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					crafti_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $crafti_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'crafti_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $crafti_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'crafti_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'crafti_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="crafti_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( crafti_is_singular( 'post' ) || crafti_is_singular( 'attachment' ) )
							&& $crafti_prev_post_loading 
							&& crafti_get_theme_option( 'posts_navigation_scroll_which_block' ) == 'article'
						) {
							do_action( 'crafti_action_between_posts' );
						}

						// Widgets area above content
						crafti_create_widgets_area( 'widgets_above_content' );

						do_action( 'crafti_action_page_content_start_text' );
