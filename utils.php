<?php

namespace Zeek\Util;

function safe_read( $array, $name ) {

	if ( empty( $array[ $name ] ) ) {
		return '';
	}

	return $array[ $name ];
}

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

function is_constant_true( $constant ) {
	if ( ! defined( $constant ) ) {
		return false;
	}

	if ( true !== constant( $constant ) ) {
		return false;
	}

	return true;
}
