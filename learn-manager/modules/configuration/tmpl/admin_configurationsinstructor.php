<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-tabs');
// Lists objecs
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'learn-manager')), (object) array('id' => 0, 'text' => __('No', 'learn-manager')));
$yesnosectino = array((object) array('id' => 1, 'text' => __('Only section that have value', 'learn-manager')), (object) array('id' => 0, 'text' => __('All sections', 'learn-manager')));
$coursealert = array((object) array('id' => '', 'text' => __('Select left')), (object) array('id' => 1, 'text' => __('All Fields')), (object) array('id' => 2, 'text' => __('Only filled fields', 'learn-manager')));
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
$theme = wp_get_theme();
$theme_chk = 0;
if($theme == 'JS Learn Manager'){
    $theme_chk = 1;
}
?>

<?php 
$defaultcustom = array(
    (object) array('id' => '1', 'text' => __('Wordpress', 'jslearnmanager')),
    (object) array('id' => '2', 'text' => __('Custom', 'jslearnmanager'))
);

?>
<script type="text/javascript">
    jQuery(document).ready(function (){
      // jQuery('.redirect-field').attr('style','display: none');
     var value = jQuery('select#instructor_set_register_link').val();
     if(value == 1){
         jQuery('.redirect-field').attr('style','display: block');
         jQuery('.custome-link').attr('style','display: none');
      }else if(value == 2){
         jQuery('.redirect-field').attr('style','display: none');
         jQuery('.custome-link').attr('style','display: block');
      }
    });
</script>
<script type="text/javascript">
// for the set register 
        jQuery(document).ready(function () {
        jQuery('select#instructor_set_register_link').change(function(){
           var value = jQuery(this).val();
           // alert(value);
           if (value == 2)
            {
               jQuery('.redirect-field').attr('style','display: none');
               jQuery('.custome-link').attr('style','display: block');

            }else if(value == 1)
            {
                jQuery('.redirect-field').attr('style','display: block');
                jQuery('.custome-link').attr('style','display: none');

            }
            })
         
            });
    //end set register
