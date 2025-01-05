<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERpostinstallationController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'stepone');
        if($this->canaddfile()){
            switch ($layout) {
                case 'admin_quickconfig':
                    JSLEARNMANAGERincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_stepone':
                    JSLEARNMANAGERincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_steptwo':
                    JSLEARNMANAGERincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_stepthree':
                    JSLEARNMANAGERincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_themedemodata':
                    jslearnmanager::$_data['flag'] = JSLEARNMANAGERrequest::getVar('flag');
                break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'postinstallation');
            $module = str_replace('jslm_', '', $module);
            JSLEARNMANAGERincluder::include_file($layout, $module);
        }

    }
    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jslearnmanager')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jslmstask')
            return false;
        else
            return true;
    }

    function save(){
        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('postinstallation')->storeConfigurations($data);
        $url = admin_url("admin.php?page=jslm_postinstallation&jslmslay=steptwo");
        if($data['step'] == 2){
            $url = admin_url("admin.php?page=jslm_postinstallation&jslmslay=stepthree");
        }
        if($data['step'] == 3){
            //$url = admin_url("admin.php?page=jslm_postinstallation&jslmslay=settingcomplete");
            $url = admin_url("admin.php?page=jslm_postinstallation&jslmslay=stepfour");
        }
        wp_redirect($url);
        exit();
    }

    function savesampledata(){

        // $b = JSLEARNMANAGER_PLUGIN_URL . "includes/sample-data/sample-data.zip";

        $data = JSLEARNMANAGERrequest::get('post');
        $sampledata = $data['sampledata'];
        $tempdata = isset($data['temp_data']) ? $data['temp_data'] : '';
        // $jsmenu = $data['jsmenu'];
        // $empmenu = $data['empmenu'];

        //$url = admin_url("admin.php?page=jslearnmanager");
        if(!empty($data)){
            $url = admin_url("admin.php?page=jslm_postinstallation&jslmslay=settingcomplete");
        }else{
            $url = admin_url("admin.php?page=jslm_postinstallation&jslmslay=stepfour");
        }
        $result = JSLEARNMANAGERincluder::getJSModel('postinstallation')->installSampleData($sampledata,$tempdata);
        wp_redirect($url);
        exit();
    }
}
$JSLEARNMANAGERpostinstallationController = new JSLEARNMANAGERpostinstallationController();
?>
