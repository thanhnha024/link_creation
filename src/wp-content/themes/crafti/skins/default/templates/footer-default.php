<?php
/**
 * The template to display default site footer
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$crafti_footer_scheme = crafti_get_theme_option( 'footer_scheme' );
if ( ! empty( $crafti_footer_scheme ) && ! crafti_is_inherit( $crafti_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $crafti_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
