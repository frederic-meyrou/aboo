<?php 

class WPNS_SETTING_PAGE{
	
	//Holds the values to be used in the fields callbacks
  protected $_options;
	protected $_default_settings;

  //Start up
	public function __construct($default_settings){
		add_action('admin_menu', array($this, 'add_plugin_page'));
    add_action('admin_init', array($this, 'page_init'));
		$this->_default_settings = $default_settings;
		$this->_options = get_option('wpns_settings_preview');
  }

  //Add options page
  public function add_plugin_page(){
		add_options_page('Page navi slider', 'Page navi slider', 'manage_options', 'wpns_settings_admin', array( $this, 'create_admin_page' ));
		
		wp_enqueue_style ('jquery-ui-style', plugins_url('../style/jquery-ui.css', __FILE__));
		wp_enqueue_script('accordion-style',  plugins_url('../js/settings.js', __FILE__),	array( 'jquery', 'jquery-ui-slider', 'jquery-ui-tabs' ),true);
		
		wp_register_style( 'page_navi_slider_style', plugins_url('../style/page-navi-slider.css', __FILE__) );
		wp_enqueue_style( 'page_navi_slider_style' );
		if(ereg('MSIE 7',$_SERVER['HTTP_USER_AGENT'])){
			wp_register_style('page_navi_slider_styleIE', plugins_url('../style/page-navi-slider.ie.css', __FILE__) );
			wp_enqueue_style('page_navi_slider_styleIE' );
		} 
		
		wp_enqueue_script('page-navi-slider-script',  plugins_url('../js/page-navi-slider.min.js', __FILE__),	array( 'jquery', 'jquery-ui-slider', 'jquery-ui-resizable' ),true	);
		wp_enqueue_script('jQueryUiTouch',  plugins_url('../js/jquery.ui.touch-punch.min.js', __FILE__),	array( 'jquery' ), true);
		wp_enqueue_script('jscolor',  plugins_url('../js/jsColor/jscolor.min.js', __FILE__),	array( 'jquery' ));
  }

  //Options page callback
  public function create_admin_page(){
	?>

		<div class="wrap">
		
		<?php if (isset($_GET['current'])){$this->_options['wpns_preview_current']=$_GET['current'];}?>
		<?php echo get_screen_icon(); ?>
		
		<h2>Page navi slider <span style="font-size:small"><?php echo wpns_version(); ?></span></h2> 
		<div class="wpns_settings_page">
    <h3><?php _e('Preview','page-navi-slider');?></h3> 	
		<?php self::wpns_display_preview(); ?>
		<h3><?php _e('Settings','page-navi-slider');?></h3> 
		
    <form method="post" action="options.php">
			<table class="wpns_setting_buttons"><tr>
				<td><?php submit_button(__('Preview','page-navi-slider'),'reset','preview'); ?></td>
				<td><?php submit_button(__('Revert','page-navi-slider'),'reset','revert',true, $this->_options['wpns_revert_disabled'] ); ?></td>
				<td><?php submit_button(__('Save changes','page-navi-slider'),'primary','submit',true); ?>
				</td>
				<td><?php submit_button(__('Reset to default','page-navi-slider'),'reset','reset'); ?>
				</td>
			</tr></table>
			
			<?php
				echo '<div id="wpns_tabs">';
				settings_fields( 'wpns_settings_preview' );
				$this->wpns_do_settings_sections( 'wpns_settings_admin' );
				echo '</div>';
			?>
		</form>
		</div>
	</div>

	<?php
	}

	//Adaptated do_settings_sections function 
	//to use jQuery pannels
	function wpns_do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;
		if ( ! isset( $wp_settings_sections ) || !isset( $wp_settings_sections[$page] ) )		return;
		
		$i=1;
		echo "<ul>";
		foreach ( (array) $wp_settings_sections[$page] as $section ){
			echo "<li><a href='#wpns_tabs_".$i."'>".$section['title']."</a></li>";
			$i++;
		}
		echo "</ul>";
		
