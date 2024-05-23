<?php
/**
 * The template to display the attachment
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */


get_header();

while ( have_posts() ) {
	the_post();

	// Display post's content
	get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/content', 'single-' . crafti_get_theme_option( 'single_style' ) ), 'single-' . crafti_get_theme_option( 'single_style' ) );

	// Parent post navigation.
	$crafti_posts_navigation = crafti_get_theme_option( 'posts_navigation' );
	if ( 'links' == $crafti_posts_navigation ) {
		?>
		<div class="nav-links-single<?php
			if ( ! crafti_is_off( crafti_get_theme_option( 'posts_navigation_fixed' ) ) ) {
				echo ' nav-links-fixed fixed';
			}
		?>">
			<?php
			the_post_navigation( apply_filters( 'crafti_filter_post_navigation_args', array(
					'prev_text' => '<span class="nav-arrow"></span>'
						. '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Published in', 'crafti' ) . '</span> '
						. '<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'crafti' ) . '</span> '
						. '<h5 class="post-title">%title</h5>'
						. '<span class="post_date">%date</span>',
			), 'image' ) );
			?>
		</div>
		<?php
	}

	// Comments
	do_action( 'crafti_action_before_comments' );
	comments_template();
	do_action( 'crafti_action_after_comments' );
}

get_footer();
