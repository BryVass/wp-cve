<?php

// scoper-autoload.php @generated by PhpScoper

// Backup the autoloaded Composer files
if (isset($GLOBALS['__composer_autoload_files'])) {
    $existingComposerAutoloadFiles = $GLOBALS['__composer_autoload_files'];
}

$loader = require_once __DIR__.'/autoload.php';
// Ensure InstalledVersions is available
$installedVersionsPath = __DIR__.'/composer/InstalledVersions.php';
if (file_exists($installedVersionsPath)) require_once $installedVersionsPath;

// Restore the backup
if (isset($existingComposerAutoloadFiles)) {
    $GLOBALS['__composer_autoload_files'] = $existingComposerAutoloadFiles;
} else {
    unset($GLOBALS['__composer_autoload_files']);
}

// Class aliases. For more information see:
// https://github.com/humbug/php-scoper/blob/master/docs/further-reading.md#class-aliases
if (!function_exists('humbug_phpscoper_expose_class')) {
    function humbug_phpscoper_expose_class($exposed, $prefixed) {
        if (!class_exists($exposed, false) && !interface_exists($exposed, false) && !trait_exists($exposed, false)) {
            spl_autoload_call($prefixed);
        }
    }
}
humbug_phpscoper_expose_class('ComposerAutoloaderInit52e04b087b130fad6aa057801dbdb665', 'Dotdigital_WordPress_Vendor\ComposerAutoloaderInit52e04b087b130fad6aa057801dbdb665');
humbug_phpscoper_expose_class('JsonException', 'Dotdigital_WordPress_Vendor\JsonException');
humbug_phpscoper_expose_class('PhpToken', 'Dotdigital_WordPress_Vendor\PhpToken');
humbug_phpscoper_expose_class('ValueError', 'Dotdigital_WordPress_Vendor\ValueError');
humbug_phpscoper_expose_class('UnhandledMatchError', 'Dotdigital_WordPress_Vendor\UnhandledMatchError');
humbug_phpscoper_expose_class('Stringable', 'Dotdigital_WordPress_Vendor\Stringable');
humbug_phpscoper_expose_class('Attribute', 'Dotdigital_WordPress_Vendor\Attribute');
humbug_phpscoper_expose_class('DM_Widget', 'Dotdigital_WordPress_Vendor\DM_Widget');

