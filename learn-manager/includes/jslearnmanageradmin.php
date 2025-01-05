<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class jslearnmanageradmin {

    function __construct() {
        add_action('admin_menu', array($this, 'mainmenu'));
    }

    function mainmenu() {
        if(current_user_can('jslearnmanager')){
            add_menu_page(__('Control Panel', 'learn-manager'), // Page title
                __('Learn Manager', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslearnmanager', //menu slug
                array($this, 'showAdminPage') // function name
                ,JSLEARNMANAGER_PLUGIN_URL.'includes/images/admin_jslearnmanager1.png'
            );
            add_submenu_page('jslearnmanager', // parent slug
                __('Category', 'learn-manager'), // Page title
                __('Categories', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_category', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager', // parent slug
                __('Courses', 'learn-manager'), // Page title
                __('Courses', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_course', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager', // parent slug
                __('Instructors', 'learn-manager'), // Page title
                __('Instructors', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_instructor', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager', // parent slug
                __('Students', 'learn-manager'), // Page title
                __('Students', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_student', //menu slug
                array($this, 'showAdminPage') // function name
            );
            if(in_array('awards', jslearnmanager::$_active_addons)){
                add_submenu_page('jslearnmanager', // parent slug
                    __('Awards', 'learn-manager'), // Page title
                    __('Awards', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_awards', //menu slug
                    array($this, 'showAdminPage') // function name
                );
                add_submenu_page('jslearnmanager_hide', // parent slug
                    __('Award Actions', 'learn-manager'), // Page title
                    __('Award Actions', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_awardsaction', //menu slug
                    array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('jslm_awards');
                $this->addMissingAddonPage('jslm_awardsaction');
            }
            add_submenu_page('jslearnmanager', // parent slug
                __('Configurations', 'learn-manager'), // Page title
                __('Configurations', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_configuration', //menu slug
                array($this, 'showAdminPage') // function name
            );
            if(in_array('reports', jslearnmanager::$_active_addons)){
                add_submenu_page('jslearnmanager', // parent slug
                    __('Reports', 'learn-manager'), // Page title
                    __('Reports', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_reports', //menu slug
                    array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('jslm_reports');
            }
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Short Codes', 'learn-manager'), // Page title
                __('Short Codes', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_shortcodes', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager', // parent slug
                __('Activity Logs', 'learn-manager'), // Page title
                __('Activity Logs', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_activitylog', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('User', 'learn-manager'), // Page title
                __('User', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_user', //menu slug
                array($this, 'showAdminPage') // function name
            );
            if(in_array('paymentplan', jslearnmanager::$_active_addons)){
                add_submenu_page('jslearnmanager_hide', // parent slug
                    __('Payment Plans', 'learn-manager'), // Page title
                    __('Payment Plans', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_paymentplan', //menu slug
                    array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('jslm_paymentplan');
            }
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Course Level', 'learn-manager'), // Page title
                __('Course Level', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_courselevel', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Add Lecture', 'learn-manager'), // Page title
                __('Add Lecture', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_lecture', //menu slug
                array($this, 'showAdminPage') // function name
            );
            if(in_array('message', jslearnmanager::$_active_addons)){
                add_submenu_page('jslearnmanager_hide', // parent slug
                    __('Messages', 'learn-manager'), // Page title
                    __('Messages', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_message', //menu slug
                    array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('jslm_message');
            }
            if(in_array('paidcourse', jslearnmanager::$_active_addons)){
                add_submenu_page('jslearnmanager_hide', // parent slug
                    __('Earnings', 'learn-manager'), // Page title
                    __('Earnings', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_earning', //menu slug
                    array($this, 'showAdminPage') // function name
                );
                add_submenu_page('jslearnmanager_hide', // parent slug
                    __('Course Earnings', 'learn-manager'), // Page title
                    __('Course Earnings', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_courseearning', //menu slug
                    array($this, 'showAdminPage') // function name
                );
                add_submenu_page('jslearnmanager_hide', // parent slug
                    __('Payment Method Config', 'learn-manager'), // Page title
                    __('Payment Method Config', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_paymentmethodconfiguration', //menu slug
                    array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('jslm_earning');
                $this->addMissingAddonPage('jslm_courseearning');
                $this->addMissingAddonPage('jslm_paymentmethodconfiguration');
            }

            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Currencies', 'learn-manager'), // Page title
                __('Currencies', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_currency', //menu slug
                array($this, 'showAdminPage') // function name
            );

            if(in_array('payouts', jslearnmanager::$_active_addons)){
                add_submenu_page('jslearnmanager_hide', // parent slug
                    __('Payouts', 'learn-manager'), // Page title
                    __('Payouts', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_payouts', //menu slug
                    array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('jslm_payouts');
            }

            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Fields', 'learn-manager'), // Page title
                __('Fields', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_fieldordering', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Course Languages', 'learn-manager'), // Page title
                __('Course Languages', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_language', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager', // parent slug
                __('System Errors', 'learn-manager'), // Page title
                __('System Errors', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_systemerror', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Email Templates', 'learn-manager'), // Page title
                __('Email Templates', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_emailtemplate', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Common', 'learn-manager'), // Page title
                __('Common', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_common', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Email Templates Status', 'learn-manager'), // Page title
                __('Email Templates Status', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_emailtemplatestatus', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Countries', 'learn-manager'), // Page title
                __('Countries', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_country', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Slug', 'learn-manager'), // Page title
                __('Slug', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_slug', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Post Installation', 'learn-manager'), // Page title
                __('Post Installation', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_postinstallation', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Pro Installation', 'learn-manager'), // Page title
                __('Pro Installation', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_proinstaller', //menu slug
                array($this, 'showAdminPage') // function name
            );
            if(in_array('themes', jslearnmanager::$_active_addons)){
                add_submenu_page('jslearnmanager_hide', // parent slug
                    __('Theme', 'learn-manager'), // Page title
                    __('Theme', 'learn-manager'), // menu title
                    'jslearnmanager', // capability
                    'jslm_themes', //menu slug
                    array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('jslm_themes');
            }
            add_submenu_page('jslearnmanager_hide', // parent slug
                __('Information', 'learn-manager'), // Page title
                __('Information', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_information', //menu slug
                array($this, 'showAdminPage') // function name
            );

            add_submenu_page('jslearnmanager', // parent slug
                __('Translations'), // Page title
                __('Translations'), // menu title
                'jslearnmanager', // capability
                'jslearnmanager&jslmslay=translations', //menu slug
                array($this, 'showAdminPage') // function name
            );
             add_submenu_page('jslearnmanager', // parent slug
                __('Help'), // Page title
                __('Help'), // menu title
                'jslearnmanager', // capability
                'jslearnmanager&jslmslay=help', //menu slug
                array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jslearnmanager', // parent slug
                __('Premium Plugin', 'learn-manager'), // Page title
                __('Premium Plugin', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                'jslm_premiumplugin', //menu slug
                array($this, 'showAdminPage') // function name
            );
        }
    }

    function showAdminPage() {
        jslearnmanager::addStyleSheets();
        $page = JSLEARNMANAGERrequest::getVar('page');
        $page = str_replace('jslm_', '', $page);
        JSLEARNMANAGERincluder::include_file($page);
    }

    function addMissingAddonPage($module_name){
        add_submenu_page('jslearnmanager_hide', // parent slug
                __('Premium Addon', 'learn-manager'), // Page title
                __('Premium Addon', 'learn-manager'), // menu title
                'jslearnmanager', // capability
                $module_name, //menu slug
                array($this, 'showMissingAddonPage') // function name
        );
    }

    function showMissingAddonPage() {
        JSLEARNMANAGERincluder::include_file('admin_missingaddon','premiumplugin');
    }
}

$jslearnmanagerAdmin = new jslearnmanageradmin();
?>
