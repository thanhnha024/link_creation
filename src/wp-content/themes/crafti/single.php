<?php
/**
 * The template to display single post
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

// Full post loading
$full_post_loading          = crafti_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = crafti_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = crafti_get_theme_option( 'posts_navigation_scroll_which_block' );

// Position of the related posts
$crafti_related_position   = crafti_get_theme_option( 'related_position' );

// Type of the prev/next post navigation
$crafti_posts_navigation   = crafti_get_theme_option( 'posts_navigation' );
$crafti_prev_post          = false;
$crafti_prev_post_same_cat = crafti_get_theme_option( 'posts_navigation_scroll_same_cat' );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( crafti_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	crafti_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'crafti_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $crafti_posts_navigation ) {
		$crafti_prev_post = get_previous_post( $crafti_prev_post_same_cat );  // Get post from same category
		if ( ! $crafti_prev_post && $crafti_prev_post_same_cat ) {
			$crafti_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $crafti_prev_post ) {
			$crafti_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $crafti_prev_post ) ) {
		crafti_sc_layouts_showed( 'featured', false );
		crafti_sc_layouts_showed( 'title', false );
		crafti_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $crafti_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/content', 'single-' . crafti_get_theme_option( 'single_style' ) ), 'single-' . crafti_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $crafti_related_position, 'inside' ) === 0 ) {
		$crafti_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'crafti_action_related_posts' );
		$crafti_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $crafti_related_content ) ) {
			$crafti_related_position_inside = max( 0, min( 9, crafti_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $crafti_related_position_inside ) {
				$crafti_related_position_inside = mt_rand( 1, 9 );
			}

			$crafti_p_number         = 0;
			$crafti_related_inserted = false;
			$crafti_in_block         = false;
			$crafti_content_start    = strpos( $crafti_content, '<div class="post_content' );
			$crafti_content_end      = strrpos( $crafti_content, '</div>' );

			for ( $i = max( 0, $crafti_content_start ); $i < min( strlen( $crafti_content ) - 3, $crafti_content_end ); $i++ ) {
				if ( $crafti_content[ $i ] != '<' ) {
					continue;
				}
				if ( $crafti_in_block ) {
					if ( strtolower( substr( $crafti_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$crafti_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $crafti_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $crafti_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$crafti_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $crafti_content[ $i + 1 ] && in_array( $crafti_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$crafti_p_number++;
					if ( $crafti_related_position_inside == $crafti_p_number ) {
						$crafti_related_inserted = true;
						$crafti_content = ( $i > 0 ? substr( $crafti_content, 0, $i ) : '' )
											. $crafti_related_content
											. substr( $crafti_content, $i );
					}
				}
			}
			if ( ! $crafti_related_inserted ) {
				if ( $crafti_content_end > 0 ) {
					$crafti_content = substr( $crafti_content, 0, $crafti_content_end ) . $crafti_related_content . substr( $crafti_content, $crafti_content_end );
				} else {
					$crafti_content .= $crafti_related_content;
				}
			}
		}

		crafti_show_layout( $crafti_content );
	}

	// Comments
	do_action( 'crafti_action_before_comments' );
	comments_template();
	do_action( 'crafti_action_after_comments' );

	// Related posts
	if ( 'below_content' == $crafti_related_position
		&& ( 'scroll' != $crafti_posts_navigation || crafti_get_theme_option( 'posts_navigation_scroll_hide_related' ) == 0 )
		&& ( ! $full_post_loading || crafti_get_theme_option( 'open_full_post_hide_related' ) == 0 )
	) {
		do_action( 'crafti_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $crafti_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $crafti_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $crafti_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $crafti_prev_post ) ); ?>"
			<?php do_action( 'crafti_action_nav_links_single_scroll_data', $crafti_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
