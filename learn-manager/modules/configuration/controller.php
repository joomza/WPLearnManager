<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERConfigurationController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('configuration')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'configurations');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_configurations':
                case 'admin_configurationsinstructor':
                case 'admin_configurationsstudent':
                    JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationsForForm();
                    break;
                case 'admin_cronjob':
                    JSLEARNMANAGERincluder::getJSModel('configuration')->getCronKey(md5(date_i18n('Y-m-d')));
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'configurations');
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

    function saveconfiguration() {
        $data = JSLEARNMANAGERrequest::get('post');
        $layout = JSLEARNMANAGERrequest::getVar('jslmslay');
        $result = JSLEARNMANAGERincluder::getJSModel('configuration')->storeConfig($data);
        $msg = JSLEARNMANAGERmessages::getMessage($result, "configuration");
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_configuration&jslmslay=" . $layout);
        wp_redirect($url);
        die();
    }

}

$JSLEARNMANAGERConfigurationController = new JSLEARNMANAGERConfigurationController();
?>