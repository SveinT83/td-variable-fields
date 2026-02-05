<?php
/**
 * td_variable_fields frontend part
 *
 * @since   {VERSION}
 * @link    {URL}
 * @license GPLv2 or later
 * @package td_variable_fields
 * @author  {AUTHOR}
 */

namespace TD\VariableFields\Front;

use TD\VariableFields\Plugin;

/**
 * Class Front
 *
 * @since   {VERSION}
 *
 * @package td_variable_fields\Front
 */
class Front {

	/**
	 * Init hooks
	 *
	 * @since {VERSION}
	 */
	public function hooks(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        add_shortcode( 'td_var', [ $this, 'shortcode_td_var' ] );
	}

    /**
     * Shortcode: [td_var key="nextcloud_price" default=""]
     *
     * @param array|string $atts Shortcode attributes.
     * @return string
     */
    public function shortcode_td_var( $atts ): string {
        $atts = shortcode_atts(
            [
                'key'     => '',
                'default' => '',
            ],
            is_array( $atts ) ? $atts : [],
            'td_var'
        );

        $key = sanitize_key( (string) $atts['key'] );
        if ( '' === $key ) {
            return '';
        }

        $value = function_exists( 'td_var' ) ? td_var( $key, $atts['default'] ) : $atts['default'];

        if ( is_bool( $value ) ) {
            $value = $value ? 'true' : 'false';
        } elseif ( is_null( $value ) ) {
            $value = '';
        }

        return esc_html( (string) $value );
    }

	/**
	 * Enqueue styles for the front area.
	 *
	 * @since {VERSION}
	 */
	public function enqueue_styles(): void {
		wp_enqueue_style(
			'td-variable-fields',
			td_variable_fields_URL . 'assets/build/css/main.css',
			[],
			Plugin::VERSION,
			'all'
		);
	}

	/**
	 * Enqueue scripts for the front area.
	 *
	 * @since {VERSION}
	 */
	public function enqueue_scripts(): void {
		wp_enqueue_script(
			'td-variable-fields',
			td_variable_fields_URL . 'assets/build/js/main.js',
			[],
			Plugin::VERSION,
			true
		);
	}

}
