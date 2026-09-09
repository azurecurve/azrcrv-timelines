<?php
/*
	activation / deactivation functions
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
 * Plugin activation. Registers the custom post type/taxonomy immediately so
 * a subsequent permalink flush (also done here) picks up the 'timeline'
 * rewrite slug on the very first activation, rather than requiring the
 * admin to visit Settings > Permalinks manually.
 */
function activate_plugin() {
	create_custom_post_type();
	create_timeline_taxonomy();
	flush_rewrite_rules();
}

/**
 * Plugin deactivation. Deliberately does NOT delete the settings option, any
 * timeline entries, or the taxonomy terms - deactivating a plugin should
 * never lose content or configuration; that only happens on uninstall (see
 * uninstall.php), and even then only if the admin has opted in via the
 * "Delete timeline entries and taxonomy terms on uninstall" setting.
 */
function deactivate_plugin() {
	flush_rewrite_rules();
}
