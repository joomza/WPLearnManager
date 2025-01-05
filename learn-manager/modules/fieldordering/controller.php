<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERfieldorderingController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'fieldsordering');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_fieldsordering':
                    $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
                    jslearnmanager::$_data['fieldfor'] = $fieldfor;
                    JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrdering($fieldfor);
                    break;
                case 'admin_formuserfield':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
                    if (empty($fieldfor)){
                        $fieldfor = jslearnmanager::$_data['fieldfor'];
                    }else{
                        jslearnmanager::$_data['fieldfor'] = $fieldfor;
                    }
                    jslearnmanager::$_data[0]['fieldfor'] = $fieldfor;
                    JSLEARNMANAGERincluder::getJSModel('fieldordering')->getUserFieldbyId($id, $fieldfor);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'fieldordering');
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

    function fieldrequired() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-required') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->fieldsRequiredOrNot($ids, 1); // required
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'fieldordering');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url('admin.php?page=jslm_fieldordering&jslmslay=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldnotrequired() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-required') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->fieldsRequiredOrNot($ids, 0); // notrequired
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'fieldordering');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url('admin.php?page=jslm_fieldordering&jslmslay=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldpublished() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-published') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->fieldsPublishedOrNot($ids, 1);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url('admin.php?page=jslm_fieldordering&jslmslay=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldunpublished() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-published') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->fieldsPublishedOrNot($ids, 0);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url('admin.php?page=jslm_fieldordering&jslmslay=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function visitorfieldpublished() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-visitorpublished') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->visitorFieldsPublishedOrNot($ids, 1);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url('admin.php?page=jslm_fieldordering&jslmslay=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function visitorfieldunpublished() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-visitorpublished') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->visitorFieldsPublishedOrNot($ids, 0);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'record');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url('admin.php?page=jslm_fieldordering&jslmslay=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    // Save the Ordering system in this Function 
    function saveordering(){
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-saveordering') ) {
            die( 'Security check Failed' );
        }
      $post = JSLEARNMANAGERrequest::get('post');
      if($post['task'] == 'fieldpublished' && $post['fieldfor'] == 1 ){
          $this->fieldpublished();
          $url = admin_url("admin.php?page=jslm_fieldordering&ff=1");
          exit();
      }
      if($post['task'] == 'fieldunpublished' && $post['fieldfor'] == 1 ){
        $this->fieldunpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=1");
         exit();
      }
      if($post['task'] == 'visitorfieldpublished' && $post['fieldfor'] == 1 ){
        $this->visitorfieldpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=1");
         exit();
      }
      if($post['task'] == 'visitorfieldunpublished' && $post['fieldfor'] == 1 ){
        $this->visitorfieldunpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=1");
         exit();
      }

     if($post['task'] == 'fieldrequired' && $post['fieldfor'] == 1 ){
        $this->fieldrequired();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=1");
         exit();
      }
      if($post['task'] == 'fieldnotrequired' && $post['fieldfor'] == 1 ){
        $this->fieldnotrequired();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=1");
         exit();
      }

      // for field order 2
      if($post['task'] == 'fieldpublished' && $post['fieldfor'] == 2 ){
        $this->fieldpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=2");
         exit();
      }
      if($post['task'] == 'fieldunpublished' && $post['fieldfor'] == 2 ){
        $this->fieldunpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=2");
         exit();
      }
      if($post['task'] == 'visitorfieldpublished' && $post['fieldfor'] == 2 ){
        $this->visitorfieldpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=2");
         exit();
      }
      if($post['task'] == 'visitorfieldunpublished' && $post['fieldfor'] == 2 ){
        $this->visitorfieldunpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=2");
         exit();
      }

     if($post['task'] == 'fieldrequired' && $post['fieldfor'] == 2 ){
        $this->fieldrequired();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=2");
         exit();
      }
      if($post['task'] == 'fieldnotrequired' && $post['fieldfor'] == 2 ){
        $this->fieldnotrequired();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=2");
         exit();
      }

      // field Order 3
      if($post['task'] == 'fieldpublished' && $post['fieldfor'] == 3 ){
        $this->fieldpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=3");
         exit();
      }
      if($post['task'] == 'fieldunpublished' && $post['fieldfor'] == 3 ){
        $this->fieldunpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=3");
         exit();
      }
      if($post['task'] == 'visitorfieldpublished' && $post['fieldfor'] == 3 ){
        $this->visitorfieldpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=3");
         exit();
      }
      if($post['task'] == 'visitorfieldunpublished' && $post['fieldfor'] == 3 ){
        $this->visitorfieldunpublished();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=3");
         exit();
      }

     if($post['task'] == 'fieldrequired' && $post['fieldfor'] == 3 ){
        $this->fieldrequired();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=3");
         exit();
      }
      if($post['task'] == 'fieldnotrequired' && $post['fieldfor'] == 3 ){
        $this->fieldnotrequired();
         $url = admin_url("admin.php?page=jslm_fieldordering&ff=3");
         exit();
      }


    $result =  JSLEARNMANAGERincluder::getJSModel('fieldordering')->storeOrderingFromPage($post);
       if($post['fieldfor'] == 1){
          $msg = JSLEARNMANAGERmessages::getMessage($result, 'fieldordering');
          JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
          $url = admin_url("admin.php?page=jslm_fieldordering&ff=1");
       }elseif($post['fieldfor'] == 2){
            $url = admin_url("admin.php?page=jslm_fieldordering&ff=2");
       }elseif($post['fieldfor'] == 3){
            $url = admin_url("admin.php?page=jslm_fieldordering&ff=3");
       }else{
           $url = admin_url("admin.php?page=jslm_course");
       }

      
        wp_redirect($url);
        exit;
    }
   //End function


    function fieldorderingup() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-orderingup') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
        $id = JSLEARNMANAGERrequest::getVar('fieldid');
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->fieldOrderingUp($id);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'fieldordering');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url('admin.php?page=jslm_fieldordering&jslmslay=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldorderingdown() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-orderingdown') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $fieldfor = JSLEARNMANAGERrequest::getVar('ff');
        $id = JSLEARNMANAGERrequest::getVar('fieldid');
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->fieldOrderingDown($id);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'fieldordering');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url('admin.php?page=jslm_fieldordering&jslmslay=fieldsordering&ff=' . $fieldfor);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function saveuserfield() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-form') ) {
            die( 'Security check Failed' );
        }
        $data = JSLEARNMANAGERrequest::get('post');
        $fieldfor = JSLEARNMANAGERrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = $data['fieldfor'];
        }
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->storeUserField($data);
        if ($result === JSLEARNMANAGER_SAVE_ERROR || $result === false) {
            $url = admin_url("admin.php?page=jslm_fieldordering&jslmslay=formuserfield&ff=" . $fieldfor);
        } else
            $url = admin_url("admin.php?page=jslm_fieldordering&ff=" . $fieldfor);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'customfield');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'fieldordering-remove') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $id = JSLEARNMANAGERrequest::getVar('fieldid');
        $ff = JSLEARNMANAGERrequest::getVar('ff');
        $result = JSLEARNMANAGERincluder::getJSModel('fieldordering')->deleteUserField($id);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'fieldordering');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_fieldordering&ff=".$ff);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }   

}

$JSLEARNMANAGERfieldorderingController = new JSLEARNMANAGERfieldorderingController();
?>
