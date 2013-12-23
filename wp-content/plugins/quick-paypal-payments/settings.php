<?php
add_action('init', 'qpp_init');
add_action('admin_menu', 'qpp_page_init');
add_action('admin_notices', 'qpp_admin_notice' );
add_action( 'admin_menu', 'qpp_admin_pages' );
add_action( 'admin_enqueue_scripts', 'qpp_admin_pointers_header' );

wp_enqueue_style('qpp_settings',plugins_url('settings.css', __FILE__));

register_uninstall_hook(__FILE__, 'delete_everything');

function qpp_admin_pages() {
	add_menu_page('Payments', 'Payments', 'manage_options','quick-paypal-payments/quick-paypal-messages.php');
	}
function qpp_page_init() {
	add_options_page('Paypal Payments', 'Paypal Payments', 'manage_options', __FILE__, 'qpp_tabbed_page');
	}
function qpp_admin_tabs($current = 'settings') { 
	$tabs = array( 'setup' => 'Setup' , 'settings' => 'Form Settings', 'styles' => 'Styling' , 'send' => 'Send Options' , 'error' => 'Error Messages' , 'shortcodes' => 'Shortcodes' , 'reset' => 'Reset' , ); 
	$links = array();  
	echo '';
	echo '<h2 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ) {
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=quick-paypal-payments/settings.php&tab=$tab'>$name</a>";
		}
	echo '</h2>';
	}
function qpp_tabbed_page() {
	$qpp_setup = qpp_get_stored_setup();
	$id=$qpp_setup['current'];
	qpp_options_css();
	echo '<div class="wrap">';
	echo '<h1>Quick Paypal Payments</h1>';
	if ( isset ($_GET['tab'])) {qpp_admin_tabs($_GET['tab']); $tab = $_GET['tab'];} else {qpp_admin_tabs('setup'); $tab = 'setup';}
	switch ($tab) {
		case 'setup' : qpp_setup($id); break;
		case 'settings' : qpp_form_options($id); break;
		case 'styles' : qpp_styles($id); break;
		case 'send' : qpp_send_page($id); break;
		case 'error' : qpp_error_page ($id); break;
		case 'shortcodes' : qpp_shortcodes (); break;
		case 'reset' : qpp_reset_page($id); break;
		}
	echo '</div>';
	}
