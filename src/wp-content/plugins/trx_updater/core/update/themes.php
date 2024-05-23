<?php
namespace TrxUpdater\Core\Update;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Themes extends Base {

	/**
	 * Theme info from the upgrade server
	 *
	 * Info from the upgrade server about active theme
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var theme
	 */
	private $theme;

	/**
	 * Theme parts to save while upgrade
	 *
	 * Save theme parts before upgrade
	 *
	 * @since 1.4.1
	 * @access private
	 *
	 * @var theme_parts
	 */
	private $theme_parts;

	/**
	 * List of supported multiskin themes
	 *
	 * List of multiskin themes which need to save skins before upgrade
	 *
	 * @since 1.4.1
	 * @access private
	 *
	 * @var multiskin_themes
	 */
	private $multiskin_themes;

	/**
	 * Upgrading method
	 *
	 * Upgrading method: 'internal' - via this plugin
	 *                   'external' - via third-party plugin
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var upgrading_theme_dir
	 */
	private $upgrading_method;

	/**
	 * Upgrading theme directory
	 *
	 * Directory of the current upgrading theme
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var upgrading_theme_dir
	 */
	private $upgrading_theme_dir;

	/**
	 * Upgrading theme slug
	 *
	 * Directory of the current upgrading theme
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var upgrading_theme_slug
	 */
	private $upgrading_theme_slug;

	/**
	 * Active stylesheet
	 *
	 * Active stylesheet before update started
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var active_stylesheet
	 */
	private $active_stylesheet;

