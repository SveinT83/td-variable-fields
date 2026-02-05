<?php
/**
 * PluginTest
 *
 * @since   {VERSION}
 * @link    {URL}
 * @license GPLv2 or later
 * @package td_variable_fields
 * @author  {AUTHOR}
 */

namespace td_variable_fieldsUnitTests;

use Brain\Monkey\Expectation\Exception\ExpectationArgsRequired;
use Exception;
use td_variable_fields\Plugin;
use td_variable_fields\Front\Front;
use td_variable_fieldsTests\TestCase;
use td_variable_fields\Admin\SettingsPage;

use function Brain\Monkey\Functions\expect;
use td_variable_fields\Vendor\Auryn\Injector;

/**
 * Class FrontTest
 */
class PluginTest extends TestCase {

	/**
	 * Test for adding hooks
	 *
	 * @throws ExpectationArgsRequired
	 */
	public function testRunAdmin(): void {
		expect( 'is_admin' )
			->once()
			->withNoArgs()
			->andReturn( true );
		$settings = \Mockery::mock( SettingsPage::class );
		$settings
			->shouldReceive( 'hooks' )
			->once()
			->withNoArgs();
		$injector = \Mockery::mock( Injector::class );
		$injector
			->shouldReceive( 'make' )
			->once()
			->with( SettingsPage::class )
			->andReturn( $settings );
		$plugin = new Plugin( $injector );

		$plugin->run();
	}

	/**
	 * Test for adding hooks
	 *
	 * @throws ExpectationArgsRequired
	 * @throws Exception
	 */
	public function testRunFront(): void {
		expect( 'is_admin' )
			->once()
			->withNoArgs()
			->andReturn( false );
		$front = \Mockery::mock( Front::class );
		$front
			->shouldReceive( 'hooks' )
			->once()
			->withNoArgs();
		$injector = \Mockery::mock( Injector::class );
		$injector
			->shouldReceive( 'make' )
			->once()
			->with( Front::class )
			->andReturn( $front );
		$plugin = new Plugin( $injector );

		$plugin->run();
	}

}
