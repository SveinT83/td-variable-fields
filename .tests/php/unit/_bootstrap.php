<?php
/**
 * Bootstrap file for unit tests that run before all tests.
 *
 * @since   {VERSION}
 * @link    {URL}
 * @license GPLv2 or later
 * @package td_variable_fields
 * @author  {AUTHOR}
 */

define( 'td_variable_fields_DEBUG', true );
define( 'td_variable_fields_PATH', realpath( __DIR__ . '/../../../' ) . '/' );
define( 'ABSPATH', realpath( td_variable_fields_PATH . '../../' ) . '/' );
define( 'td_variable_fields_URL', 'https://site.com/wp-content/plugins/td-variable-fields/' );
