<?php
/**
 * The Portfolio template to display the content
 *
 * Used for index/archive/search.
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

$crafti_template_args = get_query_var( 'crafti_template_args' );
if ( is_array( $crafti_template_args ) ) {
	$crafti_columns    = empty( $crafti_template_args['columns'] ) ? 2 : max( 1, $crafti_template_args['columns'] );
	$crafti_blog_style = array( $crafti_template_args['type'], $crafti_columns );
    $crafti_columns_class = crafti_get_column_class( 1, $crafti_columns, ! empty( $crafti_template_args['columns_tablet']) ? $crafti_template_args['columns_tablet'] : '', ! empty($crafti_template_args['columns_mobile']) ? $crafti_template_args['columns_mobile'] : '' );
} else {
	$crafti_template_args = array();
	$crafti_blog_style = explode( '_', crafti_get_theme_option( 'blog_style' ) );
	$crafti_columns    = empty( $crafti_blog_style[1] ) ? 2 : max( 1, $crafti_blog_style[1] );
    $crafti_columns_class = crafti_get_column_class( 1, $crafti_columns );
}

$crafti_post_format = get_post_format();
$crafti_post_format = empty( $crafti_post_format ) ? 'standard' : str_replace( 'post-format-', '', $crafti_post_format );

?><div class="
<?php
if ( ! empty( $crafti_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( crafti_is_blog_style_use_masonry( $crafti_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $crafti_columns ) : esc_attr( $crafti_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $crafti_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $crafti_columns )
		. ( 'portfolio' != $crafti_blog_style[0] ? ' ' . esc_attr( $crafti_blog_style[0] )  . '_' . esc_attr( $crafti_columns ) : '' )
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

	$crafti_hover   = ! empty( $crafti_template_args['hover'] ) && ! crafti_is_inherit( $crafti_template_args['hover'] )
								? $crafti_template_args['hover']
								: crafti_get_theme_option( 'image_hover' );

	if ( 'dots' == $crafti_hover ) {
		$crafti_post_link = empty( $crafti_template_args['no_links'] )
								? ( ! empty( $crafti_template_args['link'] )
									? $crafti_template_args['link']
									: get_permalink()
									)
								: '';
		$crafti_target    = ! empty( $crafti_post_link ) && false === strpos( $crafti_post_link, home_url() )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$crafti_components = ! empty( $crafti_template_args['meta_parts'] )
							? ( is_array( $crafti_template_args['meta_parts'] )
								? $crafti_template_args['meta_parts']
								: explode( ',', $crafti_template_args['meta_parts'] )
								)
							: crafti_array_get_keys_by_value( crafti_get_theme_option( 'meta_parts' ) );

	// Featured image
	crafti_show_post_featured( apply_filters( 'crafti_filter_args_featured',
        array(
			'hover'         => $crafti_hover,
			'no_links'      => ! empty( $crafti_template_args['no_links'] ),
			'thumb_size'    => ! empty( $crafti_template_args['thumb_size'] )
								? $crafti_template_args['thumb_size']
								: crafti_get_thumb_size(
									crafti_is_blog_style_use_masonry( $crafti_blog_style[0] )
										? (	strpos( crafti_get_theme_option( 'body_style' ), 'full' ) !== false || $crafti_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( crafti_get_theme_option( 'body_style' ), 'full' ) !== false || $crafti_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => crafti_is_blog_style_use_masonry( $crafti_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $crafti_components,
			'class'         => 'dots' == $crafti_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $crafti_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $crafti_post_link )
												? '<a href="' . esc_url( $crafti_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $crafti_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $crafti_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $crafti_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!