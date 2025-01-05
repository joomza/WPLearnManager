<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

wp_enqueue_script('jquery-ui-tabs');

// Lists objecs
$date_format = array((object) array('id' => 'd-m-Y', 'text' => __('dd-mm-yyyy', 'learn-manager')), (object) array('id' => 'm/d/Y', 'text' => __('mm/dd/yyyy', 'learn-manager')), (object) array('id' => 'Y-m-d', 'text' => __('yyyy-mm-dd', 'learn-manager')));
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'learn-manager')), (object) array('id' => 0, 'text' => __('No', 'learn-manager')));
$showhide = array((object) array('id' => 1, 'text' => __('Show', 'learn-manager')), (object) array('id' => 0, 'text' => __('Hide', 'learn-manager')));
$social = array(1 => '');
$captchalist = array((object) array('id' => 1, 'text' => __('Google Captcha', 'learn-manager')), (object) array('id' => 2, 'text' => __('Learn Manager Captcha', 'learn-manager')));
$captchacalculation = array((object) array('id' => 0, 'text' => __('Any', 'learn-manager')), (object) array('id' => 1, 'text' => __('Addition', 'learn-manager')), (object) array('id' => 2, 'text' => __('Subtraction', 'learn-manager')));
$captchaop = array((object) array('id' => 2, 'text' => 2), (object) array('id' => 3, 'text' => 3));
$searchcoursetag = array((object) array('id' => 1, 'text' => __('Top left', 'learn-manager')), (object) array('id' => 2, 'text' => __('Top right', 'learn-manager')), (object) array('id' => 3, 'text' => __('Middle left', 'learn-manager')), (object) array('id' => 4, 'text' => __('Middle right', 'learn-manager')), (object) array('id' => 5, 'text' => __('Bottom left', 'learn-manager')), (object) array('id' => 6, 'text' => __('Bottom right', 'learn-manager')));
$awardcriteria = array((object) array('id' => 1, 'text' => __('Show Maximum rule value award', 'learn-manager')), (object) array('id' => 0, 'text' => __('Show all awards awarded', 'learn-manager')));
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
$wpdir = wp_upload_dir();
$data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
$theme = wp_get_theme();
$theme_chk = 0;
if($theme == 'JS Learn Manager'){
    $theme_chk = 1;
}
?>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu" class="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <?php
            $msgkey = JSLEARNMANAGERincluder::getJSModel('configuration')->getMessagekey();
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
                        <li><?php echo __(' General','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __(' Configurations', 'learn-manager'); ?></span>
         </div>            
        <div id="jslearnmanager-admin-wrapper" class="jslmsadmin-wrapper-white-bg jslmsadmin-config-main-wrapper">
            <form id="jslearnmanager-form" class="jslearnmanager-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_configuration&task=saveconfiguration") ?>" enctype="multipart/form-data">
                <div class="jslmsadmin-configurations-toggle">
                    <img alt="<?php echo __('Help','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/left-icons/menu.png" />
                    <span class="jslm_text">Select Configuration </span>
                </div>
                <div class="jslmsadmin-left-menu jslearnmanageradmin-leftmenu">
                    <?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigSideMenu(); ?>
                </div>
                <div class="jslmsadmin-right-content">
                    <div id="jslm_tabs" class="jslm_tabs">
                        <ul class="jslm_tabs-list">
                            <li class="jslm_tabs-list-item ui-tabs-active"><a href="#jslm_site_setting"><?php echo __('Site Settings', 'learn-manager'); ?></a></li>
                            <li class="jslm_tabs-list-item"><a href="#jslm_visitor_setting"><?php echo __('Visitor Settings', 'learn-manager'); ?></a></li>
                            <li class="jslm_tabs-list-item"><a href="#jslm_list_course"><?php echo __('List Course', 'learn-manager'); ?></a></li>
                            <li class="jslm_tabs-list-item"><a href="#jslm_instructor_topmenu"><?php echo __('Instructor Top menu', 'learn-manager'); ?></a></li>
                            <li class="jslm_tabs-list-item"><a href="#jslm_student_topmenu"><?php echo __('Student Top menu', 'learn-manager'); ?></a></li>
                            <li class="jslm_tabs-list-item"><a href="#jslm_visitor_topmenu"><?php echo __('Visitor Top menu', 'learn-manager'); ?></a></li>
                            <li class="jslm_tabs-list-item"><a href="#jslm_email"><?php echo __('Email setting', 'learn-manager'); ?></a></li>
                            <?php if(in_array('courserss', jslearnmanager::$_active_addons)){ ?>
                                <li class="jslm_tabs-list-item"><a href="#jslm_rss"><?php echo __('RSS setting', 'learn-manager'); ?></a></li>
                            <?php } ?>
                            <?php if(in_array('sociallogin', jslearnmanager::$_active_addons)){ ?>
                                <li class="jslm_tabs-list-item"><a href="#jslm_sociallogin"><?php echo __('Social Login', 'learn-manager'); ?></a></li>
                            <?php } ?>
                            <?php if(in_array('socialshare', jslearnmanager::$_active_addons)){ ?>
                                <li class="jslm_tabs-list-item"><a href="#jslm_socailsharing"><?php echo __('Social Sharing', 'learn-manager'); ?></a></li>
                            <?php } ?>
                        </ul>
                        <div class="jslm_tabInner">
                            <div id="jslm_site_setting" class="jslm_gen_body">
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Site Settings', 'learn-manager'); ?></h3>
                                <div class="jslm_js-learn-manager-configuration-table jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Site Title', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('title', isset(jslearnmanager::$_data[0]['title']) ? jslearnmanager::$_data[0]['title'] : '' , array('class' => 'jslm_inputbox')),  JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Offline', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('offline', $yesno, jslearnmanager::$_data[0]['offline']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Offline message', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value full-width"><?php echo wp_editor(jslearnmanager::$_data[0]['offline_text'], 'offline_text', array('media_buttons' => false)); ?></div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Data directory', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('data_directory', jslearnmanager::$_data[0]['data_directory'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('System will upload all user files in this folder', 'learn-manager'); echo '<br/>'; echo esc_html(jslearnmanager::$_path.jslearnmanager::$_data[0]['data_directory']);?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('System slug', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('system_slug', jslearnmanager::$_data[0]['system_slug'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                                        </div>
                                        <?php //if($theme_chk == 0){ ?>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Show breadcrumbs', 'learn-manager')?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('cur_location', $yesno, jslearnmanager::$_data[0]['cur_location']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Show navigation in breadcrumbs', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>
                                        <?php //} ?>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Course SEO', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('course_seo', jslearnmanager::$_data[0]['course_seo'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Course seo options are title, category, access type, course level', 'learn-manager').'. eg- ['.__('title', 'learn-manager').']'.' ['.__('category', 'learn-manager').']'.' ['.__('courselevel', 'learn-manager').']'; ?></small></div>
                                            </div>
                                        </div>
                                        <?php if(in_array('paymentplan', jslearnmanager::$_active_addons)){ ?>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Auto assign default payment plan to new course', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('auto_assign_payment_plan', $yesno, jslearnmanager::$_data[0]['auto_assign_payment_plan']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Auto assign default payment plan to new user', 'learn-manager'); ?></small></div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="jslm_right">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Default pagination size', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('pagination_default_page_size', jslearnmanager::$_data[0]['pagination_default_page_size'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Maximum number of records show per Page', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12 js-learn-manager-configuration-title">
                                                <?php echo __('Mark Course New', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('newdays', jslearnmanager::$_data[0]['newdays'], array('class' => 'jslm_inputbox not-full-width')), JSLEARNMANAGER_ALLOWED_TAGS); ?>&nbsp;<?php echo __('Days', 'learn-manager'); ?>
                                                <div class="js-col-xs-12 js-learn-manager-configuration-description"><small><?php echo __('How many days system show New tag', 'learn-manager'); ?></small></div>
                                            </div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                                <a href="https://www.youtube.com/watch?v=mLZYP5EA-lg" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                    <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                                </a>
                                            </div>
                                        </div>
                                        <?php if($theme_chk == 1){ ?>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Category List in Right Side bar', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('no_of_categories_rightbar', jslearnmanager::$_data[0]['no_of_categories_rightbar'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Show number of category list on right side bar of course detail and edit course page', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Allowed upload file size', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('allowed_file_size', jslearnmanager::$_data[0]['allowed_file_size'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('File size in kb', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Allowed course image size', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('allowed_logo_size', jslearnmanager::$_data[0]['allowed_logo_size'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('File size in kb', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Allowed image extensions type', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('image_file_type', jslearnmanager::$_data[0]['image_file_type'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Allowed file extensions types for upload files', 'learn-manager'); echo '<br/>'; ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Upload file types for lecture', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('file_file_type', jslearnmanager::$_data[0]['file_file_type'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('For example', 'learn-manager').':'.'jpeg,jpg,png'; ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Default Currency Symbol', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('default_curreny_forpaid', JSLEARNMANAGERincluder::getJSModel('currency')->getCurrencyForCombo(),jslearnmanager::$_data[0]['default_curreny_forpaid']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Default Curreny Symbol for Paid course, Payouts, Earning', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Date format', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('date_format', $date_format, jslearnmanager::$_data[0]['date_format']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Profile Image Size', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('allowed_profileimage_size', jslearnmanager::$_data[0]['allowed_profileimage_size'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Profile Image size in kb', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Default page', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('default_pageid', JSLEARNMANAGERincluder::getJSModel('postinstallation')->getPageList(), jslearnmanager::$_data[0]['default_pageid']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Select Learn Manager default page, on action system will redirect on selected page. If not select default page, email links and support icon might not work.', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <?php if(in_array('awards', jslearnmanager::$_active_addons)){ ?>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Select to show awards to user', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('award_multiple_or_maximum_rule', $awardcriteria, jslearnmanager::$_data[0]['award_multiple_or_maximum_rule']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Show Awards to user dashboard', 'learn-manager'); ?></small></div>
                                                </div>
                                                
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div id="jslm_visitor_setting" class="jslm_gen_body">
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Visitors Settings', 'learn-manager'); ?></h3>
                                <div class="js-learn-manager-configuration-table  jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title">  
                                                <?php echo __('Show captcha on registration form', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('cap_on_reg_form', $yesno, jslearnmanager::$_data[0]['cap_on_reg_form'], '', array('class' => 'jslm_inputbox', 'data-validation' => '')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Show captcha on Learn Manager registration form','learn-manager'); ?></small></div>
                                            </div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                                <a href="https://www.youtube.com/watch?v=OGISy5aTqFg" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                    <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                                </a>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('default captcha', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('captcha_selection', $captchalist, jslearnmanager::$_data[0]['captcha_selection']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Select captcha for plugin', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Learn Manager captcha calculation type', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('owncaptcha_calculationtype', $captchacalculation, jslearnmanager::$_data[0]['owncaptcha_calculationtype']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Select calculation type (addition, subtraction)', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Learn Manager captcha answer always positive', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('owncaptcha_subtractionans', $yesno, jslearnmanager::$_data[0]['owncaptcha_subtractionans']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Subtraction answer should be positive', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="jslm_right">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Number of operands for Learn Manager captcha', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('owncaptcha_totaloperand', $captchaop, jslearnmanager::$_data[0]['owncaptcha_totaloperand']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Number of operands for captcha', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Google recaptcha private key', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('recaptcha_privatekey', jslearnmanager::$_data[0]['recaptcha_privatekey'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enter the google recaptcha private key from','learn-manager') .'https://www.google.com/recaptcha/admin' ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Google recaptcha public key', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('recaptcha_publickey', jslearnmanager::$_data[0]['recaptcha_publickey'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enter the google recaptcha public key from','learn-manager').'https://www.google.com/recaptcha/admin'; ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title">
                                                <?php echo __('Visitor can shortlist course', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('visitor_have_shortlist_course', $yesno, jslearnmanager::$_data[0]['visitor_have_shortlist_course']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Can Visitor shortlist the course', 'learn-manager'); ?></small></div>
                                            </div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                                <a href="https://www.youtube.com/watch?v=_qk_9Lme4RU" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                    <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                                </a>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jslm_list_course" class="jslm_gen_body">
                                <div class="jslm_gen_body-inner">
                                    <?php if(in_array('featuredcourse', jslearnmanager::$_active_addons)){ ?>
                                        
                                        <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Cousre List', 'learn-manager'); ?></h3>
                                        <div class="jslm_left">
                                            <?php /* if($theme_chk == 0){ ?>
                                                <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Search icon position', 'learn-manager'); ?></div>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('searchcoursetag', $searchcoursetag, jslearnmanager::$_data[0]['searchcoursetag'])); ?></div>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Postion for search icon on course listing page.', 'learn-manager'); ?></small></div>
                                                </div>
                                            <?php }*/ ?>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Show featured course', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('showfeaturedcourseinlistcourses', $yesno, jslearnmanager::$_data[0]['showfeaturedcourseinlistcourses']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Show featured course in course lising page', 'learn-manager'); ?></small></div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="jslm_right">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Number of featured course', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('nooffeaturedcourseinlisting', jslearnmanager::$_data[0]['nooffeaturedcourseinlisting'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Number of featured course show per scroll', 'learn-manager'); ?></small></div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Cousre Detail', 'learn-manager'); ?></h3>
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Show Student List', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('showstudentlistincoursedetail', $yesno, jslearnmanager::$_data[0]['showstudentlistincoursedetail']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Show student list in course detail', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jslm_instructor_topmenu" class="jslm_gen_body">
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Instructor Top Menu', 'learn-manager'); ?></h3>
                                <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Home', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_home_instructor', $yesno, jslearnmanager::$_data[0]['tmenu_home_instructor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Course List', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_courses_instructor', $yesno, jslearnmanager::$_data[0]['tmenu_courses_instructor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Add Course', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_addcourse_instructor', $yesno, jslearnmanager::$_data[0]['tmenu_addcourse_instructor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <?php if(in_array('message', jslearnmanager::$_active_addons)){ ?>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Message', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_message_instructor', $yesno, jslearnmanager::$_data[0]['tmenu_message_instructor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="jslm_right">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('My Profile', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_myprofile_instructor', $yesno, jslearnmanager::$_data[0]['tmenu_myprofile_instructor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('My Courses', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_mycourses_instructor', $yesno, jslearnmanager::$_data[0]['tmenu_mycourses_instructor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Login/Logout', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_loginlogout_instructor', $yesno, jslearnmanager::$_data[0]['tmenu_loginlogout_instructor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jslm_student_topmenu" class="jslm_gen_body">
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Student Top Menu', 'learn-manager'); ?></h3>
                                <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Home', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_home_student', $yesno, jslearnmanager::$_data[0]['tmenu_home_student']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Course List', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_courses_student', $yesno, jslearnmanager::$_data[0]['tmenu_courses_student']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Shortlist Course', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_shortlistcourse_student', $yesno, jslearnmanager::$_data[0]['tmenu_shortlistcourse_student']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <?php if(in_array('message', jslearnmanager::$_active_addons)){ ?>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Message', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_message_student', $yesno, jslearnmanager::$_data[0]['tmenu_message_student']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="jslm_right">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('My Profile', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_myprofile_student', $yesno, jslearnmanager::$_data[0]['tmenu_myprofile_student']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('My Courses', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_mycourses_student', $yesno, jslearnmanager::$_data[0]['tmenu_mycourses_student']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Login/Logout', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_loginlogout_student', $yesno, jslearnmanager::$_data[0]['tmenu_loginlogout_student']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jslm_visitor_topmenu" class="jslm_gen_body">
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Visitor Top Menu', 'learn-manager'); ?></h3>
                                <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Home', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_home_visitor', $yesno, jslearnmanager::$_data[0]['tmenu_home_visitor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Course List', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_courses_visitor', $yesno, jslearnmanager::$_data[0]['tmenu_courses_visitor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Shortlist Course', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_shortlistcourse_visitor', $yesno, jslearnmanager::$_data[0]['tmenu_shortlistcourse_visitor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                    </div>
                                    <div class="jslm_right">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Login/Logout', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_loginlogout_visitor', $yesno, jslearnmanager::$_data[0]['tmenu_loginlogout_visitor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Register', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"> <?php echo wp_kses(JSLEARNMANAGERformfield::select('tmenu_register_visitor', $yesno, jslearnmanager::$_data[0]['tmenu_register_visitor']), JSLEARNMANAGER_ALLOWED_TAGS); ?> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jslm_email" class="jslm_gen_body">
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Email', 'learn-manager'); ?></h3>
                                <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Sender email address', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('mailfromaddress', jslearnmanager::$_data[0]['mailfromaddress'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Default sender email', 'learn-manager'); echo '<br/><b></b>'; ?></small></div>
                                            </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Email sender name', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('mailfromname', jslearnmanager::$_data[0]['mailfromname'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Default Email Sender Name', 'learn-manager'); echo '<br/><b></b>'; ?></small></div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="jslm_right">
                                       <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Admin email address', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('adminemailaddress', jslearnmanager::$_data[0]['adminemailaddress'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Admin Email', 'learn-manager'); echo '<br/><b></b>'; ?></small></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if(in_array('courserss', jslearnmanager::$_active_addons)){ ?>
                                <div id="jslm_rss" class="jslm_gen_body">
                                    <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('RSS', 'learn-manager'); ?></h3>
                                    <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                        <div class="jslm_left">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Course RSS', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('course_rss', $yesno, jslearnmanager::$_data[0]['course_rss']), JSLEARNMANAGER_ALLOWED_TAGS); ?><div><small></small></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Webmaster', 'learn-manager'); echo '<br/>'; ?></small></div></div>
                                                
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Title', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('rss_course_title', jslearnmanager::$_data[0]['rss_course_title'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Must provide title for feed', 'learn-manager'); echo '<br/>'; ?></small></div>
                                                </div>
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Description', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo JSLEARNMANAGERformfield::textarea('rss_course_description', jslearnmanager::$_data[0]['rss_course_description'], array('class' => 'jslm_inputbox')); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Must provide description for feed', 'learn-manager'); echo '<br/>'; ?></small></div>
                                                </div>
                                                
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Copyright', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('rss_course_copyright', jslearnmanager::$_data[0]['rss_course_copyright'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Leave blank to hide', 'learn-manager'); echo '<br/>'; ?></small></div>
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="jslm_right">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Editor', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('rss_course_editor', jslearnmanager::$_data[0]['rss_course_editor'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Leave blank to hide editor used for feed content issue', 'learn-manager'); echo '<br/>'; ?></small></div>
                                                </div>
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Time to live', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('rss_course_ttl', jslearnmanager::$_data[0]['rss_course_ttl'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Time to live for feed', 'learn-manager'); echo '<br/>'; ?></small></div>
                                                </div>
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Web master', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('rss_course_webmaster', jslearnmanager::$_data[0]['rss_course_webmaster'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Leave blank to hide webmaster used for technical issue', 'learn-manager'); echo '<br/>'; ?></small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Course block', 'learn-manager'); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
                                    <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                        <div class="jslm_left">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12 js-col-md-2 js-learn-manager-configuration-title"><?php echo __('Show with categories', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('rss_course_categories', $showhide, jslearnmanager::$_data[0]['rss_course_categories']), JSLEARNMANAGER_ALLOWED_TAGS); ?><div><small><?php echo __('Use rss categories with our course categories', 'learn-manager'); ?></small></div></div>
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Show course image', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('rss_course_image', $yesno, jslearnmanager::$_data[0]['rss_course_image']), JSLEARNMANAGER_ALLOWED_TAGS); ?><div><small></small></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Show course image in feeds', 'learn-manager'); echo '<br/>'; ?></small></div></div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if(in_array('sociallogin', jslearnmanager::$_active_addons)){ ?>
                                <div id="jslm_sociallogin" class="jslm_gen_body">
                                    <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Facebook', 'learn-manager'); ?></h3>
                                    <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                        <div class="jslm_left">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Login with facebook', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('loginwithfacebook', $yesno, jslearnmanager::$_data[0]['loginwithfacebook']), JSLEARNMANAGER_ALLOWED_TAGS); ?><div><small></small></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Facebook user can login in learn manager', 'learn-manager'); echo '<br/>'; ?></small></div></div>
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('Valid OAuth redirect URI', 'js-jobs'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php?>
                                                    <div class="js-col-xs-12 js-learn-manager-configuration-description"><small><?php
                                                        $pageid = jslearnmanager::$_db->get_var("SELECT configvalue FROM `".jslearnmanager::$_db->prefix."js_learnmanager_config` WHERE configname = 'default_pageid'");
                                                        $url_fb = site_url("?page_id=".$pageid."&jslmsmod=sociallogin&action=jslmstask&task=sociallogin&media=facebook");
                                                        echo '<strong>'.$url_fb.'</strong>';
                                                        echo '<br/>';
                                                        echo __('This url is to be inserted in "Valid OAuth redirect URI" field on facebook app interface for facebook app to work.','js-jobs');
                                                        echo '<br/>';
                                                        echo __('This URL is dependent on "Default page" on site settings.','js-jobs');
                                                        ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="jslm_right">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('API Key', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('apikeyfacebook', jslearnmanager::$_data[0]['apikeyfacebook'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('API key is required for facebook app', 'learn-manager'); echo '<br/>'; ?></small></div>
                                                </div>
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Secret', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('clientsecretfacebook', jslearnmanager::$_data[0]['clientsecretfacebook'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Linkedin', 'learn-manager'); ?></h3>
                                    <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                        <div class="jslm_left">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Login with linkedin', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('loginwithlinkedin', $yesno, jslearnmanager::$_data[0]['loginwithlinkedin']), JSLEARNMANAGER_ALLOWED_TAGS); ?><div><small></small></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Linkedin user can login in learn manager', 'learn-manager'); echo '<br/>'; ?></small></div></div>
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('API Key', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('apikeylinkedin', jslearnmanager::$_data[0]['apikeylinkedin'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('API key is required for linkedin app', 'learn-manager'); echo '<br/>'; ?></small></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="jslm_right">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Secret', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('clientsecretlinkedin', jslearnmanager::$_data[0]['clientsecretlinkedin'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Xing', 'learn-manager'); ?></h3>
                                    <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                        <div class="jslm_left">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Login with Xing', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('loginwithxing', $yesno, jslearnmanager::$_data[0]['loginwithxing']), JSLEARNMANAGER_ALLOWED_TAGS); ?><div><small></small></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Xing user can login in learn manager', 'learn-manager'); echo '<br/>'; ?></small></div></div>
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('API Key', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('apikeyxing', jslearnmanager::$_data[0]['apikeyxing'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('API key is required for xing app', 'learn-manager');  ?></small></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="jslm_right">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Secret', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('clientsecretxing', jslearnmanager::$_data[0]['clientsecretxing'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Google', 'learn-manager'); ?></h3>
                                    <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                        <div class="jslm_left">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Login with Google', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('loginwithgoogle', $yesno, jslearnmanager::$_data[0]['loginwithgoogle']), JSLEARNMANAGER_ALLOWED_TAGS); ?><div><small></small></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Google user can login in learn manager', 'learn-manager'); echo '<br/>'; ?></small></div></div>
                                            </div>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('API Key', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('apikeygoogle', jslearnmanager::$_data[0]['apikeygoogle'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('API key is required for google app', 'learn-manager');  ?></small></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="jslm_right">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Secret', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('clientsecretgoogle', jslearnmanager::$_data[0]['clientsecretgoogle'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if(in_array('socialshare', jslearnmanager::$_active_addons)){ ?>
                                <div id="jslm_socailsharing" class="jslm_gen_body">
                                    <h3 class="js-learn-manager-configuration-heading-main">
                                        <span class="jslms-socialmedia-hd"><?php echo __('Social Media','learn-manager'); ?></span>
                                         <a href="https://www.youtube.com/watch?v=2lF2BFVDXRk" target="_blank" class="jslmsadmin-add-link-w jslms-tkt-det-hdg-w black-bg-w  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                        <img alt="<?php echo __('Watch','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                        <?php echo __('Watch Video','learn-manager'); ?>
                                    </a> 
                                   </h3>
                                    <div class="js-learn-manager-configuration-table jslm_gen_body-inner">
                                        <div class="js-learn-managerconfig-threecols">
                                            <div class="js-learn-manager-configuration-row">
                                                <label><?php echo JSLEARNMANAGERformfield::checkbox('tumbler_share', $social, (jslearnmanager::$_data[0]['tumbler_share'] == 1) ? 1 : 0, array('class' => 'jslm_checkbox')); ?><?php echo __('Tumbler share', 'learn-manager'); ?></div>
                                        </div>
                                        <div class="js-learn-managerconfig-threecols">
                                            <div class="js-learn-manager-configuration-row"><label><?php echo JSLEARNMANAGERformfield::checkbox('fb_share', $social, (jslearnmanager::$_data[0]['fb_share'] == 1) ? 1 : 0, array('class' => 'jslm_checkbox')); ?><?php echo __('Facebook share', 'learn-manager'); ?></label></div>
                                        </div>
                                        <div class="js-learn-managerconfig-threecols">
                                            <div class="js-learn-manager-configuration-row"><label><?php echo JSLEARNMANAGERformfield::checkbox('google_share', $social, (jslearnmanager::$_data[0]['google_share'] == 1) ? 1 : 0, array('class' => 'jslm_checkbox')); ?><?php echo __('Google share', 'learn-manager'); ?></label></div>
                                        </div>
                                        <div class="js-learn-managerconfig-threecols">
                                            <div class="js-learn-manager-configuration-row"><label><?php echo JSLEARNMANAGERformfield::checkbox('blogger_share', $social, (jslearnmanager::$_data[0]['blogger_share'] == 1) ? 1 : 0, array('class' => 'jslm_checkbox')); ?><?php echo __('Blogger share', 'learn-manager'); ?></label></div>
                                        </div>
                                        <div class="js-learn-managerconfig-threecols">
                                            <div class="js-learn-manager-configuration-row"><label><?php echo JSLEARNMANAGERformfield::checkbox('instgram_share', $social, (jslearnmanager::$_data[0]['instgram_share'] == 1) ? 1 : 0, array('class' => 'jslm_checkbox')); ?><?php echo __('Instagram share', 'learn-manager'); ?></label></div>
                                        </div>
                                        <div class="js-learn-managerconfig-threecols">
                                            <div class="js-learn-manager-configuration-row"><label><?php echo JSLEARNMANAGERformfield::checkbox('linkedin', $social, (jslearnmanager::$_data[0]['linkedin'] == 1) ? 1 : 0, array('class' => 'jslm_checkbox')); ?><?php echo __('Linkedin share', 'learn-manager'); ?></label></div>
                                        </div>
                                        <div class="js-learn-managerconfig-threecols">
                                            <div class="js-learn-manager-configuration-row"><label><?php echo JSLEARNMANAGERformfield::checkbox('digg_share', $social, (jslearnmanager::$_data[0]['digg_share'] == 1) ? 1 : 0, array('class' => 'jslm_checkbox')); ?><?php echo __('Digg share', 'learn-manager'); ?></label></div>
                                        </div>
                                        <div class="js-learn-managerconfig-threecols">
                                            <div class="js-learn-manager-configuration-row"><label><?php echo JSLEARNMANAGERformfield::checkbox('twitter_share', $social, (jslearnmanager::$_data[0]['twitter_share'] == 1) ? 1 : 0, array('class' => 'jslm_checkbox')); ?><?php echo __('Twitter share', 'learn-manager'); ?></label></div>
                                        </div>
                                        <div class="js-learn-managerconfig-threecols">
                                            <div class="js-learn-manager-configuration-row"><label><?php echo JSLEARNMANAGERformfield::checkbox('pintrest_share', $social, (jslearnmanager::$_data[0]['pintrest_share'] == 1) ? 1 : 0, array('class' => 'jslm_checkbox')); ?><?php echo __('Pintrest share', 'learn-manager'); ?></label></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('isgeneralbuttonsubmit', 1), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslmslay', 'configurations'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'configuration_saveconfiguration'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <div class="jslm_js-form-button">
                    <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save','learn-manager') .' '. __('Configuration', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </div>
            </form>
          </div>
        </div>
</div>

    <?php /*
    <style type="text/css">
        div#map_container{
            z-index:1000;
            position:relative;
            background:#000;
            width:100%;
            height:<?php echo jslearnmanager::$_config['mapheight'] . 'px'; ?>;}
    </style>

    <?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
    <script type="text/javascript" src="<?php echo esc_url($protocol); ?>maps.googleapis.com/maps/api/js?key=<?php echo jslearnmanager::$_config['google_map_api_key']; ?>"></script>
    <script type="text/javascript">
        function hideshowtables(table_id) {
            var obj = document.getElementById(table_id);
            var bool = obj.style.display;
            if (bool == '')
                obj.style.display = "none";
            else
                obj.style.display = "";
        }
        function loadMap() {
            var default_latitude = document.getElementById('default_latitude').value;
            var default_longitude = document.getElementById('default_longitude').value;
            var latlng = new google.maps.LatLng(default_latitude, default_longitude);
            zoom = 10;
            var myOptions = {
                zoom: zoom,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
            var lastmarker = new google.maps.Marker({
                postiion: latlng,
                map: map,
            });
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
            });
            marker.setMap(map);
            lastmarker = marker;

            google.maps.event.addListener(map, "click", function (e) {
                var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({'latLng': latLng}, function (results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {
                        if (lastmarker != '')
                            lastmarker.setMap(null);
                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                        });
                        marker.setMap(map);
                        lastmarker = marker;
                        document.getElementById('default_latitude').value = marker.position.lat();
                        document.getElementById('default_longitude').value = marker.position.lng();

                    } else {
                        alert("<?php echo __('Geocode was not successful for the following reason', 'learn-manager'); ?>: " + status);
                    }
                });
            });
        }
        function showdiv() {
            document.getElementById('map').style.visibility = 'visible';
            jQuery("div#full_background").css("display", "block");
            jQuery("div#popup_main").slideDown('slow');
        }
        function hidediv() {
            document.getElementById('map').style.visibility = 'hidden';
            jQuery("div#popup_main").slideUp('slow');
            jQuery("div#full_background").hide();
        }
*/ ?>

<script type="text/javascript">
    jQuery(document).ready(function($){
        // jQuery("div#jslm_tabs").tabs();
        // $.validate();
    });

            jQuery('.jslm_tabs ul li').click(function(){
                jQuery('.jslm_tabs ul li').removeClass('ui-tabs-active');
                jQuery(this).addClass('ui-tabs-active');
            });
            jQuery('.jslm_js-divlink').click(function(){
                jQuery('.jslm_js-divlink').removeClass('active');
                jQuery(this).addClass('active');
            });

            jQuery(".jslmsadmin-configurations-toggle").click(function(){
                jQuery(".jslmsadmin-left-menu.jslearnmanageradmin-leftmenu").toggleClass("show");
            });

</script>

