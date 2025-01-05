<?php
if (!defined('ABSPATH'))die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('student')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
if(jslearnmanager::$_config['date_format'] == 'd-m-Y' ){
    $date_format_string = 'd/F/Y';
}elseif(jslearnmanager::$_config['date_format'] == 'm/d/Y'){
    $date_format_string = 'F/d/Y';
}elseif(jslearnmanager::$_config['date_format'] == 'Y-m-d'){
    $date_format_string = 'Y/F/d';
}
$categoryarray = array(
    (object) array('id' => 1, 'text' => __('Category', 'learn-manager')),
    (object) array('id' => 2, 'text' => __('Price', 'learn-manager')),
    (object) array('id' => 3, 'text' => __('Created', 'learn-manager')),
);
?>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php  JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
     <div id="jslearnmanageradmin-data">
    <div id="jslearnmanageradmin-wrapper-top">
            <div id="jslearnmanageradmin-wrapper-top-left">
                <div id="jslearnmanageradmin-breadcrunbs">
                    <ul>
                        <li>
                            <a href="admin.php?page=jslearnmanager">
                                <?php echo __('Dashboard', 'learn-manager'); ?>
                            </a>
                        </li>
                        <li><?php echo __('Student Details','js-support-ticket'); ?></li>
                    </ul>
                </div>
            </div>
            <div id="jslearnmanageradmin-wrapper-top-right">
                <div id="jslearnmanageradmin-help-txt">
                   <a Href="<?php echo esc_url(admin_url("admin.php?page=jslearnmanager&jslmslay=help")); ?>" title="<?php echo __('help','leARN-MANAGER'); ?>">
                        <img alt="<?Php ecHo __('help','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help.png" />
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
            <span class="jslm_heading-dashboard"><?php echo __('Student Details', 'learn-manager'); ?></span>
        </div>            
      <div id="jslms-data-wrp">
        <hr class="listing-hr" />
    <?php if (!empty(jslearnmanager::$_data['profile'])) {
            $profile = jslearnmanager::$_data['profile']; ?>
            <div id="student_<?php echo esc_attr($profile->user_id); ?>" class="instructor-container instructor-container-margin-bottom js-col-lg-12 js-col-md-12 no-padding">
                <div id="item-data" class="item-data item-data-resume js-row no-margin">
                   <div class="item-icon js_circle">
                        <div class="profile">
                            <a class="js-anchor" href="#">
                            <span class="js-border">
                              <?php if($profile->image !='' && $profile->image != null){
                                $imageadd = $profile->image;
                              }else{
                                $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                              }?>
                                <img src="<?php echo esc_url($imageadd); ?>"/>
                            </span>
                            </a>
                        </div>
                    </div>
                    <div class="item-details js-col-lg-10 js-col-md-10 js-col-xs-12 no-padding">
                        <div class="item-title js-col-lg-12 js-col-md-12 js-col-xs-8 no-padding">
                            <span class="value title-text-user">
                                <a href="#" ><?php echo esc_html($profile->name); ?></a>
                                <span class="role-student">
                                    <?php
                                      echo __('Student','learn-manager');
                                    ?>
                                </span>
                            </span>
                        </div>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                          <span class="heading"><?php echo __('Email', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($profile->email);?></span>
                        </div>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                          <span class="heading"><?php echo __('Gender', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($profile->gender);?></span>
                        </div>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                          <span class="heading"><?php echo __('Location', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($profile->location); ?></span>
                        </div>
                    </div>
                </div>
                 <div id="item-actions" class="item-actions js-row no-margin">
                    <div class="item-text-block js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding instructor-layout-id">
                        <span class="heading"><?php echo __('Student ID', 'learn-manager') . ': '; ?></span><span class="item-action-text"><?php echo esc_html(JSLEARNMANAGERrequest::getVar('jslearnmanagerid')); ?></span>
                    </div>
                </div>
                <div class="jslm_user-stats">
                  <div class="stat-parts">
                    <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/courses.png">
                    <span class="number"><?php echo esc_html(jslearnmanager::$_data[0]['totalcourses']); ?></span>
                    <span class="text"><?php echo __('Course','learn-manager')?></span>
                  </div>
                  <div class="stat-parts">
                    <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/instructor_students.png">
                    <span class="number"><?php echo  esc_html(jslearnmanager::$_data[0]['shortlistcourses']); ?></span>
                    <span class="text"><?php echo __('Stortlist Courses','learn-manager')?></span>
                  </div>
                  <?php do_action("jslm_awards_show_userdetail_awards_for_admin"); ?>
                </div>
            </div>
            <div class="jslm_course_wrapper">
                <div class="jslm_heading">
                  <h4 class="jslm_heading_style"><?php echo __("Student's Courses","learn-manager"); ?> </h4>
                </div>
                  <?php if (!empty(jslearnmanager::$_data['mycourses'])){
                    foreach (jslearnmanager::$_data['mycourses'] AS $course) {
                  ?>
                <div id="course<?php echo esc_attr($course->course_id);?>" class="course-container js-col-lg-12 js-col-md-12 no-padding">
                    <div id="item-data" class="item-data js-row no-margin">
                        <span id="selector_<?php echo esc_attr($course->course_id); ?>" class="selector"><input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($course->course_id); ?>);" class="jslearnmanager-cb" id="jslearnmanager-cb" name="jslearnmanager-cb[]" value="<?php echo esc_attr($course->course_id); ?>" /></span>
                        <div class="item-icon">
                          <?php if($course->fileloc != ''){
                            $imageadd = $course->fileloc;
                          }else{
                            $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                          }
                          ?>
                          <a href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id);?>">
                            <img src="<?php echo esc_url($imageadd); ?>" />
                          </a>
                        </div>
                        <div class="item-details">
                            <div class="item-title js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                <span class="value"><a href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id);?>"><?php echo esc_html($course->title); ?></a></span>
                            </div>
                            <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                                <span class="heading"><?php echo __("Instructor","learn-manager"); ?>:</span><span class="value"><?php echo isset($course->instructor_name) ? esc_html($course->instructor_name) : "Admin"; ?></span>
                            </div>
                            <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                                <span class="heading"><?php echo __("Enrolled Date","learn-manager"); ?>:</span><span class="value"><?php echo esc_html(__(date('D d-M-Y', strtotime($course->enrolleddate)),'learn-manager')); ?></span>
                            </div>
                            <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                                <span class="heading"><?php echo __("Category","learn-manager"); ?>:</span><span class="value"><?php echo esc_html(__($course->category,'learn-manager')); ?></span>
                            </div>
                              <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                                <span class="heading"><?php echo __("Lesson","learn-manager"); ?>:</span><span class="value"><?php echo esc_html(__($course->total_lessons,'learn-manager')); ?></span>
                            </div>

                          <?php
                          $print = true;
                          $curdate = date_i18n('Y-m-d');
                          $publishstatus = __('Enrolled','learn-manager');
                          $publishstyle = 'background:#00A859;color:#ffffff;border:unset;';
                          ?>
                    </div>
                    <div id="for_ajax_only_<?php echo esc_attr($course->course_id); ?>">
                      <div id="item-actions" class="item-actions js-row no-margin">
                        <div class="item-text-block js-col-lg-4 js-col-md-4 js-col-xs-12 no-padding">
                          <span class="heading"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/students.png"></span><span class="item-action-text"><?php echo esc_html($course->total_students); ?></span>
                          <span class="heading"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/lesson.png"></span><span class="item-action-text"><?php echo esc_html($course->total_lessons); ?></span>
                        </div>
                        <div class="item-values js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding">
                          <a class="js-action-link button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=jslm_student&action=jslmstask&task=removeenrollment&jslearnmanagerid='.$course->course_id.'&jslearnmanagersid='.JSLEARNMANAGERrequest::getVar('jslearnmanagerid')),'remove-enrollment'); ?>&callfrom=1" onclick="return confirm('<?php echo __('Are you sure to delete','learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete-icon.png" alt="del" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>"/>&nbsp;&nbsp;<?php echo __('Un-enroll', 'learn-manager'); ?></a>
                          <a class="js-action-link button" href="<?php echo esc_url(admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id));?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/tick.png" /><?php echo __('View Detail', 'learn-manager') ?></a>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
        <?php } ?>
    <?php }
      if (jslearnmanager::$_data[1]) {
        echo '<div class="tablenav"><div class="tablenav-pages">' . jslearnmanager::$_data[1] . '</div></div>';
      } ?>
    <?php
    } else {
        $msg = __('No record found','learn-manager');
        echo JSLEARNMANAGERlayout::getNoRecordFound($msg);
    }
    ?>
</div>
</div>
</div>
