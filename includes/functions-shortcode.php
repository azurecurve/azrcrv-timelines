<?php
/*
	[timeline] shortcode handler and output templates.

	Every style (classic/alternating/compact) shares the same query and the
	same per-entry data-gathering logic below; only build_entry_html() and
	the wrapping markup differ per style, since all three consume the same
	CSS custom properties (--azrcrv-t-color, --azrcrv-t-date-offset) on the
	wrapping element rather than each writing their own inline styles - see
	assets/css/frontend.css.
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
 * [timeline] / [TIMELINE] shortcode handler.
 */
function render_shortcode( $atts ) {

	$options = get_settings();

	$args = shortcode_atts(
		array(
			'slug'    => $options['default'],
			'color'   => $options['color'],
			'date'    => $options['date'],
			'left'    => $options['dateleftalignment'],
			'orderby' => $options['orderby'],
			'style'   => $options['style'],
		),
		$atts,
		'timeline'
	);

	$slug          = sanitize_text_field( $args['slug'] );
	$color         = sanitize_text_field( $args['color'] );
	$date_format   = sanitize_text_field( $args['date'] );
	$date_offset   = sanitize_text_field( $args['left'] );
	$orderby_input = sanitize_text_field( $args['orderby'] );
	$style         = sanitize_key( $args['style'] );

	if ( '' === $color ) {
		$color = '#000';
	}
	if ( '' === $date_format ) {
		$date_format = 'd/m/Y';
	}
	// Fixes a pre-2.0.0 inconsistency where a blank 'left' fell back to a
	// positive offset ('150px') while the settings-page default is
	// negative ('-150px'), which visually broke the date position whenever
	// this fallback was actually hit.
	if ( '' === $date_offset ) {
		$date_offset = '-150px';
	}
	$order = ( 'DESC' === strtoupper( $orderby_input ) || 'Descending' === $orderby_input ) ? 'DESC' : 'ASC';

	$valid_styles = array( 'classic', 'alternating', 'compact' );
	if ( ! in_array( $style, $valid_styles, true ) ) {
		$style = 'classic';
	}

	if ( '' === $slug ) {
		return '<p class="azrcrv-t-empty">' . esc_html__( 'No timeline has been selected.', 'azrcrv-t' ) . '</p>';
	}

	// WP_Query (rather than the pre-2.0.0 plugin's hand-written $wpdb SQL
	// join) so this benefits from core's own object caching and stays
	// resilient to any future change in how terms/posts are joined.
	$query = new \WP_Query(
		array(
			'post_type'      => CPT_SLUG,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => $order,
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- bounded by a single taxonomy term slug, not user-arbitrary.
				array(
					'taxonomy' => TAXONOMY_SLUG,
					'field'    => 'slug',
					'terms'    => $slug,
				),
			),
		)
	);

	if ( ! $query->have_posts() ) {
		return '<p class="azrcrv-t-empty">' . esc_html__( 'This timeline has no entries yet.', 'azrcrv-t' ) . '</p>';
	}

	$entries_html = '';
	$count        = 0;
	foreach ( $query->posts as $timeline_entry ) {
		++$count;
		$entries_html .= build_entry_html( $timeline_entry, $count, $date_format, $style, $options );
	}

	wp_reset_postdata();

	return wrap_entries_html( $entries_html, $color, $date_offset, $style );
}

/**
 * Build the "link out + optional flag" markup for one entry, shared by
 * every style.
 */
function build_entry_link_html( $timeline_entry, $options ) {

	$meta_fields = get_post_meta( $timeline_entry->ID, META_KEY, true );

	if ( ! is_array( $meta_fields ) || empty( $meta_fields['timeline-link'] ) ) {
		return '';
	}

	$link = $meta_fields['timeline-link'];
	$html = '';

	if ( 1 === (int) $options['integrate-with-flags-and-nearby']
		&& is_companion_plugin_active( 'azrcrv-flags/azrcrv-flags.php' )
		&& is_companion_plugin_active( 'azrcrv-nearby/azrcrv-nearby.php' )
		&& function_exists( 'azrcrv_f_flag' ) ) {

		$linked_post_id      = url_to_postid( $link );
		$linked_post_country = $linked_post_id ? get_post_meta( $linked_post_id, '_azrcrv_n_country', true ) : '';

		if ( ! empty( $linked_post_country ) ) {
			$html .= '&nbsp;' . azrcrv_f_flag( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output of a sibling azurecurve plugin's own public API function, which is responsible for escaping its own markup.
				array(
					'id'    => $linked_post_country,
					'width' => absint( $options['flag-width'] ) . 'px',
				)
			);
		}
	}

	$html .= '&nbsp;<a href="' . esc_url( $link ) . '"><img class="azrcrv-t-link-icon" src="' . esc_url( plugins_url( 'assets/images/link.png', PLUGIN_FILE ) ) . '" alt="" /></a>';

	return $html;
}

