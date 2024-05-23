"use strict";

/* Basic -------------------------------------------------------------- */

(function($) {
	var adpMetabox = {};

	( function() {
		var $this;

		adpMetabox = {
			/*
			* Initialize
			*/
			init: function( e ) {
				$this = adpMetabox;

				// Variables.
				$this.wrap = $( '.adp-metabox-wrap' );

				// Init.
				$this.metaboxInit( e );

				// Init events.
				$this.events( e );
			},

			/*
			* Events
			*/
			events: function( e ) {
				// Custom Events
				$this.wrap.on( 'click change keyup keydown', '.adp-metabox-repeater .attribute-name', $this.setSignature );
				$this.wrap.on( 'click', '.adp-metabox-repeater .row-topbar', $this.toggleItems );
				$this.wrap.on( 'click', '.adp-metabox-repeater .btn-remove-row', $this.removeRepeaterRow );
				$this.wrap.on( 'click', '.adp-metabox-repeater .btn-add-row', $this.addRepeaterRow );
			},

			/*
			* Init metabox elements
			*/
			metaboxInit: function( e ) {
				// Add tabs for Meta Box (UI)
				$this.wrap.find( '.adp-metabox-tabs' ).tabs();

				// Repeater sortable
				$this.wrap.find( '.adp-metabox-repeater tbody' ).sortable( {
					items: 'tr',
					placeholder: 'ui-state-highlight',
					handle: '.row-topbar, .row-handle',
					start: function( e, ui ) {
						ui.placeholder.height( ui.item.height() );
					},
				} );
			},

			/*
			* Toggle items
			*/
			toggleItems: function() {
				if ( $( this ).hasClass( 'closed' ) ) {
					$( this ).removeClass( 'closed' );

					$( this ).siblings( '.row-fields' ).slideDown();
				} else {
					$( this ).addClass( 'closed' );

					$( this ).siblings( '.row-fields' ).slideUp();
				}
			},

			/*
			* Set signature
			*/
			setSignature: function() {

				var label = '<span>' + $( this ).data( 'label' ) + '</span>';

				var value = $( this ).val() ? $( this ).val() : label;

				$( this ).parents( '.row-content' ).find( '.signature' ).html( value );
			},

			/*
			* Add repeater row
			*/
			addRepeaterRow: function() {

				var repeater = $( this ).siblings( '.adp-metabox-repeater-table' )

				// Get html row.
				var html = repeater.find( 'tbody tr.hidden' ).html();

				// Add new row.
				repeater.find( 'tbody' ).append( '<tr class="row">' + html + '</tr>' );

				// Visible all input and textarea.
				repeater.find( 'tr' ).not( '.hidden' ).find( 'input, textarea' ).removeAttr( 'disabled' );

				return false;
			},

			/*
			* Remove repeater row
			*/
			removeRepeaterRow: function() {
				$( this ).parents( '.row' ).remove();

				return false;
			}
		};

	} )();

	// Initialize.
	$( function() {
		adpMetabox.init();
	});

})(jQuery);

/* Popup -------------------------------------------------------------- */

