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
