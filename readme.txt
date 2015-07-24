=== Maintenance Switch ===
Contributors: fugudesign
Donate link: http://example.com/
Tags: comments, spam
Requires at least: 3.5.1
Tested up to: 3.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin add a button to the admin bar for toggling the builtin maintenance mode.

== Description ==

This plugin add a button to the admin bar for toggling the builtin maintenance mode.

A .maintenance file is generated and copied to the Wordpress installation folder when turning on the maintenance mode.
A maintenance.php file is generated and added to the wp-content folder for custom HTML code.

The option page allows to set the roles can bypass the maintenance view on the frontend.
The option page allows to set the entire HTML code used for the maintenance page. 

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

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

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.
