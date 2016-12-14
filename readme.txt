=== Code Freeze ===
Contributors: k3davis
Tags: code freeze, read only, disable, disable comments, disable dashboard, migration
Author URI: http://www.davistribe.org/code/
Requires at least: 3.0
Tested up to: 3.8
Stable tag: trunk

Temporarily puts your WordPress site into a "read only" state. Useful on multi-author sites that are in the process of changing web hosts.

== Description ==

Code Freeze is a simple plugin that temporarily does the following while activated:

* Disables adding/editing/deleting new content, media, themes, etc.
* Disables installing/activating/deactivating/updating/deleting all plugins (except this one)
* Disables new comments/trackbacks on all content items
* Removes the QuickPress widget and notices to upgrade core or plugins
* Provides notice to dashboard users that any changes will be lost
* Provides generally "read only" access to the dashboard

These changes apply to all users (including admins). When deactivated, full functionality is restored. This plugin makes no database or site changes and has no settings. Simply deactivate/delete when no longer needed.

Activate this plugin on your old site when you're in the process of changing web hosts to prevent lost data due to delays in DNS changes, or as a simple short-term "lock down" for other reasons. Also can be network activated to apply to all network sites.

English only, but translation-ready. Edit the English language file (or create one for your locale) to change the text of the login or admin messages.

== Installation ==

1. Upload the entire `code-freeze` folder to the `/wp-content/plugins/` directory.
2. When you are ready to enact the "code freeze," activate the plugin through the 'Plugins' menu in WordPress.
3. Done.

This plugin makes no database or site changes and has no settings. Simply deactivate/delete when no longer needed.

== Frequently Asked Questions ==

= What is the purpose of this? =
This plugin was developed to aid multi-user sites when they are being moved from one web host to another. In this case, the following workflow may be helpful:

* Download a backup of your site (cPanel, etc.) and database. (Export and backup options are still available even if this plugin is already active.)
* Install the Code Freeze plugin on the old site, and activate it. This will alert others that content changes won't be accepted or will be lost.
* Upload/import your old site to your new one, and don't install the Code Freeze plugin there (unless you want to verify everything before returning the site to normal).
* Notify your users that when the "code freeze" message is removed, they may resume their work. When DNS changes are in effect, you'll be directed to the site that doesn't have it installed, and all should be well.

= How do I return my site and dashboard to normal? =
Just deactivate or delete the plugin. There are no changes made to the site or database nor any settings to worry about.

= Is it 100% bulletproof? =
Not exactly. Many of the restrictions are hidden options rather than disabled capabilities, to reduce complexity. Therefore this is not intended to be a 100% bulletproof solution to preventing changes to your site, as a user who is familiar with the URL syntax of the different commands may still make changes. Additionally, some plugins may continue to expose options that can be modified, though this should be rare. While not completely bulletproof in every circumstance, it is designed to keep the average user from making changes during a brief time frame (such as waiting for DNS changes to take effect).

= How do I change the text on the login screen or the alert message in the dashboard? =
Edit the values in the appropriate language file. If there isn't one for your locale, go ahead and create one.

= Can I use this on a Network (Multisite) install? =
Yes. The plugin can be network activated rendering all sites on the network effectively read-only. However, it does not support loading via the `mu-plugins` folder at this time.

= Why can users still do such-and-such while the plugin is active? =
Let me know what you discover is still available and I'll try to disable modifications to it where appropriate.

== Changelog ==

= 1.2.3 =
* WP 3.8 readiness

= 1.2.2 =
* Fixed broken plugin page deactivation
* Added bbPress support (thanks theZedt)
* Additional WP 3.7+ features read-only

= 1.2.1 =
* Re-enabled Export while plugin is activated

= 1.2 =
* Additional appearance settings read-only.
* Additional read-only settings for Network Admins.

= 1.1 =
* GPL'd
* Translation ready.
* More disabled options in dashboard.

= 1.0  =
* Initial release (private).