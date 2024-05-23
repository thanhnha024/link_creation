<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

$crafti_columns     = max( 1, min( 3, count( get_option( 'sticky_posts' ) ) ) );
$crafti_post_format = get_post_format();
$crafti_post_format = empty( $crafti_post_format ) ? 'standard' : str_replace( 'post-format-', '', $crafti_post_format );

?><div class="column-1_<?php echo esc_attr( $crafti_columns ); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class( 'post_item post_layout_sticky post_format_' . esc_attr( $crafti_post_format ) );
	crafti_add_blog_animation( $crafti_template_args );
	?>
>

	<?php
	if ( is_sticky() && is_home() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	crafti_show_post_featured(
		array(
			'thumb_size' => crafti_get_thumb_size( 1 == $crafti_columns ? 'big' : ( 2 == $crafti_columns ? 'med' : 'avatar' ) ),
		)
	);

	if ( ! in_array( $crafti_post_format, array( 'link', 'aside', 'status', 'quote' ) ) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h5 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			crafti_show_post_meta( apply_filters( 'crafti_filter_post_meta_args', array(), 'sticky', $crafti_columns ) );
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div><?php

// div.column-1_X is a inline-block and new lines and spaces after it are forbidden
