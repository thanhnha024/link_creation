<?php
/**
 * The template to display the socials in the footer
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.10
 */


// Socials
if ( crafti_is_on( crafti_get_theme_option( 'socials_in_footer' ) ) ) {
	$crafti_output = crafti_get_socials_links();
	if ( '' != $crafti_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php crafti_show_layout( $crafti_output ); ?>
			</div>
		</div>
		<?php
	}
}
