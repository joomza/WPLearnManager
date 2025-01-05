<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <?php 
        $msgkey = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getMessagekey();
        JSLEARNMANAGERmessages::getLayoutMessage($msgkey); 
        
        $specclass = "jslm_title";
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
                        <li><?php echo __('Email Templates Options','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Email Templates Options ', 'learn-manager'); ?></span>
        </div>            
    <div id="jslms-data-wrp">
       <table class="jslm_adminlist" border="0" id="jslm_js-table">
            <thead>
                <tr>
                    <th width="40%" class="jslm_title"><?php echo __('Title' , 'learn-manager'); ?></th>
                    <th width="20%" class="jslm_center"><?php echo __('Admin' , 'learn-manager'); ?></th>
                    <th width="20%" class="jslm_center"><?php echo __('Instructor' , 'learn-manager'); ?></th>
                    <th width="20%" class="jslm_center"><?php echo __('Student' , 'learn-manager'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="jslm_section-header" ><?php echo __('Course', 'learn-manager'); ?></td>
                </tr>       
                <tr class="<?php echo esc_html($specclass); ?>">
                    <?php
                    $lang = __('Add new course', 'learn-manager');
                    ?>              
                    <td class="jslm_left-row"><?php echo esc_html($lang); ?></td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['add_new_course']['admin'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_course']['tempid'].'&actionfor=1')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_course']['tempid'].'&actionfor=1')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['add_new_course']['instructor'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_course']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_course']['tempid'].'&actionfor=2'); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td> - </td>
                </tr>           
                <tr class="<?php echo esc_attr($specclass); ?>">
                    <?php
                    $lang = __('Delete Course' , 'learn-manager');
                    ?>
                    <td class="jslm_left-row"><?php echo esc_html($lang); ?></td>
                    <td> - </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['delete_course']['instructor'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['delete_course']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['delete_course']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td> - </td>
                </tr>
                <tr class="<?php echo esc_attr($specclass); ?>">
                    <?php
                    $lang = __('Featured Course Status','learn-manager');
                    ?>
                    <td class="jslm_left-row"><?php echo esc_html($lang); ?></td>
                    <td> - </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['course_status']['instructor'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['course_status']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['course_status']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>-</td>
                </tr>
                <tr class="<?php echo esc_attr($specclass); ?>">
                    <?php
                    $lang = __('Course Status','learn-manager');
                    ?>
                    <td class="jslm_left-row"><?php echo esc_html($lang); ?></td>
                    <td> - </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['feature_course_status']['instructor'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['feature_course_status']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['feature_course_status']['tempid'].'&actionfor=2'); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>-</td>
                </tr>
                <tr>
                    <td colspan="4" class="jslm_section-header"><?php echo __('Enrollment', 'learn-manager'); ?></td>
                </tr>
                <tr class="<?php echo esc_attr($specclass); ?>">
                    <?php
                    $lang = __('New Enrollment','learn-manager');
                    ?>
                    <td class="jslm_left-row"><?php echo esc_html($lang); ?></td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['course_enrollment']['admin'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['course_enrollment']['tempid'].'&actionfor=1')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['course_enrollment']['tempid'].'&actionfor=1')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['course_enrollment']['instructor'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['course_enrollment']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['course_enrollment']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['course_enrollment']['student'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['course_enrollment']['tempid'].'&actionfor=3')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['course_enrollment']['tempid'].'&actionfor=3')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="jslm_section-header" ><?php echo __('Payouts', 'learn-manager'); ?></td>
                </tr>
                <tr class="<?php echo esc_attr($specclass); ?>">
                    <?php
                    $lang = __('Payouts','learn-manager');
                    ?>
                    <td class="jslm_left-row"><?php echo esc_html($lang); ?></td>
                    <td><?php if (jslearnmanager::$_data[0]['payout']['admin'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['payout']['tempid'].'&actionfor=1')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['payout']['tempid'].'&actionfor=1'); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['payout']['instructor'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['payout']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['payout']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>-</td>
                </tr>
                <tr>
                    <td colspan="4" class="jslm_section-header" ><?php echo __('Miscellaneous', 'learn-manager'); ?></td>
                </tr>
                <tr class="<?php echo esc_attr($specclass); ?>">
                    <?php
                    $lang = __('Register New User','learn-manager');
                    ?>
                    <td class="jslm_left-row"><?php echo esc_html($lang); ?></td>
                    <td><?php if (jslearnmanager::$_data[0]['add_new_user']['admin'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_user']['tempid'].'&actionfor=1')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_user']['tempid'].'&actionfor=1')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['add_new_user']['instructor'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_user']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_user']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['add_new_user']['student'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_user']['tempid'].'&actionfor=3')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['add_new_user']['tempid'].'&actionfor=3')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                </tr>
                <tr class="<?php echo esc_attr($specclass); ?>">
                    <?php
                    $lang = __('Message to sender','learn-manager');
                    ?>
                    <td class="jslm_left-row"><?php echo esc_html($lang); ?></td>
                    <td>-</td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['message']['instructor'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['message']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['message']['tempid'].'&actionfor=2')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (jslearnmanager::$_data[0]['message']['student'] == 1) { ?> 
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=noSendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['message']['tempid'].'&actionfor=3')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'learn-manager'); ?>" /></a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_emailtemplatestatus&task=sendEmail&action=jslmstask&jslearnmanagerid='.jslearnmanager::$_data[0]['message']['tempid'].'&actionfor=3')); ?>">
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Do not send email', 'learn-manager'); ?>" /></a>
                        <?php } ?>
                    </td>
                </tr>
            </tbody>
         </table>
       </div>
    </div>
</div>
