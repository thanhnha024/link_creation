<?php
/**
 * Plugin Name:       Advanced Popups
 * Description:       Display high-converting newsletter popups, a cookie notice, or a notification with the light-weight yet feature-rich plugin.
 * Version:           1.1.9
 * Author:            Code Supply Co.
 * Author URI:        https://codesupply.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-popups
 * Domain Path:       /languages
 *
 * @link              https://codesupply.co
 * @package           ADP
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Variables
 */
define( 'ADP_URL', plugin_dir_url( __FILE__ ) );
define( 'ADP_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin Activation.
 */
function adp_activation() {
	do_action( 'adp_activation' );
}
register_activation_hook( __FILE__, 'adp_activation' );

/**
 * Plugin Deactivation.
 */
function adp_deactivation() {
	do_action( 'adp_deactivation' );
}
register_deactivation_hook( __FILE__, 'adp_deactivation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-advanced-popups.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function adp_init() {

	$plugin = new ADP();
	$plugin->run();

}
adp_init();
