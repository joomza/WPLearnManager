<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERformhandler {

    function __construct() {
        add_action('init', array($this, 'checkFormRequest'));
        add_action('init', array($this, 'checkDeleteRequest'));
    }

    /*
     * Handle Form request
     */

    function checkFormRequest() {
        $formrequest = JSLEARNMANAGERrequest::getVar('form_request', 'post');
        if ($formrequest == 'jslearnmanager') {
            //handle the request
            $modulename = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($modulename);
            $module = str_replace('jslm_', '', $module);
            JSLEARNMANAGERincluder::include_file($module);
            $class = 'JSLEARNMANAGER' . $module . "Controller";
            $task = JSLEARNMANAGERrequest::getVar('task');
            $obj = new $class;
            $obj->$task();
        }
    }

    /*
     * Handle Form request
     */

    function checkDeleteRequest() {
        $jslearnmanager_action = JSLEARNMANAGERrequest::getVar('action', 'get');
        if ($jslearnmanager_action == 'jslmstask') {
            //handle the request
            $modulename = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($modulename);
            $module = str_replace('jslm_', '', $module);
            JSLEARNMANAGERincluder::include_file($module);
            $class = 'JSLEARNMANAGER' . $module . "Controller";
            $action = JSLEARNMANAGERrequest::getVar('task');
            $obj = new $class;
            $obj->$action();
        }
    }

}

$formhandler = new JSLEARNMANAGERformhandler();
?>