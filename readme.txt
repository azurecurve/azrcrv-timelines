=== Timelines ===

Description:	Create timelines showing the sequence of events and place in any post or page using a shortcode.
Version:		1.7.1
Tags:			timeline, timelines, custom post type
Author:			azurecurve
Author URI:		https://development.azurecurve.co.uk/
Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/timelines/
Download link:	https://github.com/azurecurve/azrcrv-timelines/releases/download/v1.7.1/azrcrv-timelines.zip
Donate link:	https://development.azurecurve.co.uk/support-development/
Requires PHP:	5.6
Requires:		1.0.0
Tested:			4.9.99
Text Domain:	timelines
Domain Path:	/languages
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Create timelines showing the sequence of events and place in any post or page using a shortcode.

== Description ==

# Description

Create timelines showing the sequence of events and place in any post or page using a shortcode.

Timelines create a custom post type for timeline entry; timelines are used by adding the `[timeline={timeline name}]` shortcode

Integrate with [Flags](https://development.azurecurve.co.uk/classicpress-plugins/flags/) and [Nearby](https://development.azurecurve.co.uk/classicpress-plugins/nearby/) to display a country flag next to timeline entry.; Nearby is required for the setting of a country on a post or page.

This plugin is multisite compatible; each site will need settings to be configured in the admin dashboard.

== Installation ==

# Installation Instructions

 * Download the latest release of the plugin from [GitHub](https://github.com/azurecurve/azrcrv-timelines/releases/latest/).
 * Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
 * Activate the plugin.
 * Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

== Frequently Asked Questions ==

# Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot file is in the plugins languages folder; if you do translate this plugin, please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

== Changelog ==

# Changelog

### [Version 1.7.1](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.7.1)
 * Update azurecurve menu.
 * Update readme files.

### [Version 1.7.0](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.7.0)
 * Update translations to escape strings.
 * Update azurecurve menu and logo.
 
### [Version 1.6.0](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.6.0)
 * Add width setting for integration with [Flags by azurecurve](https://development.azurecurve.co.uk/classicpress-plugins/flags/) plugin.

### [Version 1.5.0](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.5.0)
 * Rename taxonomy to Categories.
 * Update azurecurve menu.
 * Fix bug in azurecurve menu.

### [Version 1.4.1](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.4.1)
 * Fix version number bug.
 
### [Version 1.4.0](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.4.0)
 * Fix plugin action link to use admin_url() function.
 * Rewrite option handling so defaults not stored in database on plugin initialisation.
 * Update azurecurve plugin menu.
 * Amend to only load css when shortcode on page.
 
### [Version 1.3.0](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.3.0)
 * Add Settings to Timelines admin menu.
 * Rename Timeline Parents submenu page.
 * Amend width of Timeline Link field in Timeline Entry metabox.
 * Add plugin icon and banner.
 
### [Version 1.2.2](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.2.2)
 * Fix bug with display of flag when Flags and Nearby installed, but not active.

### [Version 1.2.1](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.2.1)
 * Fix bug with display of flag display option.

### [Version 1.2.0](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.2.0)
 * Integrate with [Flags](https://development.azurecurve.co.uk/classicpress-plugins/flags/) and [Nearby](https://development.azurecurve.co.uk/classicpress-plugins/nearby/) to display a country flag next to timeline entry.
 * Fix bug with default timeline parameter when slug not provided.

### [Version 1.1.6](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.1.6)
 * Fix bug with undefined index in save metabox function.

### [Version 1.1.5](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.1.5)
 * Fix bug with setting of default options.
 * Fix bug with plugin menu.
 * Update plugin menu css.

### [Version 1.1.4](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.1.4)
 * Rewrite default option creation function to resolve several bugs.
 * Upgrade azurecurve plugin to store available plugins in options.

### [Version 1.1.3](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.1.3)
 * Update Update Manager class to v2.0.0.
 * Update action link.
 * Update azurecurve menu icon with compressed image.

### [Version 1.1.2](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.1.2)
 * Correct problem with version number.

### [Version 1.1.1](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.1.1)
 * Fix bug with incorrect language load text domain.

### [Version 1.1.0](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.1.0)
 * Add integration with Update Manager for automatic updates.
 * Fix issue with display of azurecurve menu.
 * Change settings page heading.
 * Add load_plugin_textdomain to handle translations.

### [Version 1.0.1](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.0.1)
 * Fix bug with css label.
 * Update azurecurve menu for easier maintenance.
 * Move require of azurecurve menu below security check.
 * Localization fixes.
 * Fix bug with icon display.

### [Version 1.0.0](https://github.com/azurecurve/azrcrv-timelines/releases/tag/v1.0.0)
 * Initial release for ClassicPress forked from azurecurve Timelines WordPress Plugin.

== Other Notes ==

# About azurecurve

**azurecurve** was one of the first plugin developers to start developing for Classicpress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager) for fully integrated, no hassle, updates.

Some of the other plugins available from **azurecurve** are:
 * Add Open Graph Tags - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-open-graph-tags/) / [download](https://github.com/azurecurve/azrcrv-add-open-graph-tags/releases/latest/)
 * Avatars - [details](https://development.azurecurve.co.uk/classicpress-plugins/avatars/) / [download](https://github.com/azurecurve/azrcrv-avatars/releases/latest/)
 * Breadcrumbs - [details](https://development.azurecurve.co.uk/classicpress-plugins/breadcrumbs/) / [download](https://github.com/azurecurve/azrcrv-breadcrumbs/releases/latest/)
 * Display After Post Content - [details](https://development.azurecurve.co.uk/classicpress-plugins/display-after-post-content/) / [download](https://github.com/azurecurve/azrcrv-display-after-post-content/releases/latest/)
 * Estimated Read Time - [details](https://development.azurecurve.co.uk/classicpress-plugins/estimated-read-time/) / [download](https://github.com/azurecurve/azrcrv-estimated-read-time/releases/latest/)
 * RSS Feed - [details](https://development.azurecurve.co.uk/classicpress-plugins/rss-feed/) / [download](https://github.com/azurecurve/azrcrv-rss-feed/releases/latest/)
 * Shortcodes in Comments - [details](https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-comments/) / [download](https://github.com/azurecurve/azrcrv-shortcodes-in-comments/releases/latest/)
 * Shortcodes in Widgets - [details](https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/) / [download](https://github.com/azurecurve/azrcrv-shortcodes-in-widgets/releases/latest/)
 * Theme Switcher - [details](https://development.azurecurve.co.uk/classicpress-plugins/theme-switcher/) / [download](https://github.com/azurecurve/azrcrv-theme-switcher/releases/latest/)
 * Update Admin Menu - [details](https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/) / [download](https://github.com/azurecurve/azrcrv-update-admin-menu/releases/latest/)