function qpp_setup ($id) {
	if( isset( $_POST['Submit'])) {
		$qpp_setup['alternative'] = $_POST['alternative'];
		$qpp_setup['email'] = $_POST['email'];
		if (!empty($_POST['new_form'])) {
			$qpp_setup['current'] = stripslashes($_POST['new_form']);
			$qpp_setup['current'] = str_replace(' ','',$qpp_setup['current']);
			$qpp_setup['alternative'] = $qpp_setup['current'].','.$qpp_setup['alternative'];
			}
		else $qpp_setup['current'] = $_POST['current'];
		if (empty($qpp_setup['current'])) $qpp_setup['current'] = '';
		$arr = explode(",",$qpp_setup['alternative']);
		foreach ($arr as $item) $qpp_curr[$item] = stripslashes($_POST['qpp_curr'.$item]);
		if (!empty($_POST['new_form'])) {
			$email = $qpp_setup['current'];
			$qpp_curr[$email] = stripslashes($_POST['new_curr']);}
		$qpp_setup['dashboard'] = $_POST['dashboard'];
		update_option( 'qpp_curr', $qpp_curr);
		update_option( 'qpp_setup', $qpp_setup);
		qpp_admin_notice("The forms have been updated.");	
		}
	$qpp_setup = qpp_get_stored_setup();
	$qpp_curr = qpp_get_stored_curr();
	$content ='<div class="qpp-settings"><div class="qpp-options" style="margin-right:10px;">
		<form method="post" action="">
		<h2>Account Email</h2>
		<p><span style="color:red; font-weight: bold; margin-right: 3px">Important!</span> Enter your PAYPAL email address</p>
		<input type="text" style="width:100%" label="Email" name="email" value="' . $qpp_setup['email'] . '" /></p>
		<h2 style="color:#B52C00">Existing Forms</h2>
		<table>
		<tr><td><b>Form name&nbsp;&nbsp;</b></td><td><b>Currency&nbsp;&nbsp;</b></td><td><b>Shortcode</b></td></tr>';
	$arr = explode(",",$qpp_setup['alternative']);
	foreach ($arr as $item) {
		if ($qpp_setup['current'] == $item) $checked = 'checked'; else $checked = '';
		if ($item == '') $formname = 'default'; else $formname = $item;
		$content .='<tr><td><input style="margin:0; padding:0; border:none" type="radio" name="current" value="' .$item . '" ' .$checked . ' /> '.$formname.'</td>';
		$content .='<td><input type="text" style="width:6em;padding:1px;" label="qpp_curr" name="qpp_curr'.$item.'" value="' . $qpp_curr[$item].'" /></td>';
		if ($item) $shortcode = ' form="'.$item.'"'; else $shortcode='';
		$content .= '<td><code>[qpp'.$shortcode.']</code></td></tr>';
		}
	$content .= '</table><p>To delete or reset a form use the <a href="?page=quick-paypal-payments/settings.php&tab=reset">reset</a> tab.</p>
		<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Settings" /></p>		
		<h2>Create New Form</h2>
		<p>Enter form name (letters and numbers only - no spaces or punctuation marks)</p>
		<p><input type="text" style="width:100%" label="new_Form" name="new_form" value="" /></p>
		<p>Enter currency code: <input type="text" style="width:6em" label="new_curr" name="new_curr" value="'.$new_curr.'" />(For example: GBP, USD, EUR)</p>
		<p>Allowed Paypal Currency codes are given <a href="https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes" target="blank">here</a>.</p>
		<p><span style="color:red; font-weight: bold; margin-right: 3px">Important!</span> If your currency is not listed the plugin will work but paypal will not accept the payment.</p>
		<input type="hidden" name="alternative" value="' . $qpp_setup['alternative'] . '" />
		<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Create New Form" /></p>
		</form>
		</div>
		<div class="qpp-options" style="float:right"> 
		<h2>Adding the payment form to your site</h2>
		<p>To add the basic payment form to your posts or pages use the shortcode: <code>[qpp]</code>.<br />
		<p>If you have a named form the shortcode is <code>[qpp id="form name"]</code>.<br />
		<p>To add the form to your theme files use <code>&lt;?php echo do_shortcode("[qpp]"); ?&gt;</code></p>
		<p>There is also a widget called "Quick Paypal Payments" you can drag and drop into a sidebar.</p>
		<p>That\'s it. The payment form is ready to use.</p>
		<h2>Options and Settings</h2>
		<p>To change the layout of the form, add or remove fields and the order they appear and edit the labels and captions use the <a href="?page=quick-paypal-payments/settings.php&tab=settings">Form Settings</a> tab.</p>
		<p>Use the <a href="?page=quick-paypal-payments/settings.php&tab=reply">Send Options</a> tab to change the thank you message and how the form is sent.</p>
		<p>To change the way the form looks use the <a href="?page=quick-paypal-payments/settings.php&tab=styles">styling</a> tab.</p>
		<p>You can also customise the <a href="?page=quick-paypal-payments/settings.php&tab=error">error messages</a>.</p>
		<p>If it all goes wrong you can <a href="?page=quick-paypal-payments/settings.php&tab=reset">reset</a> everything.</p>
		<p>To see all your payment messages click on the <b>Payments</b> link in the dashboard menu or <a href="?page=quick-paypal-payments/quick-paypal-messages.php">click here</a>.</p>
		<h2>Version 3.2: What\'s New</h2>
		<p>Patched some security holes and added the option to display a PayPal logo on the form.</p>
		<p>Please send bug reports and questions to <a href="mailto:mail@quick-plugins.com">mail@quick-plugins.com</a>.</p>';	
		$content .= donate_loop();
		$content .= '</div>';
	echo $content;
	}
