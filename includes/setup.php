<?php
/*
	setup - wires up all actions/filters/shortcodes. Loaded last from the
	main plugin file, since it references functions declared in every other
	included file.
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
 * Activation / deactivation.
 */
register_activation_hook( PLUGIN_FILE, __NAMESPACE__ . '\\activate_plugin' );
register_deactivation_hook( PLUGIN_FILE, __NAMESPACE__ . '\\deactivate_plugin' );

/**
 * Custom post type / taxonomy.
 */
add_action( 'init', __NAMESPACE__ . '\\create_custom_post_type' );
add_action( 'init', __NAMESPACE__ . '\\create_timeline_taxonomy', 0 );

/**
 * Meta box.
 */
add_action( 'add_meta_boxes', __NAMESPACE__ . '\\add_meta_box_timeline_link' );
add_action( 'save_post_' . CPT_SLUG, __NAMESPACE__ . '\\save_meta_box' );

/**
 * Shortcode - registered both lower and upper case, matching the pre-2.0.0
 * plugin's behaviour so existing [TIMELINE] usage keeps working.
 */
add_shortcode( 'timeline', __NAMESPACE__ . '\\render_shortcode' );
add_shortcode( 'TIMELINE', __NAMESPACE__ . '\\render_shortcode' );

/**
 * Admin menu and settings.
 */
add_action( 'admin_menu', __NAMESPACE__ . '\\create_admin_menu' );
add_filter( 'plugin_action_links_' . plugin_basename( PLUGIN_FILE ), __NAMESPACE__ . '\\add_plugin_action_link' );

/**
 * Scripts and styles.
 */
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_assets' );
add_filter( 'the_posts', __NAMESPACE__ . '\\maybe_enqueue_frontend_assets' );

/**
 * Language.
 */
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_languages' );

/**
 * Update Manager custom plugin images.
 */
add_filter( 'codepotent_update_manager_image_path', __NAMESPACE__ . '\\custom_image_path' );
add_filter( 'codepotent_update_manager_image_url', __NAMESPACE__ . '\\custom_image_url' );
