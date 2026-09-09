<?php
/*
	settings tab
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

$settings = get_settings();

// Modern array-argument form of get_terms() (fixes a deprecated pre-4.5
// calling convention used by the pre-2.0.0 plugin).
$timeline_terms = get_terms(
	array(
		'taxonomy'   => TAXONOMY_SLUG,
		'hide_empty' => false,
	)
);
if ( is_wp_error( $timeline_terms ) ) {
	$timeline_terms = array();
}

$styles = array(
	'classic'     => esc_html__( 'Classic (vertical line, current default look)', 'azrcrv-t' ),
	'alternating' => esc_html__( 'Alternating (entries zigzag left/right of a centred line)', 'azrcrv-t' ),
	'compact'     => esc_html__( 'Compact (single-line date + title list)', 'azrcrv-t' ),
);

$flags_active  = is_companion_plugin_active( 'azrcrv-flags/azrcrv-flags.php' );
$nearby_active = is_companion_plugin_active( 'azrcrv-nearby/azrcrv-nearby.php' );
?>

<p><?php esc_html_e( 'These settings control the default appearance of every [timeline] shortcode. Most can be overridden per shortcode using the color, date, left, orderby and style attributes - see the Instructions tab.', 'azrcrv-t' ); ?></p>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

	<input type="hidden" name="action" value="<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_save_settings" />
	<?php wp_nonce_field( PLUGIN_HYPHEN . '-save-settings', PLUGIN_HYPHEN . '-nonce' ); ?>

	<table class="form-table azrcrv-t-settings" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><label for="style"><?php esc_html_e( 'Default Style', 'azrcrv-t' ); ?></label></th>
				<td>
					<fieldset>
						<?php foreach ( $styles as $style_value => $style_label ) : ?>
							<label style="display:block;margin-bottom:6px;">
								<input type="radio" name="style" value="<?php echo esc_attr( $style_value ); ?>" <?php checked( $settings['style'], $style_value ); ?> />
								<?php echo esc_html( $style_label ); ?>
							</label>
						<?php endforeach; ?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="color"><?php esc_html_e( 'Default Colour', 'azrcrv-t' ); ?></label></th>
				<td>
					<input type="text" name="color" id="color" value="<?php echo esc_attr( $settings['color'] ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Any valid CSS colour, e.g. #007FFF.', 'azrcrv-t' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="default"><?php esc_html_e( 'Default Timeline', 'azrcrv-t' ); ?></label></th>
				<td>
					<select name="default" id="default">
						<option value=""><?php esc_html_e( '- none -', 'azrcrv-t' ); ?></option>
						<?php foreach ( $timeline_terms as $term ) : ?>
							<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $settings['default'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
						<?php endforeach; ?>
					</select>
					<p class="description"><?php esc_html_e( 'Used when a [timeline] shortcode has no slug attribute.', 'azrcrv-t' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="date"><?php esc_html_e( 'Date Format', 'azrcrv-t' ); ?></label></th>
				<td>
					<input type="text" name="date" id="date" value="<?php echo esc_attr( $settings['date'] ); ?>" class="regular-text" />
					<p class="description">
						<?php
						printf(
							/* translators: %s: link to the PHP date() format documentation. */
							esc_html__( 'Any format accepted by PHP\'s date() function - see %s.', 'azrcrv-t' ),
							'<a href="https://www.php.net/manual/en/datetime.format.php" target="_blank" rel="noopener noreferrer">php.net</a>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static, developer-controlled URL and text.
						);
						?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="dateleftalignment"><?php esc_html_e( 'Date Left Alignment', 'azrcrv-t' ); ?></label></th>
				<td>
					<input type="text" name="dateleftalignment" id="dateleftalignment" value="<?php echo esc_attr( $settings['dateleftalignment'] ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'CSS offset for the date, e.g. -150px. Only used by the Classic style.', 'azrcrv-t' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="orderby"><?php esc_html_e( 'Order By', 'azrcrv-t' ); ?></label></th>
				<td>
					<select name="orderby" id="orderby">
						<option value="Ascending" <?php selected( $settings['orderby'], 'Ascending' ); ?>><?php esc_html_e( 'Ascending', 'azrcrv-t' ); ?></option>
						<option value="Descending" <?php selected( $settings['orderby'], 'Descending' ); ?>><?php esc_html_e( 'Descending', 'azrcrv-t' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Flags / Nearby Integration', 'azrcrv-t' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="integrate-with-flags-and-nearby" value="1" <?php checked( $settings['integrate-with-flags-and-nearby'], 1 ); ?> />
						<?php esc_html_e( 'Show a country flag next to entries with a matching location, using the Flags plugin.', 'azrcrv-t' ); ?>
					</label>
					<p class="description">
						<?php
						if ( $flags_active ) {
							esc_html_e( 'Flags plugin detected as active.', 'azrcrv-t' );
						} else {
							esc_html_e( 'Flags plugin not detected as active - install it for this option to have any effect.', 'azrcrv-t' );
						}
						if ( $nearby_active ) {
							echo ' ' . esc_html__( 'Nearby plugin detected as active.', 'azrcrv-t' );
						}
						?>
					</p>
					<label>
						<?php esc_html_e( 'Flag width:', 'azrcrv-t' ); ?>
						<input type="number" min="1" name="flag-width" value="<?php echo esc_attr( $settings['flag-width'] ); ?>" class="small-text" />
						px
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'On Uninstall', 'azrcrv-t' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="delete-data-on-uninstall" value="1" <?php checked( $settings['delete-data-on-uninstall'], 1 ); ?> />
						<?php esc_html_e( 'Also delete all Timeline Entries and their categories when this plugin is uninstalled.', 'azrcrv-t' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'Leave unchecked to keep your timeline entries if you reinstall the plugin later. This only takes effect on uninstall, not on deactivation.', 'azrcrv-t' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button( __( 'Save Settings', 'azrcrv-t' ) ); ?>
</form>
