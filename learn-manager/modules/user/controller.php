<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERUserController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('user')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'users');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $instflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_instructor');
        $stdntflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_student');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_users':
                    JSLEARNMANAGERincluder::getJSModel('user')->getAllUser();
                break;
                case 'admin_editprofileform':
                case 'profileform':
                    if(JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('user', $layout);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = jslearnmanager::$_js_login_redirct_link;
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                        }
                    }elseif (!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser() && !is_admin()) {
                        $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                        $linktext = __('Select role','learn-manager');
                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                        // jslearnmanager::$_error_flag_message_for = 7;
                    }else{
                        if(is_admin()){
                            $uid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                        }
                        JSLEARNMANAGERincluder::getJSModel('user')->getDataForProfileForm($uid);
                    }
                break;
                case 'myprofile':
                    if(JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('user', $layout);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('user', $layout);
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,0);
                        }
                    }elseif (!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()) {
                        $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                        $linktext = __('Select role','learn-manager');
                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                        // jslearnmanager::$_error_flag_message_for = 7;
                    }else{
                        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
                        if($usertype == "Instructor"){
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
                                if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                                    if(JSLEARNMANAGERincluder::getObjectClass('user')->isInstructor($uid)){
                                        $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
                                        JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorProfilebyInstructorid($instructorid);
                                        JSLEARNMANAGERincluder::getJSModel('instructor')->getMyStats($instructorid);
                                        JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorCoursesByInstructorId($instructorid);
                                    }else{
                                        if(jslearnmanager::$_learn_manager_theme == true){
                                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
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
                            // if($instflag == 1){
                            //     $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageId(), 'jslmsmod'=>'instructor', 'jslmslay'=>'instructordetails'));
                            //     wp_redirect($url);
                            //     exit();
                            // }else{
                            //     if(jslearnmanager::$_learn_manager_theme == true){
                            //         jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                            //     }else{
                            //         jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                            //     }
                            // }
                            $layout = 'instructordetails';
                        }elseif($usertype == "Student"){
                            // if($stdntflag == 1){
                            //     $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageId(), 'jslmsmod'=>'student', 'jslmslay'=>'studentprofile'));
                            //     wp_redirect($url);
                            //     exit();
                            // }else{
                            //     if(jslearnmanager::$_learn_manager_theme == true){
                            //         jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                            //     }else{
                            //         jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                            //     }
                            // }
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
                            $layout = 'studentprofile';
                        }
                    }
                break;
                case 'dashboard':
                    if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
                        if($usertype == "Instructor" && $instflag == 1){
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
                                        JSLEARNMANAGERincluder::getJSModel('purchasehistory')->getDataForPurchasehistroy($uid);
                                        JSLEARNMANAGERincluder::getJSModel('earning')->getInstructorEarningsForDashboard($instructorid);
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
                            $layout = 'instructordashboard';
                            // $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'instructor', 'jslmslay'=>'instructordashboard'));
                            // wp_redirect($url);
                            // exit();
                        }elseif($usertype == "Student" && $stdntflag == 1){
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
                                    JSLEARNMANAGERincluder::getJSModel('course')->getRelatedCoursesForDashboard($uid);
                                    JSLEARNMANAGERincluder::getJSModel('purchasehistory')->getStudentPurchaseHistoryforDashboard($uid);
                                    JSLEARNMANAGERincluder::getJSModel('purchasehistory')->getStudentPurchaseHistorydetail($uid);
                                }else{
                                    JSLEARNMANAGERincluder::getJSModel('course')->getShortlistCourseByStudentidForDashoboard($studentid);
                                }
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                            }
                            $layout = 'studentdashboard';
                            // $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'student', 'jslmslay'=>'studentdashboard'));
                            // wp_redirect($url);
                            // exit();
                        }elseif (!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()) {
                            $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                            $linktext = __('Select role','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                            // jslearnmanager::$_error_flag_message_for = 7;
                        }else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,1);
                            }
                        }

                    }else{
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('user', $layout);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('user', $layout);
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,0);
                        }
                    }
                break;
                case 'register':
                    if(is_user_logged_in()){
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_ALREADYREGISTER;
                        }else{
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                        }
                    }
                break;
                case 'instructorregister':
                    $instructorregister = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('showinstructorlink');
                    if(JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        if($instructorregister == 1){
                            jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingByFor(3);
                        }else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_REGISTRATIONISDISABLED;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::getRegistrationNotAllow(1);
                            }
                        }
                    }else{
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_ALREADYREGISTER;
                        }else{
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                        }
                    }
                break;
                case 'studentregister':
                    $studentregister = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('showstudentlink');
                    if(is_user_logged_in()){
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_ALREADYREGISTER;
                        }else{
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                        }
                    }elseif($stdntflag != 1){
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_REGISTRATIONISDISABLED;
                        }else{
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::getRegistrationNotAllow(1);
                        }
                    }else{
                        if($studentregister == 1){
                            jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingByFor(3);
                        }else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = LEARN_MANAGER_REGISTRATIONISDISABLED;
                            }else{
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::getRegistrationNotAllow(1);
                            }
                        }
                    }
                break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'user');
            $module = str_replace('jslm_', '', $module);

            if($layout == 'studentdashboard' || $layout == 'studentprofile'){
                $module = 'student';
            }
            if($layout == 'instructordashboard' || $layout == 'instructordetails'){
                $module = 'instructor';
            }

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

    function saveprofile() {
        $data = JSLEARNMANAGERrequest::get('post');
        if (is_admin()) {
           if($data['role'] == "Student"){
                $url = admin_url("admin.php?page=jslm_student");
           }elseif($data['role'] == "Instructor"){
                $url = admin_url("admin.php?page=jslm_instructor");
           }else{
                $url = admin_url("admin.php?page=jslm_user&jslmslay=users");
           }
        } else{
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid'), 'jslmsmod'=>'user', 'jslmslay'=>'myprofile'));
        }
        if(!is_admin()){
            $data['id'] = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
            $data['uid'] = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerwpidbyuserid($data['id']);
            $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($data['id']);
            if($usertype == "Instructor"){
                $data['role'] = "Instructor";
                $data['lmsid'] = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($data['id']);
            }else{
                $data['role'] = "Student";
                $data['lmsid'] = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($data['id']);
            }
        }
        $result = JSLEARNMANAGERincluder::getJSModel('user')->storeProfile($data);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'profile');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_student");
        wp_redirect($url);
        exit();
    }

    function remove() { // only delete LMS records
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-users') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('user')->deleteUserData($ids);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'user');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_user&jslmslay=users");
        wp_redirect($url);
        die();
    }

    function enforceremove() { // delete eitire user
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-users') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('user')->enforceDeleteUserData($ids);
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'user');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_user&jslmslay=users");
        wp_redirect($url);
        die();
    }

    function publish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('user')->publishUnpublish($ids, 1); //  for publish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'user');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_user&jslmslay=users");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $result = JSLEARNMANAGERincluder::getJSModel('user')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSLEARNMANAGERmessages::getMessage($result, 'user');
        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jslm_user&jslmslay=users");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }
}
$JSLEARNMANAGERUserController = new JSLEARNMANAGERUserController();
?>
