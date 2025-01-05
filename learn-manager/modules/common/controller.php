<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class jslearnmanagerCommonController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'newinjslearnmanager');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'newinjslearnmanager':
                    if(JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        $link = get_permalink();
                        $linktext = __('Login','learn-manager');
                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1 , $link , $linktext,1);
                    }
                break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'common');
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

    function makedefault() {
        $id = JSLEARNMANAGERrequest::getVar('id');
        $for = JSLEARNMANAGERrequest::getVar('for'); // table name
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $result = JSLEARNMANAGERincluder::getJSModel('common')->setDefaultForDefaultTable($id, $for);
        $layout = JSLEARNMANAGERrequest::getVar('jslmslay');
        $msg = JSLEARNMANAGERmessages::getMessage($result, $for);
        $url = admin_url("admin.php?page=jslm_" . $for . "&jslmslay=" . $layout);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        $msgkey = JSLEARNMANAGERincluder::getJSModel($for)->getMessagekey();
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'],$msgkey);
        wp_redirect($url);
        die();
    }

    function defaultorderingup() {
        $id = JSLEARNMANAGERrequest::getVar('id');
        $for = JSLEARNMANAGERrequest::getVar('for'); //table name
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $result = JSLEARNMANAGERincluder::getJSModel('common')->setOrderingUpForDefaultTable($id, $for);
        $layout = JSLEARNMANAGERrequest::getVar('jslmslay');
        $msg = JSLEARNMANAGERmessages::getMessage($result, $for);
        $url = admin_url("admin.php?page=jslm_" . $for . "&jslmslay=" . $layout);
        // for models layout
        $makeid = JSLEARNMANAGERrequest::getVar('makeid');
        if(is_numeric($makeid)){
            $url .= '&makeid='.$makeid;            
        }
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        $msgkey = JSLEARNMANAGERincluder::getJSModel($for)->getMessagekey();
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $msgkey);
        wp_redirect($url);
        die();
    }

    function defaultorderingdown() {
        $id = JSLEARNMANAGERrequest::getVar('id');
        $for = JSLEARNMANAGERrequest::getVar('for'); // table name
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $result = JSLEARNMANAGERincluder::getJSModel('common')->setOrderingDownForDefaultTable($id, $for);
        $layout = JSLEARNMANAGERrequest::getVar('jslmslay');
        $msg = JSLEARNMANAGERmessages::getMessage($result, $for);
        $url = admin_url("admin.php?page=jslm_" . $for . "&jslmslay=" . $layout);
        // for models layout
        $makeid = JSLEARNMANAGERrequest::getVar('makeid');
        if(is_numeric($makeid)){
            $url .= '&makeid='.$makeid;            
        }
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        $msgkey = JSLEARNMANAGERincluder::getJSModel($for)->getMessagekey();
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $msgkey);
        wp_redirect($url);
        die();
    }

    function savenewinjslearnmanager() {
        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('common')->saveNewInJSLearnManager($data);
        //if ($data['desired_module'] == 'common' && $data['desired_layout'] == 'newinjslearnmanager') {
            $data['desired_module'] = 'user';
            $data['desired_layout'] = 'myprofile';
        //}
        $url = jslearnmanager::makeUrl(array('jslmsmod'=>$data['desired_module'], 'jslmslay'=>$data['desired_layout'],'jslearnmanagerpageid'=>jslearnmanager::getPageid()));
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'userrole');
        $msgkey = JSLEARNMANAGERincluder::getJSModel('common')->getMessagekey();
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'],$msgkey);
        wp_redirect($url);
        die();
    }

}

$jslearnmanagerCommonController = new jslearnmanagerCommonController;
?>