function qpp_form_options($id) {
	qpp_change_form_update($id);
	if( isset( $_POST['qpp_submit'])) {
		$options = array('title','blurb','inputreference','inputamount','shortcodereference','use_quantity','quantitylabel','shortcodeamount','shortcode_labels','submitcaption','cancelurl,','thanksurl','target','paypal-url','paypal-location');
		foreach ($options as $item) $qpp[$item] = stripslashes( $_POST[$item]);
		update_option('qpp_options'.$id, $qpp);
		qpp_admin_notice("The form and submission settings have been updated.");
		}
	if( isset( $_POST['Reset'])) {
		delete_option('qpp_options'.$id);
		qpp_admin_notice("The form and submission settings have been reset.");
		}
	$qpp_setup = qpp_get_stored_setup();
	$id=$qpp_setup['current'];
	$qpp = qpp_get_stored_options($id);
	$$qpp['paypal-location'] = 'checked';
	$content ='<div class="qpp-settings"><div class="qpp-options" style="margin-right:10px;">';
	if ($id) $content .='<h2 style="color:#B52C00">Form settings for ' . $id . '</h2>';
	else $content .='<h2 style="color:#B52C00">Default form settings</h2>';
	$content .= qpp_change_form($qpp_setup);
	$content .= '<form action="" method="POST">
		<p>Paypal form heading (optional)</p>
		<input type="text" style="width:100%" name="title" value="' . $qpp['title'] . '" />
		<p>This is the blurb that will appear below the heading and above the form (optional):</p>
		<input type="text" style="width:100%" name="blurb" value="' . $qpp['blurb'] . '" />
		<h2>Payment labels</h2>
		<p>Label for the payment Reference/ID/Number:</p>
		<input type="text" style="width:100%" name="inputreference" value="' . $qpp['inputreference'] . '" />
		<p><input type="checkbox" style="margin:0; padding: 0; border: none" name="use_quantity" ' . $qpp['use_quantity'] . ' value="checked" /> Use quantity field</p>
		<p>Label for the quantity field:</p>
		<input type="text" style="width:100%" name="quantitylabel" value="' . $qpp['quantitylabel'] . '" />
		<p>Label for the amount field:</p>
		<input type="text" style="width:100%" name="inputamount" value="' . $qpp['inputamount'] . '" />
		<h2>Shortcode labels</h2>
		<p>These are the labels that will display if you are using <a href="?page=quick-paypal-payments/settings.php&tab=help">shortcode attributes</a>.</p>
		<p>Label for the payment Reference/ID/Number:</p>
		<input type="text" style="width:100%" name="shortcodereference" value="' . $qpp['shortcodereference'] . '" />
		<p>Label for the amount field:</p>
		<input type="text" style="width:100%" name="shortcodeamount" value="' . $qpp['shortcodeamount'] . '" />
		<h2>Submit button caption</h2>
		<input type="text" style="width:100%" name="submitcaption" value="' . $qpp['submitcaption'] . '" />
		<h2>PayPal Image</h2>
		<p>Add a paypal image to your form. Enter the URL of the image below and slect where you want it to display.</p>
		<p>Below form title: <input type="radio" label="paypal-location" name="paypal-location" value="imageabove" ' . $imageabove . ' /> Below Submit Button: <input type="radio" label="paypal-location" name="paypal-location" value="imagebelow" ' . $imagebelow . ' /></p>
		<p>PayPal: <input type="text" style="width:25em" label="paypal-url" name="paypal-url" value="' . $qpp['paypal-url'] . '" /><br>
		Leave blank if you don\'t want to use an image</p>
	<p><input type="submit" name="qpp_submit" class="button-primary" style="color: #FFF;" value="Save Changes" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the form settings?\' );"/></p>
		</form>
		</div>
		<div class="qpp-options" style="float:right;">
		<h2 style="color:#B52C00">Form Preview</h2>
		<p>Note: The preview form uses the wordpress admin styles. Your form will use the theme styles so won\'t look exactly like the one below.</p>';
	$args = array('form' => $id, 'id' => '', 'amount' => '');
	$content .= qpp_loop($args);
	$content .= '</div></div>';
	echo $content;
	}
