=== Modern Images WP ===
Contributors:  adamsilverstein, google
Tags:          modern images, webp, avif, jpegxl
Tested up to:  6.4
Stable tag:    1.2.0
License:       Apache License 2.0
License URI:   https://www.apache.org/licenses/LICENSE-2.0

Modern images for WordPress.

== Description ==

Specify the default image format used for sub-sized images generated by WordPress.

=== Technical Notes ===

* Requires PHP 5.6+.
* Requires WordPress 5.8+.
* Issues and Pull requests welcome on the GitHub repository: https://github.com/adamsilverstein/wordpress-modern-images.

== Installation ==

1. Install the plugin via the plugin installer, either by searching for it or uploading a .zip file.
2. Activate the plugin.
3. Select an output image format under Settings->Media.

== Screenshots ==

1. Modern image output format options on `Settings` > `Media` admin page.
2. Image format options available for various image types.

== Changelog ==

= 1.2.0 =
- Tested up to 6.4.

= 1.1.0 =
- Correct support for AVIF & JPEGXL with `mime_types` filter.
- Add `wp-env` support. To test the plugin with `wp-env` which currently supports AVIF, use `npm install -g @wordpress/env && wp-env start`.

= 1.0.4 =
- Tested up to 6.1.

= 1.0.3 =
* Check correct image engine for mime type format support when multiple engines are available.

= 1.0.0 =
* Initial plugin release
