<?php
delete_option('jslm_addon_install_data');
delete_option( 'jslm_addon_install_data_actual_transaction_key' );
?>
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
                    <div class="jslm-addon-installer-wrapper" >
                        <form id="jslearnfrom" action="<?php echo admin_url('admin.php?page=jslm_premiumplugin&task=verifytransactionkey&action=jslmstask'); ?>" method="post">
                            <div class="jslm-addon-installer-left-section-wrap" >
                                <div class="jslm-addon-installer-left-image-wrap" >
                                    <img class="jslm-addon-installer-left-image" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/addon-images/addon-installer-logo.png" />
                                </div>
                                <div class="jslm-addon-installer-left-title" >
                                    <?php echo __("Wordpress Plugin","learn-manager"); ?>
                                </div>
                                <div class="jslm-addon-installer-left-description" >
                                    <?php echo __("JS Learn Manager is extensive, featured rich and comprehensive learning management system for WordPress. JS Learn Manager comes with a lots of features like course list, course search with many filters, create course, create lectures, Add Quizzes, take lectures, enrollment, shortlist courses, Messaging, Social logins, Social sharing, Awards and many more.","learn-manager"); ?>
                                </div>
                            </div>
                            <div class="jslm-addon-installer-right-section-wrap" >
                                <div class="jslm-addon-installer-right-heading" >
                                    <?php echo __("JS Learn Manager Addon Installer","learn-manager"); ?>
                                </div>
                                <div class="jslm-addon-installer-right-description" >
                                    <a href="?page=jslm_premiumplugin&jslmslay=addonfeatures" class="jslm-addon-installer-addon-list-link" >
                                        <?php echo __("Add on list","learn-manager"); ?>
                                    </a>
                                </div>
                                <div class="jslm-addon-installer-right-key-section" >
                                    <div class="jslm-addon-installer-right-key-label" >
                                        <?php echo __("Please Insert Your Transaction key","learn-manager"); ?>.
                                    </div>

                                    <?php
                                    $error_message = '';
                                    $transactionkey = '';
                                    if(get_option('jslm_addon_return_data','') != ''){
                                        if(get_option('jslm_addon_return_data_status',0) == 0){
                                            $error_message = get_option('jslm_addon_return_data_message');
                                            $transactionkey = get_option('jslm_addon_return_data_transactionkey');
                                        }
                                        delete_option( 'jslm_addon_return_data' );
                                        delete_option( 'jslm_addon_return_data_status' );
                                        delete_option( 'jslm_addon_return_data_message' );
                                        delete_option( 'jslm_addon_return_data_transactionkey' );
                                    }

                                    ?>
                                    <div class="jslm-addon-installer-right-key-field" >
                                        <input type="text" name="transactionkey" id="transactionkey" class="jsvm_key_field" value="<?php echo esc_attr($transactionkey);?>" placeholder="<?php echo esc_attr(__('Transaction key','js-support-ticket')); ?>"/>
                                        <?php if($error_message != '' ){ ?>
                                            <div class="jslm-addon-installer-right-key-field-message" > <img alt="image" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/addon-images/error.png" /> <?php echo esc_html($error_message);?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="jslm-addon-installer-right-key-button" >
                                        <button type="submit" class="jsvm_btn" role="submit" onclick="jsShowLoading();"><?php echo esc_html(__("Proceed","learn-manager")); ?></button>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery('#jslearnfrom').on('submit', function() {
            jsShowLoading();
        });
    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#jstran_loading').show();
    }
</script>
