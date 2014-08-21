=== Embed Webmap ===
Contributors: gavinr
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=gavreh%40gmail%2ecom&lc=US&item_name=Gavin%20Rehkemper&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest
Tags: maps, gis, arcgis, webmap
Requires at least: 3.5.1
Tested up to: 3.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed a public webmap from ArcGIS Online into WordPress with a shortcode.

== Description ==

Easily and quickly embed ArcGIS Online Webmaps into WordPress!

*Usage*

Get your webmap ID (see screenshots for help) of a public webmap in ArcGIS Online, then in any page or post in WordPress include this shortcode:

	[webmap id="52475e6edb18471780858627b40460c2"]

... replacing the ID section with the webmap ID that you got from your map.

You can add many different options to the shortcode. See "Other Notes" above for details.

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'embed-webmap'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `embed-webmap.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `embed-webmap.zip`
2. Extract the `embed-webmap` directory to your computer
3. Upload the `embed-webmap` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==

= What is Arcgis.com and how do I create a webmap? =

Go to http://www.arcgis.com. If you have an account, login. If not, sign up for an account. Then create a new map, add some content, save, then share the map publicly. Then use the webmap ID (see the "Screenshots" page for help) to embed the map in WordPress in the format `[webmap id="MyWebmapId"]`


= Does my webmap need to be publicly shared? =

Yes. Share your webmap with the public, copy the webmap ID (from the URL bar when you're on the ArcGIS Online map page) and paste it into the shortcode!

= What options are available in the shortcode? =

Height, width, zoom, scale, and many more! Please see the "Shortcode Options" page for more details.

= Feedback, Problems, Questions? =

File issues on the github page: https://github.com/gavreh/embed-webmap. Also, you can find more resources at http://www.gavinr.com/embed-webmap-plugin.

== Screenshots ==

1. Get webmap ID from Arcgis.com details page.
2. Or, get webmap ID from Arcgis.com map page.
3. Then, embed the ID in your WordPress page or post using the shortcode.
4. Your map appears!
5. The shortcode has many options, including legend and zoom.


== Changelog ==

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.0 =
First stable release

== Shortcode Options ==

* **id** - The webmap ID of the map you wish to embed. Get this from the URL bar on arcgis.com. See the "Screenshots" page for help. Example: [webmap id="52475e6edb18471780858627b40460c2"]
* **extent** - in the "shortened" form. Use http://psstl.esri.com/apps/extenthelper/ for help. Example: [webmap extent="-159.3635,7.093,-45.8967,63.7401"]
* **height** - specify the height, in pixels. Example: [webmap height="600"]
* **width** - specify the width, in pixels. Example: [webmap width="230"]
* **zoom** - Include zoom buttons. Example: [webmap id="52475e6edb18471780858627b40460c2" zoom]
* **home** - Include a home button. If this is included, the zoom buttons will automatically be included. Example: [webmap id="52475e6edb18471780858627b40460c2" home]
* **scale** - Include a scale bar. Example: [webmap scale id="52475e6edb18471780858627b40460c2"]
* **legend** - Include a legend button. Example: [webmap id="52475e6edb18471780858627b40460c2" legend]
* **description** - display a details button on the map. Example: [webmap  description]
* **search** - Include a location search textbox. Example: [webmap id="52475e6edb18471780858627b40460c2" search]
* **basemaps** - Include a basemaps switch button/menu. Example: [webmap id="52475e6edb18471780858627b40460c2" basemaps]
* **view-larger-link** - Include a link below the map to view the map in a larger window. Example: [webmap id="52475e6edb18471780858627b40460c2" view-larger-link]