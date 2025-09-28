<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Security check: only allow admin users
if (!current_user_can('manage_options')) {
    wp_die(__('Insufficient permissions to access this page.'));
}

// Security check: verify nonce
if (!empty($_POST['preview-code'])) {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), 'maintenance_switch_preview')) {
        wp_die(__('Security check failed.'));
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
$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? sanitize_text_field($_SERVER['SERVER_PROTOCOL']) : 'HTTP/1.0';
if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol)
    $protocol = 'HTTP/1.0';
header('Content-Type: text/html; charset=utf-8');

if (!empty($_POST['preview-code'])) {
    echo wp_kses_post(wp_unslash(sanitize_textarea_field($_POST['preview-code'])));
}