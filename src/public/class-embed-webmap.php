<?php
/**
 * Plugin Name.
 *
 * @package   Embed_Webmap
 * @author    Gavin Rehkemper <gavin@gavinr.com>
 * @license   GPL-2.0+
 * @link      http://gavinr.com/embed-webmap-plugin
 * @copyright 2019 Gavin Rehkemper
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 *
 * @package Embed_Webmap
 * @author  Gavin Rehkemper <gavin@gavinr.com>
 */
class Embed_Webmap {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '2.0.3';

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

		add_shortcode( 'webmap', array( $this, 'webmap_function' ) );
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

	private static function get_html_attr( $shortcodes, $htmlAttrName ) {
		if ( '' !== $shortcodes[$htmlAttrName] ) {
			return ' ' . $htmlAttrName . '="' . $shortcodes[$htmlAttrName] . '"';
		} else {
			return '';
		}
	}

	public function webmap_function( $atts ) {
		$viewLargerLinkString = '';
		$shortcodes = shortcode_atts( array(
			'id' => 'a72b0766aea04b48bf7a0e8c27ccc007',
			'width' => '100%', // default 100% because most blogs will want this
			'height' => '',
			'extent' => '',
			'theme' => 'light',
			'alt_basemap' => '',
			'larger_text' => __( 'View larger map', 'embed-webmap' )
		), $atts );

		$shortcodes = array_map( 'esc_attr', $shortcodes );

		$width = self::get_html_attr( $shortcodes, 'width' );
		$height = self::get_html_attr( $shortcodes, 'height' );

		$baseUrl = 'https://www.arcgis.com/apps/Embed/index.html';

		// defaults:
		$queryString = array(
			'webmap'=> $shortcodes['id'],
			'extent'=> $shortcodes['extent'],
			'theme' => $shortcodes['theme'],
			'zoom'=>'false',
			'scale'=>'false',
			'disable_scroll'=>'false',
			'basemap_gallery'=>'false',
			'basemap_toggle'=>'false',
			'basemaps'=>'false',
			'description'=>'false'
		);

		if ( '' !== $atts ) {
			// override the defaults and add to the querystring if we've added a 'keyword'
			foreach ( $atts as $key => $value ) {
				if ( is_numeric( $key )  && 'view-larger-link' !== $value ) {
					$queryString[$value] = 'true';
				} elseif ( is_numeric( $key ) && 'view-larger-link' === $value ) {
					$viewLargerLinkString = '<br /><small><a href="' . $baseUrl . '?webmap=' . $shortcodes['id'] . '" style="text-align:left" target="_blank">' . $shortcodes['larger_text'] . '</a></small>';
				}

			}
		}

		// if we have basemap toggle, add the alt_basemap
		if ( 'true' == $queryString['basemap_toggle'] ) {
			if ( '' == $shortcodes['alt_basemap'] ) {
				$queryString['alt_basemap'] = 'topo'; // default
			} else {
				$queryString['alt_basemap'] = $shortcodes['alt_basemap'];
			}
		}

		// SPECIAL FIXES ---------------------------------

		// If HOME is selected, ZOOM must also be selected.
		if ( 'true' == $queryString['home'] ) {
			$queryString['zoom'] = 'true';
		}

		// Version 1.0 supported 'basemaps' as an option - This would show the "basemap gallery".
		// Since things have changed to be basemap_toggle or basemap_gallery, lets support that old style.
		if ( 'true' == $queryString['basemaps'] ) {
			$queryString['basemap_gallery'] = 'true';
			$queryString['basemap_toggle'] = 'false';
		}

		// Version 1.0 supported 'description' as an option - This would show the new "details"
		if ( 'true' == $queryString['description'] ) {
			$queryString['details'] = 'true';
		}

		// If basemap_toggle is selected, we should have 'alt_basemap'
		if ( 'true' == $queryString['basemap_toggle'] && ! array_key_exists( 'alt_basemap', $queryString ) ) {
			$queryString['alt_basemap'] = 'topo';
		}
		// END SPECIAL FIXES ---------------------------------

		return '<iframe class="webmap-widget-map"' . $width . $height . ' frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' . $baseUrl . '?' . http_build_query( $queryString ) . '"></iframe>' . $viewLargerLinkString;
	}
}
