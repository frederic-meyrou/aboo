<?php
/*
Plugin Name: Quick Paypal Payments
Plugin URI: http://quick-plugins.com/quick-paypal-payments/
Description: Accept any amount or payment ID before submitting to paypal.
Version: 3.6.3
Author: fisicx
Author URI: http://quick-plugins.com/
*/

add_shortcode('qpp', 'qpp_start');
add_shortcode('qppreport', 'qpp_report');

add_filter('plugin_action_links', 'qpp_plugin_action_links', 10, 2 );
add_action('init', 'qpp_init');
add_action('wp_enqueue_scripts','qpp_enqueue_scripts');

if (is_admin()) require_once( plugin_dir_path( __FILE__ ) . '/settings.php' );

function qpp_init() {
	qpp_create_css_file ('');
	}
function qpp_enqueue_scripts() {
	wp_enqueue_script( 'qpp_script',plugins_url('quick-paypal-payments.js', __FILE__));
	wp_enqueue_style( 'qpp_style',plugins_url('quick-paypal-payments.css', __FILE__));
	wp_enqueue_style( 'qpp_custom',plugins_url('quick-paypal-payments-custom.css', __FILE__));
	}
function qpp_create_css_file ($update) {
	if (function_exists(file_put_contents)) {
		$css_dir = plugin_dir_path( __FILE__ ) . '/quick-paypal-payments-custom.css' ;
		$filename = plugin_dir_path( __FILE__ );
		if (is_writable($filename) && (!file_exists($css_dir) || !empty($update))) {
			$data = qpp_generate_css();
			file_put_contents($css_dir, $data, LOCK_EX);
			}
		}
	else add_action('wp_head', 'qpp_head_css');
	}
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
	$arr = array('amount','reference','quantity','stock');
	foreach ($arr as $item) $formvalues[$item] = filter_var($formvalues[$item], FILTER_SANITIZE_STRING);
	if (!$formvalues['pay']) if ($formvalues['amount'] == $qpp['inputamount'] || empty($formvalues['amount'])) $errors = 'first';
	if (!$formvalues['id']) if ($formvalues['reference'] == $qpp['inputreference'] || empty($formvalues['reference'])) $errors = 'second';
    if($qpp['captcha'] == 'checked') {
		$formvalues['maths'] = strip_tags($formvalues['maths']); 
		if($formvalues['maths']<>$formvalues['answer']) $errors = 'maths';
		if(empty($formvalues['maths'])) $errors = 'maths'; 
		}
	return $errors;
	}
