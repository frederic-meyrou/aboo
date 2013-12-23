<?php
/*
Plugin Name: Quick Paypal Payments
Plugin URI: http://quick-plugins.com/quick-paypal-payments/
Description: Accept any amount or payment ID before submitting to paypal.
Version: 3.3
Author: fisicx
Author URI: http://quick-plugins.com/
*/

add_shortcode('qpp', 'qpp_start');
add_filter('plugin_action_links', 'qpp_plugin_action_links', 10, 2 );

if (is_admin()) require_once( plugin_dir_path( __FILE__ ) . '/settings.php' );

$css_dir = plugin_dir_path( __FILE__ ) . '/quick-paypal-payments-custom.css' ;
$filename = plugin_dir_path( __FILE__ );
if (is_writable($filename) && !file_exists($css_dir)) qpp_options_css();

wp_enqueue_script( 'qpp_script',plugins_url('quick-paypal-payments.js', __FILE__));
wp_enqueue_style( 'qpp_style',plugins_url('quick-paypal-payments.css', __FILE__));
wp_enqueue_style( 'qpp_custom',plugins_url('quick-paypal-payments-custom.css', __FILE__));

function qpp_start($atts) {
	return qpp_loop($atts);
	}
function qpp_plugin_action_links($links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$qpp_links = '<a href="'.get_admin_url().'options-general.php?page=quick-paypal-payments/settings.php">'.__('Settings').'</a>';
		array_unshift( $links, $qpp_links );
		}
	return $links;
	}
function qpp_verify_form($formvalues,$form) {
	$qpp = qpp_get_stored_options($form);
	$errors = '';
	$check = preg_replace ( '/[^.,0-9]/', '', $formvalues['amount']);
	if (!$formvalues['pay']) if ($formvalues['amount'] == $qpp['inputamount'] || empty($formvalues['amount'])) $errors = 'first';
	if (!$formvalues['id']) if ($formvalues['reference'] == $qpp['inputreference'] || empty($formvalues['reference'])) $errors = 'second';
	return $errors;
	}
function qpp_display_form( $values, $errors, $id ) {
	$qpp_form = qpp_get_stored_setup();
	$qpp = qpp_get_stored_options($id);
	$error = qpp_get_stored_error($id);
	$send = qpp_get_stored_send($id);
	$style = qpp_get_stored_style($id);
	if ($id) $formstyle=$id; else $formstyle='default';
	if (!empty($qpp['title'])) $qpp['title'] = '<h2>' . $qpp['title'] . '</h2>';
	if (!empty($qpp['blurb'])) $qpp['blurb'] = '<p>' . $qpp['blurb'] . '</p>';
	$content = "<div class='qpp-style ".$formstyle."'>\r\t";
	$content .= "<div id='" . $style['border'] . "'>\r\t";
	if ($errors)
		$content .= "<h2>" . $error['errortitle'] . "</h2>\r\t<p style='color: #D31900;margin: 4px 0;'>" . $error['errorblurb'] . "</p>\r\t";
	else
		{$content .= $qpp['title'] . "\r\t";
		if ($qpp['paypal-url'] && $qpp['paypal-location'] == 'imageabove') $content .= "<img src='".$qpp['paypal-url']."' />";
		$content .=  $qpp['blurb'] . "\r\t";}
		$content .= '<form id="frmPayment" name="frmPayment" method="post" action="" onsubmit="return validatePayment();">';
	if (empty($values['id'])) {$values['reference'] = strip_tags($values['reference']); $content .= '<p><input type="text" label="Reference" name="reference" value="' . $values['reference'] . '" onfocus="qppclear(this, \'' . $values['reference'] . '\')" onblur="qpprecall(this, \'' . $values['reference'] . '\')"/></p>';}
	else {
		if ($values['explode']) {
			$checked = 'checked';$ref = explode(",",strip_tags($values['reference']));
			$content .= '<p class="payment" >'.$qpp['shortcodereference'].'<br>';
			foreach ($ref as $item) { $content .=  '<label><input type="radio" style="margin:0; padding: 0; border:none;width:auto;" name="reference" value="' .  $item . '" ' . $checked . '> ' .  $item . '</label><br>';$checked='';}
			$content .= '</p>';}
		else $content .= '<p class="input" >'.strip_tags($values['reference']).'</p>';
		}
	if ($qpp['use_quantity']) {$values['quantity'] = strip_tags($values['quantity']); $content .= '<p><span class="input">'.$qpp['quantitylabel'].'</span><input type="text" style="width:3em;margin-left:5px" label="quantity" name="quantity" value="' . $values['quantity'] . '" onfocus="qppclear(this, \'' . $values['quantity'] . '\')" onblur="qpprecall(this, \'' . $values['quantity'] . '\')"/></p>';}
	if ($qpp['use_account']) {$values['account'] = strip_tags($values['account']); $content .= '<p><input type="text" label="Account" name="account" value="' . $values['account'] . '" onfocus="qppclear(this, \'' . $values['account'] . '\')" onblur="qpprecall(this, \'' . $values['account'] . '\')"/></p>';}
	if (empty($values['pay'])) {$values['amount'] = strip_tags($values['amount']); $content .= '<p><input type="text" label="Amount" name="amount" value="' . $values['amount'] . '" onfocus="qppclear(this, \'' . $values['amount'] . '\')" onblur="qpprecall(this, \'' . $values['amount'] . '\')"/></p>';}
	else $content .= '<p class="input" >' . $values['amount'] . '</p>';	
	$caption = $qpp['submitcaption'];
	if ($style['submit-button']) $content .= '<p><input type="image" value="' . $caption . '" style="border:none;" src="'.$style['submit-button'].'" name="PaymentSubmit" /></p>';
	else $content .= '<p><input type="submit" value="' . $caption . '" id="submit" name="qppsubmit'.$id.'" /></p>';
	$content .= '</form>'."\r\t";
		if ($qpp['paypal-url'] && $qpp['paypal-location'] == 'imagebelow') $content .= '<img src="'.$qpp['paypal-url'].'" />';
		$content .= '</div>'."\r\t".
		'</div>'."\r\t";
	echo $content;
	}
