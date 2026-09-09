<?php
/*
	"Timeline Link" meta box - lets an entry optionally link out to another
	post/page (e.g. a full write-up), shown as a small link icon next to the
	entry's title in the rendered timeline.
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
 * Register the meta box on the Timeline Entry edit screen.
 */
function add_meta_box_timeline_link() {
	add_meta_box(
		PLUGIN_UNDERSCORE . '_meta_box',
		esc_html__( 'Timeline Entry Meta Fields', 'azrcrv-t' ),
		__NAMESPACE__ . '\\render_meta_box',
		CPT_SLUG,
		'normal',
		'high'
	);
}

/**
 * Render the meta box.
 */
function render_meta_box( $post ) {

	$meta_fields = get_post_meta( $post->ID, META_KEY, true );
	$link        = is_array( $meta_fields ) && isset( $meta_fields['timeline-link'] ) ? $meta_fields['timeline-link'] : '';

	wp_nonce_field( PLUGIN_UNDERSCORE . '_meta_box', PLUGIN_UNDERSCORE . '_meta_box_nonce' );
	?>
	<p>
		<label for="<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_timeline_link"><?php esc_html_e( 'Timeline Link', 'azrcrv-t' ); ?></label>
		<br />
		<input
			type="url"
			name="<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_meta[timeline-link]"
			id="<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_timeline_link"
			style="width: 100%;"
			value="<?php echo esc_attr( $link ); ?>"
			placeholder="https://example.com/full-story/"
		/>
		<span class="description"><?php esc_html_e( 'Optional URL this timeline entry should link to (e.g. a full post or an external page).', 'azrcrv-t' ); ?></span>
	</p>
	<?php
}

/**
 * Save the meta box.
 *
 * Fixes three bugs present in the pre-2.0.0 plugin:
 *  - the permission check used to read a $_POST key that was never actually
 *    submitted, so current_user_can() never ran on save;
 *  - the submitted value was saved with no sanitisation at all;
 *  - the field name has been namespaced (previously bare 'timelines_metafields')
 *    to avoid any risk of colliding with another meta box on the same screen.
 */
function save_meta_box( $post_id ) {

	if ( ! isset( $_POST[ PLUGIN_UNDERSCORE . '_meta_box_nonce' ] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ PLUGIN_UNDERSCORE . '_meta_box_nonce' ] ) ), PLUGIN_UNDERSCORE . '_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$submitted_link = '';
	if ( isset( $_POST[ PLUGIN_UNDERSCORE . '_meta' ]['timeline-link'] ) ) {
		$submitted_link = esc_url_raw( wp_unslash( $_POST[ PLUGIN_UNDERSCORE . '_meta' ]['timeline-link'] ) );
	}

	$new = array( 'timeline-link' => $submitted_link );
	$old = get_post_meta( $post_id, META_KEY, true );

	if ( '' !== $submitted_link ) {
		update_post_meta( $post_id, META_KEY, $new );
	} elseif ( $old ) {
		delete_post_meta( $post_id, META_KEY );
	}
}
