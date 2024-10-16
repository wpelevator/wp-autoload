# WP Autoload

PHP autoloader that can load classes from files named according to the WordPress coding standards (with `class-`, `abstract-` and `trait-` prefixes). Use this if for some reason you can't use the Composer classmap autoloader.

Example:

	use WPElevator\WP_Autoload\Autoload;

	$autoload = new Autoload();
	$autoload->add( 'Your_Vendor\Your_Namespace', __DIR__ . '/php' );

	spl_autoload_register( [ $autoload, 'load' ] );
