<?php
/**
 * Required plugins
 *
 * @package CRAFTI
 * @since CRAFTI 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$crafti_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'crafti' ),
	'page_builders' => esc_html__( 'Page Builders', 'crafti' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'crafti' ),
	'socials'       => esc_html__( 'Socials and Communities', 'crafti' ),
	'events'        => esc_html__( 'Events and Appointments', 'crafti' ),
	'content'       => esc_html__( 'Content', 'crafti' ),
	'other'         => esc_html__( 'Other', 'crafti' ),
);
$crafti_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'crafti' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'crafti' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $crafti_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'crafti' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'crafti' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $crafti_theme_required_plugins_groups['page_builders'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'crafti' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'crafti' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $crafti_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'crafti' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'crafti' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $crafti_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'crafti' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'crafti' ),
		'required'    => false,
		'logo'        => 'woocommerce.png',
		'group'       => $crafti_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'crafti' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'crafti' ),
		'required'    => false,
		'logo'        => 'elegro-payment.png',
		'group'       => $crafti_theme_required_plugins_groups['ecommerce'],
	),
	'instagram-feed'             => array(
		'title'       => esc_html__( 'Instagram Feed', 'crafti' ),
		'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'crafti' ),
		'required'    => false,
		'logo'        => 'instagram-feed.png',
		'group'       => $crafti_theme_required_plugins_groups['socials'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'crafti' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'crafti' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $crafti_theme_required_plugins_groups['socials'],
	),
	'booked'                     => array(
		'title'       => esc_html__( 'Booked Appointments', 'crafti' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => 'booked.png',
		'group'       => $crafti_theme_required_plugins_groups['events'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'crafti' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $crafti_theme_required_plugins_groups['events'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'crafti' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'crafti' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $crafti_theme_required_plugins_groups['content'],
	),

	'latepoint'                  => array(
		'title'       => esc_html__( 'LatePoint', 'crafti' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => crafti_get_file_url( 'plugins/latepoint/latepoint.png' ),
		'group'       => $crafti_theme_required_plugins_groups['events'],
	),
	'advanced-popups'                  => array(
		'title'       => esc_html__( 'Advanced Popups', 'crafti' ),
		'description' => '',
		'required'    => false,
		'logo'        => crafti_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
		'group'       => $crafti_theme_required_plugins_groups['content'],
	),
	'devvn-image-hotspot'                  => array(
		'title'       => esc_html__( 'Image Hotspot by DevVN', 'crafti' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => crafti_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
		'group'       => $crafti_theme_required_plugins_groups['content'],
	),
	'ti-woocommerce-wishlist'                  => array(
		'title'       => esc_html__( 'TI WooCommerce Wishlist', 'crafti' ),
		'description' => '',
		'required'    => false,
		'logo'        => crafti_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
		'group'       => $crafti_theme_required_plugins_groups['ecommerce'],
	),
	'woo-smart-quick-view'                  => array(
		'title'       => esc_html__( 'WPC Smart Quick View for WooCommerce', 'crafti' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => crafti_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.png' ),
		'group'       => $crafti_theme_required_plugins_groups['ecommerce'],
	),
	'twenty20'                  => array(
		'title'       => esc_html__( 'Twenty20 Image Before-After', 'crafti' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => crafti_get_file_url( 'plugins/twenty20/twenty20.png' ),
		'group'       => $crafti_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'crafti' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'essential-grid.png',
		'group'       => $crafti_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'crafti' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'revslider.png',
		'group'       => $crafti_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'crafti' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'crafti' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $crafti_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'Cookie Information', 'crafti' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'crafti' ),
		'required'    => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $crafti_theme_required_plugins_groups['other'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'crafti' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'crafti' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $crafti_theme_required_plugins_groups['other'],
	),
);

if ( CRAFTI_THEME_FREE ) {
	unset( $crafti_theme_required_plugins['js_composer'] );
	unset( $crafti_theme_required_plugins['booked'] );
	unset( $crafti_theme_required_plugins['the-events-calendar'] );
	unset( $crafti_theme_required_plugins['calculated-fields-form'] );
	unset( $crafti_theme_required_plugins['essential-grid'] );
	unset( $crafti_theme_required_plugins['revslider'] );
	unset( $crafti_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $crafti_theme_required_plugins['trx_updater'] );
	unset( $crafti_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
crafti_storage_set( 'required_plugins', $crafti_theme_required_plugins );
