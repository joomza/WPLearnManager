<div class="jslm_main-up-wrapper">
<?php
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
if(jslearnmanager::$_error_flag_message != null){
    echo jslearnmanager::$_error_flag_message;

}else{
if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
    // check to make sure user registration is enabled
    $is_enable = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('showstudentlink');
    // only show the registration form if allowed
    if ($is_enable) {	?>

<?php 
	$genderarray = array(
    	(object) array('id' => 1, 'text' => __('Male', 'learn-manager')),
    	(object) array('id' => 2, 'text' => __('Female', 'learn-manager')),
    );
    function checkLinks($name) {
        foreach (jslearnmanager::$_data[2] as $field) {
            $array =  array();
            $array[0] = 0;
            switch ($field->field) {
                case $name:
                if($field->showonlisting == 1){
                    $array[0] = 1;
                    $array[1] =  $field->fieldtitle;
                }
                return $array;
                break;
            }
        }
        return $array; 
    }
?>
	<div class="jslm_content_wrapper">
	    <div class="jslm_content_data"> <!-- Bottom_content_data -->
	        <div class="jslm_search_content no-border">
                <div class="jslm_top_title">
                    <div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Register as a Student","learn-manager"); ?></h3></div>
                </div>
            </div>
	        <div class="jslm_data_container no-padding-top">
	        	<div class="jslm_register_wrapper">
		        	<?php jslearnmanager_show_error_messages(); ?>
			        <form id="jslearnmanager_registration_form" class="form-validate jslm_has-validation-callback" name="jslearnmanager-learnmanager-register-nonce" method="POST" enctype="multipart/form-data">
				        <?php $print = checkLinks('user_image');
				        if($print[0] == 1){	 ?>
					        <div class="jslm_left_data">
					           	<div class="jslm_img_wrapper">
					           		<img alt="<?php echo esc_attr(__("User image","learn-manager")); ?>" title="<?php echo esc_attr(__("User image","learn-manager")); ?>" id="imagePreview" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png'); ?>">
					           	</div>
					          	<div class="jslm_file_upload">
				                    <label for="upload" class="jslm_file_upload_label"><?php echo esc_html__("Upload Image","learn-manager"); ?></label>
				                    <input id="upload" class="jslm_file_upload_input" type="file" name="profilephoto" accept="image/*" />
				                </div>
					           	<span class="jslm_text">
					          	 	<?php $file_size = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_profileimage_size');
				                    $file_ext = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
				                    echo esc_html__("Max Image Size ","learn-manager"); echo esc_html($file_size)." KB";
				                    echo "<br/>".esc_html__("Extensions Allowed ","learn-manager"); echo esc_html($file_ext); ?>
					           	</span>
					        </div>
					    <?php } ?>    
			        	<div class="jslm_right_data">
			            	<div class="jslm_input_wrapper">
			                  	<div for="jslearnmanager_user_Login" class="jslm_input_title jslm_big_font"><?php _e('Username','learn-manager'); ?><font color="red">&nbsp*</font></div>
			                  	<div class="jslm_input_field"><input type="text" name="jslearnmanager_user_login" id="jslearnmanager_user_login" class="jslm_input_style" placeholder="<?php echo __("Username","learn-manager"); ?>" data-validation="required"></div>
			               	</div>
			               	<div class="jslm_input_wrapper">
			                  	<div for="jslearnmanager_user_email" class="jslm_input_title jslm_big_font"><?php _e('Email','learn-manager'); ?><font color="red">&nbsp*</font></div>
			                  	<div class="jslm_input_field"><input type="email" name="jslearnmanager_user_email" id="jslearnmanager_user_email" class="jslm_input_style" placeholder="<?php echo __("Email","learn-manager"); ?>" data-validation="email required"></div>
			               	</div>
			               	<div class="jslm_input_wrapper">
			                  	<div for="jslearnmanager_user_first" class="jslm_input_title jslm_big_font"><?php _e('First Name','learn-manager'); ?><font color="red">&nbsp*</font></div>
			                  	<div class="jslm_input_field"><input type="text" name="jslearnmanager_user_first" id="jslearnmanager_user_first" class="jslm_input_style" placeholder="<?php echo __("First Name","learn-manager"); ?>" data-validation="required"></div>
			               	</div>
			               	<div class="jslm_input_wrapper">
			                  	<div for="jslearnmanager_user_last" class="jslm_input_title jslm_big_font"><?php _e('Last Name','learn-manager'); ?><font color="red">&nbsp*</font></div>
			                  	<div class="jslm_input_field"><input type="text" name="jslearnmanager_user_last" id="jslearnmanager_user_last" class="jslm_input_style" placeholder="<?php echo __("Last Name","learn-manager"); ?>" data-validation="required"></div>
			               	</div>
			               	<div class="jslm_input_wrapper">
			                  	<div for="password" class="jslm_input_title jslm_big_font"><?php _e('Password','learn-manager'); ?><font color="red">&nbsp*</font></div>
			                  	<div class="jslm_input_field"><input type="password" name="jslearnmanager_user_pass" id="jslearnmanager_user_pass" class="jslm_input_style" placeholder="<?php echo __("Enter Password","learn-maanger"); ?>" data-validation="required"></div>
			               	</div>
			               	<div class="jslm_input_wrapper">
			                  	<div for="password_again" class="jslm_input_title jslm_big_font"><?php _e('Password Again','learn-manager'); ?><font color="red">&nbsp*</font></div>
			                  	<div class="jslm_input_field"><input type="password" name="jslearnmanager_user_pass_confirm" id="jslearnmanager_user_pass_confirm" class="jslm_input_style" placeholder="<?php echo __("Enter Password Again","learn-manager"); ?>" data-validation="required"></div>
			               	</div>
			               	<?php
			               		do_action('register_form');
	                    	?>
	                    	<?php foreach (jslearnmanager::$_data[2] as $field ) { 
	                    			switch($field->field){ 
	                    				case 'gender':	?>
											<div class="jslm_input_wrapper">
							                  	<div class="jslm_input_title jslm_big_font"><?php echo esc_html($field->fieldtitle,'learn-manager'); ?></div>
							                  	<?php
				                                    $req = '';
				                                    if ($field->required == 1) {
				                                        $req = 'required';
				                                        echo '<font color="red">&nbsp*</font>';
				                                    }
		                                    	?>
							                  	<div class="jslm_input_field_select">
							                     	<?php echo wp_kses(JSLEARNMANAGERformfield::select($field->field, $genderarray, '', __('Select Gender', 'learn-manager'), array('class' => 'jslm_input_style jslm_input_title', 'data-validation'=>$req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
							                    </div>
							               	</div>
							            <?php 
							            break;
							            case 'country':	?>   	
								               	<div class="jslm_input_wrapper">
								                  	<div class="jslm_input_title"><?php echo esc_html($field->fieldtitle,'learn-manager'); ?></div>
								                  	<?php
				                                    $req = '';
				                                    if ($field->required == 1) {
				                                        $req = 'required';
				                                        echo '<font color="red">&nbsp*</font>';
				                                    }
		                                    		?>
								                  	<div class="jslm_input_field_select ">
								                     	<?php echo wp_kses(JSLEARNMANAGERformfield::select('country_id', JSLEARNMANAGERincluder::getJSModel('country')->getCountriesForCombo(), '', __('Select Country', 'learn-manager') , array('class' => 'jslm_input_style jslm_input_title', 'data-validation'=>$req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
								                  	</div>
								               	</div>
								        <?php 
								        break;
								        case 'bio': ?>       	
								               	<div class="jslm_input_wrapper">
								                  	<div class="jslm_input_title jslm_input_title"><?php echo esc_html($field->fieldtitle,'learn-manager'); ?></div>
								                  	<?php
				                                    $req = '';
				                                    if ($field->required == 1) {
				                                        $req = 'required';
				                                        echo '<font color="red">&nbsp*</font>';
				                                    }
		                                    		?>
								                  	<div class="jslm_input_field"><?php echo wp_editor('', $field->field, array('media_buttons' => false, 'data-validation' => $req, 'class'=>'jslm_input_style', 'rows'=>8)); ?></div>
								               	</div>
								        <?php 
								        break;
								        case 'weblink' : ?>
								        		<div class="jslm_input_wrapper">
								               		<div class="jslm_input_title jslm_big_font"><?php echo esc_html($field->fieldtitle,'learn-manager'); ?>
								                  	<?php
					                                    $req = '';
					                                    if ($field->required == 1) {
					                                        $req = 'required';
					                                        echo '<font color="red">&nbsp*</font>';
					                                    }
			                                    	?>
			                                    	</div>
							                  		<div class="jslm_input_field"><?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, '', array('class'=>'jslm_input_style', 'placeholder'=>__('Enter Website Address','learn-manager'),'data-validation' => 'url '.$req, 'data-validation-optional' => "true")), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
							               		</div>
								        <?php 
								        break; 
								        case 'sociallinks':	?>       	
								               	<div class="jslm_input_wrapper">
								                  	<div class="jslm_input_title"><?php echo esc_html($field->fieldtitle,'learn-manager'); ?></div>
								                  	<div class="jslm_input_field"><input type="text" name="facebook_url" class="jslm_input_style" placeholder="<?php echo esc_html__("Facebook","learn-manager");?>"></div>
	                                       			<div class="jslm_input_field"><input type="text" name="twitter" class="jslm_input_style" placeholder="<?php echo esc_html__("Twitter","learn-manager"); ?>"></div>
	                                       			<div class="jslm_input_field"><input type="text" name="linkedin" class="jslm_input_style" placeholder="<?php echo esc_html__("Linkedin","learn-manager"); ?>"></div>
								               	</div>
								        <?php 
								        break;
								        default:
	                                		echo JSLEARNMANAGERincluder::getObjectClass('customfields')->formCustomFields($field,3);
	                              		break;			 
								        	}	
								        		} 
								        ?> 
							<?php 	$config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('captcha');
		                    		$google_recaptcha = false;
				                    if ($config_array['cap_on_reg_form'] == 1) {
				                        if ($config_array['captcha_selection'] == 1) { // Google recaptcha
				                            $google_recaptcha = true; 
				                            ?>
				                            <div class="jslm_input_wrapper">
				                            	<div class="g-recaptcha" data-sitekey="<?php echo esc_attr($config_array['recaptcha_publickey']);?>"></div>
				                            </div>
				                        <?php
				                        } else { // own captcha
				                            $captcha = new JSLEARNMANAGERcaptcha;
				                            echo wp_kses($captcha->getCaptchaForForm(), JSLEARNMANAGER_ALLOWED_TAGS);
				                        }
				                    } ?>	
	                	</div>
						<div class="jslm_bottom_button">
	               			<input type="hidden" name="jslearnmanager_learnmanager_register_nonce" value="<?php echo wp_create_nonce('jslearnmanager-learnmanager-register-nonce'); ?>"/>
		                  	<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_newlearner_register', 'JSLEARNMANAGER_newlearner_register'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
		                  	<input class="jslm_button" type="submit" value="<?php esc_attr(_e('Register New Account','learn-manager')); ?>">
	                  	 	<a class="jslm_button jslm_cancel" href="<?php echo esc_url(jslearnmanager::makeUrl()); ?>"><?php echo __("Cancel","learn-manager"); ?></a>
	                  	</div>
			    	</form>
		    	</div>
		    </div>
		</div>  
	</div>
<?php
    }else{
    	JSLEARNMANAGERlayout::getRegistrationNotAllow();
    }
} 

if(isset($google_recaptcha) && $google_recaptcha){ ?>
	<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		wp_enqueue_script('jquery-google-repaptcha', $protocol.'www.google.com/recaptcha/api.js');
	?>
<?php 
}
}
?>
</div>

