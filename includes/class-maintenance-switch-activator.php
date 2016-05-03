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
		
		// Get the main controller
		$plugin = new Maintenance_Switch();
		
		$plugin->init_settings();
		$plugin->sync_status();
		
	}

}