function qpp_display_form( $values, $errors, $id ) {
	$qpp_form = qpp_get_stored_setup();
	$qpp = qpp_get_stored_options($id);
	$error = qpp_get_stored_error($id);
    $coupon = qpp_get_stored_coupon($id);
	$send = qpp_get_stored_send($id);
	$style = qpp_get_stored_style($id);
    global $_GET;
    if(isset($_GET["coupon"])) {$values['couponblurb'] = $_GET["coupon"];$values['couponget']=$coupon['couponget'];}
	if ($id) $formstyle=$id; else $formstyle='default';
	if (!empty($qpp['title'])) $qpp['title'] = '<h2>' . $qpp['title'] . '</h2>';
	if (!empty($qpp['blurb'])) $qpp['blurb'] = '<p>' . $qpp['blurb'] . '</p>';
	$content = "<div class='qpp-style ".$formstyle."'>\r\t
		<div id='".$style['border']."'>\r\t
		<div id='place".$id."'>";
	if ($errors) $content .= "<h2>" . $error['errortitle'] . "</h2>\r\t<p style='color: #D31900;margin: 4px 0;'>" . $error['errorblurb'] . "</p>\r\t";
	else {
		$content .= $qpp['title'];
		if ($qpp['paypal-url'] && $qpp['paypal-location'] == 'imageabove') $content .= "<img src='".$qpp['paypal-url']."' />";
		$content .=  $qpp['blurb'] . "\r\t";}
	$content .= '</div><div id="replacements" style="display:none"><span id="rep_place'.$id.'"><h2>'.$send['waiting'].'</h2></span></div>';
	$content .= '<form id="frmPayment" name="frmPayment" method="post" action="">';
	foreach (explode( ',',$qpp['sort']) as $name) {
		switch ( $name ) {
			case 'field1':
				if (empty($values['id'])) $content .= '<p><input type="text" label="Reference" name="reference" value="' . $values['reference'] . '" onfocus="qppclear(this, \'' . $values['reference'] . '\')" onblur="qpprecall(this, \'' . $values['reference'] . '\')"/></p>';
				else {
					if ($values['explode']) {
						$checked = 'checked';$ref = explode(",",$values['reference']);
						$content .= '<p class="payment" >'.$qpp['shortcodereference'].'<br>';
						foreach ($ref as $item) { $content .=  '<label><input type="radio" style="margin:0; padding: 0; border:none;width:auto;" name="reference" value="' .  $item . '" ' . $checked . '> ' .  $item . '</label><br>';$checked='';}
						$content .= '</p>';}
					else $content .= '<p class="input" >'.$values['reference'].'</p><input type="hidden" name="id" value="' . $values['id'] . '" />';
					}
				break;	
				case 'field2':
					if ($qpp['use_stock']) {$content .= '<p><input type="text" label="stock" name="stock" value="' . $values['stock'] . '" onfocus="qppclear(this, \'' . $values['stock'] . '\')" onblur="qpprecall(this, \'' . $values['stock'] . '\')"/></p>';}
					break;	
				case 'field3':
					if ($qpp['use_quantity']) {$content .= '<p><span class="input">'.$qpp['quantitylabel'].'</span><input type="text" style="width:3em;margin-left:5px" label="quantity" name="quantity" value="' . $values['quantity'] . '" onfocus="qppclear(this, \'' . $values['quantity'] . '\')" onblur="qpprecall(this, \'' . $values['quantity'] . '\')"/></p>';}
					break;
				case 'field4':
					if (empty($values['pay'])) {$content .= '<p><input type="text" label="Amount" name="amount" value="' . $values['amount'] . '" onfocus="qppclear(this, \'' . $values['amount'] . '\')" onblur="qpprecall(this, \'' . $values['amount'] . '\')"/></p>';}
					else {
					if ($values['explodepay']) {
						$checked = 'checked';$ref = explode(",",$values['amount']);
						$content .= '<p class="payment" >'.$qpp['shortcodeamount'].'<br>';
						foreach ($ref as $item) { $content .=  '<label><input type="radio" style="margin:0; padding: 0; border:none;width:auto;" name="amount" value="' .  $item . '" ' . $checked . '> ' .  $item . '</label><br>';$checked='';}
						$content .= '</p>';}
					else $content .= '<p class="input" >' . $values['amount'] . '</p><input type="hidden" name="amount" value="'.$values['amount'].'" />';
					}
					break;
				case 'field5':
					if ($qpp['use_options']) {
					$content .= '<p class="input">' . $qpp['optionlabel'] . '</p><p>';
					$arr = explode(",",$qpp['optionvalues']);
					foreach ($arr as $item) {
						$checked = '';
						if ($values['option1'] == $item) $checked = 'checked';
						if ($item === reset($arr)) $content .= '<input type="radio" style="margin:0; padding: 0; border: none" name="option1" value="' .  $item . '" id="' .  $item . '" checked><label for="' .  $item . '"> ' .  $item . '</label><br>';
						else $content .=  '<input type="radio" style="margin:0; padding: 0; border: none" name="option1" value="' .  $item . '" id="' .  $item . '" ' . $checked . '><label for="' .  $item . '"> ' .  $item . '</label><br>';
						}
					$content .= '</p>';
					}
					break;
				case 'field6':
					if ($qpp['usepostage']) {
					$content .= '<p class="input" >'.$qpp['postageblurb'].'</p>';
					}
					break;
				case 'field7':
					if ($qpp['useprocess']) {
					$content .= '<p class="input" >'.$qpp['processblurb'].'</p>';
					}
					break;
                case 'field8':
					if ($qpp['captcha']) {
		if (!empty($qpp['mathscaption'])) $content .= '<p class="input">' . $qpp['mathscaption'] . '</p>';
		$content .= '<p>' . strip_tags($values['thesum']) . ' = <input type="text" style="width:3em; font-size:100%" label="Sum" name="maths"  value="' . $values['maths'] . '"></p> 
			<input type="hidden" name="answer" value="' . strip_tags($values['answer']) . '" />
			<input type="hidden" name="thesum" value="' . strip_tags($values['thesum']) . '" />';
			}		
					break;
            case 'field9':
					if ($qpp['usecoupon'] && $values['couponapplied']) $content .= '<p>'.$qpp['couponref'].'</p>';
                    if ($qpp['usecoupon'] && !$values['couponapplied']){$content .= '<p>'.$values['couponget'].'
<input type="text" label="coupon" name="couponblurb" value="' . $values['couponblurb'] . '" onfocus="qppclear(this, \'' . $values['couponblurb'] . '\')" onblur="qpprecall(this, \'' . $values['couponblurb'] . '\')"/></p>
                    <p><input type="submit" value="'.$qpp['couponbutton'].'" id="submitcoupon" name="qppapply'.$id.'" /></p>';}
					$content .= '<input type="hidden" name="couponapplied" value="'.$values['couponapplied'].'" />';
                    break;	
			}
		}	
    
	$caption = $qpp['submitcaption'];
	if ($style['submit-button']) $content .= '<p><input type="image" value="' . $caption . '" style="border:none;width:100%;height:auto;overflow:hidden;" src="'.$style['submit-button'].'" name="qppsubmit'.$id.'" onClick="replaceContentInContainer(\'place'.$id.'\', \'rep_place'.$id.'\')"/>';
	else $content .= '<p><input type="submit" value="' . $caption . '" id="submit" name="qppsubmit'.$id.'" onClick="replaceContentInContainer(\'place'.$id.'\', \'rep_place'.$id.'\')"/></p>';
	$content .= '</form>'."\r\t";
	if ($qpp['paypal-url'] && $qpp['paypal-location'] == 'imagebelow') $content .= '<img src="'.$qpp['paypal-url'].'" />';
	$content .= '<div style="clear:both;"></div></div></div>'."\r\t";
	echo $content;
	}

function qpp_process_form($values,$id) {
	$currency = qpp_get_stored_curr();
	$qpp = qpp_get_stored_options($id);
	$send = qpp_get_stored_send($id);
    $coupon = qpp_get_stored_coupon($id);
	$style = qpp_get_stored_style($id);
	$qpp_setup = qpp_get_stored_setup();
	$page_url = qpp_current_page_url();
	if ($qpp_setup['sandbox']) $paypalurl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	else $paypalurl = 'https://www.paypal.com/cgi-bin/webscr';
	if (empty($send['thanksurl'])) $send['thanksurl'] = $page_url;
	if (empty($send['cancelurl'])) $send['cancelurl'] = $page_url;
	if ($send['target'] == 'newpage') $target = ' target="_blank" ';
    $curr = ($currency[$id] == '' ? 'USD' : $currency[$id]);
	$check = preg_replace ( '/[^.,0-9]/', '', $values['amount']);
    $decimal = array('HKD','JPY','MYR','TWD');$d='2';
    foreach ($decimal as $item) if ($item == $currency[$id]) $d ='0';
    $check = number_format($check, $d,'.','');
    $quantity =($values['quantity'] < 1 ? '1' : strip_tags($values['quantity']));
   	if ($qpp['useprocess'] && $qpp['processtype'] == 'processpercent') {
		$percent = preg_replace ( '/[^.,0-9]/', '', $qpp['processpercent']) / 100;
		$handling = $check * $quantity * $percent;}
	if ($qpp['useprocess'] && $qpp['processtype'] == 'processfixed') {
		$handling = preg_replace ( '/[^.,0-9]/', '', $qpp['processfixed']);}
	if ($qpp['usepostage'] && $qpp['postagetype'] == 'postagepercent') {
		$percent = preg_replace ( '/[^.,0-9]/', '', $qpp['postagepercent']) / 100;
		$packing = $check * $quantity * $percent;}
	if ($qpp['usepostage'] && $qpp['postagetype'] == 'postagefixed') {
		$packing = preg_replace ( '/[^.,0-9]/', '', $qpp['postagefixed']);}	
	$handling = number_format($handling,$d);
	$packing = number_format($packing,$d);
    $qpp_messages = get_option('qpp_messages'.$id);
	if(!is_array($qpp_messages)) $qpp_messages = array();
	$sentdate = date_i18n('d M Y');
    $amounttopay = $check * $quantity + $handling + $packing;
	$qpp_messages[] = array(
		'field0'=>$sentdate,
		'field1' => $values['reference'] ,
		'field2' => $values['quantity'],
		'field3' => $amounttopay,
		'field4' => $values['stock'],
		'field5' => $values['option1'],
		'field6' => $couponcode,
		'date' => $sentdate,);
	update_option('qpp_messages'.$id,$qpp_messages);
	$content = '<h2>'.$send['waiting'].'</h2>
        <form action="'.$paypalurl.'" method="post" name="frmCart" id="frmCart" ' . $target . '>
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="upload" value="1">
        <input type="hidden" name="business" value="' . $qpp_setup['email'] . '">
        <input type="hidden" name="return" value="' .  $send['thanksurl'] . '">
        <input type="hidden" name="cancel_return" value="' .  $send['cancelurl'] . '">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="currency_code" value="' .  $curr . '">
        <input type="hidden" name="item_name_1" value="' .$qpp['inputreference'] . ': ' . strip_tags($values['reference']);
    if ($qpp['use_options']) $content .= ' - '.strip_tags($values['option1']);
    $content .= '" />';
	if ($qpp['use_stock']) $content .= '<input type="hidden" name="item_number_1" value="' .$qpp['stocklabel'] . ': ' . strip_tags($values['stock']) . '">';
	$content .= '<input type="hidden" name="quantity_1" value="' . $quantity . '">
		<input type="hidden" name="amount_1" value="' . $check . '">';
	if ($qpp['useprocess']) $content .='<input type="hidden" name="item_name_2" value="'.$qpp['processref'].'">
		<input type="hidden" name="amount_2" value="' . $handling . '">';
	if ($qpp['usepostage']) $content .='<input type="hidden" name="item_name_3" value="'.$qpp['postageref'].'">
		<input type="hidden" name="amount_3" value="' . $packing . '">';
    if ($send['use_lc']) $content .= '<input type="hidden" name="lc" value="' . $send['lc'] . '"><input type="hidden" name="country" value="' . $send['lc'] . '">';
	$content .='</form>
	<script language="JavaScript">document.getElementById("frmCart").submit();</script>';
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

function qpp_currency ($id) {
    $currency = qpp_get_stored_curr();
    $c = array();
	$before = array(
        'USD'=>'&#x24;',
        'CDN'=>'&#x24;',
        'EUR'=>'&euro;',
        'GBP'=>'&pound;',
        'JPY'=>'&yen;',
        'AUD'=>'&#x24;',
        'BRL'=>'R&#x24;',
        'HKD'=>'&#x24;',
        'ILS'=>'&#x20aa;',
        'MXN'=>'&#x24;',
        'NZD'=>'&#x24;',
        'PHP'=>'&#8369;',
        'SGD'=>'&#x24;',
        'TWD'=>'NT&#x24;',
        'TRY'=>'&pound;');
    $after = array(
        'CZK'=>'K&#269;',
        'DKK'=>'Kr',
        'HUF'=>'Ft',
        'MYR'=>'RM',
        'NOK'=>'kr',
        'PLN'=>'z&#322',
        'RUB'=>'&#1056;&#1091;&#1073;',
        'SEK'=>'kr',
        'CHF'=>'CHF',
        'THB'=>'&#3647;');
    foreach($before as $item=>$key) {if ($item == $currency[$id]) $c['b'] = $key;}
    foreach($after as $item=>$key) {if ($item == $currency[$id]) $c['a'] = $key;}
    return $c;
    }

function qpp_loop($atts) {
	ob_start();
	extract(shortcode_atts(array( 'form' =>'','amount' => '' , 'id' => '','stock' => '', 'labels' => ''), $atts));
	$qpp = qpp_get_stored_options($form);
    global $_GET;
    if(isset($_GET["reference"])) {$id = $_GET["reference"];}
    if(isset($_GET["amount"])) {$amount = $_GET["amount"];}
    $formvalues['quantity'] = 1;
	$formvalues['stock'] = $qpp['stocklabel'].' ';
    $formvalues['couponblurb'] = $qpp['couponblurb'];
           
    if (!$labels) {
        $shortcodereference = $qpp['shortcodereference'].' ';
        $shortcodeamount = $qpp['shortcodeamount'].' ';
        }
    
    if ($id) {
		$formvalues['id'] = 'checked';
		if (strrpos($id,',')) {$formvalues['reference'] = $id;$formvalues['explode'] = 'checked';}
		else $formvalues['reference'] = $shortcodereference.$id;
		}
	else {$formvalues['reference'] = $qpp['inputreference'];$formvalues['id'] = '';}
	
    if ($amount) {
        $formvalues['pay'] = 'checked';
        if (strrpos($amount,',')) {$formvalues['amount'] = $amount;$formvalues['explodepay'] = 'checked';}
        else $formvalues['amount'] = $shortcodeamount.$amount;
        }
	else {$formvalues['amount'] = $qpp['inputamount'];$formvalues['pay'] = '';}
    
    if (isset($_POST['qppapply'.$form]) || isset($_POST['qppsubmit'.$form]) || isset($_POST['qppsubmit'.$form.'_x'])) {
        $_POST = qpp_sanitize($_POST);
        if (isset($_POST['reference'])) $id = $_POST['reference'];
        if (isset($_POST['amount'])) $amount = $_POST['amount'];
        $arr = array('reference','amount','stock','quantity','option1','couponblurb','maths','thesum','answer');
        foreach($arr as $item) if (isset($_POST[$item])) $formvalues[$item] = $_POST[$item];
        }
    
    if (isset($_POST['qppapply'.$form])) {
    	$check = preg_replace ( '/[^.,0-9]/', '', $formvalues['amount']); 
            $coupon = qpp_get_stored_coupon($form);
            $c = qpp_currency ($form);
            for ($i=1; $i<=10; $i++) {
                if ($formvalues['couponblurb'] == $coupon['code'.$i]) {
                    if ($coupon['coupontype'.$i] == 'percent'.$i) $check = $check - ($check * $coupon['couponpercent'.$i]/100);
                    if ($coupon['coupontype'.$i] == 'fixed'.$i) $check = $check - $coupon['couponfixed'.$i];
                    if ($check > 0) {
                        $formvalues['couponapplied'] = 'checked';
                        $formvalues['pay'] = 'checked';
                        $formvalues['amount'] = $shortcodeamount.$c['b'].$check.$c['a'];
                    } else $formvalues['couponblurb'] = $qpp['couponblurb'];
                }
            }
        }
    
    if (isset($_POST['qppsubmit'.$form]) || isset($_POST['qppsubmit'.$form.'_x'])) {
		if (qpp_verify_form($formvalues,$form)) qpp_display_form($formvalues,'error',$form);
   		else {
			if ($amount) $formvalues['amount'] = $amount;
			if ($id) $formvalues['reference'] = $id;
			qpp_process_form($formvalues,$form);
			}
		}
	 
    else {	$digit1 = mt_rand(1,10);
		$digit2 = mt_rand(1,10);
		if( $digit2 >= $digit1 ) {
		$formvalues['thesum'] = "$digit1 + $digit2";
		$formvalues['answer'] = $digit1 + $digit2;
		} else {
		$formvalues['thesum'] = "$digit1 - $digit2";
		$formvalues['answer'] = $digit1 - $digit2;
		}
        qpp_display_form($formvalues,'',$form);
		}
	$output_string=ob_get_contents();
	ob_end_clean();
	return $output_string;
	}

function qpp_sanitize($input) {
    if (is_array($input)) foreach($input as $var=>$val) $output[$var] = filter_var($val, FILTER_SANITIZE_STRING);
    return $output;
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
        $form=$instance['form'];
		echo qpp_loop($instance);
	   }
 	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['id'] = $new_instance['id'];
		$instance['amount'] = $new_instance['amount'];
        		$instance['form'] = $new_instance['form'];
		return $instance;
		}
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'amount' => '' , 'id' => '','form' => '' ) );
		$id = $instance['id'];
		$amount = $instance['amount'];
		$form=$instance['form'];
		$qpp_setup = qpp_get_stored_setup();
		?>
		<h3>Select Form:</h3>
		<select class="widefat" name="<?php echo $this->get_field_name('form'); ?>">
		<?php
		$arr = explode(",",$qpp_setup['alternative']);
		foreach ($arr as $item) {
			if ($item == '') {$showname = 'default'; $item='';} else $showname = $item;
			if ($showname == $form || $form == '') $selected = 'selected'; else $selected = '';
			?><option value="<?php echo $item; ?>" id="<?php echo $this->get_field_id('form'); ?>" <?php echo $selected; ?>><?php echo $showname; ?></option><?php } ?>
		</select>
		<h3>Presets:</h3>
		<p><label for="<?php echo $this->get_field_id('id'); ?>">Payment Reference: <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo attribute_escape($id); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('amount'); ?>">Amount: <input class="widefat" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" type="text" value="<?php echo attribute_escape($amount); ?>" /></label></p>
		<p>Leave blank to use the default settings.</p>
		<p>To configure the payment form use the <a href="'.get_admin_url().'options-general.php?page=quick-paypal-payments/quick-paypal-payments.php">Settings</a> page</p>
		<?php
		}
	}
