<div class="jslm_main-up-wrapper">
<?php
$msgkey = JSLEARNMANAGERincluder::getJSModel('user')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
$msgkey = JSLEARNMANAGERincluder::getJSModel('instructor')->getMessagekey();
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
$sortarray = array(
	(object) array('id' => 1, 'text' => __('Category', 'learn-manager')),
	(object) array('id' => 3, 'text' => __('Created', 'learn-manager')),
	(object) array('id' => 2, 'text' => __('Price', 'learn-manager')),
	(object) array('id' => 5, 'text' => __('Title', 'learn-manager')),
);

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
$isapprove = 0;
if(isset(jslearnmanager::$_data['profile'])){
    $studentname = jslearnmanager::$_data['profile']->name;
    $profileimage = jslearnmanager::$_data['profile']->image;
    $isapprove = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorApprovalStatus(jslearnmanager::$_data['profile']->user_id , 1);
} ?>
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
    <?php if(jslearnmanager::$_error_flag_message == null){ ?>
    <div class="jslm_data_container">
        <div class="jslm_dashboard_wrapper">
            <div class="jslm_dashboard_wrapper_cover">
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
                </div>
                <div class="jslm_dashboard_right_wrp">
                    <div class="jslm_myprofile">
                        <div class="jslm_profile_heading"><?php echo __("My Courses","learn-manager"); ?></div>
                        <div class="jslm_profileleft_middle jslm_profile_padding">
                            <ul class="nav nav-tabs jslm-textalign-left" id="myTabdashboard">
                                <li class="jslm_li_styling active" ><a title="<?php echo esc_attr(__("My courses","learn-manager")); ?>" class="jslm_anchor_style" data-toggle="#mycourses"><?php echo esc_html__("My Courses","learn-manager"); ?></a></li>
                                <li class="jslm_li_styling"><a title="<?php echo esc_attr(__("Enrolled","learn-manager")); ?>" class="jslm_anchor_style jslm_left_border" data-toggle="#enrolled"><?php echo esc_html__("Recently enrolled","learn-manager"); ?></a></li>
                            </ul>
                            <div id="myTabdashboarddata" class="tab-content">
                                <div id="mycourses" class="jslm_dashboard_course_list active fade in">
                                    <div class="jslm_dashboard_course_list_container">
                                        <div class="jslm_top_heading">
                                            <span class="jslm_heading_left_name"><?php echo __("Course Name","learn-manager");?></span>
                                            <span class="jslm_heading_right_category"><?php echo __("Category","learn-manager");?></span>
                                        </div>
                                        <?php if(count(jslearnmanager::$_data['recentcreate']) > 0){
                                            for ($rc=0 , $rcp = count(jslearnmanager::$_data['recentcreate']); $rc < $rcp ; $rc++) {
                                                $recentcreate = jslearnmanager::$_data['recentcreate'][$rc];    ?>
                                                <div class="jslm_bottom_list">
                                                    <span class="jslm_heading_left_name">
                                                        <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$recentcreate->id))); ?>"><?php echo esc_html($recentcreate->title); ?></a>
                                                    </span>
                                                    <span class="jslm_heading_right_category"><?php echo esc_html($recentcreate->category); ?></span>
                                                </div>
                                        <?php }
                                        }else{ ?>
                                            <div class="jslm_no_record"><?php echo __("You have no course","learn-manager"); ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div id="enrolled" class="jslm_dashboard_course_list fade" >
                                    <table class="jslm-table">
                                        <thead>
                                            <tr class="jslm-table-row">
                                                <th class="jslm-table-row-heading"><?php echo __("Student Name","learn-manager"); ?></th>
                                                <th class="jslm-table-row-heading"><?php echo __("Enrolled Course","learn-manager"); ?></th>
                                                <th class="jslm-table-row-heading"><?php echo __("Category","learn-manager"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="jslm-table-body">
                                            <?php if(count(jslearnmanager::$_data['recent_enroll']) > 0){
                                                    for ($en=0 , $enp = count(jslearnmanager::$_data['recent_enroll']); $en < $enp; $en++) {
                                                        $recently_enroll = jslearnmanager::$_data['recent_enroll'][$en];?>
                                                        <tr class="jslm-table-row jslm-no-bgc">
                                                            <td data-th="Student Name" class="jslm-table-data"><a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'student', 'jslmslay'=>'studentprofile', 'jslearnmanagerid'=>$recently_enroll->student_id))) ?>"><?php echo esc_html($recently_enroll->student_name); ?></a></td>
                                                            <td data-th="Enrolled Course" class="jslm-table-data"><?php echo esc_html($recently_enroll->course_title); ?></td>
                                                            <td data-th="Category" class="jslm-table-data"><?php echo esc_html($recently_enroll->category); ?></td>
                                                        </tr>
                                            <?php   }
                                            }else{ ?>
                                                <tr><td colspan="3"><div class="jslm_no_record"><?php echo __("You have no student","learn-manager"); ?></div></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="jslm_dashboard_wrapper_cover">
                <div class="jslm_dashboard_left_wrp">
                    <div class="jslm_myprofile">
                        <div class="jslm_profile_heading"><?php echo __("Useful Links","learn-manager"); ?></div>
                        <div class="jslm_links_list">
                            <ul class="jslm_usefullinks">
                                <!-- <li class="jslm_links">
                                    <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'dashboard'))); ?>"><i class="fa fa-dashboard"></i><?php echo __("Dashboard","learn-manager"); ?></a>
                                </li> -->
                                <li class="jslm_links">
                                    <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'instructor', 'jslmslay'=>'mycourses'))); ?>"><i class="fa fa-book"></i><?php echo __("My Courses","learn-manager"); ?></a>
                                </li>
                                <li class="jslm_links">
                                    <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'instructor', 'jslmslay'=>'instructordetails'))); ?>"><i class="fa fa-user"></i><?php echo __("My Profile","learn-manager"); ?></a>
                                </li>
                                <?php do_action("jslm_addons_dashboard_links_for_instructor"); ?>

                            </ul>
                        </div>
                    </div>
                </div>
                <?php if(in_array('paidcourse', jslearnmanager::$_active_addons) || in_array('awards', jslearnmanager::$_active_addons) || in_array('payouts',jslearnmanager::$_active_addons)){ ?>
                    <div class="jslm_dashboard_right_wrp">
                        <div class="jslm_myprofile">
                            <div class="jslm_profile_heading"><?php echo __("Stats","learn-manager"); ?></div>
                            <div class="jslm_profileleft_middle jslm_profile_padding">
                                <ul class="nav nav-tabs jslm-textalign-left" id="myearningtabs">
                                    <?php do_action("jslm_addons_html_for_user_dashboardtab"); ?>
                                    <?php do_action("jslm_addons_html_for_instructor_dashboardtab"); ?>
                                </ul>
                                <div id="myearningtabsdata" class="tab-content">
                                    <?php do_action("jslm_addons_user_dashboard_tabs_data"); ?>
                                </div>
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

