<?php

namespace Zeek\PHP_Util;

/**
 * Common dependencies for escaping functions. Taken from WordPress for cross-compatibility.
 * Modified to support the default PHP environment.
 *
 * - Actions/filters removed.
 * - Unnecessary WP environment functions removed.
 * - Use WP function if available.
 */

/**
 * Converts a number of special characters into their HTML entities.
 *
 * Specifically deals with: &, <, >, ", and '.
 *
 * $quote_style can be set to ENT_COMPAT to encode " to
 * &quot;, or ENT_QUOTES to do both. Default is ENT_NOQUOTES where no quotes are encoded.
 *
 * @since 1.2.2
 * @access private
 *
 * @staticvar string $_charset
 *
 * @param string     $string         The text which is to be encoded.
 * @param int|string $quote_style    Optional. Converts double quotes if set to ENT_COMPAT,
 *                                   both single and double if set to ENT_QUOTES or none if set to ENT_NOQUOTES.
 *                                   Also compatible with old values; converting single quotes if set to 'single',
 *                                   double if set to 'double' or both if otherwise set.
 *                                   Default is ENT_NOQUOTES.
 * @param string     $charset        Optional. The character encoding of the string. Default is UTF-8.
 * @param bool       $double_encode  Optional. Whether to encode existing html entities. Default is false.
 * @return string The encoded text with HTML entities.
 */
function _wp_specialchars( $string, $quote_style = ENT_NOQUOTES, $charset = 'UTF-8', $double_encode = false ) {
	if ( function_exists( '\_wp_specialchars' ) ) {
		return \_wp_specialchars($string, $quote_style, $charset, $double_encode);
	}
	$string = (string) $string;

	if ( 0 === strlen( $string ) )
		return '';

	// Don't bother if there are no specialchars - saves some processing
	if ( ! preg_match( '/[&<>"\']/', $string ) )
		return $string;

	// Account for the previous behaviour of the function when the $quote_style is not an accepted value
	if ( empty( $quote_style ) )
		$quote_style = ENT_NOQUOTES;
	elseif ( ! in_array( $quote_style, array( 0, 2, 3, 'single', 'double' ), true ) )
		$quote_style = ENT_QUOTES;

	$_quote_style = $quote_style;

	if ( $quote_style === 'double' ) {
		$quote_style = ENT_COMPAT;
		$_quote_style = ENT_COMPAT;
	} elseif ( $quote_style === 'single' ) {
		$quote_style = ENT_NOQUOTES;
	}

	if ( ! $double_encode ) {
		// Guarantee every &entity; is valid, convert &garbage; into &amp;garbage;
		// This is required for PHP < 5.4.0 because ENT_HTML401 flag is unavailable.
		$string = wp_kses_normalize_entities( $string );
	}

	$string = @htmlspecialchars( $string, $quote_style, $charset, $double_encode );

	// Back-compat.
	if ( 'single' === $_quote_style )
		$string = str_replace( "'", '&#039;', $string );

	return $string;
}

/**
 * Perform a deep string replace operation to ensure the values in $search are no longer present
 *
 * Repeats the replacement operation until it no longer replaces anything so as to remove "nested" values
 * e.g. $subject = '%0%0%0DDD', $search ='%0D', $result ='' rather than the '%0%0DD' that
 * str_replace would return
 *
 * @since 2.8.1
 * @access private
 *
 * @param string|array $search  The value being searched for, otherwise known as the needle.
 *                              An array may be used to designate multiple needles.
 * @param string       $subject The string being searched and replaced on, otherwise known as the haystack.
 * @return string The string with the replaced svalues.
 */
function _deep_replace( $search, $subject ) {
	if ( function_exists( '\_deep_replace' ) ) {
		return \_deep_replace($search, $subject);
	}
	$subject = (string) $subject;

	$count = 1;
	while ( $count ) {
		$subject = str_replace( $search, '', $subject, $count );
	}

	return $subject;
}

/**
 * A wrapper for PHP's parse_url() function that handles consistency in the return
 * values across PHP versions.
 *
 * PHP 5.4.7 expanded parse_url()'s ability to handle non-absolute url's, including
 * schemeless and relative url's with :// in the path. This function works around
 * those limitations providing a standard output on PHP 5.2~5.4+.
 *
 * Secondly, across various PHP versions, schemeless URLs starting containing a ":"
 * in the query are being handled inconsistently. This function works around those
 * differences as well.
 *
 * Error suppression is used as prior to PHP 5.3.3, an E_WARNING would be generated
 * when URL parsing failed.
 *
 * @since 4.4.0
 * @since 4.7.0 The $component parameter was added for parity with PHP's parse_url().
 *
 * @link https://secure.php.net/manual/en/function.parse-url.php
 *
 * @param string $url       The URL to parse.
 * @param int    $component The specific component to retrieve. Use one of the PHP
 *                          predefined constants to specify which one.
 *                          Defaults to -1 (= return all parts as an array).
 * @return mixed False on parse failure; Array of URL components on success;
 *               When a specific component has been requested: null if the component
 *               doesn't exist in the given URL; a string or - in the case of
 *               PHP_URL_PORT - integer when it does. See parse_url()'s return values.
 */
