<?php
/**
 * Plugin Name: Plugin Favourites
 * Plugin URI:  https://github.com/emirpprime/pluginfavourites
 * Description:  Simple plugin to display a users favourited plugins from the WordPress.org repository.
 * Version:     0.0.1
 * Author:      Phil Banks
 * Author URI:  https://customcreative.co.uk
 * Text Domain: plugin-favourites
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * Register shortcode to retrieve favourites.
 * @param  string $atts Values supplied to shortcode.
 * @return string       Content to be displayed.
 */
function cc_wp_plugin_favourites( $atts ) {
	$atts = shortcode_atts( array(
		'user' => '',
	), $atts, 'pluginfavourites' );

	// Ensure we can access a required function.
	if ( ! function_exists( 'plugins_api' ) ) {
		include_once ABSPATH . '/wp-admin/includes/plugin-install.php';
	}

	if ( ! empty( $atts['user'] ) ) {

		// Retrieve data using plugins_api().
		// See https://code.tutsplus.com/tutorials/communicating-with-the-wordpressorg-plugin-api--wp-33069 for other args.
	    $plugins_api = plugins_api( 'query_plugins', array( 'user' => $atts['user'], 'per_page' => '-1' ) );

	    if ( is_wp_error( $plugins_api ) ) {

	        return '<pre>' . print_r( $plugins_api->get_error_message(), true ) . '</pre>';

	    } else {

			$output = '<p>Found: ' . $plugins_api->info['results'] . '</p>';
			$outout .= '<ul>';
			foreach ( $plugins_api->plugins as $plugin ) {
				$output .= '<li><a href="https://en-gb.wordpress.org/plugins/' . $plugin->slug . '">' . $plugin->name . '</a> by ' . $plugin->author . '<br/><i>' . $plugin->short_description . '</i></li>';
			}
			$outout .= '</ul>';

			return $output;

	    }

	}

	return 'You must supply a username to retrieve thier favourites';
}
add_shortcode( 'pluginfavourites', 'cc_wp_plugin_favourites' );
