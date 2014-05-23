<?php

/*

Plugin Name: Google Universal Analytics
Plugin URI: http://wordpress.org/extend/plugins/google-universal-analytics/
Description: Adds <a href="http://www.google.com/analytics/">Google Analytics</a> tracking code on all pages.
Version: 2.3.1
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


register_deactivation_hook(__FILE__, 'deactive_google_universal_analytics');
register_activation_hook( __FILE__, 'activate_google_universal_analytics' );
add_action( 'admin_init', 'register_plugin_settings' );
add_action( 'admin_init', 'register_plugin_settings1' );
add_action( 'admin_init', 'register_plugin_settings2' );
function register_plugin_settings(){

	register_setting('google-universal-settings','plugin_switch');
	register_setting('google-universal-settings','web_property_id');
	register_setting('google-universal-settings','in_footer');
	register_setting('google-universal-settings','track_links');
	register_setting('google-universal-settings','enable_display');
	register_setting('google-universal-settings','anonymize_ip');
	register_setting('google-universal-settings','woo_tracking');
	register_setting('google-universal-settings','set_domain');
	register_setting('google-universal-settings','set_domain_domain');
	register_setting('google-universal-settings','tracking_off_for_this_role');
	register_setting('google-universal-settings','tracking_off_for_role');
	
	
}
function register_plugin_settings1(){
	//classic_options
	register_setting('google-universal-settings-classic','classic_property_id');
	register_setting('google-universal-settings-classic','classic_in_footer');
	register_setting('google-universal-settings-classic','classic_plugin_switch');
	register_setting('google-universal-settings-classic','classic_tracking_off_for_role');
	register_setting('google-universal-settings-classic','classic_tracking_off_for_this_role');	
}
function register_plugin_settings2(){
		//custom_options
	register_setting('google-universal-settings-custom','custom_in_footer');
	register_setting('google-universal-settings-custom','custom_plugin_switch');
	register_setting('google-universal-settings-custom','custom_web_property_id');
	register_setting('google-universal-settings-custom','custom_tracking_off_for_role');
	register_setting('google-universal-settings-custom','custom_tracking_off_for_this_role');
}
function activate_google_universal_analytics(){
	
	
	
	
	$woo_code_path	=	get_template_directory().'/woo_code.php';
	if(!file_exists($woo_code_path)){
		copy(plugin_dir_path( __FILE__ ).'woo_code.php', $woo_code_path);
	}
}
function deactive_google_universal_analytics() {
  delete_option('web_property_id');
  delete_option('in_footer');
  delete_option('plugin_switch');
  delete_option('track_links');
  delete_option('enable_display');
  delete_option('anonymize_ip');
  delete_option('woo_tracking');
  delete_option('set_domain');
  delete_option('set_domain_domain');
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

//delete woo code file
	$woo_code_path	=	get_template_directory().'/woo_code.php';
	if(file_exists($woo_code_path)){
		unlink($woo_code_path);
	}
	$var1	=	'require "woo_code.php";';
	$get_func_file_path	=	get_template_directory().'/functions.php';
	$contents = file_get_contents($get_func_file_path);
	$contents = str_replace($var1, '', $contents);
	file_put_contents($get_func_file_path, $contents);
}





function admin_menu_google_universal_analytics() {

  global  $settings_page, $settings_page1, $classic_page, $cutom_page;

  

  $settings_page	=	add_menu_page( 'Google Universal Analytics', 'Google Universal Analytics', 'manage_options', 'google_universal_analytics', 'options_page_google_universal_analytics' );

  add_submenu_page('google_universal_analytics','','','manage_options','google_universal_analytics','options_page_google_universal_analytics');

 $settings_page1	=	 add_submenu_page( 'google_universal_analytics', 'Universal Analytics', 'Universal Analytics', 'manage_options', 'google_universal_analytics', 'options_page_google_universal_analytics' );

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
//adding woo tracking

//$var1	=	'require "woo_code.php";';

$var1	=	'function ia_wc_ga_integration( $order_id ) {
	$order = new WC_Order( $order_id ); ?>
	
	<script type="text/javascript">
	ga("require", "ecommerce", "ecommerce.js"); // Load The Ecommerce Tracking Plugin
		
		// Transaction Details
		ga("ecommerce:addTransaction", {
			"id": "<?php echo $order_id;?>",
			"affiliation": "<?php echo get_option( "blogname" );?>",
			"revenue": "<?php echo $order->get_total();?>",
			"shipping": "<?php echo $order->get_total_shipping();?>",
			"tax": "<?php echo $order->get_total_tax();?>",
			"currency": "<?php echo get_woocommerce_currency();?>"
		});

	
	<?php
		//Item Details
	if ( sizeof( $order->get_items() ) > 0 ) {
		foreach( $order->get_items() as $item ) {
			$product_cats = get_the_terms( $item["product_id"], "product_cat" );
				if ($product_cats) { 
					$cat = $product_cats[0];
				} ?>
			ga("ecommerce:addItem", {
				"id": "<?php echo $order_id;?>",
				"name": "<?php echo $item["name"];?>",
				"sku": "<?php echo get_post_meta($item["product_id"], "_sku", true);?>",
				"category": "<?php echo $cat->name;?>",
				"price": "<?php echo $item["line_subtotal"];?>",
				"quantity": "<?php echo $item["qty"];?>",
				"currency": "<?php echo get_woocommerce_currency();?>"
			});
	<?php
		}	
	} ?>
		ga("ecommerce:send");
		</script>
<?php }
add_action( "woocommerce_thankyou", "ia_wc_ga_integration" );';

$get_func_file_path	=	get_template_directory().'/functions.php';
if(get_option('woo_tracking')=='on'){
	if(strpos(file_get_contents($get_func_file_path),$var1) !== false){
	}else{
	$data = sprintf("%s\n", $var1);
	file_put_contents($get_func_file_path, $data, FILE_APPEND);
	}
}elseif(get_option('woo_tracking')=='off'){
		$contents = file_get_contents($get_func_file_path);
		$contents = str_replace($var1, '', $contents);
		file_put_contents($get_func_file_path, $contents);
}

