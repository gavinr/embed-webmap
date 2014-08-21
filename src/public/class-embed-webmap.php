<?php
/**
 * Plugin Name.
 *
 * @package   Embed_Webmap
 * @author    Gavin Rehkemper <gavreh@gmail.com>
 * @license   GPL-2.0+
 * @link      http://gavinr.com/embed-webmap-plugin
 * @copyright 2014 Gavin Rehkemper
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 *
 * @package Embed_Webmap
 * @author  Gavin Rehkemper <gavreh@gmail.com>
 */
class Embed_Webmap {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'embed-webmap';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_shortcode('webmap', array($this, 'webmap_function'));

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();

					restore_current_blog();
				}

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

					restore_current_blog();

				}

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	private static function get_html_attr($shortcodes, $htmlAttrName) {
		if($shortcodes[$htmlAttrName] !== '') {
			return ' ' . $htmlAttrName . '="' . $shortcodes[$htmlAttrName] . '"';
		} else {
			return '';
		}
	}

	private static function get_query_string_item($shortcodes, $queryStringItemName) {
		if($shortcodes[$queryStringItemName] !== '') {
			return '&amp;' . $queryStringItemName . '=' . $shortcodes[$queryStringItemName] . '';
		} else {
			return '';
		}
	}

	private static function get_query_string_true( $queryStringItemName) {
		// todo - do some erro checking here
		return '&amp;' . $queryStringItemName . '=true';
	}

	// [webmap id="a72b0766aea04b48bf7a0e8c27ccc007" extent="-155.6006,6.5161,-42.1338,61.7856"]
	public function webmap_function( $atts) {
		$viewLargerLinkString = '';
		$shortcodes = shortcode_atts( array(
			'id' => 'a72b0766aea04b48bf7a0e8c27ccc007',
			'width' => '100%', // default 100% because most blogs will want this
			'height' => '',
			'extent' => ''
		), $atts );

		$width = self::get_html_attr($shortcodes, 'width');
		$height = self::get_html_attr($shortcodes, 'height');

		$extent = self::get_query_string_item($shortcodes, 'extent'); // will get overridden later if we're using embed.html


		// Find out if we can use embedViewer or if we need to use embed.html
		$alternateUrl = FALSE;
		$baseUrl = 'http://www.arcgis.com/home/webmap/embedViewer.html';
		if($atts !== '') {
			foreach ($atts as $key => $value) {
				if(is_numeric($key)) {
					if($value == 'legend' || $value == 'description' || $value == 'search' || $value == 'basemaps') {
						$baseUrl = 'http://www.arcgis.com/home/webmap/templates/OnePane/basicviewer/embed.html';
						$alternateUrl = TRUE;
					}
				}
			}

			// we now know which url we need to use (embedViewer.html or basicviewer/embed.html)
			// if we are using embed.html, we must rename many of the properties
			if($alternateUrl === TRUE) {
				$shortcodes['gcsextent'] = $shortcodes['extent'];
				unset($shortcodes['extent']);
				$extent = self::get_query_string_item($shortcodes, 'gcsextent');

				foreach ($atts as $key => $value) {
					if ($value == "legend") {
						$atts[$key] = "displaylegend";
					} else if ($value == "description") {
						$atts[$key] = "displaydetails";
					} else if ($value == "search") {
						$atts[$key] = "displaysearch";
					} else if ($value == "basemaps") {
						$atts[$key] = "displaybasemaps";
					}  else if ($value == "zoom") {
						$atts[$key] = "displayslider";
					} else if ($value == "scale") {
						$atts[$key] = "displayscalebar";
					} 

				}
			}

			// Build the querystring
			foreach ($atts as $key => $value) {
				if(is_numeric($key) && $value !== 'view-larger-link') {
					$booleanAttrs .= self::get_query_string_true($value);
				} else if(is_numeric($key) && $value === 'view-larger-link') {
					$viewLargerLinkString = '<br /><small><a href="http://www.arcgis.com/home/webmap/viewer.html?webmap=' . $shortcodes['id'] . '" style="text-align:left" target="_blank">View larger map</a></small>';
				}
			}
		}
		return '<iframe class="webmap-widget-map"' . $width . $height . ' frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' . $baseUrl . '?webmap=' . $shortcodes['id'] . $extent . $booleanAttrs . '"></iframe>' . $viewLargerLinkString;
	}
}
