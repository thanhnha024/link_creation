<?php
/**
 * The template to display Admin notices
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.1
 */

$crafti_theme_slug = get_option( 'template' );
$crafti_theme_obj  = wp_get_theme( $crafti_theme_slug );
?>
<div class="crafti_admin_notice crafti_welcome_notice notice notice-info is-dismissible" data-notice="admin">
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
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'crafti' ),
				$crafti_theme_obj->get( 'Name' ) . ( CRAFTI_THEME_FREE ? ' ' . __( 'Free', 'crafti' ) : '' ),
				$crafti_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="crafti_notice_text">
		<p class="crafti_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $crafti_theme_obj->description ) );
			?>
		</p>
		<p class="crafti_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'crafti' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="crafti_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=crafti_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'crafti' );
			?>
		</a>
	</div>
</div>
