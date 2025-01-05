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
                    <a href="#" title="link" class="tab_icon">
                        <img class="start" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/general-settings.png" />
                        <span class="text"><?php echo __('General', 'learn-manager'); ?></span>
                    </a>
                </li>
                <li class="header-parts second-part">
                    <a href="#" title="link" class="tab_icon">
                        <img class="start" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/user.png" />
                        <span class="text"><?php echo __('User', 'learn-manager'); ?></span>
                    </a>
                </li>
                <li class="header-parts third-part">
                    <a href="#" title="link" class="tab_icon">
                        <img class="start" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/course.png" />
                        <span class="text"><?php echo __('Course', 'learn-manager'); ?></span>
                    </a>
                </li>
                <li class="header-parts fourth-part">
                   <a href="#" title="link" class="tab_icon">
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
                    <div class="jslm_setting_complete_heading"><h1 class="Jslm_heading"><?php echo __('Setting Completed', 'learn-manager'); ?></h1></div>
                    <div class="jslm_img_wrp">
                        <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/postinstallation/complete-setting.png" alt="Seting Log" title="Seting Logo"> 
                    </div>
                    <div class="jslm_text_below_img">
                        <?php echo __('Setting you have applied has been save successfully','learn-manager');?>
                    </div>
                   <div class="pic-button-part">
                    <?php
                        /*
                        <a class="next-step finish" href="#" onclick="document.getElementById('jslearnmanager-form-ins').submit();">
                        */
                    ?>
                        <a class="next-step finish" href="?page=jslearnmanager">
                            <?php echo __('Next','learn-manager'); ?>
                            <i class=" fa fa-long-arrow-right"></i>
                        </a>
                        <a class="back" href="<?php echo admin_url('admin.php?page=jslm_postinstallation&jslmslay=stepthree'); ?>"> 
                            <i class=" fa fa-long-arrow-left"></i>
                            <?php echo __('Back','learn-manager'); ?>
                        </a>
                    </div>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('step', 3), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </form>
            </div>
        </div>
    </div>