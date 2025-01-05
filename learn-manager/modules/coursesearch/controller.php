<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEANRMANAGERcourseSearchController {
    private $_msgkey;

    function __construct() {

        self::handleRequest();
        
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('coursesearch')->getMessagekey();        
    }

    function handleRequest() {
        
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'coursesearch');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        if (self::canaddfile()) {
             switch ($layout) {
                case 'coursesearch':
                    JSLEARNMANAGERincluder::getJSModel('coursesearch')->getCourseSearchOptions();
                    if (JSLEARNMANAGERincluder::getObjectClass('user')->isStudent($uid) || JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_js_coursesearch') == 1 ) {
                        JSLEARNMANAGERincluder::getJSModel('coursesearch')->getCourseSearchOptions();
                    } else {
                        if (JSLEARNMANAGERincluder::getObjectClass('user')->isInstructor($uid)) {
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(3,null,null,1);
                            jslearnmanager::$_error_flag_message_for=3;
                        }elseif (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
                            $link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('coursesearch', $layout, 1);
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1 , $link , $linktext,1);
                            jslearnmanager::$_error_flag_message_for=1;
                        } 
                        if(isset($link) && isset($linktext)){
                            jslearnmanager::$_error_flag_message_for_link = $link;               
                            jslearnmanager::$_error_flag_message_for_link_text = $linktext;              
                        }
                        jslearnmanager::$_error_flag = true;
                    }
                break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'coursesearch');
            $module = str_replace('jslearnmanager_', '', $module);
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

$JSLEANRMANAGERcourseSearchController = new JSLEANRMANAGERcourseSearchController();
?>
