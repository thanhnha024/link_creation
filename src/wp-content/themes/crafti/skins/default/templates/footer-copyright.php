<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$crafti_copyright_scheme = crafti_get_theme_option( 'copyright_scheme' );
if ( ! empty( $crafti_copyright_scheme ) && ! crafti_is_inherit( $crafti_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $crafti_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$crafti_copyright = crafti_get_theme_option( 'copyright' );
			if ( ! empty( $crafti_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$crafti_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $crafti_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$crafti_copyright = crafti_prepare_macros( $crafti_copyright );
				// Display copyright
				echo wp_kses( nl2br( $crafti_copyright ), 'crafti_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
