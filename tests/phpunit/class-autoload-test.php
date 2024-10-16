<?php

use PHPUnit\Framework\TestCase;
use Some_Vendor\Some_Package\Some_Test;
use WPElevator\WP_Autoload\Autoload;

class Autoload_Test extends TestCase {

	public function test_can_load_class() {
		$this->assertFalse( class_exists( Some_Test::class ), 'Test cass is not loaded without a custom autoloader' );

		$autoload = new Autoload();
		$autoload->add( 'Some_Vendor\Some_Package', __DIR__ . '/../phpunit-stubs' );

		$this->assertTrue( class_exists( Some_Test::class ), 'Test class can be loaded.' );
	}
}
