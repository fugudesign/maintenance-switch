<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.fugu.fr
 * @since      1.0.0
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/includes
 * @author     Fugu <info@fugu.fr>
 */
class Maintenance_Switch_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		$plugin = new Maintenance_Switch();
		
		if ( ! get_option( 'ms_page_html') ) {
			add_option( 'ms_page_html', MS_DEFAULT_PAGE_HTML );
		}
		if ( ! get_option( 'ms_allowed_roles') ) {
			add_option( 'ms_allowed_roles', explode( ',', MS_DEFAULT_ALLOWED_ROLES ) );
		}
		
		$plugin->create_php_file();
		
	}

}
