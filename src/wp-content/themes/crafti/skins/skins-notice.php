<?php
/**
 * The template to display Admin notices
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.64
 */

$crafti_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$crafti_skins_args = get_query_var( 'crafti_skins_notice_args' );
?>
<div class="crafti_admin_notice crafti_skins_notice notice notice-info is-dismissible" data-notice="skins">
	<?php
	// Theme image
	$crafti_theme_img = crafti_get_file_url( 'screenshot.jpg' );
	if ( '' != $crafti_theme_img ) {
		?>
		<div class="crafti_notice_image"><img src="<?php echo esc_url( $crafti_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'crafti' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="crafti_notice_title">
		<?php esc_html_e( 'New skins available', 'crafti' ); ?>
	</h3>
	<?php

	// Description
	$crafti_total      = $crafti_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$crafti_skins_msg  = $crafti_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $crafti_total, 'crafti' ), $crafti_total ) . '</strong>'
							: '';
	$crafti_total      = $crafti_skins_args['free'];
	$crafti_skins_msg .= $crafti_total > 0
							? ( ! empty( $crafti_skins_msg ) ? ' ' . esc_html__( 'and', 'crafti' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $crafti_total, 'crafti' ), $crafti_total ) . '</strong>'
							: '';
	$crafti_total      = $crafti_skins_args['pay'];
	$crafti_skins_msg .= $crafti_skins_args['pay'] > 0
							? ( ! empty( $crafti_skins_msg ) ? ' ' . esc_html__( 'and', 'crafti' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $crafti_total, 'crafti' ), $crafti_total ) . '</strong>'
							: '';
	?>
	<div class="crafti_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'crafti' ), $crafti_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="crafti_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $crafti_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'crafti' );
			?>
		</a>
	</div>
</div>
