<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERinstructorModel {

    function getAllInstructorForAdminForCombo() {
        $db = new jslearnmanagerdb;
        $data = array();
        $query = "SELECT id AS instructor_id, name AS instructor_name FROM `#__js_learnmanager_instructor` ORDER BY name ASC ";
        $db->setQuery($query);
        $instructors = $db->loadObjectList();
        foreach ($instructors as $instr ) {
            $data[] = (object) array('id' => $instr->instructor_id, 'text' => __($instr->instructor_name, 'learn-manager'));
        }
        return $data;
    }

    function getInstructorNameAndImagebyuid($uid){
        if(!is_numeric($uid) || $uid == ''){
            return false;
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT s.name, s.image FROM `#__js_learnmanager_instructor` AS s WHERE s.user_id = ".$uid;
        $db->setQuery($query);
        jslearnmanager::$_data['userprofile'] = $db->loadObject();
        return;
    }

    function getInstructorNameBYId($id){
        if(!is_numeric($id) || $id == ''){
            return false;
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT name as text FROM `#__js_learnmanager_instructor`  WHERE id = ".$id;
        $db->setQuery($query);
        $name = $db->loadResult();
        return $name;
    }

    function storeInstructorProfile($data){
        if(empty($data))
            return false;
        if(isset($data['lmsid']) && $data['lmsid'] != ""){
            $data['id'] = $data['lmsid'];
        }else{
            $data['id'] = "";
        }
        if($data['id'] == ""){
            $data['approvalstatus'] = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('instructor_autoapprove');
            if($data["approvalstatus"] == 0){
                $msg = JSLEARNMANAGERmessages::getMessage(JSLEARNMANAGER_PENDING, 'user');
                JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->getMessagekey());
            }
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $data['bio']= sanitize_text_field(wptexturize(stripslashes($_POST['bio'])));
        $row = JSLEARNMANAGERincluder::getJSTable('instructor');
        if(isset($data['bio'])){
            $data['bio']= sanitize_textarea_field(wptexturize(stripslashes($data['bio'])));
        }
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        return $row->id;
    }

    function getMyStats($instructor_id){
        if(!is_numeric($instructor_id)) return false;

        $db = new jslearnmanagerdb();

        $query = "SELECT COUNT(DISTINCT sc.student_id) as totalstudents
                    FROM `#__js_learnmanager_instructor` AS r
                        JOIN `#__js_learnmanager_course` AS c ON c.instructor_id = r.id
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS sc ON sc.course_id = c.id
                        WHERE r.id = " .$instructor_id;

        $db->setQuery($query);
        $totalstudents = $db->loadResult();
        jslearnmanager::$_data[0]['totalstudents'] = $totalstudents;

        jslearnmanager::$_data[0]['avgreview'] = apply_filters('jslm_coursereview_get_instructor_average_rating',0,$instructor_id);

        $query = "SELECT COUNT(c.id) as totalcourses
                    FROM  `#__js_learnmanager_course` AS c
                        WHERE c.instructor_id = " .$instructor_id. " AND c.course_status = 1";
        $db->setQuery($query);
        $totalcourses = $db->loadResult();
        jslearnmanager::$_data[0]['totalcourses'] = $totalcourses;

        $query = "SELECT COUNT(c.id) as totalcourses
                    FROM  `#__js_learnmanager_course` AS c
                        WHERE c.instructor_id = " .$instructor_id. " AND c.course_status = 0";
        $db->setQuery($query);
        $totalcourses = $db->loadResult();
        jslearnmanager::$_data[0]['unpublishcourses'] = $totalcourses;

        $query = "SELECT c.id AS courseid, c.title AS coursetitle , COUNT(DISTINCT l.id) as lessons
                    FROM  `#__js_learnmanager_course` AS c
                        LEFT JOIN `#__js_learnmanager_course_section` AS sc ON c.id = sc.course_id
                        LEFT JOIN `#__js_learnmanager_course_section_lecture` AS l ON sc.id = l.section_id
                        WHERE c.instructor_id = " .$instructor_id;
        $query .= " GROUP BY c.id";
        $db->setQuery($query);
        $recent = $db->loadObjectList();
        jslearnmanager::$_data[0]['recent'] = $recent;

        return;
    }

    function getInstructorProfilebyInstructorid($instructor_id){
        if(!is_numeric($instructor_id))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT i.id as instructor_id,i.name, i.bio, i.image, i.gender, u.country_id, u.email, u.facebook_url, u.twitter, u.linkedin,u.status,u.user_role_id, u.params, u.id as user_id
                    FROM `#__js_learnmanager_user` AS u
                        INNER JOIN `#__js_learnmanager_instructor` AS i ON u.id = i.user_id WHERE i.id =" .$instructor_id;
        $db->setQuery($query);
        $profile = $db->loadObject();
        jslearnmanager::$_data['profile'] = $profile;
        jslearnmanager::$_data['profile']->location = JSLEARNMANAGERincluder::getJSModel('country')->getCountryNameById(jslearnmanager::$_data['profile']->country_id);
        jslearnmanager::$_data['profilecustomfields'] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // For user profile
        return;
    }

    function deleteInstructorById($id){
        if(!is_numeric($id)) return false;

        $row = JSLEARNMANAGERincluder::getJSTable('instructor');

        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data';

        $notdeleted = 0;
        foreach ($ids as $id) {
            if(is_numeric($id)){
                if ($this->instructorCanDelete($id) == true) {
                    if (!$row->delete($id)) {
                        $notdeleted += 1;
                    }else{
                        $filepath = $path . '/instructor/instructor_' . $id;
                        $files = glob( $filepath . '/*');
                        foreach($files as $file){
                            if(is_file($file)) unlink($file);
                        }
                        if(is_dir($filepath)) rmdir($filepath);
                    }
                } else {
                    $notdeleted += 1;
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

    function instructorCanDelete($id){
        if(!is_numeric($id)) return false;

        //db Object
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(c.instructor_id) as total
                    FROM '#__js_learnmanager_course' AS c
                        WHERE c.instructor_id =" .$id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if($total > 0)
            return false;
        else
            return true;
    }

    function getInstructorId($userid){ // by lms user id
        if(!is_numeric($userid) || $userid == ''){
            return false;
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT id FROM `#__js_learnmanager_instructor`  WHERE user_id = ".$userid;
        $db->setQuery($query);
        $instructorid = $db->loadResult();
        return $instructorid;
    }

    function getInstructorProfileformessage($userid){
        if(!is_numeric($userid) || $userid == ''){
            return false;
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT i.id, i.name, i.image  FROM `#__js_learnmanager_instructor` AS i
                    WHERE i.user_id = ".$userid;
        $db->setQuery($query);
        $instructor = $db->loadObject();
        return $instructor;
    }

    function getInstructorImage($uid){
        if(!is_numeric($uid))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT image
                    FROM `#__js_learnmanager_instructor`
                        WHERE user_id=" .$uid;
        $db->setQuery($query);
        $image = $db->loadResult();
        jslearnmanager::$_data['profileimage'] = $image;
        return;
    }

    function getAllInstructors($datafor,$for=0){

        //DB Object
        $db = new jslearnmanagerdb();
        //Filter
        $isadmin = is_admin();
        $instructorname = ($isadmin) ? 'instructorname' : 'jslm_instructor';
        $instructorname = jslearnmanager::$_data['filter']['instructorname'];
        $instructoremail = jslearnmanager::$_data['filter']['instructoremail'];
        $inquery = '';
        $oper = " WHERE";
        if (is_string($instructorname)){
            $inquery .= $oper;
            $inquery .= " i.name LIKE '%" . $instructorname . "%'";
            $oper = " AND";
        }

        if (is_string($instructoremail)){
            $inquery .= $oper;
            $inquery .= " u.email LIKE '%" . $instructoremail . "%'";
            $oper = " AND";
        }

        if($datafor == 1){
            $inquery .= $oper;
            $inquery .= " i.approvalstatus = 1";
            $oper = " AND";
        }
        else if ($datafor == 3) {
            /*For front end View*/
            $inquery .= $oper;
            $inquery .= " i.approvalstatus = 1 AND u.status = 1" ;
            $oper = " AND";
        }
        else{ // For Queue
            $inquery .= $oper;
            if($for == 0){
                $inquery .= " i.approvalstatus = 0";
            }elseif($for == -1){
                $inquery .= " i.approvalstatus = -1";
            }
            $oper = " AND";
        }

        jslearnmanager::$_data['filter']['instructorname'] = $instructorname;
        jslearnmanager::$_data['filter']['instructoremail'] = $instructoremail;

        //pagination
        $query = "SELECT COUNT(i.id)
                    FROM `#__js_learnmanager_instructor` AS i
                        LEFT JOIN `#__js_learnmanager_user` AS u ON u.id = i.user_id";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        //data
        $query = "SELECT i.name, i.image, i.gender, i.id, u.email, u.country_id, u.status,u.user_role_id,u.facebook_url,u.twitter,u.linkedin, u.id as uid, i.approvalstatus
                    FROM `#__js_learnmanager_instructor` AS i
                        INNER JOIN `#__js_learnmanager_user` AS u ON u.id = i.user_id";
        $query .= $inquery;
        $query .= " GROUP BY i.id";
        $query .= " ORDER BY i.created_at DESC";
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset ." , ". JSLEARNMANAGERpagination::$_limit;

        $db->setQuery($query);
        $instructors = $db->loadObjectList();
        foreach($instructors as $instructor => $key){
            $instructors[$instructor]->location = JSLEARNMANAGERincluder::getJSModel('country')->getCountryNameById($key->country_id);
        }
        jslearnmanager::$_data[0] = $instructors;
        return;
    }

    function getInstructorCoursesByInstructorId($instructor_id){ // For My Profile
        if(!is_numeric($instructor_id)) return false;

        $db = new jslearnmanagerdb;

        $query = "SELECT COUNT(c.id) FROM `#__js_learnmanager_course` as c
                    JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                    WHERE c.course_status = 1 AND c.isapprove = 1 AND c.instructor_id = " .$instructor_id;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['totalcourses'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        do_action("jslm_featuredcourse_select_query_data");
        do_action("jslm_paidcourse_join_select_query_data",1);
        $query = "SELECT  c.id as course_id, c.title as title, accesstype.access_type as access_type,  c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c.course_level as level,
                cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, c.params, intr.image as instructor_image, c.file, c.start_date as start_date,c.expire_date as expire_date,
                c.course_status as course_status,c.created_at as created_at, ". jslearnmanager::$_addon_query['select'] ." c.params,
                COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students
                        FROM `#__js_learnmanager_course` as c
                            LEFT JOIN `#__js_learnmanager_category` as cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` as intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` as sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` as sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` as stdnt_c ON c.id = stdnt_c.course_id
                            -- LEFT JOIN `#__js_learnmanager_student` as stdnt ON stdnt.id = stdnt_c.student_id
                            -- LEFT JOIN `#__js_learnmanager_course_reviews` c_r ON c.id = c_r.course_id
                            " . jslearnmanager::$_addon_query['join'] . "
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            WHERE c.instructor_id = " .$instructor_id. " AND c.course_status = 1 AND c.isapprove = 1";
        $query .= " GROUP BY c.id";
        $query .= " ORDER BY c.created_at DESC";
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset ." , ". JSLEARNMANAGERpagination::$_limit;
        do_action('reset_jslmaddon_query');
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        jslearnmanager::$_data['mycourses'] = $courses;
        foreach($courses as $course => $key){
            $courses[$course]->reviews =  apply_filters("jslm_coursereview_get_coursereviews_by_courseid",0,$key->course_id,1);
        }
        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1); // For user Courses
        return ;
    }

    function getTotalCoursesByInstructor(){  // For My Profile
        $db = new jslearnmanagerdb;
        $query="SELECT i.name, i.id,i.image, COUNT(c.id) as totalcourses FROM `wp_js_learnmanager_instructor` AS i
        INNER JOIN `wp_js_learnmanager_course` AS c ON i.id = c.instructor_id
        INNER JOIN `wp_js_learnmanager_user` AS u ON u.id = i.user_id
        WHERE c.course_status = 1 AND c.isapprove = 1 AND i.approvalstatus = 1 AND u.status = 1
        GROUP BY i.id
        ORDER BY totalcourses DESC
        LIMIT 5";

        $db->setQuery($query);
        $totalcourses = $db->loadObjectList();
        jslearnmanager::$_data['totalcourses'] = $totalcourses;
        return ;
    }








    function getDownloadFileByName($file_name,$id){
        if(empty($file_name)) return false;
        if(!is_numeric($id)) return false;
        $layout = JSLEARNMANAGERrequest::getVar('layout');
        $filename = str_replace(' ', '_',$file_name);
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        if($layout == 4){
            $path = $wpdir['basedir'] . '/' . $data_directory . '/data/profile/profile_' .$id.'/';
        }

        $file = $path . $filename;
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        //ob_clean();
        flush();
        readfile($file);
        exit();
    }

    function getInstructorCourseforGraphcombo($instructor_id){
        if(!is_numeric($instructor_id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT id, title as text FROM `#__js_learnmanager_course` AS c  WHERE c.instructor_id = " .$instructor_id;
        $db->setQuery($query);
        $allcourses = $db->loadObjectList();
        return $allcourses;
    }

    function getInstructorCoursesForAjaxCombo(){

        $coursename = JSLEARNMANAGERrequest::getVar('course');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $instructor_id = $this->getInstructorId($uid);
        if(!is_string($coursename))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT c.id, c.title FROM `#__js_learnmanager_course` AS c WHERE c.instructor_id=" .$instructor_id . " AND c.title LIKE '" . $coursename . "%'";
        $db->setQuery($query);
        $allcourses = $db->loadObjectList();
        $html = '';
        if(count($allcourses) > 0){
            $html = '<ul id="course-list" name="course" class="list-group">';
            foreach($allcourses as $course) {
                $html .='<a href="#" onClick="selectCourse('."'$course->title'".','."'$course->id'".')"><li class="list-group-item" id='.$course->id.'>'.$course->title.'</li></a>';
            }
            $html .= '</ul>';
        }
        if($html == '' && empty($html)){
            $html = '<ul id="course-list" class="list-group">';
            $html .= '<li class="list-group-item">No Course Found!</li>';
            $html .= '</ul>';
        }
        return $html;
    }

    function getInstructorListForAjaxCombo(){

        $instructor_name = JSLEARNMANAGERrequest::getVar('instructorname');
        if(!is_string($instructor_name))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT i.id, i.name, u.email FROM `#__js_learnmanager_instructor` AS i
                    INNER JOIN  `#__js_learnmanager_user` AS u ON u.id = i.user_id
                        WHERE u.status=1  AND i.name LIKE '" . $instructor_name . "%'";
        $db->setQuery($query);
        $allinstructors = $db->loadObjectList();
        $html = '';
        if(count($allinstructors) > 0){
            $html = '<ul id="instructors-list" name="instructors" class="list-group">';
            foreach($allinstructors as $instructor) {
                $html .='<a href="#" onClick="selectInstructor('."'$instructor->name'".','."'$instructor->id'".')"><li class="list-group-item" id='.$instructor->id.'>'.$instructor->name.'('.$instructor->email.')</li></a>';
            }
            $html .= '</ul>';
        }
        if($html == '' && empty($html)){
            $html = '<ul id="course-list" class="list-group">';
            $html .= '<li class="list-group-item">No Instructor Found!</li>';
            $html .= '</ul>';
        }
        return $html;
    }

    function approveReject($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSLEARNMANAGERincluder::getJSTable('instructor');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'approvalstatus' => $status))) {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            }
        }else{
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'approvalstatus' => $status))) {
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
                return JSLEARNMANAGER_APPROVED;
            else
                return JSLEARNMANAGER_REJECTED;
        }else {
            JSLEARNMANAGERmessages::$counter = $total;
            if ($status == 1)
                return JSLEARNMANAGER_APPROVE_ERROR;
            else
                return JSLEARNMANAGER_REJECT_ERROR;
        }
    }

    function deleteInstructorRecords($uid , $is_delete_user = false){
        if(! is_numeric($uid))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT c.id FROM `#__js_learnmanager_course` AS c
                    LEFT JOIN `#__js_learnmanager_instructor` AS i ON i.id = c.instructor_id
                    WHERE i.user_id =".$uid;
        $db->setQuery($query);
        $data = $db->loadObjectList();

        $str = '';
        $i = '';
        if($is_delete_user){
            $str = ' , user ';
            $i = ' ,i ';
            if(in_array("message", jslearnmanager::$_addon_query)){
                $i .= " , message";
            }
        }
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data';
        do_action("jslm_addon_delete_query_data_for_course","c","");
        do_action("jslm_payouts_delete_query_data_for_course","i");
        do_action("jslm_quiz_select_query_data_for_delete_course_quiz","lecture","quiz");
        $query = "DELETE c, ".jslearnmanager::$_addon_query['select']." section, lecture,shortlist,shc,lf  ".$str.$i."
            FROM `#__js_learnmanager_user` AS user
            LEFT JOIN `#__js_learnmanager_instructor` AS i ON i.user_id = user.id
            LEFT JOIN `#__js_learnmanager_course` AS c ON c.instructor_id = i.id
            ".jslearnmanager::$_addon_query['join']."
            LEFT JOIN `#__js_learnmanager_course_section` AS section ON section.course_id = c.id
            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS lecture ON lecture.section_id = section.id
            LEFT JOIN `#__js_learnmanager_lecture_file` AS lf ON lecture.id = lf.lecture_id
            LEFT JOIN `#__js_learnmanager_wishlist` AS shortlist ON shortlist.course_id = c.id
            LEFT JOIN `#__js_learnmanager_student_enrollment` AS shc ON shc.course_id = c.id";
        if(in_array('message', jslearnmanager::$_active_addons)){
            $query .= " LEFT JOIN `#__js_learnmanager_messages` AS message ON message.instructor_uid = user.id";
        }

        $query .= " WHERE user.id = " . $uid;

        $db->setQuery($query);
        do_action("reset_jslmaddon_query");
        if($db->query()){
            foreach ($data as $key) {
                $filepath = $path . '/course/course_'. $key->id;
                $this->removeContents($filepath);
            }
            return true;
        }else{
            return false;
        }
    }

    function removeContents($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir"){
                        $this->removeContents($dir."/".$object);
                    }else{
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    function setListStyleSession(){
        $listingstyle = JSLEARNMANAGERrequest::getVar('styleid');
        $_SESSION['jslm_instructordashoard_pages'] = $listingstyle;
        return ($listingstyle);
    }


    function getinstructorlistajaxforcourse() {
        $db = new jslearnmanagerdb();
        $userlimit = JSLEARNMANAGERrequest::getVar('userlimit', null, 0);
        $maxrecorded = 3;
        //Filters
        $uname = JSLEARNMANAGERrequest::getVar('uname');
        $name = JSLEARNMANAGERrequest::getVar('name');
        $email = JSLEARNMANAGERrequest::getVar('email');

        jslearnmanager::$_data['filter']['name'] = $name;
        jslearnmanager::$_data['filter']['uname'] = $uname;
        jslearnmanager::$_data['filter']['email'] = $email;

        $inquery = "";

        if ($name != null) {
            $inquery .= " AND ( user.firstname LIKE '%" . $name . "%' OR user.lastname LIKE '%" . $name . "%' ) ";
        }
        if ($uname != null) {
            $inquery .= " AND  user.username LIKE  '%" . $uname . "%' ";
        }
        if ($email != null)
            $inquery .= " AND user.email LIKE '%" . $email . "%' ";

        $query = "SELECT COUNT(user.id)
                FROM `#__js_learnmanager_user` AS user
                INNER JOIN `#__js_learnmanager_instructor` AS i ON user.id = i.user_id
                WHERE user.status = 1";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        $limit = $userlimit * $maxrecorded;
        if ($limit >= $total) {
            $limit = 0;
        }

        //Data
        $query = "SELECT i.id AS instructor_id,user.firstname,user.lastname,user.email
                    ,user.username as user_login
                FROM `#__js_learnmanager_user` AS user
                INNER JOIN `#__js_learnmanager_instructor` AS i ON user.id = i.user_id
                WHERE user.status = 1";
        $query .= $inquery;
        $query .= " ORDER BY user.id LIMIT $limit, $maxrecorded";
        $db->setQuery($query);
        $users = $db->loadObjectList();
        $html = $this->makeUserList($users, $total, $maxrecorded, $userlimit);
        return $html;
    }


    function makeUserList($users, $total, $maxrecorded, $userlimit , $assignrole = false) {
        $html = '';
        if (!empty($users)) {
            if (is_array($users)) {

                $html .= '
                    <div id="records">';

                $html .='
                <div id="user-list-header">
                    <div class="js-col-md-1 user-id">' . __('ID', 'learn-manager') . '</div>
                    <div class="js-col-md-3 user-name">' . __('Name', 'learn-manager') . '</div>
                    <div class="js-col-md-3 user-name-n">' . __('User Name', 'learn-manager') . '</div>
                    <div class="js-col-md-5 user-email">' . __('Email Address', 'learn-manager') . '</div>
                </div>';

                foreach ($users AS $user) {
                    if($assignrole){
                        $username = $user->name;
                    }else{
                        $username = $user->firstname . ' ' . $user->lastname;
                    }
                    $html .='
                        <div class="user-records-wrapper" >
                            <div class="js-col-xs-12 js-col-md-1 user-id">
                                ' . $user->instructor_id . '
                            </div>
                            <div class="js-col-xs-12 js-col-md-3 user-name">
                                <a href="#" class="js-userpopup-link" data-id=' . $user->instructor_id . ' data-name="' . $username . '" data-email="' . $user->email . '" >' . $username . '</a>
                            </div>
                            <div class="js-col-xs-12 js-col-md-3 user-name-n">
                                ' . $user->user_login . '
                            </div>
                            <div class="js-col-xs-12 js-col-md-5 user-email">
                                ' . $user->email . '
                            </div>
                        </div>';
                }
            }
            $num_of_pages = ceil($total / $maxrecorded);
            $num_of_pages = ($num_of_pages > 0) ? ceil($num_of_pages) : floor($num_of_pages);
            if ($num_of_pages > 0) {
                $page_html = '';
                $prev = $userlimit;
                if ($prev > 0) {
                    $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist(' . ($prev - 1) . ');">' . __('Previous', 'learn-manager') . '</a>';
                }
                for ($i = 0; $i < $num_of_pages; $i++) {
                    if ($i == $userlimit)
                        $page_html .= '<span class="jsst_userlink selected" >' . ($i + 1) . '</span>';
                    else
                        $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist(' . $i . ');">' . ($i + 1) . '</a>';
                }
                $next = $userlimit + 1;
                if ($next < $num_of_pages) {
                    $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist(' . $next . ');">' . __('Next', 'learn-manager') . '</a>';
                }
                if ($page_html != '') {
                    $html .= '<div class="jsst_userpages">' . $page_html . '</div>';
                }
            }
        } else {
            $html = JSLEARNMANAGERlayout::getAdminPopupNoRecordFound();
        }
        $html .= '</div>';
        return $html;
    }

    function getInstructorApprovalStatus($id,$for=0){
        if ($for != 0) { // if $for != 0 than get instructor id
            $id = $this->getInstructorId($id);
        }
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb;
        $query = "SELECT approvalstatus FROM `#__js_learnmanager_instructor` WHERE id = ".$id;
        $db->setQuery($query);
        return $db->loadResult();
    }

    function getMessagekey(){
        $key = 'instructor';
        if(is_admin()){
            $key = 'admin_'.$key;
        }
        return $key;
    }

   function getAdminInstructorSearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $instructorname = ($isadmin) ? 'instructorname' : 'jslm_instructor';
        $jslms_search_array['instructorname'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($instructorname)));
        $jslms_search_array['instructoremail'] = trim(JSLEARNMANAGERrequest::getVar('instructoremail' , ''));
        $jslms_search_array['search_from_instructor'] = 1;
        return $jslms_search_array;
    }



}


?>
