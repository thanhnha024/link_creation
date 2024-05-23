<div class="front_page_section front_page_section_about<?php
	$crafti_scheme = crafti_get_theme_option( 'front_page_about_scheme' );
	if ( ! empty( $crafti_scheme ) && ! crafti_is_inherit( $crafti_scheme ) ) {
		echo ' scheme_' . esc_attr( $crafti_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( crafti_get_theme_option( 'front_page_about_paddings' ) );
	if ( crafti_get_theme_option( 'front_page_about_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$crafti_css      = '';
		$crafti_bg_image = crafti_get_theme_option( 'front_page_about_bg_image' );
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
	$crafti_anchor_icon = crafti_get_theme_option( 'front_page_about_anchor_icon' );
	$crafti_anchor_text = crafti_get_theme_option( 'front_page_about_anchor_text' );
if ( ( ! empty( $crafti_anchor_icon ) || ! empty( $crafti_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_about"'
									. ( ! empty( $crafti_anchor_icon ) ? ' icon="' . esc_attr( $crafti_anchor_icon ) . '"' : '' )
									. ( ! empty( $crafti_anchor_text ) ? ' title="' . esc_attr( $crafti_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_about_inner
	<?php
	if ( crafti_get_theme_option( 'front_page_about_fullheight' ) ) {
		echo ' crafti-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$crafti_css           = '';
			$crafti_bg_mask       = crafti_get_theme_option( 'front_page_about_bg_mask' );
			$crafti_bg_color_type = crafti_get_theme_option( 'front_page_about_bg_color_type' );
			if ( 'custom' == $crafti_bg_color_type ) {
				$crafti_bg_color = crafti_get_theme_option( 'front_page_about_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_about_content_wrap content_wrap">
			<?php
			// Caption
			$crafti_caption = crafti_get_theme_option( 'front_page_about_caption' );
			if ( ! empty( $crafti_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_about_caption front_page_block_<?php echo ! empty( $crafti_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $crafti_caption, 'crafti_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$crafti_description = crafti_get_theme_option( 'front_page_about_description' );
			if ( ! empty( $crafti_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_about_description front_page_block_<?php echo ! empty( $crafti_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $crafti_description ), 'crafti_kses_content' ); ?></div>
				<?php
			}

			// Content
			$crafti_content = crafti_get_theme_option( 'front_page_about_content' );
			if ( ! empty( $crafti_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_content front_page_section_about_content front_page_block_<?php echo ! empty( $crafti_content ) ? 'filled' : 'empty'; ?>">
					<?php
					$crafti_page_content_mask = '%%CONTENT%%';
					if ( strpos( $crafti_content, $crafti_page_content_mask ) !== false ) {
						$crafti_content = preg_replace(
							'/(\<p\>\s*)?' . $crafti_page_content_mask . '(\s*\<\/p\>)/i',
							sprintf(
								'<div class="front_page_section_about_source">%s</div>',
								apply_filters( 'the_content', get_the_content() )
							),
							$crafti_content
						);
					}
					crafti_show_layout( $crafti_content );
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
