# WP Autoload

PHP autoloader that can load classes from files named according to the WordPress coding standards (with `class-`, `abstract-` and `trait-` prefixes). Use this if for some reason you can't use the Composer classmap autoloader.

Alternatively, create a copy of the [`Autoload` class](php/class-autoload.php) in your project and update the namespace to match your project.

Example:

	use WPElevator\WP_Autoload\Autoload;

	$autoload = new Autoload();
	$autoload->add( 'Your_Vendor\Your_Namespace', __DIR__ . '/php' );
