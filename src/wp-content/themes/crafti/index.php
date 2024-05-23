<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

$crafti_template = apply_filters( 'crafti_filter_get_template_part', crafti_blog_archive_get_template() );

if ( ! empty( $crafti_template ) && 'index' != $crafti_template ) {

	get_template_part( $crafti_template );

} else {

	crafti_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$crafti_stickies   = is_home()
								|| ( in_array( crafti_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) crafti_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$crafti_post_type  = crafti_get_theme_option( 'post_type' );
		$crafti_args       = array(
								'blog_style'     => crafti_get_theme_option( 'blog_style' ),
								'post_type'      => $crafti_post_type,
								'taxonomy'       => crafti_get_post_type_taxonomy( $crafti_post_type ),
								'parent_cat'     => crafti_get_theme_option( 'parent_cat' ),
								'posts_per_page' => crafti_get_theme_option( 'posts_per_page' ),
								'sticky'         => crafti_get_theme_option( 'sticky_style' ) == 'columns'
															&& is_array( $crafti_stickies )
															&& count( $crafti_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		crafti_blog_archive_start();

		do_action( 'crafti_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'crafti_action_before_page_author' );
			get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'crafti_action_after_page_author' );
		}

		if ( crafti_get_theme_option( 'show_filters' ) ) {
			do_action( 'crafti_action_before_page_filters' );
			crafti_show_filters( $crafti_args );
			do_action( 'crafti_action_after_page_filters' );
		} else {
			do_action( 'crafti_action_before_page_posts' );
			crafti_show_posts( array_merge( $crafti_args, array( 'cat' => $crafti_args['parent_cat'] ) ) );
			do_action( 'crafti_action_after_page_posts' );
		}

		do_action( 'crafti_action_blog_archive_end' );

		crafti_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'crafti_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
