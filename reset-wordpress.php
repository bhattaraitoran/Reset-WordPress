<?php
/**
 * Plugin Name:       Reset Wordpress
 * Plugin URI: 		  https://github.com/bhattaraitoran/Reset-Wordpress
 * Description:       Resets WordPress to default installation
 * Version:           1.0.0
 * Author:            Toran Bhattarai
 * Author URI:        https://github.com/bhattaraitoran/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       reset-wordpress
 * Domain Path:       /lang
 */

/*
WP Htaccess File Editor is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Htaccess File Editor is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Regenerate Thumbnails. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

// Restrict direct access 
if ( ! defined( 'ABSPATH' ) ) die( 'Silence is golden!' );

/**
 * Plugin setup and initialization
 */
class Reset_WordPress {

	private static $instance;

	/**
	 * Actions setup
	 */
	public function __construct() { 

		add_action( 'plugins_loaded', array( $this, 'constants' ), 2 );
		add_action( 'plugins_loaded', array( $this, 'locale' ), 3 );
		add_action( 'plugins_loaded', array( $this, 'includes' ), 4 );
		add_action( 'admin_enqueue_scripts', array( $this, 'backend_enqueue' ), 5 );
		add_action( 'admin_menu' , array( $this, 'menu' )  , 6 );
	}


	/**
	 * Define plugin constants
	 */
	function constants() {

		define( 'RW_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'RW_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	/**
	 * Include required files
	 */
	function includes() {

		if ( ! function_exists( 'wp_install' ) ) {

	      require ABSPATH . '/wp-admin/includes/upgrade.php';

	    } 

	    if (!function_exists('get_plugins')) {

	      require_once ABSPATH . 'wp-admin/includes/plugin.php';
	      
	    }

	    require('inc/library/shuttle-export/dumper.php');
		require('inc/class/reset-wordpress-admin-notice.php');
		require('inc/class/reset-wordpress-handle.php');

	}

	/**
	 * String translations
	 */
	function locale() {
		load_plugin_textdomain( 'reset-wordpress', false, 'reset-wordpress/languages' );
	}


	/**
     * loads javscript and css files in admin section.
     */
	function backend_enqueue(){
	     
	     wp_register_style( 'rw-style', RW_URI.'assets/css/style.css','', '1.0.0' , 'all' );
	     wp_enqueue_style( 'rw-style' );

	     wp_register_script( 'rw-script', RW_URI.'assets/js/scripts.js','', '1.0.0' , 'all' );
	     wp_enqueue_script( 'rw-script' );
	 
	     
	    }


	/**
     * Adds menu on wordpress admin panel
     */
	function menu(){
	
	 	add_submenu_page( 'tools.php','Reset WordPress', 'Reset WordPress' , 'activate_plugins', 'reset-wordpress' , function(){ $this->rw_reset_options();});
	 	
	 }


	 /**
	  * rw_reset_options_callback
	  */
	 public function rw_reset_options(){

	 	include( 'inc/reset-form.php' );
	 }

	 
	/**
	 * Returns the instance.
	 */
	public static function get_instance() {

		if ( ! self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	
}
  	

	function Reset_WordPress_plugin_load() {

		return Reset_WordPress::get_instance();
			
	}
	add_action('plugins_loaded', 'Reset_WordPress_plugin_load' , 1 );