<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERStudentController {

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('student')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'students');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $studentflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_student');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_students':
                    JSLEARNMANAGERincluder::getJSModel('student')->getAllStudentsList(1);
                break;
                case 'admin_studentqueue':
                    $for = JSLEARNMANAGERrequest::getVar('for');
                    JSLEARNMANAGERincluder::getJSModel('student')->getAllStudentsList(2,$for);
                break;
                case 'admin_studentdetail':
                    $studentid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    $u_id=JSLEARNMANAGERincluder::getJSModel('user')->getUserIDByStudentid($studentid);
                    JSLEARNMANAGERincluder::getJSModel('student')->getStudentProfilebyUid($u_id);
                    JSLEARNMANAGERincluder::getJSModel('student')->getMyStats($studentid);
                    JSLEARNMANAGERincluder::getJSModel('user')->getStudentDataForDashboardTab($u_id);
                    jslearnmanager::$_data['award'] = apply_filters("jslm_awards_get_user_awards",'',$u_id,2); // 2 for student
                break;
                case 'studentprofile':
                    if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        $isstudent = JSLEARNMANAGERincluder::getObjectClass('user')->isStudent($uid);
                        if($isstudent){
                            // only for itself profile view
                            $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
                            JSLEARNMANAGERincluder::getJSModel('student')->getStudentProfilebyUid($uid);
                            JSLEARNMANAGERincluder::getJSModel('student')->getMyStats($studentid);
                            JSLEARNMANAGERincluder::getJSModel('student')->getStudentCoursesForProfile($studentid);
                        }else{
                            // For instructor view student profile
                            $studentid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                            if(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('instructorviewstudent_js_controlpanel') && isset($studentid)){
                                $isdisabled = JSLEARNMANAGERincluder::getJSModel('user')->isStudentDisabled($studentid);
                                if($isdisabled != 1){
                                    $uid = JSLEARNMANAGERincluder::getJSModel('user')->getUserIDByStudentid($studentid);
                                    JSLEARNMANAGERincluder::getJSModel('student')->getStudentProfilebyUid($uid);
                                    JSLEARNMANAGERincluder::getJSModel('student')->getMyStats($studentid);
                                    JSLEARNMANAGERincluder::getJSModel('student')->getStudentCoursesForProfile($studentid);
                                }else{
                                    if(jslearnmanager::$_learn_manager_theme == true){
                                        jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                                    }else{
                                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                    }
                                }
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                            }
                        }
                    }else{
                        $studentid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                        if(isset($studentid) && $studentid != ""){
                            jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('student', $layout,$studentid);
                        }else{
                            jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('student', $layout);
                        }
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = jslearnmanager::$_js_login_redirct_link;
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                        }
                    }
                break;
                case 'studentdashboard':
                    if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        if(JSLEARNMANAGERincluder::getObjectClass('user')->isStudent($uid)){
                            $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
                            JSLEARNMANAGERincluder::getJSModel('user')->getProfileByUid($uid);
                            JSLEARNMANAGERincluder::getJSModel('course')->getStudentRecentCourses($studentid);
                            JSLEARNMANAGERincluder::getJSModel('student')->getStudentProfilebyUid($uid);
                            jslearnmanager::$_data['award'] = apply_filters("jslm_awards_get_user_awards",'',$uid,2); // 2 for student
                            do_action("jslm_quiz_get_student_quiz_result",$studentid);
                            if(jslearnmanager::$_learn_manager_theme == true){
                                JSLEARNMANAGERincluder::getJSModel('course')->getShortlistCourseByStudentid($studentid);
                                JSLEARNMANAGERincluder::getJSModel('user')->getStudentDataForDashboardTab($uid);
                                do_action("jslm_featuredcourse_get_featured_course_instructor_dashboard_theme");
                                do_action( "jslm_paidcourse_get_student_purchase_history_dashboard", $uid);
                                JSLEARNMANAGERincluder::getJSModel('course')->getRelatedCoursesForDashboard($uid);
                            }else{
                                JSLEARNMANAGERincluder::getJSModel('course')->getShortlistCourseByStudentidForDashoboard($studentid);
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
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('student', $layout);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = jslearnmanager::$_js_login_redirct_link;
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                        }
                    }
                break;
                case 'mycourses' :
                    if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        if(JSLEARNMANAGERincluder::getObjectClass('user')->isStudent($uid) && $studentflag == 1){
                            $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
                            JSLEARNMANAGERincluder::getJSModel('user')->getStudentDataForDashboardTab($uid);
                        }elseif (!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()) {
                            $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                            $linktext = __('Select role','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                            // jslearnmanager::$_error_flag_message_for = 7;
                        }else{
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                        }
                    }else{
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('student', $layout);
                        $link = jslearnmanager::$_js_login_redirct_link;
                        $linktext = __('Login','learn-manager');
                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                    }
                break;

            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'student');
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
        JSLEARNMANAGERincluder::getJSModel('course')->getDownloadFileByName($name,$id);
    }

    function approve(){
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb'); // user id
        $url = admin_url("admin.php?page=jslm_student&jslmslay=studentqueue&for=".$call_from);
        $result = JSLEARNMANAGERincluder::getJSModel('student')->approveReject($ids,1); // 1 for publish
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'student');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function reject(){
        $pagenum = JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb'); // user id
        $url = admin_url("admin.php?page=jslm_student&jslmslay=studentqueue&for=0");
        if($pagenum)
            $url .= "&pagenum=".$pagenum;
        $result = JSLEARNMANAGERincluder::getJSModel('student')->approveReject($ids,-1); // 0 for unpublish
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'student');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function remove() { // only delete LM records
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-student') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $result = JSLEARNMANAGERincluder::getJSModel('user')->deleteUserData($ids,1); // 1 for student
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'student');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if($call_from == 2){
            $url = admin_url("admin.php?page=jslm_student&jslmslay=studentqueue");
        }else{
            $url = admin_url("admin.php?page=jslm_student&jslmslay=students");
        }

        wp_redirect($url);
        die();
    }

    function enforceremove() { // delete eitire user
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-student') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $result = JSLEARNMANAGERincluder::getJSModel('user')->enforceDeleteUserData($ids, 1); // 1 for student
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'student');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if($call_from == 2){
            $url = admin_url("admin.php?page=jslm_student&jslmslay=studentqueue");
        }else{
            $url = admin_url("admin.php?page=jslm_student&jslmslay=students");
        }
        wp_redirect($url);
        die();
    }

    function removeenrollment() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'remove-enrollment') ) {
            die( 'Security check Failed' );
        }
        if(is_admin()){
            $sid = JSLEARNMANAGERrequest::getVar('jslearnmanagersid');
        }else{
            $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
            $sid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
        }
        $cid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $result = JSLEARNMANAGERincluder::getJSModel('student')->deleteEnrollment($sid,$cid);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid=".$sid);
        }else{
            $url = jslearnmanager::makeUrl(array('jslmsmod'=>'student', 'jslmslay'=>'studentdashboard', 'jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid')));
            if(jslearnmanager::$_learn_manager_theme != true){
                $url = jslearnmanager::makeUrl(array('jslmsmod'=>'student', 'jslmslay'=>'mycourses', 'jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid')));
            }
        }
        wp_redirect($url);
        die();
    }

}

$JSLEARNMANAGERStudentController = new JSLEARNMANAGERStudentController();
?>
