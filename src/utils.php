<?php

namespace Zeek\PHP_Util;

/**
 * Reads an array or object safely for the requested key
 *
 * Returns false if the value does not exist
 *
 * @param array|object  $item
 * @param string        $key
 *
 *                          
 * @return mixed
 */
function safe_read( $item, $key ) {

	if ( is_object( $item ) ) {
		if ( isset( $item->$key ) ) {
			return $item->$key;
		}

		return false;
	}

	if ( empty( $item[ $key ] ) ) {
		return false;
	}

	return $item[ $key ];
}

/**
 * Checks that a constant is defined and that it is true
 *
 * Returns false if the constant does not exist or if it is not true
 *
 * @param string $constant
 *
 * @return bool
 */
function is_constant_true( $constant ) {
	if ( ! defined( $constant ) ) {
		return false;
	}

	if ( true !== constant( $constant ) ) {
		return false;
	}

	return true;
}

/**
 * Load a file with a given filename and extension
 *
 * Acceptable extensions are:
 *   - xml
 *   - sql
 *   - txt
 *   - json
 *
 * @param $filename
 * @param $extension
 *
 * @return bool|string
 * @throws \Exception
 */
function get_file( $filename, $extension ) {
	if ( ! in_array( $extension, [ 'xml', 'sql', 'txt', 'json' ] ) ) {
		throw new \Exception( 'Invalid file type requested' );
	}

	$filename = $filename . '.' . $extension;

	// Can't open a file that doesn't exist
	if ( ! file_exists( $filename ) ) {
		return false;
	}

	$handle = fopen( $filename, 'r' );

	// Finally, let's read our file
	$contents = fread( $handle, filesize( $filename ) );
	fclose( $handle );

	return $contents;
}

/**
 * Recursively remove a directory
 *
 * @param $dir
 * @return bool
 */
function rrmdir( $dir ) {

	if ( ! file_exists( $dir ) ) {
		return false;
	}

	foreach ( glob( $dir . '/*' ) as $file ) {
		if ( is_dir( $file ) ) {
			rrmdir( $file );
			continue;
		}

		unlink( $file );
	}

	rmdir( $dir );

	return true;
}
