<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

$crafti_template_args = get_query_var( 'crafti_template_args' );
$crafti_columns = 1;
if ( is_array( $crafti_template_args ) ) {
	$crafti_columns    = empty( $crafti_template_args['columns'] ) ? 1 : max( 1, $crafti_template_args['columns'] );
	$crafti_blog_style = array( $crafti_template_args['type'], $crafti_columns );
	if ( ! empty( $crafti_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $crafti_columns > 1 ) {
	    $crafti_columns_class = crafti_get_column_class( 1, $crafti_columns, ! empty( $crafti_template_args['columns_tablet']) ? $crafti_template_args['columns_tablet'] : '', ! empty($crafti_template_args['columns_mobile']) ? $crafti_template_args['columns_mobile'] : '' );
		?>
		<div class="<?php echo esc_attr( $crafti_columns_class ); ?>">
		<?php
	}
} else {
	$crafti_template_args = array();
}
$crafti_expanded    = ! crafti_sidebar_present() && crafti_get_theme_option( 'expand_content' ) == 'expand';
$crafti_post_format = get_post_format();
$crafti_post_format = empty( $crafti_post_format ) ? 'standard' : str_replace( 'post-format-', '', $crafti_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_excerpt post_format_' . esc_attr( $crafti_post_format ) );
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
								: array_map( 'trim', explode( ',', $crafti_template_args['meta_parts'] ) )
								)
							: crafti_array_get_keys_by_value( crafti_get_theme_option( 'meta_parts' ) );
	crafti_show_post_featured( apply_filters( 'crafti_filter_args_featured',
		array(
			'no_links'   => ! empty( $crafti_template_args['no_links'] ),
			'hover'      => $crafti_hover,
			'meta_parts' => $crafti_components,
			'thumb_size' => ! empty( $crafti_template_args['thumb_size'] )
							? $crafti_template_args['thumb_size']
							: crafti_get_thumb_size( strpos( crafti_get_theme_option( 'body_style' ), 'full' ) !== false
								? 'full'
								: ( $crafti_expanded 
									? 'huge' 
									: 'big' 
									)
								),
		),
		'content-excerpt',
		$crafti_template_args
	) );

	// Title and post meta
	$crafti_show_title = get_the_title() != '';
	$crafti_show_meta  = count( $crafti_components ) > 0 && ! in_array( $crafti_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $crafti_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if ( apply_filters( 'crafti_filter_show_blog_title', true, 'excerpt' ) ) {
				do_action( 'crafti_action_before_post_title' );
				if ( empty( $crafti_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'crafti_action_after_post_title' );
			}
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( apply_filters( 'crafti_filter_show_blog_excerpt', empty( $crafti_template_args['hide_excerpt'] ) && crafti_get_theme_option( 'excerpt_length' ) > 0, 'excerpt' ) ) {
		?>
		<div class="post_content entry-content">
			<?php

			// Post meta
			if ( apply_filters( 'crafti_filter_show_blog_meta', $crafti_show_meta, $crafti_components, 'excerpt' ) ) {
				if ( count( $crafti_components ) > 0 ) {
					do_action( 'crafti_action_before_post_meta' );
					crafti_show_post_meta(
						apply_filters(
							'crafti_filter_post_meta_args', array(
								'components' => join( ',', $crafti_components ),
								'seo'        => false,
								'echo'       => true,
							), 'excerpt', 1
						)
					);
					do_action( 'crafti_action_after_post_meta' );
				}
			}

			if ( crafti_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'crafti_action_before_full_post_content' );
					the_content( '' );
					do_action( 'crafti_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
				wp_link_pages(
					array(
						'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'crafti' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'crafti' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
			} else {
				// Post content area
				crafti_show_post_content( $crafti_template_args, '<div class="post_content_inner">', '</div>' );
			}

			// More button
			if ( apply_filters( 'crafti_filter_show_blog_readmore',  ! isset( $crafti_template_args['more_button'] ) || ! empty( $crafti_template_args['more_button'] ), 'excerpt' ) ) {
				if ( empty( $crafti_template_args['no_links'] ) ) {
					do_action( 'crafti_action_before_post_readmore' );
					if ( crafti_get_theme_option( 'blog_content' ) != 'fullpost' ) {
						crafti_show_post_more_link( $crafti_template_args, '<p>', '</p>' );
					} else {
						crafti_show_post_comments_link( $crafti_template_args, '<p>', '</p>' );
					}
					do_action( 'crafti_action_after_post_readmore' );
				}
			}

			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $crafti_template_args ) ) {
	if ( ! empty( $crafti_template_args['slider'] ) || $crafti_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
