<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.validate();
    });
</script>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
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
                        <li><?php echo __('Add New Language','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Add New Language ', 'learn-manager'); ?></span>
            <?php 
            // $msg = isset(jslearnmanager::$_data[0]) ? __('Edit', 'learn-manager') : __('Add New','learn-manager'); ?>
            <?php
             // echo esc_html($msg) . ' ' . __('Language', 'learn-manager');
              ?>

        </div>            
 <div id="jslms-data-wrp">
   <div class="jslearnmanager-form-wrap">
        <form id="jslearnmanager-form" method="post" action="<?php echo esc_attr(admin_url("admin.php?page=jslm_language&action=jslmstask&task=savelanguage")); ?>">
            <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Title', 'learn-manager'); ?><font class="jslm_required-notifier">*</font></div>
                <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text('language', isset(jslearnmanager::$_data[0]->language) ? jslearnmanager::$_data[0]->language : '', array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required ')), JSLEARNMANAGER_ALLOWED_TAGS) ?></div>
            </div>
            <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Published', 'learn-manager'); ?></div>
                <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::radiobutton('status', array('1' => __('Yes', 'learn-manager'), '0' => __('No', 'learn-manager')), isset(jslearnmanager::$_data[0]->status) ? jslearnmanager::$_data[0]->status : 1, array('class' => 'jslm_radiobutton')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
            </div>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', isset(jslearnmanager::$_data[0]->id) ? jslearnmanager::$_data[0]->id : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('created_at', isset(jslearnmanager::$_data[0]->created_at) ? jslearnmanager::$_data[0]->created_at : date('Y-m-d')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <div class="jslm_js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
                <div class="jslm_js-button-container">
                    <a id="jslm_form-cancel-button" href="<?php echo admin_url('admin.php?page=jslm_language'); ?>" ><?php echo __('Cancel', 'learn-manager'); ?></a>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save','learn-manager') .' '. __('Language', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </div>
            </div>
        </form>
       </div>
      </div>
    </div>
</div>
