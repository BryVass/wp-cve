<?php
/**
 * Plugin Name: WebP Uploads
 * Plugin URI: https://github.com/WordPress/performance/tree/trunk/modules/images/webp-uploads
 * Description: Creates WebP versions for new JPEG image uploads if supported by the server.
 * Requires at least: 6.3
 * Requires PHP: 7.0
 * Version: 1.0.5
 * Author: WordPress Performance Team
 * Author URI: https://make.wordpress.org/performance/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: webp-uploads
 *
 * @package webp-uploads
 */

// Define the constant.
if ( defined( 'WEBP_UPLOADS_VERSION' ) ) {
	return;
}

define( 'WEBP_UPLOADS_VERSION', '1.0.5' );

// Do not load the code if it is already loaded through another means.
if ( function_exists( 'webp_uploads_create_sources_property' ) ) {
	return;
}

// Do not load the code and show an admin notice instead if conditions are not met.
if ( ! require __DIR__ . '/can-load.php' ) {
	add_action(
		'admin_notices',
		static function () {
			printf(
				'<div class="notice notice-error"><p>%s</p></div>',
				esc_html__( 'The WebP Uploads feature cannot be loaded from within the plugin since it is already merged into WordPress core.', 'webp-uploads' )
			);
		}
	);
	return;
}

require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/rest-api.php';
require_once __DIR__ . '/image-edit.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/hooks.php';
