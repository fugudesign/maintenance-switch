<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.fugu.fr
 * @since      1.0.0
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/includes
 * @author     Fugu <info@fugu.fr>
 */
class Maintenance_Switch_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
		$plugin = new Maintenance_Switch();
		
		$plugin->_delete_file( MS_PHP_FILE_ACTIVE );
		$plugin->_delete_file( MS_DOT_FILE_ACTIVE );
		
	}

}
