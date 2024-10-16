<?php

use PHPUnit\Framework\TestCase;
use Some_Vendor\Some_Package\Test;
use WPElevator\WP_Autoload\Autoload;

class Autoload_Test extends TestCase {

	public function test_can_load_class() {
		$this->assertFalse( class_exists( Test::class ), 'Stub class can\'t be loaded without an autoloader' );

		$autoload = new Autoload();
		$autoload->add( 'Some_Vendor\Some_Package', __DIR__ . '/stubs' );
		$autoload->init();

		$this->assertTrue( class_exists( Test::class ), 'Test class can be loaded.' );
	}
}
