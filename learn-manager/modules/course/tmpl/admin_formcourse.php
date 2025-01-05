<?php if (!defined('ABSPATH')) die('Restricted Access');
  $msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
  JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
  wp_enqueue_script('jquery-ui-selectable');
  wp_enqueue_script('jquery-ui-datepicker');
  wp_enqueue_script('jquery-ui-core');
  if (jslearnmanager::$_error_flag_message == null) {
    $status = array(
      (object) array('id' => 1, 'text' => esc_html__('Publish', 'learn-manager')),
      (object) array('id' => 0, 'text' => esc_html__('Unpublish', 'learn-manager')),
    );
    $approvestatus = array(
      (object) array('id' => 1, 'text' => esc_html__('Approve', 'learn-manager')),
      (object) array('id' => 0, 'text' => esc_html__('Pending', 'learn-manager')),
      (object) array('id' => 2, 'text' => esc_html__('Reject', 'learn-manager')),
    );
    $config = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('default');
    if ($config['date_format'] == 'm/d/Y' || $config['date_format'] == 'd/m/y' || $config['date_format'] == 'm/d/y' || $config['date_format'] == 'd/m/Y') {
      $dash = '/';
    } else {
      $dash = '-';
    }
  $dateformat = $config['date_format'];
  $firstdash = strpos($dateformat, $dash, 0);
  $firstvalue = substr($dateformat, 0, $firstdash);
  $firstdash = $firstdash + 1;
  $seconddash = strpos($dateformat, $dash, $firstdash);
  $secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
  $seconddash = $seconddash + 1;
  $thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
  $js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
  $js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
  $js_scriptdateformat = str_replace('Y', 'yy', $js_scriptdateformat);
  $discounttype = array(
    (object) array('id' => 1, 'text' => esc_html__('Percentage', 'learn-manager')),
    (object) array('id' => 2, 'text' => esc_html__('Fixed', 'learn-manager')),
  );
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
      jQuery('.custom_date').datepicker({dateFormat: '<?php echo esc_js($js_scriptdateformat); ?>'});
      jQuery('.jslm_select_full_width').selectable();
      jQuery("#access_type").change(function(){
        var access_type = jQuery( "#access_type option:selected" ).val();
        jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "accesstype",task: "getaccesstypeByIdAjax",id: access_type} , function (access_type) {
          if (access_type) {
            if(access_type == "Paid"){
              jQuery("#jslm_price_id").show();
              <?php if(in_array("paymentplan",jslearnmanager::$_active_addons)){ ?>
                jQuery("#paymentplanid").show();
              <?php } ?>
            }else{
              jQuery("#jslm_price_id").hide();
              <?php if(in_array('paymentplan', jslearnmanager::$_active_addons)){ ?>
                jQuery("#paymentplanid").hide();
              <?php } ?>
            } 
          }
        });
      });

      jQuery("#uploadFile").change(function () {
        if(fileExtValidate(this)) {
          if(fileSizeValidate(this)) {
            return true;
          }
        }
      });

      jQuery(window).load(function(){
        var access_type = jQuery( "#access_type option:selected" ).val();
        jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "accesstype",task: "getaccesstypeByIdAjax",id: access_type} , function (access_type) {
            if(access_type == "Paid"){
              jQuery("#jslm_price_id").show();
              <?php if(in_array('paymentplan', jslearnmanager::$_active_addons)){ ?>
                jQuery("#paymentplanid").show();
              <?php } ?>
              isDiscountEnable();
            }else{
                jQuery("#jslm_price_id").hide();
            }
        });
      });
      jQuery.validate();
      jQuery( "#jslearnmanager-form" ).submit(function( e ) {
        if(jQuery("#instructor").val() == ""){
          jQuery( "#userpopup" ).append().after("<div id='pricevalidate' class='validation form-error' style='color:red;margin-bottom: 20px;'>Select an valid instructor</div>");
        }
        var access_type = jQuery( "#access_type option:selected" ).val();
        if(access_type == 2){
          var price = document.getElementById("price").value;
          var discount_price = document.getElementById("discount_price").value;
          jQuery("#pricevalidate").remove();
          jQuery("#discountvaldiate").remove();
          jQuery("#currencyvalidate").remove();
          if(price < 1){
             e.preventDefault();
             jQuery( "#price" ).append().after("<div id='pricevalidate' class='validation form-error' style='color:red;margin-bottom: 20px;'>Price Must be greater or equal than 1</div>");
          }else{
             discountTypeAndPriceCheck(e,price,discount_price);
          }
          if(document.getElementById("currency").value == ""){
             e.preventDefault();
             jQuery( "#currency" ).append().after("<div id='currencyvalidate' class='validation form-error' style='color:red;margin-bottom: 20px;'>Please Select a valid currency</div>");
          }
        }else{
          document.getElementById("price").value = 0;
          document.getElementById("discount_price").value = 0;
          document.getElementById("currency").value = 0;
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

    function updateuserlist(pagenum) {
      var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
      jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'instructor', task: 'getinstructorlistajaxforcourse', userlimit: pagenum}, function (data) {
        if (data) {
          jQuery("div#popup-record-data").html("");
          jQuery("span#popup_title").html(jQuery("input#user-popup-title-text").val());
          jQuery("div#popup-record-data").html(data);
          setUserLink();
        }
      });
    }

    function setUserLink() {
      jQuery("a.js-userpopup-link").each(function () {
        var anchor = jQuery(this);
        jQuery(anchor).click(function (e) {
          var name = jQuery(this).attr('data-name');
          jQuery("label#uname").html(name);
          var id = jQuery(this).attr('data-id');
          jQuery("input#instructor").val(id);
          jQuery("div#popup_main").slideUp('slow', function () {
            jQuery("div#full_background").hide();
          });
        });
      });
    }
    jQuery(document).ready(function () {
      jQuery("#discount_checkbox").change(function(){
        isDiscountEnable();
      });
      jQuery("a#userpopup").click(function (e) {
         e.preventDefault();
         jQuery("div#popup-new-company").css("display", "none");
         jQuery("img.icon").css("display", "none");
         jQuery("div#popup-record-data").css("display", "block");
         jQuery("div#full_background").show();
         var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
         jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'instructor', task: 'getinstructorlistajaxforcourse'}, function (data) {
            if (data) {
               jQuery("div#popup-record-data").html("");
               jQuery("span#popup_title").html(jQuery("input#user-popup-title-text").val());
               jQuery("div#popup-record-data").html(data);
               setUserLink();
            }
         });
         jQuery("div#popup_main").slideDown('slow');
      });
        //jQuery("form#userpopupsearch").submit(function (e) {
        jQuery(document).delegate('form#userpopupsearch', 'submit', function (e) {
            e.preventDefault();
            e.preventDefault();
            var username = jQuery("input#uname").val();
            var name = jQuery("input#name").val();
            var emailaddress = jQuery("input#email").val();
            var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
            jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'instructor', task: 'getinstructorlistajaxforcourse', name: name, uname: username, email: emailaddress}, function (data) {
              if (data) {
                jQuery("span#popup_title").html(jQuery("input#user-popup-title-text").val());
                jQuery("div#popup-record-data").html(data);
                setUserLink();
              }
            });//jquery closed
        });
        jQuery("span.close, div#full_background,img#popup_cross").click(function (e) {
          jQuery("div#popup_main").slideUp('slow', function () {
            jQuery("div#full_background").hide();
          });
        });
    });

    function isDiscountEnable(){
      var isdiscount = jQuery('#isdiscount1').prop('checked');
      if(isdiscount === true){
        jQuery("#isdiscount1").val("1");
        jQuery("#discount_type").show();
        jQuery("#discount_amount").show();
      }else if(isdiscount != true){
        jQuery("#isdiscount1").val("0");
        jQuery("#discount_type").hide();
        jQuery("#discount_amount").hide();
      }
    }

    function discountTypeAndPriceCheck(e,price,disprice){
      var isdiscount = jQuery('#isdiscount1').prop('checked');
      if(isdiscount == true){
        var discounttype = jQuery("#discount_type option:selected").val();
        if(discounttype == ""){
           e.preventDefault();
           jQuery( "#discount_type" ).append("<div id='pricevalidate' class='validation form-error'><?php echo __("Please Select Valid Discount Type","learn-manager"); ?></div>");
        }
        if(parseInt(price) < parseInt(disprice)){
           e.preventDefault();
           jQuery( "#discount_price" ).append().after("<div id='discountvaldiate' class='validation form-error'><?php echo __("Discount price must be less or equal than Course price","learn-manager"); ?></div>");
        }
        if(discounttype == 1 && (parseInt(disprice) > 100 || parseInt(disprice) <1)){
          e.preventDefault();
          jQuery( "#discount_price" ).append().after("<div id='discountvaldiate' class='validation form-error'><?php echo __("Discount percentage must be less or equal to 100 or greater than 0","learn-manager"); ?></div>");
        }else if(parseInt(disprice) <= 0 && discounttype == 2){
          e.preventDefault();
          jQuery( "#discount_price" ).append().after("<div id='discountvaldiate' class='validation form-error'><?php echo __("Discount price must be greater or equal than 0","learn-manager"); ?></div>");
        }
      }
    }

  function removeLogo(id) {
    var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
    jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'course', task: 'deletecourseimageAjax', courseid: id}, function (data) {
        if (data) {
            jQuery("img#courseimage").css("display", "none");
            jQuery(".remove-file").css("display", "none");
            jQuery("form#jslearnmanager-form").append('<input type="hidden" name="jslms_course_image_del" value="1"/>');
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
      <div id="full_background" style="display:none;"></div>
      <div id="popup_main" style="display:none;">
        <img class="icon" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/popup-coin-icon.png"/>
       
        <span class="popup-top"><span id="popup_title"></span><img id="popup_cross" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/popup-close.png">
        </span>
        <div style="display:inline-block;width:100%;float:left;">
          <form id="userpopupsearch">
            <div class="search-center">
              <div class="js-col-md-12">
                <div class="js-col-xs-12 js-col-md-3 search-value">
                  <input type="text" name="uname" id="uname" placeholder="<?php echo __('Username', 'learn-manager');?>" />
                </div>
                <div class="js-col-xs-12 js-col-md-3 search-value">
                  <input type="text" name="name" id="name" placeholder="<?php echo __('Name', 'learn-manager');?>" />
                </div>
                <div class="js-col-xs-12 js-col-md-3 search-value">
                  <input type="text" name="email" id="email" placeholder="<?php echo __('Email Address', 'learn-manager');?>"/>
                </div>
                <div class="js-col-xs-12 js-col-md-3 search-value-button">
                  <div class="js-button ">
                    <input type="submit" class="submit-button" value="<?php echo __('Search', 'learn-manager');?>" />
                  </div>
                  <div class="js-button">
                    <input type="submit" onclick="document.getElementById('name').value = '';document.getElementById('uname').value = ''; document.getElementById('email').value = '';" value="<?php echo __('Reset', 'learn-manager');?>" />
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div id="popup-record-data" style="display:inline-block;width:100%;"></div>
      </div>
  
      <div id="jslearnmanageradmin-wrapper-top">
            <div id="jslearnmanageradmin-wrapper-top-left">
                <div id="jslearnmanageradmin-breadcrunbs">
                    <ul>
                        <li>
                            <a href="admin.php?page=jslearnmanager">
                                <?php echo __('Dashboard', 'learn-manager'); ?>
                            </a>
                        </li>
                        <li><?php echo __('Add new course','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Add new course', 'learn-manager'); ?></span>
            <a target="blank" href="https://www.youtube.com/watch?v=cPH5zKlhNpo&ab_channel=WPLearnManager" class="jslmsadmin-add-link black-bg" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                <img alt="arrow" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/play-btn.png">
                <?php echo __('Watch Video', 'learn-manager'); ?>
            </a>
        </div>            
   <div id="jslms-data-wrp">
      <form  method="post" name="adminForm" id="jslearnmanager-form" class="jslm_form-validate js-form-horizontal" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=jslm_course&action=jslmstask&task=savecourse"); ?>">
        <div class="jslm_content_data">
        <?php
        foreach (jslearnmanager::$_data[3] AS $field) {
          switch ($field->field) {
            case 'logo': ?>
              <div class="jslm_js-field-wrapper js-row no-margin">
                <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __("Course Logo","learn-manager"); ?></div>
                <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                  <input type="file" id="uploadFile" name="logo" class="jslm_file_upload_input" accept="image/*">
                  <?php if (isset(jslearnmanager::$_data[0]->logofilename) && jslearnmanager::$_data[0]->logofilename != "") {
                      $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                      $wpdir = wp_upload_dir();
                      $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/course/course_' . jslearnmanager::$_data[0]->id . '/' . jslearnmanager::$_data[0]->logofilename; ?>
                      <img id="courseimage" src="<?php echo esc_url($path); ?>" style="display:inline;width:60px;height:auto;" />
                      <span class="remove-file" onclick="return removeLogo(<?php echo esc_js(jslearnmanager::$_data[0]->id); ?>);"><img  src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png"></span>
                  <?php } ?>
                </div>
              </div>
            <?php
            break;
            case 'title': ?>
              <div class="jslm_js-field-wrapper js-row no-margin">
                <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                   <?php
                      $req = '';
                      if ($field->required == 1) {
                         $req = 'required';
                         echo '<font color="red">&nbsp*</font>';
                      }
                   ?>
                </div>
                <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                   <?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->title) ? jslearnmanager::$_data[0]->title : '', array('placeholder' => 'Enter Course title', 'class' => 'jslm_inputbox jslm_one','data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS) ?>
                </div>
              </div>
            <?php
              break;
              case 'category_id':  ?>
                 <div class="jslm_js-field-wrapper js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                       <?php
                          $req = '';
                          if ($field->required == 1) {
                             $req = 'required';
                             echo '<font color="red">&nbsp*</font>';
                          }
                       ?>
                    </div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                       <?php  echo wp_kses(JSLEARNMANAGERformfield::select($field->field, JSLEARNMANAGERincluder::getJSModel('category')->getCategoryForCombobox(),isset(jslearnmanager::$_data[0]->category_id)? jslearnmanager::$_data[0]->category_id :'',__($field->fieldtitle, 'learn-manager'), array('class' => 'jslm_inputbox jslm_one ','data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    </div>
                 </div>
              <?php
              break;
              case 'access_type':
                do_action("jslm_paidcourse_accesstype_combobox_admin_add_course",$field);
              break;
              case 'price':
                do_action("jslm_paidcourse_price_input_for_admin_form_course",$field,$discounttype);
              break;
              case 'description': ?>
                 <div class="jslm_js-field-wrapper js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                       <?php $req = '';
                        if ($field->required == 1) {
                          $req = 'required';
                          echo '<font color="red">&nbsp*</font>';
                        }
                       ?>
                    </div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                       <?php echo wp_editor(isset(jslearnmanager::$_data[0]->description) ? jslearnmanager::$_data[0]->description: '', 'description', array('media_buttons' => false, 'data-validation' => $req)); ?>
                    </div>
                 </div>
              <?php
              break;
              case 'learningoutcomes': ?>
                 <div class="jslm_js-field-wrapper js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                       <?php $req = ''; if ($field->required == 1) {
                          $req = 'required';
                          echo '<font color="red">&nbsp*</font>';
                       }
                       ?>
                    </div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                       <?php echo wp_editor(isset(jslearnmanager::$_data[0]->learningoutcomes) ? jslearnmanager::$_data[0]->learningoutcomes: '', 'learningoutcomes', array('media_buttons' => false, 'data-validation' => $req)); ?>
                    </div>
                 </div>
              <?php
              break;
              case 'meta_description': ?>
                 <div class="jslm_js-field-wrapper js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                      <?php $req = ''; if ($field->required == 1) {
                          $req = 'required';
                          echo '<font color="red">&nbsp*</font>';
                       }
                       ?>
                    </div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                       <?php echo wp_kses(JSLEARNMANAGERformfield::textarea($field->field, isset(jslearnmanager::$_data[0]->meta_description) ? jslearnmanager::$_data[0]->meta_description : '', array('data-validation' => $req, 'rows' => 8 , 'class' => 'jslm_text_area')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    </div>
                 </div>
              <?php
              break;
              case 'keywords': ?>
              <div class="jslm_js-field-wrapper js-row no-margin">
                 <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                    <?php $req = ''; if ($field->required == 1) {
                       $req = 'required';
                       echo '<font color="red">&nbsp*</font>';
                    }
                    ?>
                 </div>
                 <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                    <?php echo wp_kses(JSLEARNMANAGERformfield::text('keywords', isset(jslearnmanager::$_data[0]->keywords) ? jslearnmanager::$_data[0]->keywords : '', array('data-validation'=>$req , 'class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                 </div>
              </div>
              <?php
              break;
              case 'instructor_id':
                echo wp_kses(JSLEARNMANAGERformfield::hidden('instructor', isset(jslearnmanager::$_data[0]->instructor_id) ? jslearnmanager::$_data[0]->instructor_id : '', array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <div class="jslm_js-field-wrapper js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle, 'learn-manager')); ?>
                      <?php $req = ''; if($field->required == 1){
                        $req = 'required';
                        echo '<font color="red">&nbsp*</font>';
                      } ?>
                    </div>
                  <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                    <label id="uname"><?php echo isset(jslearnmanager::$_data[0]->instructorname) ? esc_html(ucwords(jslearnmanager::$_data[0]->instructorname)) : ""; ?></label>
                    <a href="#" id="userpopup"><?php echo __('Select','learn-manager') .'&nbsp;'. __('Instructor', 'learn-manager'); ?></a><div id="username-div"></div>
                  </div>
                </div>
                <?php
              break;
              default:
                $u_field = JSLEARNMANAGERincluder::getObjectClass('customfields')->formCustomFields($field,1);
                if($u_field){
                  echo __($u_field);
                }
              break;
              case 'course_duration': ?>
                <div class="jslm_js-field-wrapper js-row no-margin">
                   <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                      <?php $req = ''; if ($field->required == 1) {
                         $req = 'required';
                         echo '<font color="red">&nbsp*</font>';
                      }
                      ?>
                   </div>
                   <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                      <?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->course_duration) ? jslearnmanager::$_data[0]->course_duration : '' , array('data-validation'=>$req, 'class'=>'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS) ?>
                   </div>
                </div>
                 <?php
                 break;
                 case 'course_level':
                 ?>
                    <div class="jslm_js-field-wrapper js-row no-margin">
                       <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                          <?php
                             $req = '';
                             if ($field->required == 1) {
                                $req = 'required';
                                echo '<font color="red">&nbsp*</font>';
                             }
                          ?>
                       </div>
                       <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                          <?php  echo wp_kses(JSLEARNMANAGERformfield::select($field->field, JSLEARNMANAGERincluder::getJSModel('courselevel')->getLevelForCombo(),isset(jslearnmanager::$_data[0]->course_level)? jslearnmanager::$_data[0]->course_level :'',__($field->fieldtitle, 'learn-manager'), array('class' => 'jslm_inputbox jslm_one','data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                       </div>
                    </div>
                 <?php
                 break;
                 case 'language':
                 ?>
                    <div class="jslm_js-field-wrapper js-row no-margin">
                       <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                          <?php
                             $req = '';
                             if ($field->required == 1) {
                                $req = 'required';
                                echo '<font color="red">&nbsp*</font>';
                             }
                          ?>
                       </div>
                       <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                          <?php  echo wp_kses(JSLEARNMANAGERformfield::select($field->field, JSLEARNMANAGERincluder::getJSModel('language')->getlanguageForCombo(),isset(jslearnmanager::$_data[0]->language)? jslearnmanager::$_data[0]->language :'',__($field->fieldtitle, 'learn-manager'), array('class' => 'jslm_inputbox jslm_one','data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                       </div>
                    </div>
              <?php
              break;
              case 'paymentplan_id':
                if(in_array('paidcourse', jslearnmanager::$_active_addons)){
                    $paymentplan_id = isset(jslearnmanager::$_data[0]->paymentplan_id) ? jslearnmanager::$_data[0]->paymentplan_id : 0;
                    do_action("jslm_paymentplan_get_combo_box_admin_course_form",$field,$paymentplan_id);
                }
              break;
              case 'course_status': ?>
                 <div class="jslm_js-field-wrapper js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                       <?php $req = '';
                          if ($field->required == 1) {
                             $req = 'required';
                             echo '<font color="red">&nbsp*</font>';
                          }
                       ?>
                    </div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                       <?php  echo wp_kses(JSLEARNMANAGERformfield::select($field->field, $status,isset(jslearnmanager::$_data[0]->course_status)? jslearnmanager::$_data[0]->course_status :'',__('Course Publish Status', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one','data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    </div>
                 </div>
              <?php
              break;
              }
            } ?>
            <?php if(isset(jslearnmanager::$_data[0]->id)){ ?>
                <div class="jslm_js-field-wrapper js-row no-margin">
                  <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __("Course Approval Status",'learn-manager');?>
                    <?php $req = '';
                      if ($field->required == 1) {
                        $req = 'required';
                        echo '<font color="red">&nbsp*</font>';
                      }
                    ?>
                  </div>
                  <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                    <?php  echo wp_kses(JSLEARNMANAGERformfield::select('isapprove', $approvestatus,isset(jslearnmanager::$_data[0]->isapprove) ? jslearnmanager::$_data[0]->isapprove :'',__('Course Approval Status', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one','data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                  </div>
                </div>
            <?php } ?>
            <div class="jslm_js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
              <a id="form-cancel-button" href="<?php echo admin_url("admin.php?page=jslm_course"); ?>">Cancel</a>
              <?php if(!isset(jslearnmanager::$_data[0]->id)){ ?>
                <input class="button" type="submit" value="<?php echo __("Create Course","learn-manager"); ?>">
              <?php }else{ ?>
                <input class="button" type="submit" value="<?php echo __("Update Course","learn-manager"); ?>  ">
              <?php } ?>
            </div>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', isset(jslearnmanager::$_data[0]->id) ? jslearnmanager::$_data[0]->id : '', array('class' => 'inputbox one')), JSLEARNMANAGER_ALLOWED_TAGS) ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('user-popup-title-text', __('Select','learn-manager') .'&nbsp;'. __('Instructor', 'learn-manager')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </div>
      </form>

    </div>
   </div>
</div>

<?php } ?>
