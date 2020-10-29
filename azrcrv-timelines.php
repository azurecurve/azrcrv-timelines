<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Timelines
 * Description: Create a timeline and place on pages or posts using the timeline shortcode.
 * Version: 1.3.0
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/timelines/
 * Text Domain: timelines
 * Domain Path: /languages
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

// include plugin menu
require_once(dirname( __FILE__).'/pluginmenu/menu.php');
add_action('admin_init', 'azrcrv_create_plugin_menu_t');

// include update client
require_once(dirname(__FILE__).'/libraries/updateclient/UpdateClient.class.php');

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
add_action('admin_menu', 'azrcrv_t_create_admin_menu');
add_action('admin_post_azrcrv_t_save_options', 'azrcrv_t_save_options');
add_action('init', 'azrcrv_t_create_custom_post_type');
add_action('init', 'azrcrv_t_create_timeline_taxonomy', 0);
add_action('add_meta_boxes', 'azrcrv_t_add_meta_box');
add_action('save_post', 'azrcrv_t_save_meta_box');
add_action('plugins_loaded', 'azrcrv_t_load_languages');

// add filters
add_filter('the_posts', 'azrcrv_t_check_for_shortcode', 10, 2);
add_filter('plugin_action_links', 'azrcrv_t_add_plugin_action_link', 10, 2);
add_filter('codepotent_update_manager_image_path', 'azrcrv_t_custom_image_path');
add_filter('codepotent_update_manager_image_url', 'azrcrv_t_custom_image_url');

// add shortcodes
add_shortcode('timeline', 'azrcrv_t_shortcode');
add_shortcode('TIMELINE', 'azrcrv_t_shortcode');

/**
 * Load language files.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_load_languages() {
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('timelines', false, $plugin_rel_path);
}

/**
 * Check if shortcode on current page and then load css and jqeury.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_check_for_shortcode($posts){
    if (empty($posts)){
        return $posts;
	}
	
	
	// array of shortcodes to search for
	$shortcodes = array(
						'timeline','TIMELINE'
						);
	
    // loop through posts
    $found = false;
    foreach ($posts as $post){
		// loop through shortcodes
		foreach ($shortcodes as $shortcode){
			// check the post content for the shortcode
			if (has_shortcode($post->post_content, $shortcode)){
				$found = true;
				// break loop as shortcode found in page content
				break 2;
			}
		}
	}
 
    if ($found){
		// as shortcode found call functions to load css and jquery
        azrcrv_t_load_css();
    }
    return $posts;
}

/**
 * Load CSS.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_load_css(){
	wp_enqueue_style('azrcrv-t', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
}

/**
 * Custom plugin image path.
 *
 * @since 1.3.0
 *
 */
function azrcrv_t_custom_image_path($path){
    if (strpos($path, 'azrcrv-timelines') !== false){
        $path = plugin_dir_path(__FILE__).'assets/pluginimages';
    }
    return $path;
}

/**
 * Custom plugin image url.
 *
 * @since 1.3.0
 *
 */
function azrcrv_t_custom_image_url($url){
    if (strpos($url, 'azrcrv-timelines') !== false){
        $url = plugin_dir_url(__FILE__).'assets/pluginimages';
    }
    return $url;
}

/**
 * Get options including defaults.
 *
 * @since 1.4.0
 *
 */
function azrcrv_t_get_option($option_name){
 
	$defaults = array(
						'color' => '#007FFF',
						'default' => '',
						'date' => 'd/m/Y',
						'dateleftalignment' => '-150px',
						'orderby' => 'Ascending',
						'integrate-with-flags-and-nearby' => 0,
					);

	$options = get_option($option_name, $defaults);

	$options = wp_parse_args($options, $defaults);

	return $options;

}

