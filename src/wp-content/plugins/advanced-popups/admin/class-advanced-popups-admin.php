<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ADP
 * @subpackage ADP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ADP
 * @subpackage ADP/admin
 */
class ADP_Admin {

	/**
	 * The ID of this plugin.

	 * @access private
	 * @var string $adp The ID of this plugin.
	 */
	private $adp;

	/**
	 * The version of this plugin.

	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $adp     The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $adp, $version ) {
		$this->adp     = $adp;
		$this->version = $version;
	}

	/**
	 * Register post type
	 */
	public function register_post_type() {
		register_post_type( 'adp-popup', array(
			'labels'             => array(
				'name'               => esc_html__( 'Popups', 'coffee-guru' ),
				'singular_name'      => esc_html__( 'Popup', 'coffee-guru' ),
				'menu_name'          => esc_html__( 'Popups', 'coffee-guru' ),
				'name_admin_bar'     => esc_html__( 'Popup', 'coffee-guru' ),
				'add_new'            => esc_html__( 'Add New', 'coffee-guru' ),
				'add_new_item'       => esc_html__( 'Add New Popup', 'coffee-guru' ),
				'new_item'           => esc_html__( 'New Popup', 'coffee-guru' ),
				'edit_item'          => esc_html__( 'Edit Popup', 'coffee-guru' ),
				'view_item'          => esc_html__( 'View Popup', 'coffee-guru' ),
				'all_items'          => esc_html__( 'Popups', 'coffee-guru' ),
				'search_items'       => esc_html__( 'Search Popups', 'coffee-guru' ),
				'parent_item_colon'  => esc_html__( 'Parent Popups:', 'coffee-guru' ),
				'not_found'          => esc_html__( 'No popups found.', 'coffee-guru' ),
				'not_found_in_trash' => esc_html__( 'No popups found in Trash.', 'coffee-guru' ),
			),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 55,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-editor-expand',
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
			'show_in_rest'       => true,
			'show_in_menu'       => 'options-general.php',
		) );
	}

	/**
	 * Addd new meta box.
	 */
	public function metabox_popup_register() {
		add_meta_box( 'adp_popup_metabox', esc_html__( 'Popup Settings', 'advanced-popups' ), array( $this, 'metabox_popup_callback' ), array( 'adp-popup' ), 'normal', 'high', null );
	}

