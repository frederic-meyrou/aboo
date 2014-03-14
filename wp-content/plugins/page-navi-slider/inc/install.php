<?php 
global $default_settings;

	$default_settings=array(
		
		//Preview
		'wpns_revert_disabled'=>'disabled',
		'wpns_preview_background_color'=>'#E6E6E6',
		'wpns_preview_pages'=>'25',
		'wpns_preview_current'=>'5',
		
		//General
		'wpns_margin'=>'0.833em',
		'wpns_padding'=>'0.417em',
		'wpns_display_if_one_page'=>'0',
		'wpns_align'=>'center',
		'wpns_pn_width'=>'2em',
		'wpns_align'=>'center',
		'wpns_spacing'=>'0.2em',
		'wpns_show_total'=>'1',
		
		//Caption
		'wpns_caption_text'=>'Page %%NUMBER%% of %%TOTAL%%',
		'wpns_caption_position'=>'bottom',
		'wpns_caption_alignment'=>'right',
		
		//Fonts
		
		'wpns_caption_font'=>'italic normal bold 0.833em/1em Arial,Helvetica,Sans-Serif',
		'wpns_caption_font_style'=>'italic',
		'wpns_caption_font_variant'=>'normal',
		'wpns_caption_font_weight'=>'bold',
		'wpns_caption_font_size'=>'0.833em',
		'wpns_caption_font_lineheight'=>'1em',
		'wpns_caption_font_family'=>'Arial,Helvetica,Sans-Serif',
		
		'wpns_page_font'=>'normal normal normal 1em/2em Arial,Helvetica,Sans-Serif',
		'wpns_page_font_style'=>'normal',
		'wpns_page_font_variant'=>'normal',
		'wpns_page_font_variant'=>'normal',
		'wpns_page_font_size'=>'1em',
		'wpns_page_font_lineheight'=>'2em',
		'wpns_page_font_family'=>'Arial,Helvetica,Sans-Serif',
		
		'wpns_current_font'=>'normal normal bold 1.25em/2em Arial,Helvetica,Sans-Serif',
		'wpns_current_font_style'=>'normal',
		'wpns_current_font_variant'=>'normal',
		'wpns_current_font_weight'=>'bold',
		'wpns_current_font_size'=>'1.25em',
		'wpns_current_font_lineheight'=>'2em',
		'wpns_current_font_family'=>'Arial,Helvetica,Sans-Serif',
		
		'wpns_hover_font'=>'normal normal bold 1.25em/2em Arial,Helvetica,Sans-Serif',
		'wpns_hover_font_style'=>'normal',
		'wpns_hover_font_variant'=>'normal',
		'wpns_hover_font_weight'=>'bold',
		'wpns_hover_font_size'=>'1.25em',
		'wpns_hover_font_lineheight'=>'2em',
		'wpns_hover_font_family'=>'Arial,Helvetica,Sans-Serif',
		
		//Colors
		'wpns_caption_color'=>'#505050',
		'wpns_page_fore_color'=>'#505050',
		'wpns_page_back_color'=>'transparent',
		'wpns_current_fore_color'=>'#ffffff',
		'wpns_current_back_color'=>'#505050',
		'wpns_hover_fore_color'=>'#ffffff',
		'wpns_hover_back_color'=>'#505050',
		'wpns_slider_color'=>'#ffffff',
		'wpns_cursor_color'=>'#E6E6E6',
		
		//Borders
		'wpns_radius'=>'0.333em',
		'wpns_page_border'=>'1px solid #505050',
		'wpns_current_border'=>'1px solid #505050',
		'wpns_hover_border'=>'1px solid #505050',
		'wpns_slider_border'=>'1px solid #AAAAAA',
		'wpns_cursor_border'=>'1px solid #AAAAAA',
		
		//Auto display
		'wpns_auto_display'=>'0',
		'wpns_auto_display_position'=>'footer top'
	);


function wpns_install(){
	global $default_settings;
	$new_settings = array_intersect_key(get_option('wpns_settings',$default_settings),$default_settings)+$default_settings;
	update_option('wpns_settings', $new_settings);
	update_option('wpns_settings_preview', $new_settings);
}

function wpns_uninstall(){
	delete_option('wpns_settings');
	delete_option('wpns_settings_preview');
	wpns_remove_auto_display();
}

function wpns_auto_display(){
	$settings = get_option('wpns_settings');
	if ($settings['wpns_auto_display'] !='1'){
		wpns_remove_auto_display();
	}else{
		if($settings['wpns_auto_display_position']=='footer top'){
			if (!has_action('get_footer','page_navi_slider')){add_action('get_footer', 'page_navi_slider');}
		};
		if($settings['wpns_auto_display_position']=='footer bottom'){
			if (!has_action('wp_footer','page_navi_slider')){add_action('wp_footer', 'page_navi_slider');}
		};
	}
}

function wpns_remove_auto_display(){
	if (has_action('get_footer','page_navi_slider')){remove_action('get_footer', 'page_navi_slider');}
	if (has_action('wp_footer','page_navi_slider')){remove_action('wp_footer', 'page_navi_slider');}
}
?>