<?php
/**
 * @package   Responsive_IO
 * @author    14islands <hello@14islands.com>
 * @license   GPL-2.0+
 * @link      http://responsive.io
 * @copyright 2013 14islands
 *
 * @wordpress-plugin
 * Plugin Name:       responsive.io
 * Plugin URI:        http://responsive.io/
 * Description:       This plugin will make sure all of the images in your content comply with the responsive.io script. Responsive.io takes care of delivering the image with the proper dimensions thus greatly speeding up your website.
 * Version:           1.0.0
 * Author:            14islands
 * Author URI:        http://14islands.com
 * Text Domain:       responsive-io-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/14islands/wp-reponsive-io
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
 * @TODO:
 *
 * - `class-plugin-admin.php` is the name of the plugin's admin file
 * - Responsive_IO_Admin is the name of the class defined in
 *   `class-responsive-io-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-responsive-io-admin.php' );
	add_action( 'plugins_loaded', array( 'Responsive_IO_Admin', 'get_instance' ) );

}
