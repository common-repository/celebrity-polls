<?php
/*
Plugin Name: Celebrity Polls
Description: Adds a widgets that allows blog visitors to vote on celebrity polls and share votes on facebook pages.
Plugin URI: http://www.datasub.com/widget
Author: Datasub.com
Author URI: http://www.datasub.com
Version: 1.1.0 beta
License: GPLv2 or later
*/



define("MANAGEMENT_PERMISSION", "edit_themes"); //The minimum privilege required to manage ads. http://tinyurl.com/wpprivs



//Stylesheet
    function s22survey_stylesheet() {
	if (get_option("s22survey_disable_default_style")=='') {		
            wp_register_style('s22surveystyle', s22survey_get_plugin_dir('url').'/s22survey.css');
            wp_enqueue_style('s22surveystyle');
	}
}
add_action('wp_enqueue_scripts', 's22survey_stylesheet');


//Installer
function s22survey_install () {
	require_once(dirname(__FILE__).'/installer.php');
}
register_activation_hook(__FILE__,'s22survey_install');



//Add the Admin Menus
if (is_admin()) {
    function s22survey_add_admin_menu() {
                add_menu_page(__("Celebrity Polls", 's22survey'), __("Celebrity Polls", 's22survey'), MANAGEMENT_PERMISSION, __FILE__, "s22survey_write_managemenu");             
	}
	//Include menus
	require_once(dirname(__FILE__).'/adminmenu.php');
}



//Return path to plugin directory (url/path)
function s22survey_get_plugin_dir($type) {
	if ( !defined('WP_CONTENT_URL') )
		define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
	if ( !defined('WP_CONTENT_DIR') )
		define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ($type=='path') { return WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)); }
	else { return WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)); }
}




function s22survey_add_menu_favorite($actions) {
	$actions['admin.php?page=s22survey/s22survey.php'] = array('Manage Ads', 'manage_options');
	return $actions;
}
add_filter('favorite_actions', 's22survey_add_menu_favorite'); //Favorites Menu


//Hooks
if (is_admin()) { add_action('admin_menu', 's22survey_add_admin_menu'); } //Admin pages



?>