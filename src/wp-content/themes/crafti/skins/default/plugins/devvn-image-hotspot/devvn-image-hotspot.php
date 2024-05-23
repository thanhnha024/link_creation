<?php
/* Image Hotspot by DevVN support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'crafti_devvn_image_hotspot_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'crafti_devvn_image_hotspot_theme_setup9', 9 );
	function crafti_devvn_image_hotspot_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'crafti_filter_tgmpa_required_plugins', 'crafti_devvn_image_hotspot_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'crafti_devvn_image_hotspot_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('crafti_filter_tgmpa_required_plugins',	'crafti_devvn_image_hotspot_tgmpa_required_plugins');
	function crafti_devvn_image_hotspot_tgmpa_required_plugins( $list = array() ) {
		if ( crafti_storage_isset( 'required_plugins', 'devvn-image-hotspot' ) && crafti_storage_get_array( 'required_plugins', 'devvn-image-hotspot', 'install' ) !== false ) {
			$list[] = array(
				'name'     => crafti_storage_get_array( 'required_plugins', 'devvn-image-hotspot', 'title' ),
				'slug'     => 'devvn-image-hotspot',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'crafti_exists_devvn_image_hotspot' ) ) {
	function crafti_exists_devvn_image_hotspot() {
        return defined( 'DEVVN_IHOTSPOT_DEV_MOD' );
	}
}
