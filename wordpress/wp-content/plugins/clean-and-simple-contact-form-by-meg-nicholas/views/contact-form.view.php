<div id="cscf" class="cscfBlock">
    <div class="cscfVersion" style="display:none;">
       <a href="http://www.megnicholas.co.uk?version=<?php echo urlencode($version); ?>">Clean and Simple WordPress Contact Form by Meg Nicholas - WordPress Developer</a>
    </div> 
    <div class="cscfMessageSent" style="display:none;">
        <?php echo $messageSentView->Render(); ?>
    </div>
    <div class="cscfMessageNotSent" style="display:none;">
        <?php echo $messageNotSentView->Render(); ?>
    </div>
    <div class="cscfForm">
        <p><?php echo $message; ?></p>
        <form role="form" id="frmCSCF" name="frmCSCF" method="post">
            <?php wp_nonce_field('cscf_contact','cscf_nonce'); ?>
            <input type="hidden" name="post-id" value="<?php echo $postID; ?>">
            
            <?php if (isset($contact->Errors['recaptcha'])) { ?>
                <div class="control-group form-group">
                    <p class="text-error"><?php echo $contact->Errors['recaptcha']; ?></p>
                </div>            
            <?php } ?>

            <!--email address-->
            <div class="control-group form-group<?php if (isset($contact->Errors['email'])) echo ' error has-error'; ?>">
                <label for="cscf_email"><?php _e('Email Address:','cleanandsimple');?></label>
                <div class="<?php echo cscf_PluginSettings::InputIcons() ? "input-group" : ""; ?>">
                    <?php if ( cscf_PluginSettings::InputIcons() == true ) { ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <?php } ?>
                    <input class="form-control input-xlarge"
                        data-rule-required="true"
                        data-rule-email="true"
                        data-msg-required="<?php _e('Please give your email address.','cleanandsimple');?>"
                        data-msg-email="<?php _e('Please enter a valid email address.','cleanandsimple');?>"
                        type="email" id="cscf_email" name="cscf[email]" 
                        value="<?php echo $contact->Email; ?>" 
                        placeholder="<?php _e('Your Email Address','cleanandsimple');?>"
                    />
                </div>
                <span for="cscf_email" class="help-inline help-block error" style="display:<?php echo isset($contact->Errors['email']) ? 'block' : 'none'; ?>;">
                    <?php if (isset($contact->Errors['email'])) echo $contact->Errors['email']; ?>
                </span>
            </div>

            <?php if ( $confirmEmail ) { ?>
            <!--confirm email address -->
            <div class="control-group form-group<?php if (isset($contact->Errors['confirm-email'])) echo ' error has-error'; ?>">
                <label for="cscf_confirm-email"><?php _e('Confirm Email Address:','cleanandsimple');?></label>
                <div class="<?php echo cscf_PluginSettings::InputIcons() ? "input-group" : ""; ?>">
                    <?php if ( cscf_PluginSettings::InputIcons() == true ) { ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <?php } ?>
                    <input class="form-control input-xlarge"
                        data-rule-required="true"
                        data-rule-email="true"
                        data-rule-equalTo="#cscf_email"
                        data-msg-required="<?php _e('Please enter the same email address again.','cleanandsimple');?>"
                        data-msg-email="<?php _e('Please enter a valid email address.','cleanandsimple');?>"
                        data-msg-equalTo="<?php _e('Please enter the same email address again.','cleanandsimple');?>"
                        type="email" id="cscf_confirm-email" name="cscf[confirm-email]" 
                        value="<?php echo $contact->ConfirmEmail; ?>" 
                        placeholder="<?php _e('Confirm Your Email Address','cleanandsimple');?>"
                    />
                </div>
                <span for="cscf_confirm-email" class="help-inline help-block error" style="display:<?php echo isset($contact->Errors['confirm-email']) ? 'block' : 'none'; ?>;">
                    <?php if (isset($contact->Errors['confirm-email'])) echo $contact->Errors['confirm-email']; ?>
                </span>
            </div> 
            <?php } ?>

            <!-- name --> 
            <div class="control-group form-group<?php if (isset($contact->Errors['name'])) echo ' error has-error'; ?>">
                <label for="cscf_name"><?php _e('Name:','cleanandsimple');?></label>
                <div class="<?php echo cscf_PluginSettings::InputIcons() ? "input-group" : ""; ?>">
                    <?php if ( cscf_PluginSettings::InputIcons() == true ) { ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                    <?php } ?>
                    <input class="form-control input-xlarge"
                       data-rule-required="true"
                       data-msg-required="<?php _e('Please give your name.','cleanandsimple');?>"
                       type="text" id="cscf_name" name="cscf[name]" 
                       value="<?php echo $contact->Name; ?>" 
                       placeholder="<?php _e('Your Name','cleanandsimple');?>"
                    />
                </div>
                <span for="cscf_name" class="help-inline help-block error" style="display:<?php echo isset($contact->Errors['name']) ? 'block' : 'none'; ?>;">
                    <?php if (isset($contact->Errors['name'])) echo $contact->Errors['name']; ?>
                </span>
            </div>  

            <!-- message -->
            <div class="control-group form-group<?php if (isset($contact->Errors['message'])) echo ' error has-error'; ?>">
                <label for="cscf_message"><?php _e('Message:','cleanandsimple');?></label>
                <div class="<?php echo cscf_PluginSettings::InputIcons() ? "input-group" : ""; ?>">
                    <?php if ( cscf_PluginSettings::InputIcons() == true ) { ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
                    <?php } ?>
                    <textarea class="form-control input-xlarge"
                        data-rule-required="true"
                        data-msg-required="<?php _e('Please give a message.','cleanandsimple');?>"
                        id="cscf_message" name="cscf[message]" rows="10" 
                        placeholder="<?php _e('Your Message','cleanandsimple');?>"><?php echo $contact->Message; ?></textarea>
                </div>
                <span for="cscf_message" class="help-inline help-block error" style="display:<?php echo isset($contact->Errors['message']) ? 'block' : 'none'; ?>;">
                    <?php if (isset($contact->Errors['message'])) echo $contact->Errors['message']; ?>
                </span>
            </div>

            <!-- recaptcha -->
            <?php if ( $contact->RecaptchaPublicKey<>'' && $contact->RecaptchaPrivateKey<>'') { ?>
                <script type="text/javascript">
                 var RecaptchaOptions = {
                    theme : '<?php echo cscf_PluginSettings::Theme(); ?>'
                 };
                 </script>            
                <div class="control-group form-group<?php 
                    if (isset($contact->Errors['recaptcha'])) echo ' error'; ?>">
                        <div id="recaptcha_div">
                            <?php echo cscf_recaptcha_get_html($contact->RecaptchaPublicKey,null,isset($_SERVER['HTTPS'])); ?>
                        <div for="cscf_recaptcha" class="help-block has-error error"><?php if (isset($contact->Errors['recaptcha'])) echo $contact->Errors['recaptcha']; ?></div> 
                     </div>	
                </div>	
            <?php } ?>     
                <input type="submit" class="btn btn-default" value="<?php _e('Send Message','cleanandsimple');?>"/>
        </form>
    </div>
</div>