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
                        <li><?php echo __('Premium Add ons','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Premium Add ons', 'learn-manager'); ?></span>
        </div>            
 <div id="jslms-data-wrp">

        <div id="jslearnmanager-content">
            <div id="black_wrapper_translation"></div>
            <div id="jslm_jstran_loading">
                <img alt="image" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/spinning-wheel.gif" />
            </div>
            <div id="jslm-lower-wrapper">
                <div class="jslm-addon-installer-wrapper step3" >
                    <div class="jslm-addon-installer-left-image-wrap" >
                        <img class="jslm-addon-installer-left-image" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/addon-images/addon-installer-logo.png" />
                    </div>
                    <div class="jslm-addon-installer-left-heading" >
                        <?php echo __("Add ons installed and activated successfully","learn-manager"); ?>
                    </div>
                    <div class="jslm-addon-installer-left-description" >
                        <?php echo __("Add ons for WP Learn Manager have been installed and activated successfully. ","learn-manager"); ?>
                    </div>
                    <div class="jslm-addon-installer-right-button" >
                        <a class="jsst_btn" href="?page=jslearnmanager" ><?php echo __("Control Panel","learn-manager"); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
