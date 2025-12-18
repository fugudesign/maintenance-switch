<?php

/**
 * {{MS_PLUGIN_SLUG}}
 *
 * @author    Fugu <info@fugu.fr>
 * @license   GPL-2.0+
 * @copyright 2015 Fugu
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

// Displaying this page during the maintenance mode
// Sécurité : WordPress functions pas disponibles dans ce contexte
if (isset($_SERVER['SERVER_PROTOCOL'])) {
	// Validation manuelle sécurisée (WordPress non chargé)
	$maintenance_switch_protocol = htmlspecialchars(stripslashes($_SERVER['SERVER_PROTOCOL']), ENT_QUOTES, 'UTF-8'); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- Secure manual validation when WordPress unavailable
	// Validation stricte des valeurs autorisées
	if ($maintenance_switch_protocol !== 'HTTP/1.1' && $maintenance_switch_protocol !== 'HTTP/1.0') {
		$maintenance_switch_protocol = 'HTTP/1.0';
	}
} else {
	$maintenance_switch_protocol = 'HTTP/1.0';
}

// Validation déjà faite ci-dessus

// Return 503 status code?
$maintenance_switch_return503 = '{{MS_RETURN_503}}';

if ($maintenance_switch_return503 == '1') {
	header("$maintenance_switch_protocol 503 Service Unavailable", true, 503);
	header('Retry-After: 600');
}

// Standards headers
header('Content-Type: text/html; charset=utf-8');

$maintenance_switch_theme_file = '{{MS_THEME_FILE}}';
$maintenance_switch_use_theme = '{{MS_USE_THEME_FILE}}';

if ($maintenance_switch_use_theme == '1' && !empty($maintenance_switch_theme_file) && file_exists($maintenance_switch_theme_file) && strpos(realpath($maintenance_switch_theme_file), ABSPATH) === 0) {
	require_once $maintenance_switch_theme_file;
	die();
}

// Get the HTML code from plugin options ?>
{{MS_PAGE_HTML}}

<?php die(); ?>