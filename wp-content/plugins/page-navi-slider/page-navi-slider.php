<?php

/*
Plugin Name: Page navi slider
Plugin URI: 
Description: An advanced, fully customizable and actually responsive navigation plugin using jQuery slider 
Version: 1.2.2
Author: Iznogood1
Author URI: denisns1@free.fr
*/

//Installation / Uninstallation
require_once(dirname( __FILE__ ) . '/inc/install.php');

//Administration
require_once(dirname( __FILE__ ) . '/inc/settings.php');

//Display the plugin
require_once(dirname( __FILE__ ) . '/inc/frontend.php');

//Version
function wpns_version(){
	$v=get_plugin_data(__File__);
	return $v['Version'];
}

//Load style and JS
function wpns_style_and_scripts() {
	wp_register_style('page_navi_slider_style', plugins_url('style/page-navi-slider.css', __FILE__) );
  wp_enqueue_style('page_navi_slider_style' );
	wp_enqueue_script('page-navi-slider-script',  plugins_url('/js/page-navi-slider.min.js', __FILE__),	array( 'jquery', 'jquery-ui-slider' ), true);
	wp_enqueue_script('jQueryUiTouch',  plugins_url('/js/jquery.ui.touch-punch.min.js', __FILE__),	array( 'jquery' ), true);
	if(ereg('MSIE 7',$_SERVER['HTTP_USER_AGENT'])){
		wp_register_style('page_navi_slider_styleIE', plugins_url('style/page-navi-slider.ie.css', __FILE__) );
		wp_enqueue_style('page_navi_slider_styleIE' );
	} 
}

//Localization
load_plugin_textdomain( 'page-navi-slider', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );

//Actions and filtes
add_action( 'wp_enqueue_scripts', 'wpns_style_and_scripts' );
register_activation_hook(__FILE__, 'wpns_install' ); 
register_deactivation_hook(__FILE__, 'wpns_remove_auto_display' ); 
register_uninstall_hook(__FILE__, 'wpns_uninstall');
wpns_auto_display();

//Main function
function page_navi_slider(){
	//Prepare pagination	
	global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => all,
		'type' => 'array',
		prev_next => false,
	);
	
	if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
	
	if( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array( 's' => str_replace( ' ' , '+', get_query_var( 's' ) ) );
	
	//Display the plugin
	$page_links=paginate_links( $pagination );
	wpns_frontend($current,$wp_query->max_num_pages,$page_links,get_option('wpns_settings'));
}
?>