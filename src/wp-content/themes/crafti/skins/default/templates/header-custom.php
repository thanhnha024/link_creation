<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.06
 */

$crafti_header_css   = '';
$crafti_header_image = get_header_image();
$crafti_header_video = crafti_get_header_video();
if ( ! empty( $crafti_header_image ) && crafti_trx_addons_featured_image_override( is_singular() || crafti_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$crafti_header_image = crafti_get_current_mode_image( $crafti_header_image );
}

$crafti_header_id = crafti_get_custom_header_id();
$crafti_header_meta = get_post_meta( $crafti_header_id, 'trx_addons_options', true );
if ( ! empty( $crafti_header_meta['margin'] ) ) {
	crafti_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( crafti_prepare_css_value( $crafti_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $crafti_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $crafti_header_id ) ) ); ?>
				<?php
				echo ! empty( $crafti_header_image ) || ! empty( $crafti_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
				if ( '' != $crafti_header_video ) {
					echo ' with_bg_video';
				}
				if ( '' != $crafti_header_image ) {
					echo ' ' . esc_attr( crafti_add_inline_css_class( 'background-image: url(' . esc_url( $crafti_header_image ) . ');' ) );
				}
				if ( is_single() && has_post_thumbnail() ) {
					echo ' with_featured_image';
				}
				if ( crafti_is_on( crafti_get_theme_option( 'header_fullheight' ) ) ) {
					echo ' header_fullheight crafti-full-height';
				}
				$crafti_header_scheme = crafti_get_theme_option( 'header_scheme' );
				if ( ! empty( $crafti_header_scheme ) && ! crafti_is_inherit( $crafti_header_scheme  ) ) {
					echo ' scheme_' . esc_attr( $crafti_header_scheme );
				}
				?>
">
	<?php

	// Background video
	if ( ! empty( $crafti_header_video ) ) {
		get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/header-video' ) );
	}

	// Custom header's layout
	do_action( 'crafti_action_show_layout', $crafti_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
