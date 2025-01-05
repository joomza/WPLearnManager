<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGEREmailtemplateController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('emailtemplate')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'emailtemplate');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_emailtemplate':
                    $for = JSLEARNMANAGERrequest::getVar('for', null, 'nw-co-a');
                    $tempfor = $this->parseTemplateFor($for);
                    JSLEARNMANAGERincluder::getJSModel('emailtemplate')->getTemplate($tempfor);
                    jslearnmanager::$_data[1] = $for;
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'emailtemplate');
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

    function saveemailtemplate() {
        $data = JSLEARNMANAGERrequest::get('post');
        $templatefor = $data['templatefor'];
        $result = JSLEARNMANAGERincluder::getJSModel('emailtemplate')->storeEmailTemplate($data);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'emailtemplate');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);

        switch ($templatefor){
            case 'new-course-admin' : $tempfor = 'nw-co-a'; break;
            case 'new-course' : $tempfor = 'nw-co'; break;
            case 'course-status' : $tempfor = 'co-st'; break;
            case 'delete-course' : $tempfor = 'de-co'; break;
            case 'courseenrollment-student' : $tempfor = 'co-en-st'; break;
            case 'courseenrollment-instructor' : $tempfor = 'co-en-in'; break;
            case 'courseenrollment-admin' : $tempfor = 'co-en-ad'; break;
            case 'new-user' : $tempfor = 'nw-u'; break;
            case 'new-user-admin' : $tempfor = 'nw-u-a'; break;
            case 'course-alert' : $tempfor = 'co-al'; break;
            case 'featured-course-status' : $tempfor = 'fe-co-st'; break;
            case 'tell-a-friend' : $tempfor = 't-a-fr'; break;
            case 'payout-email-admin' : $tempfor = 'pa-em-ad'; break;
            case 'payout-email-instructor' : $tempfor = 'pa-em-in'; break;
            case 'message-to-sender' : $tempfor = 'mg-t-sr'; break;
        }

        $url = admin_url("admin.php?page=jslm_emailtemplate&for=" . $tempfor);
        wp_redirect($url);
        die();
    }
    
    function parseTemplateFor($for) {
        switch ($for){
            case 'nw-co-a' : $templatefor = 'new-course-admin'; break;
            case 'nw-co' : $templatefor = 'new-course'; break;
            case 'co-st' : $templatefor = 'course-status'; break;
            case 'de-co' : $templatefor = 'delete-course'; break;
            case 'co-en-st' : $templatefor = 'courseenrollment-student'; break;
            case 'co-en-in' : $templatefor = 'courseenrollment-instructor'; break;
            case 'co-en-ad' : $templatefor = 'courseenrollment-admin'; break;
            case 'nw-u' : $templatefor = 'new-user'; break;
            case 'nw-u-a' : $templatefor = 'new-user-admin'; break;
            case 'co-al' : $templatefor = 'course-alert'; break;
            case 'fe-co-st' : $templatefor = 'featured-course-status'; break;
            case 't-a-fr' : $templatefor = 'tell-a-friend'; break;
            case 'pa-em-ad' : $templatefor = 'payout-email-admin'; break;
            case 'pa-em-in' : $templatefor = 'payout-email-instructor'; break;
            case 'mg-t-sr' : $templatefor = 'message-to-sender'; break;

        }
        return $templatefor;
    }

}

$JSLEARNMANAGEREmailtemplateController = new JSLEARNMANAGEREmailtemplateController();
?>