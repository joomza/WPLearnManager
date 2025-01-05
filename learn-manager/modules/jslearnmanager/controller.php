<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERjslearnmanagerController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'controlpanel');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_controlpanel':
                    include_once JSLEARNMANAGER_PLUGIN_PATH . 'includes/updates/updates.php';
                    JSLEARNMANAGERupdates::checkUpdates();
                    do_action( 'jslm_delete_expire_session_data' );
                    JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getAdminControlPanelData();
                    break;
                case 'admin_jslearnmanagerstats':
                    JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getJSLearnManagerStats();
                    break;
                case 'info':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    JSLEARNMANAGERincluder::getJSModel('announcement')->getAnnouncementDetails($id);
                    break;
                case 'updates':
                    break;
                case 'login':
                    if(JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        $url = JSLEARNMANAGERrequest::getVar('jslearnmanagerredirecturl', 'get');
                        if(isset($url)){
                            jslearnmanager::$_data[0]['redirect_url'] = base64_decode($url);
                        }else{
                            jslearnmanager::$_data[0]['redirect_url'] = home_url();
                        }
                    }else{
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_ALREADYLOGGEDIN;
                        }else{
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(5,null,null,null,0);
                        }
                    }
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'jslearnmanager');
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
}

$JSLEARNMANAGERController = new JSLEARNMANAGERjslearnmanagerController();
?>