function qpp_generate_css() {
	$qpp_form = qpp_get_stored_setup();
	$arr = explode(",",$qpp_form['alternative']);
	foreach ($arr as $item) {
		$corners='';$input='';$background='';$paragraph='';$submit='';
		$style = qpp_get_stored_style($item);
		if ($item !='') $id = '.'.$item; else $id = '.default';
		if ($style['font'] == 'plugin') {
            $font = "font-family: ".$style['text-font-family']."; font-size: ".$style['text-font-size'].";color: ".$style['text-font-colour'].";line-height:100%;";
			$inputfont = "font-family: ".$style['font-family']."; font-size: ".$style['font-size']."; color: ".$style['font-colour'].";";
			$submitfont = "font-family: ".$style['font-family'];
			if ($style['header']) $header = ".qpp-style".$id." h2 {font-size: ".$style['header-size']."; color: ".$style['header-colour'].";}";
			}
		$input = ".qpp-style".$id." input[type=text]{border: ".$style['input-border'].";".$inputfont.";font-size: inherit;}\r\n";
		$paragraph = ".qpp-style".$id." p{".$font.";}\r\n";
		if ($style['submitwidth'] == 'submitpercent') $submitwidth = 'width:100%;';
		if ($style['submitwidth'] == 'submitrandom') $submitwidth = 'width:auto;';
		if ($style['submitwidth'] == 'submitpixel') $submitwidth = 'width:'.$style['submitwidthset'].';';
		if ($style['submitposition'] == 'submitleft') $submitposition = 'float:left;'; else $submitposition = 'float:right;';
		$submitbutton = ".qpp-style".$id." #submit, .qpp-style".$id." #submit:hover{".$submitposition.$submitwidth."color:".$style['submit-colour'].";background:".$style['submit-background'].";border:".$style['submit-border'].";".$submitfont.";font-size: inherit;}\r\n";
        $couponbutton = ".qpp-style".$id." #couponsubmit, .qpp-style".$id." #couponsubmit:hover{".$submitposition.$submitwidth."color:".$style['coupon-colour'].";background:".$style['coupon-background'].";border:".$style['submit-border'].";".$submitfont.";font-size: inherit;}\r\n";
		if ($style['border']<>'none') $border =".qpp-style".$id." #".$style['form-border']." {border:".$style['form-border'].";}\r\n";
		if ($style['background'] == 'white') $background = ".qpp-style".$id." div {background:#FFF;}\r\n";
		if ($style['background'] == 'color') $background = ".qpp-style".$id." div {background:".$style['backgroundhex'].";}\r\n";
		if ($style['backgroundimage']) $background = ".qpp-style".$id." #".$style['border']." {background: url('".$style['backgroundimage']."');}\r\n";
		if ($style['widthtype'] == 'pixel') $width = preg_replace("/[^0-9]/", "", $style['width']) . 'px';
		else $width = '100%';
		if ($style['corners'] == 'round') $corner = '5px'; else $corner = '0';
		$corners = ".qpp-style".$id." input[type=text], .qpp-style".$id." textarea, .qpp-style".$id." select, .qpp-style".$id." #submit {border-radius:".$corner.";}\r\n";
		if ($style['corners'] == 'theme') $corners = '';
		$code .= ".qpp-style".$id." {width:".$width.";}\r\n".$border.$corners.$header.$paragraph.$input.$background.$submitbutton.$couponbutton;
		if ($style['use_custom'] == 'checked') $code .= $style['styles'] . "\r\n";
		}
	return $code;
	}
