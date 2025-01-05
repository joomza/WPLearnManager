<div class="jslm_main-up-wrapper">
<?php if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('student')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
$msgkey = JSLEARNMANAGERincluder::getJSModel('user')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
$i = 0;
$curdate = date_i18n('Y-m-d');
function getDataRow($title, $value) {
   $html = '<div class="jslm_custom_field">
               <span class="jslm_heading">' . $title . ':</span>';
               if(isset($value)){
                  if(strlen($value) > 40){
                     $value = substr($value,0,25).'....';
                  }
                  $html .= '<span class="jslm_text">' . $value . '</span>';
              }
      $html .= '</div>';
      return $html;
}

function additionfields($title,$value,$l=0){
   $html = "";
   if($value != ""){
      $html = '<div class="jslm_custom_field">
         <span class="jslm_heading">' . $title . ':</span>';
         if($l == 1){
            if(strlen($value) > 25){
               $value = substr($value,0,25).'....';
            }
         }
         if($value != ""){
            $html .= '<span class="jslm_text">' . $value . '</span>';
         }

      $html .= '</div>';
      return $html;
   }
   return $html;
}

function checkLinks($name,$for=1) {
   if($for == 1){ // for course
      $array = jslearnmanager::$_data[2];
   }else{ // for user
      $array = jslearnmanager::$_data['profilecustomfields'];
   }
   foreach ($array as $field) {
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
      <div class="jslm_content_data">  <!-- Bottom_content_data -->
         <div class="jslm_search_content">
            <div class="jslm_top_title">
               <div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Student Profile","learn-manager"); ?></h3></div>
            </div>
         </div>
         <?php if(jslearnmanager::$_error_flag_message == null){ ?>
         <?php if(!empty(jslearnmanager::$_data['profile'])){
            $profile = jslearnmanager::$_data['profile'];
            $total_courses = jslearnmanager::$_data[0]['totalcourses'];
            $shortlist_courses = jslearnmanager::$_data[0]['shortlistcourses'];
            $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
            $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
            $isapprove = JSLEARNMANAGERincluder::getJSModel('student')->getStudentApprovalStatus($uid,1);
         ?>
         <div class="jslm_student_wrapper">
            <div class="jslm_left_data">
	            <div class="jslm_img_wrapper">
                  <?php if($profile->image == "" && empty($profile->image)){
                     $profileimage = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                  }else{
                     $profileimage = $profile->image;
                  }?>
	               <img alt="<?php echo esc_attr(__("Profile image","learn-manager")); ?>" title="<?php echo esc_attr(__("Profile image","learn-manager")); ?>" src="<?php echo esc_attr($profileimage); ?>">
	            </div>
               <?php if($usertype == "Instructor"){
                  do_action("jslm_message_button_for_user_profile",$profile->user_id,1);
	            }
               if(JSLEARNMANAGERrequest::getVar('jslearnmanagerid') == "" && $isapprove == 1){ ?>
                  <div class="jslm_send_message_wrapper">
                     <a title="<?php echo esc_attr(__("Edit Profile","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'profileform'))); ?>" class="jslm_send_message_anchor"><?php echo esc_html__("Edit Profile","learn-manager"); ?></a>
                  </div>
               <?php }
               if(JSLEARNMANAGERrequest::getVar('jslearnmanagerid') == "" && $isapprove != 1){   ?>
                  <div class="jslm_send_message_wrapper">
                     <span class="jslm_approve_status"><?php echo __("Account not approved","learn-manager"); ?></span>
                  </div>
               <?php }
               $print = checkLinks('sociallinks',2);
               if($print[0] == 1){  ?>
                  <div class="jslm_social_icons">
                  <?php if($profile->facebook_url != null && !empty($profile->facebook_url)){ ?>
                     <a title="<?php echo esc_attr(__("Facebook","learn-manager")); ?>" href="<?php echo esc_url($profile->facebook_url); ?>" class="jslm_social jslm_facebook"><i class="fa fa-facebook"></i></a>
                  <?php } if($profile->twitter != null && !empty($profile->twitter)){ ?>
                     <a title="<?php echo esc_attr(__("Twitter","learn-manager")); ?>" href="<?php echo esc_url($profile->twitter); ?>" class="jslm_social jslm_twitter"><i class="fa fa-twitter"></i></a>
                  <?php } if($profile->linkedin != null && !empty($profile->linkedin)){ ?>
                     <a title="<?php echo esc_attr(__("Linkedin","learn-manager")); ?>" href="<?php echo esc_url($profile->linkedin); ?>" class="jslm_social jslm_linkedin"><i class="fa fa-linkedin"></i></a>
                  <?php } ?>
                  </div>
               <?php } ?>
      	   </div>
	         <div class="jslm_right_data">
	            <div class="jslm_top_heading">
	               <div class="jslm_heading">
	                  <h2 class="jslm_heading_title"><?php echo esc_html($profile->name); ?></h2>
	                  <span class="jslm_label"><?php echo esc_html__("Student","learn-manager"); ?></span>
	               </div>
                  <div class="jslm_info">
                     <?php $print = checkLinks('email',2);
                     if($print[0] == 1 && $profile->email != ""){  ?>
                        <h4 class="jslm_icons jslm_primary_color"><i class="fa fa-envelope-o"></i> <?php echo esc_html($profile->email); ?></h4>
                     <?php }
                     $print = checkLinks('gender',2);
                     if($print[0] == 1 && $profile->gender != ""){  ?>
                        <h4 class="jslm_icons"><i class="fa fa-transgender"></i> <?php echo esc_html($profile->gender); ?></h4>
                     <?php }
                     $print = checkLinks('country',2);
                     if($print[0] == 1 && $profile->location != ""){  ?>
                        <h4 class="jslm_icons"><i class="fa fa-map-marker"></i> <?php echo esc_html($profile->location); ?></h4>
                     <?php } ?>
                  </div>
	            </div>
	            <div class="jslm_middle_content">
                  <?php $print = checkLinks('bio',2);
                  if($print[0] == 1){  ?>
   	               <span class="jslm_text jslm_bigfont">
                       <?php echo html_entity_decode(wp_kses_post(esc_html($profile->bio,'learn-manager'))); ?>
                     </span>
                  <?php } ?>
	            </div>
               <?php $iscustomfield = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsData(3);
               if(!empty($iscustomfield)){ ?>
                  <div class="jslm_home_data_row">
                     <div class="jslm_custom_fields_wrapper">
                        <!-- <div class="jslm_custom_fields_heading">
                           <h4 class="jslm_heading_style"><?php echo esc_html__("Additional Features","learn-manager"); ?></h4>
                        </div> -->
                        <?php foreach(jslearnmanager::$_data['profilecustomfields'] as $fields){
                              switch($fields->field){
                                 default:
                                    if($fields->isuserfield == 1){
                                       $array = JSLEARNMANAGERincluder::getObjectClass('customfields')->showCustomFields($fields, 1, $profile->params,3);
                                          echo additionfields(__($array[0],'learn-manager'), __($array[1],'learn-manager'));
                                       break;
                                 }
                              }
                           }
                        ?>
                     </div>
                  </div>
               <?php } ?>
               <div class="jslm_bottom_content">
                  <span class="jslm_logo">
                     <span class="jslm_left">
                       <img alt="<?php echo esc_attr(__("Courses","learn-manager")); ?>" title="<?php echo esc_attr(__("Courses","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/courses.png'); ?>">
                     </span>
                     <span class="jslm_right">
                        <span class="jslm_bc_text"><?php echo esc_html__("Courses","learn-manager"); ?></span>
                        <span class="jslm_number"><?php echo esc_html($total_courses); ?></span>
                     </span>
                  </span>
                  <span class="jslm_logo">
                     <span class="jslm_left">
                       <img alt="<?php echo esc_attr(__("Shortlisted","learn-manager")); ?>" title="<?php echo esc_attr(__("Shortlisted","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/courses.png'); ?>">
                     </span>
                     <span class="jslm_right">
                        <span class="jslm_bc_text"><?php echo esc_html__("Shortlisted","learn-manager"); ?></span>
                        <span class="jslm_number"><?php echo esc_html($shortlist_courses); ?></span>
                     </span>
                  </span>
               </div>
	         </div>
         </div>
         <?php } ?>
      <?php }else{
         echo jslearnmanager::$_error_flag_message;
      } ?>
      </div>
   </div>
</div>


