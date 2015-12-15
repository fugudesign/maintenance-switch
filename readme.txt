=== Maintenance Switch ===
Contributors: fugudesign
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JYBDJCKL3FCE8
Tags: maintenance, maintenance mode, coming soon, coming soon page, mode, wordpress maintenance mode, site maintenance, site offline, unavailable, switch, administration, construction, under construction, offline, fugu
Requires at least: 3.5
Tested up to: 4.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Customize and switch easily and simply in (native) maintenance mode from your backend or frontend.

== Description ==

This plugin adds a button to the admin bar for toggling the builtin maintenance mode.

A .maintenance file is generated and copied to the Wordpress installation folder when turning on the maintenance mode.
A maintenance.php file is generated and added to the wp-content folder for custom HTML code.

The option page allows to set which roles can bypass the maintenance mode on the frontend.
The option page allows to set the entire HTML code used for the maintenance page. 
The option page allows to enable the theme file support, so you can create in each your themes a maintenance.php file to customize the maintenance page.

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
6. Navigate to 'Settings' > 'Maintenance Switch'
7. Adjust and save your settings

= Using FTP =

1. Download `maintenance-switch.zip`
2. Extract the `maintenance-switch` directory to your computer
3. Upload the `maintenance-switch` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard
5. Navigate to 'Settings' > 'Maintenance Switch'
6. Adjust and save your settings


== Frequently Asked Questions ==

= Can I set a counter for coming soon page? =

The plugin allows you to set the entire HTML code of the maintenance page, you can implement a counter with css and js code.

= Is my maintenance page HTML used during the core maintenance? =

Yes, the maintenance.php file is used.

= Does the plugin automatically detects the url login? =

Yes.

= Is it possible to customize page from my theme? =

Yes, use the settings page to enable the theme file support. 


== Screenshots ==

1. A button is added to the admin bar for simple toggling in maintenance mode.
2. A setting page allows to define which roles can bypass the maintenance mode and to define the entire HTML code of the maintenance page, or use a custom file in your theme.

== Changelog ==

= 1.1.1 =
* Fix a deactivation bug after updates
* Adding the status management in database
* Adding the ability to use a maintenance.php file in the theme

= 1.0.7 =
* Fix a saving bug in the settings page

= 1.0.6 =
* Fix a js bug with the "add my ip" button

= 1.0.5 =
* Fix a php syntax error in config.php

= 1.0.4 =
* Adding exceptions for IP addresses

= 1.0.3 =
* Adding automatic detection of the login url

= 1.0.2 =
* Ajusting the readme file

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
