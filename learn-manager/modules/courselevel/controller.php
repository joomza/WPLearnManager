<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcourselevelController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('courselevel')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'courselevel');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_courselevel':
                    JSLEARNMANAGERincluder::getJSModel('courselevel')->getAllLevels();
                    break;
                case 'admin_formcourselevel':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    JSLEARNMANAGERincluder::getJSModel('courselevel')->getLevelbyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'courselevel');
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

    function savelevel() {
        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('courselevel')->storeCourseLevel($data);
        $url = admin_url("admin.php?page=jslm_courselevel&jslmslay=courselevel");

        $msg = JSLEARNMANAGERmessages::getMessage($result, 'courselevel');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function removelevel() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-courselevel') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('courselevel')->deleteLevels($ids);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'courselevel');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect(admin_url("admin.php?page=jslm_courselevel&jslmslay=courselevel"));
        die();
    }

    function publish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('courselevel')->publishUnpublish($ids, 1); //  for publish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_courselevel&jslmslay=courselevel");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        exit();
    }

    function unpublish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('courselevel')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_courselevel&jslmslay=courselevel");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        exit();
    }

}

$JSLEARNMANAGERcourselevelController = new JSLEARNMANAGERcourselevelController();
?>