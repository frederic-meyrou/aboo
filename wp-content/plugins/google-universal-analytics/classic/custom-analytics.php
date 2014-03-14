<div class="wrap">
  <h2>Custom Google Analytics</h2>
 
  <br />
  <em>This is a place to customize your tracking code. </em>
  <br />
  <br />
  <div class="col-lg-6 row">
      <form class="form-horizontal" role="form" id="google-universal-options">
        
        <div class="form-group">
          <label for="web_property_id" class="col-sm-3 control-label">Status</label>
          <div class="col-sm-9">
            <input id="custom_plugin_switch" type="checkbox" name="custom_plugin_switch" <?php if(get_option('custom_plugin_switch')=='on'): ?> checked="checked" <?php endif; ?>>
            
          </div>
        </div>
        <div class="form-group">
          <label for="web_property_id" class="col-sm-3 control-label">Tracking Code</label>
          <div class="col-sm-9">
            <textarea class="form-control" name="custom_web_property_id" id="custom_web_property_id" rows="6" placeholder="Past your custom google tracking code here"><?php echo get_option('custom_web_property_id'); ?></textarea>
            
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="custom_in_footer" id="custom_in_footer" <?php if(get_option('custom_in_footer')=='on'): ?> checked="checked" <?php endif; ?>>
                Place code in footer </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
          <?php global $wp_roles;
     $roles = $wp_roles->get_names(); ?>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="custom_tracking_off_for_role" id="custom_tracking_off_for_role" <?php if(get_option('custom_tracking_off_for_role')=='on'): ?> checked="checked" <?php endif; ?>>
               Disable Tracking For <select id="custom_tracking_off_for_this_role">
               	<?php foreach($roles as $role) { ?>
                <option value="<?php echo $role;?>" <?php if(get_option('custom_tracking_off_for_this_role')== $role){echo 'selected="selected"';} ?>><?php echo $role;?></option>
                <?php } ?>
               </select> </label>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
            <input type="hidden" id="ajax_url" name="ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
            <button type="button" class="btn btn-primary" id="save-custom-settings">Save Changes</button><span class="alert alert-success hide"><strong>Options Saved</strong></span>
          </div>
        </div>
      </form>
  </div>
  <div class="clearfix"></div>
  <div class="row col-lg-6">Have a question? Drop us a question at <a href="http://onlineads.lt/?utm_source=WordPress&utm_medium=Google%20Universal%20Analytics%202.1&utm_content=Google%20Custom%20Analytics&utm_campaign=WordPress%20plugins">OnlineAds.lt</a> </div>
</div>
</br>
