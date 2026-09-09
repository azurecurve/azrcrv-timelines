<?php
/*
	settings functions - settings are stored as a single option (see
	SETTINGS_OPTION_NAME in the main plugin file), shaped as:

	array(
		'color'                           => '#007FFF',
		'default'                         => '',      // default timeline taxonomy term ID
		'date'                            => 'd/m/Y',
		'dateleftalignment'               => '-150px',
		'orderby'                         => 'Ascending',
		'style'                           => 'classic',
		'integrate-with-flags-and-nearby' => 0,
		'flag-width'                      => 16,
		'delete-data-on-uninstall'        => 0,
	)
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
 * Render the admin page (Settings/Instructions/Other Plugins, in tabs).
 */
function display_admin_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'azrcrv-t' ) );
	}

	echo '<div class="wrap ' . esc_attr( PLUGIN_HYPHEN ) . '-wrap">';
	echo '<h1>';
		echo '<a href="' . esc_url_raw( DEVELOPER_RAW_LINK ) . esc_attr( PLUGIN_SHORT_SLUG ) . '/"><img src="' . esc_url_raw( plugins_url( '../assets/images/logo.svg', __FILE__ ) ) . '" style="padding-right: 6px; height: 20px; width: 20px;" alt="' . esc_attr( DEVELOPER_NAME ) . '" /></a>';
		echo esc_html( get_admin_page_title() );
	echo '</h1>';

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only status flag, not a state-changing action.
	if ( isset( $_GET[ PLUGIN_HYPHEN . '-message' ] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$message_key = sanitize_key( wp_unslash( $_GET[ PLUGIN_HYPHEN . '-message' ] ) );
		render_admin_notice( $message_key );
	}

	require_once __DIR__ . '/tabs-output.php';

	echo '</div>';
}

/**
 * Show a dismissible admin notice for a given message key, set via a
 * redirect query arg after a save action.
 */
function render_admin_notice( $message_key ) {

	$messages = array(
		'settings-saved' => array( 'success', __( 'Settings saved.', 'azrcrv-t' ) ),
		'invalid-nonce'  => array( 'error', __( 'Security check failed - please try again.', 'azrcrv-t' ) ),
	);

	if ( ! isset( $messages[ $message_key ] ) ) {
		return;
	}

	list( $type, $text ) = $messages[ $message_key ];
	$css_class            = 'success' === $type ? 'notice-success' : 'notice-error';

	echo '<div class="notice ' . esc_attr( $css_class ) . ' is-dismissible"><p>' . esc_html( $text ) . '</p></div>';
}

/**
 * Build a redirect URL back to the admin page with a status message.
 */
function redirect_with_message( $message_key ) {
	wp_safe_redirect(
		add_query_arg(
			array(
				'page'                      => PLUGIN_HYPHEN,
				PLUGIN_HYPHEN . '-message' => $message_key,
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}

/**
 * The plugin's built-in default values.
 *
 * 'color', 'date', 'dateleftalignment' and 'orderby' defaults are unchanged
 * from the pre-2.0.0 plugin, so upgrading keeps the same look for anyone
 * who never visited the settings page. 'style', and 'delete-data-on-uninstall'
 * are new in 2.0.0 (see the upgrade PRD).
 */
function get_builtin_settings() {
	return array(
		'color'                           => '#007FFF',
		'default'                         => '',
		'date'                            => 'd/m/Y',
		'dateleftalignment'               => '-150px',
		'orderby'                         => 'Ascending',
		'style'                           => 'classic',
		'integrate-with-flags-and-nearby' => 0,
		'flag-width'                      => 16,
		'delete-data-on-uninstall'        => 0,
	);
}

/**
 * Get the saved settings, merged over the built-in defaults so every key is
 * always present even for a fresh install - fixes a bug in the pre-2.0.0
 * plugin where a fresh install's un-set option (get_option() returning
 * false) was read from directly, producing PHP warnings/deprecation notices
 * both on the settings page and inside the save handler.
 */
function get_settings() {
	$stored = get_option( SETTINGS_OPTION_NAME, array() );

	if ( ! is_array( $stored ) ) {
		$stored = array();
	}

	return wp_parse_args( $stored, get_builtin_settings() );
}

/**
 * Persist the settings.
 */
function save_settings( $settings ) {
	update_option( SETTINGS_OPTION_NAME, $settings );
}

/**
 * Check whether a given azurecurve companion plugin is active. Uses
 * is_plugin_active() directly (safe here, since this is only ever called
 * from the admin settings screen, where wp-admin/includes/plugin.php is
 * already loaded).
 */
function is_companion_plugin_active( $plugin_basename ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	return is_plugin_active( $plugin_basename );
}

/**
 * Build a sanitized settings array from a submitted settings form ($_POST).
 */
function sanitize_settings_from_post( $post_data ) {

	$valid_orderby = array( 'Ascending', 'Descending' );
	$valid_style   = array( 'classic', 'alternating', 'compact' );

	$orderby = isset( $post_data['orderby'] ) ? sanitize_text_field( $post_data['orderby'] ) : 'Ascending';
	if ( ! in_array( $orderby, $valid_orderby, true ) ) {
		$orderby = 'Ascending';
	}

	$style = isset( $post_data['style'] ) ? sanitize_key( $post_data['style'] ) : 'classic';
	if ( ! in_array( $style, $valid_style, true ) ) {
		$style = 'classic';
	}

	return array(
		'color'                           => isset( $post_data['color'] ) ? sanitize_text_field( $post_data['color'] ) : '',
		'default'                         => isset( $post_data['default'] ) ? sanitize_text_field( $post_data['default'] ) : '',
		'date'                            => isset( $post_data['date'] ) ? sanitize_text_field( $post_data['date'] ) : '',
		'dateleftalignment'               => isset( $post_data['dateleftalignment'] ) ? sanitize_text_field( $post_data['dateleftalignment'] ) : '',
		'orderby'                         => $orderby,
		'style'                           => $style,
		'integrate-with-flags-and-nearby' => isset( $post_data['integrate-with-flags-and-nearby'] ) ? 1 : 0,
		'flag-width'                      => isset( $post_data['flag-width'] ) ? absint( $post_data['flag-width'] ) : 16,
		'delete-data-on-uninstall'        => isset( $post_data['delete-data-on-uninstall'] ) ? 1 : 0,
	);
}

/**
 * Handle the "Save Settings" form on the Settings tab.
 */
function handle_save_settings() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permissions to perform this action.', 'azrcrv-t' ) );
	}

	if ( ! isset( $_POST[ PLUGIN_HYPHEN . '-nonce' ] ) || ! check_admin_referer( PLUGIN_HYPHEN . '-save-settings', PLUGIN_HYPHEN . '-nonce' ) ) {
		redirect_with_message( 'invalid-nonce' );
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce already verified above.
	$settings = sanitize_settings_from_post( wp_unslash( $_POST ) );

	save_settings( $settings );

	redirect_with_message( 'settings-saved' );
}
add_action( 'admin_post_' . PLUGIN_UNDERSCORE . '_save_settings', __NAMESPACE__ . '\\handle_save_settings' );
