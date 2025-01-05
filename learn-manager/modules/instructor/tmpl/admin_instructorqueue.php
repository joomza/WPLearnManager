<?php if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('instructor')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
?>
<script type="text/javascript">
    function resetFrom() {
        document.getElementById('instructorname').value = '';
        document.getElementById('jslearnmanagerform').submit();
    }
</script>
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
                            <?php if(JSLEARNMANAGERrequest::getVar('for') == 0){
                                $approvalstatus = "Approval Queue";
                            }else{
                                $approvalstatus = "Rejected Queue";
                            } ?>
                        <li><?php echo esc_html(__($approvalstatus,'js-support-ticket')); ?></li>
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
            <?php if(JSLEARNMANAGERrequest::getVar('for') == 0){
                    $approvalstatus = "Approval Queue";
                }else{
                    $approvalstatus = "Rejected Queue";
                } ?>
                <span class="jslm_heading-dashboard"><?php echo esc_html(__($approvalstatus, 'learn-manager')) ?></span> 
        </div>            
   <div id="jslms-data-wrp">
    <form class="jslm_js-filter-form" name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_instructor&jslmslay=instructors&jslmslay=instructorqueue"); ?>">
        <?php echo wp_kses(JSLEARNMANAGERformfield::text('instructorname', jslearnmanager::$_data['filter']['instructorname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::text('instructoremail', jslearnmanager::$_data['filter']['instructoremail'], array('class' => 'inputbox', 'placeholder' => __('Email Address', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <div class="filterbutton">
            <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Search', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::button('reset', __('Reset', 'learn-manager', 'learn-manager', 'learn-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </div>
    </form>
    <hr class="listing-hr" />
    <?php
    if (!empty(jslearnmanager::$_data[0])) {
        foreach (jslearnmanager::$_data[0] AS $instructor) { ?>
            <div id="instructor_<?php echo esc_attr($instructor->id); ?>" class="instructor-container instructor-container-margin-bottom js-col-lg-12 js-col-md-12 no-padding">
                <div id="item-data" class="item-data item-data-resume js-row no-margin">
                    <div class="item-icon js_circle">
                        <div class="profile">
                            <a class="js-anchor" href="<?php echo esc_url(admin_url('admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid='.$instructor->id)); ?>">
                            <span class="js-border">
                            <?php if($instructor->image !='' && $instructor->image != null){
                                $imageadd = $instructor->image;
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
                            <span class="value title-text-instructor">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid='.$instructor->id)); ?>" ><?php echo esc_html($instructor->name); ?></a>
                                <span class="role-student">
                                    <?php 
                                        echo __('Instructor','learn-manager');   
                                    ?>
                                </span>
                            </span>
                            <div class="flag-and-type">
                                <?php
                                    if ($instructor->approvalstatus == 0) {
                                        echo '<span class="flag pending">' . __('Pending for approval', 'learn-manager') . '</span>';
                                    }elseif ($instructor->approvalstatus == -1) {
                                        echo '<span class="flag rejected">' . __('Rejected', 'learn-manager') . '</span>';
                                    }
                                ?> 
                            
                            </div>
                        </div>
                        <?php if($instructor->user_role_id == 2){ ?>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Email', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($instructor->email);?></span>
                        </div>
                        <?php }?>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Gender', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($instructor->gender);?></span>
                        </div>
                       
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Location', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($instructor->location); ?></span>
                        </div>
                    </div>
                </div>
                <div id="item-actions" class="item-actions js-row no-margin">
                    <div class="item-text-block js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding instructor-layout-id">
                        <span class="heading"><?php echo __('Instructor ID', 'learn-manager') . ': '; ?></span><span class="item-action-text"><?php echo esc_html($instructor->id); ?></span>
                    </div>
                    <div class="item-values js-col-lg-7 js-col-md-7 js-col-xs-12 no-padding">
                        <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_instructor&action=jslmstask&task=enforceremove&jslearnmanager-cb[]='.$instructor->uid.'&call_from=2'),'delete-instructor')); ?>" onclick="return confirm('<?php echo __('This will delete every thing about this record','learn-manager').'. '.__('Are you sure to delete','learn-manager').'?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/force-delete.png" /><?php echo __('Enforce Delete', 'learn-manager') ?></a>
                        <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_instructor&action=jslmstask&task=remove&jslearnmanager-cb[]='.$instructor->uid.'&call_from=2'),'delete-instructor')); ?>" onclick="return confirm('<?php echo __('This will delete every thing about this record','learn-manager').'. '.__('Are you sure to delete','learn-manager').'?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete.png" /><?php echo __('Delete', 'learn-manager') ?></a>
                        <?php if($instructor->approvalstatus == 0){ ?>
                            <a class="js-action-link button" href="<?php echo esc_url(admin_url('admin.php?page=jslm_instructor&action=jslmstask&task=reject&jslearnmanager-cb[]='.$instructor->id.'&call_from=2')); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/unpublish.png" /><?php echo __("Reject" , "learn-manager"); ?></a>
                        <?php } ?>
                        <a class="js-action-link button" href="<?php echo esc_url(admin_url('admin.php?page=jslm_instructor&action=jslmstask&task=approve&jslearnmanager-cb[]='.$instructor->id.'&jslearnmanagerpageid='.JSLEARNMANAGERrequest::getVar('pagenum').'&call_from='.JSLEARNMANAGERrequest::getVar('for'))); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/user-publish.png" /><?php echo __("Approve" , "learn-manager"); ?></a>
                    </div>
                </div>
            </div>
            <?php
        }
        if (jslearnmanager::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . jslearnmanager::$_data[1] . '</div></div>';
        }
    } else {
        $msg = __('No record found','learn-manager');
        echo JSLEARNMANAGERlayout::getNoRecordFound($msg); }
    ?>

</div>
</div>