function wp_parse_url( $url, $component = -1 ) {
	if ( function_exists( '\wp_parse_url' ) ) {
		return \wp_parse_url($url, $component);
	}
	$to_unset = array();
	$url = strval( $url );

	if ( '//' === substr( $url, 0, 2 ) ) {
		$to_unset[] = 'scheme';
		$url = 'placeholder:' . $url;
	} elseif ( '/' === substr( $url, 0, 1 ) ) {
		$to_unset[] = 'scheme';
		$to_unset[] = 'host';
		$url = 'placeholder://placeholder' . $url;
	}

	$parts = @parse_url( $url );

	if ( false === $parts ) {
		// Parsing failure.
		return $parts;
	}

	// Remove the placeholder values.
	foreach ( $to_unset as $key ) {
		unset( $parts[ $key ] );
	}

	return _get_component_from_parsed_url_array( $parts, $component );
}

/**
 * Retrieve a specific component from a parsed URL array.
 *
 * @internal
 *
 * @since 4.7.0
 * @access private
 *
 * @link https://secure.php.net/manual/en/function.parse-url.php
 *
 * @param array|false $url_parts The parsed URL. Can be false if the URL failed to parse.
 * @param int    $component The specific component to retrieve. Use one of the PHP
 *                          predefined constants to specify which one.
 *                          Defaults to -1 (= return all parts as an array).
 * @return mixed False on parse failure; Array of URL components on success;
 *               When a specific component has been requested: null if the component
 *               doesn't exist in the given URL; a string or - in the case of
 *               PHP_URL_PORT - integer when it does. See parse_url()'s return values.
 */
function _get_component_from_parsed_url_array( $url_parts, $component = -1 ) {
	if ( function_exists( '\_get_component_from_parsed_url_array' ) ) {
		return \_get_component_from_parsed_url_array($url_parts, $component);
	}
	if ( -1 === $component ) {
		return $url_parts;
	}

	$key = _wp_translate_php_url_constant_to_key( $component );
	if ( false !== $key && is_array( $url_parts ) && isset( $url_parts[ $key ] ) ) {
		return $url_parts[ $key ];
	} else {
		return null;
	}
}

/**
 * Translate a PHP_URL_* constant to the named array keys PHP uses.
 *
 * @internal
 *
 * @since 4.7.0
 * @access private
 *
 * @link https://secure.php.net/manual/en/url.constants.php
 *
 * @param int $constant PHP_URL_* constant.
 * @return string|bool The named key or false.
 */
function _wp_translate_php_url_constant_to_key( $constant ) {
	if ( function_exists( '\_wp_translate_php_url_constant_to_key' ) ) {
		return \_wp_translate_php_url_constant_to_key($constant);
	}
	$translation = array(
		PHP_URL_SCHEME   => 'scheme',
		PHP_URL_HOST     => 'host',
		PHP_URL_PORT     => 'port',
		PHP_URL_USER     => 'user',
		PHP_URL_PASS     => 'pass',
		PHP_URL_PATH     => 'path',
		PHP_URL_QUERY    => 'query',
		PHP_URL_FRAGMENT => 'fragment',
	);

	if ( isset( $translation[ $constant ] ) ) {
		return $translation[ $constant ];
	} else {
		return false;
	}
}

/**
 * Retrieve a list of protocols to allow in HTML attributes.
 *
 * @since 3.3.0
 * @since 4.3.0 Added 'webcal' to the protocols array.
 * @since 4.7.0 Added 'urn' to the protocols array.
 *
 * @see wp_kses()
 * @see esc_url()
 *
 * @staticvar array $protocols
 *
 * @return array Array of allowed protocols. Defaults to an array containing 'http', 'https',
 *               'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet',
 *               'mms', 'rtsp', 'svn', 'tel', 'fax', 'xmpp', 'webcal', and 'urn'.
 */
function wp_allowed_protocols() {
	if ( function_exists( '\wp_allowed_protocols' ) ) {
		return \wp_allowed_protocols();
	}
	static $protocols = array();

	if ( empty( $protocols ) ) {
		$protocols = array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'svn', 'tel', 'fax', 'xmpp', 'webcal', 'urn' );
	}

	return $protocols;
}