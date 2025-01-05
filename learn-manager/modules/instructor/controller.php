<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERInstructorController {
    private $_msgkey;
    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('instructor')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'instructors');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $instflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_instructor');
        $stdViewInstFlag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('studentview_js_controlpanel');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_instructors':
                    JSLEARNMANAGERincluder::getJSModel('instructor')->getAllInstructors(1);
                break;
                case 'admin_instructorqueue':
                    $for = JSLEARNMANAGERrequest::getVar('for');
                    JSLEARNMANAGERincluder::getJSModel('instructor')->getAllInstructors(2,$for);
                break;
                case 'instructorslist':
                   jslearnmanager::$_data['profilecustomfields'] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3);
                   jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1);
                    JSLEARNMANAGERincluder::getJSModel('instructor')->getAllInstructors(3);
                    JSLEARNMANAGERincluder::getJSModel('course')->countCourseByCategory();
                    if(jslearnmanager::$_learn_manager_theme == true){
                        JSLEARNMANAGERincluder::getJSModel('course')->getLatestCourses();
                    }
                    JSLEARNMANAGERincluder::getJSModel('instructor')->getTotalCoursesByInstructor();
                    JSLEARNMANAGERincluder::getJSModel('course')->getCourseByPriceType();
                break;
                case 'instructordetails':
                    $instructorid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    $isdisabled = JSLEARNMANAGERincluder::getJSModel('user')->isInstructorDisabled($instructorid);
                    if(isset($instructorid)){
                        if(JSLEARNMANAGERincluder::getObjectClass('user')->isStudent($uid) && $stdViewInstFlag != 1 && $instflag == 1){
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                            }
                        }else{
                            if($isdisabled != 1){
                                JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorProfilebyInstructorid($instructorid);
                                JSLEARNMANAGERincluder::getJSModel('instructor')->getMyStats($instructorid);
                                JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorCoursesByInstructorId($instructorid);
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                            }
                        }
                    }else{
                        if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest() && JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()){
                            if(JSLEARNMANAGERincluder::getObjectClass('user')->isInstructor($uid)){
                                $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
                                JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorProfilebyInstructorid($instructorid);
                                JSLEARNMANAGERincluder::getJSModel('instructor')->getMyStats($instructorid);
                                JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorCoursesByInstructorId($instructorid);
                            }elseif(!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()){
                                $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                                $linktext = __('Select role','learn-manager');
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                            }
                        }elseif(!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()){
                            $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                            $linktext = __('Select role','learn-manager');
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_NOTJSLMSUSER;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                            }
                        }else{
                            jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('instructor', $layout);
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                            }else{
                                $link = jslearnmanager::$_js_login_redirct_link;
                                $linktext = __('Login','learn-manager');
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                            }
                        }
                    }
                break;
                case 'admin_instructordetail':
                    $instructorid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    $u_id=JSLEARNMANAGERincluder::getJSModel('user')->getUserIDByInstructorid($instructorid);
                    JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorProfilebyInstructorid($instructorid);
                    JSLEARNMANAGERincluder::getJSModel('instructor')->getMyStats($instructorid);
                    JSLEARNMANAGERincluder::getJSModel('user')->getInstructorDataForDashboardTab($u_id);
                    jslearnmanager::$_data['award'] = apply_filters("jslm_awards_get_user_awards",'',$u_id,1); // 1 for instructor awards
                break;
                case 'instructordashboard':
                    if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        if(JSLEARNMANAGERincluder::getObjectClass('user')->isInstructor($uid)){
                        	if($instflag == 1){
	                            $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
	                            JSLEARNMANAGERincluder::getJSModel('user')->getProfileByUid($uid);
	                            JSLEARNMANAGERincluder::getJSModel('user')->getInstructorDataForDashboardTab($uid);
	                            JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorProfilebyInstructorid($instructorid);
	                            JSLEARNMANAGERincluder::getJSModel('course')->getrecentEnrollStudent($instructorid);
	                            JSLEARNMANAGERincluder::getJSModel('course')->getRecentCourses($instructorid);
	                            do_action("jslm_paidcourse_instructor_dashboard_earning_data",$instructorid);
	                            jslearnmanager::$_data['award'] = apply_filters("jslm_awards_get_user_awards",'',$uid,1); // 1 for instructor
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    do_action("jslm_featuredcourse_get_featured_course_instructor_dashboard_theme");
                                    if(in_array('paidcourse', jslearnmanager::$_active_addons)){
                                        JSLEARNMANAGERincluder::getJSModel('purchasehistory')->getDataForPurchasehistroy($uid);
                                        JSLEARNMANAGERincluder::getJSModel('earning')->getInstructorEarningsForDashboard($instructorid);
                                    }
                                    do_action("jslm_payouts_payouts_detail_instructor_dashboard",$instructorid);
                                }
                        	}else{
                        		if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                        	}
                        }elseif (!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()) {
                            jslearnmanager::$_js_login_redirct_link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                            $linktext = __('Select role','learn-manager');
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_NOTJSLMSUSER;

                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , jslearnmanager::$_js_login_redirct_link , $linktext,1);
                            }
                            // jslearnmanager::$_error_flag_message_for = 7;
                        }else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                            }
                        }
                    }else{
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('instructor', $layout);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = jslearnmanager::$_js_login_redirct_link;
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                        }
                    }
                break;
                case 'mycourses':
                    if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        if(JSLEARNMANAGERincluder::getObjectClass('user')->isInstructor($uid)){
                            if($instflag == 1){
                                $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
                                JSLEARNMANAGERincluder::getJSModel('user')->getInstructorDataForDashboardTab($uid);
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                            }
                        }elseif (!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()) {
                            $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                            $linktext = __('Select role','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                            // jslearnmanager::$_error_flag_message_for = 7;
                        }
                        else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                            }
                        }
                    }else{
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('instructor', $layout);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = jslearnmanager::$_js_login_redirct_link;
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                        }
                    }
                break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'instructor');
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

    function downloadbyname(){
        $name = JSLEARNMANAGERrequest::getVar('name');
        $id = JSLEARNMANAGERrequest::getVar('id');
        JSLEARNMANAGERincluder::getJSModel('instructor')->getDownloadFileByName($name,$id);
    }

    function approve(){
        $pagenum = JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        if(!isset($pagenum) && $call_from != 1){
            $pagenum = 1;
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb'); // user id
        $url = admin_url("admin.php?page=jslm_instructor&jslmslay=instructorqueue&for=".$call_from);
        $result = JSLEARNMANAGERincluder::getJSModel('instructor')->approveReject($ids,1); // 1 for approve
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'instructor');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function reject(){
        $pagenum = JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid');
        if(!isset($pagenum)){
            $pagenum = 1;
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb'); // user id
        $url = admin_url("admin.php?page=jslm_instructor&jslmslay=instructorqueue&for=0");
        $result = JSLEARNMANAGERincluder::getJSModel('instructor')->approveReject($ids,-1); // -1 for reject
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'instructor');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function remove() { // only delete LM records
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-instructor') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $result = JSLEARNMANAGERincluder::getJSModel('user')->deleteUserData($ids,2); // 2 for instructor
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'instructor');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_instructor&jslmslay=instructors");
        if($call_from == 2){
            $url = admin_url("admin.php?page=jslm_instructor&jslmslay=instructorqueue");
        }else{
            $url = admin_url("admin.php?page=jslm_instructor&jslmslay=instructors");
        }
        wp_redirect($url);
        die();
    }

    function enforceremove() { // delete eitire user
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-instructor') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('user')->enforceDeleteUserData($ids, 2); // 2 for instructor
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'instructor');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if($call_from == 2){
            $url = admin_url("admin.php?page=jslm_instructor&jslmslay=instructorqueue");
        }else{
            $url = admin_url("admin.php?page=jslm_instructor&jslmslay=instructors");
        }
        wp_redirect($url);
        die();
    }

}

$JSLEARNMANAGERInstructorController = new JSLEARNMANAGERInstructorController();
?>
