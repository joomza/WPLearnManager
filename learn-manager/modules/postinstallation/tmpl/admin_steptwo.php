<?php
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'learn-manager'))
                    , (object) array('id' => 0, 'text' => __('No', 'learn-manager')));
// wp_enqueue_script('jslearnmanagercommonjs', JSLEARNMANAGER_PLUGIN_URL .'includes/js/radio.js');
if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jslearnmanageradmin-wrapper" class="post-installation">
    <div class="js-admin-title-installtion">
        <span class="jslm_heading"><?php echo __('Learn Manager Settings','learn-manager'); ?></span>
        <div class="close-button-bottom">
            <a href="<?php echo admin_url('admin.php?page=jslearnmanager'); ?>" class="close-button">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/close-icon.png" />
            </a>
        </div>
    </div>
    <div class="post-installtion-content-wrapper">
        <div class="post-installtion-content-header">
            <ul class="update-header-img step-1">
                <li class="header-parts first-part">
                    <a href="<?php echo admin_url("admin.php?page=jslm_postinstallation&jslmslay=stepone"); ?>" title="link" class="tab_icon">
                        <img class="start" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/general-settings.png" />
                        <span class="text"><?php echo __('General', 'learn-manager'); ?></span>
                    </a>
                </li>
                <li class="header-parts second-part active">
                    <a href="<?php echo admin_url("admin.php?page=jslm_postinstallation&jslmslay=steptwo"); ?>" title="link" class="tab_icon">
                        <img class="start" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/user.png" />
                        <span class="text"><?php echo __('Instructor', 'learn-manager'); ?></span>
                    </a>
                </li>
                <li class="header-parts third-part">
                    <a href="<?php echo admin_url("admin.php?page=jslm_postinstallation&jslmslay=stepthree"); ?>" title="link" class="tab_icon">
                        <img class="start" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/course.png" />
                        <span class="text"><?php echo __('Student', 'learn-manager'); ?></span>
                    </a>
                </li>
                <li class="header-parts fourth-part">
                   <a href="<?php echo admin_url("admin.php?page=jslm_postinstallation&jslmslay=stepfour"); ?>" title="link" class="tab_icon">
                       <img class="start" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/sample-data.png" />
                        <span class="text"><?php echo __('Sample data', 'learn-manager'); ?></span>
                    </a>
                </li>
                <li class="header-parts fifth-part">
                    <a href="#" title="link" class="tab_icon">
                       <img class="start" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/complete.png" />
                        <span class="text"><?php echo __('Complete', 'learn-manager'); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="post-installtion-content_wrapper_right">
            <span class="heading-post-ins"><?php echo __('Instructor Configuration','learn-manager');?></span>
            <div class="post-installtion-content">
                <form id="jslearnmanager-form-ins" method="post" action="<?php echo admin_url("admin.php?page=jslm_postinstallation&task=save&action=jslmstask"); ?>">
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Enable Instructor Area','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('disable_instructor', $yesno,jslearnmanager::$_data[0]['disable_instructor'],'',array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('If no then front end instructor area is not accessible','learn-manager');?> </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Allow user register as instructor','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('showinstructorlink', $yesno,jslearnmanager::$_data[0]['showinstructorlink'],'',array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('Effects on user registration','learn-manager');?> </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Instructor default role','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('instructor_defaultgroup', $userroles,jslearnmanager::$_data[0]['instructor_defaultgroup'],'',array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('This role will auto assign to new instructor','learn-manager');?> </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Instructor can view student area','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('instructorviewstudent_js_controlpanel', $yesno,jslearnmanager::$_data[0]['instructorviewstudent_js_controlpanel'],'',array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Instructor can add course','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('allow_user_to_add_course', $yesno,jslearnmanager::$_data[0]['allow_user_to_add_course'],'',array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Course auto approve','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('course_auto_approve', $yesno,jslearnmanager::$_data[0]['course_auto_approve'],'',array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                    <?php if(in_array('message', jslearnmanager::$_active_addons)){ ?>
                        <div class="pic-config">
                            <div class="title">
                                <?php echo __('Can send message to student','learn-manager');?>:
                            </div>
                            <div class="field">
                                <?php echo wp_kses(JSLEARNMANAGERformfield::select('instructorsend_message', $yesno,jslearnmanager::$_data[0]['instructorsend_message'],'',array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="pic-button-part">
                        <a class="next-step" href="#"  onclick="document.getElementById('jslearnmanager-form-ins').submit();" >
                            <?php echo __('Next','learn-manager'); ?>
                             <i class=" fa fa-long-arrow-right"></i>
                        </a>
                        <a class="back" href="<?php echo admin_url('admin.php?page=jslm_postinstallation&jslmslay=stepone'); ?>">
                             <i class=" fa fa-long-arrow-left"></i>
                            <?php echo __('Back','learn-manager'); ?>
                        </a>
                    </div>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'postinstallation_save'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('step', 2), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </form>
            </div>
        </div>
    </div>
</div>
