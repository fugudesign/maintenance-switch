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
	$ms_protocol = htmlspecialchars(stripslashes($_SERVER['SERVER_PROTOCOL']), ENT_QUOTES, 'UTF-8'); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- Secure manual validation when WordPress unavailable
	// Validation stricte des valeurs autorisées
	if ($ms_protocol !== 'HTTP/1.1' && $ms_protocol !== 'HTTP/1.0') {
		$ms_protocol = 'HTTP/1.0';
	}
} else {
	$ms_protocol = 'HTTP/1.0';
}

// Validation déjà faite ci-dessus

// Return 503 status code?
$ms_return503 = '{{MS_RETURN_503}}';

if ($ms_return503 == '1') {
	header("$ms_protocol 503 Service Unavailable", true, 503);
	header('Retry-After: 600');
}

// Standards headers
header('Content-Type: text/html; charset=utf-8');

$ms_theme_file = '{{MS_THEME_FILE}}';
$ms_use_theme = '{{MS_USE_THEME_FILE}}';

if ($ms_use_theme == '1' && !empty($ms_theme_file) && file_exists($ms_theme_file) && strpos(realpath($ms_theme_file), ABSPATH) === 0) {
	require_once $ms_theme_file;
	die();
}

// Get the HTML code from plugin options ?>
{{MS_PAGE_HTML}}

<?php die(); ?>