function qpp_styles($id) {
	qpp_change_form_update();
	if( isset( $_POST['Submit'])) {
		$options = array( 'font','font-family','font-size','font-colour','input-border','input-required','border','width','widthtype','background','backgroundhex','corners','custom','use_custom','usetheme','styles','submit-colour','submit-background','submit-button');
		foreach ( $options as $item) $style[$item] = stripslashes($_POST[$item]);
		update_option( 'qpp_style'.$id, $style);
		qpp_admin_notice("The form styles have been updated.");
		}
	if( isset( $_POST['Reset'])) {
		delete_option('qpp_style'.$id);
		qpp_admin_notice("The form styles have been reset.");
		}
	$qpp_setup = qpp_get_stored_setup();
	$id=$qpp_setup['current'];
	$style = qpp_get_stored_style($id);
	$$style['font'] = 'checked';
	$$style['widthtype'] = 'checked';
	$$style['border'] = 'checked';
	$$style['background'] = 'checked';
	$$style['corners'] = 'checked';
	$$style['styles'] = 'checked';
	qpp_options_css();
	$content ='<div class="qpp-settings"><div class="qpp-options" style="margin-right:10px;">';
	if ($id) $content .='<h2 style="color:#B52C00">Style options for ' . $id . '</h2>';
	else $content .='<h2 style="color:#B52C00">Default form style options</h2>';
	$content .= qpp_change_form($qpp_setup);
	$content .= '
		<form method="post" action=""> 
		<h2>Form Width</h2>
		<p>
			<input style="margin:0; padding:0; border:none;" type="radio" name="widthtype" value="percent" ' . $percent . ' /> 100% (fill the available space)<br />
			<input style="margin:0; padding:0; border:none;" type="radio" name="widthtype" value="pixel" ' . $pixel . ' /> Pixel (fixed)</p>
		<p>Enter the width of the form in pixels. Just enter the value, no need to add \'px\'. The current width is as you see it here.</p>
		<p><input type="text" style="width:4em" label="width" name="width" value="' . $style['width'] . '" /> px</p>
		<h2>Font Options</h2>
		<p>
			<input style="margin:0; padding:0; border:none" type="radio" name="font" value="theme" ' . $theme . ' /> Use your theme font styles<br />
			<input style="margin:0; padding:0; border:none" type="radio" name="font" value="plugin" ' . $plugin . ' /> Use Plugin font styles (enter font family and size below)</p>
		<table>
		<tr>
		<td>Font Family: </td><td><input type="text" style="width:15em" label="font-family" name="font-family" value="' . $style['font-family'] . '" /></td>
		<tr>
		</tr>
		<td>Font Size: </td><td><input type="text" style="width:6em" label="font-size" name="font-size" value="' . $style['font-size'] . '" /></td>
		<tr>
		</tr>
		<td>Font Colour: </td><td><input type="text" style="width:15em" label="font-colour" name="font-colour" value="' . $style['font-colour'] . '" /></td>
		</tr>
		</table>
		<h2>Form Border</h2>
		<p>Note: The rounded corners and shadows only work on CSS3 supported browsers and even then not in IE8. Don\'t blame me, blame Microsoft.</p>
		<p>
			<input style="margin:0; padding:0; border:none;" type="radio" name="border" value="none" ' . $none . ' /> No border<br />
			<input style="margin:0; padding:0; border:none;" type="radio" name="border" value="plain" ' . $plain . ' /> Plain Border<br />
			<input style="margin:0; padding:0; border:none;" type="radio" name="border" value="rounded" ' . $rounded . ' /> Round Corners (Not IE8)<br />
			<input style="margin:0; padding:0; border:none;" type="radio" name="border" value="shadow" ' . $shadow . ' /> Shadowed Border(Not IE8)<br />
			<input style="margin:0; padding:0; border:none;" type="radio" name="border" value="roundshadow" ' . $roundshadow . ' /> Rounded Shadowed Border (Not IE8)</p>
		<h2>Background colour</h2>
		<p>
			<input style="margin:0; padding:0; border:none;" type="radio" name="background" value="white" ' . $white . ' /> White<br />
			<input style="margin:0; padding:0; border:none;" type="radio" name="background" value="theme" ' . $theme . ' /> Use theme colours<br />
			<input style="margin:0; padding:0; border:none;" type="radio" name="background" value="color" ' . $color . ' /> Set your own (enter HEX code or color name below)</p>
		<p><input type="text" style="width:7em" label="background" name="backgroundhex" value="' . $style['backgroundhex'] . '" /></p>
		<h2>Input fields</h2>
		<h3>Borders</h3>
		<p>Style: <input type="text" style="width:15em" label="input-border" name="input-border" value="' . $style['input-border'] . '" /></p>
		<h3>Corners</h3>
		<p>
			<input style="margin:0; padding:0; border:none;" type="radio" name="corners" value="corner" ' . $corner . ' /> Use theme settings<br />
			<input style="margin:0; padding:0; border:none;" type="radio" name="corners" value="square" ' . $square . ' /> Square corners<br />
			<input style="margin:0; padding:0; border:none;" type="radio" name="corners" value="round" ' . $round . ' /> 5px rounded corners
		</p>
		<h2>Submit Button</h2>
		<table>
		<tr>
		<td>Font Colour: </td><td><input type="text" style="width:15em" label="submit-colour" name="submit-colour" value="' . $style['submit-colour'] . '" /></td>
		<tr>
		</tr>
		<td>Background: </td><td><input type="text" style="width:15em" label="submit-background" name="submit-background" value="' . $style['submit-background'] . '" /></td>
		<tr>
		</tr>
		<td>Button Image: </td><td><input type="text" style="width:25em" label="submit-button" name="submit-button" value="' . $style['submit-button'] . '" /><br>
		Leave blank if you don\'t want to use an image</td>
		</tr>
		</table>
		<h2>Custom CSS</h2>
		<p><input type="checkbox" style="margin:0; padding: 0; border: none" name="use_custom" ' . $style['use_custom'] . ' value="checked" /> Use Custom CSS</p>
		<p><textarea style="width:100%; height: 200px" name="custom">' . $style['custom'] . '</textarea></p>
		<p>To see all the styling use the <a href="'.get_admin_url().'plugin-editor.php?file=quick-paypal-payments/quick-paypal-payments.css">CSS editor</a>.</p>
		<p>The main style wrapper is the <code>.qpp-style</code> id.</p>
		<p>The form borders are: #none, #plain, #rounded, #shadow, #roundshadow.</p>
		<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Changes" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the form styles?\' );"/></p>
		</form>
		</div>
		<div class="qpp-options" style="float:right;"> <h2 style="color:#B52C00">Test Form</h2>
		<p>Not all of your style selections will display here (because of how WordPress works). So check the form on your site.</p>';
		$args = array('form' => $id, 'id' => '', 'amount' => '');
	$content .= qpp_loop($args);
	$content .= '</div></div>';
	echo $content;
	}
