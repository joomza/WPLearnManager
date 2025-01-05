<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERslugController {
    private $_msgkey;
    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('slug')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'slug');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_slug':
                    JSLEARNMANAGERincluder::getJSModel('slug')->getSlug();
                    break;
            }
            $module = 'page';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'slug');
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

    function saveSlug() {
        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('slug')->storeSlug($data);
        if($data['pagenum'] > 0){
            $url = admin_url("admin.php?page=jslm_slug&pagenum=".$data['pagenum']);
        }else{
            $url = admin_url("admin.php?page=jslm_slug");
        }

        $msg = JSLEARNMANAGERMessages::getMessage($result, 'slug');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function saveprefix() {
        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('slug')->savePrefix($data);
        $url = admin_url("admin.php?page=jslm_slug");
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'prefix');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function resetallslugs() {
        $result = JSLEARNMANAGERincluder::getJSModel('slug')->resetAllSlugs();
        $url = admin_url("admin.php?page=jslm_slug");
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'slug');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }
}

$JSLEARNMANAGERslugController = new JSLEARNMANAGERslugController();
?>