/**
 * Build the markup for a single timeline entry, for the given style.
 *
 * Fixes two pre-2.0.0 escaping bugs: the entry title is now passed through
 * esc_html(), and the "link out" URL is now passed through esc_url() (see
 * build_entry_link_html() above) before being echoed.
 */
function build_entry_html( $timeline_entry, $count, $date_format, $style, $options ) {

	$title       = esc_html( get_the_title( $timeline_entry ) );
	$link_html   = build_entry_link_html( $timeline_entry, $options );
	$date_html   = esc_html( date_i18n( $date_format, strtotime( $timeline_entry->post_date ) ) );
	$has_content = '' !== trim( wp_strip_all_tags( $timeline_entry->post_content ) );

	// Deliberately NOT apply_filters( 'the_content', ... ) here: that hook is
	// where plugins like Breadcrumbs, related-posts, ad-injection, and share
	// buttons attach themselves to inject markup into "the" main
	// post/page content. Running a timeline entry's content through it means
	// every one of those unrelated filters also fires on each entry, which
	// is not what a caller of this shortcode expects (this was a real
	// regression: on-page breadcrumbs unexpectedly appearing once per
	// timeline entry). Instead, apply only the predictable, non-hookable
	// formatting the pre-2.0.0 plugin was missing (paragraph breaks and
	// shortcode expansion), matching how a small content snippet - not a
	// full single post view - should be rendered.
	$content = $has_content ? wpautop( do_shortcode( $timeline_entry->post_content ) ) : '';

	if ( 'compact' === $style ) {
		return sprintf(
			'<li class="azrcrv-t-entry"><span class="azrcrv-t-date">%1$s</span><span class="azrcrv-t-title">%2$s%3$s</span></li>',
			$date_html,
			$title,
			$link_html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped/built safely above.
		);
	}

	$content_html = '';
	if ( $has_content ) {
		$content_html = '<div class="azrcrv-t-content">' . $content . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from wpautop()/do_shortcode() above, not raw user input.
	}

	$side_class = '';
	if ( 'alternating' === $style ) {
		$side_class = ( 0 === $count % 2 ) ? ' azrcrv-t-entry-right' : ' azrcrv-t-entry-left';
	}

	return sprintf(
		'<li class="azrcrv-t-entry%1$s"><div class="azrcrv-t-entry-inner"><span class="azrcrv-t-circle"></span><span class="azrcrv-t-title">%2$s%3$s</span><span class="azrcrv-t-date">%4$s</span>%5$s</div></li>',
		esc_attr( $side_class ),
		$title,
		$link_html, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped/built safely above.
		$date_html,
		$content_html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped/built safely above.
	);
}

/**
 * Wrap the built entries in the outer element for the given style, exposing
 * colour and date-offset as CSS custom properties rather than the
 * pre-2.0.0 plugin's per-element inline styles, so the whole timeline can
 * be restyled from a theme stylesheet without overriding inline styles.
 */
function wrap_entries_html( $entries_html, $color, $date_offset, $style ) {

	$style_vars = sprintf(
		'--azrcrv-t-color:%1$s;--azrcrv-t-date-offset:%2$s;',
		esc_attr( $color ),
		esc_attr( $date_offset )
	);

	return sprintf(
		'<div class="azrcrv-t azrcrv-t-style-%1$s" style="%2$s"><ul class="azrcrv-t-list">%3$s</ul></div>',
		esc_attr( $style ),
		$style_vars,
		$entries_html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped/built safely above.
	);
}
