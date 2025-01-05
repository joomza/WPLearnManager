<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <?php 
        $msgkey = JSLEARNMANAGERincluder::getJSModel('emailtemplate')->getMessagekey();
        JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
        ?>
     <div id="jslearnmanageradmin-wrapper-top">
            <div id="jslearnmanageradmin-wrapper-top-left">
                <div id="jslearnmanageradmin-breadcrunbs">
                    <ul>
                        <li>
                            <a href="admin.php?page=jslearnmanager">
                                <?php echo __('Dashboard', 'learn-manager'); ?>
                            </a>
                        </li>
                        <li><?php echo __('Email Templates','js-support-ticket'); ?></li>
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
           <span class="jslm_heading-dashboard"><?php echo __('Email Templates', 'learn-manager'); ?></span>
       </div>            
    <div id="jslms-data-wrp">
        
        <form method="post" class="jslm_emailtemplateform" action="<?php echo admin_url("?page=jslm_emailtemplate&task=saveemailtemplate"); ?>">
            <div class="jslm_js-email-menu">
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'nw-co-a') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=nw-co-a'); ?>"><?php echo __('New Course Admin', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'nw-co') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=nw-co'); ?>"><?php echo __('New Course', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'co-st') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=co-st'); ?>"><?php echo __('Course Status', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'de-co') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=de-co'); ?>"><?php echo __('Delete Course', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'co-en-st') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=co-en-st'); ?>"><?php echo __('New Enrollment Student', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'co-en-in') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=co-en-in'); ?>"><?php echo __('New Enrollment Instructor', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'co-en-ad') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=co-en-ad'); ?>"><?php echo __('New Enrollment Admin', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'nw-u') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=nw-u'); ?>"><?php echo __('Register New User', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'nw-u-a') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=nw-u-a'); ?>"><?php echo __('New User Admin', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'co-al') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=co-al'); ?>"><?php echo __('Course Alert', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'fe-co-st') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=fe-co-st'); ?>"><?php echo __('Featured Course Status', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 't-a-fr') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=t-a-fr'); ?>"><?php echo __('Tell A Friend', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'pa-em-ad') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=pa-em-ad'); ?>"><?php echo __('Payout Email Admin', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'pa-em-in') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=pa-em-in'); ?>"><?php echo __('Payout Email Instructor', 'learn-manager'); ?></a></span>
                <span class="jslm_js-email-menu-link <?php if (jslearnmanager::$_data[1] == 'mg-t-sr') echo 'jslm_selected'; ?>"><a class="jslm_js-email-link" href="<?php echo admin_url('admin.php?page=jslm_emailtemplate&for=mg-t-sr'); ?>"><?php echo __('Message To Sender', 'learn-manager'); ?></a></span>
            </div>
            <div class="jslm_js-email-body">
                <div class="jslm_js-form-wrapper">
                    <div class="jslm_a-js-form-title"><?php echo __('Subject', 'learn-manager'); ?></div>
                    <div class="jslm_a-js-form-field"><?php echo wp_kses(JSLEARNMANAGERformfield::text('subject', jslearnmanager::$_data[0]->subject, array('class' => 'jslm_inputbox', 'style' => 'width:100%;')), JSLEARNMANAGER_ALLOWED_TAGS) ?></div>
                </div>
                <div class="jslm_js-form-wrapper">
                    <div class="jslm_a-js-form-title"><?php echo __('Body', 'learn-manager'); ?></div>
                    <div class="jslm_a-js-form-field"><?php echo wp_editor(jslearnmanager::$_data[0]->body, 'body', array('media_buttons' => false)); ?></div>
                </div>
                <div class="jslm_js-email-parameters">
                    <span class="jslm_js-email-parameter-heading"><?php echo __('Parameters', 'learn-manager') ?></span>
                        
                    <?php if (jslearnmanager::$_data[1] == 'nw-co-a'){ ?> 
                        <span class="jslm_js-email-paramater">{INSTRUCTOR_NAME}:  <?php echo __('Instructor name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_TITLE}:  <?php echo __('Course title', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_STATUS}:  <?php echo __('Course status', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_CATEGORY}:  <?php echo __('Course category', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{ACCESS_TYPE}:  <?php echo __('Access type', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{PUBLISHED_STATUS}:  <?php echo __('Publish/Unpublish status', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_LINK}:  <?php echo __('Course link', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'nw-co'){ ?> 
                        <span class="jslm_js-email-paramater">{INSTRUCTOR_NAME}:  <?php echo __('Instructor name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_TITLE}:  <?php echo __('Course title', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_STATUS}:  <?php echo __('Course status', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_CATEGORY}:  <?php echo __('Course category', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{ACCESS_TYPE}:  <?php echo __('Access type', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_LINK}:  <?php echo __('Course link', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'co-st'){ ?> 
                        <span class="jslm_js-email-paramater">{INSTRUCTOR_NAME}:  <?php echo __('Instructor name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_TITLE}:  <?php echo __('Course title', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_LINK}:  <?php echo __('Course link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{ACCESS_TYPE}:  <?php echo __('Access type', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{PUBLISHED_STATUS}:  <?php echo __('Publish/Unpublish status', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_STATUS}:  <?php echo __('Course approve or reject', 'learn-manager').'('.__('Featured','learn-manager') . ')'; ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'de-co'){ ?> 
                        <span class="jslm_js-email-paramater">{INSTRUCTOR_NAME}:  <?php echo __('Instructor name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_TITLE}:  <?php echo __('Course title', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'co-en-st'){ ?> 
                        <span class="jslm_js-email-paramater">{COURSE_TITLE}:  <?php echo __('Course title', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{STUDENT_NAME}:  <?php echo __('Student name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_LINK}:  <?php echo __('Course link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{INSTRUCTOR_NAME}:  <?php echo __('Instructor name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{ACCESS_TYPE}:  <?php echo __('Course access type', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_PRICE}:  <?php echo __('Course price', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'co-en-in'){ ?> 
                        <span class="jslm_js-email-paramater">{INSTRUCTOR_NAME}:  <?php echo __('Instructor name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{STUDENT_NAME}:  <?php echo __('Student name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{STUDENT_PROFILE}:  <?php echo __('Student profile ', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_LINK}:  <?php echo __('Course link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{ACCESS_TYPE}:  <?php echo __('Course access type', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_PRICE}:  <?php echo __('Course price', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'co-en-ad'){ ?> 
                        <span class="jslm_js-email-paramater">{STUDENT_NAME}:  <?php echo __('Student name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_TITLE}:  <?php echo __('Course title', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_LINK}:  <?php echo __('Course link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{STUDENT_PROFILE}:  <?php echo __('Student profile', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{ACCESS_TYPE}:  <?php echo __('Course access type', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_PRICE}:  <?php echo __('Course price', 'learn-manager'); ?></span>    
                    <?php }elseif (jslearnmanager::$_data[1] == 'nw-u'){ ?> 
                        <span class="jslm_js-email-paramater">{USER_NAME}:  <?php echo __('User name(Instructor/Student)', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{MY_DASHBOARD_LINK}:  <?php echo __('My dashboard link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{USER_ROLE}:  <?php echo __('User Role(Instructor/Student)', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'nw-u-a'){ ?> 
                        <span class="jslm_js-email-paramater">{USER_NAME}:  <?php echo __('User name(Instructor/Student)', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{MY_DASHBOARD_LINK}:  <?php echo __('User dashboard link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{USER_ROLE}:  <?php echo __('User Role(Instructor/Student)', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'co-al'){ ?> 
                        <span class="jslm_js-email-paramater">{COURSE_TITLE}:  <?php echo __('Course title', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_CATEGORY}:  <?php echo __('Course category', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_LINK}:  <?php echo __('Course link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{ACCESS_TYPE}:  <?php echo __('Access type', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'fe-co-st'){ ?> 
                        <span class="jslm_js-email-paramater">{COURSE_TITLE}:  <?php echo __('Course title', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_LINK}:  <?php echo __('Course link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{ACCESS_TYPE}:  <?php echo __('Access type', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_STATUS}:  <?php echo __('Course status', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 't-a-fr'){ ?> 
                        <span class="jslm_js-email-paramater">{SENDER_NAME}:  <?php echo __('Sender name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{SITE_NAME}:  <?php echo __('Your website name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_TITLE}:  <?php echo __('Course title', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{COURSE_LINK}:  <?php echo __('Course link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{SENDER_MESSAGE}:  <?php echo __('Sender message', 'learn-manager'); ?></span>
                    <?php }elseif (jslearnmanager::$_data[1] == 'pa-em-in'){ ?> 
                        <span class="jslm_js-email-paramater">{INSTRUCTOR_NAME}:  <?php echo __('Instructor name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{PAYOUT_AMOUNT}:  <?php echo __('Amount', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{PAYOUT_DATE}:  <?php echo __('Payout date', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{PAYOUT_LINK}:  <?php echo __('Link', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{PAYOUT_FILE_LINK}:  <?php echo __('Attachment', 'learn-manager'); ?></span>    
                    <?php }elseif (jslearnmanager::$_data[1] == 'pa-em-ad'){ ?> 
                        <span class="jslm_js-email-paramater">{INSTRUCTOR_NAME}:  <?php echo __('Instructor name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{PAYOUT_AMOUNT}:  <?php echo __('Amount', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{PAYOUT_DATE}:  <?php echo __('Payout date', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{PAYOUT_LINK}:  <?php echo __('Link', 'learn-manager'); ?></span>    
                        <span class="jslm_js-email-paramater">{PAYOUT_FILE_LINK}:  <?php echo __('Attachment', 'learn-manager'); ?></span>    
                    <?php }elseif (jslearnmanager::$_data[1] == 'mg-t-sr'){ ?> 
                        <span class="jslm_js-email-paramater">{RECEIVER_NAME}:  <?php echo __('Receiver name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{SENDER_NAME}:  <?php echo __('Sender name', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{SENDER_EMAIL_ADDRESS}:  <?php echo __('Sender email address', 'learn-manager'); ?></span>
                        <span class="jslm_js-email-paramater">{MESSAGE}:  <?php echo __('Message from sender', 'learn-manager'); ?></span>
                    <?php } ?>
                </div>
                <div class="jslm_js-form-button">
                    <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save','learn-manager') .' '. __('Email Template', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </div>          
            </div>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', jslearnmanager::$_data[0]->id), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('created', jslearnmanager::$_data[0]->created), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('templatefor', jslearnmanager::$_data[0]->templatefor), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('for', jslearnmanager::$_data[1]), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'emailtemplate_saveemailtemplate'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </form>
       </div>
    </div>
</div>
