<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ADP
 * @subpackage ADP/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    ADP
 * @subpackage ADP/includes
 */
class ADP {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access protected
	 * @var ADP_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access protected
	 * @var string $adp The string used to uniquely identify this plugin.
	 */
	protected $adp;

	/**
	 * The current version of the plugin.
	 *
	 * @access protected
	 * @var string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */
	public function __construct() {

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Get plugin data.
		$plugin_data = get_plugin_data( ADP_PATH . '/advanced-popups.php' );

		$this->version = $plugin_data['Version'];
		$this->adp     = 'advanced-popups';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - ADP_Loader. Orchestrates the hooks of the plugin.
	 * - ADP_i18n. Defines internationalization functionality.
	 * - ADP_Admin. Defines all hooks for the admin area.
	 * - ADP_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers-advanced-popups.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-popups-rules.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-popups-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-popups-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-advanced-popups-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-advanced-popups-public.php';

		$this->loader = new ADP_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the ADP_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access private
	 */
	private function set_locale() {

		$plugin_i18n = new ADP_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new ADP_Admin( $this->get_adp(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_admin, 'register_post_type' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'metabox_popup_register' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'metabox_popup_save', 10, 2 );
		$this->loader->add_action( 'wp_ajax_nopriv_adp_popup_rules_objects', $plugin_admin, 'ajax_rules_objects' );
		$this->loader->add_action( 'wp_ajax_adp_popup_rules_objects', $plugin_admin, 'ajax_rules_objects' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'admin_enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access private
	 */
	private function define_public_hooks() {

		$plugin_public = new ADP_Public( $this->get_adp(), $this->get_version() );

		$this->loader->add_action( 'wp_head', $plugin_public, 'wp_head' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'wp_footer' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wp_enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return string The name of the plugin.
	 */
	public function get_adp() {
		return $this->adp;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return ADP_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
