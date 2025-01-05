<?php 
if (!defined('ABSPATH')) die('Restricted Access');
  $msgkey = JSLEARNMANAGERincluder::getJSModel('user')->getMessagekey();
  JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  wp_enqueue_script('jquery-ui-selectable');
  wp_enqueue_script('jquery-ui-datepicker');
  wp_enqueue_script('jquery-ui-core');
  if (jslearnmanager::$_error_flag == null) {
  $genderarray = array(
    (object) array('id' => "Male", 'text' => __('Male', 'learn-manager')),
    (object) array('id' => "Female", 'text' => __('Female', 'learn-manager')),
  );

  $approvereject = array(
    (object) array('id' => "0", 'text' => __('Pending', 'learn-manager')),
    (object) array('id' => "1", 'text' => __('Approve', 'learn-manager')),
    (object) array('id' => "-1", 'text' => __('Reject', 'learn-manager')),
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
  function removeLogo(id) {
      var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
      jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'user', task: 'deleteuserimageAjax', userid: id}, function (data) {
          if (data) {
              jQuery("img#userimage").css("display", "none");
              jQuery(".remove-file").css("display", "none");
              jQuery("form#jslearnmanager-form").append('<input type="hidden" name="jslms_user_image_del" value="1"/>');
          } else {
              jQuery("div.logo-container").append('<span style="color:Red;">Error Deleting Logo');
          }
      });
  }
