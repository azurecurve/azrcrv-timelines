<?php
/*
	plugin images functions
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\Timelines;

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Custom plugin image path. Points Update Manager at this plugin's own
 * assets/images folder (banner/icon) rather than falling back to a generic
 * default image.
 */
function custom_image_path( $path ) {
	if ( strpos( $path, PLUGIN_SLUG ) !== false ) {
		$path = plugin_dir_path( PLUGIN_FILE ) . 'assets/images';
	}
	return $path;
}

/**
 * Custom plugin image url.
 */
function custom_image_url( $url ) {
	if ( strpos( $url, PLUGIN_SLUG ) !== false ) {
		$url = plugin_dir_url( PLUGIN_FILE ) . 'assets/images';
	}
	return $url;
}
