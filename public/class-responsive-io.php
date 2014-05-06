<?php
/**
 * Responsive IO.
 *
 * @package   Responsive_IO
 * @author    14islands <hello@14islands.com>
 * @license   GPL-2.0+
 * @link      http://responsive.io
 * @copyright 2013 14islands
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * @package Responsive_IO
 * @author  14islands <hello@14islands.com>
 */
class Responsive_IO {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
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
	protected $plugin_slug = 'responsive-io';

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_filter( 'the_content', array( $this, 'update_images' ), 20 );
		add_filter( 'acf_the_content', array( $this, 'update_images' ), 20 );

		// WIP cropping feature filters
		// add_filter( 'attachment_fields_to_edit', array( $this, 'image_cropping_field' ) );
		// add_filter( 'edit_attachment', array( $this, 'image_cropping_field_save' ) );

	}

	/**
	 * Adds a cropping fields to the media uploader.
	 *
	 * @param  Array $form_fields $form_fields already set to be displayed.
	 * @param  Object $post       $post object containing information such as ID.
	 * @return Array              $form_fields with additional fields
	 */
	public function image_cropping_field( $form_fields, $post ) {

		$field_value = get_post_meta( $post->ID, 'rio-cropping', true );

		$form_fields['rio-image-focus'] = array(
			'label' => 'Cropping',
			'input' => 'button',
			'value' => $field_value ? $field_value : '',
			'helps' => 'If provided, photo credit will be displayed',
		);

		return $form_fields;
	}

	/**
	 * Saves the values of image cropping in the media uploader.
	 *
	 * @param  Object $post      $post object containing some post information.
	 * @param  Array $attachment The $attachment field.
	 * @return [type]             $post object with updated meta.
	 */
	public function image_cropping_field_save( $post, $attachment ) {

		if ( isset( $_REQUEST['attachments'][$attachment_id]['rio-cropping'] ) ) {
      $rio_cropping = $_REQUEST['attachments'][$attachment_id]['rio-cropping'];
      update_post_meta( $attachment_id, 'rio-cropping', $rio_cropping );
    }

		if( isset( $attachment['rio-cropping'] ) )
			update_post_meta( $post['ID'], 'rio-cropping', $attachment['rio-cropping'] );

		return $post;
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 *@return    Plugin slug variable.
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
				}

				restore_current_blog();

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

				}

				restore_current_blog();

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

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if (!wp_script_is($this->plugin_slug . '-plugin-script')) {
			wp_enqueue_script($this->plugin_slug . '-plugin-script', '//src.responsive.io/r.js', false, self::VERSION, true);
			wp_enqueue_script( $this->plugin_slug . '-plugin-fallback-script', plugins_url( 'assets/js/r.js', __FILE__ ), false, self::VERSION, true );
		}
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

	/**
	 * Get's the content that's about to be output
	 * and fixes the images to follow the responsive.io service conventions.
	 * @param  string $content the unmodified html
	 * @return string          the modified html
	 */
	public function update_images($content) {

		// Create a DOMDocument instance
		$dom = new DOMDocument;

		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;

		if (empty($content)) {
			return;
		}

		// Loads our content as HTML
		@$dom->loadHTML( $content );

		// Get all of our img tags
		$images = $dom->getElementsByTagName('img');

		// Loop through all the images in this content
		foreach ($images as $image) {

			// Get some attributes
			$src = $image->getAttribute('src');
			$alt = $image->getAttribute('alt');

			// Only interested in those who have a src set
			if (empty($src)) {
				continue;
			}

			// Create our fallback image before changing this node
			$imageClone = $image->cloneNode();

			// Add the src as a data-src attribute instead
			$image->setAttribute('data-src', $src);

			// Empty the src of this img
			$image->setAttribute('src', '');

			// Now prepare our <noscript> markup
			$noscript = $dom->createElement("noscript");

			// Insert it
			$noscriptNode = $image->parentNode->insertBefore( $noscript, $image );

			// Append the image fallback
			$noscriptNode->appendChild( $imageClone );

		}

		// Return our modified content
		$html = $dom->saveHTML();
		$html = preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $html);

		return $html;

	}

}
