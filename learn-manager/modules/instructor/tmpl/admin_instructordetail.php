<?php
if (!defined('ABSPATH'))die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('instructor')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
if(jslearnmanager::$_config['date_format'] == 'd-m-Y' ){
    $date_format_string = 'd/F/Y';
}elseif(jslearnmanager::$_config['date_format'] == 'm/d/Y'){
    $date_format_string = 'F/d/Y';
}elseif(jslearnmanager::$_config['date_format'] == 'Y-m-d'){
    $date_format_string = 'Y/F/d';
}
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
                        <li><?php echo __('Instructor Details','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Instructor Details', 'learn-manager'); ?></span>
        </div>            
   <div id="jslms-data-wrp">
        <hr class="listing-hr" />
    <?php if (!empty(jslearnmanager::$_data['profile'])) {
            $profile = jslearnmanager::$_data['profile'];?>
            <div id="instructor_<?php echo esc_attr($profile->instructor_id); ?>" class="instructor-container instructor-container-margin-bottom js-col-lg-12 js-col-md-12 no-padding">
                <div id="item-data" class="item-data item-data-resume js-row no-margin">
                    <div class="item-icon js_circle">
                        <div class="profile">
                            <a class="js-anchor" href="<?php echo admin_url('admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid='.$profile->instructor_id); ?>">
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
                              <span class="role-<?php echo ($profile->user_role_id == 1) ? 'instructor' : 'student'; ?>">
                                  <?php
                                  if($profile->user_role_id == 2){
                                      echo __('Instructor','learn-manager');
                                  }elseif($profile->user_role_id == 3){
                                      echo __('Student','learn-manager');
                                  } ?>
                              </span>
                          </span>
                      </div>
                      <?php if($profile->user_role_id == 2){ ?>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                          <span class="heading"><?php echo __('Email', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($profile->email);?></span>
                        </div>
                      <?php }?>
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
                        <span class="heading"><?php echo __('Instructor ID', 'learn-manager') . ': '; ?></span><span class="item-action-text"><?php echo esc_html($profile->instructor_id); ?></span>
                    </div>
                </div>
                <div class="jslm_user-stats">
                    <?php if($profile->user_role_id == 2){ ?>
                        <div class="stat-parts">
                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/courses.png">
                            <span class="number"><?php echo (jslearnmanager::$_data[0]['totalcourses']) + (jslearnmanager::$_data[0]['unpublishcourses']); ?></span>
                            <span class="text"><?php echo __('Total Courses','learn-manager')?></span>
                        </div>
                        <div class="stat-parts">
                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/courses.png">
                            <span class="number"><?php echo esc_html(jslearnmanager::$_data[0]['totalcourses']); ?></span>
                            <span class="text"><?php echo __('Publish Courses','learn-manager')?></span>
                        </div>
                        <div class="stat-parts">
                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/instructor_students.png">
                            <span class="number"><?php echo  esc_html(jslearnmanager::$_data[0]['totalstudents']); ?></span>
                            <span class="text"><?php echo __('Students','learn-manager')?></span>
                        </div>
                        <?php do_action("jslm_coursereview_rating_for_instructor_profile",1); // Admin ?>
                    <?php }?>
                    <?php do_action("jslm_awards_show_userdetail_awards_for_admin"); ?>
                </div>
                <?php if(has_action('jslm_addons_admin_button_for_instructor_detail')){ ?>
                  <div id="item-actions" class="item-actions js-row no-margin">
                    <div class="item-values js-col-lg-7 js-col-md-7 js-col-xs-12 no-padding">
                      <?php do_action("jslm_addons_admin_button_for_instructor_detail",JSLEARNMANAGERrequest::getVar('jslearnmanagerid')); ?>
                    </div>
                  </div>
                <?php } ?>
            </div>
            <div class="jslm_course_wrapper">
                <div class="jslm_heading">
                   <h4 class="jslm_heading_style">Instructor's Courses </h4>
                </div>
                 <?php if (!empty(jslearnmanager::$_data['mycourses'])){
                    foreach (jslearnmanager::$_data['mycourses'] AS $course) {
                    /*echo "<pre>"; print_r($course);
                    die();*/
                 ?>
                <div id="course<?php echo esc_attr($course->course_id);?>" class="course-container js-col-lg-12 js-col-md-12 no-padding">
                    <div id="item-data" class="item-data js-row no-margin">
                        <?php
                        if($course->file == ''){
                          $course->file = $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                        }
                        ?>
                        <div class="item-icon"><a href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id);?>"><img src="<?php echo esc_url($course->file); ?>" /></a></div>
                        <div class="item-details">
                            <div class="item-title jslm_course_title js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding">
                                <span class="value"><a href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id);?>"><?php echo esc_html($course->title); ?></a></span>
                            </div>
                            <div class="item-title jslm_course_date item-values js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding text-align-right">
                                <span class="value"><span class="heading padding-right">Created:</span><span class="item-action-text"><?php echo esc_html(date_i18n('d-M-Y', strtotime($course->created_at))); ?></span></span>
                            </div>
                            <div class="js-col-lg-1 jslm_course_price js-col-md-1 js-col-xs-12 no-padding">
                            <?php if(!isset($course->price) || $course->price == 0){
                                $price = "Free";
                            }else{
                              $price =  $course->symbol.$course->price;
                            } ?>
                                <span class="value right price"><?php echo esc_html($price); ?></span>
                            </div>
                            <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                                <span class="heading">Instructor:</span><span class="value"><?php echo esc_html($course->instructor_name); ?></span>
                            </div>
                            <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                                <span class="heading">Posted Date:</span><span class="value"><?php echo esc_html(__($course->start_date,'learn-manager')); ?></span>
                            </div>
                            <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                                <span class="heading">Category:</span><span class="value"><?php echo esc_html(__($course->category,'learn-manager')); ?></span>
                            </div>
                              <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                                <span class="heading">Lesson:</span><span class="value"><?php echo esc_html(__($course->total_lessons,'learn-manager')); ?></span>
                            </div>

                          <?php
                          $print = true;
                          $curdate = date_i18n('Y-m-d');
                          if($course->course_status == 1){
                            $publishstatus = __('Publish','learn-manager');
                            $publishstyle = 'background:#00A859;color:#ffffff;border:unset;';
                          }else{
                            $publishstatus = __('Unpublish','learn-manager');
                            $publishstyle = 'background:#ffd900;color:#00000;border:unset;';
                          }

                          ?>
                          <span class="bigupper-coursestatus" style="padding:4px 8px;<?php echo esc_attr($publishstyle); ?>"><?php echo esc_html($publishstatus); ?></span>
                        </div>
                    </div>
                    <div id="for_ajax_only_<?php echo esc_attr($course->course_id); ?>">
                      <div id="item-actions" class="item-actions js-row no-margin">
                          <div class="item-text-block js-col-lg-4 js-col-md-4 js-col-xs-12 no-padding">
                              <span class="heading"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/students.png"></span><span class="item-action-text"><?php echo esc_html($course->total_students); ?></span>
                              <span class="heading"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/lesson.png"></span><span class="item-action-text"><?php echo esc_html($course->total_lessons); ?></span>
                          </div>
                          <div class="item-values js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding">
                              <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_course&action=jslmstask&task=remove&jslearnmanagerid='.$course->course_id.'&instructorid='.JSLEARNMANAGERrequest::getVar('jslearnmanagerid')),'delete-course')); ?>&callfrom=1" onclick="return confirm('<?php echo __('Are you sure to delete','learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete-icon.png" alt="del" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>"/>&nbsp;&nbsp;<?php echo __('Delete', 'learn-manager'); ?></a>
                              <a class="js-action-link button" href="<?php echo esc_url(admin_url('admin.php?page=jslm_course&jslmslay=formcourse&jslearnmanagerid='.$course->course_id));?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/edit.png" /><?php echo __('Edit Course', 'learn-manager') ?></a>
                          </div>
                      </div>
                    </div>
                </div>
        <?php } ?>
         </div>
         <?php if(isset(jslearnmanager::$_data['mycoursespagination'])){
                  echo '<div class="tablenav"><div class="tablenav-pages">' . esc_html(jslearnmanager::$_data['mycoursespagination']) . '</div></div>';
                } ?>
    <?php } ?>
    <?php
    } else {
        $msg = __('No record found','learn-manager');
        echo JSLEARNMANAGERlayout::getNoRecordFound($msg);
    }
    ?>
</div>
</div>
</div>
