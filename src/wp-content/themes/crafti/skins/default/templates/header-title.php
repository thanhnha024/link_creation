<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package CRAFTI
 * @since CRAFTI 1.0
 */

// Page (category, tag, archive, author) title

if ( crafti_need_page_title() ) {
	crafti_sc_layouts_showed( 'title', true );
	crafti_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								crafti_show_post_meta(
									apply_filters(
										'crafti_filter_post_meta_args', array(
											'components' => join( ',', crafti_array_get_keys_by_value( crafti_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', crafti_array_get_keys_by_value( crafti_get_theme_option( 'counters' ) ) ),
											'seo'        => crafti_is_on( crafti_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$crafti_blog_title           = crafti_get_blog_title();
							$crafti_blog_title_text      = '';
							$crafti_blog_title_class     = '';
							$crafti_blog_title_link      = '';
							$crafti_blog_title_link_text = '';
							if ( is_array( $crafti_blog_title ) ) {
								$crafti_blog_title_text      = $crafti_blog_title['text'];
								$crafti_blog_title_class     = ! empty( $crafti_blog_title['class'] ) ? ' ' . $crafti_blog_title['class'] : '';
								$crafti_blog_title_link      = ! empty( $crafti_blog_title['link'] ) ? $crafti_blog_title['link'] : '';
								$crafti_blog_title_link_text = ! empty( $crafti_blog_title['link_text'] ) ? $crafti_blog_title['link_text'] : '';
							} else {
								$crafti_blog_title_text = $crafti_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $crafti_blog_title_class ); ?>">
								<?php
								$crafti_top_icon = crafti_get_term_image_small();
								if ( ! empty( $crafti_top_icon ) ) {
									$crafti_attr = crafti_getimagesize( $crafti_top_icon );
									?>
									<img src="<?php echo esc_url( $crafti_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'crafti' ); ?>"
										<?php
										if ( ! empty( $crafti_attr[3] ) ) {
											crafti_show_layout( $crafti_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $crafti_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $crafti_blog_title_link ) && ! empty( $crafti_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $crafti_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $crafti_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'crafti_action_breadcrumbs' );
						$crafti_breadcrumbs = ob_get_contents();
						ob_end_clean();
						crafti_show_layout( $crafti_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
