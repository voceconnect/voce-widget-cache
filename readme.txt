=== Plugin Name ===
Contributors: johnciacia, markparolisi, voceplatforms
Tags: widget
Requires at least: 3.3
Tested up to: 3.4
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Cache widget output.

== Description ==

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

== Screenshots ==

== Changelog ==

= 1.0 =
* Initial version.
