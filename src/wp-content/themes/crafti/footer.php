<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

							do_action( 'crafti_action_page_content_end_text' );
							
							// Widgets area below the content
							crafti_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'crafti_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'crafti_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'crafti_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'crafti_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$crafti_body_style = crafti_get_theme_option( 'body_style' );
					$crafti_widgets_name = crafti_get_theme_option( 'widgets_below_page' );
					$crafti_show_widgets = ! crafti_is_off( $crafti_widgets_name ) && is_active_sidebar( $crafti_widgets_name );
					$crafti_show_related = crafti_is_single() && crafti_get_theme_option( 'related_position' ) == 'below_page';
					if ( $crafti_show_widgets || $crafti_show_related ) {
						if ( 'fullscreen' != $crafti_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $crafti_show_related ) {
							do_action( 'crafti_action_related_posts' );
						}

						// Widgets area below page content
						if ( $crafti_show_widgets ) {
							crafti_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $crafti_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'crafti_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'crafti_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! crafti_is_singular( 'post' ) && ! crafti_is_singular( 'attachment' ) ) || ! in_array ( crafti_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="crafti_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'crafti_action_before_footer' );

				// Footer
				$crafti_footer_type = crafti_get_theme_option( 'footer_type' );
				if ( 'custom' == $crafti_footer_type && ! crafti_is_layouts_available() ) {
					$crafti_footer_type = 'default';
				}
				get_template_part( apply_filters( 'crafti_filter_get_template_part', "templates/footer-" . sanitize_file_name( $crafti_footer_type ) ) );

				do_action( 'crafti_action_after_footer' );

			}
			?>

			<?php do_action( 'crafti_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'crafti_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'crafti_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>