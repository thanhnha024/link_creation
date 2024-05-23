<?php
/**
 * Class Advanced Popups Rules.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ADP
 * @subpackage ADP/includes
 */

/**
 * Class Rules
 */
class ADP_Popup_Rules {

	/**
	 * The instance.
	 *
	 * @var mixed $instance The instance.
	 */
	public static $instance;

	/**
	 * The rules.
	 *
	 * @var array $instance The rules.
	 */
	public $rules;

	/**
	 * The rule sort order.
	 *
	 * @var array $rule_sort_order The rule sort order.
	 */
	public $rule_sort_order = array();

	/**
	 * Init.
	 */
	public static function init() {
		self::instance();
	}

	/**
	 * Return instance.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get List rules
	 */
	public function get_list() {
		$list = array(
			'general'    => array(),
			'post_types' => array(),
			'taxonomies' => array(),
		);

		$types = array();

		// General.
		$list['general'] = array(
			'none'          => esc_html__( 'None', 'advanced-popups' ),
			'is_front_page' => esc_html__( 'Front Page', 'advanced-popups' ),
			'is_home'       => esc_html__( 'Home Page', 'advanced-popups' ),
			'is_archive'    => esc_html__( 'Archive Page', 'advanced-popups' ),
			'is_author'     => esc_html__( 'Author Page', 'advanced-popups' ),
			'is_search'     => esc_html__( 'Search Result Page', 'advanced-popups' ),
			'is_404'        => esc_html__( '404 Error Page', 'advanced-popups' ),
			'url'           => esc_html__( 'URL (slug)', 'advanced-popups' ),
		);

		// Post types.
		$post_types = get_post_types( array(
			'public' => true,
		), 'objects' );

		foreach ( $post_types as $name => $post_type ) {

			if ( 'adp-popup' === $name ) {
				continue;
			}

			if ( 'attachment' === $name ) {
				continue;
			}

			$types[] = $name;

			$list['post_types'][ 'post_type_' . $name ] = $post_type->labels->name;
		}

		// Taxonomies.
		foreach ( $types as $name ) {

			$post_type = get_post_type_object( $name );

			$taxonomies = get_object_taxonomies( $name, 'object' );

			foreach ( $taxonomies as $tax_name => $taxonomy ) {
				$list['taxonomies'][ 'taxonomy_' . $tax_name ] = esc_html__( 'Taxonomy of ', 'advanced-popups' ) . $post_type->labels->name . ': ' . $taxonomy->labels->name;
			}
		}

		return $list;
	}

	/**
	 * Get type from rule value
	 *
	 * @param string $val The value.
	 */
	public function get_type( $val ) {

		if ( 'url' === $val ) {
			return 'url';
		}

		if ( 0 === strpos( $val, 'is_' ) ) {
			return 'is';
		}

		if ( 0 === strpos( $val, 'post_type' ) ) {
			return 'post';
		}

		if ( 0 === strpos( $val, 'taxonomy' ) ) {
			return 'taxonomy';
		}
	}

	/**
	 * Get object from rule value
	 *
	 * @param string $val The value.
	 */
	public function get_object( $val ) {

		$val = str_replace( array( 'post_type', 'taxonomy' ), '', $val );

		return ltrim( $val, '_' );
	}

	/**
	 * Checking all the rules on the front end
	 *
	 * @param string $rules The rules.
	 */
	public function is_check( $rules ) {
		if ( ! $rules ) {
			return true;
		}

		$check = array();

		foreach ( $rules as $i => $row ) {

			$check[ $i ] = true;

			foreach ( $row as $t => $tools ) {
				if ( ! isset( $tools['rule'] ) ) {
					continue;
				}

				if ( 'none' === $tools['rule'] ) {
					continue;
				}

				$rule = $tools['rule'];

				// Get type from rule value.
				$type_rule = self::instance()->get_type( $rule );

				// Get type object from rule value.
				$type_object = self::instance()->get_object( $rule );

				$check_or = true;

				// Check rules.
				switch ( $type_rule ) {
					case 'url':
						if ( isset( $tools['url'] ) && $tools['url'] ) {

							$object_uri  = ltrim( rtrim( $tools['url'], '/' ), '/' );
							$current_uri = ltrim( rtrim( $_SERVER['REQUEST_URI'], '/' ), '/' );

							if ( $object_uri !== $current_uri ) {
								$check_or = false;
							}
						}
						break;
					case 'is':
						if ( ! function_exists( $rule ) || ! call_user_func( $rule ) ) {
							$check_or = false;
						}
						break;
					case 'post':
						if ( ! is_singular( $type_object ) ) {
							$check_or = false;
						}

						$post_id = get_the_ID();

						if ( isset( $tools['object'] ) && is_array( $tools['object'] ) ) {
							foreach ( $tools['object'] as $object ) {
								$check_or = (int) $post_id === (int) $object ? true : false;
								if ( $check_or ) {
									break;
								}
							}
						}
						break;
					case 'taxonomy':
						if ( ! is_singular() ) {
							$check_or = false;
						}

						$term_ids = (array) wp_get_post_terms( get_queried_object_id(), $type_object, array( 'fields' => 'ids' ) );

						if ( isset( $tools['object'] ) && is_array( $tools['object'] ) ) {
							foreach ( $tools['object'] as $object ) {

								$check_or = in_array( (int) $object, $term_ids, true) ? true : false;

								if ( $check_or ) {
									break;
								}
							}
						}
						break;
				}

				$check[ $i ] = $check_or;

				if ( $check_or ) {
					break;
				}
			}
		}

		$is_check = true;

		foreach ( $check as $item ) {
			if ( ! $item ) {
				$is_check = false;
				break;
			}
		}

		return $is_check;
	}
}