function qpp_head_css () {
	$data = '<style type="text/css" media="screen">'.qpp_generate_css().'</style>';
	echo $data;
	}

function qpp_report($atts) {
	extract(shortcode_atts(array( 'form' =>''), $atts));
	return qpp_messagetable($form);
	}
function qpp_messagetable ($id) {
	$options = qpp_get_stored_options ($id);
	$message = get_option('qpp_messages'.$id);
	$messageoptions = qpp_get_stored_msg();
	$c = qpp_currency ($id);
	$showthismany = '9999';
	if ($messageoptions['messageqty'] == 'fifty') $showthismany = '50';
	if ($messageoptions['messageqty'] == 'hundred') $showthismany = '100';
	$$messageoptions['messageqty'] = "checked";
	$$messageoptions['messageorder'] = "checked";
	if(!is_array($message)) $message = array();
	$title = $id; if ($id == '') $title = 'Default';
	$dashboard .= '<div class="wrap"><div id="qpp-widget">';
	$dashboard .= '<table cellspacing="0"><tr><th>Date Sent</th>';
	foreach (explode( ',',$options['sort']) as $name) {
	$title='';
		switch ( $name ) {
			case 'field1': $title=$options['inputreference'];break;
			case 'field2': $title=$options['quantitylabel'];break;
			case 'field3': $title=$options['inputamount'];break;
			case 'field4': if ($options['usestock']) $title=$options['stock'];break;
			case 'field5': if ($options['use_options']) $title=$options['optionlabel'];break;
			case 'field6': if ($options['usecoupon']) $title=$options['couponblurb'];break;
			}
		$dashboard .= '<th>'.$title.'</th>';
		}
	$dashboard .= '</tr>';
	if ($messageoptions['messageorder'] == 'newest') {
        foreach(array_reverse( $message ) as $value) {
            if ($count < $showthismany ) {
                if ($value['date']) $report = 'messages';
                $content .= qpp_messagecontent ($id,$value,$options,$c);
                $content .='</tr>';
                $count = $count+1;
            }
        }
	}
	else {
        foreach($message as $value) {
		if ($count < $showthismany ) {
            if ($value['date']) $report = 'messages';
			$content .= $content .= qpp_messagecontent ($id,$value,$options,$c);
			$content .='</tr>';
			$count = $count+1;
			}
		}
	}	
	if ($report) $dashboard .= $content.'</table>';
	else $dashboard .= '</table><p>No messages found</p>';
	return $dashboard;
	}
