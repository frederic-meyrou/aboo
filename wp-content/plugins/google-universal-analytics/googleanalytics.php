<?php
/*
Plugin Name: Google Universal Analytics
Plugin URI: http://wordpress.org/extend/plugins/google-universal-analytics/
Description: Adds <a href="http://www.google.com/analytics/">Google Analytics</a> tracking code on all pages.
Version: 2.1
Author: Audrius Dobilinskas
Author URI: http://onlineads.lt/
*/

if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');

function deactive_google_universal_analytics() {
  delete_option('web_property_id');
  delete_option('in_footer');
  delete_option('plugin_switch');
  delete_option('track_links');
  delete_option('tracking_off_for_this_role');
  delete_option('tracking_off_for_role');
  //classic_options
  delete_option('classic_property_id');
  delete_option('classic_in_footer');
  delete_option('classic_plugin_switch');
  delete_option('classic_tracking_off_for_role');
  delete_option('classic_tracking_off_for_this_role');
  
  //custom_options
  delete_option('custom_in_footer');
  delete_option('custom_plugin_switch');
  delete_option('custom_web_property_id');
  delete_option('custom_tracking_off_for_role');
  delete_option('custom_tracking_off_for_this_role');
}


function admin_menu_google_universal_analytics() {

 
  global  $settings_page, $settings_page1, $classic_page, $cutom_page;
  
  $settings_page	=	add_menu_page( 'Google Universal Analytics', 'Google Universal Analytics', 'manage_options', 'google_universal_analytics', 'options_page_google_universal_analytics' );
  add_submenu_page('google_universal_analytics','','','manage_options','google_universal_analytics','options_page_google_universal_analytics');
 $settings_page1	=	 add_submenu_page( 'google_universal_analytics', 'Settings', 'Settings', 'manage_options', 'google_universal_analytics', 'options_page_google_universal_analytics' );
 $classic_page	=	 add_submenu_page( 'google_universal_analytics', 'Classic Analytics', 'Classic Analytics', 'manage_options', 'classic_analytics', 'classic_analytics_page_google_universal_analytics' );
 
 $cutom_page	=	 add_submenu_page( 'google_universal_analytics', 'Custom Tracker', 'Custom Tracker', 'manage_options', 'custom_analytics', 'custom_analytics_page_google_universal_analytics' );
  
}

function options_page_google_universal_analytics() {
  include(WP_PLUGIN_DIR.'/google-universal-analytics/options.php');  
}
function classic_analytics_page_google_universal_analytics() {
  include(WP_PLUGIN_DIR.'/google-universal-analytics/classic/classic-analytics.php');  
}
function custom_analytics_page_google_universal_analytics() {
  include(WP_PLUGIN_DIR.'/google-universal-analytics/classic/custom-analytics.php');  
}

function google_universal_analytics() {
  require 'tracking-code.php';
}

function google_classic_analytics() {
  $classic_property_id	=	get_option('classic_property_id');
  $classic_analytics_code	=	"<script type='text/javascript'>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$classic_property_id']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>";
echo $classic_analytics_code;
}
function google_custom_analytics() {
	echo get_option('custom_web_property_id');
}