function qpp_process_form($values,$id) {
	$currency = qpp_get_stored_curr();
	$qpp = qpp_get_stored_options($id);
	$send = qpp_get_stored_send($id);
	$style = qpp_get_stored_style($id);
	$qpp_setup = qpp_get_stored_setup();
	$page_url = qpp_current_page_url();
	if (empty($send['thanksurl'])) $send['thanksurl'] = $page_url;
	if (empty($send['cancelurl'])) $send['cancelurl'] = $page_url;
	if ($send['target'] == 'newpage') $target = ' target="_blank" ';
	$qpp_messages = get_option('qpp_messages'.$id);
	if(!is_array($qpp_messages)) $qpp_messages = array();
	$sentdate = date_i18n('d M Y');
	$qpp_messages[] = array('field0'=>$sentdate,'field1' => $values['reference'] , 'field2' => $values['quantity'],'field3' => $values['amount'],'field4' => $values['account'],'date' => $sentdate,);
	update_option('qpp_messages'.$id,$qpp_messages);
	$check = preg_replace ( '/[^.,0-9]/', '', $values['amount']);
	$content = '<h2>'.$send['waiting'].'</h2><form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="frmCart" id="frmCart" ' . $target . '>
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="' . $qpp_setup['email'] . '">
	<input type="hidden" name="return" value="' .  $send['thanksurl'] . '">
	<input type="hidden" name="cancel_return" value="' .  $send['cancelurl'] . '">
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="currency_code" value="' .  $currency[$id] . '">
	<input type="hidden" name="item_number" value="' .$qpp['inputacount'] . ': ' . strip_tags($values['account']) . '">
	<input type="hidden" name="quantity" value="' . strip_tags($values['quantity']) . '">
	<input type="hidden" name="item_name" value="' .$qpp['inputreference'] . ': ' . strip_tags($values['reference']) . '">
	<input type="hidden" name="amount" value="' . $check . '">
	</form>
	<script language="JavaScript">
	document.getElementById("frmCart").submit();
	</script>';
	echo $content;
	}
