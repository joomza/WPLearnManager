<div class="jslm_main-up-wrapper">
<?php if (!defined('ABSPATH')) die('Restricted Access');
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
$msgkey = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
?>
<?php
    $show_social = false;
    $config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('login');
    if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
        if ($config_array['loginwithfacebook'] == 1) {
            $show_social = true;
        }
        if ($config_array['loginwithlinkedin'] == 1) {
            $show_social = true;
        }
        if($config_array['loginwithgoogle'] == 1){
            $show_social = true;
        }
    } ?>

<div class="jslm_content_wrapper">
    <div class="jslm_content_data">
        <div class="jslm_search_content no-border">
            <div class="jslm_top_title">
                <div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Login","learn-manager"); ?></h3></div>
            </div>
        </div>
        <?php
        if (jslearnmanager::$_error_flag_message == null) { ?>
            <div class="jslm_data_container">
                <div class="jslm_login_wrapper">
                    <div class="jslm_login_left">
                        <h2 class="jslm_login_heading">
                            <?php echo __("Login","learn-manager"); ?>
                        </h2>
                        <form id="loginform-custom" name="loginform-custom" action="<?php echo site_url('wp-login.php'); ?>" method="post">
                            <div class="jslm_input_field">
                                <input class="jslm_input_field_style" type="text" id="user_login" name="log" placeholder="<?php echo  __("Username","learn-manager"); ?>" >
                            </div>
                            <div class="jslm_input_field">
                                <input class="jslm_input_field_style" id="user_pass" type="password" name="pwd" size="20" placeholder="<?php echo __("Password","learn-manager"); ?>" >
                            </div>
                            <div class="jslm_login_button">
                                <input class="jslm_button_style" type="hidden" name="redirect_to" value="<?php echo esc_attr(jslearnmanager::$_data[0]['redirect_url']); ?>">
                                <input class="jslm_button_style" type="submit" id="wp-submit" name="wp-submit" value="<?php echo __("Login","learn-manager"); ?>">
                            </div>
                            <div class="jslm_input_field jslm_left_align">
                                <input class="jslm_checkbox" name="rememberme" id="rememberme" value="forever" type="checkbox"><?php echo __("Keep me login","learn-manager"); ?>
                            </div>
                        </form>
                        <a class="jslm_forgot_password" href="<?php echo esc_url(site_url('wp-login.php?action=lostpassword')); ?>"><?php echo  __("Forget Your Password?","learn-manager"); ?></a>
                    </div>
                    <?php if($show_social ==  true){
                        do_action("jslm_sociallogin_get_sociallogin_access_html");
                    }?>
                </div>
            </div>
        <?php }else{
            echo jslearnmanager::$_error_flag_message;
        } ?>
    </div>
</div>
</div>
