<?php

/**
 * Config file
 *
 * @link       http://www.fugu.fr
 * @since      1.0.0
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/includes
 */


$default_html = <<<EOD
<!DOCTYPE html><html lang="fr-FR">
<head>
	<meta charset="UTF-8">
	<title>My Website</title>
	<style>
		body { font-family: Helvetica, Arial, sans-serif; font-size:16px; color: #000; font-weight:normal; }
		strong { font-weight:bold; }
		#container { width: 600px; padding: 70px 0; border:1px solid #000; text-align:center; position:absolute; left:50%; top:50%; -webkit-transform: translate(-50%, -50%);  -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%); }
		h1 { font-size: 36px; font-weight:normal; color: #000; }
	</style>
</head>
<body class="home">
	<div id="container">
		<h1>Maintenance</h1>
		<p class="maintenance-text"><strong>My Website</strong> is currently under maintenance.<br />Please coming soon!</p>
	</div>
</body>
</html>
EOD;

/**
 * Default value for ms_page_html
 * @since    1.0.0
 */
define( 'MS_DEFAULT_PAGE_HTML', $default_html ); 

/**
 * Default value for ms_allowed_roles option
 * @since    1.0.0
 */
define( 'MS_DEFAULT_ALLOWED_ROLES', 'administrator' );


/**
 * Path of the maintenance.php file.
 * @since    1.0.0
 */
define( 'MS_SLUG', 'maintenance-switch' );

/**
 * Path of the maintenance.php file.
 * @since    1.0.0
 */
define( 'MS_PHP_FILE_USED', WP_CONTENT_DIR . '/maintenance.php' );

/**
 * Path of the maintenance.php template file.
 * @since    1.0.0
 */
define( 'MS_PHP_FILE_TEMPLATE', WP_PLUGIN_DIR . '/maintenance-switch/templates/maintenance.php' );

/**
 * Path of the .maintenance file.
 * @since    1.0.0
 */
define( 'MS_DOT_FILE_USED', ABSPATH . '/.maintenance' );

/**
 * Path of the .maintenance template file.
 * @since    1.0.0
 */
define( 'MS_DOT_FILE_TEMPLATE', WP_PLUGIN_DIR . '/maintenance-switch/templates/.maintenance' );