	/**
	 * Class constructor.
	 *
	 * Initializing themes update manager.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct( $manager ) {

		parent::__construct( $manager );

		$this->upgrading_method = 'external';

		$this->upgrading_theme_slug = '';
		$this->upgrading_theme_dir = '';
		$this->active_stylesheet = '';
		$this->theme_parts = array();

		add_action( 'init', array( $this, 'init') );

		add_filter( 'wp_get_update_data', array( $this, 'add_theme_to_update_counts' ), 10, 2 );
		add_action( 'core_upgrade_preamble', array( $this, 'add_theme_to_update_screen' ), 8 );
		add_action( 'update-custom_update-theme', array( $this, 'update_theme' ) );
		add_filter( 'trx_updater_filter_localize_admin_script', array( $this, 'add_attention_to_js' ), 10, 1 );


		// Before update theme
		add_filter( 'upgrader_package_options', array( $this, 'before_theme_upgrade' ), 10, 1 );

		// After update theme
		add_filter( 'upgrader_install_package_result', array( $this, 'after_theme_upgrade' ), 10, 2 );
	}

	/**
	 * Init object
	 *
	 * Get current (active) theme information from upgrade server
	 *
	 * Fired by `init` action
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		$this->theme = $this->get_theme_info();
		// List of supported multiskin themes
		$this->multiskin_themes = apply_filters( 'trx_updater_filter_multiskin_themes',
													array( 'topper-personal', 'qwery', 'kicker' )
												);
	}


	/**
	 * Return an original slug of the current theme
	 *
	 * Return an original slug of the current theme - need to update themes if a theme's folder name
	 * and original slug are different
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function get_theme_original_slug( $theme_slug = '' ) {
		return apply_filters( 'trx_updater_filter_original_theme_slug', ! empty( $theme_slug ) ? $theme_slug : $this->theme_slug );
	}


	/**
	 * Check if a theme update is allowed
	 *
	 * Return true if an original slug of the current theme and its folder name are equals
	 * or if a theme's folder name and original slug are different
	 * and in the folder 'themes' not exists a folder with a name equals to the original theme's slug
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function is_theme_update_allowed() {
		static $allowed = -1;
		if ( $allowed == -1 ) {
			$allowed = 1;
			$original_theme_slug = $this->get_theme_original_slug();
			if ( $original_theme_slug != $this->theme_slug ) {
				$original_theme_path = trailingslashit( get_template_directory() ) . '../' . $original_theme_slug;
				// If a folder with a name equals to the original slug is exists - update is disabled
				if ( is_dir( $original_theme_path ) ) {
					$allowed = 0;
				}
			}
		}
		return $allowed;
	}


	/**
	 * Retrieve info about current theme
	 *
	 * Retrieve info about current (active) theme from the updates server
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function get_theme_info() {
		$data = get_transient( 'trx_updater_theme_info' );
		if ( ! is_array( $data ) || ! empty( $_GET['force-check'] ) ) {
			$data = array(
						$this->theme_slug => array(
													'version' => '0.0.1',
													'update_from' => '0.0.2',
													)
						);
			if ( $this->is_theme_update_allowed() ) {
				$skin = $this->get_active_skin();
				$response = trx_updater_fgc( $this->update_url
												. '?action=info_theme'
												. '&theme_slug=' . urlencode( $this->get_theme_original_slug() )
												. ( ! empty( $skin ) ? '&skin=' . urlencode( $skin ) : '' )
											);
				if ( !empty($response) && is_serialized($response) ) {
					$response = unserialize($response);
					if ( !empty($response['data']) && substr($response['data'], 0, 1) == '{' ) {
						$data[ $this->theme_slug ] = json_decode($response['data'], true);
					}
				}
			}
			set_transient( 'trx_updater_theme_info', $data, 12 * 60 * 60 );       // Store to the cache for 12 hours
		}
		return apply_filters( 'trx_updater_filter_get_theme_info', array(
					'slug'        => $this->theme_slug,
					'title'       => $this->theme_name,
					'key'         => $this->theme_key,
					'version'     => $this->theme_version,
					'update'      => ! empty( $data[$this->theme_slug]['version'] ) ? $data[$this->theme_slug]['version'] : '',
					'update_from' => ! empty( $data[$this->theme_slug]['update_from'] ) ? $data[$this->theme_slug]['update_from'] : '',
					'attention'   => ! empty( $data[$this->theme_slug]['attention'] ) ? $data[$this->theme_slug]['attention'] : '',
					'icon'        => $this->get_item_icon( 'theme', $this->theme_slug, $this->theme_name ),
				) );
	}

	/**
	 * Add JS variables
	 *
	 * Add a variable with an attention about a current theme upgrade
	 *
	 * @hook 'trx_updater_filter_localize_admin_script'
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_attention_to_js( $vars ) {
		if ( ! empty( $this->theme['attention'] ) ) {
			$vars['upgrade_theme_attention'] = $this->theme['attention'];
			$vars['upgrade_theme_title'] = $this->theme['title'];
			$vars['upgrade_theme_slug'] = get_template();
		}
		return $vars;
	}

	/**
	 * Count new themes
	 *
	 * Return a new themes number
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function count_theme_updates() {
		return  ! empty( $this->theme['version'] )
				&& ! empty( $this->theme['update'] )
				&& version_compare( $this->theme['update'], $this->theme['version'], '>' )
				&& ( empty( $this->theme['update_from'] ) || version_compare( $this->theme['version'], $this->theme['update_from'], '>=' ) )
					? 1
					: 0;
	}

	/**
	 * Add new themes count to the WordPress updates count
	 *
	 * Add new themes count to the WordPress updates count.
	 *
	 * Fired by `wp_get_update_data` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_theme_to_update_counts($update_data, $titles) {
		if ( current_user_can( 'update_themes' ) ) {
			$update = $this->count_theme_updates();
			if ( $update > 0 ) {
				$update_data[ 'counts' ][ 'themes' ] += $update;
				$update_data[ 'counts' ][ 'total' ]  += $update;
				// Translators: %d: number of updates available to installed skins
				$titles['themes']                     = sprintf( _n( '%d Theme Update', '%d Theme Updates', $update_data[ 'counts' ][ 'themes' ], 'trx-updater' ), $update );
				$update_data['title']                 = esc_attr( implode( ', ', $titles ) );
			}
		}
		return $update_data;
	}

	/**
	 * Add new theme version to the WordPress update screen
	 *
	 * Add new theme version to the WordPress update screen
	 *
	 * Fired by `core_upgrade_preamble` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_theme_to_update_screen() {
		if ( current_user_can( 'update_themes' ) ) {
			$update = $this->count_theme_updates();
			if ( $update == 0 ) return;
			?>
			<h2>
				<?php
				// Translators: add theme name to the section title
				echo esc_html( sprintf( __( 'Active theme: "%s"', 'trx-updater' ), $this->theme_name ) );
				?>
			</h2>
			<p>
				<?php esc_html_e( 'Active theme have new version available. Check it and then click &#8220;Update Theme&#8221;.', 'trx-updater' ); ?>
			</p>
			<div class="upgrade trx_updater_upgrade trx_updater_upgrade_theme">
				<p><input id="upgrade-theme" class="button trx_updater_upgrade_button trx_updater_upgrade_theme_button" type="button" value="<?php esc_attr_e( 'Update Theme', 'trx-updater' ); ?>" /></p>
				<table class="widefat updates-table" id="update-theme-table">
					<tbody class="plugins themes">
						<?php $checkbox_id = 'checkbox_' . md5( $this->theme['slug'] ); ?>
						<tr>
							<td class="check-column">
								<input type="checkbox"
									name="checked[]"
									id="<?php echo esc_attr( $checkbox_id ); ?>"
									data-update-url="<?php echo esc_url( $this->get_iau_link( $this->theme_slug, 'update', 'theme' ) ); ?>"
									value="<?php echo esc_attr( $this->theme['slug'] ); ?>"
								/>
								<label for="<?php echo esc_attr( $checkbox_id ); ?>" class="screen-reader-text">
									<?php
									// Translators: %s: Theme name
									printf( esc_html__( 'Select %s', 'trx-updater' ), $this->theme['title'] );
									?>
								</label>
							</td>
							<td class="plugin-title"><p>
								<?php echo $this->theme['icon']; ?>
								<strong><?php echo esc_html( $this->theme['title'] ); ?></strong>
								<?php
								// Translators: 1: Theme version, 2: new version
								printf(
									esc_html__( 'You have version %1$s installed. Update to %2$s.', 'trx-updater' ),
									$this->theme['version'],
									$this->theme['update']
								);
								if ( ! empty( $this->theme['attention'] ) ) {
									echo '<span class="trx_updater_attention">' . wp_kses_post( $this->theme['attention'] ) . '</span>';
								}
								?>
							</p></td>
						</tr>
					</tbody>
				</table>
				<p><input id="upgrade-theme-2" class="button trx_updater_upgrade_button trx_updater_upgrade_theme_button" type="button" value="<?php esc_attr_e( 'Update Theme', 'trx-updater' ); ?>" /></p>
			</div>
			<?php
		}
	}

	/**
	 * Update theme
	 *
	 * Download theme from upgrade server and update it
	 *
	 * Fired by `update-custom_update-theme` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function update_theme() {
		$nonce = trx_updater_get_value_gp('_wpnonce');
		$theme = trx_updater_get_value_gp('theme');
		if ( ! empty( $nonce ) && ! empty( $theme ) && $theme == $this->theme_slug && wp_verify_nonce( $nonce, "update-theme_{$theme}" ) && current_user_can( 'update_themes' ) ) {
			// Prepare URL to upgrade server
			$theme_url = sprintf( $this->update_url 
									. '?action=install_theme'
									. '&src=%1$s'
									. '&key=%2$s'
									. '&theme_slug=%3$s'
									. '&theme_name=%4$s'
									. '&skin=%5$s'
									. '&domain=%6$s'
									. '&rnd=%7$s',
								urlencode( $this->get_theme_market_code() ),
								urlencode( $this->theme_key ),
								urlencode( $this->get_theme_original_slug() ),
								urlencode( $this->theme_name ),
								urlencode( $this->get_active_skin() ),
								urlencode( trx_updater_remove_protocol( get_home_url(), true ) ),
								mt_rand()
							);
			// Add theme data to upgrade cache
			$this->inject_update_info( 'themes', array(
				$theme => array(
								'theme' => $theme,
								'new_version' => $this->theme['update'],
								'package' => $theme_url,
								'requires' => '4.7.0',
								'requires_php' => '5.6.0'
								)
			) );
			// Load upgrader
			if ( ! class_exists( 'Theme_Upgrader' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			}
			$upgrader = new \Theme_Upgrader(
							new \Theme_Upgrader_Skin(
								array(
									'title'   => sprintf( __( 'Updating Theme "%s"', 'trx-updater' ), $this->theme_name ),
									'nonce'   => "update-theme_{$theme}",
									'url'     => add_query_arg( array( 'package' => $theme_url ), 'update.php?action=upgrade-theme' ),
									'theme'   => $theme,
									'type'    => 'upload',
									'package' => $theme_url
								)
							)
						);
			$this->upgrading_method = 'internal';
			$this->before_theme_upgrade();
			$upgrader->upgrade( $theme );
			$this->after_theme_upgrade();
		}
	}

	/**
	 * Return active skin
	 *
	 * Return a slug of the active skin for some themes
	 *
	 * @since 1.4.0
	 * @access public
	 */
	public function get_active_skin() {
		$skin = '';
		if ( $this->theme_slug == 'topper-personal' ) {
			$skin = get_option( sprintf( 'theme_skin_%s', get_option( 'stylesheet' ) ), defined( 'TOPPER_DEFAULT_SKIN' ) ? TOPPER_DEFAULT_SKIN : '' );
		}
		return $skin;
	}