(function($) {
	var adpPopup = {};

	( function() {
		var $this;

		adpPopup = {
			/*
			* Initialize
			*/
			init: function( e ) {
				$this = adpPopup;

				// Variables.
				$this.wrap = $( '.popup-wrap' );

				// Init.
				$this.popupInit( e );

				// Init events.
				$this.events( e );
			},

			/*
			* Events
			*/
			events: function( e ) {
				// Custom Events
				$this.wrap.on( 'change', 'select[name="adp_popup_type"]', $this.actionType );
				$this.wrap.on( 'change', 'select[name="adp_popup_info_button_action"]', $this.actionInfoAct );
				$this.wrap.on( 'change click', 'input[name="adp_popup_open_trigger"]', $this.actionOpenTrigger );
				$this.wrap.on( 'change click', 'input[name="adp_popup_close_trigger"]', $this.actionCloseTrigger );
				$this.wrap.on( 'change click', 'input[name="adp_popup_rules_mode"]', $this.actionRulesMode );
				$this.wrap.on( 'click', '.add-new-rule', $this.addNewRule );
				$this.wrap.on( 'click', '.add-another-rule', $this.addAnotherRule );
				$this.wrap.on( 'click', '.remove-rule', $this.removeRule );
				$this.wrap.on( 'click', '.remove-another-rule', $this.removeAnotherRule );
				$this.wrap.on( 'change', '.adp-popup-rules', $this.actionPopupRules );
			},

			/*
			* Init popup elements
			*/
			popupInit: function( e ) {
				$this.actionType( 'select[name="adp_popup_type"]' );
				$this.actionInfoAct( 'select[name="adp_popup_info_button_action"]' );
				$this.actionOpenTrigger( 'input[name="adp_popup_open_trigger"]:checked' );
				$this.actionCloseTrigger( 'input[name="adp_popup_close_trigger"]:checked' );
				$this.actionRulesMode( 'input[name="adp_popup_rules_mode"]:checked' );
				$this.actionPopupRules( '.adp-popup-rules' );
			},

			/*
			* Action popup type
			*/
			actionType: function( e ) {
				let val = $( typeof e === 'string' ? e : this ).val();

				// Set editor easy and hide overlay close.
				if ( 'content' !== val ) {

					$( '.popup-field-overlay-close' ).addClass( 'hidden' );

					$( '.block-editor' ).addClass( 'popup-block-easy' );

					$( '.editor-post-featured-image' ).parents( '.components-panel__body' ).hide();
				} else {
					$( '.popup-field-overlay-close' ).removeClass( 'hidden' );

					$( '.block-editor' ).removeClass( 'popup-block-easy' );

					$( '.editor-post-featured-image' ).parents( '.components-panel__body' ).show();
				}

				// Hide preview image and content width.
				if ( 'content' !== val ) {
					$( '.popup-field-preview-image' ).addClass( 'hidden' );
					$( '.popup-field-content-box-width' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-preview-image' ).removeClass( 'hidden' );
					$( '.popup-field-content-box-width' ).removeClass( 'hidden' );
				}

				// Hide notification box width.
				if ( 'notification-box' !== val ) {
					$( '.popup-field-notification-box-width' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-notification-box-width' ).removeClass( 'hidden' );
				}

				// Hide notification bar width.
				if ( 'notification-bar' !== val ) {
					$( '.popup-field-notification-bar-width' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-notification-bar-width' ).removeClass( 'hidden' );
				}

				// Hide location.
				if ( 'content' !== val && 'notification-box' !== val ) {
					$( 'select[name="adp_popup_location"] option[value="top-left"]' ).addClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="top-right"]' ).addClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="bottom-left"]' ).addClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="bottom-right"]' ).addClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="left"]' ).addClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="right"]' ).addClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="center"]' ).addClass( 'hidden' );

					let location = $( 'select[name="adp_popup_location"]' ).val();

					if ( 'top' !== location && 'bottom' !== location ) {
						$( 'select[name="adp_popup_location"] option[value="bottom"]' ).prop( 'selected', true );
					}
				} else {
					$( 'select[name="adp_popup_location"] option[value="top-left"]' ).removeClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="top-right"]' ).removeClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="bottom-left"]' ).removeClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="bottom-right"]' ).removeClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="left"]' ).removeClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="right"]' ).removeClass( 'hidden' );
					$( 'select[name="adp_popup_location"] option[value="center"]' ).removeClass( 'hidden' );
				}
			},

			/*
			* Action info button act.
			*/
			actionInfoAct: function( e ) {
				let val = $( typeof e === 'string' ? e : this ).val();

				if ( 'link' !== val ) {
					$( '.popup-field-info-buton-link' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-info-buton-link' ).removeClass( 'hidden' );
				}
			},

			/*
			* Action open trigger.
			*/
			actionOpenTrigger: function( e ) {
				let val = $( typeof e === 'string' ? e : this ).val();

				if ( 'delay' !== val ) {
					$( '.popup-field-open-delay-number' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-open-delay-number' ).removeClass( 'hidden' );
				}

				if ( 'scroll' !== val ) {
					$( '.popup-field-open-scroll-position' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-open-scroll-position' ).removeClass( 'hidden' );
				}

				if ( 'accept' !== val ) {
					$( '.popup-field-open-accept-desc' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-open-accept-desc' ).removeClass( 'hidden' );
				}

				if ( 'manual' !== val ) {
					$( '.popup-field-open-manual-selector' ).addClass( 'hidden' );
					$( '.popup-field-limit-display' ).removeClass( 'hidden' );
					$( '.popup-field-limit-lifetime' ).removeClass( 'hidden' );
				} else {
					$( '.popup-field-limit-display' ).addClass( 'hidden' );
					$( '.popup-field-limit-lifetime' ).addClass( 'hidden' );
					$( '.popup-field-open-manual-selector' ).removeClass( 'hidden' );
				}
			},

			/*
			* Action close trigger.
			*/
			actionCloseTrigger: function( e ) {
				let val = $( typeof e === 'string' ? e : this ).val();

				if ( 'delay' !== val ) {
					$( '.popup-field-close-delay-number' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-close-delay-number' ).removeClass( 'hidden' );
				}

				if ( 'scroll' !== val ) {
					$( '.popup-field-close-scroll-position' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-close-scroll-position' ).removeClass( 'hidden' );
				}
			},

			/*
			* Action rules mode.
			*/
			actionRulesMode: function( e ) {
				let val = $( typeof e === 'string' ? e : this ).val();

				if ( 'specific' !== val ) {
					$( '.popup-field-rules' ).addClass( 'hidden' );
				} else {
					$( '.popup-field-rules' ).removeClass( 'hidden' );
				}
			},

			/*
			* Build select rules.
			*/
			buildSelectRules: function() {

				var list = JSON.parse( adp_popup_data.rules_list );

				var output = '<select class="adp-popup-rules">';

				for ( var optgroup in list ) {

					let label = optgroup;

					label = label.replace( 'general', adp_popup_data.label_general );
					label = label.replace( 'post_types', adp_popup_data.label_post_types );
					label = label.replace( 'taxonomies', adp_popup_data.label_taxonomies );

					output += '<optgroup data-group="' + optgroup + '" label="' + label + '">';

					for ( var event in list[ optgroup ] ) {

						output += '<option value="' + event + '">';

						 // Name of option.
						output += list[ optgroup ][ event ];

						output += '</option>';
					}

					output += '</optgroup>';
				}

				output += '</select>';

				return output;
			},

			/*
			* Build input url.
			*/
			buildInputUrl: function() {
				var output = '<input type="text" class="adp-popup-url">';

				return output;
			},

			/*
			* Build select objects.
			*/
			buildSelectObjects: function() {
				var output = '<select multiple class="adp-popup-objects"></select>';

				return output;
			},

			/*
			* Build row rules.
			*/
			buildRowRules: function() {
				var output = '<div class="row">';

				// Add new tools.
				output += $this.buildToolsRules();

				output += '<div class="tools-bar">';

				// Add button another OR rule.
				output += '<div class="button add-another-rule">';

				output += adp_popup_data.btn_label_another;

				output += '</div>';

				output += '<a href="#" class="delete remove-rule">';

				output += adp_popup_data.btn_delete;

				output += '</a>';

				output += '</div>';

				// Close.
				output += '</div>';

				return output;
			},

			/*
			* Build tools rules.
			*/
			buildToolsRules: function() {
				var output = '<div class="tools">';

				output += $this.buildSelectRules();

				output += $this.buildInputUrl();

				output += $this.buildSelectObjects();

				output += '<a href="#" class="delete remove-another-rule">';

				output += '<span class="dashicons dashicons-no-alt"></span>'

				output += '</a>';

				output += '</div>';

				return output;
			},

			/*
			* Add new rule.
			*/
			addNewRule: function() {

				var row = $this.buildRowRules();

				$( row ).appendTo( '.popup-field-rules-list' );

				$this.setIndexRules();

				$this.popupInit();
			},

			/*
			* Add anothe rule.
			*/
			addAnotherRule: function() {
				var tools = $this.buildToolsRules();

				$( this ).parents( '.tools-bar' ).before( tools );

				$this.setIndexRules();

				$this.popupInit();
			},

			/*
			* Remove new rule.
			*/
			removeRule: function() {
				let list = $( this ).parents( '.popup-field-rules-list' );

				$( this ).parents( '.row' ).remove();

				if ( ! $( list ).find( '.row' ).length ) {
					$( list ).html('');
				}

				$this.setIndexRules();
			},

			/*
			* Remove anothe rule.
			*/
			removeAnotherRule: function() {

				let list = $( this ).parents( '.popup-field-rules-list' );

				let row = $( this ).parents( '.row' );

				if ( $( row ).find('.tools').length <= 1 ) {
					$( this ).parents( '.row' ).remove();

					if ( ! $( list ).find( '.row' ).length ) {
						$( list ).html('');
					}
				} else {
					$( this ).parents( '.tools' ).remove();
				}

				$this.setIndexRules();
			},

			/*
			* Set index rules.
			*/
			setIndexRules: function() {
				$( '.popup-field-rules-list .row' ).each(function(i, row) {

					$( row ).find( '.tools' ).each(function(t, tools) {
						// Select rules.
						$( tools ).find( '.adp-popup-rules' ).each(function(j, select) {
							$( select ).attr( 'name', 'adp_popup_rules[' + i + '][' + t + '][rule]' );
						});

						// Input url.
						$( tools ).find( '.adp-popup-url' ).each(function(j, input) {
							$( input ).attr( 'name', 'adp_popup_rules[' + i + '][' + t + '][url]' );
						});

						// Input objects.
						$( tools ).find( '.adp-popup-objects' ).each(function(j, select) {
							$( select ).attr( 'name', 'adp_popup_rules[' + i + '][' + t + '][object][]'.replace( 'index', i ) );
						});
					});
				});
			},

			/*
			* Action poopup rules.
			*/
			actionPopupRules: function( e ) {
				let el = $( typeof e === 'string' ? e : this );

				// Action.
				if ( typeof e !== 'string' ) {
					let select = $( el ).siblings( '.adp-popup-objects' );

					let group = $( el ).find('option:selected').parents('optgroup').data( 'group' );

					if ( 'general' === group ) {
						$( el ).siblings( '.adp-popup-objects' ).addClass( 'hidden' );
					} else {
						$( el ).siblings( '.adp-popup-objects' ).removeClass( 'hidden' );
					}

					if ( 'url' !== $( el ).val() ) {
						$( el ).siblings( '.adp-popup-url' ).addClass( 'hidden' );
					} else {
						$( el ).siblings( '.adp-popup-url' ).removeClass( 'hidden' );
					}

					// Reset options.
					$( select ).html( '' );

					// Init Select2.
					$this.setObjectsSelect2( select );

				// Init.
				} else {

					$( el ).each(function(i, rule) {
						let select = $( rule ).siblings( '.adp-popup-objects' );

						let group = $( rule ).find('option:selected').parents('optgroup').data( 'group' );

						if ( 'general' === group ) {
							$( rule ).siblings( '.adp-popup-objects' ).addClass( 'hidden' );
						} else {
							$( rule ).siblings( '.adp-popup-objects' ).removeClass( 'hidden' );
						}

						if ( 'url' !== $( rule ).val() ) {
							$( rule ).siblings( '.adp-popup-url' ).addClass( 'hidden' );
						} else {
							$( rule ).siblings( '.adp-popup-url' ).removeClass( 'hidden' );
						}

						// Init Select2.
						$this.setObjectsSelect2( select );
					});
				}
			},

			/*
			* Set objects Select2.
			*/
			setObjectsSelect2: function( el ) {

				var rules = $( el ).siblings('.adp-popup-rules');

				var group = $( rules ).find('option:selected').parents('optgroup').data( 'group' );

				var rule = $( rules ).find('option:selected').val();

				$( el ).select2( {
					placeholder: adp_popup_data.select2_placeholder,
					minimumInputLength: 0,
					language: {
						errorLoading: function() {
							return adp_popup_data.select2_errorLoading;
						},
						loadingMore: function() {
							return adp_popup_data.select2_loadingMore;
						},
						noResults: function() {
							return adp_popup_data.select2_noResults;
						},
						searching: function() {
							return adp_popup_data.select2_searching;
						},
						removeAllItems: function() {
							return adp_popup_data.select2_removeAllItems;
						}
					},
					delay: 250,
					multiple: true,
					width: '100%',
					ajax: {
						url: adp_popup_data.ajaxurl,
						dataType: 'json',
						quietMillis: 100,
						data: function (params) {

							var query = {
								group: group,
								rule: rule,
								search: params.term,
								page: params.page || 1,
								_wpnonce: adp_popup_data.nonce,
								action: 'adp_popup_rules_objects',
							}

							return query;
						},
						cache: true
					},
				} );
			}
		};

	} )();

	// Initialize.
	$( function() {
		adpPopup.init();
	});

})(jQuery);
