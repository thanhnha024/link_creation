<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

$crafti_args = get_query_var( 'crafti_logo_args' );

// Site logo
$crafti_logo_type   = isset( $crafti_args['type'] ) ? $crafti_args['type'] : '';
$crafti_logo_image  = crafti_get_logo_image( $crafti_logo_type );
$crafti_logo_text   = crafti_is_on( crafti_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$crafti_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $crafti_logo_image['logo'] ) || ! empty( $crafti_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $crafti_logo_image['logo'] ) ) {
			if ( empty( $crafti_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($crafti_logo_image['logo']) && (int) $crafti_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$crafti_attr = crafti_getimagesize( $crafti_logo_image['logo'] );
				echo '<img src="' . esc_url( $crafti_logo_image['logo'] ) . '"'
						. ( ! empty( $crafti_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $crafti_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $crafti_logo_text ) . '"'
						. ( ! empty( $crafti_attr[3] ) ? ' ' . wp_kses_data( $crafti_attr[3] ) : '' )
						. '>';
			}
		} else {
			crafti_show_layout( crafti_prepare_macros( $crafti_logo_text ), '<span class="logo_text">', '</span>' );
			crafti_show_layout( crafti_prepare_macros( $crafti_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
