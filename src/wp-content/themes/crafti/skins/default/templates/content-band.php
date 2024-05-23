<?php

/**
 * 'Band' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package CRAFTI
 * @since CRAFTI 1.71.0
 */

$crafti_template_args = get_query_var('crafti_template_args');
if (!is_array($crafti_template_args)) {
	$crafti_template_args = array(
		'type'    => 'band',
		'columns' => 1
	);
}

$crafti_columns       = 1;

$crafti_expanded      = !crafti_sidebar_present() && crafti_get_theme_option('expand_content') == 'expand';

$crafti_post_format   = get_post_format();
$crafti_post_format   = empty($crafti_post_format) ? 'standard' : str_replace('post-format-', '', $crafti_post_format);

if (is_array($crafti_template_args)) {
	$crafti_columns    = empty($crafti_template_args['columns']) ? 1 : max(1, $crafti_template_args['columns']);
	$crafti_blog_style = array($crafti_template_args['type'], $crafti_columns);
	if (!empty($crafti_template_args['slider'])) {
?><div class="slider-slide swiper-slide">
		<?php
	} elseif ($crafti_columns > 1) {
		$crafti_columns_class = crafti_get_column_class(1, $crafti_columns, !empty($crafti_template_args['columns_tablet']) ? $crafti_template_args['columns_tablet'] : '', !empty($crafti_template_args['columns_mobile']) ? $crafti_template_args['columns_mobile'] : '');
		?><div class="<?php echo esc_attr($crafti_columns_class); ?>"><?php
																																}
																															}
																																	?>
		<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>" <?php
																																						post_class('post_item post_item_container post_layout_band post_format_' . esc_attr($crafti_post_format));
																																						crafti_add_blog_animation($crafti_template_args);
																																						?>>
			<?php

			// Sticky label
			if (is_sticky() && !is_paged()) {
			?>
				<span class="post_label label_sticky"></span>
			<?php
			}

			// Featured image
			$crafti_hover      = !empty($crafti_template_args['hover']) && !crafti_is_inherit($crafti_template_args['hover'])
				? $crafti_template_args['hover']
				: crafti_get_theme_option('image_hover');
			$crafti_components = !empty($crafti_template_args['meta_parts'])
				? (is_array($crafti_template_args['meta_parts'])
					? $crafti_template_args['meta_parts']
					: array_map('trim', explode(',', $crafti_template_args['meta_parts']))
				)
				: crafti_array_get_keys_by_value(crafti_get_theme_option('meta_parts'));
			crafti_show_post_featured(apply_filters(
				'crafti_filter_args_featured',
				array(
					'no_links'   => !empty($crafti_template_args['no_links']),
					'hover'      => $crafti_hover,
					'meta_parts' => $crafti_components,
					'thumb_bg'   => true,
					'thumb_ratio'   => '1:1',
					'thumb_size' => !empty($crafti_template_args['thumb_size'])
						? $crafti_template_args['thumb_size']
						: crafti_get_thumb_size(
							in_array($crafti_post_format, array('gallery', 'audio', 'video'))
								? (strpos(crafti_get_theme_option('body_style'), 'full') !== false
									? 'full'
									: ($crafti_expanded
										? 'big'
										: 'medium-square'
									)
								)
								: 'masonry-big'
						)
				),
				'content-band',
				$crafti_template_args
			));

			?><div class="post_content_wrap"><?php

																				// Title and post meta
																				$crafti_show_title = get_the_title() != '';
																				$crafti_show_meta  = count($crafti_components) > 0 && !in_array($crafti_hover, array('border', 'pull', 'slide', 'fade', 'info'));
																				if ($crafti_show_title) {
																				?>
					<div class="post_header entry-header">
						<?php
																					// Categories
																					if (apply_filters('crafti_filter_show_blog_categories', $crafti_show_meta && in_array('categories', $crafti_components), array('categories'), 'band')) {
																						do_action('crafti_action_before_post_category');
						?>
							<div class="post_category">
								<?php
																						crafti_show_post_meta(
																							apply_filters(
																								'crafti_filter_post_meta_args',
																								array(
																									'components' => 'categories',
																									'seo'        => false,
																									'echo'       => true,
																									'cat_sep'    => false,
																								),
																								'hover_' . $crafti_hover,
																								1
																							)
																						);
								?>
							</div>
						<?php
																						$crafti_components = crafti_array_delete_by_value($crafti_components, 'categories');
																						do_action('crafti_action_after_post_category');
																					}
																					// Post title
																					if (apply_filters('crafti_filter_show_blog_title', true, 'band')) {
																						do_action('crafti_action_before_post_title');
																						if (empty($crafti_template_args['no_links'])) {
																							the_title(sprintf('<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h4>');
																						} else {
																							the_title('<h4 class="post_title entry-title">', '</h4>');
																						}
																						do_action('crafti_action_after_post_title');
																					}
						?>
					</div><!-- .post_header -->
				<?php
																				}

																				// Post content
																				if (!isset($crafti_template_args['excerpt_length']) && !in_array($crafti_post_format, array('gallery', 'audio', 'video'))) {
																					$crafti_template_args['excerpt_length'] = 13;
																				}
																				if (apply_filters('crafti_filter_show_blog_excerpt', empty($crafti_template_args['hide_excerpt']) && crafti_get_theme_option('excerpt_length') > 0, 'band')) {
				?>
					<div class="post_content entry-content">
						<?php
																					// Post content area
																					crafti_show_post_content($crafti_template_args, '<div class="post_content_inner">', '</div>');
						?>
					</div><!-- .entry-content -->
				<?php
																				}
																				// Post meta
																				if (apply_filters('crafti_filter_show_blog_meta', $crafti_show_meta, $crafti_components, 'band')) {
																					if (count($crafti_components) > 0) {
																						do_action('crafti_action_before_post_meta');
																						crafti_show_post_meta(
																							apply_filters(
																								'crafti_filter_post_meta_args',
																								array(
																									'components' => join(',', $crafti_components),
																									'seo'        => false,
																									'echo'       => true,
																								),
																								'band',
																								1
																							)
																						);
																						do_action('crafti_action_after_post_meta');
																					}
																				}
																				// More button
																				if (apply_filters('crafti_filter_show_blog_readmore', !$crafti_show_title || !empty($crafti_template_args['more_button']), 'band')) {
																					if (empty($crafti_template_args['no_links'])) {
																						do_action('crafti_action_before_post_readmore');
																						crafti_show_post_more_link($crafti_template_args, '<div class="more-wrap">', '</div>');
																						do_action('crafti_action_after_post_readmore');
																					}
																				}
				?>
			</div>
		</article>
		<?php

		if (is_array($crafti_template_args)) {
			if (!empty($crafti_template_args['slider']) || $crafti_columns > 1) {
		?>
			</div>
	<?php
			}
		}
