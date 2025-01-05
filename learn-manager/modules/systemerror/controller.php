<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERsystemerrorController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('systemerror')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'systemerrors');

        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_systemerrors':
                    JSLEARNMANAGERincluder::getJSModel('systemerror')->getSystemErrors();
                    break;

                case 'admin_addsystemerror':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid', 'get');
                    JSLEARNMANAGERincluder::getJSModel('systemerror')->getsystemerrorForForm($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'systemerror');
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

$systemerrorController = new JSLEARNMANAGERsystemerrorController();
?>