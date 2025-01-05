<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCourseController {

    private $_msgkey;

    function __construct() {

        $this->_msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'courses');
        $studentflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_student');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $instflag  = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_instructor');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_coursequeue':
                    JSLEARNMANAGERincluder::getJSModel('course')->getAllCourses(2);
                break;
                case 'admin_courses':
                    JSLEARNMANAGERincluder::getJSModel('course')->getAllCourses(1);
                break;
                case 'admin_coursedetail':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    if($id != ""){
                        JSLEARNMANAGERincluder::getJSModel('course')->getCoursedetailForEditbyId($id);
                        JSLEARNMANAGERincluder::getJSModel('course')->getCourseSectionforEditCourse($id);
                        do_action("jslm_coursereview_get_course_review_for_detail",$id);
                    }else{
                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::getNoRecordFound('','',1);
                    }
                break;
                case 'admin_formcourse':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    JSLEARNMANAGERincluder::getJSModel('course')->getCoursebyId($id);
                    JSLEARNMANAGERincluder::getJSModel('course')->getCourseforForm($id);
                    JSLEARNMANAGERincluder::getJSModel('course')->getCourseSection($id);
                    JSLEARNMANAGERincluder::getJSModel('instructor')->getinstructorlistajaxforcourse();
                    $user = JSLEARNMANAGERincluder::getJSModel('user')->getUsernameAndEmailFromProfile($uid);
                    jslearnmanager::$_data['user'] = $user;
                break;
                case 'courselist':
                    $search = JSLEARNMANAGERrequest::getVar('issearchform', 'post');
                    JSLEARNMANAGERincluder::getJSModel('course')->getCourses($search);
                    jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1);
                    if(jslearnmanager::$_learn_manager_theme == true){
                        JSLEARNMANAGERincluder::getJSModel('course')->countCourseByCategory();
                        JSLEARNMANAGERincluder::getJSModel('course')->getLatestCourses();
                        JSLEARNMANAGERincluder::getJSModel('instructor')->getTotalCoursesByInstructor();
                        JSLEARNMANAGERincluder::getJSModel('course')->getCourseByPriceType();
                    }

                break;
                case 'coursebycategory':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    if(isset($id)){
                        JSLEARNMANAGERincluder::getJSModel('course')->getCoursesByCategoryId($id);
                        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            JSLEARNMANAGERincluder::getJSModel('course')->countCourseByCategory();
                            JSLEARNMANAGERincluder::getJSModel('course')->getLatestCourses();
                            JSLEARNMANAGERincluder::getJSModel('instructor')->getTotalCoursesByInstructor();
                            JSLEARNMANAGERincluder::getJSModel('course')->getCourseByPriceType();
                        }
                    }else{
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_NORECORDFOUND;
                        }else{
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::getNoRecordFound();
                        }
                    }
                break;
                case 'shortlistcourses':
                    $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
                    if($usertype != "Instructor" && $studentflag == 1){
                        $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
                        JSLEARNMANAGERincluder::getJSModel('course')->getShortlistCourseByStudentid($studentid);
                        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            JSLEARNMANAGERincluder::getJSModel('course')->countCourseByCategory();
                            JSLEARNMANAGERincluder::getJSModel('course')->getLatestCourses();
                            JSLEARNMANAGERincluder::getJSModel('instructor')->getTotalCoursesByInstructor();
                            JSLEARNMANAGERincluder::getJSModel('course')->getCourseByPriceType();
                        }
                    }elseif (!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()) {
                        $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                        $linktext = __('Select role','learn-manager');
                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                        // jslearnmanager::$_error_flag_message_for = 7;
                    }else{
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_NORECORDFOUND;
                        }else{
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(3,null,null,null,0);
                        }
                    }
                break;
                case 'coursedetails':
                case 'homecoursedetail':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    $redirectlayout = JSLEARNMANAGERrequest::getVar('layout');
                    $canviewdetail = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allow_view_coursedetail');
                    if(!is_numeric($id)){
                        $id = JSLEARNMANAGERincluder::getJSModel('common')->parseID($id);
                    }

                    if(JSLEARNMANAGERincluder::getObjectClass('user')->isStudent($uid) && $canviewdetail == 0){
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                        }else{
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                        }
                    }else{
                        JSLEARNMANAGERincluder::getJSModel('course')->getCoursedetailbyId($id);
                        do_action("jslm_coursereview_get_course_review_for_detail",$id);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            JSLEARNMANAGERincluder::getJSModel('course')->getLatestCourses();
                            // JSLEARNMANAGERincluder::getJSModel('course')->getRelatedCourse($id);
                            JSLEARNMANAGERincluder::getJSModel('course')->countCourseByCategory();
                        }
                        JSLEARNMANAGERincluder::getJSModel('course')->getCourseSection($id);
                        jslearnmanager::$_data['config'] = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('socialmedia');
                        $isstudentenroll = JSLEARNMANAGERincluder::getJSModel('course')->isStudentEnroll($uid,$id);
                        if($isstudentenroll){
                            JSLEARNMANAGERincluder::getJSModel('course')->getStudentCourseById($id, $uid);
                        }
                    }
                break;
                case 'addcourse':
                    $addcourseflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allow_user_to_add_course');
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    $isinstructor = JSLEARNMANAGERincluder::getObjectClass('user')->isInstructor($uid);
                    if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        $isowner = JSLEARNMANAGERincluder::getJSModel('course')->getIfCourseOwner($id,$uid);
                        if($id == "")
                            $isowner = 1;
                        if($isinstructor && $instflag == 1 && $isowner){
                            if($addcourseflag == 1){
                                JSLEARNMANAGERincluder::getJSModel('course')->getCourseforForm($id);
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOADDCOURSE;
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
                    }elseif(JSLEARNMANAGERincluder::getObjectClass('user')->isguest() && isset($id)){
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('course', $layout,$id);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = jslearnmanager::$_js_login_redirct_link;
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                        }
                    }elseif(JSLEARNMANAGERincluder::getObjectClass('user')->isguest() && !isset($id)){
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('course', $layout);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = jslearnmanager::$_js_login_redirct_link;
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                        }
                    }elseif (!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()) {
                        $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                        $linktext = __('Select role','learn-manager');
                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                        // jslearnmanager::$_error_flag_message_for = 7;
                    }

                break;
                case 'editcourse':
                    $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                    if(!JSLEARNMANAGERincluder::getObjectClass('user')->isguest()){
                        $isdisabled = JSLEARNMANAGERincluder::getObjectClass('user')->isdisabled();
                        if(!$isdisabled){
                            $isowner = JSLEARNMANAGERincluder::getJSModel('course')->getIfCourseOwner($id,$uid);
                            if($isowner && $instflag){
                                JSLEARNMANAGERincluder::getJSModel('course')->getCoursedetailForEditbyId($id);
                                JSLEARNMANAGERincluder::getJSModel('course')->getCourseSectionforEditCourse($id);
                                do_action("jslm_coursereview_get_course_review_for_detail",$id);
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    JSLEARNMANAGERincluder::getJSModel('course')->getLatestCourses();
                                    JSLEARNMANAGERincluder::getJSModel('course')->getRelatedCourse($id);
                                }
                                JSLEARNMANAGERincluder::getJSModel('course')->countCourseByCategory();
                                apply_filters("jslm_paymentplan_get_course_payment_plan","",$id,0);
                                jslearnmanager::$_data['config'] = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('socialmedia');
                            }else{
                                if(jslearnmanager::$_learn_manager_theme == true){
                                    jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWED;
                                }else{
                                    jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(4,null,null,null,0);
                                }
                            }
                        }else{
                            if(jslearnmanager::$_learn_manager_theme == true){
                                jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(6,null,null,null,1);
                            }
                        }
                    }elseif (!JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()) {
                        $link = jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager'));
                        $linktext = __('Select role','learn-manager');
                        jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(7 , $link , $linktext,1);
                        // jslearnmanager::$_error_flag_message_for = 7;
                    }else{
                        jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('course', $layout,$id);
                        if(jslearnmanager::$_learn_manager_theme == true){
                            jslearnmanager::$_error_flag_message = LEARN_MANAGER_GUEST;
                        }else{
                            $link = jslearnmanager::$_js_login_redirct_link;
                            $linktext = __('Login','learn-manager');
                            jslearnmanager::$_error_flag_message = JSLEARNMANAGERLayout::setMessageFor(1,$link,$linktext,null,1);
                        }
                    }
                break;
                case 'categorieslist':
                    JSLEARNMANAGERincluder::getJSModel('course')->getCategoriesCountList();
                break;
            }
            $module = (is_admin()) ? 'page' : 'jslmsmod';
            $module = JSLEARNMANAGERrequest::getVar($module, null, 'course');
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

    function savecourse() {
        $data = JSLEARNMANAGERrequest::get('post');
        $result = JSLEARNMANAGERincluder::getJSModel('course')->storeCourse($data);
        if(is_admin()){
           $url = admin_url("admin.php?page=jslm_course");
        }else{
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$result['courseid']));
        }
        if($result['courseid'] == 0){
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'courselist'));
        }
        $msg = JSLEARNMANAGERMessages::getMessage($result['msg'], 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function saveenrollment(){
        $courseid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_course&jslmslay=coursedetails");
        }else{
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid'), 'jslmsmod'=>'course', 'jslmslay'=>'coursedetails', 'jslearnmanagerid' => $courseid));
        }
        $getprice = apply_filters("jslm_paidcourse_get_course_price",0,$courseid);
        $access_type = JSLEARNMANAGERincluder::getJSModel('accesstype')->getCourseAccessType($courseid);
        if($getprice == 0 && $access_type == "Free"){
            $result = JSLEARNMANAGERincluder::getJSModel('course')->storeEnrollmentinCourse($courseid);
        }else{
            $result = NOT_ENROLLED;
        }
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function publish(){
        $pagenum = JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid');
        $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_course&jslmslay=coursedetail");
        }else{
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>$pagenum, 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$id));
        }
        $result = JSLEARNMANAGERincluder::getJSModel('course')->publishUnpublish($id,1,$uid);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function approve(){
        $pagenum = JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid');
        $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_course&jslmslay=coursequeue");
        }
        if($pagenum)
            $url .= "&pagenum=".$pagenum;
        $result = JSLEARNMANAGERincluder::getJSModel('course')->approveQueueCourse($id); // 1 for approve
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function reject(){
        $pagenum = JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid');
        $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_course&jslmslay=coursequeue");
        }
        if($pagenum)
            $url .= "&pagenum=".$pagenum;
        $result = JSLEARNMANAGERincluder::getJSModel('course')->rejectQueueCourse($id); // 0 for reject
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function unpublish(){
        $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_course&jslmslay=courses");
            if($pagenum)
                $url .= "&pagenum=".$pagenum;
        }else{
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid'), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse','jslearnmanagerid'=>$id));
        }
        $result = JSLEARNMANAGERincluder::getJSModel('course')->publishUnpublish($id,0,$uid);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function removesection(){
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-section') ) {
            die( 'Security check Failed' );
        }
        $sectionid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $course_id = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdBySectionId($sectionid);
        $result = JSLEARNMANAGERincluder::getJSModel('course')->deleteSectionbyId($sectionid,$course_id);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'section');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=" .$course_id."#curriculum");
        }else{
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('page_id'), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid' => $course_id));
            $url .= "#curriculum";
        }
        wp_redirect($url);
        exit();
    }

    function downloadbyname(){
        $name = JSLEARNMANAGERrequest::getVar('name');
        $id = JSLEARNMANAGERrequest::getVar('id');
        JSLEARNMANAGERincluder::getJSModel('course')->getDownloadFileByName($name,$id);
    }

    function removecourse() {
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-course') ) {
            die( 'Security check Failed' );
        }
        $courseid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $result = JSLEARNMANAGERincluder::getJSModel('course')->deleteCourse($courseid,$uid);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if(is_admin()){
            if($call_from == 2){
                $url = admin_url("admin.php?page=jslm_course&jslmslay=coursequeue");
            }else{
                $url = admin_url("admin.php?page=jslm_course");
            }
        }elseif(jslearnmanager::$_learn_manager_theme == true){
            $url = esc_url(jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'instructor', 'jslmslay'=>'instructordashboard')));
        }else{
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'instructor', 'jslmslay'=>'mycourses'));
        }
        wp_redirect($url);
        exit();
    }

    function remove() { // For admin
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-course') ) {
            die( 'Security check Failed' );
        }
        $courseid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $result = JSLEARNMANAGERincluder::getJSModel('course')->deleteCourse($courseid,$uid);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if(is_admin()){
            if($call_from == 2){
                $url = admin_url("admin.php?page=jslm_course&jslmslay=coursequeue");
            }elseif($call_from== 1){
                $url = admin_url("admin.php?page=jslm_course");
            }else{
                $url = admin_url("admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid=".JSLEARNMANAGERrequest::getVar('instructorid'));
            }
        }
        wp_redirect($url);
        exit();
    }

    function removeall() { // For admin
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-course') ) {
            die( 'Security check Failed' );
        }
        $ids = JSLEARNMANAGERrequest::getVar('jslearnmanager-cb');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $result = JSLEARNMANAGERincluder::getJSModel('course')->deleteAllCourse($ids,$uid);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if(is_admin()){
            $url = admin_url("admin.php?page=jslm_course");
        }
        wp_redirect($url);
        exit();
    }

    function courseenforcedelete(){
        $nonce = JSLEARNMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-course') ) {
            die( 'Security check Failed' );
        }
        $courseid = JSLEARNMANAGERrequest::getVar('courseid');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $result = JSLEARNMANAGERincluder::getJSModel('course')->courseEnforceDelete($courseid,$uid);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if(is_admin() & $call_from == 1){
            $url = admin_url("admin.php?page=jslm_course");
        }elseif(is_admin() & $call_from == 2){
            $url = admin_url("admin.php?page=jslm_course&jslmslay=coursequeue");
        }
        wp_redirect($url);
        exit();
    }

    function removeshortlistcourse(){
        $rid = JSLEARNMANAGERrequest::getVar('rid');
        $cid = JSLEARNMANAGERrequest::getVar('cid');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $result = JSLEARNMANAGERincluder::getJSModel('course')->deleteShortListedCourse($cid,$rid,1);
        $msg = JSLEARNMANAGERMessages::getMessage($result, 'shortlistcourse');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        if($call_from == 1){
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('page_id'), 'jslmsmod'=>'course', 'jslmslay'=>'shortlistcourses'));
        }else{
            $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('page_id'), 'jslmsmod'=>'student', 'jslmslay'=>'studentdashboard'));
        }
        wp_redirect($url);
        exit();
    }
}

$JSLEARNMANAGERCourseController = new JSLEARNMANAGERCourseController();
?>