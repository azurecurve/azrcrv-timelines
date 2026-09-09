<?php
/*
	admin and front-end script/style enqueue functions.
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
 * Enqueue admin CSS/JS, only on this plugin's own admin page or the shared
 * azurecurve cross-plugin menu page (both of which can render the
 * azrcrv-ui-tabs component and the plugin-index grid).
 */
function enqueue_admin_assets() {

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only page identifier, not a state-changing action.
	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

	if ( PLUGIN_HYPHEN !== $page && 'azrcrv-plugin-menu' !== $page ) {
		return;
	}

	wp_enqueue_style(
		PLUGIN_HYPHEN . '-admin-standard',
		plugins_url( 'assets/css/admin-standard.css', PLUGIN_FILE ),
		array(),
		'2.0.0'
	);

	wp_enqueue_style(
		PLUGIN_HYPHEN . '-admin-pluginmenu',
		plugins_url( 'assets/css/admin-pluginmenu.css', PLUGIN_FILE ),
		array(),
		'2.0.0'
	);

	wp_enqueue_style(
		PLUGIN_HYPHEN . '-admin',
		plugins_url( 'assets/css/admin.css', PLUGIN_FILE ),
		array( PLUGIN_HYPHEN . '-admin-standard' ),
		'2.0.0'
	);

	wp_enqueue_script(
		PLUGIN_HYPHEN . '-admin-standard',
		plugins_url( 'assets/js/admin-standard.js', PLUGIN_FILE ),
		array(),
		'2.0.0',
		true
	);

	wp_enqueue_script(
		PLUGIN_HYPHEN . '-admin',
		plugins_url( 'assets/js/admin.js', PLUGIN_FILE ),
		array( PLUGIN_HYPHEN . '-admin-standard' ),
		'2.0.0',
		true
	);
}

/**
 * Enqueue the front-end stylesheet, but only on requests that actually
 * contain a [timeline] shortcode.
 *
 * Fixes a pre-2.0.0 bug where the front-end stylesheet was enqueued with an
 * empty dependency argument ('' instead of array()) and a hard-coded
 * '1.0.0' version string that never changed across releases, meaning
 * browsers/proxies would keep serving a stale cached stylesheet after every
 * plugin update.
 */
function maybe_enqueue_frontend_assets( $posts ) {

	if ( is_admin() || empty( $posts ) || ! is_array( $posts ) ) {
		return $posts;
	}

	foreach ( $posts as $post ) {
		if ( ! isset( $post->post_content ) ) {
			continue;
		}
		// Checked case-sensitively against both registered shortcode tags
		// (has_shortcode() itself is case-sensitive), since 'timeline' and
		// 'TIMELINE' are both registered - see includes/setup.php.
		if ( has_shortcode( $post->post_content, 'timeline' ) || has_shortcode( $post->post_content, 'TIMELINE' ) ) {
			wp_enqueue_style(
				PLUGIN_HYPHEN . '-frontend',
				plugins_url( 'assets/css/frontend.css', PLUGIN_FILE ),
				array(),
				'2.0.0'
			);
			break;
		}
	}

	return $posts;
}
