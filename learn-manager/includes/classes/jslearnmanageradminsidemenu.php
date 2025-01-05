<?php
if (!defined('ABSPATH')) die('Restricted Access');
$c = JSLEARNMANAGERrequest::getVar('page',null,'jslearnmanager');
$layout = JSLEARNMANAGERrequest::getVar('jslmslay');
$ff = JSLEARNMANAGERrequest::getVar('ff');
$for = JSLEARNMANAGERrequest::getVar('for');
?>
<div id="jslearnmanageradmin-logo">
    <a id="js-tk-top-lefticon" title="JS Help Desk System" class="jsst-anchor" href="javascript:void(0)">
        <img alt="JS Help Desk System" src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/logo.png'; ?>">
    </a>
    <img id="jslearnmanageradmin-menu-toggle" src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/menu.png'; ?>">
</div>
<ul id="jslearnmanageradmin-menu-links" class="tree accordion jslearnmanageradmin-menu-links"  data-widget="tree">
    <li class="treeview jslm_js-divlink  <?php if( $c == 'jslm_slug' || $c == 'jslm_activitylog' || $c ==  'jslm_systemerror' || $c ==  'jslm_currency'  || ($c == 'jslearnmanager'  && $layout != 'themes' && $layout != 'shortcodes')) echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslearnmanager">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/admin.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if( $c == 'jslm_activitylog' || $c ==  'jslm_systemerror'  || ($c == 'jslearnmanager'  && $layout != 'themes')  || ($c == 'jslearnmanager'  && $layout != 'shortcodes')) echo 'jslm_lastshown'; ?>"><?php echo __('Admin' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslearnmanager' && $layout != 'themes' && $layout != 'information' && $layout != 'translations' && $layout != 'shortcodes') echo 'active'; ?>"><a href="admin.php?page=jslearnmanager" class="jslm_text"><?php echo __('Control Panel','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslearnmanager' && $layout == 'information') echo 'active'; ?>"><a href="admin.php?page=jslearnmanager&jslmslay=information" class="jslm_text"><?php echo __('Information' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_activitylog' && $layout == 'activitylogs') echo 'active'; ?>"><a href="admin.php?page=jslm_activitylog&jslmslay=activitylogs" class="jslm_text"><?php echo __('Activity Logs' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslearnmanager' && $layout == 'translations') echo 'active'; ?>"><a href="admin.php?page=jslearnmanager&jslmslay=translations" class="jslm_text"><?php echo __('Translations'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_systemerror' && $layout == 'systemerrors') echo 'active'; ?>"><a href="admin.php?page=jslm_systemerror&jslmslay=systemerrors" class="jslm_text"><?php echo __('System Errors' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_slug') echo 'active'; ?>"><a href="admin.php?page=jslm_slug" class="jslm_text"><?php echo __('Slug' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_currency') echo 'active'; ?>"><a href="admin.php?page=jslm_currency" class="jslm_text"><?php echo __('Currency' , 'learn-manager'); ?></a></li>
        </ul>
    </li>
    <li class="treeview jslm_js-divlink <?php if($c == 'jslm_course' || $c == 'jslm_courselevel' || $c == 'jslm_language' || ($c == 'jslm_fieldordering' && $ff != 3)) echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslm_course">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/course.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_course' || $c == 'jslm_courselevel' || $c == 'jslm_language' || ($c == 'jslm_fieldordering' && $ff != 3)) echo 'jslm_lastshown'; ?>"><?php echo __('Courses' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslm_course' && $layout != 'formcourse' && $layout != 'coursequeue') echo 'active'; ?>"><a href="admin.php?page=jslm_course" class="jslm_text"><?php echo __('Courses' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_course' && $layout == 'formcourse') echo 'active'; ?>"><a href="admin.php?page=jslm_course&jslmslay=formcourse" class="jslm_text"><?php echo __('Add Course' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_course' && $layout == 'coursequeue') echo 'active'; ?>"><a href="admin.php?page=jslm_course&jslmslay=coursequeue" class="jslm_text"><?php echo __('Approval Queue' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_fieldordering' && $ff == '1') echo 'active'; ?>"><a href="admin.php?page=jslm_fieldordering&ff=1" class="jslm_text"><?php echo __('Course Fields' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_fieldordering' && $ff == '2') echo 'active'; ?>"><a href="admin.php?page=jslm_fieldordering&ff=2" class="jslm_text"><?php echo __('Lecture Fields' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_courselevel') echo 'active'; ?>"><a href="admin.php?page=jslm_courselevel" class="jslm_text"><?php echo __('Course Level' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_language') echo 'active'; ?>"><a href="admin.php?page=jslm_language" class="jslm_text"><?php echo __('Language' , 'learn-manager'); ?></a></li>

        </ul>
    </li>
    <li class="treeview jslm_js-divlink <?php if($c == 'jslm_premiumplugin') echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslm_premiumplugin">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/premium_addons.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_premiumplugin') echo 'jslm_lastshown'; ?>"><?php echo __('Premium Addons' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslm_premiumplugin' && $layout != 'addonfeatures') echo 'active'; ?>"><a href="admin.php?page=jslm_premiumplugin" class="jslm_text"><?php echo __('Install Addons' , 'learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($c == 'jslm_premiumplugin' && $layout == 'addonfeatures') echo 'active'; ?>"><a href="admin.php?page=jslm_premiumplugin&jslmslay=addonfeatures" class="jslm_text"><?php echo __('Addons list' , 'learn-manager'); ?></a></li>

        </ul>
    </li>
    <li class="treeview jslm_js-divlink <?php if($c == 'jslm_configuration' || $c == 'jslm_themes') echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslm_configuration">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/configuration.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_configuration' || $c == 'jslm_themes') echo 'jslm_lastshown'; ?>"><?php echo __('Configurations' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslm_configuration' && $layout == 'configurations') echo 'active'; ?>"><a href="admin.php?page=jslm_configuration&jslmslay=configurations" class="jslm_text"><?php echo __('Configurations' , 'learn-manager'); ?></a></li>
            <?php if(in_array('themes' , jslearnmanager::$_active_addons)){ ?>
                <li class="jslm_js-child <?php if($c == 'jslm_themes') echo 'active'; ?>"><a href="admin.php?page=jslm_themes" class="jslm_text"><?php echo __('Themes' , 'learn-manager'); ?></a></li>   
            <?php }else{
                $plugininfo = checkLmsPluginInfo('learn-manager-themes/learn-manager-themes.php');
                if($plugininfo['availability'] == "1"){
                    $text = $plugininfo['text'];
                    $url = "plugins.php?s=learn-manager-themes&plugin_status=inactive";
                }elseif($plugininfo['availability'] == "0"){
                    $text = $plugininfo['text'];
                    $url = "https://wplearnmanager.com/product/themes/";
                }?>
                <li class="jslm_js-child jslm_position" href="<?php echo esc_url( $url ); ?>">
                    <span class="jslm_text">
                        <?php echo __('Themes' , 'learn-manager'); ?>
                    </span>
                </li>
                <a href="<?php echo esc_url( $url ); ?>" class="jslm_js-install-btn jslm_js-install-btn1" title="<?php echo esc_attr( $text ); ?>"><?php echo esc_html( $text ); ?></a>
            <?php } ?>
        </ul>
    </li>
    <li class="treeview jslm_js-divlink  <?php if($c == 'jslearnmanager'  && $layout == 'shortcodes') echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslearnmanager&jslmslay=shortcodes">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/short-code.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslearnmanager'  && $layout == 'shortcodes') echo 'jslm_lastshown'; ?>"><?php echo __('Short Code' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslearnmanager' && $layout == 'shortcodes') echo 'active'; ?>"><a href="admin.php?page=jslearnmanager&jslmslay=shortcodes" class="jslm_text"><?php echo __('Short Code','learn-manager'); ?></a></li>
        </ul>
    </li>
    <li class="treeview jslm_js-divlink <?php if($c == 'jslm_instructor') echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslm_instructor">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/instructor.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_instructor') echo 'jslm_lastshown'; ?>"><?php echo __('Instructors','learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslm_instructor' && $layout != 'instructorqueue') echo 'active'; ?>"><a href="admin.php?page=jslm_instructor" class="jslm_text"><?php echo __('Instructors','learn-manager'); ?></a></li>
            <li class="jslm_js-child  <?php if($c == 'jslm_instructor' && $layout == 'instructorqueue' && $for == '0') echo 'active'; ?>"><a href="admin.php?page=jslm_instructor&jslmslay=instructorqueue&for=0" class="jslm_text"><?php echo __('Approval Queue','learn-manager'); ?></a></li>
            <li class="jslm_js-child  <?php if($c == 'jslm_instructor' && $layout == 'instructorqueue' && $for == '-1') echo 'active'; ?>"><a href="admin.php?page=jslm_instructor&jslmslay=instructorqueue&for=-1" class="jslm_text"><?php echo __('Rejected Queue','learn-manager'); ?></a></li>
        </ul>
    </li>
    <li class="treeview jslm_js-divlink <?php if($c == 'jslm_student') echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslm_student">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/students.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_student') echo 'jslm_lastshown'; ?>"><?php echo __('Students' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslm_student' && $layout != 'studentqueue') echo 'active'; ?>"><a href="admin.php?page=jslm_student" class="jslm_text"><?php echo __('Students','learn-manager'); ?></a></li>
            <li class="jslm_js-child  <?php if($c == 'jslm_student' && $layout == 'studentqueue' && $for == '0') echo 'active'; ?>"><a href="admin.php?page=jslm_student&jslmslay=studentqueue&for=0" class="jslm_text"><?php echo __('Approval Queue','learn-manager'); ?></a></li>
            <li class="jslm_js-child  <?php if($c == 'jslm_student' && $layout == 'studentqueue' && $for == '-1') echo 'active'; ?>"><a href="admin.php?page=jslm_student&jslmslay=studentqueue&for=-1" class="jslm_text"><?php echo __('Rejected Queue','learn-manager'); ?></a></li>
        </ul>
    </li>
    <li class="treeview jslm_js-divlink <?php if($c == 'jslm_user' || ($c == 'jslm_fieldordering' && $ff == 3) ) echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslm_users">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/users.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_user' || ($c == 'jslm_fieldordering' && $ff == 3) ) echo 'jslm_lastshown'; ?>"><?php echo __('Users' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child  <?php if($c == 'jslm_user') echo 'active'; ?>"><a href="admin.php?page=jslm_user" class="jslm_text"><?php echo __('Users','learn-manager'); ?></a></li>
            <li class="jslm_js-child  <?php if(($c == 'jslm_fieldordering' && $ff == 3) ) echo 'active'; ?>"><a href="admin.php?page=jslm_fieldordering&ff=3" class="jslm_text"><?php echo __('User Fields' , 'learn-manager'); ?></a></li>
        </ul>
    </li>
    <?php /*do_action("jslm_addons_left_menu_full_image_link",$c); */?>
    <?php if(in_array('awards' , jslearnmanager::$_active_addons)){ ?>
        <li class="treeview jslm_js-divlink <?php if($c == 'jslm_awards') echo 'active'; ?>">
            <a class="js-icon-left" href="admin.php?page=jslm_awards">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/awards.png"/>
                <span class="jslm_text jslm_js-parent  <?php if($c == 'jslm_awards') echo 'jslm_lastshown'; ?>"><?php echo __('Awards' , 'learn-manager'); ?></span>
            </a>
            <ul class="jslm_js-innerlink treeview-menu">
                <li class="jslm_js-child <?php if($c == 'jslm_awards') echo 'active'; ?>"><a href="admin.php?page=jslm_awards" class="jslm_text"><?php echo __('Awards' , 'learn-manager'); ?></a></li>
            </ul>
        </li>
    <?php }else{
        $plugininfo = checkLmsPluginInfo('learn-manager-awards/learn-manager-awards.php');
        if($plugininfo['availability'] == "1"){
            $text = $plugininfo['text'];
            $url = "plugins.php?s=learn-manager-awards&plugin_status=inactive";
        }elseif($plugininfo['availability'] == "0"){
            $text = $plugininfo['text'];
            $url = "https://wplearnmanager.com/product/awards/";
        }?>
        <div class="disabled-menu jslm_js-divlink">
            <a class="js-icon-left" href="admin.php?page=jslm_awards">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/grey/awards.png"/>
                <span class="jslm_text jslm_js-parent disabled-menu"><?php echo __('Awards' , 'learn-manager'); ?></span>
            </a>
            <a href="<?php echo esc_url( $url ); ?>" class="jslm_js-install-btn" title="<?php echo esc_attr( $text ); ?>"><?php echo esc_html( $text ); ?></a>
        </div>
    <?php } ?>
    <!-- messages start -->
    <?php if(in_array('message' , jslearnmanager::$_active_addons)){ ?>
        <li class="treeview jslm_js-divlink <?php if($c == 'jslm_message') echo 'active'; ?>">
            <a class="js-icon-left" href="admin.php?page=jslm_message">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/messages.png"/>
                <span class="jslm_text jslm_js-parent  <?php if($c == 'jslm_message') echo 'jslm_lastshown'; ?>"><?php echo __('Message' , 'learn-manager'); ?></span>
            </a>
            <ul class="jslm_js-innerlink treeview-menu">
                <li class="jslm_js-child <?php if($c == 'jslm_message') echo 'active'; ?>"><a href="admin.php?page=jslm_message" class="jslm_text"><?php echo __('Message' , 'learn-manager'); ?></a></li>
            </ul>
        </li>
    <?php }else{
        $plugininfo = checkLmsPluginInfo('learn-manager-message/learn-manager-message.php');
        if($plugininfo['availability'] == "1"){
            $text = $plugininfo['text'];
            $url = "plugins.php?s=learn-manager-message&plugin_status=inactive";
        }elseif($plugininfo['availability'] == "0"){
            $text = $plugininfo['text'];
            $url = "https://wplearnmanager.com/product/message/";
        }?>
        <div class="disabled-menu jslm_js-divlink">
            <a class="js-icon-left" href="admin.php?page=jslm_message">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/grey/mails.png"/>
                <span class="jslm_text jslm_js-parent disabled-menu"><?php echo __('Message' , 'learn-manager'); ?></span>
            </a>
            <a href="<?php echo esc_url( $url ); ?>" class="jslm_js-install-btn" title="<?php echo esc_attr( $text ); ?>"><?php echo esc_html( $text ); ?></a>
        </div>
    <?php } ?>
    <!-- message end -->
    <!-- paidcourses start -->
    <?php if(in_array('paidcourse' , jslearnmanager::$_active_addons)){ ?>
        <div class=" treeview jslm_js-divlink <?php if($c == 'jslm_earning') echo 'active'; ?>">
            <a class="js-icon-left" href="admin.php?page=jslm_currency">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/currencies.png"/>
                <span class="jslm_text jslm_js-parent  <?php if($c == 'jslm_earning') echo 'jslm_lastshown'; ?>"><?php echo __('Paid Course' , 'learn-manager'); ?></span>
            </a>
            <ul class="jslm_js-innerlink treeview-menu">
                <li class="jslm_js-child <?php if($c == 'jslm_paidcourse') echo 'active'; ?>"><a href="admin.php?page=jslm_earning" class="jslm_text"><?php echo __('Paid Course' , 'learn-manager'); ?></a></li>
            </ul>
        </div>
    <?php }else{
        $plugininfo = checkLmsPluginInfo('learn-manager-paidcourse/learn-manager-paidcourse.php');
        if($plugininfo['availability'] == "1"){
            $text = $plugininfo['text'];
            $url = "plugins.php?s=learn-manager-paidcourse&plugin_status=inactive";
        }elseif($plugininfo['availability'] == "0"){
            $text = $plugininfo['text'];
            $url = "https://wplearnmanager.com/product/paidcourse/";
        }?>
        <div class="disabled-menu jslm_js-divlink">
            <a class="js-icon-left" href="admin.php?page=jslm_currency">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/grey/earnings.png"/>
                <span class="jslm_text jslm_js-parent disabled-menu"><?php echo __('Paid Course' , 'learn-manager'); ?></span>
            </a>
            <a href="<?php echo esc_url( $url ); ?>" class="jslm_js-install-btn" title="<?php echo esc_attr( $text ); ?>"><?php echo esc_html( $text ); ?></a>
        </div>
    <?php } ?>
    <!-- paidcourses end -->
    <!-- paymentplane start -->
    <?php if(in_array('paymentplan' , jslearnmanager::$_active_addons)){ ?>
        <li class="treeview jslm_js-divlink  <?php if($c == 'jslm_paymentplan') echo 'active'; ?>">
            <a class="js-icon-left" href="admin.php?page=jslm_paymentplan">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/payment.png"/>
                <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_paymentplan') echo 'jslm_lastshown'; ?>"><?php echo __('Payments' , 'learn-manager'); ?></span>
            </a>
            <ul class="jslm_js-innerlink treeview-menu">
                <li class="jslm_js-child <?php if($c == 'jslm_paymentplan') echo 'active'; ?>"><a href="admin.php?page=jslm_paymentplan" class="jslm_text"><?php echo __('Payment Plan' , 'learn-manager'); ?></a></li>
            </ul>
        </li>
    <?php }else{
        $plugininfo = checkLmsPluginInfo('learn-manager-paymentplan/learn-manager-paymentplan.php');
        if($plugininfo['availability'] == "1"){
            $text = $plugininfo['text'];
            $url = "plugins.php?s=learn-manager-paymentplan&plugin_status=inactive";
        }elseif($plugininfo['availability'] == "0"){
            $text = $plugininfo['text'];
            $url = "https://wplearnmanager.com/product/paymentplan/";
        }?>
        <div class="disabled-menu jslm_js-divlink">
            <a class="js-icon-left" href="admin.php?page=jslm_paymentplan">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/grey/payment.png"/>
                <span class="jslm_text jslm_js-parent disabled-menu"><?php echo __('Payments' , 'learn-manager'); ?></span>
            </a>
            <a href="<?php echo esc_url( $url ); ?>" class="jslm_js-install-btn" title="<?php echo esc_attr( $text ); ?>"><?php echo esc_html( $text ); ?></a>
        </div>
    <?php } ?>
    <!-- payment plane end -->
    <!-- payouts start -->
    <?php if(in_array('payouts' , jslearnmanager::$_active_addons)){ ?>
        <li class="treeview jslm_js-divlink  <?php if($c == 'jslm_payouts') echo 'active'; ?>">
            <a class="js-icon-left" href="admin.php?page=jslm_payouts">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/payouts.png"/>
                <span class="jslm_text jslm_js-parent  <?php if($c == 'jslm_payouts') echo 'jslm_lastshown'; ?>"><?php echo __('Payouts' , 'learn-manager');?></span>
            </a>
            <ul class="jslm_js-innerlink treeview-menu">
                <li class="jslm_js-child <?php if($c == 'jslm_payouts') echo 'active'; ?>"><a href="admin.php?page=jslm_payouts" class="jslm_text"><?php echo __('Payouts' , 'learn-manager'); ?></a></li>
            </ul>
        </li>
    <?php }else{
        $plugininfo = checkLmsPluginInfo('learn-manager-payouts/learn-manager-payouts.php');
        if($plugininfo['availability'] == "1"){
            $text = $plugininfo['text'];
            $url = "plugins.php?s=learn-manager-payouts&plugin_status=inactive";
        }elseif($plugininfo['availability'] == "0"){
            $text = $plugininfo['text'];
            $url = "https://wplearnmanager.com/product/payouts/";
        }?>
        <div class="disabled-menu jslm_js-divlink">
            <a class="js-icon-left" href="admin.php?page=jslm_payouts">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/grey/payout.png"/>
                <span class="jslm_text jslm_js-parent disabled-menu"><?php echo __('Payouts' , 'learn-manager');?></span>
            </a>
            <a href="<?php echo esc_url( $url ); ?>" class="jslm_js-install-btn" title="<?php echo esc_attr( $text ); ?>"><?php echo esc_html( $text ); ?></a>
        </div>
    <?php } ?>
    <!-- payouts end -->
    <!-- reports start -->
    <?php if(in_array('reports' , jslearnmanager::$_active_addons)){ ?>
        <li class="treeview jslm_js-divlink <?php if($c == 'jslm_reports') echo 'active'; ?>">
            <a class="js-icon-left" href="admin.php?page=jslm_reports&jslmslay=overallreports">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/reports.png"/>
                <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_reports') echo 'jslm_lastshown'; ?>"><?php echo __('Reports' , 'learn-manager'); ?></span>
            </a>
            <ul class="jslm_js-innerlink treeview-menu">
                <li class="jslm_js-child  <?php if($layout == 'overallreports') echo 'active'; ?>"><a href="admin.php?page=jslm_reports&jslmslay=overallreports" class="jslm_text"><?php echo __('Overall Reports' , 'learn-manager'); ?></a></li>
                <li class="jslm_js-child  <?php if($layout == 'instructorsforreports') echo 'active'; ?>"><a href="admin.php?page=jslm_reports&jslmslay=instructorsforreports" class="jslm_text"><?php echo __('Instructors Report' , 'learn-manager'); ?></a></li>
                <li class="jslm_js-child  <?php if($layout == 'studentsforreports') echo 'active'; ?>"><a href="admin.php?page=jslm_reports&jslmslay=studentsforreports" class="jslm_text"><?php echo __('Students Report' , 'learn-manager');?></a></li>

            </ul>
        </li>
    <?php }else{
        $plugininfo = checkLmsPluginInfo('learn-manager-reports/learn-manager-reports.php');
        if($plugininfo['availability'] == "1"){
            $text = $plugininfo['text'];
            $url = "plugins.php?s=learn-manager-reports&plugin_status=inactive";
        }elseif($plugininfo['availability'] == "0"){
            $text = $plugininfo['text'];
            $url = "https://wplearnmanager.com/product/reports/";
        }?>
        <div class="disabled-menu jslm_js-divlink <?php if($c == 'jslm_reports') echo 'active'; ?>">
            <a class="js-icon-left" href="admin.php?page=jslm_reports&jslmslay=overallreports">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/left-icons/grey/report.png"/>
                <span class="jslm_text jslm_js-parent disabled-menu"><?php echo __('Reports' , 'learn-manager'); ?></span>
            </a>
            <a href="<?php echo esc_url( $url ); ?>" class="jslm_js-install-btn" title="<?php echo esc_attr( $text ); ?>"><?php echo esc_html( $text ); ?></a>
        </div>
    <?php } ?>
    <!-- reports end -->
    <li class="treeview jslm_js-divlink <?php if($c == 'jslm_category') echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslm_category">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/categories.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_category') echo 'jslm_lastshown'; ?>"><?php echo __('Categories' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslm_category') echo 'active'; ?>"><a href="admin.php?page=jslm_category" class="jslm_text"><?php echo __('Categories' , 'learn-manager'); ?></a></li>
        </ul>
    </li>
    <li class="treeview jslm_js-divlink <?php if($c == 'jslm_emailtemplate' || $c == 'jslm_emailtemplatestatus') echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslm_emailtemplate">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/email-template.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_emailtemplate') echo 'jslm_lastshown'; ?>"><?php echo __('Email Templates','learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslm_emailtemplatestatus') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplatestatus" class="jslm_text"><?php echo __('Options','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'nw-co-a') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=nw-co-a" class="jslm_text"><?php echo __('New Course Admin','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'nw-co') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=nw-co" class="jslm_text"><?php echo __('New Course','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'co-st') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=co-st" class="jslm_text"><?php echo __('Course Status','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'de-co') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=de-co" class="jslm_text"><?php echo __('Delete Course','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'co-en-st') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=co-en-st" class="jslm_text"><?php echo __('New Enrollment Student','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'co-en-in') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=co-en-in" class="jslm_text"><?php echo __('New Enrollment Instructor','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'co-en-ad') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=co-en-ad" class="jslm_text"><?php echo __('New Enrollment Admin','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'nw-u') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=nw-u" class="jslm_text"><?php echo __('Register New User','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'nw-u-a') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=nw-u-a" class="jslm_text"><?php echo __('New User Admin','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'co-al') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=co-al" class="jslm_text"><?php echo __('Course Alert','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'fe-co-st') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=fe-co-st" class="jslm_text"><?php echo __('Featured Course Status','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 't-a-fr') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=t-a-fr" class="jslm_text"><?php echo __('Tell li Friend','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'pa-em-ad') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=pa-em-ad" class="jslm_text"><?php echo __('Payout Email Admin','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'pa-em-in') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=pa-em-in" class="jslm_text"><?php echo __('Payout Email Instructor','learn-manager'); ?></a></li>
            <li class="jslm_js-child <?php if($for == 'mg-t-sr') echo 'active'; ?>"><a href="admin.php?page=jslm_emailtemplate&for=mg-t-sr" class="jslm_text"><?php echo __('Message To Sender','learn-manager'); ?></a></li>
         </ul>
    </li>
    <li class="treeview jslm_js-divlink <?php if($c == 'jslm_country') echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jslm_country">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/countries.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_country') echo 'jslm_lastshown'; ?>"><?php echo __('Country' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslm_country') echo 'active'; ?>"><a href="admin.php?page=jslm_country" class="jslm_text"><?php echo __('Country' , 'learn-manager'); ?></a></li>
        <?php /*?>    <a class="jslm_js-child" href="admin.php?page=jslm_addressdata"><span class="jslm_text"><?php echo __('Load Address Data' , 'learn-manager'); ?></span></a> <?php  */ ?>
        </ul>
    </li>

     <li class="treeview jslm_js-divlink <?php if($c == 'jslm_help') echo 'active'; ?>">
        <a class="js-icon-left" href="admin.php?page=jjslearnmanager&jslmslay=help">
            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/left-icons/help.png'; ?>"/>
            <span class="jslm_text jslm_js-parent <?php if($c == 'jslm_help') echo 'jslm_lastshown'; ?>"><?php echo __('Help' , 'learn-manager'); ?></span>
        </a>
        <ul class="jslm_js-innerlink treeview-menu">
            <li class="jslm_js-child <?php if($c == 'jslm_help') echo 'active'; ?>"><a href="admin.php?page=jslearnmanager&jslmslay=help" class="jslm_text"><?php echo __('Help' , 'learn-manager'); ?></a></li>
        </ul>
    </li>
