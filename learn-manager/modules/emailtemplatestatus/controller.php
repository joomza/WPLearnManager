<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERemailtemplatestatusController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'emailtemplatestatus');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_emailtemplatestatus':
                    JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatusData();
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'emailtemplatestatus');
            $module = str_replace('jslm_', '', $module);
            JSLEARNMANAGERincluder::include_file($layout, $module);
        }
    }

    function sendEmail() {
        $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $action = JSLEARNMANAGERrequest::getVar('actionfor');
        JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->sendEmailModel($id, $action); //  for send email
        $url = admin_url("admin.php?page=jslm_emailtemplatestatus");
        wp_redirect($url);
        die();
    }

    function noSendEmail() {
        $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $action = JSLEARNMANAGERrequest::getVar('actionfor');
        JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->noSendEmailModel($id, $action); //  for notsendemail
        $url = admin_url("admin.php?page=jslm_emailtemplatestatus");
        wp_redirect($url);
        die();
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

$JSLEARNMANAGEREmailtemplatestatusController = new JSLEARNMANAGEREmailtemplatestatusController();
?>