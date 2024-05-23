<div class="front_page_section front_page_section_googlemap<?php
	$crafti_scheme = crafti_get_theme_option( 'front_page_googlemap_scheme' );
	if ( ! empty( $crafti_scheme ) && ! crafti_is_inherit( $crafti_scheme ) ) {
		echo ' scheme_' . esc_attr( $crafti_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( crafti_get_theme_option( 'front_page_googlemap_paddings' ) );
	if ( crafti_get_theme_option( 'front_page_googlemap_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$crafti_css      = '';
		$crafti_bg_image = crafti_get_theme_option( 'front_page_googlemap_bg_image' );
		if ( ! empty( $crafti_bg_image ) ) {
			$crafti_css .= 'background-image: url(' . esc_url( crafti_get_attachment_url( $crafti_bg_image ) ) . ');';
		}
		if ( ! empty( $crafti_css ) ) {
			echo ' style="' . esc_attr( $crafti_css ) . '"';
		}
		?>
>
<?php
	// Add anchor
	$crafti_anchor_icon = crafti_get_theme_option( 'front_page_googlemap_anchor_icon' );
	$crafti_anchor_text = crafti_get_theme_option( 'front_page_googlemap_anchor_text' );
if ( ( ! empty( $crafti_anchor_icon ) || ! empty( $crafti_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_googlemap"'
									. ( ! empty( $crafti_anchor_icon ) ? ' icon="' . esc_attr( $crafti_anchor_icon ) . '"' : '' )
									. ( ! empty( $crafti_anchor_text ) ? ' title="' . esc_attr( $crafti_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_googlemap_inner
		<?php
		$crafti_layout = crafti_get_theme_option( 'front_page_googlemap_layout' );
		echo ' front_page_section_layout_' . esc_attr( $crafti_layout );
		if ( crafti_get_theme_option( 'front_page_googlemap_fullheight' ) ) {
			echo ' crafti-full-height sc_layouts_flex sc_layouts_columns_middle';
		}
		?>
		"
			<?php
			$crafti_css      = '';
			$crafti_bg_mask  = crafti_get_theme_option( 'front_page_googlemap_bg_mask' );
			$crafti_bg_color_type = crafti_get_theme_option( 'front_page_googlemap_bg_color_type' );
			if ( 'custom' == $crafti_bg_color_type ) {
				$crafti_bg_color = crafti_get_theme_option( 'front_page_googlemap_bg_color' );
			} elseif ( 'scheme_bg_color' == $crafti_bg_color_type ) {
				$crafti_bg_color = crafti_get_scheme_color( 'bg_color', $crafti_scheme );
			} else {
				$crafti_bg_color = '';
			}
			if ( ! empty( $crafti_bg_color ) && $crafti_bg_mask > 0 ) {
				$crafti_css .= 'background-color: ' . esc_attr(
					1 == $crafti_bg_mask ? $crafti_bg_color : crafti_hex2rgba( $crafti_bg_color, $crafti_bg_mask )
				) . ';';
			}
			if ( ! empty( $crafti_css ) ) {
				echo ' style="' . esc_attr( $crafti_css ) . '"';
			}
			?>
	>
		<div class="front_page_section_content_wrap front_page_section_googlemap_content_wrap
		<?php
		if ( 'fullwidth' != $crafti_layout ) {
			echo ' content_wrap';
		}
		?>
		">
			<?php
			// Content wrap with title and description
			$crafti_caption     = crafti_get_theme_option( 'front_page_googlemap_caption' );
			$crafti_description = crafti_get_theme_option( 'front_page_googlemap_description' );
			if ( ! empty( $crafti_caption ) || ! empty( $crafti_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'fullwidth' == $crafti_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}
					// Caption
				if ( ! empty( $crafti_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_googlemap_caption front_page_block_<?php echo ! empty( $crafti_caption ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $crafti_caption, 'crafti_kses_content' );
					?>
					</h2>
					<?php
				}

					// Description (text)
				if ( ! empty( $crafti_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_googlemap_description front_page_block_<?php echo ! empty( $crafti_description ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( wpautop( $crafti_description ), 'crafti_kses_content' );
					?>
					</div>
					<?php
				}
				if ( 'fullwidth' == $crafti_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$crafti_content = crafti_get_theme_option( 'front_page_googlemap_content' );
			if ( ! empty( $crafti_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'columns' == $crafti_layout ) {
					?>
					<div class="front_page_section_columns front_page_section_googlemap_columns columns_wrap">
						<div class="column-1_3">
					<?php
				} elseif ( 'fullwidth' == $crafti_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}

				?>
				<div class="front_page_section_content front_page_section_googlemap_content front_page_block_<?php echo ! empty( $crafti_content ) ? 'filled' : 'empty'; ?>">
				<?php
					echo wp_kses( $crafti_content, 'crafti_kses_content' );
				?>
				</div>
				<?php

				if ( 'columns' == $crafti_layout ) {
					?>
					</div><div class="column-2_3">
					<?php
				} elseif ( 'fullwidth' == $crafti_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Widgets output
			?>
			<div class="front_page_section_output front_page_section_googlemap_output">
				<?php
				if ( is_active_sidebar( 'front_page_googlemap_widgets' ) ) {
					dynamic_sidebar( 'front_page_googlemap_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! crafti_exists_trx_addons() ) {
						crafti_customizer_need_trx_addons_message();
					} else {
						crafti_customizer_need_widgets_message( 'front_page_googlemap_caption', 'ThemeREX Addons - Google map' );
					}
				}
				?>
			</div>
			<?php

			if ( 'columns' == $crafti_layout && ( ! empty( $crafti_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>
		</div>
	</div>
</div>
