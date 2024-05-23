<?php
/**
 * The template to display default site footer
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.10
 */

$crafti_footer_id = crafti_get_custom_footer_id();
$crafti_footer_meta = get_post_meta( $crafti_footer_id, 'trx_addons_options', true );
if ( ! empty( $crafti_footer_meta['margin'] ) ) {
	crafti_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( crafti_prepare_css_value( $crafti_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $crafti_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $crafti_footer_id ) ) ); ?>
						<?php
						$crafti_footer_scheme = crafti_get_theme_option( 'footer_scheme' );
						if ( ! empty( $crafti_footer_scheme ) && ! crafti_is_inherit( $crafti_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $crafti_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'crafti_action_show_layout', $crafti_footer_id );
	?>
</footer><!-- /.footer_wrap -->
