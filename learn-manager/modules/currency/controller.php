<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCurrencyController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('currency')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'currencies');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_currencies':
                    JSLEARNMANAGERincluder::getJSModel('currency')->getAllCurrencies();
                    break;
                case 'admin_formcurrency':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    JSLEARNMANAGERincluder::getJSModel('currency')->getCurrencybyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'currency');
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

    function savecurrency() {

        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('currency')->storeCurrency($data);
        $url = admin_url("admin.php?page=jslm_currency&jslmslay=currencies");

        $msg = JSLEARNMANAGERmessages::getMessage($result, 'currency');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('currency')->deleteCurrencies($ids);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'currency');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_currency&jslmslay=currencies");
        wp_redirect($url);
        die();
    }

    function publish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('currency')->publishUnpublish($ids, 1); //  for publish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_currency&jslmslay=currencies");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('currency')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_currency&jslmslay=currencies");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }


    // WE will Save the Ordering system in this Function 
    function saveordering(){
        $data = JSLEARNMANAGERrequest::get('post');
        if($data['task'] == 'publish' ){
        $this->publish();
        $url = admin_url("admin.php?page=jslm_currency");
        exit();
      }

      if($data['task'] == 'unpublish' ){
        $this->unpublish();
        $url = admin_url("admin.php?page=jslm_currency");
        exit();
      }
      if($data['task'] == 'remove' ){
        $this->remove();
        $url = admin_url("admin.php?page=jslm_currency");
        exit();
      }

      JSLEARNMANAGERincluder::getJSModel('currency')->storeOrderingFromPage($data);
      $url = admin_url("admin.php?page=jslm_currency");
      wp_redirect($url);
      exit();
  }

}

$JSLEARNMANAGERCurrencyController = new JSLEARNMANAGERCurrencyController();
?>
