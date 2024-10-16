<?php

namespace WPElevator\WP_Autoload;

class Autoload {

	private const FILENAME_PREFIXES = [
		'class',
		'interface',
		'trait',
	];

	private $paths = [];

	private $resolved = [];

	public function add( $ns, $directory_path ) {
		$ns_normalized = sprintf( '%s\\', rtrim( $ns, '\\' ) ); // Ensure we never match partial namespaces.

		$this->paths[ $ns_normalized ] = rtrim( $directory_path, '\\/' ); // Remove trailing slashes for consistent concat.
	}

	public function init() {
		spl_autoload_register( [ $this, 'load' ] );
	}

	public function load( $classname ) {
		if ( ! isset( $this->resolved[ $classname ] ) ) {
			foreach ( $this->paths as $ns => $directory_path ) {
				if ( 0 === strpos( $classname, $ns ) ) {
					$files = $this->files_for_class( $directory_path, str_replace( $ns, '', $classname ) );

					foreach ( $files as $file ) {
						if ( is_readable( $file ) ) {
							$this->resolved[ $classname ] = $file; // Cache the resolved.
						}
					}
				}
			}
		}

		if ( isset( $this->resolved[ $classname ] ) ) {
			require_once $this->resolved[ $classname ];
		}
	}

	protected function files_for_class( $directory, $classname ) {
		$parts = explode( '\\', strtolower( str_replace( '_', '-', $classname ) ) ); // Map class name to file path segments.

		array_unshift( $parts, $directory ); // Prepend the base directory.

		$filename = array_pop( $parts ); // Get the filename to build additional files for traits and interfaces.

		return array_map(
			function ( $prefix ) use ( $filename, $parts ) {
				$parts[] = sprintf( '%s-%s.php', $prefix, $filename );

				return implode( DIRECTORY_SEPARATOR, $parts );
			},
			self::FILENAME_PREFIXES
		);
	}
}