	/**
	 * Prepare current theme to upgrade
	 *
	 * Backup skins before upgrade theme
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function before_theme_upgrade( $options = false ) {
		if ( ( $this->upgrading_method == 'internal' && empty( $options ) )
			|| ( $this->upgrading_method == 'external' && ! empty( $options['hook_extra']['theme'] ) && ! empty( $options['destination'] ) )
		) {
			$this->active_stylesheet = get_stylesheet();
			$this->upgrading_theme_slug = $this->upgrading_method == 'internal'
											? $this->theme_slug
											: $options['hook_extra']['theme'];
			$this->upgrading_theme_dir = $this->upgrading_method == 'internal'
											? get_template_directory()
											: trailingslashit( $options['destination'] ) . $this->upgrading_theme_slug;
			$original_theme_slug  = $this->get_theme_original_slug( $this->upgrading_theme_slug );
			// Backup skin folders
			if ( in_array( $original_theme_slug, $this->multiskin_themes )
				|| file_exists( trailingslashit( $this->upgrading_theme_dir ) . 'skins/skins.json' )
			) {
				$this->backup_skins();
			}
			// Backup FSE folders
			$this->backup_fse_theme();
			// Trigger action
			do_action( 'trx_updater_action_before_theme_upgrade', $this->upgrading_theme_slug );
		}
		return $options;
	}

	/**
	 * Restore current theme parts after upgrade
	 *
	 * Restore skins after upgrade theme
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function after_theme_upgrade( $result = true, $options = false ) {
		if ( ! is_wp_error( $result ) ) {
			if ( ( $this->upgrading_method == 'internal' && empty( $options ) )
				|| ( $this->upgrading_method == 'external' && ! empty( $options['theme'] ) && $options['theme'] == $this->upgrading_theme_slug )
			) {
				// Rename a theme's folder back to the its old name from the original theme's slug
				$themes_folder        = trailingslashit( dirname( get_template_directory() ) );
				$original_theme_slug  = $this->get_theme_original_slug();
				$original_theme_path  = $themes_folder . $original_theme_slug;
				$upgrading_theme_path = $themes_folder . $this->upgrading_theme_slug;
				if ( $original_theme_slug != $this->upgrading_theme_slug		// A theme folder name and an original theme slug are different
					&& is_dir( $original_theme_path )							// and a folder with original theme slug is exists
					&& ! is_dir( $upgrading_theme_path )						// and a folder with old name is not exists
				) {
					rename( $original_theme_path, $upgrading_theme_path );
					// Switch active theme back
					if ( ! empty( $this->active_stylesheet ) && get_stylesheet() != $this->active_stylesheet ) {
						wp_clean_themes_cache();
						switch_theme( $this->active_stylesheet );
					}
				}
				// Restore skins
				if ( in_array( $original_theme_slug, $this->multiskin_themes )
					|| file_exists( trailingslashit( $this->upgrading_theme_dir ) . 'skins/skins.json' )
				) {
					$this->restore_skins();
				}
				// Restore FSE folders
				$this->restore_fse_theme();
				// Set flag to regenerate merged styles and scripts on first run
				update_option( 'trx_addons_action', 'trx_addons_action_save_options' );
				// Trigger action
				do_action( 'trx_updater_action_after_theme_upgrade', $this->upgrading_theme_slug );
				// Trigger action for updated theme
				do_action( "{$original_theme_slug}_action_theme_updated" );
			}
		}
		return $result;
	}

	/**
	 * Backup skins
	 *
	 * Backup skins before upgrade theme
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function backup_skins() {
		// Skins are not saved in any other action
		if ( empty( $this->theme_parts['skins'] ) ) {
			$skin_active = $this->get_active_skin();
			$skins_dir   = trailingslashit( $this->upgrading_theme_dir ) . 'skins/';
			$skins_json  = $skins_dir . 'skins.json';
			if ( file_exists( $skins_json ) ) {
				$skins_info = json_decode( trx_updater_fgc( $skins_json ), true );
				$skins_list = glob( $skins_dir . '*', GLOB_ONLYDIR);
				if ( is_array( $skins_list ) ) {
					$this->theme_parts['skins'] = array();
					trx_updater_allow_upload_archives();
					foreach( $skins_list as $sdir ) {
						$sname = basename( $sdir );
// Don't skip active skin (for Topper) and skin 'default' (for other multiskin themes)
// because during the restoration of skins (after updating the folder with the theme)
// if the folder with the skin already exists - this skin will be skipped
//						if ( ( $sname == $skin_active && $this->upgrading_theme_slug == 'topper-personal' )
//							|| ( $sname == 'default' && in_array( $this->upgrading_theme_slug, array( 'qwery', 'kicker' ) ) )
//						) {
//							continue;
//						}
						$rnd = str_replace('.', '', mt_rand());
						$result = wp_upload_bits( "backup-{$this->upgrading_theme_slug}-skin-{$sname}-{$rnd}.zip", 0, '' );
						if ( ! empty( $result['file'] ) ) {
							if ( trx_updater_pack_archive( $result['file'], $sdir ) ) {
								$this->theme_parts['skins'][$sname] = array(
									'backup' => $result['file'],
									'info'   => ! empty( $skins_info[$sname] ) ? $skins_info[$sname] : ''
								);
							}
						}
					}
					trx_updater_disallow_upload_archives();
				}
			}
		}
	}

	/**
	 * Restore skins
	 *
	 * Restore skins after upgrade theme
	 *
	 * @since 1.4.1
	 * @access public
	 */
	public function restore_skins() {
		// Have saved skins and skins are not restored in any other action
		if ( ! empty( $this->theme_parts['skins'] ) && is_array( $this->theme_parts['skins'] ) ) {
			$skins_dir  = trailingslashit( $this->upgrading_theme_dir ) . 'skins/';
			$skins_json = $skins_dir . 'skins.json';
			if ( file_exists( $skins_json ) && is_writable( $skins_json ) ) {
				$skins_info = json_decode( trx_updater_fgc( $skins_json ), true );
				foreach( $this->theme_parts['skins'] as $skin_name => $skin_data ) {
					$sdir = $skins_dir . trx_updater_esc( $skin_name );
					if ( ! empty( $skin_data['backup'] ) && file_exists( $skin_data['backup'] ) ) {
						if ( ! is_dir( $sdir )
							|| ( ! empty( $skins_info[$skin_name]['version'] )
								&& ! empty( $skin_data['info']['version'] )
								&& version_compare( $skins_info[$skin_name]['version'], $skin_data['info']['version'], '<' )
								)
						) {
							unzip_file( $skin_data['backup'], $sdir );
							if ( ! empty( $skin_data['info'] )
								&& ( empty( $skins_info[$skin_name] )
									|| ( ! empty( $skins_info[$skin_name]['version'] )
										&& ! empty( $skin_data['info']['version'] )
										&& version_compare( $skins_info[$skin_name]['version'], $skin_data['info']['version'], '<' )
										)
									)
							) {
								$skins_info[$skin_name] = $skin_data['info'];
							}
						}
						trx_updater_unlink( $skin_data['backup'] );
					}
					unset( $this->theme_parts['skins'][ $skin_name ] );
				}
				trx_updater_fpc( $skins_json, json_encode( $skins_info, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS ) );
				$this->theme_parts['skins'] = false;
			}
		}
	}

