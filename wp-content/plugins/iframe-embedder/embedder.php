<?php
/*
Plugin Name: Iframe Embedder
Plugin URI: http://de77.com/other/wordpress-iframe-embedder
Description: Lets you embed an iframe in a post
Version: 1.2
Author: de77.com
Author URI: http://de77.com
Licence: MIT
*/

// Frontend

function embedder_replace($matches)
{	
	$temp = explode(' ', $matches[1]);
    $count = count($temp);
   	
	$url = $temp[0];         
    $width = isset($temp[1]) ? $temp[1] : 200;
    $height = isset($temp[2]) ? $temp[2] : 300;
    $x = isset($temp[3]) ? $temp[3] : 0;
    $y = isset($temp[4]) ? $temp[4] : 0;

    if (strpos($width, 'px') === false and strpos($width, '%') === false)
    {
    	$width .= 'px'; 
    }
    if (strpos($height, 'px') === false and strpos($height, '%') === false)
    {
    	$height .= 'px'; 
    }
	
	if (get_option('embedder_scrollmethod') == '0')
	{ 
		$scrollTo1 = '';
		$scrollTo2 = 'onload="scro11me(this)"></iframe>' .
					'<script type="text/javascript">' .
					'function scro11me(f){f.contentWindow.scrollTo(' . $x . ',' . $y . '); }' .
					'</script>';
	}
	else
	{		
		$scrollTo1 = '<div style="position:relative; overflow: hidden; width: ' . $width . '; height: ' . $height . '">' .
					'<div style="position:absolute; left:' . (-1 * $x) . 'px; top: ' . (-1 * $y) . 'px">';
		$scrollTo2 = '></iframe></div></div>';
		$w = (int) $width;
		$h = (int) $height;
		$width = str_replace($w, $w + $x, $width);
		$height = str_replace($h, $h + $x, $height);
	}
	    
    return	$scrollTo1 .
			'<iframe class="' . get_option('embedder_class') . '" src="' . $url . '" style="width: ' . 
			$width . '; height: ' . $height . ';' . get_option('embedder_style') . ' " frameborder="' . 
			(int) get_option('embedder_border') . '" scrolling="' . get_option('embedder_scrolling') . '" ' . 
			$scrollTo2;
}

function embedder_parse_iframe($text)
{
	return preg_replace_callback("@(?:<p>\s*)?\[iframe\s*(.*?)\](?:\s*</p>)?@", 'embedder_replace', $text);
}

add_filter('the_content', 'embedder_parse_iframe');

// Backend

add_action('admin_menu', 'embedder_create_menu');

function embedder_create_menu() 
{
	//add_menu_page('IFrame Embedder Settings', 'IFrame Embedder Settings', 'administrator', __FILE__, 'embedder_settings_page',plugins_url('/images/icon.png', __FILE__));
	add_submenu_page('options-general.php', 'IFrame Embedder Settings', 'IFrame Embedder', 'administrator', __FILE__, 'embedder_settings_page'); 
	add_action( 'admin_init', 'register_embedder_settings');
}


function register_embedder_settings() 
{
	//register our settings
	register_setting( 'embedder-settings-group', 'embedder_style');
	register_setting( 'embedder-settings-group', 'embedder_class');
	register_setting( 'embedder-settings-group', 'embedder_border');
	register_setting( 'embedder-settings-group', 'embedder_scrolling');
	register_setting( 'embedder-settings-group', 'embedder_scrollmethod');
}

function embedder_settings_page() 
{
?>
	<div class="wrap">
	<h2>
		IFrame Embedder<br />
		<span style="font-size: 17px; position:relative; top:-10px; font-style: italic; padding-left:120px">by <a href="http://de77.com">de77.com</a></span>
	</h2>	
		
	<form method="post" action="options.php">
	    
		<?php settings_fields('embedder-settings-group'); ?>
	    
	    <table class="form-table">
	        <tr valign="top">
	        <th scope="row">Show border</th>
	        <td>
				<input type="checkbox" name="embedder_border" value="1" <?php if (get_option('embedder_border') == '1') echo 'checked="checked"'; ?> /></td>
			</td>
	        </tr>
	        
	        <tr valign="top">
	        <th scope="row">Scrolling</th>
	        <td>
				<select name="embedder_scrolling">
					<option value="auto" >Auto</option>
					<option value="yes" <?php if (get_option('embedder_scrolling') == 'yes') echo 'selected="selected"'; ?>>Yes</option>
					<option value="no" <?php if (get_option('embedder_scrolling') == 'no') echo 'selected="selected"'; ?>>No</option>
				</select>
			</td>
	        </tr>
	        
	        <tr valign="top">
	        <th scope="row">Scrolling method</th>
	        <td>
				<select name="embedder_scrollmethod">
					<option value="0">#1 (for same domain)</option>
					<option value="1" <?php if (get_option('embedder_scrollmethod') == '1') echo 'selected="selected"'; ?>>#2 (all domains)</option>
				</select>
				<b>Info:</b><br />
				Method #2 partially hides scrollbars so it should be used with disabled scrolling.
			</td>
	        </tr>
	        
			<tr valign="top">
	        <th scope="row">Class name</th>
	        <td><input type="text" name="embedder_class" style="width: 400px" value="<?php echo get_option('embedder_class'); ?>" /></td>
	        </tr>
	         
	        <tr valign="top">
	        <th scope="row">CSS Styles</th>
	        <td>
				<textarea name="embedder_style" style="width: 400px; height: 70px"><?php echo get_option('embedder_style'); ?></textarea><br />
				<b>Info:</b><br />
				Do not use width and height- you should specify these values in your post:<br />
				[iframe http://address.com 400px 100px]
			</td>
	        </tr>	        	        
	        
	    </table>
	    
	    <p class="submit">
	    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	    </p>
	
	</form>
	</div>
<?php 
} 
?>