function google_universal_analytics_scripts($hook){
		global  $settings_page, $settings_page1, $classic_page, $cutom_page;
		
		if($hook != $settings_page && $hook != $settings_page1 && $hook != $classic_page && $hook != $cutom_page )
		return;
		
		//register styles
		wp_register_style( 'bootstrap-css', plugins_url( 'google-universal-analytics/bootstrap/css/bootstrap.min.css' , dirname(__FILE__) ) );
		wp_register_style( 'bootstrap-switch-css', plugins_url( 'google-universal-analytics/bootstrap/css/bootstrap-switch.min.css' , dirname(__FILE__) ) );
		wp_register_style( 'main-css', plugins_url( 'google-universal-analytics/assets/gua-main.css' , dirname(__FILE__) ) );
		
		//register scripts
		wp_register_script( 'google-js', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', array(), '', true );
		wp_register_script( 'bootstrap-js', plugins_url( 'google-universal-analytics/bootstrap/js/bootstrap.min.js' , dirname(__FILE__) ), array('google-js'), '', true );
		wp_register_script( 'bootstrap-switch-js', plugins_url( 'google-universal-analytics/bootstrap/js/bootstrap-switch.min.js' , dirname(__FILE__) ) , array('bootstrap-js'),'',true );
		wp_register_script( 'main-js', plugins_url( 'google-universal-analytics/assets/gua-main.js' , dirname(__FILE__) ) , array('google-js'),'',true );
		
		
		//enqueue styles
		wp_enqueue_style( 'bootstrap-css' );
		wp_enqueue_style( 'bootstrap-switch-css' );
		wp_enqueue_style( 'main-css' );
		
		//enqueue scripts
		wp_enqueue_script( 'google-js' );
		wp_enqueue_script( 'bootstrap-js' );
		wp_enqueue_script( 'bootstrap-switch-js' );
		wp_enqueue_script( 'main-js' );
}


register_deactivation_hook(__FILE__, 'deactive_google_universal_analytics');

if (is_admin()) {
  add_action('admin_enqueue_scripts', 'google_universal_analytics_scripts');		
  add_action('admin_menu', 'admin_menu_google_universal_analytics');
}

add_action('init', 'display_google_universal_analytics_code');
function display_google_universal_analytics_code(){
		global $current_user;
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
	
	
	if(get_option('tracking_off_for_role')=='on' && strcasecmp($user_role , get_option('tracking_off_for_this_role'))!=0){
		if (!is_admin() && get_option('plugin_switch')=='on') {
			if(get_option('in_footer')=='on'){
				add_action('wp_footer', 'google_universal_analytics');
			}else{
				add_action('wp_head', 'google_universal_analytics');
			}
		}
	}elseif(is_user_logged_in() && get_option('tracking_off_for_role')!='on'){
		if (!is_admin() && get_option('plugin_switch')=='on') {
			if(get_option('in_footer')=='on'){
				add_action('wp_footer', 'google_universal_analytics');
			}else{
				add_action('wp_head', 'google_universal_analytics');
			}
		}

	}elseif(!is_user_logged_in()){
		if (!is_admin() && get_option('plugin_switch')=='on') {
			if(get_option('in_footer')=='on'){
				add_action('wp_footer', 'google_universal_analytics');
			}else{
				add_action('wp_head', 'google_universal_analytics');
			}
		}

	}
	
	
	if(get_option('custom_tracking_off_for_role')=='on' && strcasecmp($user_role , get_option('custom_tracking_off_for_this_role'))!=0){
		if (!is_admin() && get_option('custom_plugin_switch')=='on') {
			if(get_option('custom_in_footer')=='on'){
				add_action('wp_footer', 'google_custom_analytics');
			}else{
				add_action('wp_head', 'google_custom_analytics');
			}
		}
	}elseif(is_user_logged_in() && get_option('custom_tracking_off_for_role')!='on'){
		if (!is_admin() && get_option('custom_plugin_switch')=='on') {
			if(get_option('custom_in_footer')=='on'){
				add_action('wp_footer', 'google_custom_analytics');
			}else{
				add_action('wp_head', 'google_custom_analytics');
			}
		}

	}elseif(!is_user_logged_in()){
		if (!is_admin() && get_option('custom_plugin_switch')=='on') {
			if(get_option('custom_in_footer')=='on'){
				add_action('wp_footer', 'google_custom_analytics');
			}else{
				add_action('wp_head', 'google_custom_analytics');
			}
		}

	}
	
	if(get_option('classic_tracking_off_for_role')=='on' && strcasecmp($user_role , get_option('classic_tracking_off_for_this_role'))!=0){
		if (!is_admin() && get_option('classic_plugin_switch')=='on') {
			if(get_option('classic_in_footer')=='on'){
				add_action('wp_footer', 'google_classic_analytics');
			}else{
				add_action('wp_head', 'google_classic_analytics');
			}
		}
	}elseif(is_user_logged_in() && get_option('classic_tracking_off_for_role')!='on'){
		if (!is_admin() && get_option('classic_plugin_switch')=='on') {
			if(get_option('classic_in_footer')=='on'){
				add_action('wp_footer', 'google_classic_analytics');
			}else{
				add_action('wp_head', 'google_classic_analytics');
			}
		}
	}elseif(!is_user_logged_in()){
		if (!is_admin() && get_option('classic_plugin_switch')=='on') {
			if(get_option('classic_in_footer')=='on'){
				add_action('wp_footer', 'google_classic_analytics');
			}else{
				add_action('wp_head', 'google_classic_analytics');
			}
		}	
	}

}



function save_google_universal_analytics_settings() {
	// The $_REQUEST contains all the data sent via ajax
	if ( isset($_REQUEST) ) {
		$property_id = $_REQUEST['property_id'];
		$in_footer = $_REQUEST['in_footer'];
		$plugin_switch = $_REQUEST['plugin_switch'];
		$track_links = $_REQUEST['track_links'];
		$tracking_off_for_this_role = $_REQUEST['tracking_off_for_this_role'];
		$tracking_off_for_role = $_REQUEST['tracking_off_for_role'];
		
		
		update_option('web_property_id', $property_id);
  		update_option('in_footer', $in_footer);
 		update_option('plugin_switch', $plugin_switch);
		update_option('track_links', $track_links);
		update_option('tracking_off_for_this_role', $tracking_off_for_this_role);
		update_option('tracking_off_for_role', $tracking_off_for_role);
	}
	// Always die in functions echoing ajax content
   die();
}

function save_google_classic_analytics_settings() {
	// The $_REQUEST contains all the data sent via ajax
	if ( isset($_REQUEST) ) {
		$classic_property_id = $_REQUEST['classic_property_id'];
		$classic_in_footer = $_REQUEST['classic_in_footer'];
		$classic_plugin_switch = $_REQUEST['classic_plugin_switch'];
		$classic_tracking_off_for_role = $_REQUEST['classic_tracking_off_for_role'];
		$classic_tracking_off_for_this_role = $_REQUEST['classic_tracking_off_for_this_role'];
		
		
		update_option('classic_property_id', $classic_property_id);
  		update_option('classic_in_footer', $classic_in_footer);
 		update_option('classic_plugin_switch', $classic_plugin_switch);
		update_option('classic_tracking_off_for_role', $classic_tracking_off_for_role);
		update_option('classic_tracking_off_for_this_role', $classic_tracking_off_for_this_role);
	}
	// Always die in functions echoing ajax content
   die();
}
function save_google_custom_analytics_settings() {
	// The $_REQUEST contains all the data sent via ajax
	if ( isset($_REQUEST) ) {
		$custom_in_footer = $_REQUEST['custom_in_footer'];
		$custom_plugin_switch = $_REQUEST['custom_plugin_switch'];
		$custom_web_property_id = $_REQUEST['custom_web_property_id'];
		$custom_web_property_id	=	stripslashes($custom_web_property_id);
		$custom_tracking_off_for_role = $_REQUEST['custom_tracking_off_for_role'];
		$custom_tracking_off_for_this_role = $_REQUEST['custom_tracking_off_for_this_role'];
		
		
		update_option('custom_in_footer', $custom_in_footer);
  		update_option('custom_plugin_switch', $custom_plugin_switch);
 		update_option('custom_web_property_id', $custom_web_property_id);
		update_option('custom_tracking_off_for_role', $custom_tracking_off_for_role);
		update_option('custom_tracking_off_for_this_role', $custom_tracking_off_for_this_role);
	}
	// Always die in functions echoing ajax content
   die();
}


add_action( 'wp_ajax_save_google_universal_analytics_settings', 'save_google_universal_analytics_settings' );
add_action( 'wp_ajax_save_google_classic_analytics_settings', 'save_google_classic_analytics_settings' );
add_action( 'wp_ajax_save_google_custom_analytics_settings', 'save_google_custom_analytics_settings' );


?>