	/**
	 * Backup FSE-compatible folders 'templates' and 'parts' from the theme root directory
	 *
	 * @since 1.9.7
	 * @access public
	 */
	public function backup_fse_theme() {
		// Folders are not saved in any other action
		if ( empty( $this->theme_parts['fse'] ) ) {
			$templates_dir = trailingslashit( $this->upgrading_theme_dir ) . 'templates';
			$parts_dir     = trailingslashit( $this->upgrading_theme_dir ) . 'parts';
			$theme_json    = trailingslashit( $this->upgrading_theme_dir ) . 'theme.json';
			if ( file_exists( $templates_dir . '/index.html' ) && is_dir( $parts_dir ) && file_exists( $theme_json ) ) {
				trx_updater_allow_upload_archives();
				$rnd = str_replace( '.', '', mt_rand() );
				// Backup the file 'theme.json'
				$this->theme_parts['fse'] = array(
					'theme_json' => trx_updater_fgc( $theme_json )
				);
				// Backup the folder 'templates'
				$result = wp_upload_bits( "backup-{$this->upgrading_theme_slug}-fse-templates-{$rnd}.zip", 0, '' );
				if ( ! empty( $result['file'] ) ) {
					if ( trx_updater_pack_archive( $result['file'], $templates_dir ) ) {
						$this->theme_parts['fse']['templates'] = array(
							'backup' => $result['file'],
						);
					}
				}
				// Backup the folder 'parts'
				$result = wp_upload_bits( "backup-{$this->upgrading_theme_slug}-fse-parts-{$rnd}.zip", 0, '' );
				if ( ! empty( $result['file'] ) ) {
					if ( trx_updater_pack_archive( $result['file'], $parts_dir ) ) {
						$this->theme_parts['fse']['parts'] = array(
							'backup' => $result['file'],
						);
					}
				}
				trx_updater_disallow_upload_archives();
			}
		}
	}

