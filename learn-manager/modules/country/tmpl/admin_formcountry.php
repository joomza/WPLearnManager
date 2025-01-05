<?php
    if (!defined('ABSPATH')) die('Restricted Access');
 ?>
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
                        <li><?php echo __('Add New Country','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Add New Country', 'learn-manager'); ?></span>
            <?php
            // $heading = isset(jslearnmanager::$_data[0]) ? __('Edit', 'learn-manager') : __('Add New', 'learn-manager');
            // echo esc_html($heading) . '&nbsp' . __('Country', 'learn-manager');
            ?>
        </div>            
     <div id="jslms-data-wrp">
            <?php
            JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); 
            ?>
        <div class="jslearnmanager-form-wrap">
            <form id="jslearnmanager-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_country&task=savecountry"); ?>">
                <div class="jslm_js-field-wrapper js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Country Code', 'learn-manager'); ?><font class="jslm_required-notifier">*</font></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text('country_code', isset(jslearnmanager::$_data[0]->country_code) ? __(jslearnmanager::$_data[0]->country_code,'learn-manager') : '', array('class' => 'inputbox one', 'data-validation' => 'required')), JSLEARNMANAGER_ALLOWED_TAGS) ?></div>
                </div>
                <div class="jslm_js-field-wrapper js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Name', 'learn-manager'); ?><font class="jslm_required-notifier">*</font></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text('country_name', isset(jslearnmanager::$_data[0]->country_name) ? __(jslearnmanager::$_data[0]->country_name,'learn-manager') : '', array('class' => 'inputbox one', 'data-validation' => 'required')), JSLEARNMANAGER_ALLOWED_TAGS) ?></div>
                </div>
                <div class="jslm_js-field-wrapper js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Published', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::radiobutton('isenable', array('1' => __('Yes', 'learn-manager'), '0' => __('No', 'learn-manager')), isset(jslearnmanager::$_data[0]->enabled) ? jslearnmanager::$_data[0]->enabled : 1, array('class' => 'jslm_radiobutton')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', isset(jslearnmanager::$_data[0]->id) ? jslearnmanager::$_data[0]->id : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>    
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'country_savecountry'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('created_at', isset(jslearnmanager::$_data[0]->created_at) ? jslearnmanager::$_data[0]->created_at : date_i18n('Y-m-d H:i:s')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('updated_at', date_i18n('Y-m-d H:i:s')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <div class="jslm_js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
                    <a id="form-cancel-button" href="<?php echo admin_url('admin.php?page=jslm_country'); ?>" ><?php echo __('Cancel', 'learn-manager'); ?></a>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save','learn-manager') .' '. __('Country', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>
