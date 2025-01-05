<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERUserModel {

    private $_param_array;

    function jsGetPrefix(){
        global $wpdb;
        if(is_multisite()) {
            $prefix = $wpdb->base_prefix;
        }else{
            $prefix = jslearnmanager::$_db->prefix;
        }
        return $prefix;
    }

    function getUserNamebyId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT name FROM `#__js_learnmanager_user` WHERE id = " . $id;
        $db->setQuery($query);
        return $db->loadResult();
    }

    function storeUserRole($data) {
        if (empty($data))
            return false;
        $row = JSLEARNMANAGERincluder::getJSTable('users');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->check()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        return JSLEARNMANAGER_SAVED;
    }

    function getChangeRolebyId($c_id){
        if (is_numeric($c_id) == false)
            return false;
        $db = new jslearnmanagerdb;
        $query = "SELECT a.*,a.created_at AS dated,u.user_login,u.id AS wpuid
                    FROM `#__js_learnmanager_user` AS a
                    LEFT JOIN " . $this->jsGetPrefix() . "users AS u ON u.id = a.uid
                    WHERE a.id = " . $c_id;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObject();
        return;
    }

    function sorting() {

        jslearnmanager::$_data['sorton'] = JSLEARNMANAGERrequest::getVar('sorton', 'post', 3);
        jslearnmanager::$_data['sortby'] = JSLEARNMANAGERrequest::getVar('sortby', 'post', 2);
        switch (jslearnmanager::$_data['sorton']) {
            case 3:
                $sort_string = 'user.created_at';
                break;
            case 2: // category
                $sort_string = ' user.name ';
                break;
        }
        if (jslearnmanager::$_data['sortby'] == 1) {
            $sort_string .= ' ASC ';
        } else {
            $sort_string .= ' DESC ';
        }
        jslearnmanager::$_data['combosort'] = jslearnmanager::$_data['sorton'];

        return $sort_string;
    }

    function getProfileByUid($uid) {
        if (is_numeric($uid) == false)
            return false;
        // user stats
        $db = new jslearnmanagerdb();
        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
        if($usertype == "Instructor"){
            $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
            $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE instructor_id = " . $instructorid;
            $db->setQuery($query);
            jslearnmanager::$_data['totalcourses'] = $db->loadResult();
            jslearnmanager::$_data['featuredcourses'] = apply_filters("jslm_featuredcourse_user_profile_feature_course",'',$instructorid);
            $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE instructor_id = " . $instructorid. " AND expire_date < CURDATE() ";
            $db->setQuery($query);
            jslearnmanager::$_data['expiredcourses'] = $db->loadResult();
            $query = "SELECT COUNT(DISTINCT sc.student_id) as totalstudents
                            FROM `#__js_learnmanager_instructor` AS r
                                JOIN `#__js_learnmanager_course` AS c ON c.instructor_id = r.id
                                LEFT JOIN `#__js_learnmanager_student_enrollment` AS sc ON sc.course_id = c.id
                                WHERE r.id =" .$instructorid;
            $db->setQuery($query);
            jslearnmanager::$_data['totalstudents'] = $db->loadResult();
            jslearnmanager::$_data[0]['avgreview'] = apply_filters("jslm_coursereview_get_instructor_average_rating",0,$instructorid);
        }elseif($usertype == "Student"){

            $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
            $query = "SELECT COUNT(id) FROM `#__js_learnmanager_student_enrollment` WHERE student_id = " . $studentid;
            $db->setQuery($query);
            jslearnmanager::$_data['totalcourses'] = $db->loadResult();
            $query = "SELECT COUNT(id) FROM `#__js_learnmanager_wishlist` WHERE student_id = " . $studentid;
            $db->setQuery($query);
            jslearnmanager::$_data['totalshorlist'] = $db->loadResult();
        }
        $query = "SELECT * FROM `#__js_learnmanager_user` WHERE id = " . $uid;
        $db->setQuery($query);
        jslearnmanager::$_data['user'] = $db->loadObject();
        jslearnmanager::$_data['objectid'] = 1;
        if(!empty(jslearnmanager::$_data['user']))
            jslearnmanager::$_data['user']->location = JSLEARNMANAGERincluder::getJSModel('country')->getCountryNameById(jslearnmanager::$_data['user']->country_id);
        return;
    }

    function checkUserBySocialID($socialid) {
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_user` WHERE socialid = '" . $socialid . "'";
        $db->setQuery($query);
        $result = $db->loadResult($query);
        return $result;
    }

    function getAllUser() {
        // sorting
        $sort_string = $this->sorting();
        //Filters
        $isadmin = is_admin();
        $username = ($isadmin) ? 'searchname' : 'users';
        $searchname = isset(jslearnmanager::$_data['filter']['searchname']) ? jslearnmanager::$_data['filter']['searchname'] : '';
         // JSLEARNMANAGERrequest::getVar('searchname');
        $email = isset(jslearnmanager::$_data['filter']['email']) ? jslearnmanager::$_data['filter']['email'] : '';;
         // JSLEARNMANAGERrequest::getVar('email');
        $status = isset(jslearnmanager::$_data['filter']['status']) ? jslearnmanager::$_data['filter']['status'] : '';;
         // JSLEARNMANAGERrequest::getVar("status");
        $country = isset(jslearnmanager::$_data['filter']['country']) ? jslearnmanager::$_data['filter']['country'] : '';;
         // JSLEARNMANAGERrequest::getVar("country");

        $inquery = '';
        $clause = ' WHERE ';
        if ($searchname != null) {
            //$title = esc_sql($title);
            $inquery .= $clause . "user.username LIKE '%" . $searchname . "%'";
            $clause = ' AND ';
        }
        if (is_string($email)){
            $inquery .= $clause . " user.email LIKE '%" . $email . "%'";
            $clause = ' AND ';
        }
        if (is_numeric($status)){
            $inquery .= $clause . " user.status = " . $status;
            $clause = ' AND ';
        }
        if(is_numeric($country)){
            $inquery .= $clause . " user.country_id = " . $country;
            $clause = ' AND ';
        }
        jslearnmanager::$_data['filter']['searchname'] = $searchname;
        jslearnmanager::$_data['filter']['email'] = $email;
        jslearnmanager::$_data['filter']['status'] = $status;
        jslearnmanager::$_data['filter']['country'] = $country;

        $db = new jslearnmanagerdb();
        //Pagination
        $query = "SELECT COUNT(user.id) FROM `#__js_learnmanager_user` AS user ";
        $query.=$inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        //Data
        $query = "SELECT user.`id`, `username`, `email`, `facebook_url`, `twitter`, `linkedin`, `country_name` as location, `user_role_id`, user.`status`, urole.role, user.params,
                    (SELECT image FROM `#__js_learnmanager_student` WHERE user.id = user_id ) as stuimage, (SELECT image FROM `#__js_learnmanager_instructor` WHERE user.id = user_id ) as insimage
                    FROM `#__js_learnmanager_user` AS user
                        LEFT JOIN `#__js_learnmanager_country` AS co ON co.id = user.country_id
                        LEFT JOIN `#__js_learnmanager_user_role` AS urole ON user.user_role_id = urole.id";
        $query.=$inquery;
        $query.=" ORDER BY ".$sort_string;
        $query.=" LIMIT " . JSLEARNMANAGERpagination::$_offset . "," . JSLEARNMANAGERpagination::$_limit;

        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObjectList();
        return;
    }

    function getInstructorByInstrcutorName($sname) {
        $db = new jslearnmanagerdb();
        $query = "SELECT id,name FROM `#__js_learnmananger_instructor`
                    WHERE name LIKE '" . $sname . "%'
                    AND status = 1 LIMIT 0,10";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if (empty($result))
            return null;
        else
            return $result;
    }

    function getInstructorDataForDashboardTab($id) {
        if (is_numeric($id) == false)
            return false;
        $sort_string = JSLEARNMANAGERincluder::getJSModel('course')->sorting();
        $db = new jslearnmanagerdb();
        $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($id);
        if($instructorid == null && $instructorid == '')
            return false;
        // For Search
        $curdate = date_i18n('Y-m-d');
        $title = JSLEARNMANAGERrequest::getVar('coursetitle');
        $category = JSLEARNMANAGERrequest::getVar('category');
       
        $inquery = '';
       
        if (is_string($title)){
            $inquery .= " AND c.title LIKE '%" . $title . "%'";
        }
        if(is_numeric($category)){
            $inquery .= " AND c.category_id = " . $category;
        }

        jslearnmanager::$_data['filter']['coursetitle'] = $title;
        jslearnmanager::$_data['filter']['category'] = $category;

        // user stats
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE instructor_id = " . $instructorid;
        $db->setQuery($query);
        jslearnmanager::$_data['totalcourses'] = $db->loadResult();

        // user my courses
        $query = "SELECT COUNT(c.id) FROM  `#__js_learnmanager_course` AS c
                    JOIN `#__js_learnmanager_course_access_type`as accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                    WHERE c.instructor_id=".$instructorid;
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data['mycoursespagination'] = JSLEARNMANAGERpagination::getPagination($total);
        do_action("jslm_featuredcourse_select_query_data");
        do_action("jslm_paidcourse_join_select_query_data",1);
        $query = "SELECT c.id as course_id, c.title as title, accesstype.access_type as access_type, ". jslearnmanager::$_addon_query['select'] ." c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c.course_level as level, c.course_status,c.start_date as start_date,c.expire_date as expire_date,c.description,
                         c.created_at as created_at, c.isapprove , intr.approvalstatus,c.file,
                         cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, c.params,intr.image as instructor_image,
                         COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students
                        From `#__js_learnmanager_course` as c
                            LEFT JOIN `#__js_learnmanager_category` as cat ON cat.id = c.category_id
                            LEFT JOIN `#__js_learnmanager_instructor` as intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` as sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` as sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` as stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` as stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            JOIN `#__js_learnmanager_course_access_type`as accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            WHERE c.instructor_id = " .$instructorid;
        $query .= $inquery;
        $query .= " GROUP BY c.id";
        $query .= " ORDER BY c.id DESC";
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset ." , ". JSLEARNMANAGERpagination::$_limit;
        do_action("reset_jslmaddon_query");
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        foreach($courses as $course => $key){
            $courses[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",0,$key->course_id,1);
        }
        jslearnmanager::$_data['mycourses'] = $courses;

        $query = "SELECT COUNT(DISTINCT sc.student_id) as totalstudents
                    FROM `#__js_learnmanager_instructor` AS r
                        JOIN `#__js_learnmanager_course` AS c ON c.instructor_id = r.id
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS sc ON sc.course_id = c.id
                        WHERE r.id =" .$instructorid;
        $db->setQuery($query);
        jslearnmanager::$_data['totalstudents'] = $db->loadResult();

        jslearnmanager::$_data['avgreview'] = apply_filters("jslm_coursereview_get_instructor_average_rating",0,$instructorid);
        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1);

        return;
    }

    function getStudentDataForDashboardTab($id){
        if (is_numeric($id) == false)
            return false;

        $db = new jslearnmanagerdb();

        // For Search
        $curdate = date_i18n('Y-m-d');
        $title = isset(jslearnmanager::$_data['filter']['mycoursetitle']) ? jslearnmanager::$_data['filter']['mycoursetitle'] : '';
         // JSLEARNMANAGERrequest::getVar('mycoursetitle');
        $category = isset(jslearnmanager::$_data['filter']['mycoursecategory']) ? jslearnmanager::$_data['filter']['mycoursecategory'] : '';
         // JSLEARNMANAGERrequest::getVar('mycoursecategory');
        $inquery = '';
        if (is_string($title)){
            $inquery .= " AND c.title LIKE '%" . $title . "%'";
        }
        if(is_numeric($category)){
            $inquery .= " AND c.category_id = " . $category;
        }

        jslearnmanager::$_data['filter']['mycoursetitle'] = $title;
        jslearnmanager::$_data['filter']['mycoursecategory'] = $category;


        $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($id);
        if($studentid == null && $studentid == '')
            return false;

        // user stats
        $query = "SELECT COUNT(sc.id) FROM `#__js_learnmanager_student_enrollment` AS sc
                    INNER JOIN `#__js_learnmanager_course` AS c
                    LEFT JOIN `#__js_learnmanager_instructor` AS instructor ON instructor.id = c.instructor_id
                    LEFT JOIN `#__js_learnmanager_user` AS user ON user.id = instructor.user_id AND user.status = 1
                    JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                    WHERE c.id = sc.course_id AND sc.student_id = " . $studentid ." AND c.course_status = 1 AND c.isapprove = 1";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total,'mycourses');

        // user my courses
        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data');
        do_action('jslm_featuredcourse_select_query_data');
        do_action("jslm_paidcourse_join_select_query_data",1);
        $query = "SELECT c.id as course_id, c.title as title, accesstype.access_type as access_type, c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c.course_level as level, c.file as fileloc,
                        cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, c.params, stdnt_c.created_at as enrolleddate,
                        COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_all.id) as total_students, ". jslearnmanager::$_addon_query['select'] ."  intr.image as instructor_image,c.description
                    FROM `#__js_learnmanager_course` AS c
                        LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                        LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                        LEFT JOIN `#__js_learnmanager_user` AS user ON user.id = intr.user_id AND user.status = 1
                        LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                        LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_all ON c.id = stdnt_all.course_id
                        -- LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                        ".jslearnmanager::$_addon_query['join']."
                        -- LEFT JOIN  `#__js_learnmanager_student_progress` AS p ON sect_lec.id = p.lecture_id AND p.student_id =.$studentid.
                        JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                        WHERE stdnt_c.student_id = " .$studentid ." AND c.course_status = 1 AND c.isapprove = 1";
        $query .= $inquery;
        $query .= " GROUP BY c.id";
        $query .= " ORDER BY stdnt_c.created_at DESC";
        $query .=" LIMIT " . JSLEARNMANAGERpagination::$_offset . "," . JSLEARNMANAGERpagination::$_limit;
        do_action('reset_jslmaddon_query');
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        foreach($courses as $course => $key){
            $courses[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$key->course_id,1);
        }
        jslearnmanager::$_data['mycourses'] = $courses;
        $query = "SELECT * FROM `#__js_learnmanager_user` WHERE id = " . $id;
        $db->setQuery($query);
        jslearnmanager::$_data['user'] = $db->loadObject();
        jslearnmanager::$_data['user']->location = JSLEARNMANAGERincluder::getJSModel('country')->getCountryNameById(jslearnmanager::$_data['user']->country_id);
        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1);

        return;
    }

    function getChartColor() {
        $colors = array('#3366CC', '#DC3912', '#FF9900', '#109618', '#990099', '#B77322', '#8B0707', '#AAAA11', '#316395', '#DD4477', '#3B3EAC', '#ADD042', '#9D98CA', '#ED3237', '#585570', '#4E5A62', '#5CC6D0');
        return $colors;
    }

    function getUserInfoForForm($id){
        if (!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $instflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_instructor');
        $stdntflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_student');
        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($id);
        if($usertype == 'Instructor'){
            if($instflag == 1){
                $query = "SELECT i.image, i.name, i.bio, i.gender, c.country_name, u.*, i.id as lmsid, c.id as country_id, i.approvalstatus
                            FROM `#__js_learnmanager_user` AS u
                                INNER JOIN `#__js_learnmanager_instructor` AS i ON u.id = i.user_id
                                LEFT JOIN `#__js_learnmanager_country` AS c ON c.id = u.country_id
                                WHERE u.id =" .$id;
            }else{
                jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                return false;
            }
        }elseif($usertype == 'Student'){
            if($stdntflag == 1){
                $query = "SELECT s.image, s.name, s.bio, s.gender, c.country_name, u.*, s.id as lmsid , c.id as country_id,s.approvalstatus
                            FROM `#__js_learnmanager_user` AS u
                                INNER JOIN `#__js_learnmanager_student` AS s ON u.id = s.user_id
                                LEFT JOIN `#__js_learnmanager_country` AS c ON c.id = u.country_id
                                WHERE u.id =" .$id;
            }else{
                jslearnmanager::$_error_flag_message = LEARN_MANAGER_USERNOTALLOWEDTOVIEW;
                return false;
            }
        }
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObject();
        return;
    }

    function getDataForProfileForm($id){
        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingByFor(3);
        $this->getUserInfoForForm($id);
        return;
    }

    function storeProfile($data, $call_flag = 0) {
        if (empty($data)){
            return false;
        }
        $row = JSLEARNMANAGERincluder::getJSTable('users');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if($call_flag != 2){ // auto generate profile from wp user case (will not have any files or custom fields)
            if($call_flag == 0){
                $data['params'] = $this->getParamsForProfile($data,$data['id']);
                $data['bio']= sanitize_textarea_field(wptexturize(stripslashes(strip_tags($_POST['bio']))));
                if(!isset($data['firstname']) && $data['firstname'] == '')
                    $data['firstname'] = $data['jslearnmanager_user_first'];
                if(!isset($data['lastname']) && $data['lastname'] == '')
                    $data['lastname'] = $data['jslearnmanager_user_last'];
            }else{
                $pdata = JSLEARNMANAGERrequest::get('post');
                $data['params'] = $this->getParamsForProfile($pdata);
                $data['bio']= sanitize_textarea_field(wptexturize(stripslashes(strip_tags($_POST['bio']))));
                if($data['firstname'] == '' && !isset($data['firstname']))
                    $data['firstname'] = $data['jslearnmanager_user_first'];
                if($data['lastname'] == '' && !isset($data['lastname']))
                    $data['lastname'] = $data['jslearnmanager_user_last'];
            }
            $data['autogenerated']= 0;
        }
        if(!isset($data['firstname']) && $data['firstname'] == '')
            $data['firstname'] = $data['jslearnmanager_user_first'];
        if(!isset($data['lastname']) && $data['lastname'] == '')
            $data['lastname'] = $data['jslearnmanager_user_last'];
        $data['name'] = $data['firstname']." ".$data['lastname'];

        //custom field code end
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        $data['user_id'] = $row->id;
        if($data['role'] == 'Student'){
            $studentid = JSLEARNMANAGERincluder::getJSModel('student')->storeStudentProfile($data);
        }elseif($data['role'] == 'Instructor'){
            $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->storeInstructorProfile($data);
        }
        if($data['id'] == ''){
            $profileid = $row->id;
            // email template code
            JSLEARNMANAGERincluder::getJSModel('emailtemplate')->sendMail(3, 1 ,$profileid);
        }else{
            $profileid = $data['id'];
        }

        if($call_flag != 2){ // auto generate profile from wp user case (will not have any files or custom fields)
            if($call_flag == 0){
                //delete images deleted by user
                if((isset($data['validimage']) &&  $data['validimage'] == 1) || (isset($data["jslms_user_image_del"]) && $data["jslms_user_image_del"] == 1)){
                    $this->deleteProfileImage($data['user_id']);
                }
            }
            // upload new images
            if($_FILES['profilephoto']['error'] != 4){
                $this->storeProfileImages($data['user_id']);
            }
            // Storing Attachments
            //removing custom field attachments
            if( isset($this->_param_array['customflagfordelete'])){
                if($this->_param_array['customflagfordelete'] == true){
                    foreach ($this->_param_array['custom_field_namesfordelete'] as $key) {
                        $res = $this->removeFileCustom($profileid,$key);
                    }
                }
            }

            //storing custom field attachments
            if(isset($this->_param_array['customflagforadd'])){
                if($this->_param_array['customflagforadd'] == true){
                    foreach ($this->_param_array['custom_field_namesforadd'] as $key) {
                        if ($_FILES[$key]['size'] > 0) { // file
                            $res = $this->uploadFileCustom($profileid,$key);
                        }
                    }
                }
            }
        }
        if($call_flag != 0){
            return $profileid;
        }
        return JSLEARNMANAGER_SAVED;
    }

    function deleteProfileImage($profileid, $ajax = 0) {
        if(! is_numeric($profileid))
            return;
        $db = new jslearnmanagerdb();
        // select photo so that custom uploaded files are not delted
        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($profileid);
        if($usertype == "Instructor"){
            $query = "SELECT i.image FROM `#__js_learnmanager_instructor` AS i WHERE i.user_id = ".$profileid;
        }else if($usertype == 'Student'){
            $query = "SELECT s.image FROM `#__js_learnmanager_student` AS s WHERE s.user_id = ".$profileid;
        }
        $db->setQuery($query);
        $photo = $db->loadResult();
        // path to file so that it can be removed
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/profile/profile_' .$profileid;
        $files = glob( $path . '/*');
        $filename = basename($photo);
        if($filename != "" && $filename != null){
            $explodeimage = explode(".", $filename); // For removing  resizing image
            $explodeimage = $explodeimage[0].'_1.'.$explodeimage[1];
            foreach($files as $file){
                $filename = str_replace("userprofilephoto_", '', $filename);
                $explodeimage = str_replace("userprofilephoto_", '', $explodeimage);
                if(!empty($file)){
                    if(is_file($file) && strstr($file, $filename) ) {
                        unlink($file);
                    }
                    if(is_file($file) && strstr($file, $explodeimage)){
                        unlink($file);
                    }
                }
            }

            // $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($profileid);
            if($usertype == 'Instructor'){
                $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($profileid);
                $query = "UPDATE `#__js_learnmanager_instructor` SET image = '' WHERE id = ".$instructorid;
            }elseif($usertype == 'Student'){
                $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($profileid);
                $query = "UPDATE `#__js_learnmanager_student` SET image = '' WHERE id = ".$studentid;
            }
            $db->setQuery($query);
            if($ajax == 1){
                if($db->query()){
                    return 1;
                }else{
                    return 0;
                }
            }else{
                $db->query();
            }
        }
        return;
    }

    function storeProfileImages($profileid){ // jslearnmanager user id
        if(! is_numeric($profileid))
            return;
        $file = filter_var($_FILES['profilephoto'], FILTER_SANITIZE_STRING);

        $file_name = 'userprofilephoto_'.sanitize_file_name($_FILES['profilephoto']['name']);
        $file = array(
                    'name'     => sanitize_file_name($file_name),
                    'type'     => filter_var($_FILES['profilephoto']['type'], FILTER_SANITIZE_STRING),
                    'tmp_name' => filter_var($_FILES['profilephoto']['tmp_name'], FILTER_SANITIZE_STRING),
                    'error'    => filter_var($_FILES['profilephoto']['error'], FILTER_SANITIZE_STRING),
                    'size'     => filter_var($_FILES['profilephoto']['size'], FILTER_SANITIZE_STRING)
                    );
        $image = JSLEARNMANAGERincluder::getObjectClass('uploads')->learnManagerUpload($profileid, 0, $file,3);
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/profile/profile_' .$profileid ;
        if(is_array($image) && !empty($image)){
            $file_size = $image[3];
            $temp_file_name = $image[0];
            $image[1] = str_replace($temp_file_name, '', $image[2]);

            JSLEARNMANAGERincluder::getJSModel('course')->createThumbnail($file_name,160,160,$image[4],$path,1);
            $img_loc = $image[2];
            $db = new jslearnmanagerdb();
            $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($profileid);
            if($usertype == 'Instructor'){
                $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($profileid);
                $query = "UPDATE `#__js_learnmanager_instructor` SET image = '".$img_loc."' WHERE id = ".$instructorid;
            }elseif($usertype == 'Student'){
                $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($profileid);
                $query = "UPDATE `#__js_learnmanager_student` SET image = '".$img_loc."' WHERE id = ".$studentid;
            }
            $db->setQuery($query);
            $db->query();
        }
        return ;
    }

    function uploadFileCustom($id,$field){
        JSLEARNMANAGERincluder::getObjectClass('uploads')->storeCustomUploadFile($id,0,$field,3);
    }

    function storeUploadFieldValueInParams($profileid,$filename,$field){
        if(!is_numeric($profileid))
            return;
        $db = new jslearnmanagerdb();
        $query = "SELECT params FROM `#__js_learnmanager_user` WHERE id = ".$profileid;
        $db->setQuery($query);
        $params = $db->loadResult();
        if(!empty($params)){
            $decoded_params = json_decode($params,true);
        }else{
            $decoded_params = array();
        }
        $decoded_params[$field] = $filename;
        $encoded_params = json_encode($decoded_params);
        $query = "UPDATE `#__js_learnmanager_user` SET params = '" . $encoded_params . "' WHERE id = " . $profileid;
        $db->setQuery($query);
        $db->query();
        return;
    }

    function getParamsForProfile($data,$id = ''){
        //custom field code start
        $customflagforadd = false;
        $customflagfordelete = false;
        $custom_field_namesforadd = array();
        $custom_field_namesfordelete = array();
        $userfield = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getUserfieldsfor(3);
        $params = array();
        foreach ($userfield AS $ufobj) {
            $vardata = '';
            if($ufobj->userfieldtype == 'file'){
                if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1']== 0){
                    $vardata = $data[$ufobj->field.'_2'];
                }
                $this->_param_array['customflagforadd']=true;
                $this->_param_array['custom_field_namesforadd'][]=$ufobj->field;
            }else{
                $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            }
            if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 1){
                $this->_param_array['customflagfordelete'] = true;
                $this->_param_array['custom_field_namesfordelete'][]= $data[$ufobj->field.'_2'];
                }
            if($vardata != ''){

                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = htmlspecialchars($vardata);
            }
        }

        if($id != ''){
            if(is_numeric($id)){
                $db = new jslearnmanagerdb();
                $query = "SELECT params FROM `#__js_learnmanager_user` WHERE id = " . $id;
                $db->setQuery($query);
                $oParams = $db->loadResult();

                if(!empty($oParams)){
                    $oParams = json_decode($oParams,true);
                    $unpublihsedFields = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getUserUnpublishFieldsfor(3);
                    foreach($unpublihsedFields AS $field){
                        if(isset($oParams[$field->field])){
                            $params[$field->field] = $oParams[$field->field];
                        }
                    }
                }
            }
        }

        $params = ($params);

        return $params;
    }

    function removeFileCustom($id,$key){
        $filename = str_replace(' ', '_', $key);
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/profile/profile_' .$id.'/' ;
        $userpath = $path.$filename;
        unlink($userpath);
        return ;
    }

    function getUsernameAndEmailFromProfile($uid){
        if(!is_numeric($uid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT username, email, id
            FROM `#__js_learnmanager_user`
            WHERE id = ".$uid;
        $db->setQuery($query);
        $user = $db->loadObject();
        return $user;
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSLEARNMANAGERincluder::getJSTable('users');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'status' => $status))) {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            }
        }else{
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'status' => $status))) {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            }
        }

        if ($total == 0) {
            JSLEARNMANAGERmessages::$counter = false;
            if ($status == 1)
                return JSLEARNMANAGER_ENABLED;
            else
                return JSLEARNMANAGER_DISABLED;
        }else {
            JSLEARNMANAGERmessages::$counter = $total;
            if ($status == 1)
                return JSLEARNMANAGER_ENABLE_ERROR;
            else
                return JSLEARNMANAGER_DISABLE_ERROR;
        }
    }

    function deleteUserData($ids,$type=0){
        if (empty($ids))
            return false;

        $notdeleted = 0;
        $status = 0;
        foreach ($ids as $id) {
            if( is_numeric($id) ){
                if($type == 0){
                    $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($id);
                    if($usertype == "Student"){
                        $type = 1;
                    }elseif($usertype == "Instructor"){
                        $type = 2;
                    }
                }
                if($type == 1){
                    $status = JSLEARNMANAGERincluder::getJSModel('student')->deleteStudentRecords($id);
                    if(!$status){
                        $notdeleted += 1;
                    }
                }elseif($type == 2){
                    $status = JSLEARNMANAGERincluder::getJSModel('instructor')->deleteInstructorRecords($id);
                    if(!$status){
                        $notdeleted += 1;
                    }
                }
            }else{
                $notdeleted += 1;
            }
        }
        if ($notdeleted == 0) {
            JSLEARNMANAGERmessages::$counter = false;
            return JSLEARNMANAGER_DELETED;
        } else {
            JSLEARNMANAGERmessages::$counter = $notdeleted;
            return JSLEARNMANAGER_DELETE_ERROR;
        }
    }

    function enforceDeleteUserData($ids,$type=0){
        if (empty($ids))
            return false;
        $notdeleted = 0;
        $also_delete_user = true;

        require_once(ABSPATH . 'wp-admin/includes/user.php' );
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data';
        foreach ($ids as $id) {
            if( is_numeric($id) ){
                if($type == 0){
                    $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($id);
                    if($usertype == "Student"){
                        $type = 1;
                    }elseif($usertype == "Instructor"){
                        $type = 2;
                    }
                }
                $wp_uid = $this->getWPuidByOuruid($id);
                if($type == 1){
                    $status = JSLEARNMANAGERincluder::getJSModel('student')->deleteStudentRecords($id,$also_delete_user);
                    if($status){
                        if ( ! wp_delete_user($wp_uid)){
                            $notdeleted += 1;
                        }
                    }else{
                        $notdeleted += 1;
                    }
                }elseif($type == 2){
                    $status = JSLEARNMANAGERincluder::getJSModel('instructor')->deleteInstructorRecords($id,$also_delete_user);
                    if($status){
                        if ( ! wp_delete_user($wp_uid)){
                            $notdeleted += 1;
                        }else{
                            $filepath = $path . '/profile/profile_' . $id ;
                            $files = glob( $filepath . '/*');
                            foreach($files as $file){
                                if(is_file($file)) {
                                    unlink($file);
                                }elseif(is_dir($file)){
                                    unlink($file.'/*');
                                }

                            }
                            if(is_dir($filepath)) rmdir($filepath);
                        }
                    }else{
                        $notdeleted += 1;
                    }
                }
            }else{
                $notdeleted += 1;
            }
        }
        if ($notdeleted == 0) {
            JSLEARNMANAGERmessages::$counter = false;
            return JSLEARNMANAGER_DELETED;
        } else {
            JSLEARNMANAGERmessages::$counter = $notdeleted;
            return JSLEARNMANAGER_DELETE_ERROR;
        }
    }

    function deleteUserRecords($id,$type=0){
        if (empty($ids))
            return false;

        $notdeleted = 0;
        $status = 0;

        if( is_numeric($id) ){
            if($type == 0){
                $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($id);
                if($usertype == "Student"){
                    $type = 1;
                }elseif($usertype == "Instructor"){
                    $type = 2;
                }
            }
            if($type == 1){
                $status = JSLEARNMANAGERincluder::getJSModel('student')->deleteStudentRecords($id);
                if(!$status){
                    $notdeleted += 1;
                }
            }elseif($type == 2){
                $status = JSLEARNMANAGERincluder::getJSModel('instructor')->deleteInstructorRecords($id);
                if(!$status){
                    $notdeleted += 1;
                }
            }
        }else{
            $notdeleted += 1;
        }

        if ($notdeleted == 0) {
            JSLEARNMANAGERmessages::$counter = false;
            return JSLEARNMANAGER_DELETED;
        } else {
            JSLEARNMANAGERmessages::$counter = $notdeleted;
            return JSLEARNMANAGER_DELETE_ERROR;
        }
    }

    function getUserIDByWPUid($wpuid) {
        if (!is_numeric($wpuid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT id FROM `#__js_learnmanager_user` WHERE uid = " . $wpuid;
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function getUserIDByInstructorid($id) {
        if (!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT user_id FROM `#__js_learnmanager_instructor` WHERE id = " . $id;
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function getUserIDByStudentid($id) {
        if (!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT user_id FROM `#__js_learnmanager_student` WHERE id = " . $id;
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function isInstructorDisabled($instructorid){
        if (!is_numeric($instructorid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT count(user.id) FROM `#__js_learnmanager_user` AS user
                    INNER JOIN `#__js_learnmanager_instructor` AS instructor ON user.id = instructor.user_id AND user.status = 1
                    WHERE instructor.id = " . $instructorid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result == 0){
            return true;
        }else{
            return false;
        }
    }

    function isStudentDisabled($studentid){
        if (!is_numeric($studentid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT count(user.id) FROM `#__js_learnmanager_user` AS user
                    INNER JOIN `#__js_learnmanager_student` AS student ON user.id = student.user_id AND user.status = 1
                    WHERE student.id = " . $studentid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result == 0){
            return true;
        }else{
            return false;
        }
    }

    function isUserDisabled($userid){
        if (!is_numeric($userid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT count(user.id) FROM `#__js_learnmanager_user` AS user
                    WHERE user.id = " . $userid . " AND user.status = 1";
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result == 0){
            return true;
        }else{
            return false;
        }
    }

    function getWPuidByOuruid($our_uid) {
        if (!is_numeric($our_uid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT uid FROM `#__js_learnmanager_user` WHERE id = " . $our_uid;
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function getInstructorListAjax() {
        $userlimit = JSLEARNMANAGERrequest::getVar('userlimit', null, 0);
        $maxrecorded = 3;
        //Filters
        $name = JSLEARNMANAGERrequest::getVar('name');
        $email = JSLEARNMANAGERrequest::getVar('email');

        $db = new jslearnmanagerdb();

        jslearnmanager::$_data['filter']['name'] = $name;
        jslearnmanager::$_data['filter']['email'] = $email;

        $inquery = "";
        if ($name != null) {
            $inquery .= " AND i.name LIKE '%$name%' ";
        }
        if ($email != null)
            $inquery .= " AND user.email LIKE '%$email%' ";

        $query = "SELECT COUNT(user.id) FROM `#__js_learnmanager_user` AS user
                        INNER JOIN `#__js_learnmanager_instructor` AS i ON user.id = i.user_id
                        WHERE user.status = 1 ";
        $query .= $inquery;

        $db->setQuery($query);
        $total = $db->loadResult();
        $limit = $userlimit * $maxrecorded;
        if ($limit >= $total) {
            $limit = 0;
        }

        //Data
        $query = "SELECT i.name, user.email, user.id
            FROM `#__js_learnmanager_user` AS user
            INNER JOIN `#__js_learnmanager_instructor` AS i ON user.id = i.user_id
            WHERE user.status = 1 ";
        $query .= $inquery;
        $query .= " ORDER BY user.id ASC LIMIT $limit, $maxrecorded";
        $db->setQuery($query);
        $users = $db->loadObjectList();

        $html = $this->makeUserList($users, $total, $maxrecorded, $userlimit);
        return $html;
    }

    function getAutoGeneratedUserData($profileid){
        if(!is_numeric($profileid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT name,email,created_at,uid,id  FROM `#__js_learnmananger_user`
                WHERE id = ".$profileid ." AND autogenerated = 1 ";
        $db->setQuery($query);
        $result = $db->loadObject();
        if($result == ''){
            return false;
        }else{
            return $result;
        }
    }

    function getUserDataFromUserName($username){
        $db = new jslearnmanagerdb();
        $query = "SELECT user.id,user.email AS email,CONCAT(user.firstname,' ',user.lastname) AS name,user.user_role_id, role.role
                    FROM `#__js_learnmanager_user` AS user
                       JOIN `#__users` AS u ON u.ID = user.uid
                       JOIN `#__js_learnmanager_user_role` AS role ON role.id = user.user_role_id
                        WHERE u.user_login = '" . $username ."'";
        $db->setQuery($query);
        return $db->loadObject();
    }

    function athunticateUserLoginForApp(){

        $upassword = filter_var($_GET['jslearnmanagersapp-password'], FILTER_SANITIZE_STRING);
        $uusername = filter_var($_GET['jslearnmanagersapp-username'], FILTER_SANITIZE_STRING);
        $res = wp_authenticate($uusername,$upassword);
        if(get_class($res) == 'WP_User'){
           $code = '1';
        }else{
           $code = '0';
        }
        $data = array();

        if($code == '1'){
            $data = $this->getUserDataFromUserName($uusername);
        }

        $return_data['data'] = $data;
        $return_data['userverfified'] = $code;
        return $return_data;
        exit;
    }

    function validateSocialLogin(){
        $return_data = apply_filters("jslm_sociallogin_validate_user_social_login",'');
    }

    function lostPasswordForApp(){
        $usernameOrEmail = filter_var($_GET['username'], FILTER_SANITIZE_STRING);
        $user = get_user_by('login',$usernameOrEmail);
        if(!$user)
            $user = get_user_by('email',$usernameOrEmail);
        if($user !== false){
            $code   = 1;
            $key    = get_password_reset_key($user);
            $wplink = network_site_url("wp-login.php?action=rp&key=".$key."&login=".rawurlencode($user->data->user_login));
            $email  = $user->data->user_email;
        }else{
            $code = 0;
            $msg  = 'Invalid username or email';
        }
        $config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('email');
        $senderEmail = $config_array['mailfromaddress'];
        $senderName = $config_array['mailfromname'];

        JSLEARNMANAGERincluder::getJSModel('emailtemplate')->sendEmail($email, 'Learn Manager Password Reset', $wplink, $senderEmail, $senderName, $attachments = '');
        echo stripcslashes(json_encode(compact('code','msg','email','key','wplink')));
        exit;
    }

    function updatePasswordForApp(){
        $userlogin = filter_var($_GET['jslearnmanagerapp-userlogin'], FILTER_SANITIZE_STRING);
        $oldpassword = filter_var($_GET['jslearnmanagerapp-oldpassword'], FILTER_SANITIZE_STRING);
        $password =  filter_var($_GET['jslearnmanagerapp-password'], FILTER_SANITIZE_STRING);

        $user = get_user_by( 'login', $userlogin );
        if ( $user && wp_check_password( $oldpassword, $user->data->user_pass, $user->ID ) ) {
            $res = wp_update_user(array(
                'ID'    => $user->data->ID,
                'user_pass' => $password,
            ));
            if(is_wp_error($res)){
                $code = 0;
                $msg  = $res->get_error_message($res->get_error_code());
            }else{
                $code = 1;
            }
        } else {
            $code = 0;
            $msg = "Old password did not match";
        }
        echo json_encode(compact('code','msg'));
        exit;
    }

    function deleteuserimageAjax(){
        $id = JSLEARNMANAGERrequest::getVar('userid');
        if(!is_numeric($id))
            return false;
        $isdelete = $this->deleteProfileImage($id,1);
        return $isdelete;
    }

    function getMessagekey(){
        $key = 'users';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

   function getAdminUserSearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $username = ($isadmin) ? 'searchname' : 'users';
        $jslms_search_array['searchname'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($username)));
        $jslms_search_array['status'] = trim(JSLEARNMANAGERrequest::getVar('status' , ''));
        $jslms_search_array['email'] = trim(JSLEARNMANAGERrequest::getVar('email' , ''));
        $jslms_search_array['pagesize'] = absint(JSLEARNMANAGERrequest::getVar('pagesize'));
        $jslms_search_array['mycoursetitle'] = trim(JSLEARNMANAGERrequest::getVar('mycoursetitle'));
        $jslms_search_array['mycoursecategory'] = trim(JSLEARNMANAGERrequest::getVar('mycoursecategory'));
        $jslms_search_array['coursetitle'] = trim(JSLEARNMANAGERrequest::getVar('coursetitle'));
        $jslms_search_array['category'] = trim(JSLEARNMANAGERrequest::getVar('category'));
        $jslms_search_array['search_from_user'] = 1;
        return $jslms_search_array;
    }




}
?>
