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
        wp_die(__('Insufficient permissions to access this page.'));
    }

    // Security check: verify nonce
    if (!empty($_POST['preview-code'])) {
        if (function_exists('wp_verify_nonce') && (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), 'maintenance_switch_preview'))) {
            wp_die(__('Security check failed.'));
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
if (function_exists('sanitize_text_field')) {
    $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? sanitize_text_field($_SERVER['SERVER_PROTOCOL']) : 'HTTP/1.0';
} else {
    $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? strip_tags($_SERVER['SERVER_PROTOCOL']) : 'HTTP/1.0';
}

if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol)
    $protocol = 'HTTP/1.0';
header('Content-Type: text/html; charset=utf-8');

if (!empty($_POST['preview-code'])) {
    if (function_exists('wp_kses_post')) {
        echo wp_kses_post(wp_unslash(sanitize_textarea_field($_POST['preview-code'])));
    } else {
        // Fallback: output HTML directly for preview (admin-only context)
        // Clean the input but preserve HTML structure
        $html = stripslashes($_POST['preview-code']);
        
        // Basic security: remove dangerous tags and attributes
        $dangerous_tags = ['script', 'iframe', 'object', 'embed', 'form', 'input', 'textarea'];
        foreach ($dangerous_tags as $tag) {
            $html = preg_replace('/<\s*' . $tag . '[^>]*>.*?<\s*\/\s*' . $tag . '\s*>/is', '', $html);
            $html = preg_replace('/<\s*' . $tag . '[^>]*\/?>/is', '', $html);
        }
        
        // Remove dangerous attributes
        $html = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/javascript\s*:/i', '', $html);
        
        echo $html;
    }
}