function qpp_messagecontent ($id,$value,$options,$c) {
	$content .= '<tr><td>'.strip_tags($value['field0']).'</td>';
	foreach (explode( ',',$options['sort']) as $name) {
		$title='';
		$amount = preg_replace ( '/[^.,0-9]/', '', $value['field3']);                 
		switch ( $name ) {
            case 'field1': $title=$value['field1'];break;
			case 'field2': $title=$value['field2'];break;
			case 'field3': $title=$c['b'].$amount.$c['a'];break;
			case 'field4': if ($options['usestock']) $title=$value['field4'];break;
			case 'field5': if ($options['use_options']) $title=$value['field5'];break;
			case 'field6': if ($options['usecoupon']) $title=$value['field6'];break;
			}
		$content .= '<td>'.$title.'</td>';
		}
	return $content;	
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
	$qpp_curr[''] = 'USD';
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
	$qpp = get_option('qpp_options'.$id);
	if(!is_array($qpp)) $qpp = array();
	$default = qpp_get_default_options();
	$qpp = array_merge($default, $qpp);
    if (!strpos($qpp['sort'],'field6')) {$qpp['sort'] = $qpp['sort'].',field6';$update = 'update';}
    if (!strpos($qpp['sort'],'field7')) {$qpp['sort'] = $qpp['sort'].',field7';$update = 'update';}
    if (!strpos($qpp['sort'],'field8')) {$qpp['sort'] = $qpp['sort'].',field8';$update = 'update';}
    if (!strpos($qpp['sort'],'field9')) {$qpp['sort'] = $qpp['sort'].',field9';$update = 'update';}
    if ($qpp['processtype'] == 'fixed') {$qpp['processtype'] = 'processfixed';$update = 'update';}
    if ($qpp['processtype'] == 'percent') {$qpp['processtype'] = 'processpercent';$update = 'update';}
    if ($qpp['postagetype'] == 'fixed') {$qpp['postagetype'] = 'postagefixed';$update = 'update';}
    if ($qpp['postagetype'] == 'percent') {$qpp['postagetype'] = 'postagepercent';$update = 'update';}
    if ($update) update_option('qpp_options'.$id,$qpp);
	return $qpp;
	}
