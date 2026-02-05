<?php
/**
 * Variable helper class
 *
 * @since   1.0.0
 * @package td_variable_fields
 */

namespace TD\VariableFields\Helpers;

/**
 * Class Variable
 */
class Variable {

	/**
	 * Get variable value by key.
	 *
	 * @param string $key     Variable key.
	 * @param mixed  $default Default value if key not found.
	 * @return mixed
	 */
	public static function get( string $key, $default = null ) {
		$all = get_option( 'td_variable_fields', [] );

		if ( ! is_array( $all ) || ! isset( $all[ $key ] ) ) {
			return $default;
		}

		return isset( $all[ $key ]['value'] ) ? $all[ $key ]['value'] : $default;
	}
}