/**
 * Add Timelines action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.admin_url('admin.php?page=azrcrv-t').'"><img src="'.plugins_url('/pluginmenu/images/Favicon-16x16.png', __FILE__).'" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />'.esc_html__('Settings' ,'timelines').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add to menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_create_admin_menu(){
	
	// add settings to timelines submenu
	add_submenu_page(
				'edit.php?post_type=timeline-entry'
				,__('Timelines Settings', 'timelines')
				,__('Settings', 'timelines')
				,'manage_options'
				,'azrcrv-t'
				,'azrcrv_t_display_options');
	
	// add settings to azurecurve menu
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Timelines Settings", "timelines")
						,esc_html__("Timelines", "timelines")
						,'manage_options'
						,'azrcrv-t'
						,'azrcrv_t_display_options');
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_display_options(){
	if (!current_user_can('manage_options')){
		$error = new WP_Error('not_found', esc_html__('You do not have sufficient permissions to access this page.' , 'timelines'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
    }
	
	// Retrieve plugin site options from database
	$options = azrcrv_t_get_option('azrcrv-t');
	?>
	<div id="azrcrv-t-general" class="wrap">
		<fieldset>
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<?php if(isset($_GET['options-updated'])){ ?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e('Settings have been saved.','timelines') ?></strong></p>
				</div>
			<?php } ?>

			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="azrcrv_t_save_options" />
				<input name="page_options" type="hidden" value="color,timeline,date" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field('azrcrv-t', 'azrcrv-t-nonce'); ?>
				<table class="form-table">
				
					<tr><th scope="row"><label for="explanation"><?php esc_html_e('Shortcode usage', 'timelines'); ?></label></th>
					<td>
						<p class="description"><?php esc_html_e('All parameters except slug are optional: [timeline slug=\'test-timeline\' color=\'green\' orderby=\'DESC/ASC\' date=\'d/m/Y\' left=\'-150px\']', 'timelines'); ?></p>
					</td></tr>
					
					<tr><th scope="row"><label for="color"><?php esc_html_e('Default Color', 'timelines'); ?></label></th><td>
						<input type="text" name="color" value="<?php echo esc_html(stripslashes($options['color'])); ?>" class="regular-text" />
						<p class="description"><?php esc_html_e('Specify default color of timeline (this can be overriden in the shortcode using the parameter "color=#007FFF;")', 'timelines'); ?></p>
					</td></tr>
					
					<tr><th scope="row"><label for="timeline"><?php esc_html_e('Default Timeline', 'timelines'); ?></label></th><td>
						<select name="timeline" style="width: 200px;">
						<?php
							$timelines = get_terms('timeline', array('orderby' => 'name', 'hide_empty' => 0));
							if ($timelines){
								foreach ($timelines as $timeline){
									//echo '|'.$timeline->term_id.'|';
									echo "<option value='".$timeline->term_id."' ";
									if (isset($options["timeline"])){
										$timeline_option = $options["timeline"];
									}else{
										$timeline_option= '';
									}
									echo selected($timeline_option, $timeline->term_id).">";
									echo esc_html($timeline->name);
									echo "</option>";
								}        
							}
						?>
						</select>
					</td></tr>
					
					<tr><th scope="row"><label for="date"><?php esc_html_e('Default Date Format', 'timelines'); ?></label></th><td>
						<input type="text" name="date" value="<?php echo esc_html(stripslashes($options['date'])); ?>" class="regular-text" />
						<p class="description"><?php esc_html_e('Specify default date format (default is d/M/Y)', 'timelines'); ?></p>
					</td></tr>
					
					<tr><th scope="row"><label for="dateleftalignment"><?php esc_html_e('Date Left Alignment', 'timelines'); ?></label></th><td>
						<input type="text" name="dateleftalignment" value="<?php echo esc_html(stripslashes($options['dateleftalignment'])); ?>" class="regular-text" />
						<p class="description"><?php esc_html_e('Specify left alignment for date (default for d/M/Y is -150px)', 'timelines'); ?></p>
					</td></tr>
					
					<tr><th scope="row"><label for="orderby"><?php esc_html_e('Default Timeline Order By', 'timelines'); ?></label></th><td>
						<select name="orderby" style="width: 200px;">
						<?php
							$orderbyarray = array('Ascending', 'Descending');
							foreach ($orderbyarray as $orderby){
								echo "<option value='".$orderby."' ";
								echo selected($options["orderby"], $orderby).">";
								echo esc_html($orderby);
								echo "</option>";
							}
						?>
						</select>
					</td></tr>
				
					<?php
					if (azrcrv_t_is_plugin_active('azrcrv-flags/azrcrv-flags.php')){
						$flags = '<a href="admin.php?page=azrcrv-f">Flags</a>';
						$flags_active = true;
					}else{
						$flags = '<a href="https://development.azurecurve.co.uk/classicpress-plugins/flags/">Flags</a>';
						$flags_active = false;
					}
					?>
					<?php
					if (azrcrv_t_is_plugin_active('azrcrv-nearby/azrcrv-nearby.php')){
						$nearby = '<a href="admin.php?page=azrcrv-n">Nearby</a>';
						$nearby_active = true;
					}else{
						$nearby = '<a href="https://development.azurecurve.co.uk/classicpress-plugins/nearby/">Nearby</a>';
						$nearby_active = false;
					}
					?>
					<tr><th scope="row"><label for="integrate-with-flags-and-nearby"><?php printf(__('Display country flag?', 'timelines'), $flags, $nearby); ?></label></th><td>
						<?php
							if ($flags_active AND $nearby_active){ ?>
								<fieldset><legend class="screen-reader-text"><span><?php printf(esc_html_e('Integrate with %s and %s to display country flag?', 'timelines'), $flags, $nearby); ?></span></legend>
									<label for="integrate-with-flags-and-nearby"><input name="integrate-with-flags-and-nearby" type="checkbox" id="integrate-with-flags-and-nearby" value="1" <?php checked('1', $options['integrate-with-flags-and-nearby']); ?> /><?php esc_html_e('Display flag next to timeline entry', 'timelines'); ?></label>
								</fieldset>
							<?php
							}else{
								printf(__('Both %s and %s must be active for this function to be available.', 'timelines'), $flags, $nearby);
							}
						?>
					</td></tr>
					
				</table>
				<input type="submit" value="<?php esc_html_e('Submit', 'timelines'); ?>" class="button-primary"/>
			</form>
		</fieldset>
	</div>
	<?php
}

/**
 * Check if function active (included due to standard function failing due to order of load).
 *
 * @since 1.2.0
 *
 */
