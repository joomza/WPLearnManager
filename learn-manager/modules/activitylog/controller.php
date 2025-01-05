<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERactivitylogController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'activitylogs');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_activitylogs':
                    JSLEARNMANAGERincluder::getJSModel('activitylog')->getAllActivities();
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'activitylog');
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

}

$JSLEARNMANAGERactivitylogController = new JSLEARNMANAGERactivitylogController();
?>