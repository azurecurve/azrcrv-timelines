<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name:		Timelines
 * Description:		Create a timeline and place on pages or posts using the timeline shortcode.
 * Version:			2.0.0
 * Requires CP:		1.0
 * Requires PHP:	8.2
 * Author:			azurecurve
 * Author URI:		https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/timelines/
 * Donate link:		https://development.azurecurve.co.uk/support-development/
 * Text Domain:		azrcrv-t
 * Domain Path:		/assets/languages
 * License:			GPLv2 or later
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.html
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
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
 * Define constants.
 */
const DEVELOPER_SHORTNAME = 'azurecurve';
const DEVELOPER_NAME      = DEVELOPER_SHORTNAME . ' | Development';
const DEVELOPER_RAW_LINK  = 'https://development.azurecurve.co.uk/classicpress-plugins/';
const DEVELOPER_LINK      = '<a href="' . DEVELOPER_RAW_LINK . '">' . DEVELOPER_NAME . '</a>';

const PLUGIN_NAME       = 'Timelines';
const PLUGIN_SHORT_SLUG = 'timelines';
const PLUGIN_SLUG       = 'azrcrv-' . PLUGIN_SHORT_SLUG;
const PLUGIN_HYPHEN     = 'azrcrv-t';
const PLUGIN_UNDERSCORE = 'azrcrv_t';
const PLUGIN_FILE       = __FILE__;

// Option name under which settings are stored (see includes/functions-settings.php).
// Left as the same literal value ('azrcrv-t') used by the pre-2.0.0 plugin so upgrading
// keeps existing settings without any migration step.
const SETTINGS_OPTION_NAME = PLUGIN_HYPHEN;

// Custom post type, taxonomy and post-meta keys - kept identical to the pre-2.0.0 plugin so
// upgrading never loses or orphans any existing timeline entries.
const CPT_SLUG      = 'timeline-entry';
const TAXONOMY_SLUG = 'timeline';
const META_KEY      = 'timelines_metafields';

/**
 * Load the remote update client, which integrates with azurecurve's own
 * Update Manager server so this plugin updates the same way as the rest of
 * the azurecurve plugin family.
 *
 * NOTE: per the upgrade PRD, this file is left completely untouched - it
 * already self-registers via UpdateClient::get_instance() at the bottom of
 * the file, exactly as it did before this upgrade.
 */
require_once dirname( PLUGIN_FILE ) . '/libraries/updateclient/UpdateClient.class.php';

/**
 * Load activation/deactivation functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-activation.php';

/**
 * Load settings functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-settings.php';

/**
 * Load custom post type / taxonomy registration.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-post-type.php';

/**
 * Load the "Timeline Link" meta box.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-meta-box.php';

/**
 * Load the [timeline] shortcode handler and its output templates.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-shortcode.php';

/**
 * Load admin menu functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-menu.php';

/**
 * Load custom plugin icon/banner path functions, used by Update Manager.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-plugin-images.php';

/**
 * Load the shared azurecurve cross-plugin menu: populates this plugin's
 * entry into the shared directory of azurecurve plugins, and (if not
 * already added by another azurecurve plugin on this site) registers the
 * shared top-level "azurecurve" admin menu page that lists them all.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/azurecurve-menu-populate.php';
require_once dirname( PLUGIN_FILE ) . '/includes/azurecurve-menu-display.php';

/**
 * Load admin/front-end script and style enqueue functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-scripts.php';

/**
 * Load language functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-language.php';

/**
 * Load setup of activation/deactivation hooks, actions and filters. This is
 * required last, since it references functions declared in the files above.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/setup.php';
