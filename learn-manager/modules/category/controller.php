<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCategoryController {
    
    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('category')->getMessagekey();  
        self::handleRequest();      
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'categories');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_categories':
                    JSLEARNMANAGERincluder::getJSModel('category')->getAllCategories();
                    break;
                case 'admin_formcategory':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    JSLEARNMANAGERincluder::getJSModel('category')->getCategorybyId($id);
                break;
                case 'categorieslist':
                    JSLEARNMANAGERincluder::getJSModel('category')->getCategoriesCountList();
                break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'category');
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
    
    function savecategory() {
        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('category')->storeCategory($data);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'category');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jslm_category");
        wp_redirect($url);
        die();
    }

    function remove() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-category') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('category')->deleteCategories($ids);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'category');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jslm_category");
        wp_redirect($url);
        die();
    }

    function publish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('category')->publishUnpublish($ids, 1); //  for publish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jslm_category&jslmslay=categories");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('category')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = admin_url("admin.php?page=jslm_category&jslmslay=categories");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    // WE will Save the Ordering system in this Function 
    function saveordering(){
        $data = JSLEARNMANAGERrequest::get('post');
        if($data['task'] == 'publish'){
        $this->publish();
        $url = admin_url("admin.php?page=jslm_category");
        exit();
      }

      if($data['task'] == 'unpublish'){
        $this->unpublish();
        $url = admin_url("admin.php?page=jslm_category");
        exit();
      }

      if($data['task'] == 'remove'){
        $this->remove();
        $url = admin_url("admin.php?page=jslm_category");
        exit();
      }
      JSLEARNMANAGERincluder::getJSModel('category')->storeOrderingFromPage($data);
      $url = admin_url("admin.php?page=jslm_category");
      wp_redirect($url);
      exit();
  }

}

$JSLEARNMANAGERCategory = new JSLEARNMANAGERCategoryController();
?>