function qpp_current_page_url() {
	$pageURL = 'http';
	if( isset($_SERVER["HTTPS"]) ) { if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";} }
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	return $pageURL;
	}
function qpp_loop($atts) {
	ob_start();
	extract(shortcode_atts(array( 'form' =>'','amount' => '' , 'id' => '','account' => '', 'labels' => ''), $atts));
	$qpp = qpp_get_stored_options($form);
	$formvalues['quantity'] = 1;
	if (!$labels) $shortcodereference = $qpp['shortcodereference'].' ';
	if ($id) {
		$formvalues['id'] = 'checked';
		if (strrpos($id,',')) {$formvalues['reference'] = $id;$formvalues['explode'] = 'checked';}
		else $formvalues['reference'] = $shortcodereference.$id;
		}
	else {$formvalues['reference'] = $qpp['inputreference'];$formvalues['id'] = '';}
	if ($amount) {$formvalues['amount'] = $qpp['shortcodeamount'].' '.$amount;$formvalues['pay'] = 'checked';}
	else {$formvalues['amount'] = $qpp['inputamount'];$formvalues['pay'] = '';}
	if (isset($_POST['qppsubmit'.$form]) || isset($_POST['qppsubmit_x'.$form])) {
		if (isset($_POST['reference'])) {$formvalues['reference'] = $_POST['reference'];$id = $_POST['reference'];}
		if (isset($_POST['amount'])) $formvalues['amount'] = $_POST['amount'];
		if (isset($_POST['account'])) $formvalues['account'] = $_POST['account'];
		if (isset($_POST['quantity'])) $formvalues['quantity'] = $_POST['quantity'];
		if (qpp_verify_form($formvalues,$form)) qpp_display_form($formvalues,'qpperror',$form);
   		else {
			if ($amount) $formvalues['amount'] = $amount;
			if ($id) $formvalues['reference'] = $id;
			qpp_process_form($formvalues,$form);
			}
		}
	else qpp_display_form($formvalues,'',$form);
	$output_string=ob_get_contents();
	ob_end_clean();
	return $output_string;
	}

function register_qpp_widget() {register_widget( 'qpp_Widget' );}
add_action( 'widgets_init', 'register_qpp_widget' );

class qpp_widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'qpp_widget', // Base ID
			'Paypal Payments', // Name
			array( 'description' => __( 'Paypal Payments', 'Add paypal payment form to your sidebar' ), ) // Args
		);
	}
	public function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		$id=$instance['id'];
		$amount=$instance['amount'];
		echo qpp_loop($instance);
	}
 	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['id'] = $new_instance['id'];
		$instance['amount'] = $new_instance['amount'];
		return $instance;
		}
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'amount' => '' , 'id' => '' ) );
		$id = $instance['id'];
		$amount = $instance['amount'];
		?>
		<p><label for="<?php echo $this->get_field_id('id'); ?>">Payment Reference: <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo attribute_escape($id); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('amount'); ?>">Amount: <input class="widefat" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" type="text" value="<?php echo attribute_escape($amount); ?>" /></label></p>
		<p>Leave blank to use the default settings.</p>
		<p>To configure the payment form use the <a href="'.get_admin_url().'options-general.php?page=quick-paypal-payments/quick-paypal-payments.php">Settings</a> page</p>
		<?php
		}
	}
function qpp_options_css () {
	$qpp_form = qpp_get_stored_setup();
	$arr = explode(",",$qpp_form['alternative']);
	foreach ($arr as $item) {
		$corners='';$input='';$background='';$paragraph='';$submit='';
		$style = qpp_get_stored_style($item);
		if ($item !='') $id = '.'.$item; else $id = '.default';
		if ($style['font'] == 'plugin') {
			$font = "font-family: ".$style['font-family']."; font-size: ".$style['font-size'].";color: ".$style['font-colour'].";line-height:100%;";
			$inputfont = "font-family: ".$style['font-family']."; color: ".$style['font-colour'].";";
			$submitfont = "font-family: ".$style['font-family'];
			}
		$input = ".qpp-style".$id." input[type=text]{border: ".$style['input-border'].";".$inputfont.";font-size: inherit;}\r\n";
		$paragraph = ".qpp-style".$id." p{".$font.";}\r\n";
		$submit = ".qpp-style".$id." #submit{color:".$style['submit-colour'].";background:".$style['submit-background'].";".$submitfont.";font-size: inherit;}\r\n";
		if ($style['background'] == 'white') $background = ".qpp-style".$id." div {background:#FFF;}\r\n";
		if ($style['background'] == 'color') $background = ".qpp-style".$id." div {background:".$style['backgroundhex'].";}\r\n";
		if ($style['widthtype'] == 'pixel') $width = preg_replace("/[^0-9]/", "", $style['width']) . 'px';
		else $width = '100%';
		if ($style['corners'] == 'round') $corner = '5px'; else $corner = '0';
		$corners = ".qpp-style".$id." input[type=text], .qpp-style".$id." textarea, .qpp-style".$id." select, .qpp-style".$id." #submit {border-radius:".$corner.";}\r\n";
		if ($style['corners'] == 'theme') $corners = '';
		$code .= ".qpp-style".$id." {width:".$width.";}\r\n".$corners.$paragraph.$input.$background.$submit;
		if ($style['use_custom'] == 'checked') $code .= $style['styles'] . "\r\n";
		}
	$data = $code;	
	$css_dir = plugin_dir_path( __FILE__ ) . '/quick-paypal-payments-custom.css' ;
	file_put_contents($css_dir, $data, LOCK_EX); // Save it
	}
