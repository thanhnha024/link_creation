<?php
/**
 * WordPress utilities
 *
 * @package ThemeREX Updater
 * @since v2.1.2
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );



if ( ! function_exists( 'trx_updater_remove_filter' ) ) {
	/**
	 * Remove filter from the specified hook by method name and return old settings
	 *
	 * @param string $filter_name		Filter name
	 * @param string $callback_name		Callback name
	 * @param string $class_name		Class name
	 * 
	 * @return array					Old (removed) settings
	 */
	function trx_updater_remove_filter( $filter_name, $callback_name, $class_name = '' ) {
		global $wp_filter;
		$rez = false;
		if ( ! empty( $wp_filter[ $filter_name ] ) && ( is_array( $wp_filter[ $filter_name ] ) || is_object( $wp_filter[ $filter_name ] ) ) ) {
			foreach ( $wp_filter[ $filter_name ] as $p => $cb ) {
				foreach ( $cb as $k => $v ) {
					if ( strpos( $k, $callback_name ) !== false
						&& ( empty( $class_name )
							|| ! is_array( $v['function'] )
							|| ! is_object( $v['function'][0] )
							// This way needs for the full class name (with namespace)
							|| get_class( $v['function'][0] ) == $class_name
							// This way compare a class name with a last portion of the full class name
							//|| substr( get_class( $v['function'][0] ), strlen( $class_name ) ) == $class_name
							)
					) {
						$rez = array(
							'filter'   => $filter_name,
							'key'      => $k,
							'callback' => $v,
							'priority' => $p
						);
						remove_filter( $filter_name, $v['function'], $p );
					}
				}
			}
		}
		return $rez;
	}
}

if ( ! function_exists( 'trx_updater_remove_action' ) ) {
	/**
	 * Remove action from the specified hook by method name and return old settings
	 *
	 * @param string $filter_name		Filter name
	 * @param string $callback_name		Callback name
	 * @param string $class_name		Class name
	 * 
	 * @return array					Old (removed) settings
	 */
	function trx_updater_remove_action( $filter_name, $callback_name, $class_name = '' ) {
		return trx_updater_remove_filter( $filter_name, $callback_name, $class_name );
	}
}

if ( ! function_exists( 'trx_updater_restore_filter' ) ) {
	/**
	 * Restore filter to the specified hook by old settings returned by trx_updater_remove_filter
	 *
	 * @param array $filter		Old (removed) settings of the filter to restore
	 */
	function trx_updater_restore_filter( $filter ) {
		global $wp_filter;
		if ( ! empty( $filter['filter'] ) ) {
			$filter_name     = $filter['filter'];
			$filter_key      = $filter['key'];
			$filter_callback = $filter['callback'];
			$filter_priority = $filter['priority'];
			if ( ! isset( $wp_filter[ $filter_name ][ $filter_priority ][ $filter_key ] ) ) {
				add_filter( $filter_name, $filter_callback['function'], $filter_priority, $filter_callback['accepted_args'] );
			}
		}
	}
}

if ( ! function_exists( 'trx_updater_restore_action' ) ) {
	/**
	 * Restore action to the specified hook by old settings returned by trx_updater_remove_action
	 *
	 * @param array $filter		Old (removed) settings of the action to restore
	 */
	function trx_updater_restore_action( $filter ) {
		return trx_updater_restore_filter( $filter );
	}
}
