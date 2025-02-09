<?php
/**
* Plugin Name: Arc - Monetize, cache, and accelerate your website
* Plugin URI: https://wordpress.org/plugins/arc-monetize-cache-and-accelerate
* Description: The world's first Content Delivery Network (CDN) that
*   pays you to use it. To get started: activate the Arc plugin, then go
*   to the Arc Settings page to set up your Arc account.
* Version: 1.1.9
* Author: Arc
* Author URI: https://arc.io/
**/

// Apparently this is good for security
// https://wordpress.stackexchange.com/a/214617
if (!defined( 'ABSPATH')) {
    exit;
}

add_action('wp_head', 'arc_add_widget');
function arc_add_widget() {
    $IS_PROD = (getenv('ARC_ENV') ?: 'production') === 'production';
    $WIDGET_ORIGIN = getenv('WIDGET_ORIGIN') ?: 'https://arc.io';
    $filename = '/arc-widget';
    $propertyId = get_option('arc_property_id', '');

    // https://wordpress.stackexchange.com/a/285644
    $plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
    $plugin_version = $plugin_data['Version'];

    $path = $filename.'#'.$propertyId;
    $query = 'env=wp&wpPluginVersion='.$plugin_version;
    $url = $path.'?'.$query;

    echo '<script async src="'.$url.'"></script>';
}

// The .js extension is omitted b/c the web server swallows the request.
// Without the extension, the request reaches PHP.
add_action('init', 'arc_reverse_proxy');
function arc_reverse_proxy () {
    $IS_PROD = (getenv('ARC_ENV') ?: 'production') === 'production';
    $WIDGET_ORIGIN = 'https://arc.io';
    $method = $_SERVER['REQUEST_METHOD'];
    $path = strtok($_SERVER["REQUEST_URI"], '?');
    $SW_PROXY_PATH = '/arc-sw';
    $WIDGET_PROXY_PATH = '/arc-widget';

    if (in_array($method, ['GET', 'HEAD'])) {
        if ($path === $SW_PROXY_PATH) {
            return $IS_PROD
                ? arc_get_script($WIDGET_ORIGIN.'/arc-sw.js', $method)
                : arc_load_dev_script('/arc-dev-scripts/sw/arc-sw.js');
        } else if ($path === $WIDGET_PROXY_PATH) {
            return $IS_PROD
                ? arc_get_script($WIDGET_ORIGIN.'/widget.js', $method)
                : arc_load_dev_script('/arc-dev-scripts/widget/widget.js');
        }
    }
}

// https://codex.wordpress.org/Transients_API
// Cache reverse proxied scripts for an hour.
// Transients API writes to the internal mysql db
// and thus has all the performance implications thereof
function arc_get_script ($url, $method = 'GET') {
    $response = get_transient($url);
    if ($response === false) {
        $response = wp_remote_get($url);

        if (arc_is_response_error($response)) {
            // Older versions mistakenly stored error responses, delete them now.
            delete_transient($url);
            status_header(400);
            return die();
        } else {
            set_transient($url, $response, 1 * HOUR_IN_SECONDS);
        }
    }

    foreach($response['headers'] as $i => $item) {
        // Wordpress does something funky with compressed responses,
        // don't touch this header.
        if ($i === 'content-encoding') {
			continue;
		} else if ($i === 'content-type') {
            // https://plataoplomo.com.br/arc-sw content type is text/html
            // possibly due to their CDN? Hardcode the content type just
            // to be safe.
            header("content-type: application/javascript");
        } else {
            header("{$i}: {$item}");
        }
    }

    if ($method === 'GET') { // HEAD responses don't have a body.
        echo $response['body'];
    }

    die();
}

// Return true for connection errors and statuses >= 400
function arc_is_response_error ($response) {
    $status = wp_remote_retrieve_response_code($response);
    $isHttpErr = $status >= 400;
    return is_wp_error($response) || $isHttpErr;
}

# In dev, the js files are inserted into the container via Docker volume binds.
function arc_load_dev_script ($path) {
    header('content-type: application/javascript');
    echo file_get_contents(WP_CONTENT_DIR.$path);
    die();
}

// Followed this tutorial: https://www.sitepoint.com/wordpress-settings-api-build-custom-admin-page/
add_action('admin_menu', 'arc_add_admin_menu');
function arc_add_admin_menu () {
    global $arc_settings_page;
    $arc_settings_page = add_menu_page(
        'Arc Settings', 'Arc Settings','manage_options','arc-settings','arc_options_page');
}

add_action('admin_enqueue_scripts', 'arc_admin_enqueue_script');
function arc_admin_enqueue_script ($hook_suffix) {
    $IS_PROD = (getenv('ARC_ENV') ?: 'production') === 'production';
    global $arc_settings_page;
    if ($arc_settings_page === $hook_suffix) {
        wp_enqueue_script('arc_sentry', plugins_url('assets/js/sentry.min.js', __FILE__), [], false, true);
        $vueFilename = $IS_PROD ? 'vue.min.js' : 'vue.dev.js';
        wp_enqueue_script('arc_vue', plugins_url('assets/js/'.$vueFilename, __FILE__), [], false, true);
        wp_enqueue_script('arc_admin_script', plugins_url('assets/js/arc-wp-admin.js', __FILE__), [], false, true);
        wp_enqueue_style('arc_bulma', plugins_url('assets/css/bulma.min.css', __FILE__));
        wp_enqueue_style('arc_admin_style', plugins_url('assets/css/arc-wp-admin.css', __FILE__));

        // Disable wordpress' automatic emoji conversion.
        // https://wordpress.stackexchange.com/a/185578
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
    }
}

