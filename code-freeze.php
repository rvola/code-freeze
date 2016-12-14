<?php

/*
Plugin Name: Code Freeze
Plugin URI: http://wordpress.org/extend/plugins/code-freeze/
Description: Temporarily puts your WordPress into a "read only" state. When activated, comments and trackbacks are temporarily disabled as well as changes in the dashboard. Deactivate to restore full functionality.
Author: Kevin Davis
Author URI: http://www.davistribe.org/
Text Domain: codefreeze
Domain Path: /languages/
Version: 1.2.3
License: GPLv2
*/

/**
 * LICENSE
 * This file is part of Code Freeze.
 *
 * Code Freeze is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package    code-freeze
 * @author     Kevin Davis <kev@tnw.org>
 * @copyright  Copyright 2012 Kevin Davis
 * @license    http://www.gnu.org/licenses/gpl.txt GPL 2.0
 * @link       http://www.davistribe.org/codefreeze/
 */

if ( ! function_exists( 'cf_custom_login_message' ) ) {
	add_filter( 'login_message' , 'cf_custom_login_message' );
	
	/**
	 * Insert text onto login page
	 *
	 * @return  string Text to insert onto login page
	 */
	function cf_custom_login_message() {
		$message = '<p style="padding:10px;border: 2px solid red; margin-bottom: 10px;"><span style="color:red;font-weight:bold;">'.__('CODE FREEZE NOTICE', 'codefreeze' ).':</span><br/>'.__('This site is currently being migrated to a new location. Changes made here will not be reflected in the migrated site. To avoid lost work, please do not make any changes to the site until this message is removed.', 'codefreeze' ).'</p>';
		return $message;
	}
}

if ( ! function_exists( 'cf_effective_notice' ) ) {
	add_action( 'admin_notices', 'cf_effective_notice' );
	
	/**
	 * Show notice on site pages when site disabled
	 *
	 * @return  void
	 */
	function cf_effective_notice() {
		echo '<div class="error"><p><strong>'.__('Warning: Code Freeze is in effect.', 'codefreeze').'</strong> '.__('All changes made during this period will be lost.', 'codefreeze' ).'</p></div>';
	}
}

