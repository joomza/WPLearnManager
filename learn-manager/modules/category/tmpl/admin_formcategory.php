<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.validate();
    });
    // For Preview Uploaded course image
    function preview_image(fdata){
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
        }
        reader.readAsDataURL(fdata.files[0]);
    }
    jQuery(document).ready(function(){
        jQuery("#uploadFile").change(function () {
            if(fileExtValidate(this)) {
                if(fileSizeValidate(this)) {
                   // preview_image(this);
                }
            }
        });


    });
    var validExt = "<?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type'); ?>";
    function fileExtValidate(fdata) {
        var filePath = fdata.value;
        var getFileExt = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
        var pos = validExt.indexOf(getFileExt);
        if(pos < 0) {
            alert("This file is not allowed, please upload valid file.");
            jQuery("#uploadFile").val("");
            return false;
        } else {
            return true;
        }
    }

    function fileSizeValidate(fdata){
        var filesize = "<?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_logo_size'); ?>";
        var uploadedfilesize = fdata.files[0].size;
        if((uploadedfilesize / 1000) > filesize){
            alert("File Size Must be less than "+filesize+" KB");
            jQuery("#uploadFile").val("");
            return false;
        }else{
            return true;
        }
    }

    function removeLogo(id) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
        jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'category', task: 'deletecategoryimageAjax', categoryid: id}, function (data) {
            if (data) {
                jQuery("img#imagePreview").css("display", "none");
                jQuery(".remove-file").css("display", "none");
                jQuery("form#jslearnmanager-form").append('<input type="hidden" name="jslms_category_image_del" value="1"/>');
            } else {
                jQuery("div.logo-container").append('<span style="color:Red;">Error Deleting Logo');
            }
        });
    }

</script>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php  JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
    <?php
    $msgkey = JSLEARNMANAGERincluder::getJSModel('category')->getMessagekey();
    JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
    ?>
    <div id="jslearnmanageradmin-wrapper-top">
            <div id="jslearnmanageradmin-wrapper-top-left">
                <div id="jslearnmanageradmin-breadcrunbs">
                    <ul>
                        <li>
                            <a href="admin.php?page=jslearnmanager">
                                <?php echo __('Dashboard', 'learn-manager'); ?>
                            </a>
                        </li>
                        <li><?php echo __('Add New Category','js-support-ticket'); ?></li>
                    </ul>
                </div>
            </div>
            <div id="jslearnmanageradmin-wrapper-top-right">
                <div id="jslearnmanageradmin-help-txt">
                   <a href="<?php echo esc_url(admin_url("admin.php?page=jslearnmanager&jslmslay=help")); ?>" title="<?php echo __('Help','learn-manager'); ?>">
                        <img alt="<?php echo __('Help','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help.png" />
                    </a>
                </div>
                <div id="jslearnmanageradmin-vers-txt">
                    <?php echo __('Version :'); ?>
                    <span class="jslearnmanageradmin-ver">
                        <?php echo esc_html(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="jslm_dashboard">
            <span class="jslm_heading-dashboard"><?php echo __('Add New Category', 'learn-manager'); ?></span>
            <?php
                // $heading = isset(jslearnmanager::$_data[0]) ? __('Edit', 'learn-manager') : __('Add New', 'learn-manager');
                // echo esc_html($heading) . '&nbsp' . __('Category', 'learn-manager');
            ?>
        </div>            
    <div id="jslms-data-wrp">
     <form id="jslearnmanager-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_category&action=jslmstask&task=savecategory"); ?>" enctype="multipart/form-data">
        <div class="jslm_js-field-wrapper js-row no-margin">
            <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Title', 'learn-manager'); ?><font class="required-notifier">*</font></div>
            <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text('category_name', isset(jslearnmanager::$_data[0]->category_name) ? __(jslearnmanager::$_data[0]->category_name) : '', array('class' => 'inputbox one', 'data-validation' => 'required')), JSLEARNMANAGER_ALLOWED_TAGS) ?></div>
        </div>
        <div class="jslm_js-field-wrapper js-row no-margin">
            <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Category Image', 'learn-manager'); ?></div>
            <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                <div class="jslm_upload_img_wrapper">
                    <?php
                    if (isset(jslearnmanager::$_data[0]->category_img) && jslearnmanager::$_data[0]->category_img != "") {
                        $path = jslearnmanager::$_data[0]->category_img;
                    ?>
                        <img id="imagePreview" src="<?php echo esc_url($path); ?>" style="width: 125px;">
                        <span class="remove-file" onclick="return removeLogo(<?php echo jslearnmanager::$_data[0]->id; ?>);"><img  src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png"></span>
                    <?php
                        }else{?>
                        <!-- <img id="imagePreview" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/default_cat.png" style="width: 125px;"> -->
                    <?php } ?>
               </div>
                <div class="jslm_file_field">
                  <input type="file" id="uploadFile" name="category_img" accept="image/*">
                </div>
            </div>
        </div>

        <div class="jslm_js-field-wrapper js-row no-margin">
            <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Published', 'learn-manager'); ?></div>
            <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::radiobutton('isactive', array('1' => __('Yes', 'learn-manager'), '0' => __('No', 'learn-manager')), isset(jslearnmanager::$_data[0]->isactive) ? jslearnmanager::$_data[0]->isactive : 1, array('class' => 'radiobutton')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
        </div>
        <div class="jslm_js-field-wrapper js-row no-margin">
            <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Default', 'learn-manager'); ?></div>
            <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::radiobutton('isdefault', array('1' => __('Yes', 'learn-manager'), '0' => __('No', 'learn-manager')), isset(jslearnmanager::$_data[0]->isdefault) ? jslearnmanager::$_data[0]->isdefault : 0, array('class' => 'radiobutton')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
        </div>
        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', isset(jslearnmanager::$_data[0]->id) ? jslearnmanager::$_data[0]->id : ""), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('ordering', isset(jslearnmanager::$_data[0]->ordering) ? jslearnmanager::$_data[0]->ordering : '' ), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslearnmanager_isdefault', isset(jslearnmanager::$_data[0]->isdefault) ? jslearnmanager::$_data[0]->isdefault : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'category_savecategory'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <div class="jslm_js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jslm_category'); ?>" ><?php echo __('Cancel', 'learn-manager'); ?></a>
            <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save','learn-manager') .' '. __('Category', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </div>
    </form>
</div>
</div>
</div>