function arc_options_page () {
    $PORTAL_VUE_ORIGIN = getenv('PORTAL_VUE_ORIGIN') ?: 'https://portal.arc.io';
    $ACCOUNT_ORIGIN = getenv('ACCOUNT_ORIGIN') ?: 'https://account.arc.io';
    $ARC_ENV = getenv('ARC_ENV') ?: 'production';

    echo '<script>
        const ARC_ENV           = "'.$ARC_ENV.'";
        const PORTAL_VUE_ORIGIN = "'.$PORTAL_VUE_ORIGIN.'";
        const ACCOUNT_ORIGIN    = "'.$ACCOUNT_ORIGIN.'";
        const WP_HOME_URL       = "'.home_url().'";
        const WP_AJAX_URL       = "'.admin_url('admin-ajax.php').'";
        const WP_AJAX_NONCE     = "'.wp_create_nonce('update_arc_user').'";
        const WP_ADMIN_EMAIL    = "'.get_option('admin_email').'";
        const ARC_EMAIL         = "'.get_option('arc_email').'";
        const PROPERTY_ID       = "'.get_option('arc_property_id').'";
    </script>
    <div id="arc-wp-admin-app"></div>';
}

add_filter('plugin_action_links_arc/arc.php', 'arc_settings_link' );
function arc_settings_link ($links) {
    $url = arc_get_settings_page_url();
    $settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';

    array_push($links, $settings_link);
    return $links;
}

add_action('admin_notices', 'arc_setup_account_notice');
function arc_setup_account_notice(){
    global $pagenow;
    if ($pagenow == 'plugins.php' && !get_option('arc_email')) {
        $url = arc_get_settings_page_url();
        $settings_link = "<a href='$url'>" . __( 'here' ) . '</a>';
        $emoji = '<img width="25" style="margin-right: 5px" alt="🎉" src="https://twemoji.maxcdn.com/v/latest/svg/1f389.svg">';
        echo '<div class="notice notice-warning" style="display: flex">
            '.$emoji.'
            <p>
                The Arc plugin requires an Arc account.
                Click '.$settings_link.' to setup your Arc account.
            </p>
        </div>';
    }
}

add_action('wp_ajax_update_arc_user', 'arc_update_arc_user');
function arc_update_arc_user () {
    if (!wp_verify_nonce($_POST['nonce'], 'update_arc_user')) {
        status_header(403);
        die();
    }

    $email = sanitize_email($_POST['email']);
    update_option('arc_email', $email);

    $propertyId = $_POST['propertyId'];
    if (arc_is_property_id($propertyId)) {
        update_option('arc_property_id', $propertyId);
    }

    die();
}

// Redirect to options page after activation.
// https://stackoverflow.com/a/11878359/2498782
add_action('admin_init', 'arc_plugin_redirect');
function arc_plugin_redirect() {
    if (get_option('arc_do_activation_redirect', false)) {
        delete_option('arc_do_activation_redirect');

        if(!isset($_GET['activate-multi'])) {
            wp_redirect(arc_get_settings_page_url());
        }
    }
}


register_activation_hook(__FILE__, 'arc_on_activate');
function arc_on_activate () {
    if(!get_option('arc_property_id')){
        update_option('arc_property_id', arc_create_property_id());
    }
    add_option('arc_do_activation_redirect', true);

    $endpoint = 'wpPluginInstalled';
    arc_record_lifecycle_event($endpoint);
}

register_uninstall_hook(__FILE__, 'arc_on_uninstall');
function arc_on_uninstall () {
    delete_option('arc_property_id');
    delete_option('arc_email');

    $endpoint = 'wpPluginUninstalled';
    arc_record_lifecycle_event($endpoint);
}

function arc_record_lifecycle_event ($endpoint) {
    $url = 'https://portal.arc.io/api/'.$endpoint;

    wp_remote_post($url, [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode([
            'email' => get_option('admin_email'),
            'website' => home_url(),
        ]),
    ]);
}

function arc_get_settings_page_url () {
    $url = esc_url(add_query_arg(
        'page',
        'arc-settings',
        get_admin_url() . 'admin.php'
    ));

    return $url;
}

// https://stackoverflow.com/a/5438778/2498782
function arc_create_property_id () {
    $bs58 = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    $len = 8;
    $seed = str_split($bs58);

    $propertyId = '';
    foreach (array_rand($seed, $len) as $k) {
        $propertyId .= $seed[$k];
    }

    return $propertyId;
}

function arc_is_property_id ($propertyId) {
    $propertyIdRegex = '/^[1-9A-HJ-NP-Za-km-z]{8}$/';
    return preg_match($propertyIdRegex, $propertyId);
}

?>
