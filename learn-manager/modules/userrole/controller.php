<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERUserRoleController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('userrole')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'userroles');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        if (self::canaddfile()) {
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'userrole');
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
$JSLEARNMANAGERUserRoleController = new JSLEARNMANAGERUserRoleController();
?>