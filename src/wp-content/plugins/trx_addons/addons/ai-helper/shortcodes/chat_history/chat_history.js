/**
 * Shortcode AI Chat History
 *
 * @package ThemeREX Addons
 * @since v2.26.3
 */

/* global jQuery, TRX_ADDONS_STORAGE */


jQuery( document ).ready( function() {

	"use strict";

	var $window             = jQuery( window ),
		$document           = jQuery( document ),
		$body               = jQuery( 'body' );

	$document.on( 'action.init_hidden_elements', function(e, container) {

		if ( container === undefined ) {
			container = $body;
		}

		// Init AI Chat History
		container.find( '.sc_chat_history:not(.sc_chat_history_inited)' ).each( function() {

			var $history = jQuery( this ).addClass( 'sc_chat_history_inited' );

			if ( ! $history.length ) {
				return;
			}

			var chat_id = $history.data( 'chat-id' ) || '',
				$chat = jQuery( ( chat_id ? '#' + chat_id + ' ' : '' ) + '.sc_chat' ).eq(0);
			
			if ( ! $chat.length ) {
				return;
			}

			$history
				.on( 'keypress', '.sc_chat_history_item > a', function(e) {
					if ( e.keyCode == 13 ) {
						e.preventDefault();
						jQuery(this).trigger( 'click' );
					}
				} )
				.on( 'click', '.sc_chat_history_item > a', function(e) {
					$chat.trigger( 'trx_addons_action_sc_chat_update', [jQuery(this).data( 'chat-messages' )] );
				} );

		} );

	} );

	// Add a new topic to the chat history after the chat messages are updated
	trx_addons_add_action( 'trx_addons_action_ai_helper_chat_updated', function( chat, $sc ) {
		if ( ! chat || chat.length == 0 || ! TRX_ADDONS_STORAGE['user_logged_in'] ) {
			return;
		}
		var sc_id = $sc.attr('id'),
			chat_id = sc_id ? sc_id.slice(0, -3) : '';
		var $history = chat_id ? jQuery( '.sc_chat_history[data-chat-id="' + chat_id + '"]' ) : jQuery( '.sc_chat_history' ).eq(0);
		if ( chat_id && ! $history.length ) {
			$history = jQuery( '.sc_chat_history' ).eq(0);
		}
		if ( $history.length ) {
			var chat_topic = chat[0].content,
				updated = false;
			$history.find( '.sc_chat_history_item a' ).each( function() {
				if ( ! updated && jQuery(this).text() == chat_topic ) {
					jQuery(this).data( 'chat-messages', chat );
					updated = true;
				}
			} );
			if ( ! updated ) {
				var $item = $history.find( '.sc_chat_history_item' ).eq(0).clone();
				$item.find( 'a' )
					.text( chat[0].content )
					.data( 'chat-messages', chat );
				$history.find( '.sc_chat_history_list' ).prepend( $item );
			}
		}
	} );

} );