</ul>

<script type="text/javascript">
    var cookielist = document.cookie.split(';');
    for (var i=0; i<cookielist.length; i++) {
        if (cookielist[i].trim() == "jsst_collapse_admin_menu=1") {
            jQuery("#jslearnmanageradmin-wrapper").addClass("menu-collasped-active");
            break;
        }
    }
    jQuery(document).ready(function(){
        jQuery("img#js-admin-responsive-menu-link").click(function(e){
            e.preventDefault();
            if(jQuery("div#jslearnmanageradmin-leftmenu").css('display') == 'none'){
                jQuery("div#jslearnmanageradmin-leftmenu").show();
                jQuery("div#jslearnmanageradmin-leftmenu").find('.jslm_js-parent,a.jslm_js-parent2').show();
                jQuery('.jslm_js-parent.jslm_lastshown').next().find('a.jslm_js-child').css('display','block');
                jQuery('.jslm_js-parent.jslm_lastshown').find('img.arrow').attr("src","components/com_jssupportticket/include/images/c_p/arrow2.png");
                jQuery('.jslm_js-parent.jslm_lastshown').find('span').css('color','#ffffff');
            }else{
                jQuery("div#jslearnmanageradmin-leftmenu").fadeOut(5);
            }
        });
        jQuery("img#jslearnmanageradmin-menu-toggle").click(function () {
            if(jQuery("div#jslearnmanageradmin-wrapper").hasClass("menu-collasped-active")){
                jQuery('div#jslearnmanageradmin-wrapper').removeClass('menu-collasped-active');
                jQuery('.jslm_js-parent ').css('display','none');
                jQuery('a.jslm_js-child').css({'display':'none'});
                document.cookie = 'jsst_collapse_admin_menu=0; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
            }else{
                jQuery("div#jslearnmanageradmin-leftmenu").show();
                jQuery("div#jslearnmanageradmin-wrapper").addClass('menu-collasped-active');
                jQuery('.jslm_js-parent ').css('display','inline-block');
                document.cookie = 'jsst_collapse_admin_menu=1; expires=Sat, 01 Jan 2050 00:00:00 UTC; path=/';
            }
        });
    });
</script>
<script type="text/javascript">
    jQuery( function() {
        jQuery( ".accordion" ).accordion({
            heightStyle: "content",
            collapsible: true,
            active: true,
        });
    });
</script>