</script>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
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
                        <li><?php echo __(' Instructor Configuration','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __(' Instructor Configuration', 'learn-manager'); ?></span>
        </div>            
        <div id="jslms-data-wrp" class="jslmsadmin-wrapper-white-bg jslmsadmin-config-main-wrapper jslms-data-wrp">
            <form id="jslearnmanager-form" class="jslearnmanager-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_configuration&task=saveconfiguration") ?>" enctype="multipart/form-data">
                <div class="jslmsadmin-configurations-toggle">
                    <img alt="<?php echo __('Help','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/left-icons//menu.png" />
                    <span class="jslm_text">Select Configuration </span>
                </div>
                <div class="jslmsadmin-left-menu jslearnmanageradmin-leftmenu">
                    <?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigSideMenu(); ?>
                </div>
                <div class="jslmsadmin-right-content">
                    <div id="jslm_tabs" class="jslm_tabs">
                        <ul class="jslm_tabs-list">
                            <li class="jslm_tabs-list-item ui-tabs-active"><a href="#jslm_general_setting"><?php echo __('General Settings', 'learn-manager'); ?></a></li>
                            <li class="jslm_tabs-list-item"><a href="#jslm_course_setting"><?php echo __('Course Settings', 'learn-manager'); ?></a></li>
                            <?php /*<li><a href="#jslm_email"><?php echo __('Email setting', 'learn-manager'); ?></a></li>*/ ?>
                        </ul>
                        <div class="jslm_tabInner">
                            <div id="jslm_general_setting" class="jslm_gen_body">
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('General Settings', 'learn-manager'); ?></h3>
                                <div class="jslm_js-learn-manager-configuration-table jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('Enable Instructor Area', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('disable_instructor', $yesno, jslearnmanager::$_data[0]['disable_instructor']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Hide and show instructor area from layout ', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('Instructor autoapprove', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('instructor_autoapprove', $yesno, jslearnmanager::$_data[0]['instructor_autoapprove']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Approve/Reject instructor to effect instructor functionality', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12 js-learn-manager-configuration-title">
                                                <?php echo __('Allow user to register as instructor', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('showinstructorlink', $yesno, jslearnmanager::$_data[0]['showinstructorlink']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enable/Disable user to register as a instructor', 'learn-manager'); ?></small></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('effects on user registration', 'learn-manager'); ?></small></div>
                                            </div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                                <a href="https://www.youtube.com/watch?v=jm9SgfuMqMc" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                    <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                                </a>
                                            </div>
                                        </div>
                                        <?php if(in_array('featuredcourse', jslearnmanager::$_active_addons)){ ?>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12 js-learn-manager-configuration-title">
                                                <?php echo __('Enable featured course', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('system_have_featured_course', $yesno, jslearnmanager::$_data[0]['system_have_featured_course']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enable/Disable user to make featured course', 'learn-manager'); ?></small></div>
                                                </div>
                                                <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                                    <a href="https://www.youtube.com/watch?v=gLs0mlzbvwU" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                        <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('message', jslearnmanager::$_active_addons)){ ?>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Can send message to student', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('instructorsend_message', $yesno, jslearnmanager::$_data[0]['instructorsend_message']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enable/Disable to send message to student', 'learn-manager'); ?></small></div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="jslm_right">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('Course auto approve', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('course_auto_approve', $yesno, jslearnmanager::$_data[0]['course_auto_approve']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enable/Disable course auto approve', 'learn-manager'); ?></small></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Newly created course auto approve in case of yes selected', 'learn-manager'); ?></small></div>
                                            </div>
                                            
                                        </div>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Instructor can view student profile', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('instructorviewstudent_js_controlpanel', $yesno, jslearnmanager::$_data[0]['instructorviewstudent_js_controlpanel']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enable/Disable Instructor to view student area', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>
                                        
                                       <!--Set Register Link  -->
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Set Instructor Register link', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('instructor_set_register_link', $defaultcustom, jslearnmanager::$_data[0]['instructor_set_register_link']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('New Instructor Set Register link redirect page', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>

                                        <!-- custome -->
                                        <div class="js-col-xs-12 js-col-md-12 custome-link js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Set Instructor Custome Register Link', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('instructor_register_link', jslearnmanager::$_data[0]['instructor_register_link'], array('class' => 'inputbox registerlink_field')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Custome Instructor Regitser Link', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>
                                        <!--End Register Link  -->  
                                        <div class="js-col-xs-12 js-col-md-12 redirect-field  js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Instructor register redirect', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('register_instructor_redirect_page', JSLEARNMANAGERincluder::getJSModel('postinstallation')->getPageList(), jslearnmanager::$_data[0]['register_instructor_redirect_page']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enable/Disable instructor to redirect after new register', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>

                                        <?php if(in_array('featuredcourse', jslearnmanager::$_active_addons)){ ?>
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('Featured course auto approve', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('featuredcourse_autoapprove', $yesno, jslearnmanager::$_data[0]['featuredcourse_autoapprove']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enable/Disable featured course auto approve', 'learn-manager'); ?></small></div>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Featured course ', 'learn-manager'); ?></small></div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                            <div id="jslm_course_setting" class="jslm_gen_body">
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Course Settings', 'learn-manager'); ?></h3>
                                <div class="jslm_js-learn-manager-configuration-table jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title">
                                            <?php echo __('Instructor can add course', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('allow_user_to_add_course', $yesno, jslearnmanager::$_data[0]['allow_user_to_add_course']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enable/Disable instructor to create new course', 'learn-manager'); ?></small></div>
                                            </div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                                <a href="https://www.youtube.com/watch?v=cPH5zKlhNpo" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                    <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                    <?php if(in_array('paidcourse', jslearnmanager::$_active_addons)){ ?>
                                        <div class="jslm_right">
                                            <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                                <div class="js-col-xs-12 js-learn-manager-configuration-title">
                                                    <?php echo __('Can add Paid course', 'learn-manager'); ?></div>
                                                <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('allow_add_paidcourse', $yesno, jslearnmanager::$_data[0]['allow_add_paidcourse']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                    <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Enable/Disable to add paid course', 'learn-manager'); ?></small></div>
                                                </div>
                                                <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                                    <a href="https://www.youtube.com/watch?v=erq_E_9Kfuk" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                        <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Lecture Settings', 'learn-manager'); ?></h3>
                                <div class="jslm_js-learn-manager-configuration-table jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Number of files allowed', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('max_allowed_lecturesfiles', jslearnmanager::$_data[0]['max_allowed_lecturesfiles'], array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Maximum number of files allowed in a lecture and in case of empty no file will be uploaded', 'learn-manager'); ?></small></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php /*
                            <div id="jslm_email">
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Email Alert To Instructor On Enroll Student', 'learn-manager'); ?></h3>
                                <div class="jslm_left">
                                    <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                       <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('What to include in email', 'learn-manager'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                       <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('show_only_section_that_have_value', $yesnosectino, jslearnmanager::$_data[0]['show_only_section_that_have_value']); ?></div>
                                        <div class="js-col-xs-12 js-learn-manager-configuration-value"><small><?php echo __('All sections are included in instructor email content or only sections that have value','learn-manager') .'.'.__('This option is only valid if instructor created course data in email settings while posting course', 'learn-manager'); ?></small></div>
                                   </div>
                                </div>
                                <div class="jslm_right">
                                    <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                        <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('What to include in email', 'learn-manager'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                        <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('instructor_course_alert_fields', $coursealert, jslearnmanager::$_data[0]['instructor_course_alert_fields']); ?></div>
                                        <div class="js-col-xs-12 js-learn-manager-configuration-value"><small><?php echo __('All fields are included in instructor email content or only filled fields','learn-manager') .'.'.__('This option is only valid if instructor created course data in email settings while posting course', 'learn-manager'); ?></small></div>
                                    </div>
                                </div>
                            </div>
                            */ ?>
                        </div>
                    </div>
                </div>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('isgeneralbuttonsubmit', 0), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslmslay', 'configurationsinstructor'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'configuration_saveconfiguration'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <div class="jslm_js-form-button">
                    <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save','learn-manager') .' '. __('Configuration', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        // jQuery("div#jslm_tabs").tabs();
        // $.validate();
         jQuery('.jslm_tabs ul li').click(function(){
            jQuery('.jslm_tabs ul li').removeClass('ui-tabs-active');
            jQuery(this).addClass('ui-tabs-active');
        });
        jQuery('.jslm_js-divlink').click(function(){
            jQuery('.jslm_js-divlink').removeClass('active');
            jQuery(this).addClass('active');
        });
    });
            jQuery(".jslmsadmin-configurations-toggle").click(function(){
                jQuery(".jslmsadmin-left-menu.jslearnmanageradmin-leftmenu").toggleClass("show");
            });
</script>
