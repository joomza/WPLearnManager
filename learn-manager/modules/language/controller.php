<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERLanguageController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('language')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'languages');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_languages':
                    JSLEARNMANAGERincluder::getJSModel('language')->getAllLanguages();
                    break;
                case 'admin_formlanguage':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    JSLEARNMANAGERincluder::getJSModel('language')->getLanguageforForm($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'language');
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

    function savelanguage() {

        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('language')->storeLanguage($data);
        $url = admin_url("admin.php?page=jslm_language&jslmslay=languages");
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'language');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-courselanguage') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('language')->deleteLanguages($ids);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'language');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_language&jslmslay=languages");
        wp_redirect($url);
        die();
    }

    function publish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('language')->publishUnpublish($ids, 1); //  for publish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_language&jslmslay=languages");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('language')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_language&jslmslay=languages");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$JSLEARNMANAGERLanguageController = new JSLEARNMANAGERLanguageController();
?>