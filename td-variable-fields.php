<?php
/**
 * Plugin Name:       TD Variable Fields
 * Description:       Manage global custom variables (key → value) for reuse across the site.
 * Version:           1.0.0
 * Requires at least: 5.5
 * Requires PHP:      7.2.5
 * Author:            {AUTHOR}
 * Author URI:        {AUTHOR_URL}
 * License:           MIT
 * Text Domain:       td-variable-fields
 *
 * @package td_variable_fields
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use TD\VariableFields\Plugin;
use Auryn\Injector;

if ( version_compare( phpversion(), '7.2.5', '<' ) ) {

	/**
	 * Display the notice after deactivation.
	 *
	 * @since {VERSION}
	 */
	function td_variable_fields_php_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php
                echo wp_kses(
                        __( 'The minimum version of PHP is <strong>7.2.5</strong>. Please update the PHP on your server and try again.', 'td-variable-fields' ),
                        [
                                'strong' => [],
                        ]
                );
				?>
			</p>
		</div>

		<?php
		// In case this is on plugin activation.
		if ( isset( $_GET['activate'] ) ) { //phpcs:ignore
			unset( $_GET['activate'] ); //phpcs:ignore
		}
	}

	add_action( 'admin_notices', 'td_variable_fields_php_notice' );

	// Don't process the plugin code further.
	return;
}

if ( ! defined( 'td_variable_fields_DEBUG' ) ) {
	/**
	 * Enable plugin debug mod.
	 */
	define( 'td_variable_fields_DEBUG', false );
}
/**
 * Path to the plugin root directory.
 */
define( 'td_variable_fields_PATH', plugin_dir_path( __FILE__ ) );
/**
 * Url to the plugin root directory.
 */
define( 'td_variable_fields_URL', plugin_dir_url( __FILE__ ) );

/**
 * Run plugin function.
 *
 * @since {VERSION}
 *
 * @throws Exception If something went wrong.
 */
function run_td_variable_fields() {

	$autoload_vendor   = td_variable_fields_PATH . 'vendor/autoload.php';
	$autoload_prefixed = td_variable_fields_PATH . 'vendor_prefixed/autoload.php';

    // Load translations. Default strings should be English in code.
    load_plugin_textdomain(
            'td-variable-fields',
            false,
            plugin_basename( dirname( __FILE__ ) ) . '/languages'
    );

	if ( file_exists( $autoload_vendor ) ) {
		require_once $autoload_vendor;
	} elseif ( file_exists( $autoload_prefixed ) ) {
		require_once $autoload_prefixed;
	} else {
		add_action(
			'admin_notices',
			function () {
				if ( ! current_user_can( 'activate_plugins' ) ) {
					return;
				}
				?>
				<div class="notice notice-error">
					<p>
						<?php echo esc_html__( 'TD Variable Fields: Autoloader missing. Run Composer install (vendor/autoload.php) or build the plugin bundle (vendor_prefixed/autoload.php).', 'td-variable-fields' ); ?>
					</p>
				</div>
				<?php
			}
		);

		return;
	}

    $injector = new Injector();
    ( $injector->make( Plugin::class ) )->run();

	/**
	 * You can use the $injector->make( td_variable_fields\Some\Class::class ) for get any plugin class.
	 * More detail: https://github.com/wppunk/WPPlugin#dependency-injection-container
	 */
	do_action( 'td_variable_fields_init', $injector );
}

add_action( 'plugins_loaded', 'run_td_variable_fields' );