function qpp_send_page($id) {
	qpp_change_form_update();
	if( isset( $_POST['Submit'])) {
		$options = array('waiting','cancelurl','thanksurl','target');
		foreach ($options as $item) $send[$item] = stripslashes( $_POST[$item]);
		update_option('qpp_send'.$id, $send);
		qpp_admin_notice("The submission settings have been updated.");
		}
	if( isset( $_POST['Reset'])) {
		delete_option('qpp_send'.$id);
		qpp_admin_notice("The submission settings have been reset.");
		}
	$qpp_setup = qpp_get_stored_setup();
	$id=$qpp_setup['current'];
	$send = qpp_get_stored_send($id);
	$$send['target'] = 'checked';
	qpp_options_css();
	$content ='<div class="qpp-settings"><div class="qpp-options" style="margin-right:10px;">';
	if ($id) $content .='<h2 style="color:#B52C00">Send settings for ' . $id . '</h2>';
	else $content .='<h2 style="color:#B52C00">Default form send options</h2>';
	$content .= qpp_change_form($qpp_setup);
	$content .= '
		<form action="" method="POST">
		<h2>Submission Message</h2>
		<p>This is what the visitor sees while the paypal page loads</p>
		<input type="text" style="width:100%" name="waiting" value="' . $send['waiting'] . '" />
		<h2>Cancel and Thank you pages</h2>
		<p>If you leave these blank paypal will return the user to the current page.</p>
		<h3>URL of cancellation page</h3>
		<input type="text" style="width:100%" name="cancelurl" value="' . $send['cancelurl'] . '" />
		<h3>URL of thank you page</h3>
		<input type="text" style="width:100%" name="thanksurl" value="' . $send['thanksurl'] . '" />
		<h2>Paypal Link</h2>
		<p>
			<input style="width:20px; margin: 0; padding: 0; border: none;" type="radio" name="target" value="newpage" ' . $newpage . ' /> Open link in new page/tab<br>
			<input style="width:20px; margin: 0; padding: 0; border: none;" type="radio" name="target" value="current" ' . $current . ' /> Open in existing page</p>
		<p>
			<input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Changes" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the form settings?\' );"/></p>
		</form>
		</div>
		<div class="qpp-options" style="float:right;"> <h2 style="color:#B52C00">Form Preview</h2>
		<p>Note: The preview form uses the wordpress admin styles. Your form will use the theme styles so won\'t look exactly like the one below.</p>';
	$args = array('form' => $id, 'id' => '', 'amount' => '');
	$content .= qpp_loop($args);
	$content .= '</div></div>';
	echo $content;
	}
