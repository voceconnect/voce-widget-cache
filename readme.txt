=== Plugin Name ===
Contributors: johnciacia, markparolisi, voceplatforms
Tags: widget
Requires at least: 3.3
Tested up to: 3.4
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Cache widget output for better performance.

== Description ==

This plugin does not cache widgets automatically! You must define which widgets get cached.

Add a widget to the cache and designate which actions the cache gets flushed on.
`
$widget_cache = Voce_Widget_Cache::GetInstance();
$widget_cache->cache_widget( 'Archive_Links_Widget', array( 'save_post' ) );
`


== Installation ==

1. Upload `voce-widget-cache` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Usage: 

$widget_cache = Voce_Widget_Cache::GetInstance();
$widget_cache->cache_widget( 'Archive_Links_Widget', array( 'save_post' ) );


== Frequently Asked Questions ==

= I installed the plugin but nothing is happening =

The caching is not automatic. Refer to the documentation to see how to implement caching on widgets.


= How can I verify my widgets are being cached? =

You could use a plugin like WPDB Profiling

== Screenshots ==

== Changelog ==

= 1.1 =
Updated documentation.

= 1.0 =
* Initial version.
