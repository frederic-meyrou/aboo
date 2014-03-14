function page_navi_slider(index,param){
	jQuery.noConflict();
	
	//Vars
	var content_width =0;
	var init_value = 0;
	var maxi=100;
	var mini=1;
	var element_count=0;
	var current_value=0;
	
	//Adapt CSS 
	//General
	jQuery('.wpns_wrapper').css('margin',param['wpns_margin']);
	jQuery('.wpns_container').css('padding',param['wpns_padding']);
	jQuery('.wpns_element').css('margin','0 '+param['wpns_spacing']);
	jQuery('.wpns_first').css('margin','0 '+param['wpns_spacing']+' 0 0');
	jQuery('.wpns_last').css('margin','0 0 0 '+param['wpns_spacing']);
	jQuery('.wpns_selector .page-numbers').css('min-width',param['wpns_pn_width']);
	set_content_alignement('.wpns_selector');
	set_content_alignement('.wpns_container');
	//Border
	jQuery('.wpns_element').css('border-radius',param['wpns_radius']);
	jQuery('.wpns_element').css('border',param['wpns_page_border']);
	jQuery('.wpns_active').css('border',param['wpns_current_border']);
	//On hover
	jQuery(".wpns_inactive").hover(
		function(){
			var new_width=content_width-jQuery(this).outerWidth(true);
			jQuery('a',this).css('color',param['wpns_hover_fore_color']);
			jQuery(this).css('background',param['wpns_hover_back_color']);
			jQuery(this).css('border',param['wpns_hover_border']);
			jQuery(this).css('font',param['wpns_hover_font']);
			new_width =new_width + jQuery(this).outerWidth(true);
			jQuery('.wpns_sliding_list').width(new_width);
		},
		function(){
			jQuery('a',this).css('color',param['wpns_page_fore_color']);
			jQuery(this).css('background',param['wpns_page_back_color']);
			jQuery(this).css('border',param['wpns_page_border']);
			jQuery(this).css('font',param['wpns_page_font']);
			jQuery('.wpns_sliding_list').width(content_width);
		}
	)

	//Count number of elements and set width
	jQuery('.wpns_element').each(function() {
		content_width = content_width + jQuery(this).outerWidth(true);
		element_count = element_count+1;
	});
	//If several page-navi-slider in the same page !
	element_count=element_count/jQuery('.wpns_selector').length; 
	content_width=content_width /jQuery('.wpns_selector').length;
	content_width = Math.round(content_width)+1;
	maxi= Math.round(content_width/5);
	jQuery('.wpns_sliding_list').width(content_width); 

	//Display according to the init argument
	if (index < 1) { index = 1;}
	if (index > element_count) { index = element_count;}
	init_value=Math.round((index-1)*(maxi-mini)/(element_count-1)+mini);
	current_value=init_value;
	content_margin_left=(init_value-mini)*(jQuery('.wpns_window').width()-content_width)/ (maxi-mini);
	jQuery('.wpns_sliding_list').css('margin-left',content_margin_left);
	
	//Content margin when slider shown
	function set_content_margin(){
		content_margin_left=(current_value-mini)*(jQuery('.wpns_window').width()-content_width)/ (maxi-mini);
		jQuery('.wpns_sliding_list').css('margin-left',content_margin_left);
	}
	
	//Content margin when no slider
	function set_content_alignement(section){
		if( param['wpns_align'] == 'left'){
			jQuery(section).css('margin','0');
		}else{
			if( param['wpns_align'] == 'right'){
				jQuery(section).css('margin','0 0 0 auto');
			}else{
				jQuery(section).css('margin','0 auto');
			}
		}
	}
	
	//Apply content margin
	function apply_margin(){
		if (jQuery('.wpns_window').width()>content_width){
			jQuery('.wpns_slider').hide();
			set_content_alignement('.wpns_sliding_list');
		}else{
			jQuery('.wpns_slider').show();
			set_content_margin();
		}
	}
	
	apply_margin();
	//Slider
	jQuery('.wpns_slider').slider({
		value: init_value,
		min: mini,
		max: maxi,
		step: 1,
		slide: function( event, ui ) {
			current_value=ui.value;
			set_content_margin();
		}
	});
	
	//When resizing
	jQuery(window).resize(function() {
		apply_margin();
	});
	
	//Apply colors
	jQuery('.wpns_title').css('color',param['wpns_caption_color']);
	jQuery('.wpns_selector').css('color',param['wpns_page_fore_color']);
	jQuery('a','.wpns_selector').css('color',param['wpns_page_fore_color']);
	jQuery('.wpns_element').css('background',param['wpns_page_back_color']);
	jQuery('.wpns_active').css('color',param['wpns_current_fore_color']);
	jQuery('.wpns_active').css('background',param['wpns_current_back_color']);
	jQuery('.wpns_slider').css('background',param['wpns_slider_color']);
	jQuery('.wpns_slider').css('border',param['wpns_slider_border']);
	jQuery('.wpns_selector .ui-widget-content .ui-state-default').css('background',param['wpns_cursor_color']);
	jQuery('.wpns_selector .ui-widget-content .ui-state-default').css('border',param['wpns_cursor_border']);
	
}