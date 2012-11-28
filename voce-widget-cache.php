<?php
/*
  Plugin Name: Voce Widget Cache
  Plugin URI: http://voceconnect.com
  Description: Serve cached WordPress Widgets.
  Version: 1.0
  Author: John Ciacia, Mark Parolisi
  License: GPL2
*/

/**
 * Cache widget output
 *
 * Usage:
 * $widget_cache = Voce_Widget_Cache::GetInstance();
 * $widget_cache->cache_widget( 'WP_Widget_Links', array( 'load-link-manager.php', 'load-link-add.php', 'load-link.php' ) );
 * $widget_cache->cache_widget( 'Archive_Links_Widget', array( 'save_post' ) );
 *
 */
if(!class_exists('Voce_Widget_Cache')){
	class Voce_Widget_Cache {

		private static $instance;
		private $widget_classes = array();
		public $widget_ids = array();

		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );
		}

		/**
		* Create a singleton instance
		*/
		public static function GetInstance() {
			if( ! isset( self::$instance ) ) {
				$class = __CLASS__;
				self::$instance = new $class;
			}
			return self::$instance;
		}

		/**
		* Set the widget callbacks to custom functions
		*/
		public function init() {
			global $wp_registered_widgets, $wp_registered_widget_updates;

			//change the display callback for widgets to a custom callback
			foreach( $wp_registered_widgets as $id => $widget ) {
				if( in_array( get_class( $wp_registered_widgets[$id]['callback'][0] ), $this->widget_classes ) ) {
					$this->widget_ids[get_class( $wp_registered_widgets[$id]['callback'][0] )][] = $id;
					$wp_registered_widgets[$id]['callback_original'] = $wp_registered_widgets[$id]['callback'];
					$wp_registered_widgets[$id]['callback'] = array( $this, '_display_cb' );
					$wp_registered_widgets[$id]['params'][] = $id;
				}
			}

			//change the update callback for widgets to a custom callback
			foreach( $wp_registered_widget_updates as $id => $widget ) {
				if( in_array( get_class( $wp_registered_widget_updates[$id]['callback'][0] ), $this->widget_classes ) ) {
					$widget_id = $wp_registered_widget_updates[$id]['callback'][0]->id;
					$wp_registered_widget_updates[$id]['callback_original'] = $wp_registered_widget_updates[$id]['callback'];
					$wp_registered_widget_updates[$id]['callback'] = array( $this, '_update_cb' );
					$wp_registered_widget_updates[$id]['params'][] = $id;
					$wp_registered_widget_updates[$id]['params'][] = $widget_id;
				}
			}
		}

		/**
		* @param $widget_class name of widget class you want to cache
		* @param $hooks an array of hooks to clear the widget from the cache
		*/
		public function cache_widget( $widget_class, array $hooks ) {
			$this->widget_classes[] = $widget_class;
			foreach( $hooks as $hook ){
				add_action( "$hook", create_function( '', "Voce_Widget_Cache::GetInstance()->delete_cached_widgets('$widget_class');" ) );
			}
			return $this;
		}

		/**
		* Internal use only - called when widget is updated and used to
		* delete the cache for the widget that was updated.
		*/
		public function _update_cb( $params, $id, $widget_id ) {
			global $wp_registered_widget_updates;

			$callback = $wp_registered_widget_updates[$id]['callback_original'];

			if ( is_callable( $callback ) ) {
				$this->delete( $widget_id );
				call_user_func_array( $callback, func_get_args() );
			}
		}

		/**
		* Internal use only - called when widget is displayed.
		* Cache the output
		*/
		public function _display_cb( $args, $params, $id ) {
			global $wp_registered_widgets;

			$callback = $wp_registered_widgets[$id]['callback_original'];

			if( ! ( $output = get_transient( $id ) ) ) {
				if ( ! is_callable( $callback ) ){
					return;
				}
				ob_start();
				call_user_func_array( $callback, func_get_args() );
				$output = ob_get_clean();
				set_transient( $id, $output );
			}

			echo $output;
		}

		/**
		* Delete all cached widgets of a particular class
		* @param $widget_class a widget class name
		*/
		public function delete_cached_widgets( $widget_class ) {
			foreach( $this->widget_ids[$widget_class] as $widget ){
				$this->delete( $widget );
			}
		}

		/**
		* Delete a single cached widget
		* @param $widget_id a widget id
		*/
		private function delete( $widget_id ) {
			delete_transient( $widget_id );
		}
	}
}