		$i=1;
		foreach ( (array) $wp_settings_sections[$page] as $section ){
			if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )	continue;
			echo "<div id='wpns_tabs_".$i."'>";
			echo '<table class="form-table wpns_setting_table">';
			if ( $section['callback'] ){
				$title = call_user_func( $section['callback'], $section );
				if ($title != '') echo "<tr><td colspan=2>$title</td></tr>";
			};
			do_settings_fields( $page, $section['id'] );
			echo '</table>';
			echo "</div>";
			$i++;
		}
	}
	
	//Register and add settings
	public function page_init(){        
		register_setting('wpns_settings_preview','wpns_settings_preview', array( $this, 'sanitize' ));
		add_settings_section('wpns_preview_section',__('Preview options','page-navi-slider'), array( $this, 'print_preview_info' ), 'wpns_settings_admin');
		add_settings_section('wpns_general_section',__('General settings','page-navi-slider'), array( $this, 'print_general_info' ), 'wpns_settings_admin');
		add_settings_section('wpns_caption_section',__('Caption','page-navi-slider'), array( $this, 'print_caption_info' ), 'wpns_settings_admin');
		add_settings_section('wpns_font_section',__('Fonts','page-navi-slider'), array( $this, 'print_font_info' ), 'wpns_settings_admin');
		add_settings_section('wpns_color_section',__('Colors','page-navi-slider'), array( $this, 'print_color_info' ), 'wpns_settings_admin');
		add_settings_section('wpns_border_section',__('Borders','page-navi-slider'), array( $this, 'print_border_info' ), 'wpns_settings_admin');
		add_settings_section('wpns_autodisplay_section',__('Auto display','page-navi-slider')." <b> - ".__('CAUTION !','page-navi-slider')."</b>", array( $this, 'print_autodisplay_info' ), 'wpns_settings_admin');
		//fields - Preview
		add_settings_field('wpns_preview_background_color',__('Background color','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_preview_section',array('setting'=>'wpns_preview_background_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>''));      
		add_settings_field('wpns_preview_pages',__('Number of pages','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_preview_section',array('setting'=>'wpns_preview_pages'));      
		add_settings_field('wpns_preview_current',__('','page-navi-slider'),array( $this, 'wpns_hidden_field_callback' ),'wpns_settings_admin','wpns_preview_section',array('setting'=>'wpns_preview_current'));      
		//fields - General
		add_settings_field('wpns_margin',__('Wrapper margin','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_general_section',array('setting'=>'wpns_margin'));      
		add_settings_field('wpns_padding',__('Wrapper padding','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_general_section',array('setting'=>'wpns_padding'));      
		add_settings_field('wpns_display_if_one_page',__('Display even if there is only one page','page-navi-slider'),array( $this, 'wpns_checkbox_field_callback' ),'wpns_settings_admin','wpns_general_section','wpns_display_if_one_page');      
		add_settings_field('wpns_align',__('Alignment','page-navi-slider'),array( $this, 'wpns_option_field_callback' ),'wpns_settings_admin','wpns_general_section',array('setting'=>'wpns_align','values'=>array('left','center','right')));		
		add_settings_field('wpns_pn_width',__('Page numbers width','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_general_section',array('setting'=>'wpns_pn_width'));      
		add_settings_field('wpns_spacing',__('Spacing between page numbers','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_general_section',array('setting'=>'wpns_spacing'));      
		add_settings_field('wpns_show_total',__('Show total pages in the current one','page-navi-slider'),array( $this, 'wpns_checkbox_field_callback' ),'wpns_settings_admin','wpns_general_section','wpns_show_total');      
		//fields - Caption
		add_settings_field('wpns_caption_text',__('Text<br><small>%%NUMBER%% : current page</small><br><small>%%TOTAL%% : total pages<small>','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_caption_section',array('setting'=>'wpns_caption_text','style'=>'width : 100%%'));      
		add_settings_field('wpns_caption_position',__('Position','page-navi-slider'),array( $this, 'wpns_option_field_callback' ),'wpns_settings_admin','wpns_caption_section',array('setting'=>'wpns_caption_position','values'=>array('top','bottom')));      
		add_settings_field('wpns_caption_alignment',__('Alignment','page-navi-slider'),array( $this, 'wpns_option_field_callback' ),'wpns_settings_admin','wpns_caption_section',array('setting'=>'wpns_caption_alignment','values'=>array('left','center','right')));      		
		//fields - Fonts
		add_settings_field('wpns_caption_font',__('Caption','page-navi-slider'),array( $this, 'wpns_font_block_callback' ),'wpns_settings_admin','wpns_font_section','wpns_caption_font');      
		add_settings_field('wpns_page_font',__('Page numbers','page-navi-slider'),array( $this, 'wpns_font_block_callback' ),'wpns_settings_admin','wpns_font_section','wpns_page_font');      
		add_settings_field('wpns_current_font',__('Current page','page-navi-slider'),array( $this, 'wpns_font_block_callback' ),'wpns_settings_admin','wpns_font_section','wpns_current_font');      
		add_settings_field('wpns_hover_font',__('On hover','page-navi-slider'),array( $this, 'wpns_font_block_callback' ),'wpns_settings_admin','wpns_font_section','wpns_hover_font');
		//fields - Colors
		add_settings_field('wpns_caption_color',__('Caption','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_color_section',array('setting'=>'wpns_caption_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>'width: 100%%'));   
		add_settings_field('wpns_page_fore_color',__('Page numbers','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_color_section',array('setting'=>'wpns_page_fore_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>'width: 100%%'));   
		add_settings_field('wpns_page_back_color',__('Page numbers background','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_color_section',array('setting'=>'wpns_page_back_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>'width: 100%%'));    
		add_settings_field('wpns_current_fore_color',__('Current page','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_color_section',array('setting'=>'wpns_current_fore_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>'width: 100%%'));   
		add_settings_field('wpns_current_back_color',__('Current page background','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_color_section',array('setting'=>'wpns_current_back_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>'width: 100%%'));    
		add_settings_field('wpns_hover_fore_color',__('On hover','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_color_section',array('setting'=>'wpns_hover_fore_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>'width: 100%%'));   
		add_settings_field('wpns_hover_back_color',__('On hover background','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_color_section',array('setting'=>'wpns_hover_back_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>'width: 100%%'));    
		add_settings_field('wpns_slider_color',__('Slider','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_color_section',array('setting'=>'wpns_slider_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>'width: 100%%'));
		add_settings_field('wpns_cursor_color',__('Handle','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_color_section',array('setting'=>'wpns_cursor_color','class'=>'color {hash:true, adjust:false, required:false}','style'=>'width: 100%%'));    
		//fields - Borders
		add_settings_field('wpns_radius',__('Radius <small>(<b>em</b>/px)</small>','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_border_section',array('setting'=>'wpns_radius'));      
		add_settings_field('wpns_page_border',__('Page numbers','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_border_section',array('setting'=>'wpns_page_border','class'=>'color {hash:true, adjust:true, required:false, borderstyle : true}'));
		add_settings_field('wpns_current_border',__('Current page','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_border_section',array('setting'=>'wpns_current_border','class'=>'color {hash:true, adjust:true, required:false, borderstyle : true}'));      
		add_settings_field('wpns_hover_border',__('On hover','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_border_section',array('setting'=>'wpns_hover_border','class'=>'color {hash:true, adjust:true, required:false, borderstyle : true}'));
		add_settings_field('wpns_slider_border',__('Slider','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_border_section',array('setting'=>'wpns_slider_border','class'=>'color {hash:true, adjust:true, required:false, borderstyle : true}'));
		add_settings_field('wpns_cursor_border',__('Cursor','page-navi-slider'),array( $this, 'wpns_text_field_callback' ),'wpns_settings_admin','wpns_border_section',array('setting'=>'wpns_cursor_border','class'=>'color {hash:true, adjust:true, required:false, borderstyle : true}'));
		//fields - Auto display
		add_settings_field('wpns_auto_display',__('Display in footer','page-navi-slider'),array( $this, 'wpns_checkbox_field_callback' ),'wpns_settings_admin','wpns_autodisplay_section','wpns_auto_display');
		add_settings_field('wpns_auto_display_position',__('Location','page-navi-slider'),array( $this, 'wpns_option_field_callback' ),'wpns_settings_admin','wpns_autodisplay_section',array('setting'=>'wpns_auto_display_position','values'=>array('footer top','footer bottom')));
		}

	public function sanitize_font(&$input,$setting){
		if($input[$setting.'_size']==''){$input[$setting.'_size']='1em';}
		if($input[$setting.'_lineheight']==''){$input[$setting.'_lineheight']='1em';}
		if($input[$setting.'_family']==''){$input[$setting.'_family']='Arial,Helvetica,Sans-Serif';}
		$input[$setting]=$input[$setting.'_style'].' '.$input[$setting.'_variant'].' '.$input[$setting.'_weight'].' '.$input[$setting.'_size'].'/'.$input[$setting.'_lineheight'].' '.$input[$setting.'_family'];
	}	
	
	public function sanitize($input){
		self::sanitize_font($input,'wpns_caption_font');
		self::sanitize_font($input,'wpns_page_font');
		self::sanitize_font($input,'wpns_current_font');
		self::sanitize_font($input,'wpns_hover_font');
		
		if (!isset($input['wpns_show_total'])) $input['wpns_show_total']='0';
				
		if (isset($_POST['preview'])){
			add_settings_error('', 'wpns_setting_message', __('PREVIEW ONLY - Click on &laquo;Save changes&raquo; to apply settings.','page-navi-slider'), 'error');
			$input['wpns_revert_disabled']='';
		}
		if (isset($_POST['revert'])){
			add_settings_error('', 'wpns_setting_message', __('Former settings loaded.','page-navi-slider'), 'updated');
			$input=$this->_default_settings;
			$input=get_option('wpns_settings',$input);
			$input['wpns_revert_disabled']='disabled';
		}
		if (isset($_POST['submit'])){
			add_settings_error('', 'wpns_setting_message', __('Settings saved.','page-navi-slider'), 'updated');
			$input['wpns_revert_disabled']='disabled';
			update_option('wpns_settings',$input);
		}
		if (isset($_POST['reset'])){
			add_settings_error('', 'wpns_setting_message', __('Default settings loaded - PREVIEW ONLY - Click on &laquo;Save changes&raquo; to apply settings.','page-navi-slider'), 'error');
			$input=$this->_default_settings;
			$input['wpns_revert_disabled']='';
		}
		return $input;
	}

	//Print the Section text
	
	public function print_preview_info(){
		echo "<span class='wpns_setting_small'>";
		_e('These settins only affect the preview on that page; ','page-navi-slider');
		_e('they do not have any impact on the plugin itself.','page-navi-slider');
		echo "<br/>";
		_e('The dotted border is just for preview purpose and will not be displayed on your page.','page-navi-slider');
		echo "</span>";
	}
	
	public function print_general_info(){
		echo "<span class='wpns_setting_small'>";
		_e('Use CSS shorthand for margin and padding; Syntax : top right bottom left.','page-navi-slider');
		echo "<br/>px / em / %";
		_e('can be used and mixed.','page-navi-slider');
		echo "<br/><b>";
		_e('The slider is shown only if the number of pages exceeds the plugin widht.','page-navi-slider');
		echo "</b><br/>";
		_e('Example : Set the margin to &laquo;1em 20% 0 20%&raquo;','page-navi-slider');
		echo "<br/>";
		_e('The plugin will use 60% of the available width and will apply a top margin of 1 em.','page-navi-slider');
		echo "</span>";
	}
	
	public function print_caption_info(){}
	
	public function print_font_info(){
		echo "<span class='wpns_setting_small'>";
		_e('CSS shorthand; Syntax : font-weight font-style font-variant font-size line-height font-family.','page-navi-slider');
		echo "<br/>";
		_e('Choosing a bigger size for <b>Current</b> and/or <b>On hover</b> will occur a <b>zoom effect</b>.','page-navi-slider');
		echo "<br/>";
		echo "</span>";
	}
	
	public function print_color_info(){
		echo "<span class='wpns_setting_small'>";
		_e('Let empty to use the parent element color.','page-navi-slider');
		echo "<br/>";
		_e('<b>Advanced styles allowed</b> - e.g. radial-gradient(ellipse at center, rgba(0,0,0,0.65) 0%,rgba(0,0,0,0) 100%)','page-navi-slider');
		echo "<br/>";
		_e('<b>transparent</b> allowed.','page-navi-slider');
		echo "<br/>";
		echo "</span>";
	}
	
	public function print_border_info(){
		echo "<span class='wpns_setting_small'>";
		_e('Use CSS shorthand; Syntax : thickness style color.','page-navi-slider');
		echo "<br/>";
		_e('Let empty for no border.','page-navi-slider');
		echo "<br/>";
		_e('Choosing a color will generate a 1 px border; can be modified later.','page-navi-slider');
		echo "<br/>";
		_e('<b>High radius</b> will occur in <b>surrounded</b> page nubers.','page-navi-slider');
		echo "<br/>";
		echo "</span>";
	}
	
	public function print_autodisplay_info(){
		echo "<span class='wpns_setting_small'><b>";
		_e('This feature depends on your theme and could return unexpected results.','page-navi-slider');
		echo "</b><br/>";
		_e('Prefer manual installation by adding the following code in your theme templates where you want the plugin to be displayed:','page-navi-slider');
		echo "<br/><b>";
		echo "&lt;php if(function_exists('page_navi_slider')){page_navi_slider();}?&gt;";
		echo "</b></span>";
	}

	// Form fields section
	// Text fields
	public function wpns_text_field_callback($args){
		$setting=$args['setting'];
		$class=$args['class'];
		$style=$args['style'];
		printf('<input type="text" class="'.$class.'" style="'.$style.'" id="'.$setting.'" name="wpns_settings_preview['.$setting.']" value="%s" />',esc_attr($this->_options[$setting]));
	}
	
	// Hidden fields
	public function wpns_hidden_field_callback($args){
		$setting=$args['setting'];
		$class=$args['class'];
		$style=$args['style'];
		printf('<input type="hidden" class="'.$class.'" style="'.$style.'" id="'.$setting.'" name="wpns_settings_preview['.$setting.']" value="%s" />',esc_attr($this->_options[$setting]));
	}
	
	// CheckBoxes
	public function wpns_checkbox_field_callback($setting){
		printf('<input type="checkbox" id="'.$setting.'" name="wpns_settings_preview['.$setting.']" value="1"' . checked( 1,$this->_options[$setting],false) . '/>',esc_attr($this->_options[$setting]));
	}
	
	// Options
	public function wpns_option_field_callback($args){
		$setting=$args['setting'];
		$values=$args['values'];
		$style=$args['style'];
		printf('<select type="text" style="'.$style.'" id="'.$setting.'" name="wpns_settings_preview['.$setting.']" value="%s" />',esc_attr($this->_options[$setting]));
		foreach ($values as $option){
			printf('<option value="'.$option.'"'.selected($option,$this->_options[$setting],false).'>'.$option.'</option>');
		}
	printf('</select>');
	}	
	
	// Fonts
	public function wpns_font_block_callback($setting){
		self::wpns_option_field_callback(array('setting'=>$setting."_style",'values'=>array('normal','italic','oblique'),'style'=>'width : 6em'));
		self::wpns_option_field_callback(array('setting'=>$setting."_variant",'values'=>array('normal','small-caps','oblique'),'style'=>'width : 8em'));
		self::wpns_option_field_callback(array('setting'=>$setting.'_weight','values'=>array('normal','bold','bolder','lighter','inherit'),'style'=>'width : 6em'));
		printf('<br/>');
		self::wpns_text_field_callback(array('setting'=>$setting.'_size','style'=>'width : 6em; text-align : right'));
		printf('/').
		self::wpns_text_field_callback(array('setting'=>$setting.'_lineheight','style'=>'width : 6em'));
		self::wpns_text_field_callback(array('setting'=>$setting.'_family','style'=>'width : 18em'));
	}

	public function wpns_display_preview(){
		_e('Click on the right border of the preview area to resize it !','page-navi-slider');
	?>
		<div id="wpns_resizable" style="background-color : <?php echo $this->_options['wpns_preview_background_color']; ?>">
			<?php	if (($this->_options['wpns_preview_pages'] == 1) and ($this->_options['wpns_display_if_one_page'] !='1')) : ?>
				<i><?php _e('Nothing to display as there is only one page','page-navi-slider');?><br>
				<?php _e('You can change the &ldquo;Display even if there is only one page&rdquo; option in general settings','page-navi-slider');?></i>
			<?php	else: ?>
				<i> <?php _e('Your page','page-navi-slider'); ?></i>
					<?php
					for ($i = 0; $i < $this->_options['wpns_preview_pages']; $i++){
						$pagination[$i]='<a class="page-numbers" href="options-general.php?current='.($i+1).'&page=wpns_settings_admin">'.($i+1).'</a>';
					}
					wpns_frontend($this->_options['wpns_preview_current'],$this->_options['wpns_preview_pages'],$pagination,$this->_options);
					?>
				<i><?php _e('rest of your page...','page-navi-slider'); ?></i>
			
			<?php endif; ?>
		</div>
	<?php 
	}

}

if( is_admin() ) $wpns_settings_page = new WPNS_SETTING_PAGE($default_settings);


?>