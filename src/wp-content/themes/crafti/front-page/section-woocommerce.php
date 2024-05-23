<?php
$crafti_woocommerce_sc = crafti_get_theme_option( 'front_page_woocommerce_products' );
if ( ! empty( $crafti_woocommerce_sc ) ) {
	?><div class="front_page_section front_page_section_woocommerce<?php
		$crafti_scheme = crafti_get_theme_option( 'front_page_woocommerce_scheme' );
		if ( ! empty( $crafti_scheme ) && ! crafti_is_inherit( $crafti_scheme ) ) {
			echo ' scheme_' . esc_attr( $crafti_scheme );
		}
		echo ' front_page_section_paddings_' . esc_attr( crafti_get_theme_option( 'front_page_woocommerce_paddings' ) );
		if ( crafti_get_theme_option( 'front_page_woocommerce_stack' ) ) {
			echo ' sc_stack_section_on';
		}
	?>"
			<?php
			$crafti_css      = '';
			$crafti_bg_image = crafti_get_theme_option( 'front_page_woocommerce_bg_image' );
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
		$crafti_anchor_icon = crafti_get_theme_option( 'front_page_woocommerce_anchor_icon' );
		$crafti_anchor_text = crafti_get_theme_option( 'front_page_woocommerce_anchor_text' );
		if ( ( ! empty( $crafti_anchor_icon ) || ! empty( $crafti_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
			echo do_shortcode(
				'[trx_sc_anchor id="front_page_section_woocommerce"'
											. ( ! empty( $crafti_anchor_icon ) ? ' icon="' . esc_attr( $crafti_anchor_icon ) . '"' : '' )
											. ( ! empty( $crafti_anchor_text ) ? ' title="' . esc_attr( $crafti_anchor_text ) . '"' : '' )
											. ']'
			);
		}
	?>
		<div class="front_page_section_inner front_page_section_woocommerce_inner
			<?php
			if ( crafti_get_theme_option( 'front_page_woocommerce_fullheight' ) ) {
				echo ' crafti-full-height sc_layouts_flex sc_layouts_columns_middle';
			}
			?>
				"
				<?php
				$crafti_css      = '';
				$crafti_bg_mask  = crafti_get_theme_option( 'front_page_woocommerce_bg_mask' );
				$crafti_bg_color_type = crafti_get_theme_option( 'front_page_woocommerce_bg_color_type' );
				if ( 'custom' == $crafti_bg_color_type ) {
					$crafti_bg_color = crafti_get_theme_option( 'front_page_woocommerce_bg_color' );
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
			<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
				<?php
				// Content wrap with title and description
				$crafti_caption     = crafti_get_theme_option( 'front_page_woocommerce_caption' );
				$crafti_description = crafti_get_theme_option( 'front_page_woocommerce_description' );
				if ( ! empty( $crafti_caption ) || ! empty( $crafti_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					// Caption
					if ( ! empty( $crafti_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo ! empty( $crafti_caption ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( $crafti_caption, 'crafti_kses_content' );
						?>
						</h2>
						<?php
					}

					// Description (text)
					if ( ! empty( $crafti_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo ! empty( $crafti_description ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( wpautop( $crafti_description ), 'crafti_kses_content' );
						?>
						</div>
						<?php
					}
				}

				// Content (widgets)
				?>
				<div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs">
					<?php
					if ( 'products' == $crafti_woocommerce_sc ) {
						$crafti_woocommerce_sc_ids      = crafti_get_theme_option( 'front_page_woocommerce_products_per_page' );
						$crafti_woocommerce_sc_per_page = count( explode( ',', $crafti_woocommerce_sc_ids ) );
					} else {
						$crafti_woocommerce_sc_per_page = max( 1, (int) crafti_get_theme_option( 'front_page_woocommerce_products_per_page' ) );
					}
					$crafti_woocommerce_sc_columns = max( 1, min( $crafti_woocommerce_sc_per_page, (int) crafti_get_theme_option( 'front_page_woocommerce_products_columns' ) ) );
					echo do_shortcode(
						"[{$crafti_woocommerce_sc}"
										. ( 'products' == $crafti_woocommerce_sc
												? ' ids="' . esc_attr( $crafti_woocommerce_sc_ids ) . '"'
												: '' )
										. ( 'product_category' == $crafti_woocommerce_sc
												? ' category="' . esc_attr( crafti_get_theme_option( 'front_page_woocommerce_products_categories' ) ) . '"'
												: '' )
										. ( 'best_selling_products' != $crafti_woocommerce_sc
												? ' orderby="' . esc_attr( crafti_get_theme_option( 'front_page_woocommerce_products_orderby' ) ) . '"'
													. ' order="' . esc_attr( crafti_get_theme_option( 'front_page_woocommerce_products_order' ) ) . '"'
												: '' )
										. ' per_page="' . esc_attr( $crafti_woocommerce_sc_per_page ) . '"'
										. ' columns="' . esc_attr( $crafti_woocommerce_sc_columns ) . '"'
						. ']'
					);
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
