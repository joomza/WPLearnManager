<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERAccessTypeController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('accesstype')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'accesstypes');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_accesstype':
                    JSLEARNMANAGERincluder::getJSModel('accesstype')->getAllAccessTypes();
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'accesstype');
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

$JSLEARNMANAGERAccessTypeController = new JSLEARNMANAGERAccessTypeController();
?>