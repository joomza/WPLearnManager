<div class="jslm_main-up-wrapper">
<?php if (!defined('ABSPATH')) die('Restricted Access');
$isdisabled = JSLEARNMANAGERincluder::getObjectClass('user')->isdisabled();
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
$image_file_types = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
$file_size = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_logo_size');
$status = array(
	(object) array('id' => 1, 'text' => esc_html__('Publish', 'learn-manager')),
	(object) array('id' => 0, 'text' => esc_html__('Unpublish', 'learn-manager')),
);
$discounttype = array(
(object) array('id' => 1, 'text' => esc_html__('Percentage', 'learn-manager')),
	(object) array('id' => 2, 'text' => esc_html__('Fixed', 'learn-manager')),
);
?>
<div class="jslm_content_wrapper">
   <div class="jslm_content_data">
      <div class="jslm_search_content no-border">
         <div class="jslm_top_title">
            <div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Course","learn-manager"); ?></h3></div>
         </div>
      </div>
      <?php
      if(jslearnmanager::$_error_flag_message != null){
         echo jslearnmanager::$_error_flag_message;
      }elseif(jslearnmanager::$_error_flag == null){
         if(!$isdisabled){?>
      <div class="jslm_data_container no-padding-top">
         <div class="jslm_addcourse_wrapper">
            <form  method="post" name="adminForm" id="jslm_courseForm" class="jslm_form-validate js-form-horizontal" enctype="multipart/form-data" action="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'action'=>'jslmstask', 'task'=>'savecourse', 'jslearnmanagerpageid'=>jslearnmanager::getPageid()))); ?>">
               <div class="jslm_form_wrapper">
               <?php
               foreach (jslearnmanager::$_data[3] AS $field) {
                  switch ($field->field) {
                     case 'logo':  ?>
                        <div class="jslm_row">
                           <div class="jslm_title"><?php echo __("Upload Image"); ?></div>
                           <div class="jslm_upload_image">
                              <div class="jslm_file_upload">
                                 <input id="uploadFile" class="jslm_file_upload_input" type="file" name="logo" accept="image/*" value="<?php echo isset(jslearnmanager::$_data[0]->logofilename) ? __(jslearnmanager::$_data[0]->logofilename,'learn-manager') : ''   ?>" />
                                 <label for="uploadFile" class="jslm_file_upload_label"><?php echo esc_html__('Upload Course Image','learn-manager');?></label>
                              </div>
                              <?php if(isset(jslearnmanager::$_data[0]->logofilename) && jslearnmanager::$_data[0]->logofilename != ""){ ?>
                                 <span id="course_<?php echo jslearnmanager::$_data[0]->id; ?>" class="jslm_image_name">(<?php echo jslearnmanager::$_data[0]->logofilename; ?>)<a class="jslm_delete_image" href="#" onclick="deleteUploadedFileOrImage('course_<?php echo jslearnmanager::$_data[0]->id; ?>','jslms_course_image_del')"><?php echo __("Delete","learn-manager"); ?></a></span>
                                 <?php echo JSLEARNMANAGERformfield::hidden('jslms_course_image_del', ''); ?>
                              <?php } ?>
                              <span class="jslm_file_extension"><?php echo esc_html__('Max image size is','learn-manager');?> <?php echo esc_html__($file_size,'learn-manager') ; ?><?php echo esc_html__(' Kb.','learn-manager');?><?php echo esc_html__(' Allowed Type:(','learn-manager');?><?php echo esc_html__($image_file_types,'learn-manager'); ?>)</span>
                           </div>
                        </div>
                     <?php
                     break;
                     case 'title': ?>
                        <div class="jslm_row">
                           <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                              <?php
                                 $req = '';
                                 if ($field->required == 1) {
                                    $req = 'required';
                                    echo '<font color="red">&nbsp*</font>';
                                 }
                              ?>
                           </div>
                           <div class="jslm_input_field">
                              <?php echo JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->title) ? jslearnmanager::$_data[0]->title : '', array('placeholder' => esc_html__('Enter Course title','learn-manager'),'data-validation' => $req, 'autocomplete'=>esc_html__('off','learn-manager'))) ?>
                           </div>
                        </div>
                     <?php
                     break;
                     case 'category_id':  ?>
                        <div class="jslm_row">
                           <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                              <?php
                                 $req = '';
                                 if ($field->required == 1) {
                                    $req = 'required';
                                    echo '<font color="red">&nbsp*</font>';
                                 }
                              ?>
                           </div>
                           <div class="jslm_input_field jslm_select_bg">
                              <?php  echo JSLEARNMANAGERformfield::select($field->field, JSLEARNMANAGERincluder::getJSModel('category')->getCategoryForCombobox(),isset(jslearnmanager::$_data[0]->category_id)? jslearnmanager::$_data[0]->category_id :'',__($field->fieldtitle,'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width ','data-validation' => $req)); ?>
                           </div>
                        </div>
                     <?php
                     break;
                     case 'access_type':
                        do_action("jslm_paidcourse_accesstype_combobox_add_course",$field);
                     break;
                     case 'price':
                        do_action("jslm_paidcourse_price_input_for_add_course",$field,$discounttype);
                     break;
                     case 'description': ?>
                        <div class="jslm_row">
                           <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                              <?php $req = '';
                                 if ($field->required == 1) {
                                    $req = 'required';
                                    echo '<font color="red">&nbsp*</font>';
                                 }
                              ?>
                           </div>
                           <div class="jslm_wp_editor">
                              <?php echo wp_editor(isset(jslearnmanager::$_data[0]->description) ? jslearnmanager::$_data[0]->description: '', 'description', array('media_buttons' => false, 'data-validation' => $req)); ?>
                           </div>
                        </div>
                      <?php
                     break;
                     case 'course_status': ?>
                        <div class="jslm_row">
                           <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                              <?php $req = '';
                                 if ($field->required == 1) {
                                    $req = 'required';
                                    echo '<font color="red">&nbsp*</font>';
                                 }
                              ?>
                           </div>
                           <div class="jslm_input_field">
                              <?php  echo JSLEARNMANAGERformfield::select($field->field, $status,isset(jslearnmanager::$_data[0]->course_status)? jslearnmanager::$_data[0]->course_status :'',__('Course Status', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width','data-validation' => $req)); ?>
                           </div>
                        </div>
                     <?php
                     break;
                     case 'learningoutcomes': ?>
                        <div class="jslm_row">
                           <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                              <?php $req = '';
                                 if ($field->required == 1) {
                                    $req = 'required';
                                    echo '<font color="red">&nbsp*</font>';
                                 }
                              ?>
                           </div>
                           <div class="jslm_wp_editor">
                              <?php echo wp_editor(isset(jslearnmanager::$_data[0]->learningoutcomes) ? jslearnmanager::$_data[0]->learningoutcomes: '', 'learningoutcomes', array('media_buttons' => false, 'data-validation' => $req)); ?>
                           </div>
                        </div>
                     <?php
                     break;
                     case 'meta_description': ?>
                        <div class="jslm_row">
                           <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                              <?php $req = '';
                                 if ($field->required == 1) {
                                    $req = 'required';
                                    echo '<font color="red">&nbsp*</font>';
                                 }
                              ?>
                           </div>
                           <div class="jslm_input_field">
                              <?php echo JSLEARNMANAGERformfield::textarea($field->field, isset(jslearnmanager::$_data[0]->meta_description) ? jslearnmanager::$_data[0]->meta_description : '', array('data-validation' => $req, 'rows' => 8, 'cols' => 5)) ?>
                           </div>
                        </div>
                     <?php
                     break;
                     case 'keywords': ?>
                     <div class="jslm_row">
                        <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                           <?php $req = '';
                              if ($field->required == 1) {
                                 $req = 'required';
                                 echo '<font color="red">&nbsp*</font>';
                              }
                           ?>
                        </div>
                        <div class="jslm_input_field">
                           <?php echo JSLEARNMANAGERformfield::text('keywords', isset(jslearnmanager::$_data[0]->keywords) ? jslearnmanager::$_data[0]->keywords : ''); ?>
                        </div>
                     </div>
                     <?php
                     break;
                     case 'course_duration': ?>
                           <div class="jslm_row">
                              <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                                 <?php $req = '';
                                    if ($field->required == 1) {
                                       $req = 'required';
                                       echo '<font color="red">&nbsp*</font>';
                                    }
                                 ?>
                              </div>
                              <div class="jslm_input_field">
                                 <?php echo JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->course_duration) ? jslearnmanager::$_data[0]->course_duration : '',array('placeholder'=>esc_html__('Enter Course Duration Like 5-weeks, 10-mins etc','learn-manager'),'data-validation'=>$req)); ?>
                              </div>
                           </div>
                        <?php
                        break;
                        case 'course_level':
                        ?>
                           <div class="jslm_row">
                              <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                                 <?php
                                    $req = '';
                                    if ($field->required == 1) {
                                       $req = 'required';
                                       echo '<font color="red">&nbsp*</font>';
                                    }
                                 ?>
                              </div>
                              <div class="jslm_input_field">
                                 <?php  echo JSLEARNMANAGERformfield::select($field->field, JSLEARNMANAGERincluder::getJSModel('courselevel')->getLevelForCombo(),isset(jslearnmanager::$_data[0]->course_level)? jslearnmanager::$_data[0]->course_level :'',__($field->fieldtitle,'learn-manager'), array('class' => 'inputbox jslm_select_full_width','data-validation' => $req)); ?>
                              </div>
                           </div>
                        <?php
                        break;
                        case 'language':
                        ?>
                           <div class="jslm_row">
                              <div class="jslm_title"><?php echo __($field->fieldtitle,'learn-manager');?>
                                 <?php
                                    $req = '';
                                    if ($field->required == 1) {
                                       $req = 'required';
                                       echo '<font color="red">&nbsp*</font>';
                                    }
                                 ?>
                              </div>
                              <div class="jslm_input_field">
                                 <?php  echo JSLEARNMANAGERformfield::select($field->field, JSLEARNMANAGERincluder::getJSModel('language')->getlanguageForCombo(),isset(jslearnmanager::$_data[0]->language)? jslearnmanager::$_data[0]->language :'',__($field->fieldtitle,'learn-manager'), array('class' => 'inputbox jslm_select_full_width','data-validation' => $req)); ?>
                              </div>
                           </div>
                     <?php
                     break;
                     default:
                        echo JSLEARNMANAGERincluder::getObjectClass('customfields')->formCustomFields($field,1);
                     break;
                     }
                  }?>


                  <div class="jslm_btn_row">
                     <button class="jslm_btn_style" type="submit">
                        <?php if(isset(jslearnmanager::$_data[0]->id)){
                           echo esc_html__('Update','learn-manager');
                        }else{
                           echo esc_html__('Create Course','learn-manager');
                        }  ?>
                     </button>
                     <?php if(isset(jslearnmanager::$_data[0]->id)){ ?>
                        <a class="jslm_btn_style jslm_btn_cancel" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>JSLEARNMANAGERrequest::getVar('jslearnmanagerid')))); ?>"><?php echo esc_html__('Cancel','learn-manager'); ?></a>
                     <?php }else{ ?>
                        <a class="jslm_btn_style jslm_btn_cancel" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'instructor', 'jslmslay'=>'mycourses'))); ?>"><?php echo esc_html__('Cancel','learn-manager'); ?></a>
                     <?php } ?>
                  </div>
                  <?php echo JSLEARNMANAGERformfield::hidden('id', isset(jslearnmanager::$_data[0]->id) ? jslearnmanager::$_data[0]->id : '', array('class' => 'inputbox one')) ?>
                  <?php echo JSLEARNMANAGERformfield::hidden('paymentplan_id', isset(jslearnmanager::$_data[0]->paymentplan_id) ? jslearnmanager::$_data[0]->paymentplan_id : 0, array('class' => 'inputbox one')) ?>
               </div>
            </form>
         </div>
      </div>
<?php }else{
      echo JSLEARNMANAGERLayout::setMessageFor(6,null,null,0);
   }
} ?>
   </div>
</div>
</div>