function qpp_get_stored_setup () {
	$qpp_setup = get_option('qpp_setup');
	if(!is_array($qpp_setup)) $qpp_setup = array();
	$default = qpp_get_default_setup();
	$qpp_setup = array_merge($default, $qpp_setup);
	return $qpp_setup;
	}
function qpp_get_default_setup () {
	$qpp_setup = array();
	$qpp_setup['current'] = '';
	$qpp_setup['alternative'] = '';
	return $qpp_setup;
	}
function qpp_get_stored_curr () {
	$qpp_curr = get_option('qpp_curr');
	if(!is_array($qpp_curr)) $qpp_curr = array();
	$default = qpp_get_default_curr();
	$qpp_curr = array_merge($default, $qpp_curr);
	return $qpp_curr;
	}
function qpp_get_default_curr () {	
	$qpp_curr = array();
	$qpp_curr[''] = '';
	return $qpp_curr;
	}
function qpp_get_stored_msg () {
	$messageoptions = get_option('qpp_messageoptions');
	if(!is_array($messageoptions)) $messageoptions = array();
	$default = qpp_get_default_msg();
	$messageoptions = array_merge($default, $messageoptions);
	return $messageoptions;
	}
function qpp_get_default_msg () {
	$messageoptions = array();
	$messageoptions['messageqty'] = 'fifty';
	$messageoptions['messageorder'] = 'newest';
	return $messageoptions;
	}
function qpp_get_stored_options ($id) {
	$options = get_option('qpp_options'.$id);
	if(!is_array($options)) $options = array();
	$default = qpp_get_default_options();
	$options = array_merge($default, $options);
	return $options;
	}
function qpp_get_default_options () {
	$qpp = array();
	$qpp['title'] = 'Payment Form';
	$qpp['blurb'] = 'Enter the payment details and submit';
	$qpp['inputreference'] = 'Payment reference';
	$qpp['inputamount'] = 'Amount to pay';
	$qpp['quantitylabel'] = 'Quantity';
	$qpp['quantity'] = '1';
	$qpp['accountlabel'] = 'Account Number';
	$qpp['use_account'] = '';
	$qpp['shortcodereference'] = 'Payment for: ';
	$qpp['shortcodeamount'] = 'Amount: ';
	$qpp['paypal-location'] = 'imagebelow';
	$qpp['submitcaption'] = 'Make Payment';
	return $qpp;
	}
function qpp_get_stored_send($id) {
	$send = get_option('qpp_send'.$id);
	if(!is_array($send)) $send = array();
	$default = qpp_get_default_send();
	$send = array_merge($default, $send);
	return $send;
	}
function qpp_get_default_send() {
	$send['waiting'] = 'Waiting for PayPal...';
	$send['cancelurl'] = '';
	$send['thanksurl'] = '';
	$send['target'] = 'current';
	return $send;
	}
function qpp_get_stored_style($id) {
	$style = get_option('qpp_style'.$id);
	if(!is_array($style)) $style = array();
	$default = qpp_get_default_style();
	$style = array_merge($default, $style);
	return $style;
	}
function qpp_get_default_style() {
	$style['font'] = 'plugin';
	$style['font-family'] = 'arial, sans-serif';
	$style['font-size'] = '1.2em';
	$style['font-colour'] = '#465069';
	$style['width'] = 280;
	$style['widthtype'] = 'pixel';
	$style['border'] = 'plain';
	$style['input-border'] = '1px solid #415063';
	$style['input-required'] = '1px solid #00C618';
	$style['bordercolour'] = '#415063';
	$style['background'] = 'white';
	$style['backgroundhex'] = '#FFF';
	$style['corners'] = 'corner';
	$style['submit-colour'] = '#FFF';
	$style['submit-background'] = '#343838';
	$style['submit-button'] = '';
	$style['styles'] = 'plugin';
	$style['use_custom'] = '';
	$style['custom'] = "#qpp-style {\r\n\r\n}";
	return $style;
	}
function qpp_get_stored_error ($id) {
	$error = get_option('qpp_error'.$id);
	if(!is_array($error)) $error = array();
	$default = qpp_get_default_error();
	$error = array_merge($default, $error);
	return $error;
	}
function qpp_get_default_error () {
	$error['errortitle'] = 'Oops, got a problem here';
	$error['errorblurb'] = 'Please check the payment details';
	return $error;
	}