</script>
<center><div id="loading" style="display: none"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/loading.gif"></div></center>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php  JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
      <span class="jslm_js-admin-title">
          <span class="heading">
              <?php $role = JSLEARNMANAGERincluder::getJSModel('userrole')->getRoleByUid(jslearnmanager::$_data[0]->id); ?>
              <a href="<?php echo admin_url('admin.php?page=jslm_user'); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/back-icon.png" /></a>
              <span class="jslm_heading_text"><?php echo __('Edit', 'learn-manager').' '.__($role->role); ?></span> 
          </span>
      </span> 
      <form  method="post" name="adminForm" id="jslearnmanager-form" class="jslm_form-validate js-form-horizontal" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=jslm_user&action=jslmstask&task=saveprofile"); ?>">
        <div class="jslm_content_data">
        <?php 
        foreach (jslearnmanager::$_data[2] AS $field) {   
           switch ($field->field) {
              case 'user_image': ?>
                <div class="jslm_js-field-wrapper js-row no-margin">
                  <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __("User Logo","learn-manager"); ?></div>  
                  <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                    <input id="previewimage" type="file" name="profilephoto" accept="image/*">
                    <?php if (isset(jslearnmanager::$_data[0]->image) && jslearnmanager::$_data[0]->image != "") { ?>
                        <img id="userimage" src="<?php echo esc_url(jslearnmanager::$_data[0]->image); ?>" style="display:inline;width:60px;height:auto;" />
                        <span class="remove-file" onclick="return removeLogo(<?php echo jslearnmanager::$_data[0]->id; ?>);"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png"></span>
                    <?php } ?>    
                  </div>
                </div>
              <?php 
              break; 
              case 'firstname': ?>  
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
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding">
                       <?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->firstname) ? jslearnmanager::$_data[0]->firstname : '', array('placeholder' => 'First Name','data-validation' => $req, 'class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS) ?>
                    </div>
                 </div>
              <?php 
              break; 
              case 'lastname': ?>   
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
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding">
                       <?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->lastname) ? jslearnmanager::$_data[0]->lastname : '', array('placeholder' => 'Last Name','data-validation' => $req, 'class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    </div>
                 </div>
              <?php 
              break;
              case 'gender':  ?>   
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
                       <?php  echo wp_kses(JSLEARNMANAGERformfield::select($field->field, $genderarray,isset(jslearnmanager::$_data[0]->gender)? jslearnmanager::$_data[0]->gender :'',__($field->fieldtitle, 'learn-manager'), array('class' => 'jslm_inputbox jslm_one','data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    </div>
                 </div>
              <?php 
              break;
              case 'country':?>   
                 <div id="jslm_accesstype" class="jslm_js-field-wrapper js-row no-margin">
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
                       <?php  echo wp_kses(JSLEARNMANAGERformfield::select('country_id', JSLEARNMANAGERincluder::getJSModel('country')->getCountriesForCombo(),isset(jslearnmanager::$_data[0]->country_id)? jslearnmanager::$_data[0]->country_id :'',__($field->fieldtitle, 'learn-manager'), array('class' => 'jslm_inputbox jslm_one','data-validation' => $req)), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    </div>
                 </div>
              <?php 
              break; 
              case 'bio': ?>   
                <div class="jslm_js-field-wrapper js-row no-margin">
                  <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                    <?php if ($field->required == 1) {
                    $req = 'required';
                    echo '<font color="red">&nbsp*</font>';
                    }  
                    ?>   
                  </div>
                  <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                    <?php echo wp_editor(isset(jslearnmanager::$_data[0]->bio) ? jslearnmanager::$_data[0]->bio: '', $field->field, array('media_buttons' => false, 'data-validation' => $req, 'class' => 'jslm_inputbox jslm_one')); ?>
                  </div>
                </div>
              <?php
              break;
              case 'weblink': ?>   
              <div class="jslm_js-field-wrapper js-row no-margin">
                 <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?>
                    <?php if ($field->required == 1) {
                       $req = 'required';
                       echo '<font color="red">&nbsp*</font>';
                    }  
                    ?>
                 </div>
                 <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                    <?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->weblink) ? jslearnmanager::$_data[0]->weblink : '', array('data-validation'=>$req, 'placeholder'=>'Weblink', 'class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                 </div>
              </div>
              <?php 
              break;
              case 'sociallinks': ?>
                <div class="jslm_js-field-wrapper js-row no-margin">
                  <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Facebook','learn-manager'); ?></div>
                  <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><input type="text" name="facebook_url" class="jslm_inputbox jslm_one" value="<?php echo isset(jslearnmanager::$_data[0]->facebook_url) ? esc_url(jslearnmanager::$_data[0]->facebook_url) : ' '; ?>" placeholder="<?php echo __("Enter Facebook Address","learn-manager"); ?>"></div>
                </div>  
                <div class="jslm_js-field-wrapper js-row no-margin">
                  <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Twitter','learn-manager'); ?></div>
                  <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><input type="text" name="twitter" class="jslm_inputbox jslm_one" value="<?php echo isset(jslearnmanager::$_data[0]->twitter) ? jslearnmanager::$_data[0]->twitter : ''; ?>" placeholder="<?php echo __("Enter Twitter Address","learn-manager"); ?>"></div>
                </div>
                <div class="jslm_js-field-wrapper js-row no-margin">
                  <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Linkedin','learn-manager'); ?></div>  
                  <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><input type="text" name="linkedin" class="jslm_inputbox jslm_one" value="<?php echo isset(jslearnmanager::$_data[0]->linkedin) ? jslearnmanager::$_data[0]->linkedin : ''; ?>" placeholder="<?php echo __("Enter Linkedin Address","learn-manager"); ?>"></div>
                </div>
              <?php 
              break;
              case 'approvalstatus': ?>
                <div class="jslm_js-field-wrapper js-row no-margin">
                  <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo esc_html(__($field->fieldtitle,'learn-manager'));?></div>
                  <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                    <?php  echo wp_kses(JSLEARNMANAGERformfield::select($field->field, $approvereject,isset(jslearnmanager::$_data[0]->approvalstatus)? jslearnmanager::$_data[0]->approvalstatus :0,__($field->fieldtitle, 'learn-manager'), array('class' => 'jslm_inputbox jslm_one ','data-validation' => 'required')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                  </div>
                </div>  
              <?php 
              break;
              default:
                $u_field = JSLEARNMANAGERincluder::getObjectClass('customfields')->formCustomFields($field,3);
                if($u_field){
                  echo esc_html($u_field);
                }
              break;
              } 
            } ?>

          <div class="jslm_js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <?php 
            if($role->role == "Student"){
              $url = admin_url("admin.php?page=jslm_student");
            }else{
              $url = admin_url("admin.php?page=jslm_instructor");
            }
            ?>
            <a id="form-cancel-button" href="<?php echo esc_url($url); ?>"><?php echo __("Cancel","learn-manager"); ?></a>
            <input type="submit" class="button" type="submit" value="<?php echo __("Save User","learn-manager"); ?>">
          </div>
          <?php echo wp_kses(JSLEARNMANAGERformfield::hidden("task", "saveprofile"), JSLEARNMANAGER_ALLOWED_TAGS); ?>
          <?php echo wp_kses(JSLEARNMANAGERformfield::hidden("id", isset(jslearnmanager::$_data[0]->id) ?jslearnmanager::$_data[0]->id : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
          <?php echo wp_kses(JSLEARNMANAGERformfield::hidden("lmsid", isset(jslearnmanager::$_data[0]->lmsid) ?jslearnmanager::$_data[0]->lmsid : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
          <?php echo wp_kses(JSLEARNMANAGERformfield::hidden("created", isset(jslearnmanager::$_data[0]->created_at) ? jslearnmanager::$_data[0]->created_at : date_i18n('Y-m-d H:i:s')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
          <?php echo wp_kses(JSLEARNMANAGERformfield::hidden("uid", isset(jslearnmanager::$_data[0]->uid) ? jslearnmanager::$_data[0]->uid : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
          <?php echo wp_kses(JSLEARNMANAGERformfield::hidden("role", isset($role->role) ? $role->role : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </div>
      </form>
    </div>
</div>  

<?php } ?>
