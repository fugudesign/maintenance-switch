<?php
/**
 * WordPress functions stub pour éliminer les erreurs PHP
 * Ce fichier ne sera jamais exécuté, il sert uniquement à informer l'IDE
 * des fonctions WordPress disponibles avec les bons types de retour
 */

// Core WordPress functions
function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all'): void {}
function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false): void {}
function wp_enqueue_code_editor($args = array()): void {}
function wp_localize_script($handle, $object_name, $l10n): bool { return true; }
function wp_script_is($handle, $list = 'enqueued'): bool { return true; }
// Security and nonce functions
function wp_create_nonce($action = -1): string { return 'nonce_string'; }
function wp_nonce_field($action = -1, $name = "_wpnonce", $referer = true, $echo = true): string { return 'nonce_field'; }
function wp_verify_nonce($nonce, $action = -1): bool { return true; }
function current_user_can($capability, ...$args): bool { return true; }

// AJAX and response functions
function wp_send_json($response): void {}
function wp_send_json_error($data = null): void {}
function wp_die($message = '', $title = '', $args = array()): void {}

// URL and site functions  
function plugin_dir_url($file): string { return 'https://example.com/wp-content/plugins/'; }
function plugin_dir_path($file): string { return '/path/to/plugin/'; }
function plugin_basename($file): string { return 'plugin-folder/plugin-file.php'; }
function plugins_url($path = '', $plugin = ''): string { return 'https://example.com/wp-content/plugins/' . $path; }
function admin_url($path = '', $scheme = 'admin'): string { return 'https://example.com/wp-admin/' . $path; }
function get_site_url($blog_id = null, $path = '', $scheme = null): string { return 'https://example.com'; }
function wp_login_url($redirect = ''): string { return 'https://example.com/wp-login.php'; }
function get_bloginfo($show = '', $filter = 'raw'): string { return 'Site Title'; }

// Admin bar functions
function is_admin_bar_showing(): bool { return true; }

// Sanitization functions
function sanitize_text_field($str): string { return (string)$str; }
function sanitize_key($key): string { return (string)$key; }
function sanitize_textarea_field($str): string { return (string)$str; }
function esc_html($text): string { return (string)$text; }
function esc_url($url, $protocols = null, $_context = 'display'): string { return (string)$url; }
function esc_js($text): string { return (string)$text; }
function esc_textarea($text): string { return (string)$text; }
function wp_unslash($value) { return $value; }
function wp_kses_post($data): string { return (string)$data; }

// Translation functions
function __($text, $domain = 'default'): string { return (string)$text; }
function _e($text, $domain = 'default'): void {}

// Admin functions
function add_options_page($page_title, $menu_title, $capability, $menu_slug, $function = ''): string { return 'page_hook'; }
function get_admin_page_title(): string { return 'Admin Page Title'; }
function submit_button($text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null): void {}

/**
 * @return object{base: string}
 */
function get_current_screen() { 
    return (object)['base' => 'settings_page_maintenance-switch']; 
}

// Settings API
function register_setting($option_group, $option_name, $args = ''): void {}
function add_settings_section($id, $title, $callback, $page): void {}
function add_settings_field($id, $title, $callback, $page, $section = 'default', $args = array()): void {}
function settings_fields($option_group): void {}
function do_settings_fields($page, $section): void {}

// Hook and plugin functions  
function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1): bool { return true; }
function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1): bool { return true; }
function wp_redirect($location, $status = 302): bool { return true; }
function register_activation_hook($file, $function): void {}
function register_deactivation_hook($file, $function): void {}

// Internationalization functions
function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = false): bool { return true; }

// Option functions
function get_option($option, $default = false) { return $default; }
function update_option($option, $value, $autoload = null): bool { return true; }
function delete_option($option): bool { return true; }

// Theme functions
/**
 * @return object{name: string, version: string}
 */
function wp_get_theme($stylesheet = null, $theme_root = null) {
    return (object)['name' => 'Theme Name', 'version' => '1.0.0'];
}

// Utility functions
function wp_parse_args($args, $defaults = ''): array { return is_array($args) ? array_merge((array)$defaults, $args) : (array)$defaults; }
function wp_specialchars_decode($string, $quote_style = ENT_NOQUOTES): string { return (string)$string; }

// Classes WordPress
class WP_User_Query {
    public function __construct($args = array()) {}
    public function get_results(): array { return array(); }
}

// Other common WordPress globals and constants
if (!defined('ABSPATH')) define('ABSPATH', __DIR__ . '/');
if (!defined('WPINC')) define('WPINC', 'wp-includes');
if (!defined('ENT_QUOTES')) define('ENT_QUOTES', 3);
if (!defined('ENT_NOQUOTES')) define('ENT_NOQUOTES', 0);
if (!defined('WP_PLUGIN_DIR')) define('WP_PLUGIN_DIR', __DIR__ . '/wp-content/plugins');
if (!defined('WP_CONTENT_DIR')) define('WP_CONTENT_DIR', __DIR__ . '/wp-content');