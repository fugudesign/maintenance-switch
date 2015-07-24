=== Maintenance Switch ===
Contributors: fugudesign
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JYBDJCKL3FCE8
Tags: comments, spam
Requires at least: 3.5
Tested up to: 4.3.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Switch easily and simply in maintenance mode from your backend or frontend.

== Description ==

This plugin add a button to the admin bar for toggling the builtin maintenance mode.

A .maintenance file is generated and copied to the Wordpress installation folder when turning on the maintenance mode.
A maintenance.php file is generated and added to the wp-content folder for custom HTML code.

The option page allows to set the roles can bypass the maintenance mode on the frontend.
The option page allows to set the entire HTML code used for the maintenance page. 

== Installation ==

This section describes how to install the plugin and get it working.

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'maintenance-switch'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `maintenance-switch.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `maintenance-switch.zip`
2. Extract the `maintenance-switch` directory to your computer
3. Upload the `maintenance-switch` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==

= Can I set a counter for coming soon page? =

The plugin allows you to set the entire HTML code of the maintenance page, you can implement a counter with css and js code.

= Is my maintenance page HTML used during the core maintenance? =

Yes, the maintenance.php file is used.

== Screenshots ==

1. A button is added to the admin bar for simple toggling in maintenance mode.
2. A setting page allows to define which roles can bypass the maintenance mode and to define the entire HTML code of the maintenance page.

== Changelog ==

= 1.0.1 =
* Fully rewritten with http://wppb.me/ boilerplate.
* Adding activate and deactivate functionalities.
* Fix switch button styles and actions from frontend.

= 1.0.0 =
* A change since the previous version.
* Another change.

= 1.0-alpha =
* First simple implementation of the main functionality

== Upgrade Notice ==

= 1.0.1 =
This version is fully rewritten for best OOP implementation

= 1.0.0 =
This version is fully rewritten (OOP)
