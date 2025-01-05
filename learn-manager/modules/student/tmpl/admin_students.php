<?php if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('student')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
$msgkey = JSLEARNMANAGERincluder::getJSModel('user')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("span#showhidefilter").click(function (e) {
            e.preventDefault();
            var img2 = "<?php echo JSLEARNMANAGER_PLUGIN_URL."includes/images/filter-up.png"; ?>";
            var img1 = "<?php echo JSLEARNMANAGER_PLUGIN_URL."includes/images/filter-down.png"; ?>";
            if (jQuery('.default-hidden').is(':visible')) {
                jQuery(this).find('img').attr('src', img1);
            } else {
                jQuery(this).find('img').attr('src', img2);
            }
            jQuery(".default-hidden").toggle();
            var height = jQuery(this).height();
            var imgheight = jQuery(this).find('img').height();
            var currenttop = (height - imgheight) / 2;
            jQuery(this).find('img').css('top', currenttop);
        });
    });
    function resetFrom() {
        document.getElementById('studentname').value = '';
        document.getElementById('studentemail').value = '';
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
                        <li><?php echo __('Students','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Students', 'learn-manager'); ?></span>
        </div>            
    <div id="jslms-data-wrp">
      <form class="jslm_js-filter-form" name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_student&jslmslay=students"); ?>">
        <?php echo wp_kses(JSLEARNMANAGERformfield::text('studentname', jslearnmanager::$_data['filter']['studentname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::text('studentemail', jslearnmanager::$_data['filter']['studentemail'], array('class' => 'inputbox', 'placeholder' => __('Email Address', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <div class="filterbutton">
            <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Search', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::button('reset', __('Reset', 'learn-manager', 'learn-manager', 'learn-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </div>
    </form>
    <hr class="listing-hr" />
    <?php
    if (!empty(jslearnmanager::$_data[0])) {
        $wpdir = wp_upload_dir();
        foreach (jslearnmanager::$_data[0] AS $student) {
           ?>
            <div id="instructor_<?php echo esc_attr($student->id); ?>" class="instructor-container instructor-container-margin-bottom js-col-lg-12 js-col-md-12 no-padding">
                <div id="item-data" class="item-data item-data-resume js-row no-margin">
                    <!-- <span id="selector_<?php echo esc_attr($student->id); ?>" class="selector">
                        <input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($student->id); ?>);" class="jslearnmanager-cb" id="jslearnmanager-cb" name="jslearnmanager-cb[]" value="<?php echo esc_attr($student->id); ?>" />
                    </span> -->
                    <div class="item-icon js_circle">
                        <div class="profile">
                            <a class="js-anchor" href="<?php echo esc_url(admin_url('admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid='.$student->id)); ?>">
                            <span class="js-border">
                               <?php if($student->image !='' && $student->image != null){
                                    $imageadd = $student->image;
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
                                <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid='.$student->id)); ?>" ><?php echo esc_html($student->name); ?></a>
                            </span>
                            <div class="flag-and-type">
                                <span class="flag approved"><?php echo __('Approved', 'learn-manager'); ?></span>
                            </div>
                        </div>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Email', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($student->email);?></span>
                        </div>
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Gender', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($student->gender);?></span>
                        </div>
                       
                        <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Location', 'learn-manager') . ': '; ?></span><span class="value"><?php echo esc_html($student->location); ?></span>
                        </div>
                    </div>
                </div>
                <div id="item-actions" class="item-actions js-row no-margin">
                    <div class="item-text-block js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding instructor-layout-id">
                        <span class="heading"><?php echo __('Student ID', 'learn-manager') . ': '; ?></span><span class="item-action-text"><?php echo esc_html($student->id); ?></span>
                    </div>
                    <div class="item-values js-col-lg-7 js-col-md-7 js-col-xs-12 no-padding">
                        <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_student&action=jslmstask&task=enforceremove&jslearnmanager-cb[]='.$student->uid),'delete-student')); ?>" onclick="return confirm('<?php echo __('This will delete every thing about this record with user information','learn-manager').'. '.__('Are you sure to delete','learn-manager').'?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/force-delete.png" /><?php echo __('Enforce Delete', 'learn-manager') ?></a>
                        <a class="js-action-link button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=jslm_student&action=jslmstask&task=remove&jslearnmanager-cb[]='.$student->uid),'delete-student'); ?>" onclick="return confirm('<?php echo __('This will delete every thing about this user','learn-manager').'. '.__('Are you sure to delete','learn-manager').'?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete.png" /><?php echo __('Delete user data', 'learn-manager') ?></a>
                        <a class="js-action-link button" href="<?php echo esc_url(admin_url('admin.php?page=jslm_user&jslmslay=editprofileform&jslearnmanagerid='.$student->uid)); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/edit.png" /><?php echo __('Edit Profile', 'learn-manager'); ?></a>
                        <a class="js-action-link button" href="<?php echo admin_url('admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid='.$student->id); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/view-detail.png" /><?php echo __('View Detail', 'learn-manager'); ?></a>
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
</div>
