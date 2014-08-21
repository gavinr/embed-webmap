<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Embed_Webmap
 * @author    Gavin Rehkemper <gavreh@gmail.com>
 * @license   GPL-2.0+
 * @link      http://gavinr.com/embed-webmap-plugin
 * @copyright 2014 Gavin Rehkemper
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

if ( is_multisite() ) {

	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );

	if ( $blogs ) {

	 	foreach ( $blogs as $blog ) {
			switch_to_blog( $blog['blog_id'] );
			restore_current_blog();
		}
	}

} else {
	// nothing, for now
}