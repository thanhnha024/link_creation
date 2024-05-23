<?php
/**
 * The template to display the background video in the header
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.14
 */
$crafti_header_video = crafti_get_header_video();
$crafti_embed_video  = '';
if ( ! empty( $crafti_header_video ) && ! crafti_is_from_uploads( $crafti_header_video ) ) {
	if ( crafti_is_youtube_url( $crafti_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $crafti_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php crafti_show_layout( crafti_get_embed_video( $crafti_header_video ) ); ?></div>
		<?php
	}
}
