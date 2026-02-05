<?php
/**
 * td_variable_fields Settings
 *
 * @since   {VERSION}
 * @link    {URL}
 * @license GPLv2 or later
 * @package td_variable_fields
 * @author  {AUTHOR}
 */

namespace TD\VariableFields\Admin;

use TD\VariableFields\Plugin;

/**
 * Class SettingsPage
 *
 * @since   {VERSION}
 *
 * @package td_variable_fields\Admin
 */
class SettingsPage {

	/**
	 * Init hooks
	 *
	 * @since {VERSION}
	 */
	public function hooks(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_menu', [ $this, 'add_menu' ] );

        // Handle form submit from admin-post.php
        add_action( 'admin_post_td_variable_fields_save', [ $this, 'handle_save' ] );
        add_action( 'admin_post_td_variable_fields_delete', [ $this, 'handle_delete' ] );
	}

	/**
	 * Register the styles for the admin area.
	 *
	 * @since {VERSION}
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_styles( string $hook_suffix ): void {
		if ( false === strpos( $hook_suffix, Plugin::SLUG ) ) {
			return;
		}

		wp_enqueue_style(
			'td-variable-fields-settings',
			td_variable_fields_URL . 'assets/build/css/admin/settings.css',
			[],
			Plugin::VERSION,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since {VERSION}
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_scripts( string $hook_suffix ): void {
		if ( false === strpos( $hook_suffix, Plugin::SLUG ) ) {
			return;
		}

		wp_enqueue_script(
			'td-variable-fields-settings',
			td_variable_fields_URL . 'assets/build/js/admin/settings.js',
			[ 'jquery' ],
			Plugin::VERSION,
			true
		);
	}

	/**
	 * Add plugin page in WordPress menu.
	 *
	 * @since {VERSION}
	 */
	public function add_menu(): void {
        add_menu_page(
            esc_html__( 'TD Variable Fields', 'td-variable-fields' ),
            esc_html__( 'Variable Fields', 'td-variable-fields' ),
            'manage_options',
            Plugin::SLUG,
            [
                $this,
                'page_options',
            ],
            'dashicons-editor-table',
            81
        );
	}

    /**
     * Handle saving a variable (v1).
     *
     * @since {VERSION}
     */
    public function handle_save(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You are not allowed to do this.', 'td-variable-fields' ) );
        }

        check_admin_referer( 'td_variable_fields_save', '_tdvf_nonce' );

        $key         = isset( $_POST['tdvf_key'] ) ? sanitize_key( wp_unslash( $_POST['tdvf_key'] ) ) : '';
        $type        = isset( $_POST['tdvf_type'] ) ? sanitize_key( wp_unslash( $_POST['tdvf_type'] ) ) : 'text';
        $description = isset( $_POST['tdvf_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tdvf_description'] ) ) : '';

        // Note: value sanitizing depends on type.
        $raw_value = isset( $_POST['tdvf_value'] ) ? wp_unslash( $_POST['tdvf_value'] ) : '';

        if ( '' === $key ) {
            wp_safe_redirect(
                add_query_arg(
                    [
                        'page'   => Plugin::SLUG,
                        'tdvf'   => 'error',
                        'reason' => 'missing_key',
                    ],
                    admin_url( 'admin.php' )
                )
            );
            exit;
        }

        if ( ! in_array( $type, [ 'text', 'number', 'bool' ], true ) ) {
            $type = 'text';
        }

        $value = null;

        if ( 'number' === $type ) {
            $value = is_numeric( $raw_value ) ? ( 0 + $raw_value ) : 0;
        } elseif ( 'bool' === $type ) {
            // Checkbox sends "1" when checked, nothing when not.
            $value = ( '1' === (string) $raw_value );
        } else {
            $value = sanitize_text_field( (string) $raw_value );
        }

        $all = get_option( 'td_variable_fields', [] );
        if ( ! is_array( $all ) ) {
            $all = [];
        }

        $all[ $key ] = [
            'type'        => $type,
            'value'       => $value,
            'description' => ( '' === $description ) ? null : $description,
        ];

        update_option( 'td_variable_fields', $all, false );

        wp_safe_redirect(
            add_query_arg(
                [
                    'page' => Plugin::SLUG,
                    'tdvf' => 'saved',
                    'key'  => rawurlencode( $key ),
                ],
                admin_url( 'admin.php' )
            )
        );
        exit;
    }

    /**
     * Handle deleting a variable (v1).
     *
     * @since {VERSION}
     */
    public function handle_delete(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You are not allowed to do this.', 'td-variable-fields' ) );
        }

        $key = isset( $_GET['key'] ) ? sanitize_key( wp_unslash( $_GET['key'] ) ) : '';
        if ( '' === $key ) {
            wp_safe_redirect(
                add_query_arg(
                    [
                        'page' => Plugin::SLUG,
                        'tdvf' => 'error',
                    ],
                    admin_url( 'admin.php' )
                )
            );
            exit;
        }

        check_admin_referer( 'td_variable_fields_delete_' . $key );

        $all = get_option( 'td_variable_fields', [] );
        if ( ! is_array( $all ) ) {
            $all = [];
        }

        if ( isset( $all[ $key ] ) ) {
            unset( $all[ $key ] );
            update_option( 'td_variable_fields', $all, false );
        }

        wp_safe_redirect(
            add_query_arg(
                [
                    'page' => Plugin::SLUG,
                    'tdvf' => 'deleted',
                    'key'  => rawurlencode( $key ),
                ],
                admin_url( 'admin.php' )
            )
        );
        exit;
    }

	/**
	 * Plugin page callback.
	 *
	 * @since {VERSION}
	 */
	public function page_options(): void {
		require_once td_variable_fields_PATH . 'templates/admin/settings.php';
	}

}
