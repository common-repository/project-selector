=== Plugin Name ===
Contributors: silex team
Tags: project, selector, silex
Requires at least: 3.0
Tested up to: 3.0.3
Stable tag: trunk

Add project compatibility and versions to posts or pages

== Description ==

This plugin allows you to add project compatibility and versions to posts or pages.
-Discontinued- Project Selector is now a part of the Exchange Platform plugin.
see http://wordpress.org/extend/plugins/exchange-platform/ for more details.

== Installation ==

1. Upload the `project-selector` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the To Settings -> Project Selector to add your projects and versions
4. In the post or page edit choose your project and version
5. In your theme pages add <?php echo get_post_meta($post->ID, "selectedProject", true); ?> to display the selected project and or <?php echo get_post_meta($post->ID, "selectedVersion", true); ?> to display the selected version

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.0 =
* Initial release
