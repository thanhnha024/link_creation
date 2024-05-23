<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ADP
 * @subpackage ADP/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    ADP
 * @subpackage ADP/public
 */
class ADP_Public {

	/**
	 * The ID of this plugin.

	 * @access   private
	 * @var      string    $adp    The ID of this plugin.
	 */
	private $adp;

	/**
	 * The version of this plugin.

	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $adp     The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $adp, $version ) {

		$this->adp     = $adp;
		$this->version = $version;
	}

	/**
	 * Initialize
	 */
	public function wp_footer() {
		$query = new WP_Query();

		// Get all popups.
		$popups = $query->query( array(
			'post_status'    => 'publish',
			'post_type'      => 'adp-popup',
			'posts_per_page' => -1,
		) );

		// Looop popups.
		foreach ( $popups as $popup ) {
			$popup_type                   = adp_get_post_meta( $popup->ID, '_adp_popup_type', true, 'content' );
			$popup_location               = adp_get_post_meta( $popup->ID, '_adp_popup_location', true, 'center' );
			$popup_preview_image          = adp_get_post_meta( $popup->ID, '_adp_popup_preview_image', true, 'left' );
			$popup_info_text              = adp_get_post_meta( $popup->ID, '_adp_popup_info_text', true );
			$popup_info_buton_label       = adp_get_post_meta( $popup->ID, '_adp_popup_info_buton_label', true );
			$popup_info_button_action     = adp_get_post_meta( $popup->ID, '_adp_popup_info_button_action', true, 'link' );
			$popup_info_button_link       = adp_get_post_meta( $popup->ID, '_adp_popup_info_button_link', true );
			$popup_limit_display          = adp_get_post_meta( $popup->ID, '_adp_popup_limit_display', true, 1 );
			$popup_limit_lifetime         = adp_get_post_meta( $popup->ID, '_adp_popup_limit_lifetime', true, 30 );
			$popup_open_trigger           = adp_get_post_meta( $popup->ID, '_adp_popup_open_trigger', true, 'delay' );
			$popup_open_delay_number      = adp_get_post_meta( $popup->ID, '_adp_popup_open_delay_number', true, 1 );
			$popup_open_scroll_position   = adp_get_post_meta( $popup->ID, '_adp_popup_open_scroll_position', true, 10 );
			$popup_open_scroll_type       = adp_get_post_meta( $popup->ID, '_adp_popup_open_scroll_type', true, '%' );
			$popup_open_manual_selector   = adp_get_post_meta( $popup->ID, '_adp_popup_open_manual_selector', true );
			$popup_close_trigger          = adp_get_post_meta( $popup->ID, '_adp_popup_close_trigger', true, 'none' );
			$popup_close_delay_number     = adp_get_post_meta( $popup->ID, '_adp_popup_close_delay_number', true, 30 );
			$popup_close_scroll_position  = adp_get_post_meta( $popup->ID, '_adp_popup_close_scroll_position', true, 10 );
			$popup_close_scroll_type      = adp_get_post_meta( $popup->ID, '_adp_popup_close_scroll_type', true, '%' );
			$popup_open_animation         = adp_get_post_meta( $popup->ID, '_adp_popup_open_animation', true, 'popupOpenFade' );
			$popup_exit_animation         = adp_get_post_meta( $popup->ID, '_adp_popup_exit_animation', true, 'popupExitFade' );
			$popup_content_box_width      = adp_get_post_meta( $popup->ID, '_adp_popup_content_box_width', true, 500 );
			$popup_notification_box_width = adp_get_post_meta( $popup->ID, '_adp_popup_notification_box_width', true, 400 );
			$popup_notification_bar_width = adp_get_post_meta( $popup->ID, '_adp_popup_notification_bar_width', true, 1024 );
			$popup_light_close            = adp_get_post_meta( $popup->ID, '_adp_popup_light_close', true, false );
			$popup_display_overlay        = adp_get_post_meta( $popup->ID, '_adp_popup_display_overlay', true, false );
			$popup_mobile_disable         = adp_get_post_meta( $popup->ID, '_adp_popup_mobile_disable', true );
			$popup_body_scroll_disable    = adp_get_post_meta( $popup->ID, '_adp_popup_body_scroll_disable', true );
			$popup_overlay_close          = adp_get_post_meta( $popup->ID, '_adp_popup_overlay_close', true );
			$popup_esc_close              = adp_get_post_meta( $popup->ID, '_adp_popup_esc_close', true );
			$popup_f4_close               = adp_get_post_meta( $popup->ID, '_adp_popup_f4_close', true );

			// Check show popup.
			if ( ! adp_is_popup_visible( $popup->ID ) ) {
				continue;
			}

			$has_post_thumbnail = has_post_thumbnail( $popup->ID ) && 'none' !== $popup_preview_image;

			// Default location for notification bar.
			if ( 'notification-bar' === $popup_type ) {
				if ( 'top' !== $popup_location && 'bottom' !== $popup_location ) {
					$popup_location = 'bottom';
				}
			}

			// Set popup width.
			if ( 'content' === $popup_type ) {

				$popup_width = $popup_content_box_width . 'px';

				if ( $has_post_thumbnail ) {
					$popup_width = ( $popup_content_box_width * 2 ) . 'px';
				}
			} elseif ( 'notification-box' === $popup_type ) {

				$popup_width = $popup_notification_box_width . 'px';

			} else {

				$popup_width = '100%';
			}

			// Set Popup CSS.
			$popup_style = sprintf( 'width:%s;', $popup_width );

			// Set Outer CSS.
			$outer_style = sprintf( 'max-width:%s;', '100%' );

			if ( 'notification-bar' === $popup_type ) {
				$outer_style = sprintf( 'max-width:%s;', $popup_notification_bar_width . 'px' );
			}

			// Popup clasess.
			$class  = 'adp-popup';
			$class .= ' adp-popup-type-' . esc_attr( $popup_type );
			$class .= ' adp-popup-location-' . esc_attr( $popup_location );
			$class .= ' adp-preview-image-' . esc_attr( $popup_preview_image );
			$class .= ' adp-preview-image-' . esc_attr( $has_post_thumbnail ? 'yes' : 'no' );

			// Filter clasess.
			$class = apply_filters( 'adp_popup_clasess', $class, $popup->ID, $popup_type, $popup_location );
			?>
			<div class="<?php echo esc_attr( $class ); ?>"
				data-limit-display="<?php echo esc_attr( $popup_limit_display ); ?>"
				data-limit-lifetime="<?php echo esc_attr( $popup_limit_lifetime ); ?>"
				data-open-trigger="<?php echo esc_attr( $popup_open_trigger ); ?>"
				data-open-delay-number="<?php echo esc_attr( $popup_open_delay_number ); ?>"
				data-open-scroll-position="<?php echo esc_attr( $popup_open_scroll_position ); ?>"
				data-open-scroll-type="<?php echo esc_attr( $popup_open_scroll_type ); ?>"
				data-open-manual-selector="<?php echo esc_attr( $popup_open_manual_selector ); ?>"
				data-close-trigger="<?php echo esc_attr( $popup_close_trigger ); ?>"
				data-close-delay-number="<?php echo esc_attr( $popup_close_delay_number ); ?>"
				data-close-scroll-position="<?php echo esc_attr( $popup_close_scroll_position ); ?>"
				data-close-scroll-type="<?php echo esc_attr( $popup_close_scroll_type ); ?>"
				data-open-animation="<?php echo esc_attr( $popup_open_animation ); ?>"
				data-exit-animation="<?php echo esc_attr( $popup_exit_animation ); ?>"
				data-light-close="<?php echo esc_attr( $popup_light_close ? 'true' : 'false' ); ?>"
				data-overlay="<?php echo esc_attr( $popup_display_overlay ? 'true' : 'false' ); ?>"
				data-mobile-disable="<?php echo esc_attr( $popup_mobile_disable ? 'true' : 'false' ); ?>"
				data-body-scroll-disable="<?php echo esc_attr( $popup_body_scroll_disable ? 'true' : 'false' ); ?>"
				data-overlay-close="<?php echo esc_attr( $popup_overlay_close ? 'true' : 'false' ); ?>"
				data-esc-close="<?php echo esc_attr( $popup_esc_close ? 'true' : 'false' ); ?>"
				data-f4-close="<?php echo esc_attr( $popup_f4_close ? 'true' : 'false' ); ?>"
				data-id="<?php echo esc_attr( $popup->ID ); ?>"
				style="<?php echo esc_attr( $popup_style ); ?>">

				<div class="adp-popup-wrap">

					<div class="adp-popup-container">

						<!-- Content -->
						<?php if ( 'content' === $popup_type ) { ?>
							<div class="adp-popup-outer" style="<?php echo esc_attr( $outer_style ); ?>">
								<?php if ( $has_post_thumbnail ) { ?>
									<div class="adp-popup-thumbnail">
										<?php echo get_the_post_thumbnail( $popup->ID, 'large', array( 'class' => 'adp-lazyload-disabled' ) ); ?>
									</div>
								<?php } ?>

								<div class="adp-popup-content">
									<div class="adp-popup-inner">
										<?php
											$content = do_blocks( $popup->post_content );

											echo do_shortcode( $content ); // XSS.
										?>
									</div>

									<button type="button" class="adp-popup-close"></button>
								</div>
							</div>
						<?php } ?>

						<!-- Info -->
						<?php if ( 'notification-box' === $popup_type || 'notification-bar' === $popup_type ) { ?>
							<div class="adp-popup-outer" style="<?php echo esc_attr( $outer_style ); ?>">
								<?php if ( $popup_info_text ) { ?>
									<div class="adp-popup-text">
										<?php echo wp_kses( $popup_info_text, 'post' ); ?>
									</div>
								<?php } ?>

								<?php if ( $popup_info_buton_label ) { ?>
									<?php if ( 'accept' === $popup_info_button_action ) { ?>
										<button class="adp-button adp-popup-button adp-popup-accept">
											<?php echo wp_kses( $popup_info_buton_label, 'post' ); ?>
										</button>
									<?php } ?>

									<?php if ( 'link' === $popup_info_button_action ) { ?>
										<a class="adp-button adp-popup-button" target="_blank" href="<?php echo esc_attr( $popup_info_button_link ); ?>">
											<?php echo wp_kses( $popup_info_buton_label, 'post' ); ?>
										</a>
									<?php } ?>
								<?php } ?>

								<button type="button" class="adp-popup-close"></button>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<?php if ( $popup_display_overlay ) { ?>
				<div class="adp-popup-overlay"></div>
			<?php } ?>

			<?php
			// Integration css of powerkit blocks.
			if ( function_exists( 'cnvs_gutenberg' ) ) {
				// Parse blocks.
				$blocks = parse_blocks( $popup->post_content );

				$blocks_css = cnvs_gutenberg()->parse_blocks_css( $blocks );

				if ( $blocks_css ) {
					echo sprintf( '<style>%s</style>', $blocks_css ); // XSS.
				}
			}
		}
	}

	/**
	 * Fire the wp_head action.
	 */
	public function wp_head() {
		?>
		<link rel="preload" href="<?php echo esc_url( ADP_URL . 'fonts/advanced-popups-icons.woff' ); ?>" as="font" type="font/woff" crossorigin>
		<?php
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function wp_enqueue_scripts() {
		// Scripts.
		wp_enqueue_script( $this->adp, plugin_dir_url( __FILE__ ) . 'js/advanced-popups-public.js', array( 'jquery' ), $this->version, false );

		// Styles.
		wp_enqueue_style( $this->adp, adp_style( plugin_dir_url( __FILE__ ) . 'css/advanced-popups-public.css' ), array(), $this->version, 'all' );

		// Add RTL support.
		wp_style_add_data( $this->adp, 'rtl', 'replace' );
	}
}
