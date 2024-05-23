<?php
/**
 * The Classic template to display the content
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
$crafti_expanded   = ! crafti_sidebar_present() && crafti_get_theme_option( 'expand_content' ) == 'expand';

$crafti_post_format = get_post_format();
$crafti_post_format = empty( $crafti_post_format ) ? 'standard' : str_replace( 'post-format-', '', $crafti_post_format );

?><div class="<?php
	if ( ! empty( $crafti_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( crafti_is_blog_style_use_masonry( $crafti_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $crafti_columns ) : esc_attr( $crafti_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $crafti_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $crafti_columns )
				. ' post_layout_' . esc_attr( $crafti_blog_style[0] )
				. ' post_layout_' . esc_attr( $crafti_blog_style[0] ) . '_' . esc_attr( $crafti_columns )
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

	// Featured image
	$crafti_hover      = ! empty( $crafti_template_args['hover'] ) && ! crafti_is_inherit( $crafti_template_args['hover'] )
							? $crafti_template_args['hover']
							: crafti_get_theme_option( 'image_hover' );

	$crafti_components = ! empty( $crafti_template_args['meta_parts'] )
							? ( is_array( $crafti_template_args['meta_parts'] )
								? $crafti_template_args['meta_parts']
								: explode( ',', $crafti_template_args['meta_parts'] )
								)
							: crafti_array_get_keys_by_value( crafti_get_theme_option( 'meta_parts' ) );

	crafti_show_post_featured( apply_filters( 'crafti_filter_args_featured',
		array(
			'thumb_size' => ! empty( $crafti_template_args['thumb_size'] )
				? $crafti_template_args['thumb_size']
				: crafti_get_thumb_size(
				'classic' == $crafti_blog_style[0]
						? ( strpos( crafti_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $crafti_columns > 2 ? 'big' : 'huge' )
								: ( $crafti_columns > 2
									? ( $crafti_expanded ? 'square' : 'square' )
									: ($crafti_columns > 1 ? 'square' : ( $crafti_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( crafti_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $crafti_columns > 2 ? 'masonry-big' : 'full' )
								: ($crafti_columns === 1 ? ( $crafti_expanded ? 'huge' : 'big' ) : ( $crafti_columns <= 2 && $crafti_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $crafti_hover,
			'meta_parts' => $crafti_components,
			'no_links'   => ! empty( $crafti_template_args['no_links'] ),
        ),
        'content-classic',
        $crafti_template_args
    ) );

	// Title and post meta
	$crafti_show_title = get_the_title() != '';
	$crafti_show_meta  = count( $crafti_components ) > 0 && ! in_array( $crafti_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $crafti_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'crafti_filter_show_blog_meta', $crafti_show_meta, $crafti_components, 'classic' ) ) {
				if ( count( $crafti_components ) > 0 ) {
					do_action( 'crafti_action_before_post_meta' );
					crafti_show_post_meta(
						apply_filters(
							'crafti_filter_post_meta_args', array(
							'components' => join( ',', $crafti_components ),
							'seo'        => false,
							'echo'       => true,
						), $crafti_blog_style[0], $crafti_columns
						)
					);
					do_action( 'crafti_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'crafti_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'crafti_action_before_post_title' );
				if ( empty( $crafti_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'crafti_action_after_post_title' );
			}

			if( !in_array( $crafti_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'crafti_filter_show_blog_readmore', ! $crafti_show_title || ! empty( $crafti_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $crafti_template_args['no_links'] ) ) {
						do_action( 'crafti_action_before_post_readmore' );
						crafti_show_post_more_link( $crafti_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'crafti_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $crafti_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('crafti_filter_show_blog_excerpt', empty($crafti_template_args['hide_excerpt']) && crafti_get_theme_option('excerpt_length') > 0, 'classic')) {
			crafti_show_post_content($crafti_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $crafti_template_args['more_button'] )) {
			if ( empty( $crafti_template_args['no_links'] ) ) {
				do_action( 'crafti_action_before_post_readmore' );
				crafti_show_post_more_link( $crafti_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'crafti_action_after_post_readmore' );
			}
		}
		$crafti_content = ob_get_contents();
		ob_end_clean();
		crafti_show_layout($crafti_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
