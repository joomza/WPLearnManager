<div class="jslm_main-up-wrapper">
<?php
    $msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
    JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
    JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
    include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');

    $genderarray = array(
      (object) array('id' => "Male", 'text' => __('Male', 'learn-manager')),
      (object) array('id' => "Female", 'text' => __('Female', 'learn-manager')),
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
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('.jslm_select_full_width').selectable();
  });
</script>
<?php if(!empty(jslearnmanager::$_data[0])){
    $row = jslearnmanager::$_data[0];
?>
    <div class="jslm_content_wrapper"> <!-- lower bottom Content -->
        <div class="jslm_content_data">
            <div class="jslm_search_content no-border">
                <div class="jslm_top_title">
                    <div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Edit Profile","learn-manager"); ?></h3></div>
                </div>
            </div>
            <div class="jslm_data_container no-padding-top">
                <div class="jslm_register_wrapper">
                    <form id="jslearnmanager_registration_form" name="jslearnmanager-learnmanager-register-nonce" method="POST" enctype="multipart/form-data" action="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'action'=>'jslmstask', 'task'=>'saveprofile', 'jslearnmanagerpageid'=>jslearnmanager::getPageid()))); ?>" >
                        <fieldset>
                            <?php $print = checkLinks('user_image');
                            if($print[0] == 1){ ?>
                                <div class="jslm_left_data">
                                    <div class="jslm_img_wrapper">
                                        <?php if(isset(jslearnmanager::$_data[0]->image) && jslearnmanager::$_data[0]->image != ""){  ?>
                                            <img alt="<?php echo esc_attr(__("User image","learn-manager")); ?>" title="<?php echo esc_attr(__("User image","learn-manager")); ?>" id="imagePreview" src="<?php echo esc_attr(jslearnmanager::$_data[0]->image); ?>" data-default-src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png'); ?>">
                                        <?php }else{ ?>
                                            <img alt="<?php echo esc_attr(__("User image","learn-manager")); ?>" title="<?php echo esc_attr(__("User image","learn-manager")); ?>" id="imagePreview" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png'); ?>" data-default-src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png'); ?>">
                                        <?php } ?>
                                    </div>
                                    <div class="jslm_file_upload">
                                        <label for="upload" class="jslm_file_upload_label"><?php echo __("Upload Image","learn-manager"); ?></label>
                                        <input id="upload" class="jslm_file_upload_input" type="file" name="profilephoto" accept="image/*" />
                                    </div>
                                    <span class="jslm_text">
                                        <?php if(isset(jslearnmanager::$_data[0]->image) && jslearnmanager::$_data[0]->image != ""){  ?>
                                            <a id="user_profileimage" class="jslm_delete_image_user" href="#" onclick="deleteUploadedFileOrImage('user_profileimage','jslms_user_image_del')"><?php echo __("Delete image","learn-manager"); ?></a>
                                            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden("jslms_user_image_del", ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                        <?php } ?>
                                        <?php $file_size = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_profileimage_size');
                                        $file_ext = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type'); ?>
                                        <?php echo __("Max Image Size ","learn-manager"); ?><?php echo __($file_size,"learn-manager"). __(" kb","learn-manager");
                                        echo "<br/>".__("Extensions Allowed ","learn-manager"); echo __($file_ext,"learn-manager"); ?>
                                    </span>
                                </div>
                            <?php } ?>
                            <div class="jslm_right_data">
                            <?php foreach (jslearnmanager::$_data[2] as $field ) {
                                    switch($field->field){
                                        case 'firstname' : ?>
                                            <div class="jslm_input_wrapper">
                                                <div class="jslm_input_title jslm_big_font"><?php echo esc_html(__($field->fieldtitle,'learn-manager')); ?>
                                                <?php
                                                    $req = '';
                                                    if ($field->required == 1) {
                                                        $req = 'required';
                                                        echo '<font color="red">&nbsp*</font>';
                                                    }
                                                ?>
                                                </div>
                                                <div class="jslm_input_field"><?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->firstname) ? jslearnmanager::$_data[0]->firstname : '' , array('class'=>'jslm_input_style', 'placeholder'=> __('First Name','learn-manager'),'data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                                            </div>
                                    <?php
                                        break;
                                        case 'lastname' : ?>
                                            <div class="jslm_input_wrapper">
                                                <div class="jslm_input_title jslm_big_font"><?php echo esc_html(__($field->fieldtitle,'learn-manager')); ?>
                                                <?php
                                                   $req = '';
                                                   if ($field->required == 1) {
                                                       $req = 'required';
                                                       echo '<font color="red">&nbsp*</font>';
                                                   }
                                                ?>
                                                </div>
                                                <div class="jslm_input_field"><?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->lastname) ? jslearnmanager::$_data[0]->lastname : ' ', array('class'=>'jslm_input_style', 'placeholder'=>__('Last Name','learn-manager'),'data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                                            </div>
                                    <?php
                                        break;
                                        case 'gender': ?>
                                            <div class="jslm_input_wrapper">
                                                <div class="jslm_input_title jslm_big_font"><?php echo esc_html(__($field->fieldtitle,'learn-manager')); ?>
                                                    <?php
                                                    $req = '';
                                                    if ($field->required == 1) {
                                                        $req = 'required';
                                                        echo '<font color="red">&nbsp*</font>';
                                                    }
                                                    ?>
                                                </div>
                                                <div class="jslm_input_field_select">
                                                    <?php  echo wp_kses(JSLEARNMANAGERformfield::select($field->field, $genderarray,isset(jslearnmanager::$_data[0]->gender) ? jslearnmanager::$_data[0]->gender :'',__($field->fieldtitle,'learn-manager'), array('class' => 'jslm_input_style jslm_input_title','data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                    <?php
                                        break;
                                        case 'country':   ?>
                                            <div class="jslm_input_wrapper">
                                                <div class="jslm_input_title"><?php echo esc_html(__($field->fieldtitle,'learn-manager')); ?>
                                                    <?php
                                                    $req = '';
                                                    if ($field->required == 1) {
                                                        $req = 'required';
                                                        echo '<font color="red">&nbsp*</font>';
                                                    }
                                                    ?>
                                                </div>
                                                <div class="jslm_input_field_select">
                                                    <?php echo wp_kses(JSLEARNMANAGERformfield::select('country_id', JSLEARNMANAGERincluder::getJSModel('country')->getCountriesForCombo(), isset(jslearnmanager::$_data[0]->country_id) ? jslearnmanager::$_data[0]->country_id : '', __('Select Country', 'learn-manager'), array('class' => 'jslm_input_style jslm_input_title', 'data-validation'=>$req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                    <?php
                                        break;
                                        case 'bio': ?>
                                            <div class="jslm_input_wrapper">
                                                <div class="jslm_input_title jslm_input_title"><?php echo esc_html(__($field->fieldtitle,'learn-manager')); ?>
                                                    <?php
                                                    $req = '';
                                                    if ($field->required == 1) {
                                                        $req = 'required';
                                                        echo '<font color="red">&nbsp*</font>';
                                                    }
                                                    ?>
                                                </div>
                                                <!-- <div class="jslm_input_field"><?php echo wp_kses(JSLEARNMANAGERformfield::textarea($field->field, isset(jslearnmanager::$_data[0]->bio) ? jslearnmanager::$_data[0]->bio : '', array('class'=>'jslm_input_style','data-validation' => $req, 'rows'=>8)), JSLEARNMANAGER_ALLOWED_TAGS); ?></div> -->
                                                <div class="jslm_input_field"><?php echo wp_editor(isset(jslearnmanager::$_data[0]->bio) ? jslearnmanager::$_data[0]->bio : '', $field->field, array('media_buttons' => false, 'data-validation' => $req, 'class'=>'jslm_input_style', 'rows'=>8)); ?></div>
                                            </div>
                                    <?php
                                        break;
                                        case 'weblink' : ?>
                                            <div class="jslm_input_wrapper">
                                                <div class="jslm_input_title jslm_big_font"><?php echo esc_html(__($field->fieldtitle,'learn-manager')); ?>
                                                <?php
                                                    $req = '';
                                                    if ($field->required == 1) {
                                                       $req = 'required';
                                                       echo '<font color="red">&nbsp*</font>';
                                                    }
                                                ?>
                                                </div>
                                                <div class="jslm_input_field"><?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->weblink) ? jslearnmanager::$_data[0]->weblink : '', array('class'=>'jslm_input_style', 'placeholder'=>__('Enter Website Address','learn-manager'),'data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                                            </div>
                                    <?php
                                        break;
                                        case 'sociallinks':   ?>
                                            <div class="jslm_input_wrapper">
                                                <div class="jslm_input_title"><?php echo esc_html(__($field->fieldtitle,'learn-manager')); ?></div>
                                                <div class="jslm_input_field"><?php echo wp_kses(JSLEARNMANAGERformfield::text("facebook_url", isset(jslearnmanager::$_data[0]->facebook_url) ? jslearnmanager::$_data[0]->facebook_url : '', array('class'=>'jslm_input_style', 'placeholder'=>__('Enter Facebook Address','learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                                                <div class="jslm_input_field"><?php echo wp_kses(JSLEARNMANAGERformfield::text("twitter", isset(jslearnmanager::$_data[0]->twitter) ? jslearnmanager::$_data[0]->twitter : '', array('class'=>'jslm_input_style', 'placeholder'=>__('Enter Twitter Address','learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                                                <div class="jslm_input_field"><?php echo wp_kses(JSLEARNMANAGERformfield::text("linkedin", isset(jslearnmanager::$_data[0]->linkedin) ? jslearnmanager::$_data[0]->linkedin : '', array('class'=>'jslm_input_style', 'placeholder'=>__('Enter Linkedin Address','learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                                            </div>
                                    <?php
                                        break;
                                        default:
                                            echo JSLEARNMANAGERincluder::getObjectClass('customfields')->formCustomFields($field,3);
                                        break;
                                    }
                                }  ?>
                            </div>
                            <div id="jslm_save" class="jslm_bottom_button">
                                <?php $role = JSLEARNMANAGERincluder::getJSModel('userrole')->getRoleByUid(jslearnmanager::$_data[0]->id); ?>
                                <input type="hidden" name="task" value="saveprofile" />
                                <input type="hidden" name="id" value="<?php echo esc_attr(isset(jslearnmanager::$_data[0]->id) ?jslearnmanager::$_data[0]->id : ''); ?>" />
                                <input type="hidden" name="lmsid" value="<?php echo esc_attr(isset(jslearnmanager::$_data[0]->lmsid) ?jslearnmanager::$_data[0]->lmsid : ''); ?>" />
                                <input type="hidden" name="created" value="<?php echo esc_attr(isset(jslearnmanager::$_data[0]->created_at) ? jslearnmanager::$_data[0]->created_at : date_i18n('Y-m-d H:i:s')); ?>" />
                                <input type="hidden" name="uid" value="<?php echo esc_attr(isset(jslearnmanager::$_data[0]->uid) ?jslearnmanager::$_data[0]->uid : get_current_user_id()); ?>" />
                                <input type="hidden" name="role" value="<?php echo esc_attr(isset($role->role) ? $role->role : ''); ?>" />
                                <input type="hidden" name="validimage" id="validimage" value="0" />
                                <input class="jslm_button" id="jslm_save" type="submit" value="<?php _e('Update Profile','learn-manager'); ?>">
                                <a class="jslm_button jslm_cancel" title="link" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'dashboard'))); ?>"><?php echo __("Cancel","learn-manager"); ?></a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
}elseif(isset(jslearnmanager::$_error_flag_message)){
    echo jslearnmanager::$_error_flag_message;
}
?>
</div>
