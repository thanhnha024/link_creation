<?php
/**
 * Helpers Advanced Popups
 *
 * @package    ADP
 * @subpackage ADP/includes
 */

/**
 * Processing path of style.
 *
 * @param string $path URL to the stylesheet.
 */
function adp_style( $path ) {
	// Check RTL.
	if ( is_rtl() ) {
		return $path;
	}

	// Check Dev.
	$dev = ADP_PATH . 'public/css/advanced-popups-public-dev.css';

	if ( file_exists( $dev ) ) {
		return str_replace( '.css', '-dev.css', $path );
	}

	return $path;
}

/**
 * Retrieves a post meta field for the given post ID.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Optional. The meta key to retrieve. By default, returns
 *                        data for all keys. Default empty.
 * @param bool   $single  Optional. If true, returns only the first value for the specified meta key.
 *                        This parameter has no effect if $key is not specified. Default false.
 * @param mixed  $default Default value.
 * @return mixed Will be an array if $single is false. Will be value of the meta
 *               field if $single is true.
 */
function adp_get_post_meta( $post_id, $key = '', $single = false, $default = null ) {

	if ( ! metadata_exists( 'post', $post_id, $key ) && $default ) {
		return $default;
	}

	return get_metadata( 'post', $post_id, $key, $single );
}

/**
 * Checks whether a popup should be displayed or not
 *
 * @param int $popup_id The popup Id.
 */
function adp_is_popup_visible( $popup_id ) {

	$visible = true;

	// Verify rules.
	$popup_rules_mode = adp_get_post_meta( $popup_id, '_adp_popup_rules_mode', true, 'all' );

	if ( true === $visible && 'specific' === $popup_rules_mode ) {
		$popup_rules = adp_get_post_meta( $popup_id, '_adp_popup_rules', true, array() );

		$visible = ADP_Popup_Rules::instance()->is_check( $popup_rules );
	}

	// Has user seen this popup before?
	$popup_limit_display = adp_get_post_meta( $popup_id, '_adp_popup_limit_display', true, 1 );

	if ( true === $visible && $popup_limit_display && isset( $_COOKIE[ "adp-popup-{$popup_id}" ] ) && $_COOKIE[ "adp-popup-{$popup_id}" ] >= $popup_limit_display ) {
		$visible = false;
	}

	// Guests or Logged-in.
	if ( true === $visible ) {
		$popup_show_to = adp_get_post_meta( $popup_id, '_adp_popup_show_to', true, 'both' );

		$visible = ! ( ( 'guest' === $popup_show_to && is_user_logged_in() ) || ( 'user' === $popup_show_to && ! is_user_logged_in() ) );
	}

	// Apply a final filter to determine visibility.
	$visible = apply_filters( 'advanced_popups_is_popup_visible', $visible, $popup_id );

	return $visible;
}
