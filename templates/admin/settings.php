<?php
/**
 * Settings template
 *
 * @since   {VERSION}
 * @link    {URL}
 * @license GPLv2 or later
 * @package td_variable_fields
 * @author  {AUTHOR}
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$tdvf_all = get_option( 'td_variable_fields', [] );
if ( ! is_array( $tdvf_all ) ) {
    $tdvf_all = [];
}

// Sort variables by key alphabetically.
ksort( $tdvf_all );

$tdvf_edit_key  = isset( $_GET['edit'] ) ? sanitize_key( wp_unslash( $_GET['edit'] ) ) : ''; // phpcs:ignore
$tdvf_edit_item = ( '' !== $tdvf_edit_key && isset( $tdvf_all[ $tdvf_edit_key ] ) && is_array( $tdvf_all[ $tdvf_edit_key ] ) )
        ? $tdvf_all[ $tdvf_edit_key ]
        : null;

$tdvf_form_key         = $tdvf_edit_item ? $tdvf_edit_key : '';
$tdvf_form_type        = $tdvf_edit_item && isset( $tdvf_edit_item['type'] ) ? (string) $tdvf_edit_item['type'] : 'text';
$tdvf_form_value       = $tdvf_edit_item && array_key_exists( 'value', $tdvf_edit_item ) ? $tdvf_edit_item['value'] : '';
$tdvf_form_description = $tdvf_edit_item && isset( $tdvf_edit_item['description'] ) ? (string) $tdvf_edit_item['description'] : '';

?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <?php if ( isset( $_GET['tdvf'] ) && 'saved' === $_GET['tdvf'] ) : // phpcs:ignore ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo esc_html__( 'Variable saved.', 'td-variable-fields' ); ?></p>
        </div>
    <?php endif; ?>

    <?php if ( isset( $_GET['tdvf'] ) && 'deleted' === $_GET['tdvf'] ) : // phpcs:ignore ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo esc_html__( 'Variable deleted.', 'td-variable-fields' ); ?></p>
        </div>
    <?php endif; ?>

    <?php if ( isset( $_GET['tdvf'] ) && 'error' === $_GET['tdvf'] ) : // phpcs:ignore ?>
        <div class="notice notice-error is-dismissible">
            <p><?php echo esc_html__( 'Something went wrong.', 'td-variable-fields' ); ?></p>
        </div>
    <?php endif; ?>

    <h2>
        <?php echo esc_html( $tdvf_edit_item ? __( 'Edit variable', 'td-variable-fields' ) : __( 'Add variable', 'td-variable-fields' ) ); ?>
    </h2>

    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="td_variable_fields_save" />
        <?php wp_nonce_field( 'td_variable_fields_save', '_tdvf_nonce' ); ?>

        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="tdvf_key"><?php echo esc_html__( 'Key', 'td-variable-fields' ); ?></label>
                </th>
                <td>
                    <input
                            name="tdvf_key"
                            id="tdvf_key"
                            type="text"
                            class="regular-text"
                            placeholder="example_key"
                            required
                            value="<?php echo esc_attr( $tdvf_form_key ); ?>"
                            <?php echo $tdvf_edit_item ? 'readonly' : ''; ?>
                    />
                    <p class="description">
                        <?php
                        echo esc_html__(
                                $tdvf_edit_item ? 'Key cannot be changed in edit mode.' : 'Use lowercase letters, numbers and underscores.',
                                'td-variable-fields'
                        );
                        ?>
                    </p>

                    <?php if ( $tdvf_edit_item ) : ?>
                        <p>
                            <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=td-variable-fields' ) ); ?>">
                                <?php echo esc_html__( 'Cancel edit', 'td-variable-fields' ); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="tdvf_type"><?php echo esc_html__( 'Type', 'td-variable-fields' ); ?></label>
                </th>
                <td>
                    <select name="tdvf_type" id="tdvf_type">
                        <option value="text" <?php selected( $tdvf_form_type, 'text' ); ?>><?php echo esc_html__( 'Text', 'td-variable-fields' ); ?></option>
                        <option value="number" <?php selected( $tdvf_form_type, 'number' ); ?>><?php echo esc_html__( 'Number', 'td-variable-fields' ); ?></option>
                        <option value="bool" <?php selected( $tdvf_form_type, 'bool' ); ?>><?php echo esc_html__( 'Boolean', 'td-variable-fields' ); ?></option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="tdvf_value"><?php echo esc_html__( 'Value', 'td-variable-fields' ); ?></label>
                </th>
                <td>
                    <input
                            name="tdvf_value"
                            id="tdvf_value"
                            type="text"
                            class="regular-text"
                            value="<?php echo esc_attr( is_bool( $tdvf_form_value ) ? ( $tdvf_form_value ? '1' : '' ) : (string) $tdvf_form_value ); ?>"
                    />
                    <p class="description"><?php echo esc_html__( 'For boolean: enter 1 for true (or leave empty for false).', 'td-variable-fields' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="tdvf_description"><?php echo esc_html__( 'Description', 'td-variable-fields' ); ?></label>
                </th>
                <td>
                    <textarea name="tdvf_description" id="tdvf_description" class="large-text" rows="3"><?php echo esc_textarea( $tdvf_form_description ); ?></textarea>
                </td>
            </tr>
            </tbody>
        </table>

        <?php submit_button( esc_html__( 'Save variable', 'td-variable-fields' ) ); ?>
    </form>

    <hr />

    <h2><?php echo esc_html__( 'Existing variables', 'td-variable-fields' ); ?></h2>

    <?php if ( empty( $tdvf_all ) ) : ?>
        <p><?php echo esc_html__( 'No variables yet.', 'td-variable-fields' ); ?></p>
    <?php else : ?>
        <table class="widefat striped">
            <thead>
            <tr>
                <th><?php echo esc_html__( 'Key', 'td-variable-fields' ); ?></th>
                <th><?php echo esc_html__( 'Type', 'td-variable-fields' ); ?></th>
                <th><?php echo esc_html__( 'Value', 'td-variable-fields' ); ?></th>
                <th><?php echo esc_html__( 'Description', 'td-variable-fields' ); ?></th>
                <th><?php echo esc_html__( 'Actions', 'td-variable-fields' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ( $tdvf_all as $tdvf_key => $tdvf_item ) : ?>
                <?php
                $tdvf_edit_url = add_query_arg(
                        [
                                'page' => 'td-variable-fields',
                                'edit' => rawurlencode( (string) $tdvf_key ),
                        ],
                        admin_url( 'admin.php' )
                );

                $tdvf_delete_url = wp_nonce_url(
                        add_query_arg(
                                [
                                        'action' => 'td_variable_fields_delete',
                                        'key'    => rawurlencode( (string) $tdvf_key ),
                                ],
                                admin_url( 'admin-post.php' )
                        ),
                        'td_variable_fields_delete_' . (string) $tdvf_key
                );
                ?>
                <tr>
                    <td><code><?php echo esc_html( (string) $tdvf_key ); ?></code></td>
                    <td><?php echo esc_html( isset( $tdvf_item['type'] ) ? (string) $tdvf_item['type'] : '' ); ?></td>
                    <td>
                        <?php
                        $val = isset( $tdvf_item['value'] ) ? $tdvf_item['value'] : null;
                        echo esc_html( is_bool( $val ) ? ( $val ? 'true' : 'false' ) : (string) $val );
                        ?>
                    </td>
                    <td><?php echo esc_html( isset( $tdvf_item['description'] ) ? (string) $tdvf_item['description'] : '' ); ?></td>
                    <td>
                        <a class="button button-small" href="<?php echo esc_url( $tdvf_edit_url ); ?>">
                            <?php echo esc_html__( 'Edit', 'td-variable-fields' ); ?>
                        </a>
                        <a
                                class="button button-small button-link-delete"
                                href="<?php echo esc_url( $tdvf_delete_url ); ?>"
                                onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to delete this variable?', 'td-variable-fields' ) ); ?>');"
                        >
                            <?php echo esc_html__( 'Delete', 'td-variable-fields' ); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
