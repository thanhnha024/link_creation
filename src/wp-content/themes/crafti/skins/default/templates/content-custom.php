<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package CRAFTI
 * @since CRAFTI 1.0.50
 */

$crafti_template_args = get_query_var( 'crafti_template_args' );
if ( is_array( $crafti_template_args ) ) {
	$crafti_columns    = empty( $crafti_template_args['columns'] ) ? 2 : max( 1, $crafti_template_args['columns'] );
	$crafti_blog_style = array( $crafti_template_args['type'], $crafti_columns );
} else {
	$crafti_template_args = array();
	$crafti_blog_style = explode( '_', crafti_get_theme_option( 'blog_style' ) );
	$crafti_columns    = empty( $crafti_blog_style[1] ) ? 2 : max( 1, $crafti_blog_style[1] );
}
$crafti_blog_id       = crafti_get_custom_blog_id( join( '_', $crafti_blog_style ) );
$crafti_blog_style[0] = str_replace( 'blog-custom-', '', $crafti_blog_style[0] );
$crafti_expanded      = ! crafti_sidebar_present() && crafti_get_theme_option( 'expand_content' ) == 'expand';
$crafti_components    = ! empty( $crafti_template_args['meta_parts'] )
							? ( is_array( $crafti_template_args['meta_parts'] )
								? join( ',', $crafti_template_args['meta_parts'] )
								: $crafti_template_args['meta_parts']
								)
							: crafti_array_get_keys_by_value( crafti_get_theme_option( 'meta_parts' ) );
$crafti_post_format   = get_post_format();
$crafti_post_format   = empty( $crafti_post_format ) ? 'standard' : str_replace( 'post-format-', '', $crafti_post_format );

$crafti_blog_meta     = crafti_get_custom_layout_meta( $crafti_blog_id );
$crafti_custom_style  = ! empty( $crafti_blog_meta['scripts_required'] ) ? $crafti_blog_meta['scripts_required'] : 'none';

if ( ! empty( $crafti_template_args['slider'] ) || $crafti_columns > 1 || ! crafti_is_off( $crafti_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $crafti_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( crafti_is_off( $crafti_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $crafti_custom_style ) ) . "-1_{$crafti_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $crafti_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $crafti_columns )
					. ' post_layout_' . esc_attr( $crafti_blog_style[0] )
					. ' post_layout_' . esc_attr( $crafti_blog_style[0] ) . '_' . esc_attr( $crafti_columns )
					. ( ! crafti_is_off( $crafti_custom_style )
						? ' post_layout_' . esc_attr( $crafti_custom_style )
							. ' post_layout_' . esc_attr( $crafti_custom_style ) . '_' . esc_attr( $crafti_columns )
						: ''
						)
		);
	crafti_add_blog_animation( $crafti_template_args );
	?>
>
	<?php
	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}
	// Custom layout
	do_action( 'crafti_action_show_layout', $crafti_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $crafti_template_args['slider'] ) || $crafti_columns > 1 || ! crafti_is_off( $crafti_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