function qpp_error_page($id) {
	qpp_change_form_update();
	if( isset( $_POST['Submit'])) {
		$options = array('errortitle','errorblurb');
		foreach ( $options as $item) $error[$item] = stripslashes($_POST[$item]);
		update_option( 'qpp_error'.$id, $error );
		qpp_admin_notice("The error settings have been updated.");
		}
	if( isset( $_POST['Reset'])) {
		delete_option('qpp_error'.$id);
		qpp_admin_notice("The error messageshave been reset.");
		}
	$qpp_setup = qpp_get_stored_setup();
	$id=$qpp_setup['current'];
	$error = qpp_get_stored_error($id);
	qpp_options_css();
	$content ='<div class="qpp-settings"><div class="qpp-options" style="margin-right:10px;">';
	if ($id) $content .='<h2 style="color:#B52C00">Eror message settings for ' . $id . '</h2>';
	else $content .='<h2 style="color:#B52C00">Default form error message</h2>';
	$content .= qpp_change_form($qpp_setup);
	$content .= '<form method="post" action="">
		<p>Error header (leave blank if you don\'t want a heading):</p>
		<p><input type="text"  style="width:100%" name="errortitle" value="' . $error['errortitle'] . '" /></p>
		<p>This is the blurb that will appear below the error heading and above the form (leave blank if you don\'t want any blurb):</p>
		<p><input type="text" style="width:100%" name="errorblurb" value="' . $error['errorblurb'] . '" /></p>
		<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Changes" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the error message?\' );"/></p>
		</form>
		</div>
		<div class="qpp-options" style="float:right;">
		<h2 style="color:#B52C00">Error Checker</h2>
		<p>Try sending a blank form to test your error messages.</p>';
	$args = array('form' => $id, 'id' => '', 'amount' => '');
	$content .= qpp_loop($args);
	$content .= '</div></div>';
	echo $content;
	}
function qpp_shortcodes() {
	$content ='<div class="qpp-settings"><div class="qpp-options" style="margin-right:10px;">
		<h2>Simple Shortcodes</h2>
		<p>You can preset the ID and amount fields using shortcode attributes. The basic format is:</p>
		<p><code>[qpp id="ABC123" amount="$140"]</code>.</p><p>You can use just one or both as required.</p>
		<h2>Shortcode Labels</h2>
		<p>A label is displayed on the form in front of the attribute. If you dont want them just delete the shortcode lables on the <a href="?page=quick-paypal-new/settings.php&tab=styles">form setting</a> page.</p>
		<p>If you want to turn labels off for selected forms use the shortcode:</p>
		<p><code>[qpp id="ABC123" amount="$140" labels="off"]</code>.</p>
		<h2>Product Option Shortcodes</h2>
		<p>If you have a number of items the visitor can choose from you can list these in the ID shortcode. Like this:</p>
		<p><code>[qpp id="red hat, blue hat, green hat"]</code>.</p>
		<p>The options will display as a radio list</p>
		<h2>Named forms</h2>
		<p>If you have set up a named form use the shortcode</p>
		<p><code>[qpp form="name"]</code>.</p>
		<p>Where "name" is the name of your form. You can have multiple forms on each page.</p>
		</div>
		<div class="qpp-options" style="float:right;"> 
		<h2>Example Shortcodes</h2>
		<p><code>[qpp id="ABC123"]</code></p>';
	$args = array('form' =>'abc123','id' => 'ABC123');
	$content .= qpp_loop($args);
	$content .='<p><code>[qpp id="ABC123" labels="off"]</code></p>';
	$args = array('form' =>'abc123lablesoff','id' => 'ABC123','labels' =>'off' );
	$content .= qpp_loop($args);
	$content .= '<p><code>[qpp id="red hat, blue hat, green hat" amount="&pound;150"]</code></p>';
	$args = array('form' =>'abc123list','id' => 'red hat, blue hat, green hat','amount'=>'&pound;150');
	$content .= qpp_loop($args);
	$content .= '</div></div>';
	echo $content;
	}
function qpp_reset_page($id) {
	qpp_change_form_update();
	if (isset($_POST['qpp_reset'])) {
		if (isset($_POST['qpp_delete_form'])) {
			$qpp_setup = qpp_get_stored_setup();
			if ($id != '') {
				$forms = $qpp_setup['alternative'];
				$qpp_setup['alternative'] = str_replace($id.',','',$forms); 
				$qpp_setup['current'] = '';
				$qpp_setup['email'] = $_POST['email'];
				update_option('qpp_setup', $qpp_setup);
				qpp_delete_things($id);
				qpp_admin_notice("<b>The form named ".$id." has been deleted.</b>");
				$id = '';
				}
			}
		if (isset($_POST['qpp_reset_form'])) {
			qpp_delete_things($id);
			if ($id) qpp_admin_notice("<b>The form called ".$id. " has been reset.</b> Use the <a href= '?page=quick-paypal-payments/settings.php&tab=setup'>Setup</a> tab to add a new named form");
			else qpp_admin_notice("<b>The default form has been reset.</b>");
			}
		if (isset($_POST['qpp_reset_options'])) {
			delete_option('qpp_settings'.$id);
			if ($id) qpp_admin_notice("<b>Form settings for ".$id." have been reset.</b> Use the <a href= '?page=quick-paypal-payments/settings.php&tab=settings'>Form Settings</a> tab to change the settings");
			else qpp_admin_notice("<b>The default form settings have been reset.</b> Use the <a href= '?page=quick-paypal-payments/settings.php&tab=settings'>Form Settings</a> tab to change the settings");
			}
		if (isset($_POST['qpp_reset_send'])) {
			delete_option('qpp_send'.$id);
			if ($id) qpp_admin_notice("<b>The send options for ".$id." have been reset.</b>. Use the <a href= '?page=quick-paypal-payments/settings.php&tab=send'>send Options</a> tab to change the settings");
			else qpp_admin_notice("<b>The default send options have been reset.</b>. Use the <a href= '?page=quick-paypal-payments/settings.php&tab=send'>send Options</a> tab to change the settings");
			}
		if (isset($_POST['qpp_reset_styles'])) {
			delete_option('qpp_style'.$id);
			if ($id) qpp_admin_notice("<b>The styles for ".$id." have been reset.</b>. Use the <a href= '?page=quick-paypal-payments/settings.php&tab=styles'>Styling</a> tab to change the settings");
			else qpp_admin_notice("<b>The default styles have been reset.</b>. Use the <a href= '?page=quick-paypal-payments/settings.php&tab=styles'>Styling</a> tab to change the settings");
			}
		if (isset($_POST['qpp_reset_errors'])) {
			delete_option('qpp_error'.$id);
			if ($id) qpp_admin_notice("<b>The error messages for ".$id." have been reset.</b> Use the <a href= '?page=quick-paypal-payments/settings.php&tab=error'>Error Messages</a> tab to change the settings.");
			else qpp_admin_notice("<b>The default error messages have been reset.</b> Use the <a href= '?page=quick-paypal-payments/settings.php&tab=error'>Error Messages</a> tab to change the settings.");
			}
		if (isset($_POST['qpp_reset_everything'])) {
			$qpp_setup = qpp_get_stored_setup();
			$id = explode(",",$qpp_setup['alternative']);
			foreach ($id as $item) qpp_delete_things($id);
			delete_option('qpp_curr');
			delete_option('qpp_setup');
			delete_option('qpp_message');
			qpp_admin_notice("<b>Everything has been reset.</b> This is an ex-parrot. It has gone to meet it's maker.");
			$id = '';
			}
		}
	$qpp_setup = qpp_get_stored_setup();
	$id=$qpp_setup['current'];
	$content ='<div class="qpp-settings"><div class="qpp-options" style="margin-right:10px;">';
	if ($id) $content .='<h2 style="color:#B52C00">Reset the options for ' . $id . '</h2>';
	else $content .='<h2 style="color:#B52C00">Reset the default form</h2>';
	$content .= qpp_change_form($qpp_setup);
	$content .= '<p>Select the options you wish to reset and click on the blue button. This will reset the selected settings to the defaults.</p>
		<form action="" method="POST">
		<p>
			<input style="margin:0; padding:0; border:none;" type="checkbox" name="qpp_reset_options"> Form settings<br />
			<input style="margin:0; padding:0; border:none;" type="checkbox" name="qpp_reset_styles"> Styling (also delete any custom CSS)<br />
			<input style="margin:0; padding:0; border:none;" type="checkbox" name="qpp_reset_send"> Send and thank-you options<br />
			<input style="margin:0; padding:0; border:none;" type="checkbox" name="qpp_reset_errors"> Error messages</p>
		<hr>
		<p>
			<input style="margin:0; padding:0; border:none;" type="checkbox" name="qpp_reset_form"> Reset this form to default settings';
		if ($id) $content .= '<br /><input style="margin:0; padding:0; border:none;" type="checkbox" name="qpp_delete_form"> Delete '.$id.'</p>';
	$content .= '<hr>
		<p>
			<input type="hidden" name="email" value="' . $qpp_setup['email'] . '" />
			<input type="submit" class="button-primary" name="qpp_reset" style="color: #FFF" value="Reset Options" onclick="return window.confirm( \'Are you sure you want to reset these settings?\' );"/>
		</form>
	</div></div>';
	echo $content;
	}
function qpp_delete_everything() {
	$qpp_setup = qpp_get_stored_setup();
	$arr = explode(",",$qpp_setup['alternative']);
	foreach ($arr as $item) qpp_delete_things($item);
	delete_option('qpp_setup');
	delete_option('qpp_curr');
	delete_option('qpp_message');
	}

function qpp_delete_things($id) {
	delete_option('qpp_settings'.$id);
	delete_option('qpp_send'.$id);
	delete_option('qpp_error'.$id);
	delete_option('qpp_style'.$id);
	}
function qpp_init() {
	wp_enqueue_script('jquery-ui-sortable');
	qpp_generate_csv();
	return;
	}
function qpp_admin_notice($message) {
	if (!empty( $message)) echo '<div class="updated"><p>'.$message.'</p></div>';
	}
function qpp_change_form($qpp_setup) {
	if ($qpp_setup['alternative']) {
		$content .= '<form style="margin-top: 8px" method="post" action="" >';
		$arr = explode(",",$qpp_setup['alternative']);
		foreach ($arr as $item) {
			if ($qpp_setup['current'] == $item) $checked = 'checked'; else $checked = '';
			if ($item == '') {$formname = 'default'; $item='';} else $formname = $item;
			$content .='<input style="margin:0; padding:0; border:none" type="radio" name="current" value="' .$item . '" ' .$checked . ' /> '.$formname . ' ';
			}
		$content .='
			<input type="hidden" name="alternative" value = "' . $qpp_setup['alternative'] . '" />
			<input type="hidden" name="email" value = "' . $qpp_setup['email'] . '" />&nbsp;&nbsp;
			<input type="submit" name="Select" class="button-secondary" value="Change Form" />
			</form>';
		}
	return $content;
	}
function qpp_change_form_update() {
	if( isset( $_POST['Select'])) {
		$qpp_setup['current'] = $_POST['current'];
		$qpp_setup['alternative'] = $_POST['alternative'];
		$qpp_setup['email'] = $_POST['email'];
		update_option( 'qpp_setup', $qpp_setup);
		}
	}
function qpp_admin_pointers_header() {
	if ( qpp_admin_pointers_check() ) {
		add_action( 'admin_print_footer_scripts', 'qpp_admin_pointers_footer' );
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_style( 'wp-pointer' );
		}
	}
function qpp_admin_pointers_check() {
	$admin_pointers = qpp_admin_pointers();
	foreach ( $admin_pointers as $pointer => $array ) {
		if ( $array['active'] ) return true;
		}
	}
function qpp_admin_pointers_footer() {
	$admin_pointers = qpp_admin_pointers();
	?>
	<script type="text/javascript">
	/* <![CDATA[ */
	( function($) {
   	<?php
	foreach ( $admin_pointers as $pointer => $array ) {
		if ( $array['active'] ) {
		?>
		$( '<?php echo $array['anchor_id']; ?>' ).pointer( {
			content: '<?php echo $array['content']; ?>',
			position: {
			edge: '<?php echo $array['edge']; ?>',
			align: '<?php echo $array['align']; ?>'
			},
		close: function() {
		$.post( ajaxurl, {pointer: '<?php echo $pointer; ?>',action: 'dismiss-wp-pointer'} );
		}
	} ).pointer( 'open' );
	<?php } } ?>
	} )(jQuery);
	/* ]]> */
	</script>
	<?php
	}
function qpp_admin_pointers() {
	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	$version = '3_0';
	$prefix = 'qpp_admin_pointers' . $version . '_';
	$new_pointer_content = '<h3>Quick Paypal Payments</h3>';
	$new_pointer_content .= '<p>Welcome to the new and improved plugin - now with multiple form support and payment reports.</p><p>If you are new to the plugin then start on the <a href="options-general.php?page=quick-paypal-payments/settings.php">Settings</a> page.</p>';
	return array(
		$prefix . 'new_items' => array(
		'content' => $new_pointer_content,
		'anchor_id' => '#toplevel_page_quick-paypal-payments-quick-paypal-payments',
		'edge' => 'left',
		'align' => 'left',
		'active' => ( ! in_array( $prefix . 'new_items', $dismissed ) )
		),);
	}
function qpp_generate_csv() {
	if(isset($_POST['download_qpp_csv'])) {
	$id = $_POST['formname'];
		$filename = urlencode($id.'.csv');
		if ($id == '') $filename = urlencode('default.csv');
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename="'.$filename.'"');
		header( 'Content-Type: text/csv');$outstream = fopen("php://output",'w');
		$message = get_option( 'qpp_messages'.$id );
		if(!is_array($message))$message = array();
		$qpp = qpp_get_stored_options ($id);
		$headerrow = array();
		array_push($headerrow,'Date Sent');
		array_push($headerrow, $qpp['inputreference']);
		array_push($headerrow, $qpp['quantitylabel']);
		array_push($headerrow, $qpp['inputamount']);
		fputcsv($outstream,$headerrow, ',', '"');
		foreach(array_reverse( $message ) as $value) {
			$cells = array();
			array_push($cells,$value['field0']);
			array_push($cells,$value['field1']);
			array_push($cells,$value['field2']);
			array_push($cells,$value['field3']);
			fputcsv($outstream,$cells, ',', '"');
			}
		fclose($outstream); 
		exit;
		}
	}
function donate_verify($formvalues) {
	$errors = '';
	if ($formvalues['amount'] == 'Amount' || empty($formvalues['amount'])) $errors = 'first';
	if ($formvalues['yourname'] == 'Your name' || empty($formvalues['yourname'])) $errors = 'second';
	return $errors;
	}
function donate_display( $values, $errors ) {
	$content = "<script>\r\t
	function donateclear(thisfield, defaulttext) {if (thisfield.value == defaulttext) {thisfield.value = '';}}\r\t
	function donaterecall(thisfield, defaulttext) {if (thisfield.value == '') {thisfield.value = defaulttext;}}\r\t
	</script>\r\t
	<div class='qpp-style'>\r\t<div id='round'>\r\t";
	if ($errors)
		$content .= "<h2 class='error'>Feed me...</h2>\r\t<p class='error'>...your donation details</p>\r\t";
	else
		$content .= "<h2>Make a donation</h2>\r\t<p>Whilst I enjoy creating these plugins they don't pay the bills. So a donation will always be gratefully received</p>\r\t";
	$content .= '
	<form method="post" action="" >
	<p><input type="text" label="Your name" name="yourname" value="Your name" onfocus="donateclear(this, \'Your name\')" onblur="donaterecall(this, \'Your name\')"/></p>
	<p><input type="text" label="Amount" name="amount" value="Amount" onfocus="donateclear(this, \'Amount\')" onblur="donaterecall(this, \'Amount\')"/></p>
	<p><input type="submit" value="Donate" id="submit" name="donate" /></p>
	</form></div></div></div>';
	echo $content;
	}
function donate_process($values) {
	$page_url = donate_page_url();
	$content = '<h2>Waiting for paypal...</h2><form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="frmCart" id="frmCart">
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="graham@aerin.co.uk">
	<input type="hidden" name="return" value="' .  $page_url . '">
	<input type="hidden" name="cancel_return" value="' .  $page_url . '">
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="currency_code" value="">
	<input type="hidden" name="item_number" value="">
	<input type="hidden" name="item_name" value="'.$values['yourname'].'">
	<input type="hidden" name="amount" value="'.preg_replace ( '/[^.,0-9]/', '', $values['amount']).'">
	</form>
	<script language="JavaScript">
	document.getElementById("frmCart").submit();
	</script>';
	echo $content;
	}
function donate_page_url() {
	$pageURL = 'http';
	if( isset($_SERVER["HTTPS"]) ) { if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";} }
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	return $pageURL;
	}

function donate_loop() {
	ob_start();
	if (isset($_POST['donate'])) {
		$formvalues['yourname'] = $_POST['yourname'];
		$formvalues['amount'] = $_POST['amount'];
		if (donate_verify($formvalues)) donate_display($formvalues,'donateerror');
   		else donate_process($formvalues,$form);
		}
	else donate_display($formvalues,'');
	$output_string=ob_get_contents();
	ob_end_clean();
	return $output_string;
	}