	/**
	 * Callback for Popup Meta Box.
	 *
	 * @param object $post Object of post.
	 */
	public function metabox_popup_callback( $post ) {

		$popup_type                   = adp_get_post_meta( $post->ID, '_adp_popup_type', true, 'content' );
		$popup_location               = adp_get_post_meta( $post->ID, '_adp_popup_location', true, 'center' );
		$popup_preview_image          = adp_get_post_meta( $post->ID, '_adp_popup_preview_image', true, 'left' );
		$popup_info_text              = adp_get_post_meta( $post->ID, '_adp_popup_info_text', true );
		$popup_info_buton_label       = adp_get_post_meta( $post->ID, '_adp_popup_info_buton_label', true );
		$popup_info_button_action     = adp_get_post_meta( $post->ID, '_adp_popup_info_button_action', true, 'link' );
		$popup_info_button_link       = adp_get_post_meta( $post->ID, '_adp_popup_info_button_link', true );
		$popup_limit_display          = adp_get_post_meta( $post->ID, '_adp_popup_limit_display', true, 1 );
		$popup_limit_lifetime         = adp_get_post_meta( $post->ID, '_adp_popup_limit_lifetime', true, 30 );
		$popup_show_to                = adp_get_post_meta( $post->ID, '_adp_popup_show_to', true, 'both' );
		$popup_rules_mode             = adp_get_post_meta( $post->ID, '_adp_popup_rules_mode', true, 'all' );
		$popup_rules                  = adp_get_post_meta( $post->ID, '_adp_popup_rules', true, array() );
		$popup_open_trigger           = adp_get_post_meta( $post->ID, '_adp_popup_open_trigger', true, 'delay' );
		$popup_open_delay_number      = adp_get_post_meta( $post->ID, '_adp_popup_open_delay_number', true, 1 );
		$popup_open_scroll_position   = adp_get_post_meta( $post->ID, '_adp_popup_open_scroll_position', true, 10 );
		$popup_open_scroll_type       = adp_get_post_meta( $post->ID, '_adp_popup_open_scroll_type', true, '%' );
		$popup_open_manual_selector   = adp_get_post_meta( $post->ID, '_adp_popup_open_manual_selector', true );
		$popup_close_trigger          = adp_get_post_meta( $post->ID, '_adp_popup_close_trigger', true, 'none' );
		$popup_close_delay_number     = adp_get_post_meta( $post->ID, '_adp_popup_close_delay_number', true, 30 );
		$popup_close_scroll_position  = adp_get_post_meta( $post->ID, '_adp_popup_close_scroll_position', true, 10 );
		$popup_close_scroll_type      = adp_get_post_meta( $post->ID, '_adp_popup_close_scroll_type', true, '%' );
		$popup_open_animation         = adp_get_post_meta( $post->ID, '_adp_popup_open_animation', true, 'popupOpenFade' );
		$popup_exit_animation         = adp_get_post_meta( $post->ID, '_adp_popup_exit_animation', true, 'popupExitFade' );
		$popup_content_box_width      = adp_get_post_meta( $post->ID, '_adp_popup_content_box_width', true, 500 );
		$popup_notification_box_width = adp_get_post_meta( $post->ID, '_adp_popup_notification_box_width', true, 400 );
		$popup_notification_bar_width = adp_get_post_meta( $post->ID, '_adp_popup_notification_bar_width', true, 1024 );
		$popup_light_close            = adp_get_post_meta( $post->ID, '_adp_popup_light_close', true, false );
		$popup_display_overlay        = adp_get_post_meta( $post->ID, '_adp_popup_display_overlay', true, false );
		$popup_mobile_disable         = adp_get_post_meta( $post->ID, '_adp_popup_mobile_disable', true );
		$popup_body_scroll_disable    = adp_get_post_meta( $post->ID, '_adp_popup_body_scroll_disable', true );
		$popup_overlay_close          = adp_get_post_meta( $post->ID, '_adp_popup_overlay_close', true );
		$popup_esc_close              = adp_get_post_meta( $post->ID, '_adp_popup_esc_close', true );
		$popup_f4_close               = adp_get_post_meta( $post->ID, '_adp_popup_f4_close', true );

		// Default location for notification bar.
		if ( 'notification-bar' === $popup_type ) {
			if ( 'top' !== $popup_location && 'bottom' !== $popup_location ) {
				$popup_location = 'bottom';
			}
		}
		?>
			<div class="adp-metabox-wrap popup-wrap">
				<input type="hidden" name="adp_popup_action" value="1">

				<?php wp_nonce_field( 'adp_popup_meta_nonce', 'adp_popup_meta_nonce' ); ?>

				<div class="adp-metabox-tabs">
					<ul class="adp-metabox-tabs-navigation">
						<li><a href="#popup-tab-general"><?php esc_html_e( 'General', 'advanced-popups' ); ?></a></li>
						<li><a href="#popup-tab-display"><?php esc_html_e( 'Display Rules', 'advanced-popups' ); ?></a></li>
						<li><a href="#popup-tab-triggers"><?php esc_html_e( 'Triggers', 'advanced-popups' ); ?></a></li>
						<li><a href="#popup-tab-style"><?php esc_html_e( 'Style', 'advanced-popups' ); ?></a></li>
						<li><a href="#popup-tab-advanced"><?php esc_html_e( 'Advanced', 'advanced-popups' ); ?></a></li>
					</ul>
					<div class="adp-metabox-tabs-content">
						<div id="popup-tab-general">
							<div class="adp-metabox-field popup-field-type">
								<div class="adp-metabox-label">
									<label for="adp_popup_type"><?php esc_html_e( 'Type', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<select id="adp_popup_type" name="adp_popup_type">
										<option value="content" <?php selected( $popup_type, 'content' ); ?>><?php esc_html_e( 'Content Box', 'advanced-popups' ); ?></option>
										<option value="notification-box" <?php selected( $popup_type, 'notification-box' ); ?>><?php esc_html_e( 'Notification Box', 'advanced-popups' ); ?></option>
										<option value="notification-bar" <?php selected( $popup_type, 'notification-bar' ); ?>><?php esc_html_e( 'Notification Bar', 'advanced-popups' ); ?></option>
									</select>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-location">
								<div class="adp-metabox-label">
									<label for="adp_popup_location"><?php esc_html_e( 'Location', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<select id="adp_popup_location" name="adp_popup_location">
										<option value="top" <?php selected( $popup_location, 'top' ); ?>><?php esc_html_e( 'Top', 'advanced-popups' ); ?></option>
										<option value="top-left" <?php selected( $popup_location, 'top-left' ); ?>><?php esc_html_e( 'Top Left', 'advanced-popups' ); ?></option>
										<option value="top-right" <?php selected( $popup_location, 'top-right' ); ?>><?php esc_html_e( 'Top Right', 'advanced-popups' ); ?></option>
										<option value="bottom" <?php selected( $popup_location, 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'advanced-popups' ); ?></option>
										<option value="bottom-left" <?php selected( $popup_location, 'bottom-left' ); ?>><?php esc_html_e( 'Bottom Left', 'advanced-popups' ); ?></option>
										<option value="bottom-right" <?php selected( $popup_location, 'bottom-right' ); ?>><?php esc_html_e( 'Bottom Right', 'advanced-popups' ); ?></option>
										<option value="left" <?php selected( $popup_location, 'left' ); ?>><?php esc_html_e( 'Left', 'advanced-popups' ); ?></option>
										<option value="right" <?php selected( $popup_location, 'right' ); ?>><?php esc_html_e( 'Right', 'advanced-popups' ); ?></option>
										<option value="center" <?php selected( $popup_location, 'center' ); ?>><?php esc_html_e( 'Center', 'advanced-popups' ); ?></option>
									</select>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-preview-image">
								<div class="adp-metabox-label">
									<label for="adp_popup_preview_image"><?php esc_html_e( 'Preview Image', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<select id="adp_popup_preview_image" name="adp_popup_preview_image">
										<option value="left" <?php selected( $popup_preview_image, 'left' ); ?>><?php esc_html_e( 'Left', 'advanced-popups' ); ?></option>
										<option value="right" <?php selected( $popup_preview_image, 'right' ); ?>><?php esc_html_e( 'Right', 'advanced-popups' ); ?></option>
										<option value="top" <?php selected( $popup_preview_image, 'top' ); ?>><?php esc_html_e( 'Top', 'advanced-popups' ); ?></option>
										<option value="bottom" <?php selected( $popup_preview_image, 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'advanced-popups' ); ?></option>
										<option value="none" <?php selected( $popup_preview_image, 'none' ); ?>><?php esc_html_e( 'None', 'advanced-popups' ); ?></option>
									</select>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-info-text">
								<div class="adp-metabox-label">
									<label for="adp_popup_info_text"><?php esc_html_e( 'Notification Text', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<textarea type="number" id="adp_popup_info_text" name="adp_popup_info_text" row="4"><?php echo wp_kses_post( $popup_info_text ); ?></textarea>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-info-buton-label">
								<div class="adp-metabox-label">
									<label for="adp_popup_info_buton_label"><?php esc_html_e( 'Notification Button Label', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<input type="text" id="adp_popup_info_buton_label" name="adp_popup_info_buton_label" value="<?php echo esc_attr( $popup_info_buton_label ); ?>" />
								</div>
							</div>
							<div class="adp-metabox-field popup-field-info-buton-action">
								<div class="adp-metabox-label">
									<label for="adp_popup_info_button_action"><?php esc_html_e( 'Notification Button Action', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<select id="adp_popup_info_button_action" name="adp_popup_info_button_action">
										<option value="link" <?php selected( $popup_info_button_action, 'link' ); ?>><?php esc_html_e( 'Link', 'advanced-popups' ); ?></option>
										<option value="accept" <?php selected( $popup_info_button_action, 'accept' ); ?>><?php esc_html_e( 'Accept', 'advanced-popups' ); ?></option>
									</select>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-info-buton-link">
								<div class="adp-metabox-label">
									<label for="adp_popup_info_button_link"><?php esc_html_e( 'Notification Button Link', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<input type="text" id="adp_popup_info_button_link" name="adp_popup_info_button_link" value="<?php echo esc_attr( $popup_info_button_link ); ?>" />
								</div>
							</div>
							<div class="adp-metabox-field popup-field-limit-display">
								<div class="adp-metabox-label">
									<label for="adp_popup_limit_display"><?php esc_html_e( 'Limit display', 'advanced-popups' ); ?></label>
									<p class="description">
										<?php esc_html_e( 'Show the popup only [n] times.', 'advanced-popups' ); ?>
									</p>
								</div>
								<div class="adp-metabox-input">
									<input class="short" type="number" id="adp_popup_limit_display" name="adp_popup_limit_display" value="<?php echo esc_attr( $popup_limit_display ); ?>" />
								</div>
							</div>
							<div class="adp-metabox-field popup-field-limit-lifetime">
								<div class="adp-metabox-label">
									<label for="adp_popup_limit_lifetime"><?php esc_html_e( 'Limit display сache lifetime (days)', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<input class="short" type="number" id="adp_popup_limit_lifetime " name="adp_popup_limit_lifetime" value="<?php echo esc_attr( $popup_limit_lifetime ); ?>" />
								</div>
							</div>

							<div class="adp-metabox-field">
								<div class="adp-metabox-label popup-field-show-to">
									<label for="adp_popup_show_to"><?php esc_html_e( 'Guests or Logged-in', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<select id="adp_popup_show_to" name="adp_popup_show_to">
										<option value="both" <?php selected( $popup_show_to, 'both' ); ?>><?php esc_html_e( 'Show to both users and guest visitors' ); ?></option>
										<option value="guest" <?php selected( $popup_show_to, 'guest' ); ?>><?php esc_html_e( 'Show only to guest visitors' ); ?></option>
										<option value="user" <?php selected( $popup_show_to, 'user' ); ?>><?php esc_html_e( 'Show only to logged-in users', 'advanced-popups' ); ?></option>
									</select>
								</div>
							</div>
						</div>
						<div id="popup-tab-display">
							<div class="adp-metabox-field popup-field-rules-mode">
								<div class="adp-metabox-label">
									<label for="adp_popup_rules_mode"><?php esc_html_e( 'Show Popup', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input type="radio" id="adp_popup_rules_mode" name="adp_popup_rules_mode" value="all" <?php checked( $popup_rules_mode, 'all' ); ?>> <?php esc_html_e( 'Entire Site', 'advanced-popups' ); ?></label>&nbsp;
									<label><input type="radio" id="adp_popup_rules_mode" name="adp_popup_rules_mode" value="specific" <?php checked( $popup_rules_mode, 'specific' ); ?>> <?php esc_html_e( 'Specific Pages', 'advanced-popups' ); ?></label>&nbsp;
								</div>
							</div>
							<div class="adp-metabox-field popup-field-rules">
								<div class="popup-field-rules-list">
									<?php
									if ( is_array( $popup_rules ) && $popup_rules ) {
										foreach ( $popup_rules as $i => $row ) {
											?>
											<div class="row">
												<?php
												foreach ( $row as $t => $tools ) {
													?>
													<div class="tools">
														<select class="adp-popup-rules" name="adp_popup_rules[<?php echo esc_attr( $i ); ?>][<?php echo esc_attr( $t ); ?>][rule]">
															<?php
															$rules = ADP_Popup_Rules::instance()->get_list();

															foreach ( $rules as $optgroup => $items ) {
																$label = $optgroup;

																$label = str_replace( 'general', esc_html__( 'General', 'advanced-popups' ), $label );
																$label = str_replace( 'post_types', esc_html__( 'Posts Types', 'advanced-popups' ), $label );
																$label = str_replace( 'taxonomies', esc_html__( 'Taxonomies', 'advanced-popups' ), $label );
																?>
																<optgroup data-group="<?php echo esc_attr( $optgroup ); ?>"
																		label="<?php echo esc_attr( $label ); ?>">
																	<?php
																	foreach ( $items as $key => $name ) {
																		?>
																		<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $tools['rule'] ); ?>>
																			<?php echo esc_html( $name ); ?>
																		</option>
																		<?php
																	}
																	?>
																</option>
																<?php
															}
															?>
														</select>

														<input type="text" class="adp-popup-url" name="adp_popup_rules[<?php echo esc_attr( $i ); ?>][<?php echo esc_attr( $t ); ?>][url]" value="<?php echo esc_attr( $tools['url'] ); ?>">

														<?php
														$type = ADP_Popup_Rules::instance()->get_type( $tools['rule'] );
														?>
														<select multiple class="adp-popup-objects"  name="adp_popup_rules[<?php echo esc_attr( $i ); ?>][<?php echo esc_attr( $t ); ?>][object][]">
															<?php
															if ( isset( $tools['object'] ) && is_array( $tools['object'] ) ) {

																foreach ( $tools['object'] as $object ) {
																	$name = (int) $object;
																	if ( 'post' === $type ) {
																		$name = get_the_title( $object );
																	}
																	if ( 'taxonomy' === $type ) {
																		$term = get_term( $object );

																		$name = $term->name;

																	}
																	?>
																		<option value="<?php echo esc_attr( $object ); ?>" selected="selected"><?php echo esc_html( $name ); ?></option>
																	<?php
																}
															}
															?>
														</select>

														<a href="#" class="delete remove-another-rule">
															<span class="dashicons dashicons-no-alt"></span>
														</a>
													</div>
													<?php
												}
												?>

												<div class="tools-bar">
													<div class="button add-another-rule">
														<?php esc_html_e( 'Add another OR rule', 'advanced-popups' ); ?>
													</div>

													<a href="#" class="delete remove-rule"><?php esc_html_e( 'Remove', 'advanced-popups' ); ?></a>
												</div>
											</div>
											<?php
										}
									}
									?>
								</div>

								<div class="button button-primary add-new-rule">
									<?php esc_html_e( 'Add New Rule ', 'advanced-popups' ); ?>
								</div>
							</div>
						</div>
						<div id="popup-tab-triggers">
							<div class="adp-metabox-field popup-field-open-trigger">
								<div class="adp-metabox-label">
									<label for="adp_popup_open_trigger"><?php esc_html_e( 'Trigger Open Popup', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input type="radio" id="adp_popup_open_trigger" name="adp_popup_open_trigger" value="delay" <?php checked( $popup_open_trigger, 'delay' ); ?>> <?php esc_html_e( 'Time Delay', 'advanced-popups' ); ?></label>&nbsp;
									<label><input type="radio" id="adp_popup_open_trigger" name="adp_popup_open_trigger" value="viewed" <?php checked( $popup_open_trigger, 'viewed' ); ?>> <?php esc_html_e( 'Page Viewed', 'advanced-popups' ); ?></label>&nbsp;
									<label><input type="radio" id="adp_popup_open_trigger" name="adp_popup_open_trigger" value="read" <?php checked( $popup_open_trigger, 'read' ); ?>> <?php esc_html_e( 'Page Read', 'advanced-popups' ); ?></label>&nbsp;
									<label><input type="radio" id="adp_popup_open_trigger" name="adp_popup_open_trigger" value="exit" <?php checked( $popup_open_trigger, 'exit' ); ?>> <?php esc_html_e( 'Exit Intent', 'advanced-popups' ); ?></label>&nbsp;
									<label><input type="radio" id="adp_popup_open_trigger" name="adp_popup_open_trigger" value="scroll" <?php checked( $popup_open_trigger, 'scroll' ); ?>> <?php esc_html_e( 'Scroll Position', 'advanced-popups' ); ?></label>
									<label><input type="radio" id="adp_popup_open_trigger" name="adp_popup_open_trigger" value="accept" <?php checked( $popup_open_trigger, 'accept' ); ?>> <?php esc_html_e( 'Accept Agreement', 'advanced-popups' ); ?></label>&nbsp;
									<label><input type="radio" id="adp_popup_open_trigger" name="adp_popup_open_trigger" value="manual" <?php checked( $popup_open_trigger, 'manual' ); ?>> <?php esc_html_e( 'Manual Launch', 'advanced-popups' ); ?></label>&nbsp;
								</div>
							</div>
							<div class="adp-metabox-field popup-field-open-accept-desc">
								<div class="adp-metabox-label"></div>
								<div class="adp-metabox-input">
									<p class="description"><?php esc_html_e( 'It works for "Notification Box" and "Notification Bar". And if "Notification Button Action" is selected as "Accept", then the popup will be displayed until the user accepts the agreement.', 'advanced-popups' ); ?></p>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-open-delay-number">
								<div class="adp-metabox-label">
									<label for="adp_popup_open_delay_number"><?php esc_html_e( 'Time Delay', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input class="short" type="number" id="adp_popup_open_delay_number" name="adp_popup_open_delay_number" value="<?php echo esc_attr( $popup_open_delay_number ); ?>" /> <?php esc_html_e( 'Seconds', 'advanced-popups' ); ?></label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-open-scroll-position">
								<div class="adp-metabox-label">
									<label for="adp_popup_open_scroll_position"><?php esc_html_e( 'Scroll Position', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label>
										<input class="short" type="number" id="adp_popup_open_scroll_position" name="adp_popup_open_scroll_position" value="<?php echo esc_attr( $popup_open_scroll_position ); ?>" />

										<select class="short" id="adp_popup_open_scroll_type" name="adp_popup_open_scroll_type">
											<option value="px" <?php selected( $popup_open_scroll_type, 'px' ); ?>><?php esc_html_e( 'Px.', 'advanced-popups' ); ?></option>
											<option value="%" <?php selected( $popup_open_scroll_type, '%' ); ?>><?php esc_html_e( '%', 'advanced-popups' ); ?></option>
										</select> <?php esc_html_e( 'of screen', 'advanced-popups' ); ?>
									</label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-open-manual-selector">
								<div class="adp-metabox-label">
									<label for="adp_popup_open_manual_selector"><?php esc_html_e( 'CSS Selector', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<input type="text" id="adp_popup_open_manual_selector" name="adp_popup_open_manual_selector" value="<?php echo esc_attr( $popup_open_manual_selector ); ?>" />
								</div>
							</div>
							<div class="adp-metabox-field popup-field-close-trigger">
								<div class="adp-metabox-label">
									<label for="adp_popup_close_trigger"><?php esc_html_e( 'Trigger Close Popup', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input type="radio" id="adp_popup_close_trigger" name="adp_popup_close_trigger" value="none" <?php checked( $popup_close_trigger, 'none' ); ?>> <?php esc_html_e( 'None', 'advanced-popups' ); ?></label>&nbsp;
									<label><input type="radio" id="adp_popup_close_trigger" name="adp_popup_close_trigger" value="delay" <?php checked( $popup_close_trigger, 'delay' ); ?>> <?php esc_html_e( 'Time Delay', 'advanced-popups' ); ?></label>&nbsp;
									<label><input type="radio" id="adp_popup_close_trigger" name="adp_popup_close_trigger" value="scroll" <?php checked( $popup_close_trigger, 'scroll' ); ?>> <?php esc_html_e( 'Scroll Position', 'advanced-popups' ); ?></label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-close-delay-number">
								<div class="adp-metabox-label">
									<label for="adp_popup_close_delay_number"><?php esc_html_e( 'Time Delay', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input class="short" type="number" id="adp_popup_close_delay_number" name="adp_popup_close_delay_number" value="<?php echo esc_attr( $popup_close_delay_number ); ?>" /> <?php esc_html_e( 'Seconds', 'advanced-popups' ); ?></label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-close-scroll-position">
								<div class="adp-metabox-label">
									<label for="adp_popup_close_scroll_position"><?php esc_html_e( 'Offset Scroll Position', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label>
										<input class="short" type="number" id="adp_popup_close_scroll_position" name="adp_popup_close_scroll_position" value="<?php echo esc_attr( $popup_close_scroll_position ); ?>" />

										<select class="short" id="adp_popup_close_scroll_type" name="adp_popup_close_scroll_type">
											<option value="px" <?php selected( $popup_close_scroll_type, 'px' ); ?>><?php esc_html_e( 'Px.', 'advanced-popups' ); ?></option>
											<option value="%" <?php selected( $popup_close_scroll_type, '%' ); ?>><?php esc_html_e( '%', 'advanced-popups' ); ?></option>
										</select> <?php esc_html_e( 'of screen', 'advanced-popups' ); ?>
									</label>
								</div>
							</div>
						</div>
						<div id="popup-tab-style">
							<div class="adp-metabox-field popup-field-open-animation">
								<div class="adp-metabox-label">
									<label for="adp_popup_open_animation"><?php esc_html_e( 'Open Animation', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<select id="adp_popup_open_animation" name="adp_popup_open_animation">
										<option value="popupOpenFade" <?php selected( $popup_open_animation, 'popupOpenFade' ); ?>><?php esc_html_e( 'Fade', 'advanced-popups' ); ?></option>
										<option value="popupOpenSlide" <?php selected( $popup_open_animation, 'popupOpenSlide' ); ?>><?php esc_html_e( 'Slide', 'advanced-popups' ); ?></option>
										<option value="popupOpenZoom" <?php selected( $popup_open_animation, 'popupOpenZoom' ); ?>><?php esc_html_e( 'Zoom', 'advanced-popups' ); ?></option>
										<option value="popupOpenSlideFade" <?php selected( $popup_open_animation, 'popupOpenSlideFade' ); ?>><?php esc_html_e( 'Slide and Fade', 'advanced-popups' ); ?></option>
									</select>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-exit-animation">
								<div class="adp-metabox-label">
									<label for="adp_popup_exit_animation"><?php esc_html_e( 'Exit Animation', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<select id="adp_popup_exit_animation" name="adp_popup_exit_animation">
										<option value="popupExitFade" <?php selected( $popup_exit_animation, 'popupExitFade' ); ?>><?php esc_html_e( 'Fade', 'advanced-popups' ); ?></option>
										<option value="popupExitSlide" <?php selected( $popup_exit_animation, 'popupExitSlide' ); ?>><?php esc_html_e( 'Slide', 'advanced-popups' ); ?></option>
										<option value="popupExitZoom" <?php selected( $popup_exit_animation, 'popupExitZoom' ); ?>><?php esc_html_e( 'Zoom', 'advanced-popups' ); ?></option>
										<option value="popupExitSlideFade" <?php selected( $popup_exit_animation, 'popupExitSlideFade' ); ?>><?php esc_html_e( 'Slide and Fade', 'advanced-popups' ); ?></option>
									</select>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-content-box-width">
								<div class="adp-metabox-label">
									<label for="adp_popup_content_box_width"><?php esc_html_e( 'Content Box Width', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label>
										<input class="short" type="number" id="adp_popup_content_box_width" name="adp_popup_content_box_width" value="<?php echo esc_attr( $popup_content_box_width ); ?>" /> <?php esc_html_e( 'px.', 'advanced-popups' ); ?>
									</label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-notification-box-width">
								<div class="adp-metabox-label">
									<label for="adp_popup_notification_box_width"><?php esc_html_e( 'Notification Box Width', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label>
										<input class="short" type="number" id="adp_popup_notification_box_width" name="adp_popup_notification_box_width" value="<?php echo esc_attr( $popup_notification_box_width ); ?>" /> <?php esc_html_e( 'px.', 'advanced-popups' ); ?>
									</label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-notification-bar-width">
								<div class="adp-metabox-label">
									<label for="adp_popup_notification_bar_width"><?php esc_html_e( 'Notification Bar Width', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label>
										<input class="short" type="number" id="adp_popup_notification_bar_width" name="adp_popup_notification_bar_width" value="<?php echo esc_attr( $popup_notification_bar_width ); ?>" /> <?php esc_html_e( 'px.', 'advanced-popups' ); ?>
									</label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-display-overlay">
								<div class="adp-metabox-label">
									<label for="adp_popup_light_close"><?php esc_html_e( 'Light Close Button', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<input type="checkbox" id="adp_popup_light_close" name="adp_popup_light_close" value="1" <?php checked( $popup_light_close ); ?>>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-display-overlay">
								<div class="adp-metabox-label">
									<label for="adp_popup_display_overlay"><?php esc_html_e( 'Display Overlay', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<input type="checkbox" id="adp_popup_display_overlay" name="adp_popup_display_overlay" value="1" <?php checked( $popup_display_overlay ); ?>>
								</div>
							</div>
						</div>
						<div id="popup-tab-advanced">
							<div class="adp-metabox-field popup-field-mobile-disable">
								<div class="adp-metabox-label">
									<label for="adp_popup_mobile_disable"><?php esc_html_e( 'Mobile Disable', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input type="checkbox" id="adp_popup_mobile_disable" name="adp_popup_mobile_disable" value="1" <?php checked( $popup_mobile_disable ); ?>> <?php esc_html_e( 'Disable popup on mobile', 'advanced-popups' ); ?></label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-body-scroll-disable">
								<div class="adp-metabox-label">
									<label for="adp_popup_body_scroll_disable"><?php esc_html_e( 'Disable Scrolling', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input type="checkbox" id="adp_popup_body_scroll_disable" name="adp_popup_body_scroll_disable" value="1" <?php checked( $popup_body_scroll_disable ); ?>> <?php esc_html_e( 'Disable scrolling on body', 'advanced-popups' ); ?></label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-overlay-close">
								<div class="adp-metabox-label">
									<label for="adp_popup_overlay_close"><?php esc_html_e( 'Click Overlay to Close', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input type="checkbox" id="adp_popup_overlay_close" name="adp_popup_overlay_close" value="1" <?php checked( $popup_overlay_close ); ?>> <?php esc_html_e( 'Checking this will cause popup to close when user clicks on overlay', 'advanced-popups' ); ?></label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-esc-close">
								<div class="adp-metabox-label">
									<label for="adp_popup_esc_close"><?php esc_html_e( 'Press ESC to Close', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input type="checkbox" id="adp_popup_esc_close" name="adp_popup_esc_close" value="1" <?php checked( $popup_esc_close ); ?>> <?php esc_html_e( 'Checking this will cause popup to close when user presses ESC key', 'advanced-popups' ); ?></label>
								</div>
							</div>
							<div class="adp-metabox-field popup-field-f4-close">
								<div class="adp-metabox-label">
									<label for="adp_popup_f4_close"><?php esc_html_e( 'Press F4 to Close', 'advanced-popups' ); ?></label>
								</div>
								<div class="adp-metabox-input">
									<label><input type="checkbox" id="adp_popup_f4_close" name="adp_popup_f4_close" value="1" <?php checked( $popup_f4_close ); ?>> <?php esc_html_e( 'Checking this will cause popup to close when user presses F4 key', 'advanced-popups' ); ?></label>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		<?php
	}

	/**
	 * Save meta tags by post
	 *
	 * @param int    $post_id Post ID.
	 * @param object $post    Post Object.
	 */
	public function metabox_popup_save( $post_id, $post ) {

		// Break if doing autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Break if current user can't edit this post.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Break if this post revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['adp_popup_meta_nonce'] ) || ! wp_verify_nonce( $_POST['adp_popup_meta_nonce'], 'adp_popup_meta_nonce' ) ) { // Input var ok; sanitization ok.
			return;
		}

		if ( ! isset( $_POST['adp_popup_action'] ) || 1 !== (int) $_POST['adp_popup_action'] ) { // Input var ok; sanitization ok.
			return;
		}

		if ( isset( $_POST['adp_popup_type'] ) ) {
			$popup_type = sanitize_text_field( $_POST['adp_popup_type'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_type', $popup_type );
		}

		if ( isset( $_POST['adp_popup_location'] ) ) {
			$popup_location = sanitize_text_field( $_POST['adp_popup_location'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_location', $popup_location );
		}

		if ( isset( $_POST['adp_popup_preview_image'] ) ) {
			$popup_preview_image = sanitize_text_field( $_POST['adp_popup_preview_image'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_preview_image', $popup_preview_image );
		}

		if ( isset( $_POST['adp_popup_info_text'] ) ) {
			$popup_info_text = wp_kses_post( $_POST['adp_popup_info_text'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_info_text', $popup_info_text );
		}

		if ( isset( $_POST['adp_popup_info_buton_label'] ) ) {
			$popup_info_buton_label = sanitize_text_field( $_POST['adp_popup_info_buton_label'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_info_buton_label', $popup_info_buton_label );
		}

		if ( isset( $_POST['adp_popup_info_button_action'] ) ) {
			$popup_info_button_action = sanitize_text_field( $_POST['adp_popup_info_button_action'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_info_button_action', $popup_info_button_action );
		}

		if ( isset( $_POST['adp_popup_info_button_link'] ) ) {
			$popup_info_button_link = sanitize_text_field( $_POST['adp_popup_info_button_link'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_info_button_link', $popup_info_button_link );
		}

		if ( isset( $_POST['adp_popup_limit_display'] ) ) {
			$popup_limit_display = (int) sanitize_text_field( $_POST['adp_popup_limit_display'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_limit_display', $popup_limit_display );
		}

		if ( isset( $_POST['adp_popup_limit_lifetime'] ) ) {
			$popup_limit_lifetime = (int) sanitize_text_field( $_POST['adp_popup_limit_lifetime'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_limit_lifetime', $popup_limit_lifetime );
		}

		if ( isset( $_POST['adp_popup_show_to'] ) ) {
			$popup_show_to = sanitize_text_field( $_POST['adp_popup_show_to'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_show_to', $popup_show_to );
		}

		if ( isset( $_POST['adp_popup_rules_mode'] ) ) {
			$popup_rules_mode = sanitize_text_field( $_POST['adp_popup_rules_mode'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_rules_mode', $popup_rules_mode );
		}

		if ( isset( $_POST['adp_popup_rules'] ) ) {
			$popup_rules = map_deep( $_POST['adp_popup_rules'], 'sanitize_text_field' ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_rules', $popup_rules );
		} else {
			delete_post_meta( $post_id, '_adp_popup_rules' );
		}

		if ( isset( $_POST['adp_popup_open_trigger'] ) ) {
			$popup_open_trigger = sanitize_text_field( $_POST['adp_popup_open_trigger'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_open_trigger', $popup_open_trigger );
		}

		if ( isset( $_POST['adp_popup_open_delay_number'] ) ) {
			$popup_open_delay_number = sanitize_text_field( $_POST['adp_popup_open_delay_number'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_open_delay_number', $popup_open_delay_number );
		}

		if ( isset( $_POST['adp_popup_open_scroll_position'] ) ) {
			$popup_open_scroll_position = sanitize_text_field( $_POST['adp_popup_open_scroll_position'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_open_scroll_position', $popup_open_scroll_position );
		}

		if ( isset( $_POST['adp_popup_open_scroll_type'] ) ) {
			$popup_open_scroll_type = sanitize_text_field( $_POST['adp_popup_open_scroll_type'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_open_scroll_type', $popup_open_scroll_type );
		}

		if ( isset( $_POST['adp_popup_open_manual_selector'] ) ) {
			$popup_open_manual_selector = sanitize_text_field( $_POST['adp_popup_open_manual_selector'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_open_manual_selector', $popup_open_manual_selector );
		}

		if ( isset( $_POST['adp_popup_close_trigger'] ) ) {
			$popup_close_trigger = sanitize_text_field( $_POST['adp_popup_close_trigger'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_close_trigger', $popup_close_trigger );
		}

		if ( isset( $_POST['adp_popup_close_delay_number'] ) ) {
			$popup_close_delay_number = sanitize_text_field( $_POST['adp_popup_close_delay_number'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_close_delay_number', $popup_close_delay_number );
		}

		if ( isset( $_POST['adp_popup_close_scroll_position'] ) ) {
			$popup_close_scroll_position = sanitize_text_field( $_POST['adp_popup_close_scroll_position'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_close_scroll_position', $popup_close_scroll_position );
		}

		if ( isset( $_POST['adp_popup_close_scroll_type'] ) ) {
			$popup_close_scroll_type = sanitize_text_field( $_POST['adp_popup_close_scroll_type'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_close_scroll_type', $popup_close_scroll_type );
		}

		if ( isset( $_POST['adp_popup_open_animation'] ) ) {
			$popup_open_animation = sanitize_text_field( $_POST['adp_popup_open_animation'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_open_animation', $popup_open_animation );
		}

		if ( isset( $_POST['adp_popup_exit_animation'] ) ) {
			$popup_exit_animation = sanitize_text_field( $_POST['adp_popup_exit_animation'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_exit_animation', $popup_exit_animation );
		}

		if ( isset( $_POST['adp_popup_content_box_width'] ) ) {
			$popup_content_box_width = (int) sanitize_text_field( $_POST['adp_popup_content_box_width'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_content_box_width', $popup_content_box_width );
		}

		if ( isset( $_POST['adp_popup_notification_box_width'] ) ) {
			$popup_notification_box_width = (int) sanitize_text_field( $_POST['adp_popup_notification_box_width'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_notification_box_width', $popup_notification_box_width );
		}

		if ( isset( $_POST['adp_popup_notification_bar_width'] ) ) {
			$popup_notification_bar_width = (int) sanitize_text_field( $_POST['adp_popup_notification_bar_width'] ); // Input var ok; sanitization ok.

			update_post_meta( $post_id, '_adp_popup_notification_bar_width', $popup_notification_bar_width );
		}

		if ( isset( $_POST['adp_popup_light_close'] ) ) { // Input var ok; sanitization ok.
			update_post_meta( $post_id, '_adp_popup_light_close', 1 );
		} else {
			update_post_meta( $post_id, '_adp_popup_light_close', '' );
		}

		if ( isset( $_POST['adp_popup_display_overlay'] ) ) { // Input var ok; sanitization ok.
			update_post_meta( $post_id, '_adp_popup_display_overlay', 1 );
		} else {
			update_post_meta( $post_id, '_adp_popup_display_overlay', '' );
		}

		if ( isset( $_POST['adp_popup_mobile_disable'] ) ) { // Input var ok; sanitization ok.
			update_post_meta( $post_id, '_adp_popup_mobile_disable', 1 );
		} else {
			update_post_meta( $post_id, '_adp_popup_mobile_disable', '' );
		}

		if ( isset( $_POST['adp_popup_body_scroll_disable'] ) ) { // Input var ok; sanitization ok.
			update_post_meta( $post_id, '_adp_popup_body_scroll_disable', 1 );
		} else {
			update_post_meta( $post_id, '_adp_popup_body_scroll_disable', '' );
		}

		if ( isset( $_POST['adp_popup_overlay_close'] ) ) { // Input var ok; sanitization ok.
			update_post_meta( $post_id, '_adp_popup_overlay_close', 1 );
		} else {
			update_post_meta( $post_id, '_adp_popup_overlay_close', '' );
		}

		if ( isset( $_POST['adp_popup_esc_close'] ) ) { // Input var ok; sanitization ok.
			update_post_meta( $post_id, '_adp_popup_esc_close', 1 );
		} else {
			update_post_meta( $post_id, '_adp_popup_esc_close', '' );
		}

		if ( isset( $_POST['adp_popup_f4_close'] ) ) { // Input var ok; sanitization ok.
			update_post_meta( $post_id, '_adp_popup_f4_close', 1 );
		} else {
			update_post_meta( $post_id, '_adp_popup_f4_close', '' );
		}

		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}
	}

	/**
	 * Get objects list in rules.
	 */
	public function ajax_rules_objects() {

		if ( __return_false() ) {
			check_ajax_referer();
		}

		$search = isset( $_REQUEST['search'] ) ? sanitize_text_field( $_REQUEST['search'] ) : ''; // Input var ok; sanitization ok.
		$group  = isset( $_REQUEST['group'] ) ? sanitize_text_field( $_REQUEST['group'] ) : 'post_types'; // Input var ok; sanitization ok.
		$rule   = isset( $_REQUEST['rule'] ) ? sanitize_text_field( $_REQUEST['rule'] ) : 'none'; // Input var ok; sanitization ok.
		$page   = isset( $_REQUEST['page'] ) ? (int) sanitize_text_field( $_REQUEST['page'] ) : 1; // Input var ok; sanitization ok.

		// Data container.
		$data = array();

		// Get object.
		$object = ADP_Popup_Rules::instance()->get_object( $rule );

		// Get posts.
		if ( 'post_types' === $group ) {

			$args = array(
				's'                   => $search,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => 10,
				'post_type'           => $object,
				'paged'               => $page,
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$data['results'][] = array(
						'id'   => $query->post->ID,
						'text' => $query->post->post_title,
					);
				}
			}

			if ( $page < (int) $query->max_num_pages ) {
				$data['pagination']['more'] = true;
			} else {
				$data['pagination']['more'] = false;
			}
		}

		// Get terms.
		if ( 'taxonomies' === $group ) {

			$terms = get_terms( $object, array(
				'hide_empty' => false,
			) );

			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$data['results'][] = array(
						'id'   => $term->term_id,
						'text' => $term->name,
					);
				}
			}

			$data['pagination']['more'] = false;
		}

		wp_send_json( $data );
	}

	/**
	 * Register the stylesheets and JavaScript for the admin area.
	 *
	 * @param string $page Current page.
	 */
	public function admin_enqueue_scripts( $page ) {

		global $post_type;

		if ( 'adp-popup' !== $post_type ) {
			return;
		}

		if ( in_array( $page, array( 'post.php', 'post-new.php' ), true ) ) {

			// Select2.
			wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css' );
			wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array( 'jquery' ) );

			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-tabs' );

			// Scripts.
			wp_enqueue_script( $this->adp, plugin_dir_url( __FILE__ ) . 'js/advanced-popups-admin.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-sortable', 'select2' ), $this->version, false );

			wp_localize_script( $this->adp, 'adp_popup_data', array(
				'ajaxurl'                => admin_url( 'admin-ajax.php' ),
				'nonce'                  => wp_create_nonce(),
				'label_general'          => esc_html__( 'General', 'advanced-popups' ),
				'label_post_types'       => esc_html__( 'Posts Types', 'advanced-popups' ),
				'label_taxonomies'       => esc_html__( 'Taxonomies', 'advanced-popups' ),
				'btn_label_another'      => esc_html__( 'Add another OR rule', 'advanced-popups' ),
				'btn_delete'             => esc_html__( 'Remove', 'advanced-popups' ),
				'select2_placeholder'    => esc_html__( 'Find items...', 'advanced-popups' ),
				'select2_errorLoading'   => esc_html__( 'The results could not be loaded.', 'advanced-popups' ),
				'select2_loadingMore'    => esc_html__( 'Loading more results...', 'advanced-popups' ),
				'select2_noResults'      => esc_html__( 'Nothing not found', 'advanced-popups' ),
				'select2_searching'      => esc_html__( 'Searching...', 'advanced-popups' ),
				'select2_removeAllItems' => esc_html__( 'Remove all items', 'advanced-popups' ),
				'rules_list'             => wp_json_encode( ADP_Popup_Rules::instance()->get_list() ),
			) );

			// Styles.
			wp_enqueue_style( $this->adp, adp_style( plugin_dir_url( __FILE__ ) . 'css/advanced-popups-admin.css' ), array(), $this->version, 'all' );
		}
	}
}