function azrcrv_t_is_plugin_active($plugin){
    return in_array($plugin, (array) get_option('active_plugins', array()));
}

/**
 * Save settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_save_options(){
	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		$error = new WP_Error('not_found', esc_html__('You do not have sufficient permissions to perform this action.' , 'timelines'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
    }
	
	// Check that nonce field created in configuration form is present
	if (! empty($_POST) && check_admin_referer('azrcrv-t', 'azrcrv-t-nonce')){
		// Retrieve original plugin options array
		$options = get_option('azrcrv-t');
		
		$option_name = 'color';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'timeline';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'date';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'dateleftalignment';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		$option_name = 'orderby';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'integrate-with-flags-and-nearby';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		update_option('azrcrv-t', $options);
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-t&options-updated', admin_url('admin.php')));
		exit;
	}
}

/**
 * Display timeline in shortcode.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_shortcode($atts, $content = null){
	
	global $wpdb;
	// Retrieve plugin configuration options from database
	$options = azrcrv_t_get_option('azrcrv-t');
	
	$args = shortcode_atts(array(
		'slug' => stripslashes(sanitize_text_field($options['default'])),
		'color' => stripslashes(sanitize_text_field($options['color'])),
		'date' => stripslashes(sanitize_text_field($options['date'])),
		'left' => stripslashes(sanitize_text_field($options['dateleftalignment'])),
		'orderby' => stripslashes(sanitize_text_field($options['orderby'])),
	), $atts);
	$slug = $args['slug'];
	$color = $args['color'];
	$date = $args['date'];
	$left = $args['left'];
	$orderby = $args['orderby'];
	
	if ($color == ''){ $color = '#000'; }
	if ($date == ''){ $date = 'd/m/Y'; }
	if ($orderby != 'ASC' and $orderby != 'DESC'){
		if ($orderby == 'Descending'){
			$orderby = 'DESC';
		}else{
			$orderby = 'ASC';
		}
	}
	if ($left == ''){ $left = '150px'; }
	$left = 'style="left:'.$left.'; "';
	
	$sql = $wpdb->prepare("select p.ID,p.post_date,p.post_title,p.post_name,p.post_content,t.name,t.slug from ".$wpdb->prefix."posts as p
			inner join ".$wpdb->prefix."term_relationships as tr on tr.object_id = p.ID
			inner join ".$wpdb->prefix."term_taxonomy as tt on tt.term_taxonomy_id = tr.term_taxonomy_id
			inner join ".$wpdb->prefix."terms as t on t.term_id = tt.term_id
			where post_type = 'timeline-entry' and post_status = 'publish' and t.slug = %s
			order by post_date ".$orderby, $slug);
	//echo $sql;
	$return = "<div style='display: block; clear: both; '><ul id='azrcrv-t' style='border-left-color: $color; '>";
	$count = 0;
	$timeline_entries = $wpdb->get_results($sql);
	foreach ($timeline_entries as $timeline_entry){
		$count++;
		$return .= "<li class='azrcrv-t-work'>
			<div class='azrcrv-t-relative'>
			  <span class='azrcrv-t-title'><label class='azcrv-t' for='azrcrv-t-work$count'>".$timeline_entry->post_title;
			  
		// get link
		$meta_fields = get_post_meta($timeline_entry->ID, 'azc_t_metafields', true);
		if (is_array($meta_fields)){
			if (isset($meta_fields['timeline-link'])){
				if (strlen($meta_fields['timeline-link']) > 0){
					$linked_post_id = url_to_postid($meta_fields['timeline-link']);
					if (azrcrv_t_is_plugin_active('azrcrv-flags/azrcrv-flags.php') AND azrcrv_t_is_plugin_active('azrcrv-nearby/azrcrv-nearby.php') AND $options['integrate-with-flags-and-nearby'] == 1){
						$linked_post_country = get_post_meta( $linked_post_id, '_azrcrv_n_country', true );
						// get country and display flag
						if (strlen($linked_post_country) > 0){
							$return .= '&nbsp;'.do_shortcode('[flag='.$linked_post_country.']');
						}
					}
					$return .= "&nbsp;<a href='".$meta_fields['timeline-link']."'><img class='azc_t' src='".plugin_dir_url(__FILE__)."assets/images/link.png' /></a>";
		
				}
			}
		}
		$return .= "</label></span>
			  <span class='azrcrv-t-date' $left>".Date($date, strtotime($timeline_entry->post_date))."</span>
			  <span class='azrcrv-t-circle' style='border-color: $color; '></span>
			</div>";
		if (strlen($timeline_entry->post_content) > 0){
		$return .= "
			<div class='azrcrv-t-content'>
			  <p>
				".$timeline_entry->post_content."
			  </p>
			</div>";
		}
		$return .= "
		  </li>";
	}
	$return .= "</ul></div>";
	return $return;
}

/**
 * Create custom Timeline post type.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_create_custom_post_type(){
	register_post_type('timeline-entry',
		array(
				'labels' => array(
									'name' => esc_html__('Timelines', 'azc_t'),
									'singular_name' => esc_html__('Timeline Entry', 'azc_t'),
									'add_new' => esc_html__('Add New', 'azc_t'),
									'add_new_item' => esc_html__('Add New Timeline Entry', 'azc_t'),
									'edit' => esc_html__('Edit', 'azc_t'),
									'edit_item' => esc_html__('Edit Timeline Entry', 'azc_t'),
									'new_item' => esc_html__('New Timeline Entry', 'azc_t'),
									'view' => esc_html__('View', 'azc_t'),
									'view_item' => esc_html__('View Timeline Entry', 'azc_t'),
									'search_items' => esc_html__('Search Timeline Entries', 'azc_t'),
									'not_found' => esc_html__('No Timeline Entry found', 'azc_t'),
									'not_found_in_trash' => esc_html__('No Timeline Entries found in Trash', 'azc_t'),
									'parent' => esc_html__('Parent Timeline Entry', 'azc_t')
								),
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'menu_position' => 20,
			'supports' => array('title', 'revisions', 'excerpt', 'editor'),
			'taxonomies' => array(''),
			'menu_icon' => plugins_url('assets/images/timelines-16x16.png', __FILE__),
			'has_archive' => false
		)
	);
}

/**
 * Register Timeline taxonomy.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_create_timeline_taxonomy(){
$labels = array(
		'name'              => esc_html__('Timeline Parents', 'azc_t'),
		'singular_name'     => esc_html__('Timeline Parent', 'azc_t'),
		'search_items'      => esc_html__('Search Timelines', 'azc_t'),
		'all_items'         => esc_html__('All Timelines', 'azc_t'),
		'parent_item'       => esc_html__('Parent Timeline', 'azc_t'),
		'parent_item_colon' => esc_html__('Parent Timeline:', 'azc_t'),
		'edit_item'         => esc_html__('Edit Timeline', 'azc_t'),
		'update_item'       => esc_html__('Update Timeline', 'azc_t'),
		'add_new_item'      => esc_html__('Add New Timeline', 'azc_t'),
		'new_item_name'     => esc_html__('New Timeline', 'azc_t'),
		'menu_name'         => esc_html__('Timeline Parents', 'azc_t'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'timeline'),
	);

	register_taxonomy('timeline', 'timeline-entry', $args);

}

/**
 * Add meta hox.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_add_meta_box(){
	add_meta_box(
		'azc_t_meta_box', // $id
		'Timeline Entry Meta Fields', // $title
		'azrcrv_t_show_meta_box', // $callback
		'timeline-entry', // $screen
		'normal', // $context
		'high' // $priority
	);
}


/**
 * Show meta box.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_show_meta_box(){
	global $post;  
	
	$meta_fields = get_post_meta($post->ID, 'azc_t_metafields', true); ?>

	<input type="hidden" name="azc_t_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>">

	<p>
		<label for="azc_t_metafields[timeline-link]">Timeline Link</label>
		&nbsp;&nbsp;&nbsp;
		<input type="text" name="azc_t_metafields[timeline-link]" id="azc_t_metafields[timeline-link]" style="width: 100%; " value="<?php if (isset($meta_fields['timeline-link'])) { echo $meta_fields['timeline-link']; } ?>">
	</p>

<?php

}

/**
 * Save meta box.
 *
 * @since 1.0.0
 *
 */
function azrcrv_t_save_meta_box($post_id){   
	// verify nonce
	if (!isset($_POST['azc_t_meta_box_nonce']) OR !wp_verify_nonce($_POST['azc_t_meta_box_nonce'], basename(__FILE__))){
		return $post_id; 
	}
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
		return $post_id;
	}
	// check permissions
	if ('page' === $_POST['timeline-link']){
		if (!current_user_can('edit_page', $post_id)){
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)){
			return $post_id;
		}  
	}
	
	$old = get_post_meta($post_id, 'azc_t_metafields', true);
	$new = $_POST['azc_t_metafields'];

	if ($new && $new !== $old){
		update_post_meta($post_id, 'azc_t_metafields', $new);
	} elseif ('' === $new && $old){
		delete_post_meta($post_id, 'azc_t_metafields', $old);
	}
}

?>