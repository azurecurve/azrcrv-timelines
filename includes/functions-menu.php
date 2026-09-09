<?php
/*
	menu functions - admin menu registration and page rendering. The
	admin-post save handler for the Settings tab lives in
	functions-settings.php, alongside the functions it calls.

	This plugin is deliberately reachable only via the shared azurecurve
	cross-plugin menu (registered in azurecurve-menu-display.php), not its
	own top-level ClassicPress admin menu page - matching the pre-2.0.0
	plugin's navigation.
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
 * Add settings link on the Plugins list page.
 *
 * Fixes a pre-2.0.0 bug where this hooked the generic 'plugin_action_links'
 * filter (which fires once per plugin row for every plugin on the site)
 * instead of the plugin-specific 'plugin_action_links_{$basename}' filter -
 * see add_action( 'plugins_loaded', ... ) below where this is hooked to the
 * specific filter for this plugin only.
 */
function add_plugin_action_link( $links ) {

	$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=' . PLUGIN_HYPHEN ) ) . '"><img src="' . esc_url( plugins_url( '../assets/images/logo.svg', __FILE__ ) ) . '" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />' . esc_html__( 'Settings', 'azrcrv-t' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}

/**
 * Add this plugin's entry under the shared azurecurve cross-plugin menu.
 */
function create_admin_menu() {

	add_submenu_page(
		'azrcrv-plugin-menu',
		esc_html__( 'Timelines Settings', 'azrcrv-t' ),
		esc_html__( 'Timelines', 'azrcrv-t' ),
		'manage_options',
		PLUGIN_HYPHEN,
		__NAMESPACE__ . '\\display_admin_page'
	);
}
