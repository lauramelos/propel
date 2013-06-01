<?php
/*
Plugin Name: WP Export
Plugin URI: http://wpwebs.com/
Description: The plugin is useful to take backup of your database to excel sheet as well you can upload to new database. It will give you complete post information with post title,desctioption and all custom fields and values. You can see the website and author link @ <a href="http://wpwebs.com/" target="_blank">http://wpwebs.com/</a>
Version: 1.0.1
Author: VA Jariwala
Author URI: http://wpwebs.com/
*/

global $wpdb,$table_prefix;

if(!function_exists('ramwp_export_init'))
{
	function ramwp_export_init() {
		global $wpdb,$url_db_table_name;
	}
}
add_action('init', 'ramwp_export_init');

if(!function_exists('ramwp_export_interface_menu_page'))
{
	function ramwp_export_interface_menu_page() {
		if ( function_exists('add_submenu_page') )
		{
			add_submenu_page('tools.php', __('WP Export'), __("WP Export"), 8, 'export', 'ramwp_export_add_action');			
		}
	}
}
add_action('admin_menu', 'ramwp_export_interface_menu_page');

if(!function_exists('ramwp_export_admin_init'))
{
	function ramwp_export_admin_init() {
		if ( !function_exists( 'get_plugin_page_hook' ) )
			$hook = get_plugin_page_hook( 'akismet-stats-display', 'index.php' );
		else
			$hook = 'dashboard_page_akismet-stats-display';
		add_action('admin_head-'.$hook, 'akismet_stats_script');
	}
}
add_action('admin_init', 'ramwp_export_admin_init');

if(!function_exists('ram_wp_export_post_info'))
{
	function ram_wp_export_post_info()
	{
		if ($_REQUEST['page']=='export' && $_REQUEST['backup']=='posts')
		{
			include_once(WP_PLUGIN_DIR . "/wp_export/export/admin_export.php");
		exit;
		}
	}
}
add_action('init', 'ram_wp_export_post_info'); //EXPORT DATA EXCEL
if(!function_exists('ramwp_export_add_action'))
{
	function ramwp_export_add_action()
	{
		include_once(WP_PLUGIN_DIR.'/wp_export/admin_bulk.php'); //EXPORT DATA EXCEL FORM
	}
}
?>