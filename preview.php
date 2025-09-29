<?php

// Load WordPress if not already loaded
if (!defined('WPINC')) {
    // Try to find and load WordPress
    $wp_load_paths = [
        '../../../wp-load.php',      // Standard plugin path
        '../../../../wp-load.php',   // In case of subdirectories
        '../../../../../wp-load.php' // Deep subdirectories
    ];
    
    $wp_loaded = false;
    foreach ($wp_load_paths as $path) {
        if (file_exists($path)) {
            require_once($path);
            $wp_loaded = true;
            break;
        }
    }
    
    // If WordPress couldn't be loaded, we can't do security checks
    if (!$wp_loaded) {
        // Fallback for direct access - basic security
        if (!isset($_POST['preview-code'])) {
            die('Direct access not allowed');
        }
    }
}

// Security checks (only if WordPress is loaded)
if (defined('WPINC')) {
    // Security check: only allow admin users
    if (function_exists('current_user_can') && !current_user_can('manage_options')) {
        wp_die(esc_html(__('Insufficient permissions to access this page.', 'maintenance-switch')));
    }

    // Security check: verify nonce
    if (!empty($_POST['preview-code'])) {
        if (function_exists('wp_verify_nonce') && (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'maintenance_switch_preview'))) {
            wp_die(esc_html(__('Security check failed.', 'maintenance-switch')));
        }
    }
}

/**
 * Maintenance Switch
 *
 * @author    Fugu <info@fugu.fr>
 * @license   GPL-2.0+
 * @copyright 2015 Fugu
 * @since      1.2.0
 */

// Displaying this page during the maintenance mode
if (function_exists('sanitize_text_field') && function_exists('wp_unslash')) {
    $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? sanitize_text_field(wp_unslash($_SERVER['SERVER_PROTOCOL'])) : 'HTTP/1.0';
} else {
    // Fallback when WordPress functions not available
    $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? htmlspecialchars($_SERVER['SERVER_PROTOCOL'], ENT_QUOTES, 'UTF-8') : 'HTTP/1.0';
}

if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol)
    $protocol = 'HTTP/1.0';
header('Content-Type: text/html; charset=utf-8');

if (!empty($_POST['preview-code'])) {
    // WordPress 3-layer security approach: Validation → Sanitization → Escaping
    
    // 1. VALIDATION: Verify the data type and presence
    if (!is_string($_POST['preview-code'])) {
        if (function_exists('wp_die')) {
            wp_die(esc_html(__('Invalid data format provided.', 'maintenance-switch')));
        } else {
            die('Invalid data format provided.');
        }
    }
    
    // 2. SANITIZATION: Clean the input using WordPress standards
    if (function_exists('wp_unslash') && function_exists('wp_kses_post')) {
        // WordPress is loaded - use official sanitization
        $preview_html = wp_kses_post(wp_unslash($_POST['preview-code']));
    } else {
        // Fallback when WordPress isn't loaded - strict text-only approach
        $preview_html = htmlspecialchars(stripslashes($_POST['preview-code']), ENT_QUOTES, 'UTF-8');
    }
    
    // 3. ESCAPING: Output is already escaped by wp_kses_post or htmlspecialchars above
    // wp_kses_post() already escapes the output safely
    echo $preview_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}