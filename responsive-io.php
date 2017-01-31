<?php
/*
@package   Responsive_IO
@author    14islands <hello@14islands.com>
@license   GPL-2.0+
@link      http://responsive.io
@copyright 2013 14islands
@wordpress-plugin
Version:           1.1.9
Plugin Name:       responsive.io
Plugin URI:        http://wordpress.org/plugins/responsiveio/
Description:       Attention: responsive.io has been discontinued and this plugin is no longer maintained. See http://blog.responsive.io/post/156620042296/the-future-of-responsiveio. This plugin optimizes and resizes all images in your content using the responsive.io service.
Author:            14islands
Author URI:        http://14islands.com
Text Domain:       responsive-io-locale
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path:       /languages
GitHub Plugin URI: https://github.com/14islands/wp-reponsive-io
GitHub Branch:     master
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-responsive-io.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * - the name of the class defined in
 *   `class-responsive-io.php`
 */
register_activation_hook( __FILE__, array( 'Responsive_IO', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Responsive_IO', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Responsive_IO', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-responsive-io-admin.php' );
	add_action( 'plugins_loaded', array( 'Responsive_IO_Admin', 'get_instance' ) );

}
