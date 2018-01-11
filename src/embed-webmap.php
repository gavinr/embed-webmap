<?php
/**
 * @package   Embed_Webmap
 * @author    Gavin Rehkemper <gavreh@gmail.com>
 * @license   GPL-2.0+
 * @link      http://gavinr.com/embed-webmap-plugin
 * @copyright 2016 Gavin Rehkemper
 *
 * @wordpress-plugin
 * Plugin Name:       Embed Webmap
 * Plugin URI:        http://gavinr.com/embed-webmap-plugin
 * Description:       Embed a public webmap from ArcGIS Online into WordPress with a shortcode. http://gavinr.com/embed-webmap-plugin for help and details.
 * Version:           2.0.1
 * Author:            Gavin Rehkemper
 * Author URI:        http://gavinr.com
 * Text Domain:       embed-webmap-en
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-embed-webmap.php' );

/*
 *
 * - replace Embed_Webmap with the name of the class defined in
 *   `class-plugin-name.php`
 */
register_activation_hook( __FILE__, array( 'Embed_Webmap', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Embed_Webmap', 'deactivate' ) );


add_action( 'plugins_loaded', array( 'Embed_Webmap', 'get_instance' ) );
