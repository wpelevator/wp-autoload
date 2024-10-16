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

	public function add( $namespace, $directory_path ) {
		$namespace_normalized = sprintf( '%s\\', rtrim( $namespace, '\\' ) ); // Ensure we never match partial namespaces.

		$this->paths[ $namespace_normalized ] = rtrim( $directory_path, '\\/' ); // Remove trailing slashes for consistent concat.
	}

	public function init() {
		spl_autoload_register( [ $this, 'load' ] );
	}

	public function load( $class ) {
		if ( ! isset( $this->resolved[ $class ] ) ) {
			foreach ( $this->paths as $namespace => $directory_path ) {
				if ( 0 === strpos( $class, $namespace ) ) {
					$files = $this->files_for_class( $directory_path, str_replace( $namespace, '', $class ) );

					foreach ( $files as $file ) {
						if ( is_readable( $file ) ) {
							$this->resolved[ $class ] = $file; // Cache the resolved.
						}
					}
				}
			}
		}

		if ( isset( $this->resolved[ $class ] ) ) {
			require_once $this->resolved[ $class ];
		}
	}

	protected function files_for_class( $directory, $class ) {
		$parts = explode( '\\', strtolower( str_replace( '_', '-', $class ) ) ); // Map class name to file path segments.

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
