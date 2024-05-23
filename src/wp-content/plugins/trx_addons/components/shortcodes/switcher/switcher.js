/**
 * Shortcode Switcher
 *
 * @package ThemeREX Addons
 * @since v2.6.0
 */

/* global jQuery, TRX_ADDONS_STORAGE */


jQuery( document ).on( 'action.init_hidden_elements', function() {

	"use strict";

	jQuery( '.sc_switcher:not(.sc_switcher_inited)' ).each( function() {

		var $self = jQuery( this ).addClass( 'sc_switcher_inited' ),
			$slider = $self.find( '.sc_switcher_slider' ),
			$sections_wrap = $self.find( '.sc_switcher_sections' ),
			$sections = $self.find( '.sc_switcher_section' );

		// Type 'Default'
		if ( $self.hasClass( 'sc_switcher_default' ) ) {
			var $toggle = $self.find( '.sc_switcher_controls_toggle' );
			// Click on toggle
			$toggle.on( 'click', function() {
				sc_switcher_toggle_state(0);
			} );
			// Click on the left title
			$self.find('.sc_switcher_controls_section1').on( 'click', function() {
				sc_switcher_toggle_state(1);
			} );
			// Click on the right title
			$self.find('.sc_switcher_controls_section2').on( 'click', function() {
				sc_switcher_toggle_state(2);
			} );

		// Type 'Tabs'
		} else {
			var $tabs = $self.find( '.sc_switcher_tab' );
			$tabs.find( '.sc_switcher_tab_link' ).on( 'click', function( e ) {
				var $tab = jQuery( this ).parent(),
					idx = $tab.index();
				$tabs.removeClass( 'sc_switcher_tab_active' );
				$tab.addClass( 'sc_switcher_tab_active' );
				$sections
					.removeClass( 'sc_switcher_section_active' )
					.eq( idx ).addClass( 'sc_switcher_section_active' );
				$slider.get(0).style.setProperty( '--trx-addons-switcher-slide-active', idx );
				sc_switcher_change_height();
				e.preventDefault();
				return false;
			} );
		}

		// Change height of the shortcode container to the height of the active section
		sc_switcher_change_height();

		// Resize action
		jQuery( document ).on( 'action.resize_trx_addons', function() {
			sc_switcher_change_height();
		} );

		// Toggle state (for type 'Default')
		function sc_switcher_toggle_state( state ) {
			if ( $toggle.hasClass( 'sc_switcher_controls_toggle_on' ) ) {
				if ( state === 0 || state == 2 ) {
					$toggle.removeClass( 'sc_switcher_controls_toggle_on' );
					$sections.eq(0).removeClass( 'sc_switcher_section_active' );
					$sections.eq(1).addClass( 'sc_switcher_section_active' );
					//$slider.animate( { left: '50%' }, 300 );
					$slider.get(0).style.setProperty( '--trx-addons-switcher-slide-active', 1 );
					sc_switcher_change_height();
				}
			} else {
				if ( state === 0 || state == 1 ) {
					$toggle.addClass( 'sc_switcher_controls_toggle_on' );
					$sections.eq(0).addClass( 'sc_switcher_section_active' );
					$sections.eq(1).removeClass( 'sc_switcher_section_active' );
					$slider.get(0).style.setProperty( '--trx-addons-switcher-slide-active', 0 );
					sc_switcher_change_height();
				}				
			}
		}

		// Change height of the shortcode container to the height of the active section
		function sc_switcher_change_height() {
			var $active = $sections.filter( '.sc_switcher_section_active' );
			if ( $active.length > 0 ) {
				$sections_wrap.css( 'height', $active.outerHeight() );
			}
		}

	} );

} );