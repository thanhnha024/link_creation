<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

$crafti_link        = get_permalink();
$crafti_post_format = get_post_format();
$crafti_post_format = empty( $crafti_post_format ) ? 'standard' : str_replace( 'post-format-', '', $crafti_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $crafti_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	crafti_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'crafti_filter_related_thumb_size', crafti_get_thumb_size( (int) crafti_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'big' ) ),
			'post_info'     => '<div class="post_header entry-header">'
									. '<div class="post_categories">' . wp_kses( crafti_get_post_categories( '' ), 'crafti_kses_content' ) . '</div>'
									. '<h6 class="post_title entry-title"><a href="' . esc_url( $crafti_link ) . '">'
										. wp_kses_data( '' == get_the_title() ? esc_html__( '- No title -', 'crafti' ) : get_the_title() )
									. '</a></h6>'
									. ( in_array( get_post_type(), array( 'post', 'attachment' ) )
											? '<div class="post_meta"><a href="' . esc_url( $crafti_link ) . '" class="post_meta_item post_date">' . wp_kses_data( crafti_get_date() ) . '</a></div>'
											: '' )
								. '</div>',
		)
	);
	?>
</div>