function qpp_get_default_options () {
	$qpp = array();
	$qpp['sort'] = 'field1,field4,field2,field3,field5,field6,field7,field9,field8';
    $qpp['title'] = 'Payment Form';
	$qpp['blurb'] = 'Enter the payment details and submit';
	$qpp['inputreference'] = 'Payment reference';
	$qpp['inputamount'] = 'Amount to pay';
	$qpp['quantitylabel'] = 'Quantity';
	$qpp['quantity'] = '1';
	$qpp['stocklabel'] = 'Item Number';
	$qpp['use_stock'] = '';
	$qpp['optionlabel'] = 'Options';
	$qpp['optionvalues'] = 'Large,Medium,Small';
	$qpp['use_options'] = '';
	$qpp['shortcodereference'] = 'Payment for: ';
	$qpp['shortcodeamount'] = 'Amount: ';
	$qpp['paypal-location'] = 'imagebelow';
	$qpp['captcha'] = '';
	$qpp['mathscaption'] = 'Spambot blocker question';
	$qpp['submitcaption'] = 'Make Payment';
	$qpp['useprocess'] = '';
	$qpp['processblurb'] = 'A processing fee will be added before payment';
	$qpp['processref'] = 'Processing Fee';
	$qpp['processtype'] = 'processpercent';
	$qpp['processpercent'] = '5';
	$qpp['processfixed'] = '2';
	$qpp['usepostage'] = '';
	$qpp['postageblurb'] = 'Post and Packing will be added before payment';
	$qpp['postageref'] = 'Post and Packing';
	$qpp['postagetype'] = 'postagefixed';
	$qpp['postagepercent'] = '5';
	$qpp['postagefixed'] = '5';
    $qpp['usecoupon'] = '';
	$qpp['couponblurb'] = 'Enter coupon code';
	$qpp['couponref'] = 'Coupon Applied';
	$qpp['couponbutton'] = 'Apply Coupon';
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
	$style['font-size'] = '1em';
	$style['font-colour'] = '#465069';
	$style['header'] = '';
	$style['header-size'] = '1.6em';
	$style['header-colour'] = '#465069';
	$style['text-font-family'] = 'arial, sans-serif';
	$style['text-font-size'] = '1em';
	$style['text-font-colour'] = '#465069';
	$style['width'] = 280;
	$style['form-border'] = '1px solid #415063';
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
	$style['submit-border'] = '1px solid #415063';
	$style['submitwidth'] = 'submitpercent';
	$style['submitposition'] = 'submitleft';
    $style['coupon-colour'] = '#FFF';
	$style['coupon-background'] = '#1f8416';
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

function qpp_get_stored_coupon ($id) {
	$coupon = get_option('qpp_coupon'.$id);
	if(!is_array($coupon)) $coupon = array();
	$default = qpp_get_default_coupon();
	$coupon = array_merge($default, $coupon);
	return $coupon;
	}
function qpp_get_default_coupon () {
    for ($i=1; $i<=10; $i++) {
        $coupon['couponget'] = 'Coupon Code:';
        $coupon['coupontype'.$i] = 'percent'.$i;
        $coupon['couponpercent'.$i] = '10';
        $coupon['couponfixed'.$i] = '5';
        }
return $coupon;
}
