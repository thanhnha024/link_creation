<?php
/**
 * The template to display the site logo in the footer
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.10
 */

// Logo
if ( crafti_is_on( crafti_get_theme_option( 'logo_in_footer' ) ) ) {
	$crafti_logo_image = crafti_get_logo_image( 'footer' );
	$crafti_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $crafti_logo_image['logo'] ) || ! empty( $crafti_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $crafti_logo_image['logo'] ) ) {
					$crafti_attr = crafti_getimagesize( $crafti_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $crafti_logo_image['logo'] ) . '"'
								. ( ! empty( $crafti_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $crafti_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'crafti' ) . '"'
								. ( ! empty( $crafti_attr[3] ) ? ' ' . wp_kses_data( $crafti_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $crafti_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $crafti_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
