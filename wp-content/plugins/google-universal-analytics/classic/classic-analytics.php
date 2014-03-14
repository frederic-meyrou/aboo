<div class="wrap">
  <h2>Google Classic Analytics<em> (ga.js)</em></h2>
  
  <br />
  <em>This is an older version of Google Analytics. Use this version only if you have not upgraded to Universal Analytics yet or want to use both trackers (which is totally fine!).</em>
  <br />
  <br />
  <div class="col-lg-6 row">
      <form class="form-horizontal" role="form" id="classic-google-universal-options">
        
        <div class="form-group">
          <label for="web_property_id" class="col-sm-3 control-label">Status</label>
          <div class="col-sm-9">
            <input id="classic_plugin_switch" type="checkbox" name="classic_plugin_switch" <?php if(get_option('classic_plugin_switch')=='on'): ?> checked="checked" <?php endif; ?>>
            
          </div>
        </div>
        <div class="form-group">
          <label for="classic_web_property_id" class="col-sm-3 control-label">Tracking ID</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="classic_web_property_id" id="classic_web_property_id" placeholder="Tracking code example: UA-­23710779-­7" value="<?php echo get_option('classic_property_id'); ?>">
            <span class="error hide"><strong>Error! </strong> match your code with this forma: UA-41335660-1</span>
          </div>
        </div>
        
        
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="classic_in_footer" id="classic_in_footer" <?php if(get_option('classic_in_footer')=='on'): ?> checked="checked" <?php endif; ?>>
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
                <input type="checkbox" name="classic_tracking_off_for_role" id="classic_tracking_off_for_role" <?php if(get_option('classic_tracking_off_for_role')=='on'): ?> checked="checked" <?php endif; ?>>
               Disable Tracking For <select id="classic_tracking_off_for_this_role">
               	<?php foreach($roles as $role) { ?>
                <option value="<?php echo $role;?>" <?php if(get_option('classic_tracking_off_for_this_role')== $role){echo 'selected="selected"';} ?>><?php echo $role;?></option>
                <?php } ?>
               </select> </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
            <input type="hidden" id="ajax_url" name="ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
            <button type="button" class="btn btn-primary" id="save-classic-settings">Save Changes</button><span class="alert alert-success hide"><strong>Options Saved</strong></span>
          </div>
        </div>
      </form>
  </div>
  <div class="clearfix"></div>
  <div class="row col-lg-6">Have a question? Drop us a question at <a href="http://onlineads.lt/?utm_source=WordPress&utm_medium=Google%20Universal%20Analytics%202.1&utm_content=Google%20Classic%20Analytics&utm_campaign=WordPress%20plugins" title="Google Universal Analytics">OnlineAds.lt</a> </div>
</div>
</br>
