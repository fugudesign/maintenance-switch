<?php

/**
 * {{MS_PLUGIN_SLUG}}
 *
 * @author    Fugu <info@fugu.fr>
 * @license   GPL-2.0+
 * @copyright 2015 Fugu
 */

// Displaying this page during the maintenance mode
$protocol = $_SERVER["SERVER_PROTOCOL"];

if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol)
	$protocol = 'HTTP/1.0';

// Return 503 status code?
$return503 = '{{MS_RETURN_503}}';

if ($return503 == '1') {
	header("$protocol 503 Service Unavailable", true, 503);
	header('Retry-After: 600');
}

// Standards headers
header('Content-Type: text/html; charset=utf-8');

$theme_file = '{{MS_THEME_FILE}}';
$use_theme = '{{MS_USE_THEME_FILE}}';

if ($use_theme == '1' && file_exists($theme_file)) {
	require_once $theme_file;
	die();
}

// Get the HTML code from plugin options ?>
{{MS_PAGE_HTML}}

<?php die(); ?>