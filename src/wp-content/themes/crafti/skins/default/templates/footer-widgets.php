<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.10
 */

// Footer sidebar
$crafti_footer_name    = crafti_get_theme_option( 'footer_widgets' );
$crafti_footer_present = ! crafti_is_off( $crafti_footer_name ) && is_active_sidebar( $crafti_footer_name );
if ( $crafti_footer_present ) {
	crafti_storage_set( 'current_sidebar', 'footer' );
	$crafti_footer_wide = crafti_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $crafti_footer_name ) ) {
		dynamic_sidebar( $crafti_footer_name );
	}
	$crafti_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $crafti_out ) ) {
		$crafti_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $crafti_out );
		$crafti_need_columns = true;   //or check: strpos($crafti_out, 'columns_wrap')===false;
		if ( $crafti_need_columns ) {
			$crafti_columns = max( 0, (int) crafti_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $crafti_columns ) {
				$crafti_columns = min( 4, max( 1, crafti_tags_count( $crafti_out, 'aside' ) ) );
			}
			if ( $crafti_columns > 1 ) {
				$crafti_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $crafti_columns ) . ' widget', $crafti_out );
			} else {
				$crafti_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $crafti_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'crafti_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $crafti_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $crafti_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'crafti_action_before_sidebar', 'footer' );
				crafti_show_layout( $crafti_out );
				do_action( 'crafti_action_after_sidebar', 'footer' );
				if ( $crafti_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $crafti_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'crafti_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
