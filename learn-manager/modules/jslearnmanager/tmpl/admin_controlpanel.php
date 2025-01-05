<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
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
            <span class="jslm_heading-dashboard"><?php echo __('Dashboard', 'learn-manager'); ?></span>
            <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
            <script>
            function showPopUpVersionChnages(){
                jQuery("#full_background").show();
                jQuery("#popup_main").slideDown("slow");
            }

            function closePopupVersioChanges(){
                jQuery("#popup_main").slideUp("slow");
                jQuery("#full_background").hide();
            }
            </script>
            <div id="full_background" style="display:none;" onclick="closePopupVersioChanges()" ></div>
            <div id="popup_main" class="jslms-version-changes-popup" style="display:none;">
                <span class="popup-top">
                    <span id="popup_title" >
                        <?php echo __("Your Version","learn-manager").':&nbsp;'.$data_array['version'];?>
                    </span>
                    <img id="popup_cross" alt="popup cross" onclick="closePopupVersioChanges()" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/popup-close.png">
                </span>
                <div class="jslms-version-changes-popup-data" >
                    <?php
                        $version_count = 0;
                        if(!empty($response)) { ?>
                            <?php
                            $vi = 1;
                            foreach ($response as $version => $changes) {
                                if($current_version == $version){
                                    continue;
                                }
                                if($vi > 5){
                                    $vi = 1;
                                }
                                ?>
                                <div class="jslms-version-changes-popup-version-title version_count_num_<?php echo esc_attr($vi);?>" > <?php echo esc_html($version);?></div>
                                <?php
                                    if($version_count == 4){
                                        $version_count = 0;
                                    }else{
                                        $version_count++;
                                    }
                                if(empty($changes['pro'])){
                                    foreach ($changes['free'] as $key => $val) {
                                        echo '<div class="jslms-version-change-wrapper">';
                                            echo '<div class="jslms-version-changes-popup-changes" >'.$val.'

                                            </div>';
                                        echo '</div>';
                                    }
                                }
                                foreach ($changes['pro'] as $key => $val) {
                                    echo '<div class="jslms-version-change-wrapper">';
                                        echo '<div class="jslms-version-changes-popup-changes" >'.$val.'<img src='.JSLEARNMANAGER_PLUGIN_URL.'includes/images/cp/pro-version.jpg /></div>';
                                    echo '</div>';
                                }
                                $vi++;
                            }

                        }?>
                </div>
                <div class="version-change-popup-button-wrapper" >
                    <a class="version-change-popup-first-button" target="_blank" href="admin.php?page=jslearnmanager&jslmslay=stepone" ><?php echo __('Update To Latest', 'learn-manager'); ?></a>
                </div>
            </div>
        </div>
        <div id="jslearnmanager-admin-wrapper">
            <?php if(get_option( 'jslms_hide_lmadmin_top_banner') != 1){ ?>
                <div class="jslm_cp-video-baner">
                    <div class="jslm_cp-video-baner-cnt">
                        <div class="jslm_cp-video-banner-tit-bold">
                            <?php echo __('Quick installation Guide'); ?>
                        </div>
                        <div class="jslm_cp-video-banner-desc">
                            <?php echo __('It has survived not only five centuries, but also the leap into'); ?>
                        </div>
                    </div>
                    <div class="jslm_cp-video-banner-btn-main-wrp">
                        <div class="jslm_cp-video-banner-btn-wrp jslm_cp-video-banner-btn1">
                            <a target="_blank" href="https://www.youtube.com/watch?v=veQt-3QlMw8" class="jslm_cp-video-banner-btn yellow-bg">
                                <img alt="Arrow" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/play-btn.png">
                                <?php echo __('How to setup'); ?>
                            </a>
                        </div>
                        <div class="jslm_cp-video-banner-btn-wrp jslm_cp-video-banner-btn2">
                            <a target="_blank" href="https://www.youtube.com/watch?v=AWsse6gyz_M" class="jslm_cp-video-banner-btn yellow-bg">
                                <img alt="Arrow" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/play-btn.png">
                                <?php echo __('Create Course'); ?>
                            </a>
                        </div>
                        <div class="jslm_cp-video-banner-btn-wrp jslm_cp-video-banner-btn3">
                            <a target="_blank" href="https://www.youtube.com/watch?v=QNCdyvdeOXU" class="jslm_cp-video-banner-btn yellow-bg">
                                <img alt="Arrow" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/play-btn.png">
                                <?php echo __('Enroll in course'); ?>
                            </a>
                        </div>
                        <div class="jslm_cp-video-banner-btn-wrp jslm_cp-video-banner-btn4">
                            <a target="_blank" href="https://www.youtube.com/watch?v=kY15Kjkb8is" class="jslm_cp-video-banner-btn yellow-bg">
                                <img alt="Arrow" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/play-btn.png">
                                <?php echo __('Custom Fields'); ?>
                            </a>
                        </div>
                    </div>
                    <img class="jslm_cp-video-baner-close-img" alt="addon" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/close.png">
                </div>
            <?php } ?>
            <div class="jslm_count1">
                <div class="jslm_box jslm_box1">
                    <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/course.png">
                    <div class="jslm_text">
                        <div class="jslm_bold-text"><?php echo esc_html(jslearnmanager::$_data['totalcourses']); ?></div>
                        <div class="jslm_nonbold-text"><?php echo __('Total courses', 'learn-manager'); ?></div>
                    </div>
                </div>
                <div class="jslm_box jslm_box2">
                    <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/publish-course.png">
                    <div class="jslm_text">
                        <div class="jslm_bold-text"><?php echo esc_html(jslearnmanager::$_data['publishcourses']); ?></div>
                        <div class="jslm_nonbold-text"><?php echo __('Publish courses', 'learn-manager'); ?></div>
                    </div>
                </div>
                <div class="jslm_box jslm_box3">
                    <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/instructor.png">
                    <div class="jslm_text">
                        <div class="jslm_bold-text"><?php echo esc_html(jslearnmanager::$_data['totalinstructors']); ?></div>
                        <div class="jslm_nonbold-text"><?php echo __('Total instructors', 'learn-manager'); ?></div>
                    </div>
                </div>
                <div class="jslm_box jslm_box4">
                    <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/total-sutudents.png">
                    <div class="jslm_text">
                        <div class="jslm_bold-text"><?php echo esc_html(jslearnmanager::$_data['totalstudents']); ?></div>
                        <div class="jslm_nonbold-text"><?php echo __('Total students', 'learn-manager'); ?></div>
                    </div>
                </div>
            </div>
            <div class="jslm_newestnews">
                <div class="jslm-cp-cnt-left">
                    <div class="jslm-cp-cnt">
                        <span class="jslm_header">
                            <span><?php echo __('Statistics', 'learn-manager'); ?> <small>(<?php echo esc_html(jslearnmanager::$_data['fromdate']); ?> - <?php echo esc_html(jslearnmanager::$_data['curdate']); ?>) </small></span>
                        </span>
                        <div id="jslm_graph-area">
                            <div class="performance-graph" id="stack_chart_horizontal"></div>
                        </div>
                    </div>
                </div>
                <div class="jslm-cp-cnt-right">
                    <div class="jslm-cp-cnt">
                        <div class="jslm-cp-cnt-title">
                            <span class="jslm-cp-cnt-title-txt">
                                <?php echo __('Short Links'); ?>
                            </span>
                        </div>
                        <div id="jslm-wrapper-menus">
                            <a title="Courses" class="jslm-mnu-area" href="admin.php?page=jslm_course">
                                <div class="jslm-mnu-icon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/course.png'; ?>"/></div>
                                <div class="jslm-mnu-text"><span> <?php echo __('Courses' , 'learn-manager'); ?></span></div>
                                <div class="jslm-mnu-arrowicon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/arrows/dark-blue.png'; ?>"/></div>
                            </a>
                            <a title="Instructor" class="jslm-mnu-area" href="admin.php?page=jslm_instructor">
                                <div class="jslm-mnu-icon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/instructor.png'; ?>"/></div>
                                <div class="jslm-mnu-text"><span> <?php echo __('Instructor' , 'learn-manager'); ?></span></div>
                                <div class="jslm-mnu-arrowicon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/arrows/purpel.png'; ?>"/></div>
                            </a>
                            <a title="Students" class="jslm-mnu-area" href="admin.php?page=jslm_student">
                                <div class="jslm-mnu-icon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/students.png'; ?>"/></div>
                                <div class="jslm-mnu-text"><span><?php echo __('Students' , 'learn-manager'); ?></span></div>
                                <div class="jslm-mnu-arrowicon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/arrows/orange.png'; ?>"/></div>
                            </a>
                            <a title="Categories" class="jslm-mnu-area" href="admin.php?page=jslm_category">
                                <div class="jslm-mnu-icon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/categories.png'; ?>"/></div>
                                <div class="jslm-mnu-text"><span><?php echo __('Categories' , 'learn-manager'); ?></span></div>
                                <div class="jslm-mnu-arrowicon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/arrows/dark-blue.png'; ?>"/></div>
                            </a>
                            <a title="Users" class="jslm-mnu-area" href="admin.php?page=jslm_user">
                                <div class="jslm-mnu-icon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/users.png'; ?>"/></div>
                                <div class="jslm-mnu-text"><span><?php echo __('Users' , 'learn-manager'); ?></span></div>
                                <div class="jslm-mnu-arrowicon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/arrows/purpel.png'; ?>"/></div>
                            </a>
                            <a title="Email Templates" class="jslm-mnu-area" href="admin.php?page=jslm_emailtemplate">
                                <div class="jslm-mnu-icon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/email-template.png'; ?>"/></div>
                                <div class="jslm-mnu-text"><span> <?php echo __('Email Templates','learn-manager'); ?></span></div>
                                <div class="jslm-mnu-arrowicon"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/arrows/dark-blue.png'; ?>"/></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="jslm_cp-cnt-sec">
                <div class="jslm_cp-baner-left">
                    <div class="jslm_cp-baner">
                        <div class="jslm_cp-baner-cnt">
                            <div class="jslm_cp-banner-tit-bold">
                                <?php echo __('Ad-Ons List'); ?>
                            </div>
                            <div class="jslm_cp-banner-desc">
                                <?php echo __('It has survived not only five centuries ,'); ?>
                            </div>
                            <div class="jslm_cp-banner-btn-wrp">
                                <a href="admin.php?page=jslm_premiumplugin&jslmslay=addonfeatures" class="jslm_cp-banner-btn yellow-bg">
                                    <?php echo __('Show All Ad-Ons'); ?>
                                    <img alt="All Addons" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/arroq.png">
                                </a>
                            </div>
                        </div>
                        <img class="jslm_cp-baner-img" alt="addon" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/ad-ons-icon.png">
                    </div>
                </div>
                <div class="jslm_cp-baner-center">
                    <div class="jslm_cp-baner">
                        <a class="course-main-wrp" href="admin.php?page=jslm_course">
                            <div class="Configuration-upper">
                                <img alt="star" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/white-course-icoon.png">
                            </div>
                            <div class="Configuration-lower">
                                <?php echo __("All Courses"); ?>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="jslm_cp-baner-right">
                    <div class="jslm_cp-baner">
                        <a class="configuration-main-wrp" href="admin.php?page=jslm_configuration&jslmslay=configurations">
                            <div class="Configuration-upper">
                                <img alt="star" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/white-config-icon.png">
                            </div>
                            <div class="Configuration-lower">
                                <?php echo __("Configurations"); ?>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- latest course start -->
            <div class="jslm_newest-course-portion">
                <div class="jslm_newest-course-portion-header">
                    <span class="jslm_newest-course-portion-text">
                        <?php echo __('Latest Courses','learn-manager'); ?>
                    </span>
                </div>
                <div class="jslm_course-portion">
                    <?php foreach (jslearnmanager::$_data['newest'] as $course) {
                        if ($course->course_status == 1) {
                            $status = "Published";
                            $publish_style = 'background:#00A859;color:#ffffff;padding:5px 10px;';
                        }elseif ($course->course_status == 0) {
                            $status = "Un-Published";
                            $unpublish_style = 'background:#FEA702;color:#ffffff;padding:5px 10px;';
                        }elseif ($course->course_status == -1) {
                            $status = "Rejected";
                            $rejected_style = 'background:#ED3237;color:#ffffff;padding:5px 10px;';
                        }
                       ?>
                        <div class="jslm_course-admin-cp-data">
                            <div class="jslm_cp-course-list-left">
                                <div class="jslm_cp-course-image">
                                    <?php
                                    if($course->file !='' && $course->file != null){
                                        $imageadd = $course->file;
                                    }else{
                                        $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                                    }?>
                                    <img alt="" src="<?php echo esc_url($imageadd); ?>" srcset="" class="avatar avatar-96 photo" height="96" width="96">
                                </div>
                                <div class="jslm_cp-course-cnt">
                                    <div class="jslm_cp-course-info course-name">

                                        <span class="jslm_course-admin-cp-showhide" >
                                            <?php echo __('From');
                                                    echo " : "; ?>
                                        </span>
                                        <a class="jslm_title_heading" href="<?php echo esc_url(admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id)); ?>">
                                            <?php echo esc_html($course->title); ?>
                                        </a>
                                    </div>
                                    <div class="jslm_cp-course-info instructor-name">
                                        <span class="jslm_course-admin-cp-title" >
                                            <?php echo __('Instructor');
                                                    echo " : "; ?>
                                        </span>
                                        <a title="Subject" href="">
                                            <?php
                                                if ($course->instructor_name == ""){
                                                  echo __('Admin','learn-manager');
                                                }else {
                                                  echo esc_html($course->instructor_name);
                                            } ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="jslm_cp-course-crted">
                                <span class="jslm_course-admin-cp-showhide" >
                                    <?php echo __('Category');
                                    echo " : "; ?>
                                </span>
                                <?php echo esc_html($course->category); ?>
                            </div>
                            <div class="jslm_cp-course-crted">
                                <span class="jslm_course-admin-cp-showhide" >
                                    <?php echo __('Created');
                                    echo " : "; ?>
                                </span>
                                <?php echo esc_html($course->created_date); ?>
                            </div>
                            <div class="jslm_cp-course-prorty">
                                <span class="jslm_course-admin-cp-showhide" ><?php echo __('Priority');
                                    echo " : "; ?>
                                </span>
                                <?php if ($course->course_status == 1) { ?>
                                    <span style="<?php echo esc_attr($publish_style) ?>" >
                                        <?php echo esc_html($status) ?>
                                    </span>
                                <?php }elseif ($course->course_status == 0) { ?>
                                    <span style="<?php echo esc_attr($unpublish_style) ?>" >
                                        <?php echo esc_html($status) ?>
                                    </span>
                                <?php }elseif ($course->course_status == -1) { ?>
                                    <span style="<?php echo esc_attr($rejected_style) ?>" >
                                        <?php echo esc_html($status) ?>
                                    </span>
                                <?php }?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- latest course end -->
            <!-- latest students start -->
            <div class="jslm_cp-std-lates-wrp">
                <div class="jslm_cp-std-wrp">
                    <div class="jslm_cp-cnt-title">
                        <span class="jslm_cp-cnt-title-txt"><?php echo __('Latest Students', 'learn-manager'); ?></span>
                    </div>
                    <div class="jslm_cp-std-list">
                        <?php
                        if(count(jslearnmanager::$_data['enrolled']) > 0){
                            foreach(jslearnmanager::$_data['enrolled'] as $student){
                                if ($student->student_status == 1) {
                                    $status = "Enrolled";
                                    $publish_style = 'background:#00A859;color:#ffffff;';
                                }elseif ($student->student_status == 0) {
                                    $status = "Pending";
                                    $unpublish_style = 'background:#FEA702;color:#ffffff;';
                                }elseif ($student->student_status == -1) {
                                    $status = "Rejected";
                                    $rejected_style = 'background:#ED3237;color:#ffffff;';
                                }
                                ?>
                                <div class="jslm_cp-std">
                                    <div class="jslm_cp-std-image">
                                        <?php if($student->image !='' && $student->image != null){
                                            $imageadd = $student->image;
                                        }else{
                                            $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/cp/new-icons/user.png';
                                        }?>
                                        <img src="<?php echo esc_url($imageadd); ?>" srcset="" class="avatar avatar-96 photo" height="96" width="96">
                                    </div>
                                    <div class="jslm_cp-std-cnt">
                                        <div class="jslm_cp-std-row-wep-left">
                                            <div class="jslm_cp-std-row">
                                                <a class="" href="<?php echo esc_url(admin_url('admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid='.$student->student_id)); ?>">
                                                    <span class="jslm_cp-std-type">
                                                        <?php echo esc_html($student->student_name); ?>
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="jslm_cp-std-row">
                                                <span class="jslm_cp-std-tit">
                                                    <?php echo __('Course Title','learn-manager'). ' : ' ; ?>
                                                </span>
                                                <span class="jslm_cp-std-val">
                                                    <?php echo esc_html($student->title); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="jslm_cp-std-row-wep-right">
                                            <div class="jslm_cp-std-row">
                                                <span class="jslm_cp-std-prty-wrp jslm_cp-std-prty">
                                                    <?php if ($student->student_status == 1) { ?>
                                                       <span class="jslm_cp-std-prty" style="<?php echo esc_attr($publish_style) ?>" ><?php echo esc_html($status) ?> </span>
                                                    <?php }elseif ($student->student_status == 0) { ?>
                                                       <span class="jslm_cp-std-prty" style="<?php echo esc_attr($unpublish_style) ?>" ><?php echo esc_html($status) ?> </span>
                                                    <?php }elseif ($student->student_status == -1) { ?>
                                                       <span class="jslm_cp-std-prty" style="<?php echo esc_attr($rejected_style) ?>" ><?php echo esc_html($status) ?> </span>
                                                    <?php } ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }else{ ?>
                            <div class="jslm_no_record">
                                <?php echo __("No Record Found","learn-manager"); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="jslm_cp-std-btn-wrp">
                        <a href="admin.php?page=jslm_student" class="jslm_cp-std-btn" title="<?php echo __('view all Students', 'learn-manager'); ?>">
                            <?php echo __('View All Students','learn-manager'); ?>
                        </a>
                    </div>
                </div>
                <div class="jslm_cp-addon-wrp">
                    <div class="jslm_cp-cnt-title">
                        <span class="jslm_cp-cnt-title-txt"><?php echo __('Add Ons', 'learn-managert'); ?></span>
                    </div>
                    <div class="jslm_cp-addon-list">
                        <?php if ( !in_array('featuredcourse',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Agent','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/courses.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Featured Course', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('WP Learn Manager offers a feature to make your Course featured. When the student enables it, the course will be a Featured.', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-featuredcourse/learn-manager-featuredcourse.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-featuredcourse&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/featured-course/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('paidcourse',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Paid Course','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/paid-courses.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Paid Course', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('Students can take paid courses. Paid Course will help students to learn something extra from their academic courses.', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-paidcourse/learn-manager-paidcourse.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-paidcourse&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/paid-course/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('quiz',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Quiz','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/quiz.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Quiz', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('An instructor will add quizzes to students. Students can give quizzes related to a particular course', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-quiz/learn-manager-quiz.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-quiz&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/quiz/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('retakequiz',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Retake Quiz','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/retake-quiz.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Retake Quiz', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('Students will be able to retake any quizzes to a particular instructor course. Instructor will retake student quizzes', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-retakequiz/learn-manager-retakequiz.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-retakequiz&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/retake-quiz/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('paymentplan',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Payment Plan','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/payment.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Payment Plan', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('That will create how much commission will be given to admin. Admin will receive a commission as per they have committed', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-paymentplan/learn-manager-paymentplan.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-paymentplan&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/payment-plan/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('payouts',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Payouts','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/payout.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Payouts', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('Payouts will help instructors to check their course related earning. Instructors send e-mail to admin for their earnings', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-payouts/learn-manager-payouts.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-payouts&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/payouts/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('message',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Messages','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/messages.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Messages', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('Students and Instructors can message to each other. Enrolled Students can message to every course related teacher to discuss any query', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-message/learn-manager-message.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-messages&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/messages/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('awards',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Awards','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/awards.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Awards', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('Awards are given to students and instructors according to awards types.Admin will create awards for students and instructors', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-awards/learn-manager-awards.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-awards&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/awards/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('coursereview',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Course Review','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/review.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Course Review', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('This will help students to submit their reviews about individual courses. Admin sets permission for submitting reviews', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-coursereview/learn-manager-coursereview.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-coursereview&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/course-review/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('reports',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Reports','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/report.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Reports', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('JS Learn Manager offers multiple reports for Students and Instructors. Admin can check overall reports of students/instructors', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-reports/learn-manager-reports.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-reports&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/reports/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('sociallogin',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Social Login','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/social-login.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Social Login', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('Students can login from their social media accounts. They can create a new account or use social media accounts', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-sociallogin/learn-manager-sociallogin.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-sociallogin&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/social-login/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('socialshare',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Social Share','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/social-share.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Social Share', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('Students are able to share their courses on social media. Students can take courses and share course on social media sites', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-socialshare/learn-manager-socialshare.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-socialshare&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/social-share/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('rss',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('RSS','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/rss.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('RSS', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('Real Simple Syndication (RSS) to set feeds for any course. Admin manipulates RSS settings about any course', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-rss/learn-manager-rss.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-rss&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/rss/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ( !in_array('themes',jslearnmanager::$_active_addons)) { ?>
                            <div class="jslm_cp-addon">
                                <div class="jslm_cp-addon-image">
                                    <img alt="<?php echo __('Plugin Colors','learn-manager'); ?>" class="js-cp-addon-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/addons/themes.png"/>
                                </div>
                                <div class="jslm_cp-addon-cnt">
                                    <div class="jslm_cp-addon-tit">
                                        <?php echo __('Plugin Colors', 'learn-manager'); ?>
                                    </div>
                                    <div class="jslm_cp-addon-desc">
                                        <?php echo __('Get multiple themes with beautiful colors scheme to make your site more beautiful and eye catchy by just one click', 'learn-manager'); ?>
                                    </div>
                                </div>
                                <?php $plugininfo = checkLmsPluginInfo('learn-manager-plugincolors/learn-manager-plugincolors.php');
                                if($plugininfo['availability'] == "1"){
                                    $text = $plugininfo['text'];
                                    $url = "plugins.php?s=learn-manager-plugincolors&plugin_status=inactive";
                                }elseif($plugininfo['availability'] == "0"){
                                    $text = $plugininfo['text'];
                                    $url = "https://wplearnmanager.com/product/themes-colors/";
                                } ?>
                                <a href="<?php echo esc_url($url); ?>" class="jslm_cp-addon-btn" title="<?php $text; ?>">
                                    <?php echo esc_html($text); ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- other products -->
            <div class="js-other-products-wrp">
                <div class="js-other-product-title">
                    <?php echo __("Other Products","learn-manager"); ?>
                </div>
                <div class="js-other-products-detail">
                    <div class="js-other-products-image">
                        <img class="js-img" title="<?php echo __("JS Help Desk","learn-manager"); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/otherproducts/help-desk.png">
                        <div class="js-other-products-bottom">
                            <div class="js-product-title"><?php echo __("JS Help Desk","learn-manager"); ?></div>
                            <div class="js-product-bottom-btn">
                                <span class="js-product-view-btn">
                                    <a title="<?php echo __("Visit site","learn-manager"); ?>" href="https://jshelpdesk.com" target="_blank"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/otherproducts/new-tab.png"></a>
                                </span>
                                <span class="js-product-install-btn">
                                    <?php $plugininfo = checkLmsPluginInfo('js-support-ticket/js-support-ticket.php'); ?>
                                    <a title="<?php echo __("Install JS Help Desk Plugin","learn-manager"); ?>" class="wp-learn-manager-btn-color <?php echo esc_attr($plugininfo['class']); ?>" data-slug="js-support-ticket" <?php echo esc_html($plugininfo['disabled']); ?>><?php echo esc_html(__($plugininfo['text'],"learn-manager")) ?></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="js-other-products-image">
                        <img class="js-img" title="<?php echo __("WP Vehicle Manager","learn-manager"); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/otherproducts/vehicle-manager.png">
                        <div class="js-other-products-bottom">
                            <div class="js-product-title"><?php echo __("WP Vehicle Manager","learn-manager"); ?></div>
                            <div class="js-product-bottom-btn">
                                <span class="js-product-view-btn">
                                    <a href="https://wpvehiclemanager.com"  target="_blank" title="<?php echo __("Visit site","learn-manager"); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/otherproducts/new-tab.png"></a>
                                </span>
                                <span class="js-product-install-btn">
                                    <?php $plugininfo = checkLmsPluginInfo('js-vehicle-manager/js-vehicle-manager.php'); ?>
                                    <a title="<?php echo __("Install WP Vehicle Manager Plugin","learn-manager"); ?>" class="wp-vehicle-manager-btn-color <?php echo esc_attr($plugininfo['class']); ?>" data-slug="js-vehicle-manager" <?php echo esc_html($plugininfo['disabled']); ?>>
                                        <?php echo esc_html(__($plugininfo['text'],"learn-manager")) ?>
                                        <?php ?>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="js-other-products-image">
                        <img class="js-img" title="<?php echo __("JS Job Manager","learn-manager"); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/otherproducts/job.png">
                        <div class="js-other-products-bottom">
                            <div class="js-product-title"><?php echo __("JS Job Manager","learn-manager"); ?></div>
                            <div class="js-product-bottom-btn">
                                <span class="js-product-view-btn">
                                    <a href="https://joomsky.com/products/js-jobs-pro-wp.html"  target="_blank" title="<?php echo __("Visit site","learn-manager"); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/cp/new-icons/otherproducts/new-tab.png"></a>
                                </span>
                                <span class="js-product-install-btn">
                                    <?php $plugininfo = checkLmsPluginInfo('js-jobs/js-jobs.php'); ?>
                                    <a title="<?php echo __("Install JS Job Manager Plugin","learn-manager"); ?>" class="js-jobs-manager-btn-color <?php echo esc_attr($plugininfo['class']); ?>" data-slug="js-jobs" <?php echo esc_html($plugininfo['disabled']); ?>>
                                        <?php echo esc_html(__($plugininfo['text'],"learn-manager")) ?>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- latest students end -->
            <div class="jslm_review">
                <div class="jslm_upper">
                    <div class="jslm_imgs">
                        <img class="jslm_reviewpic" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/review.png">
                        <img class="jslm_reviewpic2" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/corner-1.png">
                    </div>
                    <div class="jslm_text">
                        <div class="jslm_simple-text">
                            <span class="jslm_nobold"><?php echo __('We\'d love to hear from ', 'learn-manager'); ?></span>
                            <span class="jslm_bold"><?php echo __('You', 'learn-manager'); ?>.</span>
                            <span class="jslm_nobold"><?php echo __('Please write appreciated review at', 'learn-manager'); ?></span>
                        </div>
                        <a href="https://wordpress.org/support/view/plugin-reviews/learn-manager" target="_blank"><?php echo __('Word Press Extension Directory', 'learn-manager'); ?><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/arrow2.png"></a>
                    </div>
                    <div class="jslm_right">
                        <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/star.png">
                    </div>
                </div>
                <div class="jslm_lower">
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    var cookielist = document.cookie.split(';');
    for (var i=0; i<cookielist.length; i++) {
        if (cookielist[i].trim() == "jsst_collapse_video-banner=1") {
            jQuery("div.jslm_cp-video-baner").addClass("jslm_cp-video-baner-hide");
            break;
        }
    }
    jQuery(document).ready(function () {
        jQuery("span.jslm_dashboard-icon").find('span.download').hover(function(){
            jQuery(this).find('span').show();
        }, function(){
            jQuery(this).find('span').hide();
        });
    });
    // video banner
    jQuery("img.jslm_cp-video-baner-close-img").click(function(){
        jQuery('.jslm_cp-video-baner').fadeOut('slow');
        jQuery.post(ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "jslearnmanager",task: "hidePopupFromAdmin"});
    });
</script>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(document).on('click','a.js-btn-install-now',function(){
            jQuery(this).attr('disabled',true);
            jQuery(this).html('Installing...!');
            jQuery(this).removeClass('js-btn-install-now');
            var pluginslug = jQuery(this).attr("data-slug");
            var buttonclass = jQuery(this).attr("class");
            jQuery(this).addClass('js-installing-effect');
            if(pluginslug != ''){
                jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'jslearnmanager', task: 'installPluginFromAjax', pluginslug:pluginslug}, function (data) {
                    if(data == 1){
                        jQuery("span.js-product-install-btn a."+buttonclass).attr('disabled',false);
                        jQuery("span.js-product-install-btn a."+buttonclass).html("Active Now");
                        jQuery("span.js-product-install-btn a."+buttonclass).addClass("js-btn-active-now js-btn-green");
                        jQuery("span.js-product-install-btn a."+buttonclass).removeClass("js-installing-effect");
                    }else{
                        jQuery("span.js-product-install-btn a."+buttonclass).attr('disabled',false);
                        jQuery("span.js-product-install-btn a."+buttonclass).html("Please try again");
                        jQuery("span.js-product-install-btn a."+buttonclass).addClass("js-btn-install-now");
                        jQuery("span.js-product-install-btn a."+buttonclass).removeClass("js-installing-effect");
                    }
                });
            }
        });

        jQuery(document).on('click','a.js-btn-active-now',function(){
            jQuery(this).attr('disabled',true);
            jQuery(this).html('Activating.....!');
            jQuery(this).removeClass('js-btn-active-now');
            var pluginslug = jQuery(this).attr("data-slug");
            var buttonclass = jQuery(this).attr("class");
            if(pluginslug != ''){
                jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'jslearnmanager', task: 'activatePluginFromAjax', pluginslug:pluginslug}, function (data) {
                    if(data == 1){
                        jQuery("a[data-slug="+pluginslug+"]").html("Activated");
                        jQuery("a[data-slug="+pluginslug+"]").addClass("js-btn-activated");
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
