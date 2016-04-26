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


/**
 * Path of the maintenance.php file.
 * @since    1.0.0
 */
define( 'MS_SLUG', 'maintenance-switch' );

/**
 * Default value for ms_page_html
 * @since    1.0.0
 */
$default_html = '
<!DOCTYPE html><html lang="fr-FR">
<head>
	<meta charset="UTF-8">
	<title>' . get_bloginfo( 'sitename' ) . '</title>
	<style>
		body { font-family: Helvetica, Arial, sans-serif; font-size:16px; color: #000; font-weight:normal; }
		#container { width: 600px; padding: 70px 0; text-align:center; position:absolute; left:50%; top:50%; -webkit-transform: translate(-50%, -50%);  -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%); }
		h1 { font-size: 36px; font-weight:normal; }
	</style>
</head>
<body class="home">
	<div id="container">
		<h1>' . get_bloginfo( 'sitename' ) . '</h1>
		<p>
			' . __( 'In a permanent effort to improve our services, we currently are performing upgrades on our website.', MS_SLUG ) . '<br />
			' . __( 'We apologize for the inconvenience, but we will be pleased to see you back in a very few minutes.', MS_SLUG ) . '
		</p>
		<p>' . __( 'The maintenance team.', MS_SLUG ) . '</p>
	</div>
</body>
</html>';

/**
 * Default settings values
 * @since    1.3.0
 */
define( 'MS_DEFAULT_SETTINGS', json_encode(array(
	
	'ms_page_html' 		=> $default_html,
	'ms_switch_roles' 	=> array( 'administrator' ),
	'ms_allowed_roles' 	=> array( 'administrator' ),
	'ms_allowed_ips' 	=> '',
	'ms_use_theme'		=> 0
)));

/**
 * Default value for maintenance_switch_status option
 * @since    1.1.1
 */
define( 'MS_DEFAULT_STATUS', 0 );

/**
 * Path of the maintenance.php template file.
 * @since    1.0.0
 */
define( 'MS_PHP_FILE_TEMPLATE', WP_PLUGIN_DIR . '/maintenance-switch/templates/maintenance.php' );

/**
 * Path of the maintenance.php file.
 * @since    1.0.0
 */
define( 'MS_PHP_FILE_ACTIVE', WP_CONTENT_DIR . '/maintenance.php' );

/**
 * Path of the .maintenance template file.
 * @since    1.0.0
 */
define( 'MS_DOT_FILE_TEMPLATE', WP_PLUGIN_DIR . '/maintenance-switch/templates/.maintenance' );

/**
 * Path of the .maintenance file.
 * @since    1.0.0
 */
define( 'MS_DOT_FILE_ACTIVE', ABSPATH . '/.maintenance' );

/**
 * Path of the maintenance.php file in theme.
 * @since    1.1.1
 */
define( 'MS_THEME_FILENAME', 'maintenance.php' );

