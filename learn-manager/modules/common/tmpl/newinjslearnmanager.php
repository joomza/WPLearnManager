<div class="jslm_main-up-wrapper">
<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
    $msgkey = JSLEARNMANAGERincluder::getJSModel('common')->getMessagekey();
    JSLEARNMANAGERMessages::getLayoutMessage($msgkey);
    JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
    include_once(JSLEARNMANAGER_PLUGIN_PATH .'includes/header.php');
if (jslearnmanager::$_error_flag == null) {
    $module = JSLEARNMANAGERrequest::getVar('jslmsmod');
    $layout = JSLEARNMANAGERrequest::getVar('jslmslay');
    $issociallogin = apply_filters("jslm_sociallogin_check_social_loggedin",false);
    if ($issociallogin == true) {
        $uid = sanitize_key($_SESSION['jslearnmanager-socialid']);
    } else {
        $currentuser = get_userdata(get_current_user_id());
        $uid = $currentuser->ID;
    }

    $title = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('title');
    ?>
    <div class="jslm_content_wrapper">
        <div class="page_heading"><?php echo esc_html(__( $title , 'learn-manager')); ?></div>
        <form class="js-ticket-form" id="coverletter_form" method="post" action="<?php echo jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'task'=>'savenewinjslearnmanager')); ?>">
            <div class="js-form-wrapper-newlogin">
                <div class="js-imagearea">
                    <div class="js-img">
                        <img id="jslmslogin" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/man-icon.png">
                    </div>
                </div>
                <div class="js-dataarea">
                    <div class="js-col-md-12 js-form-heading"><?php echo __('Are you new in', 'learn-manager').' '.__( $title,'learn-manager'); ?></div>
                    <div class="js-col-md-12 js-form-title"><?php echo __('Please select your role', 'learn-manager'); ?>&nbsp;<font color="red">*</font></div>
                    <div class="js-col-md-12 js-form-value">
                        <?php echo wp_kses(JSLEARNMANAGERformfield::select('roleid', JSLEARNMANAGERincluder::getJSModel('userrole')->getRoleforCombo(), '', __('Select Role'), array('class' => 'inputbox', 'data-validation' => 'required')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    </div>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('desired_module', $module), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('desired_layout', $layout), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('issociallogin', $issociallogin), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('uid', $uid), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'common_savenewinjslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslearnmanagerpageid', get_the_ID()), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Submit', 'learn-manager'), array('class' => 'button jslms-newsubmit')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </div>

            </div>
        </form>
    </div>
<?php
}else{
    echo jslearnmanager::$_error_flag_message;
}
?>
</div>
