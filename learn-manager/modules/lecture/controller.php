<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERLectureController {

    private $_msgkey;

    function __construct() {

        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('lecture')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'lectures');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $instflag  = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_instructor');
        $stdntflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_student');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'addlecture':
                case 'admin_addlecture':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    $splitch = explode("_",$id);
                    if($splitch[0] == "sec" && is_numeric($splitch[1])){
                        $sid = $splitch[1]; // section id
                        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdBySectionId($sid);
                        $isowner = JSLEARNMANAGERincluder::getJSModel('course')->getIfCourseOwner($courseid,$uid);
                        if(is_admin()){
                            $isowner = true;
                            $instflag = 1;
                        }
                        if($isowner){
                            if($instflag == 1){
                                JSLEARNMANAGERincluder::getJSModel('course')->getCourseInfoBySectionId($sid);
                                JSLEARNMANAGERincluder::getJSModel('lecture')->lectureForForm($id);
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                            }
                        }else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                            }
                        }
                    }elseif($splitch[0] == "lec" && is_numeric($splitch[1])){
                        $lid = $splitch[1]; // lecture id
                        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($lid);
                        $isowner = JSLEARNMANAGERincluder::getJSModel('course')->getIfCourseOwner($courseid,$uid);
                        if(is_admin()){
                            $isowner = true;
                            $instflag = 1;
                        }
                        if($isowner){
                            if($instflag == 1){
                                $sid = JSLEARNMANAGERincluder::getJSModel('course')->getSectionByLectureId($lid);
                                JSLEARNMANAGERincluder::getJSModel('course')->getCourseInfoBySectionId($sid);
                                JSLEARNMANAGERincluder::getJSModel('lecture')->lectureForForm($lid);
                                JSLEARNMANAGERincluder::getJSModel('lecture')->getCourseLectureFiles($lid);
                                JSLEARNMANAGERincluder::getJSModel('lecture')->getCourseLectureVideos($lid);
                                apply_filters("jslm_quiz_question_for_lecture_add_detail",'',$lid,0);
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                            }
                        }else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                        }
                    }elseif($splitch[0] == "que" && is_numeric($splitch[1])){
                        $questionid = $splitch[1];
                        $lecture_id = JSLEARNMANAGERincluder::getJSModel('quiz')->getLectureIdByQuestionId($questionid);
                        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($lecture_id);
                        $isowner = JSLEARNMANAGERincluder::getJSModel('course')->getIfCourseOwner($courseid,$uid);
                        if(is_admin()){
                            $isowner = true;
                        }
                        if($isowner){
                            $sid = JSLEARNMANAGERincluder::getJSModel('course')->getSectionByLectureId($lecture_id);
                            JSLEARNMANAGERincluder::getJSModel('course')->getCourseInfoBySectionId($sid);
                            JSLEARNMANAGERincluder::getJSModel('lecture')->lectureForForm($lecture_id);
                            JSLEARNMANAGERincluder::getJSModel('lecture')->getCourseLectureFiles($lecture_id);
                            JSLEARNMANAGERincluder::getJSModel('lecture')->getCourseLectureVideos($lecture_id);
                            apply_filters("jslm_quiz_question_for_lecture_add_detail",'',$lecture_id,0);
                            JSLEARNMANAGERincluder::getJSModel('quiz')->questionforForm($questionid);
                        }else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                            }
                        }
                    }else{
                        if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                            }
                    }
                break;
                case 'lecturedetails':
                    $lid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    $splitch = explode("_",$lid);
                    if($splitch[0] == 'retake'){
                        $lid = $splitch[1];
                    }
                    $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($lid);
                    if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest() && is_numeric($lid)){
                        $isstudentenroll = JSLEARNMANAGERincluder::getJSModel('course')->isStudentEnroll($uid,$courseid);
                        $studentapproval = JSLEARNMANAGERincluder::getJSModel('student')->getStudentApprovalStatus($uid,1);
                        if($studentapproval == 1){
                            if($isstudentenroll && isset($lid)){
                                if($stdntflag == 1){
                                    JSLEARNMANAGERincluder::getJSModel('course')->getCoursedetailbyId($courseid);
                                    JSLEARNMANAGERincluder::getJSModel('course')->getCourseSection($courseid);
                                    JSLEARNMANAGERincluder::getJSModel('lecture')->getLectureById($lid);
                                    JSLEARNMANAGERincluder::getJSModel('lecture')->getCourseLectureFiles($lid);
                                    JSLEARNMANAGERincluder::getJSModel('lecture')->getCourseLectureVideos($lid);
                                    apply_filters("jslm_quiz_question_for_lecture_add_detail",'',$lid,0);
                                    JSLEARNMANAGERincluder::getJSModel('course')->getStudentCourseById($courseid, $uid);
                                }else{
                                    if(jslearnmanager::$_learn_manager_theme == true){
                                        jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                                    }else{
                                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                    }
                                }
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                            }
                        }else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_APPROVALPENDING;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(8,null,null,null,0);
                            }
                        }
                    }else{
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('lecture', $layout,$lid);
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
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'lecture');
            $module = str_replace('jslm_', '', $module);
            JSLEARNMANAGERincluder::include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jslms')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jslmstask')
            return false;
        else
            return true;
    }

    function savelecture(){
        $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('lecture')->storeLecture($data);

        if(isset($result['msg'])){
            $msg = JSLEARNMANAGERMessages::getMessage($result['msg'], 'lecture');
        }else{
            $msg = JSLEARNMANAGERMessages::getMessage($result, 'lecture');
        }
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_lecture&jslmslay=addlecture&jslearnmanagerid=lec_".$result['lectureid']);
        }else{
            if($result === JSLEARNMANAGER_ALREADY_EXIST){
                $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'lecture', 'jslmslay'=>'addlecture', 'jslearnmanagerid' =>$id));
            }else{
                $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'lecture', 'jslmslay'=>'addlecture', 'jslearnmanagerid' =>'lec_'.$result['lectureid']));
                if(isset($data['saveandnew'])){
                    $redirect = 'sec_'.$data['section_id'];
                    $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'lecture', 'jslmslay'=>'addlecture', 'jslearnmanagerid' =>$redirect));
                }
            }
        }

        wp_redirect($url);
        exit();
    }

    function removecourselecture() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-lecture') ) {
            die( 'Security check Failed' );
        }
        $lectureid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $course_id = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($lectureid);
        $result = JSLEARNMANAGERincluder::getJSModel('lecture')->deleteLectureById($lectureid,$course_id);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'lecture');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=" .$course_id.'#curriculum');
        }else{
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid' => $course_id.'#curriculum'));
        }
        wp_redirect($url);
        exit();

    }

    function downloadbyname(){
        $name = JSLEARNMANAGERrequest::getVar('name');
        $id = JSLEARNMANAGERrequest::getVar('id');
        JSLEARNMANAGERincluder::getJSModel('lecture')->getDownloadFileByName($name,$id);
    }

    function downloadAllLectureFiles(){
        $id = JSLEARNMANAGERrequest::getVar('id');
        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($id);
    }

    function downloadall() {
        $id = JSLEARNMANAGERrequest::getVar('id');
        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($id);
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $isenroll = JSLEARNMANAGERincluder::getJSModel('course')->isStudentEnroll($uid,$courseid);
        if($isenroll == true){
            JSLEARNMANAGERincluder::getJSModel('lecture')->getAllDownloads($id);
        }
        exit;
    }

}

$JSLEARNMANAGERLectureController = new JSLEARNMANAGERLectureController();
?>
