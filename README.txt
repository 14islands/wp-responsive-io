=== responsive-io ===
Contributors: 14islands
Tags: responsive, images, rwd, responsive web design, responsive images
Requires at least: 3.5.1
Tested up to: 3.9
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin optimizes and resizes all images in your content using the responsive.io service. Sign up for a free 30 day trial at http://responsive.io.

== Description ==

Responsive.io is a service that optimizes and delivers perfect images for every screen.

We automatically pull images from your website, resize and optimize them for your visitors to speed up load time.

The plugin will make sure your tags are ready and that our javascript is loaded. All that unobtrusively without changing the content in your database.

Features:

- Images are automatically resized to fit any device.
- Our global CDN delivers your images in milliseconds.
- Retina support. High quality images for high density screens.
- We use WebP, progressive JPG and clever optimizations to make your images smaller.
- Our servers cache your images so they are ready to be served in all sizes.
- No watermarks, no user-agent sniffing, no cookies, no extra HTTP requests. No bullshit.

Register your domain for a free trial at http://responsive.io.

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'responsive-io'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `wp-responsive-io.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `wp-responsive-io.zip`
2. Extract the `responsive-io` directory to your computer
3. Upload the `responsive-io` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==

= How does it work? =

It relies on Wordpress "the_content" filter to change the images before handing over the output to the browser.

== Changelog ==

= 1.1.2 =
* Bug fix: removing acf filter to avoid charset conflicts.

= 1.1.1 =
* Adding fallback script check. The fallback script will added to the queue and load in case the CDN fails.

= 1.0.5 =
* Bug fix version. Making sure the scripts loads at the footer.

= 1.0.4 =
* Bug fix version. Lower the priority to further avoid other filters conflicts.

= 1.0.0 =
* Initial version. Changes images and check for [caption] to avoid conflicts.

== Upgrade Notice ==

* None yet.

== Updates ==

This plugin supports the [GitHub Updater](https://github.com/afragen/github-updater) plugin, so if you install that, this plugin becomes automatically updateable direct from GitHub. Any submission to WP.org repo will make this redundant.
