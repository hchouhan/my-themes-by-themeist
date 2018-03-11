<?php
/**
 * Plugin Name: My Themes by Themeist
 * Plugin URI: http://themeist.co
 * Description: Themes CPT for theme developers to display their themes on their own website
 * Version: 0.1
 * Author: Harish Chouhan
 * Author URI: http://themeist.co
 */

class Themeist_Themes {

	/**
	 * Sets up the plugin.
	 */
	function __construct() {

		add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );
		add_action( 'plugins_loaded', array( &$this, 'includes' ), 2 );
		add_action( 'plugins_loaded', array( &$this, 'admin' ), 3 );
	}

	/**
	 * Defines constants for the plugin.
	 */
	function constants() {
		define( 'THEMIST_THEMES_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
	}

	/**
	 * Loads files needed across the site.
	 */
	function includes() {

		/* Load includes. */
		require_once( THEMIST_THEMES_DIR . 'includes.php' );

		/* Load taxonomies. */
		require_once( THEMIST_THEMES_DIR . 'taxonomies.php' );

		/* Load post types. */
		require_once( THEMIST_THEMES_DIR . 'post-types.php' );
	}

	/**
	 * Loads admin files.
	 */
	function admin() {

		if ( is_admin() ) {

			/* Load main admin file. */
			require_once( THEMIST_THEMES_DIR . 'admin.php' );

			/* Load meta boxes. */
			//require_once( THEMIST_THEMES_DIR . 'meta-boxes.php' );
		}
	}

}

new Themeist_Themes();

?>