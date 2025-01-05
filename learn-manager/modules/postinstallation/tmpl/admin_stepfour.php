<script type="text/javascript">
    jQuery(document).ready(function() {
      // jQuery('.learn-manager-userselect').chosen({
      //   placeholder_text_single: "Select User",
      //   no_results_text: "Oops, nothing found!"
      // });
      jQuery("#student_list").val(0).trigger('chosen:updated')  
      jQuery("#instructor_list").val(0).trigger('chosen:updated')  
    });
    function refreshList() {
        location.reload();
    }
    function showHideUserForm() {
        var sampledata = jQuery('#sampledata').val();
        if (sampledata == 0) {
            jQuery('.learn-manager-show-sample-data-form').addClass('learn-manager-hide-sample-data-form');
        } else {
            jQuery('.learn-manager-show-sample-data-form').removeClass('learn-manager-hide-sample-data-form');
        }
    }
    function checkForStdAndINS() {
        var student_id = jQuery('#student_id').val();
        var instructor_id = jQuery('#instructor_id').val();
        if (instructor_id != 0 && instructor_id == student_id) {
            alert('Student And Instructor Cannot Be Same');
        } else if (instructor_id == 0 && student_id == 0){
            if (confirm('You have not selected any user, so no course will be available on the user side')) {
              document.getElementById('jslearnmanager-form-ins').submit();
            }
            
        } else {
            document.getElementById('jslearnmanager-form-ins').submit();
        }
    }
    function setValueForStudent() {
        var option = jQuery('#student_list').val();
        var myOption = option.split("-");
        var id =  Number(myOption[myOption.length - 1]);
        jQuery("#student_id").val(id);
    }
    function setValueForInstructor() {
        var option = jQuery('#instructor_list').val();
        var myOption = option.split("-");
        var id =  Number(myOption[myOption.length - 1]);
        jQuery("#instructor_id").val(id);
    }
</script>
<?php
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'learn-manager'))
                    , (object) array('id' => 0, 'text' => __('No', 'learn-manager')));
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
                <li class="header-parts second-part">
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
                <li class="header-parts fourth-part active">
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
            <span class="heading-post-ins"><?php echo __('Sample Data','learn-manager');?></span>
            <div class="post-installtion-content">
                <form id="jslearnmanager-form-ins" method="post" action="<?php echo admin_url("admin.php?page=jslm_postinstallation&task=savesampledata"); ?>">
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Insert Sample Data','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('sampledata', $yesno,1,'',array('class' => 'inputbox','onchange' => 'showHideUserForm()')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                    <div class="learn-manager-show-sample-data-form">
                        <div class="pic-config">
                            <div class="title">
                               <?php echo  __('student','learn-manager'); ?>
                            </div>
                            <div class="field">
                                <div class="name-part">
                                    <?php echo wp_kses(JSLEARNMANAGERformfield::select('student_list', JSLEARNMANAGERincluder::getJSModel('postinstallation')->getWpUsersList(),1,'',array('class' => 'inputbox learn-manager-userselect' , 'onchange' => 'setValueForStudent()')),JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                </div>
                                <span class="name-part-refresh-btn" onclick="refreshList()" title="<?php echo __('refresh','learn-manager');?>"><?php echo __('Refresh','learn-manager'); ?></span>
                                <a target="_blank" class="name-part-create-user-btn" href="<?php echo esc_url( admin_url( 'user-new.php' ) ); ?>" title="<?php echo __('create user','learn-manager');?>"><?php echo __('Create user','learn-manager'); ?></a>
                            </div>
                        </div>
                        <div class="pic-config">
                            <div class="title">
                               <?php echo  __('Instructor','learn-manager'); ?>
                            </div>
                            <div class="field">
                                <div class="name-part">
                                    <?php echo wp_kses(JSLEARNMANAGERformfield::select('instructor_list', JSLEARNMANAGERincluder::getJSModel('postinstallation')->getWpUsersList(),1,'',array('class' => 'inputbox learn-manager-userselect' , 'onchange' => 'setValueForInstructor()')),JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                </div>
                                <span class="name-part-refresh-btn" onclick="refreshList()" title="<?php echo __('refresh','learn-manager');?>"><?php echo __('Refresh','learn-manager'); ?></span>
                                <a target="_blank" class="name-part-create-user-btn" href="<?php echo esc_url( admin_url( 'user-new.php' ) ); ?>" title="<?php echo __('create user','learn-manager');?>"><?php echo __('Create user','learn-manager'); ?></a>
                            </div>
                        </div>
                    </div>


					<?php if(jslearnmanager::$_learn_manager_theme == 1): ?>
                        <div class="pic-config temp-demo-data">
                            <div class="title">
                                <?php echo __('JS Learn Manager theme Sample Data','learn-manager');?>: &nbsp;
                            </div>
                            <div class="field">
                                <?php echo wp_kses(JSLEARNMANAGERformfield::select('temp_data', $yesno,1,'',array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                            </div>
                            <div class="desc"><?php echo __('if yes is selected then pages and menus of JS Learn Manager theme will be created and published.','learn-manager');?>. </div>
                        </div>
                    <?php endif; ?>
					
                   <div class="pic-button-part">
                        <a class="next-step finish" href="#" onclick="checkForStdAndINS()">
                            <?php echo __('Next','learn-manager'); ?>
                            <i class=" fa fa-long-arrow-right"></i>
                        </a>
                        <a class="back" href="<?php echo admin_url('admin.php?page=jslm_postinstallation&jslmslay=stepthree'); ?>">
                            <i class=" fa fa-long-arrow-left"></i>
                            <?php echo __('Back','learn-manager'); ?>
                        </a>
                    </div>

                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('student_id', '0'),JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('instructor_id', '0'),JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('step', 3), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </form>
            </div>
        </div>
    </div>
