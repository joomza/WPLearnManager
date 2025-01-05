<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERajax {

    function __construct() {
        add_action("wp_ajax_jslearnmanager_ajax", array($this, "ajaxhandler")); // when user is login
        add_action("wp_ajax_nopriv_jslearnmanager_ajax", array($this, "ajaxhandler")); // when user is not login
        add_action("wp_ajax_jslearnmanager_loginwith_ajax", array($this, "ajaxhandlerloginwith")); // when user is login
        add_action("wp_ajax_nopriv_jslearnmanager_loginwith_ajax", array($this, "ajaxhandlerloginwith")); // when user is not login
    }

    function ajaxhandler() {
        $module = JSLEARNMANAGERrequest::getVar('jslmsmod');
        $task = JSLEARNMANAGERrequest::getVar('task');
        $result = JSLEARNMANAGERincluder::getJSModel($module)->$task();
        echo wp_kses($result, JSLEARNMANAGER_ALLOWED_TAGS);
        die();
    }
}

$jsajax = new JSLEARNMANAGERajax();
?>
