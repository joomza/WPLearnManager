<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERstudentModel {

    function getStudentId($id){ // by lms user id
        if(!is_numeric($id) || $id == ''){
            return false;
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT s.id FROM `#__js_learnmanager_student` AS s WHERE s.user_id = ".$id;
        $db->setQuery($query);
        $studentid = $db->loadResult();
        return $studentid;
    }

    function getStudentNameAndImagebyuid($uid){
        if(!is_numeric($uid) || $uid == ''){
            return false;
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT s.name, s.image FROM `#__js_learnmanager_student` AS s WHERE s.user_id = ".$uid;
        $db->setQuery($query);
        jslearnmanager::$_data['userprofile'] = $db->loadObject();
        return;
    }

    function sorting() {
        do_action("jslm_paidcourse_course_price_select_query_data");
        jslearnmanager::$_data['sorton'] = JSLEARNMANAGERrequest::getVar('sorton', 'post', 3);
        jslearnmanager::$_data['sortby'] = JSLEARNMANAGERrequest::getVar('sortby', 'post', 2);
        switch (jslearnmanager::$_data['sorton']) {
            case 5:
                $sort_string = 'c.title';
                break;
            case 4:
                $sort_string = 'c.access_type';
                break;
            case 3: // created
                $sort_string = ' c.created_at ';
                break;
            case 2: // price
                $sort_string = jslearnmanager::$_addon_query['select'];
                break;
            case 1: // category
                $sort_string = ' cat.category_name ';
                break;
        }
        do_action("reset_jslmaddon_query");
        if (jslearnmanager::$_data['sortby'] == 1) {
            $sort_string .= ' ASC ';
        } else {
            $sort_string .= ' DESC ';
        }
        jslearnmanager::$_data['combosort'] = jslearnmanager::$_data['sorton'];

        return $sort_string;
    }

    function getMyStats($studentid){
        if(!is_numeric($studentid)) return false;

        $db = new jslearnmanagerdb();

        $query = "SELECT COUNT(c.id) as totalcourses
                    FROM  `#__js_learnmanager_student_enrollment` AS c
                        INNER JOIN `#__js_learnmanager_course` AS course ON course.id = c.course_id AND course.isapprove = 1 AND course.course_status = 1
                        WHERE c.student_id = " .$studentid ;
        $db->setQuery($query);
        $totalcourses = $db->loadResult();
        jslearnmanager::$_data[0]['totalcourses'] = $totalcourses;

        $query = "SELECT COUNT(c.id) as totalcourses
                    FROM  `#__js_learnmanager_wishlist` AS c
                        INNER JOIN `#__js_learnmanager_course` AS course ON course.id = c.course_id AND course.isapprove = 1 AND course.course_status = 1
                        WHERE c.student_id = " .$studentid;
        $db->setQuery($query);
        $totalcourses = $db->loadResult();
        jslearnmanager::$_data[0]['shortlistcourses'] = $totalcourses;


        return;
    }

    function storeStudentProfile($data){
        if(empty($data))
            return false;
        if(isset($data['lmsid']) && $data['lmsid'] != ""){
            $data['id'] = $data['lmsid'];
        }else{
            $data['id'] = "";
        }
        if($data['id'] == ""){
            $data['approvalstatus'] = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('student_autoapprove');
            if($data["approvalstatus"] == 0){
                $msg = JSLEARNMANAGERmessages::getMessage(JSLEARNMANAGER_PENDING, 'user');
                JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->getMessagekey());
            }
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if(isset($_POST['bio'])){
            $data['bio']= sanitize_textarea_field(wptexturize(stripslashes($_POST['bio'])));
        }
        $row = JSLEARNMANAGERincluder::getJSTable('student');
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        return $row->id;
    }

    function getStudentProfilebyUid($id){
        if(!is_numeric($id))
            return false;

        $db = new jslearnmanagerdb();

        $query = "SELECT s.name, s.bio, s.image, s.gender, u.country_id, u.email, u.facebook_url, u.twitter, u.linkedin, u.params, u.id as user_id, u.status
                    FROM `#__js_learnmanager_user` AS u
                        INNER JOIN `#__js_learnmanager_student` AS s ON u.id = s.user_id and u.id =" .$id;
        $db->setQuery($query);
        $profile = $db->loadObject();
        jslearnmanager::$_data['profile'] = $profile;
        jslearnmanager::$_data['profile']->location = JSLEARNMANAGERincluder::getJSModel('country')->getCountryNameById(jslearnmanager::$_data['profile']->country_id);
        jslearnmanager::$_data['profilecustomfields'] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // For user profile
        return;
    }

    function getStudentImage($uid){
        if(!is_numeric($uid))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT image
                    FROM `#__js_learnmanager_student`
                        WHERE user_id=" .$uid;
        $db->setQuery($query);
        $image = $db->loadResult();
        jslearnmanager::$_data['profileimage'] = $image;
        return;
    }

    function getStudentCoursesForProfile($id){ // By Student Id
        if(!is_numeric($id)) return false;

        $db = new jslearnmanagerdb();

        //pagination
        $query = "SELECT COUNT(id)
                    FROM  `#__js_learnmanager_student_enrollment`
                        WHERE student_id = " .$id;
        $db->setQuery($query);

        //data
        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data');
        do_action("jslm_featuredcourse_select_query_data");
        do_action("jslm_paidcourse_join_select_query_data",1);
        $query = "SELECT c.id as course_id, c.title as title, accesstype.access_type as access_type, c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c.course_level as level,
                        cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, intr.image, c.file, c.params,
                        COUNT(DISTINCT sect_lec.id) as total_lessons,". jslearnmanager::$_addon_query['select'] ." COUNT(DISTINCT stdnt_all.id) as total_students
                    FROM `#__js_learnmanager_course` AS c
                        LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                        LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                        LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                        LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id AND c.isapprove = 1 AND c.course_status = 1
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_all ON c.id = stdnt_all.course_id
                        ". jslearnmanager::$_addon_query['join'] ."
                        JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                        WHERE stdnt_c.student_id = " .$id;
        $query .= " GROUP BY c.id";
        $query .= " ORDER BY c.id";
        $query .= " LIMIT 6";
        $db->setQuery($query);
        do_action('reset_jslmaddon_query');
        $courses = $db->loadObjectList();
        jslearnmanager::$_data['mycourses'] = $courses;
        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1); // For user courses

        return;
    }

    function getStudentProfileformessage($userid){
        if(!is_numeric($userid) || $userid == ''){
            return false;
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT s.id, s.name, s.image FROM `#__js_learnmanager_student` AS s
                    WHERE s.user_id = ".$userid;
        $db->setQuery($query);
        $student = $db->loadObject();
        return $student;
    }

    function getDownloadFileByName($file_name,$id){
        if(empty($file_name)) return false;
        if(!is_numeric($id)) return false;
        $layout = JSLEARNMANAGERrequest::getVar('layout');
        $filename = str_replace(' ', '_',$file_name);
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        if($layout == 3){
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

    function getAllStudentsList($datafor,$for=0){
        $db = new jslearnmanagerdb();
        //Filter
        $isadmin = is_admin();
        $studentname = ($isadmin) ? 'studentname' : 'jslm_student';
        $studentname = jslearnmanager::$_data['filter']['studentname']; 
        $studentemail = jslearnmanager::$_data['filter']['studentemail'];
        $inquery = '';
        $oper = " WHERE";
        if (is_string($studentname)){
            $inquery .= $oper;
            $inquery .= " s.name LIKE '%" . $studentname . "%'";
            $oper = " AND";
        }

        if (is_string($studentemail)){
            $inquery .= $oper;
            $inquery .= " u.email LIKE '%" . $studentemail . "%'";
            $oper = " AND";
        }

        if($datafor == 1){
            $inquery .= $oper;
            $inquery .= " s.approvalstatus = 1";
            $oper = " AND";
        }
        else{ // For Queue
            $inquery .= $oper;
            if($for == 0){ // for pending
                $inquery .= " s.approvalstatus = 0";
            }elseif($for == -1){ // for reject
                $inquery .= " s.approvalstatus = -1";
            }
            $oper = " AND";

        }

        jslearnmanager::$_data['filter']['studentname'] = $studentname;
        jslearnmanager::$_data['filter']['studentemail'] = $studentemail;

        //pagination
        $query = "SELECT COUNT(s.id)
                    FROM `#__js_learnmanager_student` AS s
                        LEFT JOIN `#__js_learnmanager_user` AS u ON u.id = s.user_id";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);
        $query = "SELECT s.name, s.image, s.id, s.gender,COUNT(sc.id) as enrolled, u.status,(SELECT COUNT(w.id) FROM `#__js_learnmanager_wishlist` AS w WHERE s.id = w.student_id) as shortlisted
                    ,u.facebook_url, u.twitter, u.linkedin, c.country_name as location,u.email, s.user_id as uid, s.approvalstatus
                        FROM `#__js_learnmanager_student` AS s
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS sc ON s.id = sc.student_id
                            INNER JOIN `#__js_learnmanager_user` AS u ON u.id = s.user_id
                            LEFT JOIN `#__js_learnmanager_country` AS c ON c.id = u.country_id
                            ";
        $query .= $inquery;
        $query .= " GROUP BY s.id";
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset ." , ". JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        $allstudents = $db->loadObjectList();
        jslearnmanager::$_data[0] = $allstudents;
        return;
    }

    function getMessagekey(){
        $key = 'student';
        if(is_admin())
            {
                $key = 'admin_'.$key;
            }
        return $key;
    }

    function approveReject($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSLEARNMANAGERincluder::getJSTable('student');
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

    function deleteStudentRecords($uid , $is_delete_user = false){
        if(! is_numeric($uid))
            return false;
        $db = new jslearnmanagerdb();
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');

        $str = '';
        $student = '';
        if($is_delete_user){
            if (isset(jslearnmanager::$_addon_query['select']) && jslearnmanager::$_addon_query['select'] != '') {
                $str = ' , user ';
            }else{
                $str = ' user ';
            }
            $student = ' ,student ';
            if(in_array('message', jslearnmanager::$_addon_query)){
                $student .= ", message";
            }
        }
        do_action("jslm_addon_delete_query_data_for_course","student",".student_id");
        do_action("jslm_addon_delete_query_data_for_course_paymentplan","user",".uid");
        $query = "DELETE shc, shortlist, ". jslearnmanager::$_addon_query['select'] .$str.$student."
            FROM `#__js_learnmanager_user` AS user
            LEFT JOIN `#__js_learnmanager_student` AS student ON user.id = student.user_id
            LEFT JOIN `#__js_learnmanager_student_enrollment` AS shc ON student.id = shc.student_id
            ".jslearnmanager::$_addon_query['join']."
            LEFT JOIN `#__js_learnmanager_wishlist` AS shortlist ON  student.id = shortlist.student_id";
        if(in_array('message', jslearnmanager::$_active_addons)){
            $query .= " LEFT JOIN `#__js_learnmanager_messages` AS message ON message.student_uid = user.id";
        }
        $query .= " WHERE user.id = " . $uid;
        $db->setQuery($query);
        do_action("reset_jslmaddon_query");
        if($db->query()){
            if($is_delete_user){
                $filepath = $wpdir['basedir'] . '/' . $data_directory . '/data/profile/profile_' .$uid.'/';
                $this->removeStudentContents($filepath);
            }
            return true;
        }else{
            return false;
        }
    }

    function removeStudentContents($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir"){
                        $this->removeStudentContents($dir."/".$object);
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
        $_SESSION['jslm_studentdashoard_pages'] = $listingstyle;
        return ($listingstyle);
    }



    function deleteEnrollment($id,$cid){
        $notdeleted = 0;
        $status = 0;
        if( is_numeric($id) ){
            $status = $this->deleteStudentEnrollmentRecords($id,$cid);
            if(!$status){
                $notdeleted += 1;
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

    function deleteStudentEnrollmentRecords($id, $cid){
        if(!is_numeric($id))
            return false;
        if(!is_numeric($cid))
            return false;
        $db = new jslearnmanagerdb();
        $row = JSLEARNMANAGERincluder::getJSTable('studentenrollment');
        $data = JSLEARNMANAGERincluder::getJSModel('activitylog')->getDeleteActionDataToStore($row->tablename, $cid);
        $query = "SELECT id FROM `#__js_learnmanager_student_enrollment` WHERE student_id=" .$id . " AND course_id =" .$cid;
        $db->setQuery($query);
        $enrollmentid = $db->loadResult();
        $query = "DELETE shc
                    FROM `#__js_learnmanager_student_enrollment` AS shc
                        WHERE shc.student_id=" .$id . " AND shc.course_id =" .$cid;
        $db->setQuery($query);
        if($db->query()){
            JSLEARNMANAGERincluder::getJSModel('activitylog')->storeActivityLogForActionDelete($data, $enrollmentid);
            return true;
        }else{
            return false;
        }
    }

    // App Data
    function getProfileDataForApp(){

        $status = 1;
        $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-uid','post','');
        if(!is_numeric($id))
            $status = 0;
        $profiledata = array();
        if($status == 1){
            $db = new jslearnmanagerdb();

            $query = "SELECT s.name, s.bio, s.image, s.gender, u.country_id, u.email, u.facebook_url, u.twitter, u.linkedin, u.params, u.id as user_id, u.status
                        FROM `#__js_learnmanager_user` AS u
                            INNER JOIN `#__js_learnmanager_student` AS s ON u.id = s.user_id and u.id =" .$id;
            $db->setQuery($query);
            $profiledata['profile'] = $db->loadObject();
            if(!empty($profiledata['profile'])){
                $profiledata['profile']->location = JSLEARNMANAGERincluder::getJSModel('country')->getCountryNameById($profiledata['profile']->country_id);
            }
            $profiledata['fields'] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // For user profile
        }
        return $profiledata;
    }

    function getMyCoursesForStudentForApp(){
        $uid = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-uid','get','0');
        $limit = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_limit','get',10);
        $offset = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_offset','get',0);
        if($uid == 0){
            return false;
        }else{
            $studentid = $this->getStudentId($uid);
        }

        $db = new jslearnmanagerdb();

        // For Search
        $curdate = date_i18n('Y-m-d');
        $title = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-coursetitle','get','');
        $category = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-category','get','');
        $courselevel = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-courselevel','get','');
        $inquery = '';
        jslearnmanager::$_data['filter'] = array();

        if ($title != ""){
            jslearnmanager::$_data['filter']['coursetitle'] = $title;
            $inquery .= " AND c.title LIKE '%" . $title . "%'";
        }
        if(is_numeric($category)){
            jslearnmanager::$_data['filter']['category'] = $category;
            $inquery .= " AND c.category_id = " . $category;
        }
        if($courselevel != ""){
            jslearnmanager::$_data['filter']['skilllevel'] = $courselevel;
            $inquery .= " AND c.course_level =" .$courselevel;
        }
        if($studentid == null && $studentid == '')
            return false;

        // user stats
        $query = "SELECT COUNT(sc.id) FROM `#__js_learnmanager_student_enrollment` AS sc, `#__js_learnmanager_course` AS c
                    JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                        WHERE c.id = sc.course_id AND sc.student_id = " . $studentid ." AND c.course_status = 1 AND c.isapprove = 1";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        $return_data['total'] = $total;

        // user my courses
        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data');
        do_action('jslm_featuredcourse_select_query_data');
        do_action('jslm_paidcourse_join_select_query_data');
        $query = "SELECT c.id as courseid, c.title as coursetitle, accesstype.access_type as access_type, c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c.course_level as level, c.file as courseimage,
                        cat.category_name as coursecategory, intr.name as instructorname, intr.id as instructor_id, c.params, stdnt_c.created_at as enrolleddate,
                        COUNT(DISTINCT sect_lec.id) as totallessons, COUNT(DISTINCT stdnt_all.id) as totalstudents, ". jslearnmanager::$_addon_query['select'] ."  intr.image as instructorimage
                    FROM `#__js_learnmanager_course` AS c
                        LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                        LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                        LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                        LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_all ON c.id = stdnt_all.course_id
                        " . jslearnmanager::$_addon_query['join'] . "
                        JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                        WHERE stdnt_c.student_id = " .$studentid ." AND c.course_status = 1 AND c.isapprove = 1";
        $query .= $inquery;
        $query .= " GROUP BY c.id, stdnt_c.id";
        $query .= " ORDER BY stdnt_c.created_at DESC";
        $query .=" LIMIT " . $offset . "," . $limit;
        do_action("reset_jslmaddon_query");
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        foreach($courses as $course => $key){
            $courses[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",0,$key->courseid);
        }
        $return_data['data'] = $courses;
        $return_data['filter'] = jslearnmanager::$_data['filter'];
        return $return_data;
    }

    function getStudentApprovalStatus($id,$by=0){ // by for getting student id from uid
        if($by != 0){ // if $by != 0 than get studentid
            $id = $this->getStudentId($id);
        }
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb;
        $query = "SELECT approvalstatus FROM `#__js_learnmanager_student` WHERE id = ".$id;
        $db->setQuery($query);
        return $db->loadResult();
    }

     function getAdminStudentSearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $studentname = ($isadmin) ? 'studentname' : 'jslm_student';
        $jslms_search_array['studentname'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($studentname)));
        $jslms_search_array['studentemail'] = trim(JSLEARNMANAGERrequest::getVar('studentemail' , ''));
        $jslms_search_array['pagesize'] = absint(JSLEARNMANAGERrequest::getVar('pagesize'));
        $jslms_search_array['search_from_student'] = 1;
        return $jslms_search_array;
    }


}

?>
