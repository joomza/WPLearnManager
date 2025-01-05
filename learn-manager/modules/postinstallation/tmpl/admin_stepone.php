<?php
$date_format = array((object) array('id' => 'd-m-Y', 'text' => __('DD MM YYYY', 'learn-manager')),
            (object) array('id' => 'm/d/Y', 'text' => __('MM DD YYYY', 'learn-manager')),
            (object) array('id' => 'Y-m-d', 'text' => __('YYYY MM DD', 'learn-manager')));
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'learn-manager')),
        (object) array('id' => 0, 'text' => __('No', 'learn-manager')));
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
                <li class="header-parts first-part active">
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
            <span class="heading-post-ins"><?php echo __('Site Settings','learn-manager');?></span>
            <div class="post-installtion-content">
                <form id="jslearnmanager-form-ins" method="post" action="<?php echo admin_url("admin.php?page=jslm_postinstallation&task=save&action=jslmstask"); ?>">
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Title','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::text('title',jslearnmanager::$_data[0]['title'], array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"> </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('System slug','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::text('system_slug',jslearnmanager::$_data[0]['system_slug'], array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Default page');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('default_pageid', JSLEARNMANAGERincluder::getJSModel('postinstallation')->getPageList(),jslearnmanager::$_data[0]['default_pageid'],__('Select default page', 'learn-manager'),array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('Select Learn Manager default page, on action system will redirect on selected page','learn-manager');?>.</div>
                        <div class="desc"><?php echo __('If not select default page, email links and support icon might not work','learn-manager');?>. </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Data directory','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::text('data_directory',jslearnmanager::$_data[0]['data_directory'], array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('System will upload all user files in this folder','learn-manager');?> </div>
                        <div class="desc"><?php echo jslearnmanager::$_path.jslearnmanager::$_data[0]['data_directory'];?> </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Admin email address','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::text('adminemailaddress',jslearnmanager::$_data[0]['adminemailaddress'], array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('Admin will receive email notifications on this address','learn-manager');?> </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('System email address','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::text('mailfromaddress',jslearnmanager::$_data[0]['mailfromaddress'], array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('Email address that will be used to send emails','learn-manager');?> </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Email from name','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::text('mailfromname',jslearnmanager::$_data[0]['mailfromname'], array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('Sender name that will be used in emails','learn-manager');?> </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Show breadcrumbs','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('cur_location', $yesno,jslearnmanager::$_data[0]['cur_location'],__('Select breadcrumbs', 'learn-manager'),array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('Show navigation in breadcrumbs','learn-manager');?> </div>
                    </div>
                    <div class="pic-config">
                        <div class="title">
                            <?php echo __('Default date format','learn-manager');?>:
                        </div>
                        <div class="field">
                            <?php echo wp_kses(JSLEARNMANAGERformfield::select('date_format', $date_format,jslearnmanager::$_data[0]['date_format'],__('Select date format', 'learn-manager'),array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        </div>
                        <div class="desc"><?php echo __('Date format for plugin','learn-manager');?> </div>
                    </div>
                    <div class="pic-button-part">
                        <a class="next-step full-width" href="#" onclick="document.getElementById('jslearnmanager-form-ins').submit();" >
                            <?php echo __('Next','learn-manager'); ?>
                            <i class=" fa fa-long-arrow-right"></i>
                        </a>
                    </div>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'postinstallation_save'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('step', 1), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </form>
            </div>
        </div>
    </div>

</div>
