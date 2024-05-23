<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ADP
 * @subpackage ADP/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    ADP
 * @subpackage ADP/includes
 */
class ADP_i18n {


	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( 'advanced-popups', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}
}
