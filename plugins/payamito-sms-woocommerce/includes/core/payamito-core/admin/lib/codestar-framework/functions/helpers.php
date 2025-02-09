<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Array search key & value
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! function_exists( 'kianfr_array_search' ) ) {
	function kianfr_array_search( $array, $key, $value )
	{
		$results = [];

		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
				$results[] = $array;
			}

			foreach ( $array as $sub_array ) {
				$results = array_merge( $results, kianfr_array_search( $sub_array, $key, $value ) );
			}
		}

		return $results;
	}
}

/**
 * Between Microtime
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! function_exists( 'kianfr_timeout' ) ) {
	function kianfr_timeout( $timenow, $starttime, $timeout = 30 )
	{
		return ( ( $timenow - $starttime ) < $timeout ) ? true : false;
	}
}

/**
 * Check for wp editor api
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! function_exists( 'kianfr_wp_editor_api' ) ) {
	function kianfr_wp_editor_api()
	{
		global $wp_version;

		return version_compare( $wp_version, '4.8', '>=' );
	}
}
