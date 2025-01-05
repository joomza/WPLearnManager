<div class="jslm_main-up-wrapper">
<?php if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('user')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
$msgkey = JSLEARNMANAGERincluder::getJSModel('student')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');


function additionfields($title, $value) {
	$html = '<div class="jslm_custom_field">
              	<span class="jslm_heading">' . $title . ':</span>';
          		if(strlen($value) > 40){
          			$value = substr($value,0,25).'....';
          		}
          		$html .= '<span class="jslm_text">' . $value . '</span>';

  	$html .= '</div>';
  	return $html;
}
$curdate = date_i18n('Y-m-d');

function checkFields($name) {
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
<?php
$isapprove = false;
if(isset(jslearnmanager::$_data['profile'])){
    $studentname = jslearnmanager::$_data['profile']->name;
    $profileimage = jslearnmanager::$_data['profile']->image;
    $isapprove = JSLEARNMANAGERincluder::getJSModel('student')->getStudentApprovalStatus(jslearnmanager::$_data['profile']->user_id,1);
}?>
<div id="loading" class="jslm_loader_loading" style="display: none"><img alt="<?php echo esc_attr(__("Loading Image","learn-manager")); ?>" title="<?php echo esc_attr(__("Loading Image","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/loading.gif"></div>
<div class="jslm_content_wrapper"> <!-- lower bottom Content -->
  <div class="jslm_content_data">
    <div class="jslm_search_content no-border no-padding-bottom">
      <div class="jslm_top_title">
        <div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Dashboard","learn-manager"); ?></h3></div>
        <?php if($isapprove != 1 && jslearnmanager::$_error_flag_message == null){ ?>
            <div class="jslm_right_data">
                <span class="jslm_sorting">
                    <span class="jslm_link"><?php echo __("Account not approved" , "learn-manager"); ?></span>
                </span>
            </div>
        <?php } ?>
      </div>
    </div>
    <?php if (jslearnmanager::$_error_flag_message == null) { ?>
        <div class="jslm_data_container no-padding-top">
            <div class="jslm_dashboard_wrapper">
                <div class="jslm_dashboard_left_wrp">
                    <div class="jslm_myprofile">
                        <div class="jslm_profile_heading"><?php echo __("My Profile","learn-manager"); ?></div>
                        <div class="jslm_profileleft_middle">
                            <div class="jslm_myprofile_image">
                                <div class="jslm_image_wrapper">
                                    <?php if( $profileimage !='' &&  $profileimage != null){
                                        $imageadd =  $profileimage;
                                    }else{
                                        $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                                    }?>
                                    <img src="<?php echo esc_url($imageadd); ?>">
                                </div>
                            </div>
                            <span class="jslm_title"><?php echo __("Welcome","learn-manager"); ?></span>
                            <span class="jslm_title jslm_name"><?php echo esc_html($studentname); ?></span>
                            <span class="jslm_edit">
                                <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'user','jslmslay'=>'profileform'))); ?>"><?php echo __("Edit Profile","learn-manager"); ?></a>
                            </span>
                        </div>
                    </div>
                    <div class="jslm_myprofile">
                        <div class="jslm_profile_heading"><?php echo __("Useful Links","learn-manager"); ?></div>
                        <div class="jslm_links_list">
                            <ul class="jslm_usefullinks">
                                <!-- <li class="jslm_links">
                                    <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'dashboard'))); ?>"><i class="fa fa-dashboard"></i><?php echo __("Dashboard","learn-manager"); ?></a>
                                </li> -->
                                <li class="jslm_links">
                                    <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'student', 'jslmslay'=>'mycourses'))); ?>"><i class="fa fa-book"></i><?php echo __("My Courses","learn-manager"); ?></a>
                                </li>
                                <li class="jslm_links">
                                    <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'shortlistcourses'))); ?>"><i class="fa fa-heart-o"></i><?php echo __("Shortlist Courses","learn-manager"); ?></a>
                                </li>
                                <li class="jslm_links">
                                    <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'student', 'jslmslay'=>'studentprofile'))); ?>"><i class="fa fa-user"></i><?php echo __("My Profile","learn-manager"); ?></a>
                                </li>
                                <?php do_action("jslm_addons_dashboard_links_for_student"); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="jslm_dashboard_right_wrp">
                    <div class="jslm_myprofile">
                        <div class="jslm_profile_heading"><?php echo __("My Courses","learn-manager"); ?></div>
                        <div class="jslm_profileleft_middle jslm_profile_padding">
                            <ul class="nav nav-tabs jslm-textalign-left" id="myTabdashboard">
                                <li class="jslm_li_styling active" ><a title="<?php echo esc_attr(__("My courses","learn-manager")); ?>" class="jslm_anchor_style" data-toggle="#mycourses"><?php echo esc_html__("My Courses","learn-manager"); ?></a></li>
                                <li class="jslm_li_styling"><a title="<?php echo esc_attr(__("Shortlist Courses","learn-manager")); ?>" class="jslm_anchor_style jslm_left_border" data-toggle="#shortlisted"><?php echo esc_html__("Shortlist Courses","learn-manager"); ?></a></li>
                                <?php  if(count(jslearnmanager::$_data['recentenroll']) > 0){ ?>
                                    <a id="jslm_view_all_mycourse" class="jslm_view_all" href="<?php echo esc_url(jslearnmanager::makeUrl(array("jslmsmod"=>"student","jslmslay"=>"mycourses"))); ?>"><?php echo __("View All","learn-manager"); ?></a>
                                <?php } ?>
                                <?php if(count(jslearnmanager::$_data['shortlist']) > 0){ ?>
                                    <a id="jslm_view_all_shortlisted" class="jslm_view_all" href="<?php echo esc_url(jslearnmanager::makeUrl(array("jslmsmod"=>"course","jslmslay"=>"shortlistcourses"))); ?>"><?php echo __("View All","learn-manager"); ?></a>
                                <?php } ?>
                            </ul>
                            <div id="myTabdashboarddata" class="tab-content">
                                <div id="mycourses" class="jslm_dashboard_course_list active fade in">
                                    <table class="jslm-table">
                                        <thead>
                                            <tr class="jslm-table-row">
                                                <th class="jslm-table-row-heading"><?php echo __("Course Name","learn-manager"); ?></th>
                                                <th class="jslm-table-row-heading"><?php echo __("Instructor","learn-manager"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="jslm-table-body">
                                            <?php if(count(jslearnmanager::$_data['recentenroll']) > 0){
                                                    for ($rc=0 , $rcp = count(jslearnmanager::$_data['recentenroll']); $rc < $rcp ; $rc++) {
                                                        $recentenroll = jslearnmanager::$_data['recentenroll'][$rc];
                                                        if($recentenroll->instructor_image != ""){
                                                            $defaultimage = $recentenroll->instructor_image;
                                                        }else{
                                                            $defaultimage = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                                                        }
                                                        ?>
                                                        <tr class="jslm-table-row jslm-no-bgc">
                                                            <td data-th="Course Name" class="jslm-table-data"><a class="jslm_dash_course" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'coursedetails', 'jslearnmanagerid'=>$recentenroll->course_id))); ?>"><?php echo esc_html($recentenroll->title); ?></a></td>
                                                            <td data-th="Instructor" class="jslm-table-data"><img src="<?php echo  esc_url($defaultimage); ?>"><a class="jslm_instructor_link" href="<?php echo isset($recentenroll->instructor_id) ? esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'instructor','jslmslay'=>'instructordetails','jslearnmanagerid'=>$recentenroll->instructor_id))) : "#"; ?>"><?php echo isset($recentenroll->instructor_name) ? $recentenroll->instructor_name : "Admin"; ?></a></td>
                                                        </tr>
                                            <?php   }
                                            }else{ ?>
                                                <tr><td colspan="2"><div class="jslm_no_record"><?php echo __("You have no course","learn-manager"); ?></div></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="shortlisted" class="jslm_dashboard_course_list fade" >
                                    <div class="jslm_dashboard_course_list_container">
                                        <table class="jslm-table">
                                            <thead>
                                                <tr class="jslm-table-row">
                                                    <th class="jslm-table-row-heading"><?php echo __("Course Name","learn-manager"); ?></th>
                                                    <th class="jslm-table-row-heading"><?php echo __("Instructor","learn-manager"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="jslm-table-body">
                                                <?php if(count(jslearnmanager::$_data['shortlist']) > 0){
                                                        for ($en=0 , $enp = count(jslearnmanager::$_data['shortlist']); $en < $enp; $en++) {
                                                            $shortlist = jslearnmanager::$_data['shortlist'][$en];
                                                            if($shortlist->instructor_image != ""){
                                                                $defaultimage = $shortlist->instructor_image;
                                                            }else{
                                                                $defaultimage = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                                                            }
                                                            ?>
                                                            <tr class="jslm-table-row jslm-no-bgc">
                                                                <td data-th="Course Name" class="jslm-table-data"><a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'coursedetails', 'jslearnmanagerid'=>$shortlist->course_id))) ?>"><?php echo esc_html($shortlist->title); ?></a></td>
                                                                <td data-th="Instructor" class="jslm-table-data"><img src="<?php echo esc_url($defaultimage); ?>"><?php echo isset($shortlist->instructor_name) ? esc_html($shortlist->instructor_name) : "Admin"; ?></td>
                                                            </tr>
                                                <?php   }
                                                }else{ ?>
                                                    <tr><td colspan="2"><div class="jslm_no_record"><?php echo __("You have no shortlist course","learn-manager"); ?></div></td></tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php  if(in_array("quiz", jslearnmanager::$_active_addons) || in_array("awards", jslearnmanager::$_active_addons)){ ?>
                        <div class="jslm_myprofile">
                            <div class="jslm_profile_heading"><?php echo __("Stats","learn-manager"); ?></div>
                            <div class="jslm_profileleft_middle jslm_profile_padding">
                                <ul class="nav nav-tabs jslm-textalign-left" id="myrewardquiztab">
                                    <?php do_action("jslm_addons_html_for_user_dashboardtab",1); ?>
                                    <?php do_action("jslm_quiz_html_for_dashboardtab_student"); ?>
                                </ul>
                                <div id="myrewardquiztabdata" class="tab-content">
                                    <?php do_action("jslm_addons_user_dashboard_tabs_data"); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php }else{
	   echo jslearnmanager::$_error_flag_message;
    }?>
  </div>
</div>
</div>

