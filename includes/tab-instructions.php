<?php
/*
	instructions tab
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
?>

<h2><?php esc_html_e( 'Instructions', 'azrcrv-t' ); ?></h2>

<p><?php esc_html_e( 'This plugin lets you build a timeline out of Timeline Entry posts, grouped by a Category, and display it on any post or page using the [timeline] shortcode.', 'azrcrv-t' ); ?></p>

<ol>
	<li><?php esc_html_e( 'Add one or more Timeline Entry posts (in the Timelines menu), giving each a title, a date, some content, and a Category to group it under.', 'azrcrv-t' ); ?></li>
	<li><?php esc_html_e( 'Optionally set a Timeline Link on an entry, to make its title link out to a full write-up.', 'azrcrv-t' ); ?></li>
	<li><?php esc_html_e( 'Place the shortcode on a post or page, e.g. [timeline slug="product-history"].', 'azrcrv-t' ); ?></li>
</ol>

<h3><?php esc_html_e( 'Shortcode attributes', 'azrcrv-t' ); ?></h3>
<table class="widefat striped">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Attribute', 'azrcrv-t' ); ?></th>
			<th><?php esc_html_e( 'Description', 'azrcrv-t' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><code>slug</code></td>
			<td><?php esc_html_e( 'Category slug of the timeline to display. Defaults to the Default Timeline set on the Settings tab.', 'azrcrv-t' ); ?></td>
		</tr>
		<tr>
			<td><code>style</code></td>
			<td><?php esc_html_e( 'classic, alternating, or compact. Defaults to the Default Style set on the Settings tab.', 'azrcrv-t' ); ?></td>
		</tr>
		<tr>
			<td><code>color</code></td>
			<td><?php esc_html_e( 'Any valid CSS colour, overrides the Default Colour setting.', 'azrcrv-t' ); ?></td>
		</tr>
		<tr>
			<td><code>date</code></td>
			<td><?php esc_html_e( 'A PHP date() format string, overrides the Date Format setting.', 'azrcrv-t' ); ?></td>
		</tr>
		<tr>
			<td><code>left</code></td>
			<td><?php esc_html_e( 'CSS offset for the date (Classic style only), overrides the Date Left Alignment setting.', 'azrcrv-t' ); ?></td>
		</tr>
		<tr>
			<td><code>orderby</code></td>
			<td><?php esc_html_e( 'Ascending or Descending, overrides the Order By setting.', 'azrcrv-t' ); ?></td>
		</tr>
	</tbody>
</table>

<p>
	<?php
	printf(
		/* translators: %s: example shortcode. */
		esc_html__( 'For example: %s', 'azrcrv-t' ),
		'<code>[timeline slug="product-history" style="alternating" color="#ff8000"]</code>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static, developer-controlled example markup.
	);
	?>
</p>

<h3><?php esc_html_e( 'Styling', 'azrcrv-t' ); ?></h3>
<p><?php esc_html_e( 'Every style is built from plain CSS custom properties, so you can restyle a timeline from your theme\'s stylesheet without fighting inline styles - see the plugin\'s Development page for details on the available custom properties and class names.', 'azrcrv-t' ); ?></p>
