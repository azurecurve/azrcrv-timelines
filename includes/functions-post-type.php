<?php
/*
	custom post type and taxonomy registration for Timeline Entries.

	Slugs ('timeline-entry' post type, 'timeline' taxonomy) are kept
	identical to the pre-2.0.0 plugin (see CPT_SLUG / TAXONOMY_SLUG in the
	main plugin file) so upgrading never loses or orphans existing content.
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
 * Register the 'timeline-entry' custom post type.
 *
 * Fixes a leftover bug from the pre-2.0.0 plugin, which declared
 * 'taxonomies' => array('') - an empty-string taxonomy association that did
 * nothing useful. The 'timeline' taxonomy is correctly attached below via
 * register_taxonomy(), so the CPT itself doesn't need to declare it too.
 */
function create_custom_post_type() {

	register_post_type(
		CPT_SLUG,
		array(
			'labels'              => array(
				'name'               => esc_html__( 'Timelines', 'azrcrv-t' ),
				'singular_name'      => esc_html__( 'Timeline Entry', 'azrcrv-t' ),
				'add_new'            => esc_html__( 'Add New', 'azrcrv-t' ),
				'add_new_item'       => esc_html__( 'Add New Timeline Entry', 'azrcrv-t' ),
				'edit'               => esc_html__( 'Edit', 'azrcrv-t' ),
				'edit_item'          => esc_html__( 'Edit Timeline Entry', 'azrcrv-t' ),
				'new_item'           => esc_html__( 'New Timeline Entry', 'azrcrv-t' ),
				'view'               => esc_html__( 'View', 'azrcrv-t' ),
				'view_item'          => esc_html__( 'View Timeline Entry', 'azrcrv-t' ),
				'search_items'       => esc_html__( 'Search Timeline Entries', 'azrcrv-t' ),
				'not_found'          => esc_html__( 'No Timeline Entry found', 'azrcrv-t' ),
				'not_found_in_trash' => esc_html__( 'No Timeline Entries found in Trash', 'azrcrv-t' ),
				'parent'             => esc_html__( 'Parent Timeline Entry', 'azrcrv-t' ),
			),
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'menu_position'       => 20,
			'supports'            => array( 'title', 'revisions', 'excerpt', 'editor' ),
			'menu_icon'           => plugins_url( 'assets/images/timelines-16x16.png', PLUGIN_FILE ),
			'has_archive'         => false,
		)
	);
}

/**
 * Register the 'timeline' taxonomy, attached to the 'timeline-entry' post type.
 */
function create_timeline_taxonomy() {

	$labels = array(
		'name'              => esc_html__( 'Categories', 'azrcrv-t' ),
		'singular_name'     => esc_html__( 'Category', 'azrcrv-t' ),
		'search_items'      => esc_html__( 'Search Categories', 'azrcrv-t' ),
		'all_items'         => esc_html__( 'All Categories', 'azrcrv-t' ),
		'parent_item'       => esc_html__( 'Parent Category', 'azrcrv-t' ),
		'parent_item_colon' => esc_html__( 'Parent Category:', 'azrcrv-t' ),
		'edit_item'         => esc_html__( 'Edit Category', 'azrcrv-t' ),
		'update_item'       => esc_html__( 'Update Category', 'azrcrv-t' ),
		'add_new_item'      => esc_html__( 'Add New Category', 'azrcrv-t' ),
		'new_item_name'     => esc_html__( 'New Category', 'azrcrv-t' ),
		'menu_name'         => esc_html__( 'Categories', 'azrcrv-t' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => TAXONOMY_SLUG ),
	);

	register_taxonomy( TAXONOMY_SLUG, CPT_SLUG, $args );
}
