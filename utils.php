<?php

namespace Zeek\Util;

/**
 * Reads an array safely for requested indice
 * Does not throw notices, instead simply returns an empty string if the value does not exist
 *
 * @param array $array
 * @param string $name
 *
 * @return string
 */
function safe_read( $array, $name ) {

	if ( empty( $array[ $name ] ) ) {
		return '';
	}

	return $array[ $name ];
}

/**
 * Checks for and returns a term by the slug
 *
 * Initializes the term if it does not yet exist
 *
 * @param string $slug
 * @param string $taxonomy
 *
 * @return bool
 */
function init_term( $slug, $taxonomy ) {

	if ( ! function_exists( 'get_term_by' ) || ! function_exists( 'wp_insert_term' ) ) {
		return false;
	}

	$term = get_term_by( 'slug', $slug, $taxonomy );

	if ( ! empty( $term->ID ) ) {
		return $term->ID;
	}

	return wp_insert_term( $slug, $taxonomy );
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
	if ( ! $file_exists = file_exists( $filename ) ) {
		return false;
	}

	$file_parts = pathinfo( $filename );

	// Ensure it's a valid file with requested extension
	if ( empty( $file_parts['extension'] ) ) {
		return false;
	} else if ( $extension !== $file_parts['extension'] ) {
		return false;
	}

	$handle = fopen( $filename, 'r' );

	if ( false === $handle ) {
		return false;
	}

	// Finally, let's read our file
	$contents = fread( $handle, filesize( $filename ) );
	fclose( $handle );

	return $contents;
}
