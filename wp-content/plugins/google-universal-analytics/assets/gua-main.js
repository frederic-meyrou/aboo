jQuery(document).ready(function(e) {
jQuery( "#google-universal-options" ).submit(function( event ) {
  
 	var property_id	=	jQuery('#web_property_id').val();
  	if(property_id.length > 0){
		if(property_id.indexOf('UA')==-1){
			jQuery('#code-error').removeClass('hide');
			event.preventDefault();
		}
	}else{
	jQuery('#code-error').removeClass('hide');
	//alert('dont proceed');
	event.preventDefault();	//	
	}	
  
  
  
  
});
   

    jQuery('#classic_plugin_switch').bootstrapSwitch('size', 'small');
	jQuery('#classic_plugin_switch').bootstrapSwitch('onColor', 'success');
	jQuery('#classic_plugin_switch').bootstrapSwitch('offColor', 'danger');

	

	jQuery('#plugin_switch').bootstrapSwitch('size', 'small');
	jQuery('#plugin_switch').bootstrapSwitch('onColor', 'success');
	jQuery('#plugin_switch').bootstrapSwitch('offColor', 'danger');



	jQuery('#custom_plugin_switch').bootstrapSwitch('size', 'small');
	jQuery('#custom_plugin_switch').bootstrapSwitch('onColor', 'success');
	jQuery('#custom_plugin_switch').bootstrapSwitch('offColor', 'danger');






});