	/**
	 * Restore FSE-compatible folders 'templates' and 'parts' to the theme root directory
	 *
	 * @since 1.9.7
	 * @access public
	 */
	public function restore_fse_theme() {
		// Remove FSE-compatible folders
		$templates_dir = trailingslashit( $this->upgrading_theme_dir ) . 'templates';
		$parts_dir     = trailingslashit( $this->upgrading_theme_dir ) . 'parts';
		$theme_json    = trailingslashit( $this->upgrading_theme_dir ) . 'theme.json';
		if ( file_exists( $templates_dir . '/index.html' ) && is_dir( $parts_dir ) && file_exists( $theme_json ) ) {
			trx_updater_del_folder( $templates_dir );
			trx_updater_del_folder( $parts_dir );
			trx_updater_unlink( $theme_json );
		}
		// Have saved FSE folders and its are not restored in any other action
		if ( ! empty( $this->theme_parts['fse'] ) && is_array( $this->theme_parts['fse'] ) ) {
			// Restore the file 'theme.json'
			if ( ! empty( $this->theme_parts['fse']['theme_json'] ) ) {
				trx_updater_fpc( $theme_json, $this->theme_parts['fse']['theme_json'] );
			}
			// Restore the folder 'templates'
			if ( ! empty( $this->theme_parts['fse']['templates'] ) && file_exists( $this->theme_parts['fse']['templates']['backup'] ) ) {
				unzip_file( $this->theme_parts['fse']['templates']['backup'], $templates_dir );
				trx_updater_unlink( $this->theme_parts['fse']['templates']['backup'] );
			}
			// Restore the folder 'parts'
			if ( ! empty( $this->theme_parts['fse']['parts'] ) && file_exists( $this->theme_parts['fse']['parts']['backup'] ) ) {
				unzip_file( $this->theme_parts['fse']['parts']['backup'], $parts_dir );
				trx_updater_unlink( $this->theme_parts['fse']['parts']['backup'] );
			}
			$this->theme_parts['fse'] = false;
		}
	}

}
