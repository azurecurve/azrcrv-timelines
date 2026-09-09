<?php
/*
	uninstall.php

	Fixes two pre-2.0.0 bugs:
	 - the option was previously never actually removed on uninstall (this
	   file referenced an undefined constant, so the delete call was never
	   reached);
	 - uninstall only ever ran against the current site, not every site on
	   a multisite network.

	Deleting Timeline Entry posts and 'timeline' taxonomy terms is opt-in,
	controlled by the "Delete timeline entries and taxonomy terms on
	uninstall" checkbox on the Settings tab (off by default) - see the
	'delete-data-on-uninstall' key read below.
*/

// Prevent direct access; only allow execution when triggered by
// ClassicPress/WordPress itself uninstalling this plugin.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}

const AZRCRV_T_UNINSTALL_OPTION_NAME = 'azrcrv-t';
const AZRCRV_T_UNINSTALL_CPT_SLUG    = 'timeline-entry';
const AZRCRV_T_UNINSTALL_TAX_SLUG    = 'timeline';

/**
 * Delete this plugin's settings option, and - only if the admin opted in via
 * the Settings tab - every Timeline Entry post and every 'timeline'
 * taxonomy term, on the current site.
 */
function azrcrv_t_uninstall_current_site() {

	$settings = get_option( AZRCRV_T_UNINSTALL_OPTION_NAME, array() );

	$delete_content = is_array( $settings )
		&& ! empty( $settings['delete-data-on-uninstall'] );

	if ( $delete_content ) {

		$entry_ids = get_posts(
			array(
				'post_type'      => AZRCRV_T_UNINSTALL_CPT_SLUG,
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);
		foreach ( $entry_ids as $entry_id ) {
			wp_delete_post( $entry_id, true );
		}

		$terms = get_terms(
			array(
				'taxonomy'   => AZRCRV_T_UNINSTALL_TAX_SLUG,
				'hide_empty' => false,
			)
		);
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				wp_delete_term( $term->term_id, AZRCRV_T_UNINSTALL_TAX_SLUG );
			}
		}
	}

	delete_option( AZRCRV_T_UNINSTALL_OPTION_NAME );
}

if ( is_multisite() ) {

	$site_ids = get_sites( array( 'fields' => 'ids' ) );

	foreach ( $site_ids as $site_id ) {
		switch_to_blog( $site_id );
		azrcrv_t_uninstall_current_site();
		restore_current_blog();
	}
} else {
	azrcrv_t_uninstall_current_site();
}