// Function aliases. For more information see:
// https://github.com/humbug/php-scoper/blob/master/docs/further-reading.md#function-aliases
if (!function_exists('__')) { function __() { return \Dotdigital_WordPress_Vendor\__(...func_get_args()); } }
if (!function_exists('activate')) { function activate() { return \Dotdigital_WordPress_Vendor\activate(...func_get_args()); } }
if (!function_exists('add_filter')) { function add_filter() { return \Dotdigital_WordPress_Vendor\add_filter(...func_get_args()); } }
if (!function_exists('app')) { function app() { return \Dotdigital_WordPress_Vendor\app(...func_get_args()); } }
if (!function_exists('apply_filters')) { function apply_filters() { return \Dotdigital_WordPress_Vendor\apply_filters(...func_get_args()); } }
if (!function_exists('array_key_first')) { function array_key_first() { return \Dotdigital_WordPress_Vendor\array_key_first(...func_get_args()); } }
if (!function_exists('array_key_last')) { function array_key_last() { return \Dotdigital_WordPress_Vendor\array_key_last(...func_get_args()); } }
if (!function_exists('delete_option')) { function delete_option() { return \Dotdigital_WordPress_Vendor\delete_option(...func_get_args()); } }
if (!function_exists('do_action')) { function do_action() { return \Dotdigital_WordPress_Vendor\do_action(...func_get_args()); } }
if (!function_exists('do_settings_sections')) { function do_settings_sections() { return \Dotdigital_WordPress_Vendor\do_settings_sections(...func_get_args()); } }
if (!function_exists('dotdigital_wordpress_uninstall')) { function dotdigital_wordpress_uninstall() { return \Dotdigital_WordPress_Vendor\dotdigital_wordpress_uninstall(...func_get_args()); } }
if (!function_exists('esc_attr')) { function esc_attr() { return \Dotdigital_WordPress_Vendor\esc_attr(...func_get_args()); } }
if (!function_exists('esc_html')) { function esc_html() { return \Dotdigital_WordPress_Vendor\esc_html(...func_get_args()); } }
if (!function_exists('esc_url')) { function esc_url() { return \Dotdigital_WordPress_Vendor\esc_url(...func_get_args()); } }
if (!function_exists('fdiv')) { function fdiv() { return \Dotdigital_WordPress_Vendor\fdiv(...func_get_args()); } }
if (!function_exists('getOpenCollectiveSponsors')) { function getOpenCollectiveSponsors() { return \Dotdigital_WordPress_Vendor\getOpenCollectiveSponsors(...func_get_args()); } }
if (!function_exists('get_debug_type')) { function get_debug_type() { return \Dotdigital_WordPress_Vendor\get_debug_type(...func_get_args()); } }
if (!function_exists('get_option')) { function get_option() { return \Dotdigital_WordPress_Vendor\get_option(...func_get_args()); } }
if (!function_exists('get_resource_id')) { function get_resource_id() { return \Dotdigital_WordPress_Vendor\get_resource_id(...func_get_args()); } }
if (!function_exists('getallheaders')) { function getallheaders() { return \Dotdigital_WordPress_Vendor\getallheaders(...func_get_args()); } }
if (!function_exists('hrtime')) { function hrtime() { return \Dotdigital_WordPress_Vendor\hrtime(...func_get_args()); } }
if (!function_exists('is_countable')) { function is_countable() { return \Dotdigital_WordPress_Vendor\is_countable(...func_get_args()); } }
if (!function_exists('mb_check_encoding')) { function mb_check_encoding() { return \Dotdigital_WordPress_Vendor\mb_check_encoding(...func_get_args()); } }
if (!function_exists('mb_chr')) { function mb_chr() { return \Dotdigital_WordPress_Vendor\mb_chr(...func_get_args()); } }
if (!function_exists('mb_convert_case')) { function mb_convert_case() { return \Dotdigital_WordPress_Vendor\mb_convert_case(...func_get_args()); } }
if (!function_exists('mb_convert_encoding')) { function mb_convert_encoding() { return \Dotdigital_WordPress_Vendor\mb_convert_encoding(...func_get_args()); } }
if (!function_exists('mb_convert_variables')) { function mb_convert_variables() { return \Dotdigital_WordPress_Vendor\mb_convert_variables(...func_get_args()); } }
if (!function_exists('mb_decode_mimeheader')) { function mb_decode_mimeheader() { return \Dotdigital_WordPress_Vendor\mb_decode_mimeheader(...func_get_args()); } }
if (!function_exists('mb_decode_numericentity')) { function mb_decode_numericentity() { return \Dotdigital_WordPress_Vendor\mb_decode_numericentity(...func_get_args()); } }
if (!function_exists('mb_detect_encoding')) { function mb_detect_encoding() { return \Dotdigital_WordPress_Vendor\mb_detect_encoding(...func_get_args()); } }
if (!function_exists('mb_detect_order')) { function mb_detect_order() { return \Dotdigital_WordPress_Vendor\mb_detect_order(...func_get_args()); } }
if (!function_exists('mb_encode_mimeheader')) { function mb_encode_mimeheader() { return \Dotdigital_WordPress_Vendor\mb_encode_mimeheader(...func_get_args()); } }
if (!function_exists('mb_encode_numericentity')) { function mb_encode_numericentity() { return \Dotdigital_WordPress_Vendor\mb_encode_numericentity(...func_get_args()); } }
if (!function_exists('mb_encoding_aliases')) { function mb_encoding_aliases() { return \Dotdigital_WordPress_Vendor\mb_encoding_aliases(...func_get_args()); } }
if (!function_exists('mb_get_info')) { function mb_get_info() { return \Dotdigital_WordPress_Vendor\mb_get_info(...func_get_args()); } }
if (!function_exists('mb_http_input')) { function mb_http_input() { return \Dotdigital_WordPress_Vendor\mb_http_input(...func_get_args()); } }
if (!function_exists('mb_http_output')) { function mb_http_output() { return \Dotdigital_WordPress_Vendor\mb_http_output(...func_get_args()); } }
if (!function_exists('mb_internal_encoding')) { function mb_internal_encoding() { return \Dotdigital_WordPress_Vendor\mb_internal_encoding(...func_get_args()); } }
if (!function_exists('mb_language')) { function mb_language() { return \Dotdigital_WordPress_Vendor\mb_language(...func_get_args()); } }
if (!function_exists('mb_list_encodings')) { function mb_list_encodings() { return \Dotdigital_WordPress_Vendor\mb_list_encodings(...func_get_args()); } }
if (!function_exists('mb_ord')) { function mb_ord() { return \Dotdigital_WordPress_Vendor\mb_ord(...func_get_args()); } }
if (!function_exists('mb_output_handler')) { function mb_output_handler() { return \Dotdigital_WordPress_Vendor\mb_output_handler(...func_get_args()); } }
if (!function_exists('mb_parse_str')) { function mb_parse_str() { return \Dotdigital_WordPress_Vendor\mb_parse_str(...func_get_args()); } }
if (!function_exists('mb_scrub')) { function mb_scrub() { return \Dotdigital_WordPress_Vendor\mb_scrub(...func_get_args()); } }
if (!function_exists('mb_str_pad')) { function mb_str_pad() { return \Dotdigital_WordPress_Vendor\mb_str_pad(...func_get_args()); } }
if (!function_exists('mb_str_split')) { function mb_str_split() { return \Dotdigital_WordPress_Vendor\mb_str_split(...func_get_args()); } }
if (!function_exists('mb_stripos')) { function mb_stripos() { return \Dotdigital_WordPress_Vendor\mb_stripos(...func_get_args()); } }
if (!function_exists('mb_stristr')) { function mb_stristr() { return \Dotdigital_WordPress_Vendor\mb_stristr(...func_get_args()); } }
if (!function_exists('mb_strlen')) { function mb_strlen() { return \Dotdigital_WordPress_Vendor\mb_strlen(...func_get_args()); } }
if (!function_exists('mb_strpos')) { function mb_strpos() { return \Dotdigital_WordPress_Vendor\mb_strpos(...func_get_args()); } }
if (!function_exists('mb_strrchr')) { function mb_strrchr() { return \Dotdigital_WordPress_Vendor\mb_strrchr(...func_get_args()); } }
if (!function_exists('mb_strrichr')) { function mb_strrichr() { return \Dotdigital_WordPress_Vendor\mb_strrichr(...func_get_args()); } }
if (!function_exists('mb_strripos')) { function mb_strripos() { return \Dotdigital_WordPress_Vendor\mb_strripos(...func_get_args()); } }
if (!function_exists('mb_strrpos')) { function mb_strrpos() { return \Dotdigital_WordPress_Vendor\mb_strrpos(...func_get_args()); } }
if (!function_exists('mb_strstr')) { function mb_strstr() { return \Dotdigital_WordPress_Vendor\mb_strstr(...func_get_args()); } }
if (!function_exists('mb_strtolower')) { function mb_strtolower() { return \Dotdigital_WordPress_Vendor\mb_strtolower(...func_get_args()); } }
if (!function_exists('mb_strtoupper')) { function mb_strtoupper() { return \Dotdigital_WordPress_Vendor\mb_strtoupper(...func_get_args()); } }
if (!function_exists('mb_strwidth')) { function mb_strwidth() { return \Dotdigital_WordPress_Vendor\mb_strwidth(...func_get_args()); } }
if (!function_exists('mb_substitute_character')) { function mb_substitute_character() { return \Dotdigital_WordPress_Vendor\mb_substitute_character(...func_get_args()); } }
if (!function_exists('mb_substr')) { function mb_substr() { return \Dotdigital_WordPress_Vendor\mb_substr(...func_get_args()); } }
if (!function_exists('mb_substr_count')) { function mb_substr_count() { return \Dotdigital_WordPress_Vendor\mb_substr_count(...func_get_args()); } }
if (!function_exists('plugin_dir_path')) { function plugin_dir_path() { return \Dotdigital_WordPress_Vendor\plugin_dir_path(...func_get_args()); } }
if (!function_exists('plugins_url')) { function plugins_url() { return \Dotdigital_WordPress_Vendor\plugins_url(...func_get_args()); } }
if (!function_exists('preg_last_error_msg')) { function preg_last_error_msg() { return \Dotdigital_WordPress_Vendor\preg_last_error_msg(...func_get_args()); } }
if (!function_exists('register_activation_hook')) { function register_activation_hook() { return \Dotdigital_WordPress_Vendor\register_activation_hook(...func_get_args()); } }
if (!function_exists('rest_url')) { function rest_url() { return \Dotdigital_WordPress_Vendor\rest_url(...func_get_args()); } }
if (!function_exists('run_dotdigital_wordpress')) { function run_dotdigital_wordpress() { return \Dotdigital_WordPress_Vendor\run_dotdigital_wordpress(...func_get_args()); } }
if (!function_exists('selected')) { function selected() { return \Dotdigital_WordPress_Vendor\selected(...func_get_args()); } }
if (!function_exists('settings_errors')) { function settings_errors() { return \Dotdigital_WordPress_Vendor\settings_errors(...func_get_args()); } }
if (!function_exists('settings_fields')) { function settings_fields() { return \Dotdigital_WordPress_Vendor\settings_fields(...func_get_args()); } }
if (!function_exists('str_contains')) { function str_contains() { return \Dotdigital_WordPress_Vendor\str_contains(...func_get_args()); } }
if (!function_exists('str_ends_with')) { function str_ends_with() { return \Dotdigital_WordPress_Vendor\str_ends_with(...func_get_args()); } }
if (!function_exists('str_starts_with')) { function str_starts_with() { return \Dotdigital_WordPress_Vendor\str_starts_with(...func_get_args()); } }
if (!function_exists('submit_button')) { function submit_button() { return \Dotdigital_WordPress_Vendor\submit_button(...func_get_args()); } }
if (!function_exists('the_widget')) { function the_widget() { return \Dotdigital_WordPress_Vendor\the_widget(...func_get_args()); } }
if (!function_exists('trigger_deprecation')) { function trigger_deprecation() { return \Dotdigital_WordPress_Vendor\trigger_deprecation(...func_get_args()); } }

return $loader;
