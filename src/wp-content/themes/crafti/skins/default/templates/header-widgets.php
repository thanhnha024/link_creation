<?php
/**
 * The template to display the widgets area in the header
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

// Header sidebar
$crafti_header_name    = crafti_get_theme_option( 'header_widgets' );
$crafti_header_present = ! crafti_is_off( $crafti_header_name ) && is_active_sidebar( $crafti_header_name );
if ( $crafti_header_present ) {
	crafti_storage_set( 'current_sidebar', 'header' );
	$crafti_header_wide = crafti_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $crafti_header_name ) ) {
		dynamic_sidebar( $crafti_header_name );
	}
	$crafti_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $crafti_widgets_output ) ) {
		$crafti_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $crafti_widgets_output );
		$crafti_need_columns   = strpos( $crafti_widgets_output, 'columns_wrap' ) === false;
		if ( $crafti_need_columns ) {
			$crafti_columns = max( 0, (int) crafti_get_theme_option( 'header_columns' ) );
			if ( 0 == $crafti_columns ) {
				$crafti_columns = min( 6, max( 1, crafti_tags_count( $crafti_widgets_output, 'aside' ) ) );
			}
			if ( $crafti_columns > 1 ) {
				$crafti_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $crafti_columns ) . ' widget', $crafti_widgets_output );
			} else {
				$crafti_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $crafti_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'crafti_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $crafti_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $crafti_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'crafti_action_before_sidebar', 'header' );
				crafti_show_layout( $crafti_widgets_output );
				do_action( 'crafti_action_after_sidebar', 'header' );
				if ( $crafti_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $crafti_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'crafti_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
