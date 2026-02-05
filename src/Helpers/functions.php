<?php
/**
 * Global helper functions for TD Variable Fields
 *
 * @since 1.0.0
 */

use TD\VariableFields\Helpers\Variable;

if ( ! function_exists( 'td_var' ) ) {
	/**
	 * Get a TD Variable Field value.
	 *
	 * @param string $key     The variable key.
	 * @param mixed  $default Default value if not found.
	 * @return mixed
	 */
	function td_var( string $key, $default = null ) {
		return Variable::get( $key, $default );
	}
}