if ( ! function_exists( 'cf_admin_init' ) ) {
	add_action( 'admin_init', 'cf_admin_init' );
	add_action( 'admin_print_scripts', 'cf_load_admin_head' );
	add_action( 'plugins_loaded', 'cf_close_comments' );
	add_action( 'admin_head' , 'cf_remove_media_buttons' );
	//add_filter( 'user_can_richedit', '__return_false' );
	add_filter( 'tiny_mce_before_init', 'cf_visedit_readonly' );
	add_filter( 'post_row_actions', 'cf_remove_row_actions', 10, 1 );
	add_filter( 'page_row_actions', 'cf_remove_row_actions', 10, 1 );
	add_filter( 'user_row_actions', 'cf_remove_row_actions', 10, 1 );
	add_filter( 'tag_row_actions', 'cf_remove_row_actions', 10, 1 );
	add_filter( 'media_row_actions', 'cf_remove_row_actions', 10, 1 );
	add_filter( 'plugin_install_action_links', 'cf_remove_row_actions', 10, 1 );
	add_filter( 'theme_install_action_links', 'cf_remove_row_actions', 10, 1 );
	add_filter('plugin_action_links', 'cf_plugin_action_links', 10, 2);
	
	/**
	 * Register javascript, disable quickpress widget, remove add/edit menu items
	 *
	 * @return  void
	 */
	function cf_admin_init() {
		// register js
		wp_register_script( 'codefreeze-js', plugins_url('/js/func.js', __FILE__) );
		
		// make localizable
		load_plugin_textdomain( 'codefreeze', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		// remove QuickPress widget
		remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');
		
		// remove menu items - doesn't work for all of them in admin_menu
		cf_modify_menu();
	}
	
	/**
	 * Load javascript on all admin pages
	 *
	 * @return  void
	 */
	function cf_load_admin_head() {
		wp_enqueue_script( 'codefreeze-js' );
	}
	
	/**
	 * Close comments and trackbacks while activated
	 *
	 * @return  void
	 */
	function cf_close_comments() {
		add_filter( 'the_posts', 'cf_set_comment_status' );
		add_filter( 'comments_open', 'cf_close_the_comments', 10, 2 );
		add_filter( 'pings_open', 'cf_close_the_comments', 10, 2 );
		
		/**
		 * Close comments and trackbacks while activated
		 *
		 * @return  array Array of posts with comments closed
		 */
		function cf_set_comment_status ( $posts ) {
			if ( ! empty( $posts ) && is_singular() ) {
				$posts[0]->comment_status = 'closed';
				$posts[0]->post_status = 'closed';
			}
			return $posts;
		}
		
		/**
		 * Close comments and trackbacks while activated
		 *
		 * @return  $open
		 */
		function cf_close_the_comments ( $open, $post_id ) {
			// if not open, than back
			if ( ! $open )
				return $open;
				$post = get_post( $post_id );
			if ( $post -> post_type ) // all post types
				return FALSE;
			return $open;
		}
	}
	/**
	 * Remove media upload button(s)
	 *
	 * @return  void
	 */
	function cf_remove_media_buttons() {
		remove_action( 'media_buttons', 'media_buttons' );
	}
	
	/**
	 * Set visual editor as "read only"
	 *
	 * @return  array Array of arguments to send to editor
	 */
	function cf_visedit_readonly() {
		// suppress php warning in core when editor is read only
		error_reporting(0);
		return $args['readonly'] = 1;
	}
	
	/**
	 * Remove invalid action links
	 *
	 * @return  array Modified array of action links
	 */
	function cf_remove_row_actions($actions) {
		unset( $actions['trash'] );
		unset( $actions['delete'] );
		
		// no normal filter action for this (install plugin row)
		foreach ($actions as $k => $v) {
			if (strpos($v, 'class="install-now') ) {
				unset ($actions[$k]);
			}
		}
		
		return $actions;
	}
	
	/**
	 * Remove add/edit menu items
	 *
	 * @return  void
	 */
	function cf_modify_menu() {
		global $submenu;
		unset($submenu['edit.php?post_type=page'][10]); // Page > Add New
		remove_submenu_page('edit.php', 'post-new.php');
		remove_submenu_page('sites.php', 'site-new.php');
		remove_submenu_page('upload.php', 'media-new.php');
		remove_submenu_page('link-manager.php', 'link-add.php');
		remove_submenu_page('themes.php', 'theme-editor.php');
		remove_submenu_page('themes.php', 'customize.php');
		remove_submenu_page('themes.php', 'theme-install.php');
		remove_submenu_page('plugins.php', 'plugin-editor.php');
		remove_submenu_page('plugins.php', 'plugin-install.php');
		remove_submenu_page('users.php', 'user-new.php');
		remove_submenu_page('tools.php', 'import.php');
		remove_submenu_page('update-core.php', 'upgrade.php');
	}
	
	/**
	 * Remove Activation/Deactivation/Edit links for all plugins but this one
	 *
	 * @return  array Modified array of action links for plugins
	 */
	function cf_plugin_action_links($links, $file) {
		$this_plugin = plugin_basename(__FILE__);
		
		unset($links['edit']);
		
		if ($file !== $this_plugin) {
			return array(); // prevents PHP warning from any plugins that have modified the action links
		}
		return $links;
	}
	
	/**
	 * Remove topic replies and new topics from bbPress
	 *
	 * @note	props to theZedt
	 * @return  void
	 */
	if ( class_exists( 'bbPress' ) ) {
		add_filter( 'bbp_current_user_can_access_create_reply_form', cf_close_bbp_comments );
		add_filter( 'bbp_current_user_can_access_create_topic_form', cf_close_bbp_comments );

		function cf_close_bbp_comments() {
			return false;
		}
	}
}