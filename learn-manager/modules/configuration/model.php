<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERconfigurationModel {

    function __construct() {
        
    }

    function getConfiguration() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        // check for plugin using plugin name
        if (is_plugin_active('learn-manager/learn-manager.php')) {
            $db = new jslearnmanagerdb();
            $query = "SELECT config.* FROM `#__js_learnmanager_config` AS config WHERE configfor = 'default'";
            $db->setQuery($query);
            $config = $db->loadObjectList();
            foreach ($config as $conf) {
                jslearnmanager::$_config[$conf->configname] = $conf->configvalue;
            }
            jslearnmanager::$_config['config_count'] = COUNT($config);
        }
    }

    function getConfigurationsForForm() {
        $db = new jslearnmanagerdb();
        $query = "SELECT config.* FROM `#__js_learnmanager_config` AS config";
        $db->setQuery($query);
        $config = $db->loadObjectList();
        foreach ($config as $conf) {
            jslearnmanager::$_data[0][$conf->configname] = $conf->configvalue;
        }
        jslearnmanager::$_data[0]['config_count'] = COUNT($config);

    }

    function storeConfig($data) {
        if (empty($data))
            return false;

        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if ($data['isgeneralbuttonsubmit'] == 1) {
            if (!isset($data['tumbler_share']))
                $data['tumbler_share'] = 0;
            if (!isset($data['fb_like']))
                $data['fb_like'] = 0;
            if (!isset($data['fb_comments']))
                $data['fb_comments'] = 0;
            if (!isset($data['fb_share']))
                $data['fb_share'] = 0;
            if (!isset($data['google_like']))
                $data['google_like'] = 0;
            if (!isset($data['google_share']))
                $data['google_share'] = 0;
            if (!isset($data['blogger_share']))
                $data['blogger_share'] = 0;
            if (!isset($data['instgram_share']))
                $data['instgram_share'] = 0;
            if (!isset($data['linkedin']))
                $data['linkedin'] = 0;
            if (!isset($data['digg_share']))
                $data['digg_share'] = 0;
            if (!isset($data['twitter_share']))
                $data['twitter_share'] = 0;
            if (!isset($data['pintrest_share']))
                $data['pintrest_share'] = 0;
            if (!isset($data['yahoo_share']))
                $data['yahoo_share'] = 0;
        }

        if (isset($_POST['offline_text'])) {
            $data['offline_text'] = sanitize_text_field(wpautop(wptexturize(stripslashes(sanitize_key($_POST['offline_text'])))));
        }
        $error = false;
        $db = new jslearnmanagerdb();
        foreach ($data as $key => $value) {
            if ($key == 'data_directory') {
                $data_directory = $value;
                if (empty($data_directory)) {
                    JSLEARNMANAGERmessages::setLayoutMessage(__('Data directory can not empty.', 'learn-manager'), 'error', 'configuration');
                    continue;
                }
                if (strpos($data_directory, '/') !== false) {
                    JSLEARNMANAGERmessages::setLayoutMessage(__('Data directory is not proper.', 'learn-manager'), 'error', 'configuration');
                    continue;
                }
                $path = jslearnmanager::$_path . '/' . $data_directory;
                if (!file_exists($path)) {
                    mkdir($path, 0755);
                }
                if (!is_writeable($path)) {
                    JSLEARNMANAGERmessages::setLayoutMessage(__('Data directory is not writable.', 'learn-manager'), 'error', 'configuration');
                    continue;
                }
            }
            $query = "UPDATE `#__js_learnmanager_config` SET `configvalue` = '$value' WHERE `configname`= '" . $key . "'";
            $db->setQuery($query);
            if (false === $db->query()) {
                $error = true;
            }
        }
        if ($error)
            return JSLEARNMANAGER_SAVE_ERROR;
        else
            return JSLEARNMANAGER_SAVED;
    }

    function getConfigByFor($configfor) {
        if (!$configfor)
            return;
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_config` WHERE configfor = '" . $configfor . "'";
        $db->setQuery($query);
        $config = $db->loadObjectList();
        $configs = array();
        foreach ($config as $conf) {
            $configs[$conf->configname] = $conf->configvalue;
        }
        return $configs;
    }

    function getCheckCronKey() {
        $db = new jslearnmanagerdb();
        $query = "SELECT configvalue FROM `#__js_learnmanager_config` WHERE configname = 'cron_job_alert_key'";
        $db->setQuery($query);
        $key = $db->loadResult();
        if ($key)
            return true;
        else
            return false;
    }

    function genearateCronKey() {
        $key = md5(date_i18n('Y-m-d'));
        $db = new jslearnmanagerdb();
        $query = "UPDATE `#__js_learnmanager_config` SET configvalue = '$key' WHERE configname = 'cron_job_alert_key'";
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        } else
            return true;
    }

    function getCronKey($passkey) {
        if ($passkey == md5(date_i18n('Y-m-d'))) {
            $db = new jslearnmanagerdb();
            $query = "SELECT configvalue FROM `#__js_learnmanager_config` WHERE configname = 'cron_job_alert_key'";
            $db->setQuery($query);
            $key = $db->loadResult();
            jslearnmanager::$_data[0]['ck'] = $key;
            return $key;
        } else
            return false;
    }

    function getCountConfig() {
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(*) FROM `#__js_learnmanager_config`";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function getConfigValue($configname) {
        $db = new jslearnmanagerdb();
        $query = "SELECT configvalue FROM `#__js_learnmanager_config` WHERE configname = '" . $configname . "'";
        $db->setQuery($query);
        return $db->loadResult();
    }

    function getConfigurationByConfigForMultiple($configfor) {
        $db = new jslearnmanagerdb();
        $query = "SELECT configname,configvalue 
                  FROM `#__js_learnmanager_config` WHERE configfor IN (" . $configfor . ")";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        $config_array = array();
        //to make configuration in to an array with key as index 
        foreach ($result as $config) {
            $config_array[$config->configname] = $config->configvalue;
        }
        return $config_array;
    }

    function getConfigurationByConfigName($configname) {
        $db = new jslearnmanagerdb();
        $query = "SELECT configvalue 
                  FROM `#__js_learnmanager_config` WHERE configname ='" . $configname . "'";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function checkCronKey($passkey) {
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(configvalue) FROM `#__js_learnmanager_config` WHERE configname = 'cron_job_alert_key' AND configvalue = '" . $passkey . "'";
        $db->setQuery($query);
        $key = $db->loadResult();
        if ($key == 1)
            return true;
        else
            return false;
    }

    function getConfiginArray($configfor) { //getConfiginArray
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_config` ";
        $db->setQuery($query);
        $config = $db->loadObjectList();

        $configs = array();
        foreach ($config as $conf) {
            $configs[$conf->configname] = $conf->configvalue;
        }
        return $configs;
    }

    function getMessagekey(){
        $key = 'configuration';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

    function getConfigSideMenu(){
        $html = '<ul id="jslearnmanageradmin-menu-links" class="tree accordion jslearnmanageradmin-menu-links"  data-widget="tree">
            <li class="treeview jslm_js-divlink active">
                <a class="js-icon-left" href="admin.php?page=jslearnmanager">
                    <img src="'.  JSLEARNMANAGER_PLUGIN_URL."includes/images/left-icons/configuration.png" .'"/>
                    <span class="jslm_text jslm_js-parent">'. __("General" , "learn-manager") .'</span>
                </a>
                <ul class="jslm_js-innerlink treeview-menu">
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_site_setting" class="jslm_text">'. __("Site Setting","learn-manager") .'</a></li>   
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_visitor_setting" class="jslm_text">'.  __("Visitor Setting" , "learn-manager") .'</a></li>
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_list_course" class="jslm_text">'.  __("List Course" , "learn-manager") .'</a></li>
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_instructor_topmenu" class="jslm_text">'.  __("Instructor Top menu" , "learn-manager") .'</a></li>
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_student_topmenu" class="jslm_text">'.  __("Student Top menu" , "learn-manager").'</a></li>
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_visitor_topmenu" class="jslm_text">'.  __("Visitor Top menu" , "learn-manager") .'</a></li>
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_email" class="jslm_text">'.  __("Emai Setting" , "learn-manager") .'</a></li>
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_rss" class="jslm_text">'.  __("RSS Setting" , "learn-manager") .'</a></li>
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_sociallogin" class="jslm_text">'.  __("Social login" , "learn-manager") .'</a></li>
                    <li class="jslm_js-child"><a href="?page=jslm_configuration&jslmslay=configurations#jslm_socailsharing" class="jslm_text">'.  __("Social Sharing" , "learn-manager") .'</a></li>
                </ul>
            </li>
            <li class="treeview jslm_js-divlink ">
                <a class="js-icon-left" href="admin.php?page=jslearnmanager">
                    <img src="'.  JSLEARNMANAGER_PLUGIN_URL."includes/images/left-icons/instructor.png" .'"/>
                    <span class="jslm_text jslm_js-parent ">'.  __("Instructor" , "learn-manager") .'</span>
                </a>
                <ul class="jslm_js-innerlink treeview-menu">
                    <li class="jslm_js-child"><a href="admin.php?page=jslm_configuration&jslmslay=configurationsinstructor#jslm_general_setting" class="jslm_text">'.  __("General Setting","learn-manager") .'</a></li>
                    <li class="jslm_js-child"><a href="admin.php?page=jslm_configuration&jslmslay=configurationsinstructor#jslm_course_setting" class="jslm_text">'.  __("Course Setting" , "learn-manager") .'</a></li>
                </ul>
            </li>
            <li class="treeview jslm_js-divlink">
                <a class="js-icon-left" href="admin.php?page=jslearnmanager">
                    <img src="'. JSLEARNMANAGER_PLUGIN_URL."includes/images/left-icons/students.png" .'"/>
                    <span class="jslm_text jslm_js-parent">'. __("Student" , "learn-manager") .'</span>
                </a>
                <ul class="jslm_js-innerlink treeview-menu">
                    <li class="jslm_js-child"><a href="admin.php?page=jslm_configuration&jslmslay=configurationsstudent#jslm_general_setting" class="jslm_text">'.  __("General Setting","learn-manager") .'</a></li>
                    <li class="jslm_js-child"><a href="admin.php?page=jslm_configuration&jslmslay=configurationsstudent#jslm_course_setting" class="jslm_text">'.  __("Course Setting" , "learn-manager") .'</a></li>
                </ul>
            </li>
        </ul>';
        return $html;
    }
}

?>
