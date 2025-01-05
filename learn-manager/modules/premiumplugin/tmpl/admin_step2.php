 <?php
$allPlugins = get_plugins(); // associative array of all installed plugins

$addon_array = array();
foreach ($allPlugins as $key => $value) {
    $addon_index = explode('/', $key);
    $addon_array[] = $addon_index[0];
}
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
                    <form id="jslearnfrom" action="<?php echo admin_url('admin.php?page=jslm_premiumplugin&task=downloadandinstalladdons&action=jslmstask'); ?>" method="post">
                        <div class="jslm-addon-installer-left-section-wrap" >
                            <div class="jslm-addon-installer-left-image-wrap" >
                                <img class="jslm-addon-installer-left-image" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/addon-images/addon-installer-logo.png" />
                            </div>
                            <div class="jslm-addon-installer-left-title" >
                                <?php echo __("Wordpress Plugin","learn-manager"); ?>
                            </div>
                            <div class="jslm-addon-installer-left-description" >
                                <?php echo __("WP Learn Manager is extensive, featured rich and comprehensive learning management system for WordPress. WP Learn Manager comes with a lots of features like course list, course search with many filters, create course, create lectures, Add Quizzes, take lectures, enrollment, shortlist courses, Messaging, Social logins, Social sharing, Awards and many more.","learn-manager"); ?>
                            </div>
                        </div>
                        <div class="jslm-addon-installer-right-section-wrap step2" >
                            <div class="jslm-addon-installer-right-heading" >
                                <?php echo __("WP Learn Manager Addon Installer","learn-manager"); ?>
                            </div>
                            <div class="jslm-addon-installer-right-addon-wrapper" >
                                <?php
                                $error_message = '';
                                if(get_option('jslm_addon_install_data', 1) != 1){
                                    $result = get_option('jslm_addon_install_data');
                                    if(isset($result['status']) && $result['status'] == 1){?>
                                        <div class="jslm-addon-installer-right-addon-title">
                                            <?php echo __("Select Addons for download","learn-manager"); ?>
                                        </div>
                                        <div class="jslm-addon-installer-right-addon-section" >
                                            <?php
                                            if(!empty($result['data'])){
                                                $addon_availble_count = 0;
                                                foreach ($result['data'] as $key => $value) {
                                                    if(!in_array($key, $addon_array)){
                                                        $addon_availble_count++;
                                                        $addon_slug_array = explode('-', $key);
                                                        $addon_image_name = $addon_slug_array[count($addon_slug_array) - 1];
                                                        $addon_slug = str_replace('-', '', $key);

                                                        $addon_img_path = '';
                                                        $addon_img_path = JSLEARNMANAGER_PLUGIN_URL.'includes/images/add-on-list/';
                                                        if($value['status'] == 1){ ?>
                                                            <div class="jslm-addon-installer-right-addon-single" >
                                                                <img class="jslm-addon-installer-right-addon-image" data-addon-name="<?php echo esc_attr($key); ?>" src="<?php echo esc_url($addon_img_path.$addon_image_name.'.png');?>" />
                                                                <div class="jslm-addon-installer-right-addon-name" >
                                                                    <?php echo esc_html($value['title']);?>
                                                                </div>
                                                                <input type="checkbox" class="jslm-addon-installer-right-addon-single-checkbox" id="addon-<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="1">
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                if($addon_availble_count == 0){ // all allowed addon are already installed
                                                    $error_message = __('All allowed add ons are already installed','learn-manager').'.';
                                                }
                                            }else{ // no addon returend
                                                $error_message = __('You are not allowed to install any add on','learn-manager').'.';
                                            }
                                            if($error_message != ''){
                                                $url = admin_url("admin.php?page=jslm_premiumplugin&jstlay=step1");

                                                echo '<div class="jslm-addon-go-back-messsage-wrap">';
                                                echo '<h1>';
                                                echo esc_html($error_message);
                                                echo '</h1>';

                                                echo '<a class="jslm-addon-go-back-link" href="'.$url.'">';
                                                echo __('Back','learn-manager');
                                                echo '</a>';
                                                echo '</div>';
                                            }
                                             ?>
                                        </div>
                                        <?php if($error_message == ''){ ?>
                                            <div class="jslm-addon-installer-right-addon-bottom" >
                                                <label for="jslm-addon-installer-right-addon-checkall-checkbox"><input type="checkbox" class="jslm-addon-installer-right-addon-checkall-checkbox" id="jslm-addon-installer-right-addon-checkall-checkbox"><?php echo __("Select All Addons","learn-manager"); ?></label>
                                            </div>
                                        <?php
                                        }
                                    }
                                }else{
                                    $error_message = __('Somthing Went Wrong','learn-manager').'!';
                                    $url = admin_url("admin.php?page=jslm_premiumplugin&jstlay=step1");

                                    echo '<div class="jslm-addon-go-back-messsage-wrap">';
                                    echo '<h1>';
                                    echo esc_html($error_message);
                                    echo '</h1>';

                                    echo '<a class="jslm-addon-go-back-link" href="'.$url.'">';
                                    echo __('Back','learn-manager');
                                    echo '</a>';
                                    echo '</div>';
                                }

                                 ?>
                            </div>
                            <?php if($error_message == ''){ ?>
                                <div class="jslm-addon-installer-right-button" >
                                    <button type="submit" class="jslm_btn" role="submit" onclick="jsShowLoading();"><?php echo __("Proceed","learn-manager"); ?></button>
                                </div>
                            <?php } ?>
                        </div>
                        <input type="hidden" name="token" value="<?php echo esc_attr($result['token']); ?>"/>
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

        jQuery('.jslm-addon-installer-right-addon-image').on('click', function() {
            var addon_name = jQuery(this).attr('data-addon-name')
            var prop_checked = jQuery("#addon-"+addon_name).prop("checked");
            if(prop_checked){
                jQuery("#addon-"+addon_name).prop("checked", false);
            }else{
                jQuery("#addon-"+addon_name).prop("checked", true);
            }
        });
        // to handle select all check box.
        jQuery('#jslm-addon-installer-right-addon-checkall-checkbox').change(function() {
           jQuery(".jslm-addon-installer-right-addon-single-checkbox").prop("checked", this.checked);
       });


    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#jslm_jstran_loading').show();
    }
</script>
<?php
if(isset($_SESSION['jslm_addon_install_data'])){// to avoid to show data on refresh
    unset($_SESSION['jslm_addon_install_data']);
}
?>
