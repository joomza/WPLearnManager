<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

wp_enqueue_script('jquery-ui-tabs');
// Lists objecs
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'learn-manager')), (object) array('id' => 0, 'text' => __('No', 'learn-manager')));
$showhide = array((object) array('id' => 1, 'text' => __('Show', 'learn-manager')), (object) array('id' => 0, 'text' => __('Hide', 'learn-manager')));
$coursealert = array((object) array('id' => '', 'text' => __('Select left')), (object) array('id' => 1, 'text' => __('All Fields')), (object) array('id' => 2, 'text' => __('Only filled fields', 'learn-manager')));
$yesnosectino = array((object) array('id' => 1, 'text' => __('Only section that have value', 'learn-manager')), (object) array('id' => 0, 'text' => __('All sections', 'learn-manager')));
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
$theme_chk = 0;
if($theme_chk == 'JS Learn Manager'){
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
     var value = jQuery('select#student_set_register_link').val();
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
        jQuery('select#student_set_register_link').change(function(){
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
                        <li><?php echo __('Student Configuration','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Student Configuration ', 'learn-manager'); ?></span>
        </div>            
    <div id="jslms-data-wrp" class="jslmsadmin-wrapper-white-bg jslmsadmin-config-main-wrapper jslms-std-data-wrp">
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
                                        <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('Enable Student Area', 'learn-manager'); ?></div>
                                        <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('disable_student', $yesno, jslearnmanager::$_data[0]['disable_student']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('If no then front end student area is not accessable', 'learn-manager'); ?></small></div>
                                        </div>
                                    </div>
                                    <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                        <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('Student autoapprove', 'learn-manager'); ?></div>
                                        <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('student_autoapprove', $yesno, jslearnmanager::$_data[0]['student_autoapprove']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Approve/Reject student to effect instructor functionality', 'learn-manager'); ?></small></div>
                                        </div>
                                    </div>
                                    <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                        <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('Allow user to register as student', 'learn-manager'); ?></div>
                                        <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('showstudentlink', $yesno, jslearnmanager::$_data[0]['showstudentlink']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jslm_right">
                                    <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                        <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Student can view instructor profile', 'learn-manager'); ?></div>
                                        <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('studentview_js_controlpanel', $yesno, jslearnmanager::$_data[0]['studentview_js_controlpanel']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                        </div>
                                    </div>

                                     <!--Set Register Link  -->
                                    <div class="js-col-xs-12 js-col-md-12  js-learn-manager-configuration-row">
                                        <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Set Student Register link', 'learn-manager'); ?></div>
                                        <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('student_set_register_link', $defaultcustom, jslearnmanager::$_data[0]['student_set_register_link']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('New Student Set Register link redirect page', 'learn-manager'); ?></small></div>
                                        </div>
                                    </div>

                                    <!-- custome -->
                                    <div class="js-col-xs-12 js-col-md-12 custome-link js-learn-manager-configuration-row">
                                        <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Set Student custome Register link', 'learn-manager'); ?></div>
                                        <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::text('student_register_link', jslearnmanager::$_data[0]['student_register_link'], array('class' => 'inputbox registerlink_field')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('Custome Student Regitser Link', 'learn-manager'); ?></small></div>
                                        </div>
                                    </div>

                                    <!--End Register Link  -->  
                                    <div class="js-col-xs-12 js-col-md-12 redirect-field js-learn-manager-configuration-row">
                                        <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Student register redirect', 'learn-manager'); ?></div>
                                        <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('register_student_redirect_page', JSLEARNMANAGERincluder::getJSModel('postinstallation')->getPageList(), jslearnmanager::$_data[0]['register_student_redirect_page']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small><?php echo __('New student register redirect page', 'learn-manager'); ?></small></div>
                                        </div>
                                    </div>

                                    <?php if(in_array('message', jslearnmanager::$_active_addons)){ ?>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('Can send message to instructor', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('studentsend_message', $yesno, jslearnmanager::$_data[0]['studentsend_message']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
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
                                        <div class="js-col-xs-12 js-learn-manager-configuration-title">
                                            <?php echo __('Can Enroll in course', 'learn-manager'); ?></div>
                                        <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('allow_enroll', $yesno, jslearnmanager::$_data[0]['allow_enroll']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                        </div>
                                        <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                            <a href="https://www.youtube.com/watch?v=RdhBUCdBAjU" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                            </a>
                                        </div>
                                    </div>
                                    <?php if(in_array('coursereview', jslearnmanager::$_active_addons)){ ?>
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12 js-learn-manager-configuration-title">
                                                <?php echo __('Can give review on course', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('allow_review', $yesno, jslearnmanager::$_data[0]['allow_review']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                            </div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                                <a href="https://www.youtube.com/watch?v=0zSAuMeqWqY" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                    <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="jslm_right">
                                    <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                        <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('Can view course detail', 'learn-manager'); ?></div>
                                        <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('allow_view_coursedetail', $yesno, jslearnmanager::$_data[0]['allow_view_coursedetail']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <?php if(in_array('retakequiz', jslearnmanager::$_active_addons)){ ?>
                                <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Lecture Settings', 'learn-manager'); ?></h3>
                                <div class="jslm_js-learn-manager-configuration-table jslm_gen_body-inner">
                                    <div class="jslm_left">
                                        <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                            <div class="js-col-xs-12 js-learn-manager-configuration-title">
                                                <?php echo __('Retake Quiz', 'learn-manager'); ?></div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('retake_quiz', $yesno, jslearnmanager::$_data[0]['retake_quiz']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <div class="js-col-xs-12  js-learn-manager-configuration-description"><small></small></div>
                                            </div>
                                            <div class="js-col-xs-12 js-learn-manager-configuration-video">
                                                <a href="https://www.youtube.com/watch?v=i-VvP9kScyA" target="_blank" class="jslms-tkt-det-hdg  js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                                                    <img alt="<?php echo __('play','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/watch-video-icon-config.png"/>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <?php /*
                        <div id="jslm_email">
                            <h3 class="js-learn-manager-configuration-heading-main"><?php echo __('Email Alert To Student On Enrolled', 'learn-manager'); ?></h3>
                            <div class="jslm_left">
                                <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                   <div class="js-col-xs-12  js-learn-manager-configuration-title"><?php echo __('What to include in email', 'learn-manager'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                   <div class="js-col-xs-12  js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('show_only_section_that_have_value_student', $yesnosectino, jslearnmanager::$_data[0]['show_only_section_that_have_value_student']); ?></div>
                                    <div class="js-col-xs-12 js-learn-manager-configuration-value"><small><?php echo __('All sections are included in student email content or only sections that have value','learn-manager') .'.'.__('This option is only valid if instructor created course data in email settings while posting course', 'learn-manager'); ?></small></div>
                               </div>
                            </div>
                            <div class="jslm_right">
                                <div class="js-col-xs-12 js-col-md-12 js-learn-manager-configuration-row">
                                    <div class="js-col-xs-12 js-learn-manager-configuration-title"><?php echo __('What to include in email', 'learn-manager'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                    <div class="js-col-xs-12 js-learn-manager-configuration-value"><?php echo wp_kses(JSLEARNMANAGERformfield::select('student_course_alert_fields', $coursealert, jslearnmanager::$_data[0]['student_course_alert_fields']); ?></div>
                                    <div class="js-col-xs-12 js-learn-manager-configuration-value"><small><?php echo __('All fields are included in student email content or only filled fields','learn-manager') .'.'.__('This option is only valid if instructor created course data in email settings while posting course', 'learn-manager'); ?></small></div>
                                </div>
                            </div>
                        </div>
                        */ ?>
                    </div>
                </div>
            </div>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('isgeneralbuttonsubmit', 0), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslmslay', 'configurationsstudent'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'configuration_saveconfiguration'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <div class="jslm_js-form-button">
                <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save','learn-manager') .' '. __('Configuration', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            </div>
        </form>
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
