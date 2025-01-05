<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcourseModel {

    function getCoursebyId($id){
        if(is_numeric($id) == false) return false;
        $db = new jslearnmanagerdb;
        $query = "SELECT c.* , u.*
                    FROM `#__js_learnmanager_course` AS c
                       INNER JOIN  `#__js_learnmanager_user` AS u ON u.id = c.instructor_id
                       WHERE c.id = " . $id;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObject();
        return;
    }

    function getCourseDetailsbyCourseId($id){
        if(is_numeric($id) == false) return false;
        $db = new jslearnmanagerdb;
        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data');
        do_action("jslm_paidcourse_join_select_query_data",0);
        $query = "SELECT c.id,c.title,". jslearnmanager::$_addon_query['select'] ." c.file
                    FROM `#__js_learnmanager_course` AS c
                    " . jslearnmanager::$_addon_query['join'] . "
                    WHERE c.id = " . $id;

        $query .= " GROUP BY c.id ";
        $db->setQuery($query);
        $data = $db->loadObject();
        do_action('reset_jslmaddon_query');
        return $data;
    }

    function getCoursedetailbyId($cid) {

        if (!is_numeric($cid)) return false;

        do_action("jslm_paidcourse_join_select_query_data",1);
        $db = new jslearnmanagerdb();
        $query = " SELECT c.id as course_id, c.title as title, accesstype.access_type as access_type, c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c_level.level, lang.language, c.learningoutcomes,
                    c.course_duration as duration, c.meta_description as meta_description, c.course_status, c.file as course_logo, c.video as course_video,cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, c.params,
                    COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students, ". jslearnmanager::$_addon_query['select'] ." intr.image as instructor_image
                        FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_user` AS user ON user.id = intr.user_id AND user.status = 1
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON sect.course_id =c.id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` as sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            -- LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            LEFT JOIN `#__js_learnmanager_course_level` AS c_level ON c_level.id = c.course_level
                            LEFT JOIN `#__js_learnmanager_language` AS lang ON lang.id = c.language
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                            WHERE c.course_status = 1 AND c.isapprove = 1 AND c.id =" .$cid;
        $db->setQuery($query);

        do_action("reset_jslmaddon_query");
        jslearnmanager::$_data[0]['coursedetail'] = $db->loadObject();
        if(isset(jslearnmanager::$_data[0]['coursedetail'])){
            jslearnmanager::$_data[0]['coursedetail']->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$cid,1);
            $enrolledstudents = $this->getAllEnrolledStudentinCourse($cid);
            jslearnmanager::$_data['enrolledstudents'] = $enrolledstudents;

            $totalquiz = apply_filters("jslm_quiz_get_course_total_quiz",0,$cid);
            jslearnmanager::$_data[0]['totalQuiz'] = $totalquiz;
        }

        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1);
        return;

    }

    function getCoursedetailForEditbyId($cid) {

        if (!is_numeric($cid)) return false;

        do_action("jslm_paidcourse_join_select_query_data",1);
        $db = new jslearnmanagerdb();
        $query = " SELECT c.id as course_id, c.title as title, accesstype.access_type as access_type,  c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c_level.level,lang.language, c.learningoutcomes,
                    c.course_duration as duration, c.meta_description as meta_description, c.course_status, c.file as course_logo, c.video as course_video,cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, c.params,
                    COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students, intr.image as instructor_image,". jslearnmanager::$_addon_query['select'] ."c.isapprove
                        FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON sect.course_id =c.id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` as sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            -- LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            LEFT JOIN `#__js_learnmanager_course_level` AS c_level ON c_level.id = c.course_level
                            LEFT JOIN `#__js_learnmanager_language` AS lang ON lang.id = c.language
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                            WHERE c.id =" .$cid;
        do_action("reset_jslmaddon_query");
        $db->setQuery($query);

        jslearnmanager::$_data[0]['coursedetail'] = $db->loadObject();
        if(isset(jslearnmanager::$_data[0]['coursedetail'])){
            jslearnmanager::$_data[0]['coursedetail']->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$cid,1);

            $comembers = $this->getAllEnrolledStudentinCourse($cid);
            jslearnmanager::$_data['enrolledstudents'] = $comembers;
            $totalquiz = apply_filters("jslm_quiz_get_course_total_quiz",0,$cid);
            jslearnmanager::$_data[0]['totalQuiz'] = $totalquiz;
        }

        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1);
        return;

    }

    function getLatestCourses(){
        $limittoshow = learn_manager_GetOptions();
        do_action("jslm_paidcourse_join_select_query_data",1);
        $db = new jslearnmanagerdb();
        $query = "SELECT c.title, c.id as course_id , i.name, c.file, accesstype.access_type, ". jslearnmanager::$_addon_query['select'] ."i.image,i.id as instructor_id
                    FROM `#__js_learnmanager_course` AS c
                        LEFT JOIN `#__js_learnmanager_instructor` AS i ON i.id = c.instructor_id
                        JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                        ". jslearnmanager::$_addon_query['join'] ."
                        WHERE c.course_status = 1 AND c.isapprove = 1
                        ORDER BY c.created_at DESC";
        $query .= " LIMIT " .$limittoshow['maximum_latestcourses'];
        do_action("reset_jslmaddon_query");
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        jslearnmanager::$_data['latestcourses'] = $courses;
        return;
    }
    function getSearchCourses(){


    }
    
    function sorting() {
        jslearnmanager::$_data['sorton'] = isset(jslearnmanager::$_data['filter']['sorton']) ? jslearnmanager::$_data['filter']['sorton'] : 3;
        jslearnmanager::$_data['sortby'] = isset(jslearnmanager::$_data['filter']['sortby']) ? jslearnmanager::$_data['filter']['sortby'] : 2;
        switch (jslearnmanager::$_data['sorton']) {
            case 5:
                $sort_string = 'c.title';
                break;
            case 4:
                $sort_string = 'c.access_type';
                break;
            case 3: // created
                $sort_string = 'c.created_at';
                break;
            case 2: // price
                $sort_string = '';
                if(in_array('paidcourse',jslearnmanager::$_active_addons))
                    $sort_string = ' c.price ';
                break;
            case 1: // category
                $sort_string = 'cat.category_name';
                break;
        }
        if (jslearnmanager::$_data['sortby'] == 2) {
                  $sort_string .= ' ASC ';
             } else {
                  $sort_string .= ' DESC ';
        }
        jslearnmanager::$_data['combosort'] = jslearnmanager::$_data['sorton'];

        return $sort_string;
    }

    function getAllCourses($datafor){
        //Sorting
        $sort_string = $this->sorting();

        //DB Object

        $db = new jslearnmanagerdb();
        $isadmin = is_admin();
        $title = ($isadmin) ? 'title' : 'jslm_course';
        //Filter
        $title = jslearnmanager::$_data['filter']['title'];
        $category =jslearnmanager::$_data['filter']['category']; 
        // JSLEARNMANAGERrequest::getVar('category');
        if(jslearnmanager::$_data['filter']['access_type'] == ''){
            $access_type = JSLEARNMANAGERrequest::getVar('access_type');
        }else{
            $access_type =jslearnmanager::$_data['filter']['access_type']; 
        }
        if(jslearnmanager::$_data['filter']['isgfcombo'] == ''){
            $isgfcombo = jslearnmanager::$_data['filter']['isgfcombo'] =='';
        }else{
            $isgfcombo = jslearnmanager::$_data['filter']['isgfcombo']; 
        }
        if(in_array('paidcourse', jslearnmanager::$_active_addons)){
            $pricestart = JSLEARNMANAGERrequest::getVar('pricestart');
            $priceend = JSLEARNMANAGERrequest::getVar('priceend');
        }
        if(jslearnmanager::$_data['filter']['skilllevel'] == ''){
            $skilllevel =JSLEARNMANAGERrequest::getVar('skilllevel');
        }else{
            $skilllevel =jslearnmanager::$_data['filter']['skilllevel']; 
        }
        if(jslearnmanager::$_data['filter']['language'] == ''){
            $language =   JSLEARNMANAGERrequest::getVar('language');
        }else{
            $language =jslearnmanager::$_data['filter']['language']; 
        }
        if(jslearnmanager::$_data['filter']['status'] == ''){
               $status = JSLEARNMANAGERrequest::getVar('status');
        }else{
            $status =jslearnmanager::$_data['filter']['status']; 
        }
        if(jslearnmanager::$_data['filter']['isapprove'] == ''){
            $isapprove =JSLEARNMANAGERrequest::getVar('isapprove');
        }else{
        
            $isapprove =jslearnmanager::$_data['filter']['isapprove']; 
        }
        
        $inquery = '';
        // $curdate = date_i18n('Y-m-d');
        do_action("jslm_featuredcourse_select_query_for_combobox_and_admin_courses",$datafor,$isgfcombo);
        if($datafor == 1){
            switch ($isgfcombo) {
                case '1':
                case '2':
                    $inquery .= " WHERE 1=1 " . jslearnmanager::$_addon_query['where'];
                break;
                default:
                    $inquery .= " WHERE ((c.isapprove = 1 OR c.isapprove = -1))";
                    break;
            }
        }
        else{ // For Queue
            switch ($isgfcombo) {
                case '1':
                    $inquery = " WHERE (c.isapprove = 0 ". jslearnmanager::$_addon_query['where'] .") ";
                break;
                case '2':
                    $inquery = " WHERE 1=1 AND " . jslearnmanager::$_addon_query['where'];
                break;
                default:
                    $inquery = " WHERE (".jslearnmanager::$_addon_query['where']." (c.isapprove = 0 OR c.isapprove = -1 OR c.isapprove = 2)) ";
                break;
            }
        }

        $oper = "AND";

        if (is_string($title)){
            $inquery .= $oper." c.title LIKE '%" . $title . "%'";
            $oper = " AND";
        }
        if(is_numeric($category)){
            $inquery .= $oper." c.category_id = " . $category;
            $oper = " AND";
        }
        if(is_string($access_type)){
            $inquery .= $oper." c.access_type =" . $access_type;
            $oper = " AND";
        }
        if(isset($pricestart) && is_numeric($pricestart)){
            $inquery .= $oper." c.price >= " . $pricestart;
            $oper = " AND";
        }
        if(isset($endprice) && is_numeric($priceend)){
            $inquery .= $oper." c.price <= " . $priceend;
            $oper = " AND";
        }
        if(is_string($skilllevel)){
            $inquery .= $oper." c.course_level LIKE '%" . $skilllevel . "%'";
            $oper = " AND";
        }
        if(is_string($language)){
            $inquery .= $oper." c.language = " . $language;
            $oper = " AND";
        }
        if(is_numeric($status)){
            $inquery .= $oper." c.course_status = " . $status;
            $oper = " AND";
        }
        if(is_numeric($isapprove)){
            $inquery .= $oper." c.isapprove = " . $isapprove;
            $oper = " AND";
        }

        jslearnmanager::$_data['filter']['status'] = $status;
        jslearnmanager::$_data['filter']['title'] = $title;
        jslearnmanager::$_data['filter']['category'] = $category;
        jslearnmanager::$_data['filter']['access_type'] = $access_type;
        jslearnmanager::$_data['filter']['isgfcombo'] = $isgfcombo;
        if(in_array('paidcourse', jslearnmanager::$_active_addons)){
            jslearnmanager::$_data['filter']['pricestart'] = $pricestart;
            jslearnmanager::$_data['filter']['priceend'] = $priceend;
        }
        jslearnmanager::$_data['filter']['skilllevel'] = $skilllevel;
        jslearnmanager::$_data['filter']['language'] = $language;
        //pagination
        $query = "SELECT COUNT(c.id)
                    FROM `#__js_learnmanager_course` AS c";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data');
        do_action('jslm_paymentplan_within_table_query_data',"pp","c");
        do_action("jslm_paidcourse_join_select_query_data",1);
        //data
        $query = "SELECT c.id as course_id, c.title as title, c.access_type as access_type, c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c.course_level as level,
                    cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id,c.file as file,c.start_date as start_date,c.expire_date as expire_date,c.course_status as course_status,c.created_at as created_at,
                    COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students, ". jslearnmanager::$_addon_query['select'] ." c.isapprove,courselang.language
                    FROM `#__js_learnmanager_course` AS c
                        LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                        LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                        LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                        LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                        LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                        " . jslearnmanager::$_addon_query['join'] . "
                        LEFT JOIN `#__js_learnmanager_language` AS courselang ON courselang.id = c.language
                ";
        $query .= $inquery;
        $query .= " GROUP BY c.id";
        $query .= " ORDER BY ".$sort_string;
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset ." , ". JSLEARNMANAGERpagination::$_limit;
        do_action('reset_jslmaddon_query');
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        jslearnmanager::$_data[0] = $courses;
        return;
    }

    function getCourses($searchform=0){

        $sort_string = $this->sorting();
        $db = new jslearnmanagerdb();

        $curdate = date_i18n('Y-m-d');
        //DB Object
        $title = isset(jslearnmanager::$_data['filter']['coursetitle']) ? jslearnmanager::$_data['filter']['coursetitle'] : '';
         // jslearnmanager::$_data['filter']['coursetitle'];
          // JSLEARNMANAGERrequest::getVar('coursetitle','post');
        $category = isset(jslearnmanager::$_data['filter']['category']) ? jslearnmanager::$_data['filter']['category'] : '';
        $access_type = JSLEARNMANAGERrequest::getVar('access_type','post');
            // $access_type = jslearnmanager::$_data['filter']['access_type'] == $access_type;
        if(in_array('paidcourse', jslearnmanager::$_active_addons)){
            $currency = JSLEARNMANAGERrequest::getVar('currencyid','post');
            $startprice = JSLEARNMANAGERrequest::getVar('rangestart','post');
            $endprice = JSLEARNMANAGERrequest::getVar('rangeend','post');
        }
        // $language = jslearnmanager::$_data['filter']['language'];
        $language = isset(jslearnmanager::$_data['filter']['language']) ? jslearnmanager::$_data['filter']['language'] : '';
        $courselevel = isset(jslearnmanager::$_data['filter']['courselevel']) ? jslearnmanager::$_data['filter']['courselevel'] : '';
        $instructorname = isset(jslearnmanager::$_data['filter']['instructorname']) ? jslearnmanager::$_data['filter']['instructorname'] : '';
;
        // $courseduration = JSLEARNMANAGERrequest::getVar('course_duration','post');
        $inquery = '';
        if (is_string($title)){
            $inquery .= " AND c.title LIKE '%" . $title . "%'";
        }
        if(is_numeric($category)){
            $inquery .= " AND c.category_id = " . $category;
        }
        if(is_string($access_type)){
            $inquery .= " AND c.access_type LIKE '%" . $access_type . "%'";
        }
        if(isset($currency) &&  is_numeric($currency)){
            $inquery .= " AND c.currency = " .$currency;
        }
        if(isset($startprice) && is_numeric($startprice)){
            $inquery .= " AND c.price >= " .$startprice;
        }
        if(isset($endprice) && is_numeric($endprice)){
            $inquery .= " AND c.price <= " .$endprice;
        }
        if(is_numeric($language)){
            $inquery .= " AND c.language LIKE '%" .$language . "%'";
        }
        if(is_numeric($courselevel)){
            $inquery .= " AND c.course_level =" .$courselevel;
        }
        if(is_string($instructorname)){
            $inquery .= " AND intr.name LIKE '%" . $instructorname . "%'";
        }
        // if(is_numeric($courseduration)){
        //     $inquery .= " AND c.course_duration = " .$courseduration;
        // }

        jslearnmanager::$_data['filter']['coursetitle'] = $title;
        jslearnmanager::$_data['filter']['category'] = $category;
        jslearnmanager::$_data['filter']['access_type'] = $access_type;
        if(in_array('paidcourse', jslearnmanager::$_active_addons)){
            jslearnmanager::$_data['filter']['currencyid'] = $currency;
            jslearnmanager::$_data['filter']['pricestart'] = $startprice;
            jslearnmanager::$_data['filter']['priceend'] = $endprice;
        }
        jslearnmanager::$_data['filter']['skilllevel'] = $courselevel;
        jslearnmanager::$_data['filter']['language'] = $language;
        jslearnmanager::$_data['filter']['instructorname'] = $instructorname;
        // jslearnmanager::$_data['filter']['duration'] = $courseduration;

        if($searchform == 1){
            jslearnmanager::$_data['issearchform'] = $searchform;
            $inquery .= $this->getrefinedcourses();
        }

        //pagination
        $query = "SELECT COUNT(c.id)
                    FROM `#__js_learnmanager_course` AS c
                    LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                    LEFT JOIN `#__js_learnmanager_user` AS user ON user.id = intr.user_id  AND user.status = 1
                    JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                    WHERE c.course_status = 1 AND c.isapprove = 1 ";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total,'courselist');

        $config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('course');
        $showfeaturedcourses = $config_array['showfeaturedcourseinlistcourses'];
        $nooffeaturedcourses = $config_array['nooffeaturedcourseinlisting'];
        $courses = $featuredcourses = array();

        //data
        do_action('jslm_featuredcourse_select_query_data');
        do_action("jslm_paidcourse_join_select_query_data",1);
        $query = "SELECT c.id as course_id, c.title as title,  c.course_code as course_code, c.subtitle as subtitle, c.course_level as course_level,  c.file as fileloc,".jslearnmanager::$_addon_query['select']."c.description,
                        cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, intr.image as instructor_image, accesstype.access_type, c.params,
                        COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students, c.created_at
                        FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_user` AS user ON user.id = intr.user_id  AND user.status = 1
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            WHERE c.course_status = 1 AND c.isapprove = 1";
        $query .= $inquery;
        $query .= " GROUP BY c.id";
        $query .= " ORDER BY ".$sort_string;
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset ." , ". JSLEARNMANAGERpagination::$_limit;
        do_action("reset_jslmaddon_query");        
       $db->setQuery($query);
        $courses = $db->loadObjectList();
        foreach($courses as $course => $key){
            $courses[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$key->course_id,1);
        }

        if($showfeaturedcourses == 1 && is_numeric($nooffeaturedcourses) && $nooffeaturedcourses != 0){
            $pagenum = JSLEARNMANAGERrequest::getVar('pagenum');
            $featuredcourses = apply_filters("jslm_featuredcourse_show_feature_course_data_course_list",'',$showfeaturedcourses,$nooffeaturedcourses,$pagenum,$sort_string,$db,$inquery);
        }
        $courses_array = array();
        if($featuredcourses != ''){
            foreach($featuredcourses as $course => $key){
                $featuredcourses[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$key->course_id,1);
            }
            $courses_array = $featuredcourses;
        }
        foreach ($courses AS $simple) {
            $matched = 0;
            foreach ($courses_array AS $course) {
                if($simple->course_id == $course->course_id){
                    $matched = 1;
                }
            }
            if($matched == 0){
                $courses_array[] = $simple;
            }
        }

        jslearnmanager::$_data[0] = $courses_array;
        $fieldorderings = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingByFor(1);
        jslearnmanager::$_data[3] = $fieldorderings;


        return;
    }

    function getrefinedcourses(){
        $inquery = "";
        $data = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsData(1);
        $valarray = array();
        if (!empty($data)) {
            foreach ($data as $uf) {
                $valarray[$uf->field] = JSLEARNMANAGERrequest::getVar($uf->field, 'post');
                if(isset($valarray[$uf->field]) && $valarray[$uf->field] != null){
                    $_SESSION['JSLEARNMANAGER_SEARCH'][$uf->field] = $valarray[$uf->field];
                }
                if (JSLEARNMANAGERrequest::getVar('pagenum', 'get', null) != null) {
                    $valarray[$uf->field] = (isset($_SESSION['JSLEARNMANAGER_SEARCH'][$uf->field]) && $_SESSION['JSLEARNMANAGER_SEARCH'][$uf->field] != '') ? $_SESSION['JSLEARNMANAGER_SEARCH'][$uf->field] : null;
                }
                if (isset($valarray[$uf->field]) && $valarray[$uf->field] != null) {
                    switch ($uf->userfieldtype) {
                        case 'text':
                            $inquery .= ' AND c.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                            break;
                        case 'combo':
                            $inquery .= ' AND c.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'depandant_field':
                            $inquery .= ' AND c.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'radio':
                            $inquery .= ' AND c.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'checkbox':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                $finalvalue .= $value.'.*';
                            }
                            $inquery .= ' AND c.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($finalvalue) . '.*"\' ';
                            break;
                        case 'date':
                            $inquery .= ' AND c.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'textarea':
                            $inquery .= ' AND c.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                            break;
                        case 'multiple':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                $finalvalue .= $value.'.*';
                            }
                            $inquery .= ' AND c.params REGEXP \'"' . $uf->field . '":"[^"].*'.htmlspecialchars($finalvalue).'.*"\'';
                            break;
                    }

                    jslearnmanager::$_data['filter']['params'] = $valarray;
                }
            }
        }
        return $inquery;
    }

    function getCoursesByCategoryId($id){
        if(!is_numeric($id))
            return false;

        //Sorting
        $sort_string = $this->sorting();

        //DB Object
        $db = new jslearnmanagerdb();
        $curdate = date_i18n('Y-m-d');
        $title = JSLEARNMANAGERrequest::getVar('coursetitle');
        $category = $id;
        $access_type = JSLEARNMANAGERrequest::getVar('access_type');
        $inquery = '';
        $formsearch = JSLEARNMANAGERrequest::getVar('JSLEARNMANAGER_form_search', 'post');

        if($formsearch == 'JSLEARNMANAGER_SEARCH'){

            $_SESSION['JSLEARNMANAGER_SEARCH']['coursetitle'] = $title;
            $_SESSION['JSLEARNMANAGER_SEARCH']['category'] = $category;
            $_SESSION['JSLEARNMANAGER_SEARCH']['access_type'] = $access_type;
        }

        if (JSLEARNMANAGERrequest::getVar('pagenum', 'get', null) != null) { //Pagination set
            $title = (isset($_SESSION['JSLEARNMANAGER_SEARCH']['coursetitle']) && $_SESSION['JSLEARNMANAGER_SEARCH']['coursetitle'] != '') ? sanitize_key($_SESSION['JSLEARNMANAGER_SEARCH']['coursetitle']) : null;
            $category = (isset($_SESSION['JSLEARNMANAGER_SEARCH']['category']) && $_SESSION['JSLEARNMANAGER_SEARCH']['category'] != '') ? sanitize_key($_SESSION['JSLEARNMANAGER_SEARCH']['category']) : null;
            $access_type = (isset($_SESSION['JSLEARNMANAGER_SEARCH']['access_type']) && $_SESSION['JSLEARNMANAGER_SEARCH']['access_type'] != '') ? sanitize_key($_SESSION['JSLEARNMANAGER_SEARCH']['access_type']) : null;
        }elseif ($formsearch != 'JSLEARNMANAGER_SEARCH') {
            unset($_SESSION['JSLEARNMANAGER_SEARCH']);
        }
        if (is_string($title)){
            $inquery .= " AND c.title LIKE '%" . $title . "%'";
        }
        if(is_numeric($category)){
            $inquery .= " AND c.category_id = " . $category;
        }
        if(is_string($access_type)){
            $inquery .= " AND c.access_type LIKE '%" . $access_type . "%'";
        }
        jslearnmanager::$_data['filter']['coursetitle'] = $title;
        jslearnmanager::$_data['filter']['category'] = $category;
        jslearnmanager::$_data['filter']['access_type'] = $access_type;

        //pagination
        $query = "SELECT COUNT(c.id)
                    FROM `#__js_learnmanager_course` AS c WHERE c.course_status = 1 AND c.isapprove = 1 AND c.category_id=" .$id;
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data');
        do_action('jslm_featuredcourse_select_query_data');
        do_action("jslm_paidcourse_join_select_query_data",1);
        //data
        $query = "SELECT c.id as course_id, c.title as title,  c.course_code as course_code, c.subtitle as subtitle,c.description as description,  c.course_level as course_level, c.file as fileloc,
                        cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, intr.image as instructor_image, accesstype.access_type, c.params,
                        COUNT(DISTINCT sect_lec.id) as total_lessons, " . jslearnmanager::$_addon_query['select'] . " COUNT(DISTINCT stdnt_c.id) as total_students
                        FROM `#__js_learnmanager_course` AS c
                            INNER JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            WHERE c.course_status = 1 AND c.isapprove = 1 AND c.category_id =" .$id;
        $query .= $inquery;
        $query .= " GROUP BY c.id";
        $query .= " ORDER BY ".$sort_string;
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset ." , ". JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        do_action("reset_jslmaddon_query");
        foreach($courses as $course => $key){
            $courses[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$key->course_id);
        }
        jslearnmanager::$_data[0] = $courses;
        $fieldorderings = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingByFor(1);
        jslearnmanager::$_data[3] = $fieldorderings;
        return;

    }

    function getCourseImage($courseid){
        if(!is_numeric($courseid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT c.file
                    FROM `#__js_learnmanager_course` AS c
                        WHERE c.id =" .$courseid;
        $db->setQuery($query);
        $images = $db->loadResult();
        return $images;
    }

    function getRecentCourses($instructorid){ // By instructor id
        if(!is_numeric($instructorid))
            return false;

        do_action("jslm_paidcourse_join_select_query_data",1);
        $db = new jslearnmanagerdb();
        $query = "SELECT c.title,c.id, c.file,accesstype.access_type, ". jslearnmanager::$_addon_query['select'] ." c.course_status, category.category_name as category
                    FROM `#__js_learnmanager_course` AS c
                    LEFT JOIN  `#__js_learnmanager_category` AS category ON c.category_id = category.id
                    JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                    ". jslearnmanager::$_addon_query['join'] ."
                        WHERE instructor_id =" .$instructorid;
        $query .= " ORDER BY c.created_at DESC LIMIT 4";
        do_action("reset_jslmaddon_query");
        $db->setQuery($query);
        jslearnmanager::$_data['recentcreate'] = $db->loadObjectList();
        return ;
    }

    function getStudentRecentCourses($studentid){
        if(!is_numeric($studentid))
            return false;
        $db = new jslearnmanagerdb();

        // user my courses
        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data');
        do_action('jslm_featuredcourse_select_query_data');
        do_action("jslm_paidcourse_join_select_query_data",1);
        $query = "SELECT c.id as course_id, c.file,c.title as title, c.params,accesstype.access_type as access_type,  c.course_status, c.course_code as course_code,c.file as fileloc,
                    c.subtitle as subtitle, c.description as c_description, c.course_level as level, cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, COUNT(DISTINCT stdnt_c.id) as total_students, ". jslearnmanager::$_addon_query['select'] ." intr.image as instructor_image
                FROM `#__js_learnmanager_course` AS c
                INNER JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON  stdnt_c.course_id = c.id
                JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                ". jslearnmanager::$_addon_query['join'] ."
                WHERE stdnt_c.student_id = ".$studentid." AND c.course_status = 1 AND c.isapprove = 1 GROUP BY c.id , stdnt_c.created_at ORDER BY stdnt_c.created_at DESC LIMIT 3";

        $db->setQuery($query);
        $recentenroll = $db->loadObjectList();
        jslearnmanager::$_data['recentenroll'] = $recentenroll;
        do_action("reset_jslmaddon_query");
        foreach($recentenroll as $course => $key){
            $recentenroll[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$key->course_id,1);
        }
        return ;
    }

    function getEnrollCoursesByStudentid($studentid){ // By Student Id
        if(!is_numeric($studentid)) return false;

        $db = new jslearnmanagerdb();

        //pagination
        $query = "SELECT COUNT(id)
                    FROM  `#__learnmanager_student_enrollment`
                        WHERE student_id = " .$studentid;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        //data
        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data');
        do_action("jslm_paidcourse_course_price_select_query_data");
        $query = "SELECT c.id as course_id, c.title as title, c.access_type as access_type, c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c.course_level as level,
                        cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id,
                        COUNT(DISTINCT sect_lec.id) as total_lessons, ". jslearnmanager::$_addon_query['select'] ." COUNT(DISTINCT stdnt_c.id) as total_students
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                        LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                        LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                        LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                        LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                        LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                        ". jslearnmanager::$_addon_query['join'] ."
                        WHERE stdnt_c.student_id = " .$studentid;
        $query .= " GROUP BY c.id";
        $query .= " ORDER BY c.id";
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset . ", " . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        do_action('reset_jslmaddon_query');
        $courses = $db->loadObjectList();
        jslearnmanager::$_data[0] = $courses;

        return;
    }

    function getShortlistCourseByStudentid($studentid = 0){ // By Student Id
        //Sorting
        $sort_string = $this->sorting();
        $subquery = '';
        //db object
        $db = new jslearnmanagerdb();
        $curdate = date_i18n('Y-m-d');
        $title =  isset(jslearnmanager::$_data['filter']['coursetitle']) ? jslearnmanager::$_data['filter']['coursetitle'] : '';
        // JSLEARNMANAGERrequest::getVar('coursetitle');
        $category =  isset(jslearnmanager::$_data['filter']['category']) ? jslearnmanager::$_data['filter']['category'] : '';
        // JSLEARNMANAGERrequest::getVar('category');
        $inquery = '';




        if (is_string($title)){
            $inquery .= " AND c.title LIKE '%" . $title . "%'";
        }
        if(is_numeric($category)){
            $inquery .= " AND c.category_id = " . $category;
        }

        jslearnmanager::$_data['filter']['coursetitle'] = $title;
        jslearnmanager::$_data['filter']['category'] = $category;
        $joinquery = '';
        if (is_numeric($studentid) && $studentid != 0) {
            $subquery .= " AND ( w.student_id = $studentid ";
            $joinquery = " INNER JOIN `#__js_learnmanager_student` AS s ON s.id = w.student_id";
        }
        $shortlistedid_cookie = $this->getShortListedIDArray();

        if (!empty($shortlistedid_cookie)) {
            if (!is_numeric($studentid)) {
                $subquery .= ' AND ';
            } else {
                $subquery .= ' OR ';
            }
            $subquery .= ' w.id IN(' . implode(', ', $shortlistedid_cookie) . ')';
        }
        if (is_numeric($studentid) && $studentid != 0) {
            $subquery .= ' ) ';
        }
        $this->getSortingForListing();
        jslearnmanager::$_data[3] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingByFor(1);
        if (empty($shortlistedid_cookie) && !is_numeric($studentid)) {
            return false;
        }



        //Pagination
        $query = "SELECT COUNT(w.id) FROM `#__js_learnmanager_wishlist` AS w
                    INNER JOIN `#__js_learnmanager_course` AS c ON w.course_id = c.id
                    LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                    LEFT JOIN `#__js_learnmanager_instructor` AS instructor ON instructor.id = c.instructor_id
                    JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                    ";
        $query .= $joinquery;
        $query .= " WHERE 1=1 ";
        $query .= $inquery;
        $query .= $subquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data['shortlist_pagination'] = JSLEARNMANAGERpagination::getPagination($total,'shortlistcourses');

        do_action('jslm_featuredcourse_select_query_data');
        do_action('jslm_paidcourse_join_select_query_data',1);
        //data
        $query = "SELECT c.id as course_id, c.title as title, accesstype.access_type as access_type, c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c_level.level as level, c.file as fileloc, ". jslearnmanager::$_addon_query['select'] ."c.description,
                         cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, intr.image as instructor_image, c.params,
                         COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students, w.id as shortlist_id
                        FROM `#__js_learnmanager_course` AS c
                            INNER JOIN `#__js_learnmanager_wishlist` AS w ON c.id = w.course_id
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            LEFT JOIN `#__js_learnmanager_course_level` AS c_level ON c_level.id = c.course_level
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            WHERE 1 = 1 ";
        $query .= $inquery;
        $query .= $subquery;
        $query .= " GROUP BY c.id, w.id";
        $query .= " ORDER BY ".$sort_string;
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset . ", " . JSLEARNMANAGERpagination::$_limit;
        do_action("reset_jslmaddon_query");
        $db->setQuery($query);
        $wishlist = $db->loadObjectList();
        foreach($wishlist as $wish => $key){
            $wishlist[$wish]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$key->course_id,1);
        }
        jslearnmanager::$_data['shortlist'] = $wishlist;


        return;
    }

    function getShortlistCourseByStudentidForDashoboard($studentid){
        $db = new jslearnmanagerdb();
        //data
        $query = "SELECT c.id as course_id, c.title as title,intr.name as instructor_name, intr.id as instructor_id, intr.image as instructor_image
                        FROM `#__js_learnmanager_course` AS c
                            INNER JOIN `#__js_learnmanager_wishlist` AS w ON c.id = w.course_id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_user` AS user ON user.id = intr.user_id AND user.status = 1
                            WHERE w.student_id = $studentid
                            ";
        $query .= " GROUP BY c.id, w.id";
        $query .= " ORDER BY w.created_at DESC";
        $query .= " LIMIT 0 , 4";
        $db->setQuery($query);
        $wishlist = $db->loadObjectList();
        jslearnmanager::$_data['shortlist'] = $wishlist;
    }

    function getCourseCategoryBycid($cid){
        if(!is_numeric($cid))
            return false;

        $db = new jslearnmanagerdb();
        $query = "SELECT category_id FROM `#__js_learnmanager_course` WHERE id=" .$cid;
        $db->setQuery($query);
        $category = $db->loadResult();
        return $category;
    }

    function getRelatedCourse($cid){
        if(!is_numeric($cid))
            return false;

        $courses = $this->getRelatedCourseByCategory($cid);
        if(empty($courses) || count($courses) <= 0){
            $courses = $this->getRelatedCourseByTitle($cid);
            if(empty($courses) || count($courses) <= 0){
                $courses = $this->getRelatedCourseByInstructor($cid);
            }
        }

        jslearnmanager::$_data['relatedcourse'] = $courses;
        return;
    }

    function getRelatedCourseByCategory($cid){
        if(!is_numeric($cid))
            return false;
        $category_id = $this->getCourseCategoryBycid($cid);

        if(!is_numeric($category_id))
            return false;
        $limitto = learn_manager_GetOptions();
        $db = new jslearnmanagerdb();
        do_action('jslm_featuredcourse_select_query_data');
        do_action("jslm_paidcourse_join_select_query_data");
        $query = "SELECT c.id as course_id, c.title as title,  c.course_code as course_code, c.subtitle as subtitle, c.course_level as course_level, c.file as fileloc,
                        cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, intr.image as instructor_image, accesstype.access_type,
                        COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students,   ".jslearnmanager::$_addon_query['select']." c.params
                        FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            WHERE c.course_status = 1 AND c.isapprove = 1 AND c.category_id=" .$category_id. " AND c.id !=" .$cid;
        $query .= " GROUP BY c.id ORDER BY c.created_at DESC LIMIT ".$limitto['maximum_relatedcourses'];
        do_action('reset_jslmaddon_query');
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        foreach($courses as $course => $key){
            $courses[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$key->course_id);
        }
        return $courses;
    }

    function getRelatedCourseByTitle($cid){
        if(!is_numeric($cid))
            return false;

        $title = $this->getCourseNameById($cid);
        if(!is_string($title))
            return false;
        $limitto = learn_manager_GetOptions();
        do_action('jslm_featuredcourse_select_query_data');
        do_action('jslm_paidcourse_join_select_query_data');
        $db = new jslearnmanagerdb();
        $query = "SELECT c.id as course_id, c.title as title, c.course_code as course_code, c.subtitle as subtitle, c.course_level as course_level,c.file as fileloc,".jslearnmanager::$_addon_query['select']."
                        cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, intr.image as instructor_image, accesstype.access_type,
                        COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students, c.params
                        FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            WHERE c.course_status = 1 AND c.isapprove = 1 AND c.title LIKE '%" .$title. "%' AND c.id !=" .$cid;
        $query .= " GROUP BY c.id ORDER BY c.created_at DESC LIMIT ".$limitto['maximum_relatedcourses'];
        do_action('reset_jslmaddon_query');
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        foreach($courses as $course => $key){
            $courses[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$key->course_id);
        }
        return $courses;
    }

    function getRelatedCourseByInstructor($cid){
        if(!is_numeric($cid))
            return false;
        $instructor_id = $this->getInstructorIdByCourseId($cid);
        if(!is_numeric($instructor_id))
            return false;
        $limitto = learn_manager_GetOptions();
        do_action('jslm_featuredcourse_select_query_data');
        do_action('jslm_paidcourse_join_select_query_data');
        $db = new jslearnmanagerdb();
        $query = "SELECT c.id as course_id, c.title as title, c.course_code as course_code, c.subtitle as subtitle, c.course_level as course_level,c.file as fileloc,".jslearnmanager::$_addon_query['select']."
                        cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, intr.image as instructor_image, accesstype.access_type,
                        COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students,  c.params
                        FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            WHERE c.course_status = 1 AND c.isapprove = 1 AND c.instructor_id=" .$instructor_id. " AND c.id !=" .$cid;
        $query .= " GROUP BY c.id ORDER BY c.created_at DESC LIMIT ".$limitto['maximum_relatedcourses'];
        do_action('reset_jslmaddon_query');
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        foreach($courses as $course => $key){
            $courses[$course]->reviews = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",false,$key->course_id);
        }
        return $courses;
    }

    function getRelatedCoursesForDashboard($uid){
        if(!is_numeric($uid))
            return false;

        $student = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
        $db = new jslearnmanagerdb();
        $query = "SELECT cat.id
                    FROM `#__js_learnmanager_category` AS cat
                        INNER JOIN `#__js_learnmanager_course` AS c ON cat.id = c.category_id AND cat.status = 1
                        INNER JOIN `#__js_learnmanager_student_enrollment` AS sc ON c.id = sc.course_id
                        WHERE sc.student_id =" .$student. " GROUP BY cat.id ORDER BY COUNT(c.category_id) DESC LIMIT 1";
        $db->setQuery($query);
        $catids = $db->loadObjectList();

        if(empty($catids)){
            return false;
        }

        $catids_string = '';
        $comma = '';
        foreach ($catids as $cat) {
            if(is_numeric($cat->id)){
                $catids_string .= $comma.$cat->id;
                $comma = ',';
            }
        }
        if($catids_string == ''){
            return false;
        }
        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data');
        do_action('jslm_featuredcourse_select_query_data');
        do_action("jslm_paidcourse_join_select_query_data");
        $query = "SELECT c.id AS course_id, c.title AS title, c.course_code AS course_code, c.subtitle AS subtitle,c.course_level AS course_level,  c.file AS fileloc,  c.params,
                 cat.category_name AS category, intr.name AS instructor_name, intr.id AS instructor_id, intr.image AS instructor_image, accesstype.access_type, COUNT(DISTINCT sect_lec.id) AS total_lessons, ". jslearnmanager::$_addon_query['select'] ." COUNT(DISTINCT stdnt_c.id) AS total_students
                    FROM `#__js_learnmanager_course` AS c
                    INNER JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                    LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                    LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                    LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                    LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                    LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                    ". jslearnmanager::$_addon_query['join'] ."
                    JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                    WHERE c.isapprove = 1 AND c.course_status = 1 AND c.category_id IN (".$catids_string.")
                    GROUP BY c.id LIMIT 6";
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        do_action("reset_jslmaddon_query");
        jslearnmanager::$_data['relatedcourse'] = $courses;
        return;
    }

    function getCourseByPriceType(){
        $db = new jslearnmanagerdb();
        $query="SELECT a.access_type, COUNT(c.id) as total,c.id as courseid
            FROM `#__js_learnmanager_course_access_type` AS a
            INNER JOIN `#__js_learnmanager_course` AS c ON a.id = c.access_type
            WHERE c.course_status = 1 AND c.isapprove = 1 AND a.status = 1
            GROUP BY a.id";
        $db->setQuery($query);
        $CourseBytype = $db->loadObjectList();
        jslearnmanager::$_data['coursebytype'] = $CourseBytype;
        return;


    }

    function countCourseByCategory($forapp=0){
        if($forapp == 0){
            $limit = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('no_of_categories_rightbar');
        }else{
            $limit = 6;
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(c.id) as total, cat.category_name, cat.id as category_id
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_category` AS cat ON cat.id = c.category_id
                        INNER JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                        WHERE c.course_status = 1 AND c.isapprove = 1";
        if($forapp == 0){
            $query .= " GROUP BY cat.id ORDER BY cat.category_name ASC LIMIT ".$limit;
        }else{
            $query .= " GROUP BY cat.id ORDER BY total DESC LIMIT ".$limit;
        }

        $db->setQuery($query);
        $coursescount = $db->loadObjectList();

        if($forapp == 0){
            jslearnmanager::$_data['categorycount'] = $coursescount;
        }else{
            return $coursescount;
        }

        return;
    }

    function getCategoriesCountList(){
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(cat.id) FROM `#__js_learnmanager_category` AS cat WHERE cat.status = 1";
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        $query = " SELECT cat.category_name, cat.id as category_id, (SELECT COUNT(c.id) FROM  `#__js_learnmanager_course` AS c
                    LEFT JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type
                    WHERE cat.id = c.category_id AND c.course_status = 1 AND c.isapprove = 1 AND accesstype.status = 1)  as total
                    FROM `#__js_learnmanager_category` AS cat
                        GROUP BY cat.id ORDER BY cat.category_name ASC ORDER BY cat.category_name";
        $query .= " LIMIT ". JSLEARNMANAGERpagination::$_offset . ", " . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        $coursescount = $db->loadObjectList();
        jslearnmanager::$_data[0] = $coursescount;
        return;
    }

    function getAllEnrolledStudentinCourse($cid=null){
        $limit = jslearnmanager::$_config['pagination_default_page_size'];
        if($cid == null){
            $cid = JSLEARNMANAGERrequest::getVar('cid');
        }
        if(!is_numeric($cid))
            return false;
        if(JSLEARNMANAGERrequest::getVar('pagei') != "") {
            $page = JSLEARNMANAGERrequest::getVar('pagei');
            $offset = $limit * ($page - 1);
            $ajax = 1;
        }else {
            $page = 1;
            $offset = 0;
            $ajax = 0;
        }
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');

        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(sc.id) FROM `#__js_learnmanager_student_enrollment` AS sc WHERE sc.course_id =" .$cid;
        $db->setQuery($query);
        $total = $db->loadResult();
        $total_pages = ceil($total/$limit);
        jslearnmanager::$_data['enrolledstudentspagination'] = $total_pages;
        jslearnmanager::$_data['offset'] = $offset;

        $query = "SELECT sc.student_id, s.name, s.image, sc.created_at , c.country_name
                    FROM `#__js_learnmanager_student_enrollment` AS sc
                        INNER JOIN `#__js_learnmanager_student` AS s ON s.id = sc.student_id
                        INNER JOIN `#__js_learnmanager_user` AS u ON u.id = s.user_id
                        LEFT JOIN `#__js_learnmanager_country` AS c ON c.id = u.country_id
                            WHERE s.id = sc.student_id AND sc.course_id=" .$cid;
        $query .= " ORDER BY sc.created_at DESC";
        $query .= " limit $offset, $limit";
        $db->setQuery($query);
        $enrolledstudents = $db->loadObjectList();
        $html = "";
        if($ajax != 0){
            foreach($enrolledstudents AS $enrolledstudent){
                $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'student', 'jslmslay'=>'studentprofile', 'jslearnmanagerid'=>$enrolledstudent->student_id));
                $html .= '<div class="jslm_member_data">
                                <div class="jslm_left_side">
                                   <div class="jslm_img_wrapper">
                                        <img src="'.$enrolledstudent->image.'">
                                   </div>
                                </div>
                                <div class="jslm_right_side">';
                                    if($call_from == 1){
                                        $html .= '<span class="jslm_text1 jslm_bigfont"><a href="'.$url.'">'.$enrolledstudent->name.'</a></span>';
                                    }else{
                                        $html .= '<span class="jslm_text1 jslm_bigfont"><a>'.$enrolledstudent->name.'</a></span>';
                                    }

                $html .=    '<span class="jslm_text2">Country: '.$enrolledstudent->country_name.'</span>
                                   <span class="jslm_text2">Enrolled Date: '.date('d-M-Y', strtotime($enrolledstudent->created_at)).'</span>
                                </div>
                            </div>';
            }
            $html .= '<input type="hidden" name="total_pages" id="total_pages" value='.$total_pages.'>
                        <input type="hidden" name="pagei" id="pagei" value='.($page).'>';

        }

        if($ajax == 0){
            return $enrolledstudents;
        }else{
            return ($html);
        }
    }

    function getCourseSection($cid){ // By Course Id, for those students who are not enroll in course

        if(!is_numeric($cid)) return false;

        //db Object
        $db = new jslearnmanagerdb();

        //section data
        $query = "SELECT sec.id as section_id, sec.name as section_name, sec.access_type as section_access , COUNT(sec_l.id) as lec_count
                    FROM `#__js_learnmanager_course_section` AS sec
                        INNER JOIN `#__js_learnmanager_course` AS c ON c.id = sec.course_id
                        INNER JOIN `#__js_learnmanager_course_section_lecture` AS sec_l ON sec.id = sec_l.section_id
                        WHERE c.course_status = 1 AND c.isapprove = 1 AND sec.course_id = " .$cid;
        $query .= " GROUP BY sec.id ORDER BY sec.section_order ASC";
        // $query .= " ORDER BY sec.id ASC";
        $db->setQuery($query);
        $sections = $db->loadObjectList();
        $lecturesarray = array();
        jslearnmanager::$_data['sections'] = $sections;
        foreach ($sections as $section) {
            $lectures = JSLEARNMANAGERincluder::getJSModel('lecture')->getCourseLectures($section->section_id);
            jslearnmanager::$_data['lectures'][$section->section_id] = $lectures;
        }

        return;
    }

    function getCourseSectionforEditCourse($cid){

        if(!is_numeric($cid)) return false;

        //db Object
        $db = new jslearnmanagerdb();

        //section data
        $query = "SELECT sec.id as section_id, sec.name as section_name, sec.access_type as section_access
                    FROM `#__js_learnmanager_course_section` AS sec
                        LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sec_l ON sec.id = sec_l.section_id
                        WHERE sec.course_id =" .$cid;
        $query .= " ORDER BY sec.id ASC";
        $db->setQuery($query);
        $sections = $db->loadObjectList();
        jslearnmanager::$_data['sections'] = $sections;
        if(isset($sections) && count($sections) > 0){
            foreach ($sections as $section) {
                $lectures = JSLEARNMANAGERincluder::getJSModel('lecture')->getCourseLectures($section->section_id);
                jslearnmanager::$_data['lectures'][$section->section_id] = $lectures;
            }
        }

        return;
    }


    function isStudentEnroll($uid,$cid){ // uid and course id
        if(!is_numeric($cid)) return false;
        if(!is_numeric($uid)) return false;
        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
        if($usertype == 'Student'){
            $student = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
            $db = new jslearnmanagerdb;
            $query = "SELECT COUNT(cs.id) FROM `#__js_learnmanager_course` AS c INNER JOIN `#__js_learnmanager_student_enrollment` AS cs ON c.id = cs.course_id WHERE c.course_status = 1 AND c.isapprove = 1 AND cs.student_id =" .$student;
            $query .= " AND cs.course_id =" .$cid;
            $db->setQuery($query);
            $total = $db->loadResult();
            if($total > 0){
                return true;
            }else {
                return false;
            }
        }
        return false;
    }

    function getrecentEnrollStudent($instructorid){
        if(!is_numeric($instructorid)) return false;

        $db = new jslearnmanagerdb();

        $query = "SELECT c.id as course_id, c.title as course_title, s.name as student_name, s.image, sc.student_id,category.category_name as category
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_student_enrollment` AS sc ON c.id = sc.course_id
                        INNER JOIN `#__js_learnmanager_student` AS s ON s.id = sc.student_id
                        LEFT JOIN `#__js_learnmanager_category` AS category ON c.category_id = category.id
                        LEFT JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id
                        WHERE accesstype.status = 1 AND c.instructor_id =" .$instructorid;
        $query .= " ORDER BY sc.created_at DESC LIMIT 3";
        $db->setQuery($query);
        $recentenroll = $db->loadObjectList();
        jslearnmanager::$_data['recent_enroll'] = $recentenroll;
        return;
    }

    function approveQueueCourse( $id ){
        if (is_numeric($id) == false)
            return false;
        $row = JSLEARNMANAGERincluder::getJSTable('course');
        if (!$row->update(array('id' => $id, 'isapprove' => 1))) {
            return JSLEARNMANAGER_APPROVE_ERROR;
        }
        JSLEARNMANAGERincluder::getJSModel('emailtemplate')->sendMail(1 , 3 , $id);
        return JSLEARNMANAGER_APPROVED;
    }

    function rejectQueueCourse( $id ) {
        if (is_numeric($id) == false)
            return false;
        $row = JSLEARNMANAGERincluder::getJSTable('course');
        $canreject = $this->courseCanReject($id);
        if($canreject){
            if (!$row->update(array('id' => $id, 'isapprove' => -1, 'course_status' => 0, 'featured' => 0))) {
                return JSLEARNMANAGER_REJECT_ERROR;
            }
            JSLEARNMANAGERincluder::getJSModel('emailtemplate')->sendMail(1 , 3 , $id);
            return JSLEARNMANAGER_REJECTED;
        }
        return JSLEARNMANAGER_REJECT_ERROR;
    }

    function approveQueueAllCourse($id) {

        if (!is_numeric($id))
            return false;

        $result = $this->approveQueueCourse($id);
        $result = apply_filters("jslm_featuredcourse_approve_queue_feature_course",JSLEARNMANAGER_APPROVED,$id);

        return $result;
    }

    function rejectQueueAllCourse($id) {

        if (!is_numeric($id))
            return false;

        $result = $this->rejectQueueCourse($id);
        $result = apply_filters("jslm_featuredcourse_reject_queue_feature_course",JSLEARNMANAGER_REJECTED,$id);
        return $result;
    }

    function getIfCourseOwner($courseid,$uid) {
        if (!is_numeric($courseid))
            return false;
        if(!is_numeric($uid)) return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT cou.id
                    FROM `#__js_learnmanager_course` AS cou
                        INNER JOIN `#__js_learnmanager_instructor` AS i ON i.id = cou.instructor_id
                        INNER JOIN `#__js_learnmanager_user` AS lu ON lu.id = i.user_id
                        WHERE lu.id = " . $uid . "
                        AND cou.id =" . $courseid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function deleteShortlistCourse($ids){
        if (empty($ids))
            return false;

        $db = new jslearnmanagerdb;
        $row = JSLEARNMANAGERincluder::getJSTable('courseshortlist');

        $notdeleted = 0;
        foreach ($ids as $id) {
            if(is_numeric($id)){
                $this->getCourseInfoForMailById($id);
                if (!$row->delete($id)) {
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

    function validateFormData(&$data,$id) {
        $canupdate = false;
        $db = new jslearnmanagerdb;
        if ($data['id'] == '') {
            $result = $this->isAlreadyExist($data,$id);
            if ($result == true) {
                return JSLEARNMANAGER_ALREADY_EXIST;
            } else {
                $canupdate = true;
            }
        } else{
            $canupdate = true;
        }
        return $canupdate;
    }

    function captchaValidate() {
        if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
            global $lms_options;
            $gresponse = sanitize_key($_POST['g-recaptcha-response']);
            $resp = googleRecaptchaHTTPPost($lms_options['lms_recaptcha_private_key'] , $gresponse);
            if ($resp) {
                return true;
            } else {
                jslearnmanager::$_data['google_captchaerror'] = __("Invalid captcha","learn-manager");
                return false;
            }
        }
        return true;
    }


    function storeCourse($data) {
        if (empty($data)) return false;
        if($data['title'] == "" && $data['title'] == null) return JSLEARNMANAGER_SAVE_ERROR;
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        if(!isset($data['instructor'])){
            $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
            $data['instructor_id'] = $instructorid;
        }else{
            $data['instructor_id'] = $data['instructor'];
        }

        if(isset($data['id']) && $data['id'] != '' && !is_admin()){
            if(!$this->getIfCourseOwner($data['id'],$uid)){
                return NOTANOWNER;
            }
        }

        $config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('course');
        if ($data['id'] == '') {
            $data['featured'] = 2;
            if(isset($data['price']) && $data['price'] > 0){
                if(!isset($data['paymentplan_id'])){
                    $data['paymentplan_id'] = apply_filters("jslm_paymentplan_get_auto_asign_paymentplan_id",0);
                }
            }
            $data['created_at'] = date_i18n('Y-m-d H:i:s');
            $data['updated_at'] = date_i18n('Y-m-d H:i:s');
            if(is_admin()){
                $data['isapprove'] = 1;
            }else{
                $instructor_approve = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorApprovalStatus($data['instructor_id']);
                if($instructor_approve == 1){
                    $data['isapprove'] = $config_array['course_auto_approve'];
                    if($data['isapprove'] != 1){
                        $msg = JSLEARNMANAGERmessages::getMessage(JSLEARNMANAGER_PENDING, 'course');
                        JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->getMessagekey());
                    }
                }else{
                    $data['isapprove'] = 0;
                    $msg = JSLEARNMANAGERmessages::getMessage(JSLEARNMANAGER_COURSE_APPROVAL_PENDING, 'course');
                    JSLEARNMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->getMessagekey());
                }
            }
            if(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allow_add_paidcourse') == 0){
                $data['access_type'] = JSLEARNMANAGERincluder::getJSModel('accesstype')->getaccesstypeIdByName('Free');
            }
        }elseif(is_numeric($data['id'])){
            if(isset($data['price']) && $data['price'] > 0){
                if($data['paymentplan_id'] == 0){
                    $data['paymentplan_id'] = apply_filters("jslm_paymentplan_get_auto_asign_paymentplan_id",0);
                }else{
                    $data['paymentplan_id'] = apply_filters( "jslm_paymentplan_get_course_payment_plan_id", 0 , $data['id']);
                }
            }
        }
        if(in_array('paidcourse', jslearnmanager::$_active_addons)){
            if(!isset($data['isdiscount'])){
                $data['isdiscount'] = 0;
                $data['discounttype'] = 0;
                $data['discount_price'] = 0;
            }
        }
        if(isset($data['id'])){
            $data['updated_at'] = date_i18n('Y-m-d H:i:s');
        }
        if(isset($data['start_date'])){
            $data['start_date'] = date_i18n('Y-m-d H:i:s', strtotime($data['start_date']));
        }
        if(isset($data['expire_date'])){
            $data['expire_date'] = date_i18n('Y-m-d H:i:s', strtotime($data['expire_date']));
        }
        if (isset($data['course_logo_deleted'])) {
            $data['logoisfile'] = '';
            $data['logofilename'] = '';
        }

        if(!isset($data['access_type'])){
            $data['access_type'] = JSLEARNMANAGERincluder::getJSModel('accesstype')->getaccesstypeIdByName("Free");
        }

        $data = filter_var_array($data, FILTER_SANITIZE_STRING);

        $data['description'] = sanitize_textarea_field(wptexturize(stripslashes($_POST['description'])));
        $data['learningoutcomes'] = sanitize_textarea_field(wptexturize(stripslashes($_POST['learningoutcomes'])));
        $data['title'] = ucwords($data['title']);
        //custom field code start
        $customflagforadd = false;
        $customflagfordelete = false;
        $custom_field_namesforadd = array();
        $custom_field_namesfordelete = array();
        $userfield = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
        $params = array();
        foreach ($userfield AS $ufobj) {
            $vardata = '';
            if($ufobj->userfieldtype == 'file'){
                if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1']== 0){
                    $vardata = $data[$ufobj->field.'_2'];
                }
                $customflagforadd=true;
                $custom_field_namesforadd[]=$ufobj->field;
            }else{
                $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            }
            if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 1){
                $customflagfordelete = true;
                $custom_field_namesfordelete[]= $data[$ufobj->field.'_2'];
                }
            if($vardata != ''){
                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = htmlspecialchars($vardata);
            }
        }
        if($data['id'] != ''){
            if(is_numeric($data['id'])){
                $db = new jslearnmanagerdb();
                $query = "SELECT params FROM `#__js_learnmanager_course` WHERE id = " . $data['id'];
                $db->setQuery($query);
                $oParams = $db->loadResult();
                if(!empty($oParams)){
                    $oParams = json_decode($oParams,true);
                    $unpublihsedFields = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getUserUnpublishFieldsfor(1);
                    foreach($unpublihsedFields AS $field){
                        if(isset($oParams[$field->field])){
                            $params[$field->field] = $oParams[$field->field];
                        }
                    }
                }
            }

            if((isset($_FILES['logo']['size']) && $_FILES['logo']['size'] > 0) || (isset($data['jslms_course_image_del']) && $data['jslms_course_image_del'] == 1)){
                $this->deleteCourseImage($data['id']);
            }
        }
        //custom field code end

        $params = json_encode($params);
        $data['params'] = $params;
        $row = JSLEARNMANAGERincluder::getJSTable('course');
        if($data['id'] == ''){
            $data['alias'] =  JSLEARNMANAGERincluder::getJSModel('common')->removeSpecialCharacter($data['title']);
        }
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        $courseid = $row->id;
        //upload
        //removing custom field attachments
        if($customflagfordelete == true){
            foreach ($custom_field_namesfordelete as $key) {
                $res = $this->removeFileCustom($courseid,$key,0);
            }
        }

        //storing custom field attachments
        if($customflagforadd == true){
            foreach ($custom_field_namesforadd as $key) {
                if ($_FILES[$key]['size'] > 0) { // file
                    $res = $this->uploadFileCustom($courseid,$key,0);
                }
            }
        }

        $res[] = '';
        if ($_FILES['logo']['size'] > 0) {  // logo
            $file = array(
                    'name'     => sanitize_file_name($_FILES['logo']['name']),
                    'type'     => filter_var($_FILES['logo']['type'], FILTER_SANITIZE_STRING),
                    'tmp_name' => filter_var($_FILES['logo']['tmp_name'], FILTER_SANITIZE_STRING),
                    'error'    => filter_var($_FILES['logo']['error'], FILTER_SANITIZE_STRING),
                    'size'     => filter_var($_FILES['logo']['size'], FILTER_SANITIZE_STRING)
                    );
            $res = JSLEARNMANAGERincluder::getObjectClass('uploads')->learnManagerUpload($courseid,0,$file,0); // if parent id is zero or none than second parameter will be zero
            if ($res[0] == 6){
                $msg = JSLEARNMANAGERMessages::getMessage(JSLEARNMANAGER_FILE_TYPE_ERROR, '');
                JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
            }
            if($res[0] == 5){
                $msg = JSLEARNMANAGERMessages::getMessage(JSLEARNMANAGER_FILE_SIZE_ERROR, '');
                JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
            }
            if($res[0] != 6 && $res[0] != 5){
                $path = $res[2];
                $file_name = $res[0];
                $wpdir = wp_upload_dir();
                $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                $urlpath = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$courseid ;
                $this->createThumbnail($file_name,350,250,$res[4],$urlpath,1);
            }
        }

        if ($data['id'] == '') {
            JSLEARNMANAGERincluder::getJSModel('emailtemplate')->sendMail(1 , 1 , $courseid);
        }

        return array('courseid' => $courseid, 'msg' => JSLEARNMANAGER_SAVED);
    }

    function deletecourseimageAjax(){
        $id = JSLEARNMANAGERrequest::getVar('courseid');
        if(!is_numeric($id))
            return false;
        $isdelete = $this->deleteCourseImage($id , 1);
        return $isdelete;
    }

    function deleteCourseImage($cid , $ajaxcall = 0){
        if(! is_numeric($cid))
            return false;
        $db = new jslearnmanagerdb();
        // select photo so that custom uploaded files are not delted
        $query = "SELECT logofilename FROM `#__js_learnmanager_course` AS i WHERE id = ".$cid;
        $db->setQuery($query);
        $photo = $db->loadResult();
        // path to file so that it can be removed
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$cid;
        $files = glob( $path . '/*');
        $filename = basename($photo);
        if($filename != "" && $filename != null){
            $explodeimage = explode(".", $filename); // For removing  resizing image

            $explodeimage = $explodeimage[0].'_1.'.$explodeimage[1];
            foreach($files as $file){
                if(is_file($file) && strstr($file, $filename) ) {
                    unlink($file);
                }
                if(is_file($file) && strstr($file, $explodeimage)){
                    unlink($file);
                }
            }

            $query = "UPDATE `#__js_learnmanager_course` SET logofilename = '' , file = '' WHERE id = ".$cid;
            $db->setQuery($query);
            if($ajaxcall == 1){
                if($db->query()){
                    return true;
                }
            }else{
                $db->query();
            }
        }
        return;
    }

    // Custom Field File code
    function uploadFileCustom($id,$field,$layout){
        if($layout == 0){ // For upload course custom files
            $result = JSLEARNMANAGERincluder::getObjectClass('uploads')->storeCustomUploadFile($id,0,$field,$layout);
        }

        return $result;
    }

    function storeUploadFieldValueInCourseParams($courseid,$filename,$field){
        if( ! is_numeric($courseid))
            return;
        $db = new jslearnmanagerdb();
        $query = "SELECT params FROM `#__js_learnmanager_course` WHERE id = ".$courseid;
        $db->setQuery($query);
        $params = $db->loadResult();
        if(!empty($params)){
            $decoded_params = json_decode($params,true);
        }else{
            $decoded_params = array();
        }
        $decoded_params[$field] = $filename;
        $encoded_params = json_encode($decoded_params);
        $query = "UPDATE `#__js_learnmanager_course` SET params = '" . $encoded_params . "' WHERE id = " . $courseid;
        $db->setQuery($query);
        if($db->query()){
            return JSLEARNMANAGER_SAVED;
        }else{
            return JSLEARNMANAGER_SAVE_ERROR;
        }
    }

    function storeSection(){
        $data = array();
        $array = array();
        $course_id = JSLEARNMANAGERrequest::getVar('course_id');
        $isadmin = JSLEARNMANAGERrequest::getVar('redirect_id');
        if(!is_numeric($course_id)){
            return false;
        }else{
            $data['course_id'] = $course_id;
        }

        $data['id'] = JSLEARNMANAGERrequest::getVar('section_id');
        $data['name'] = JSLEARNMANAGERrequest::getVar('name');
        $db = new jslearnmanagerdb;
        if($data['id'] == ""){
            $query = "SELECT MAX(section_order) FROM `#__js_learnmanager_course_section` WHERE course_id = ". $course_id ."";
            $db->setQuery($query);
            $maxorder = $db->loadResult();
            $data['section_order'] = $maxorder + 1;
            $flag = 1;
        }else{
            $flag = 0;
        }
        $canupdate = $this->validateFormData($data,2);
        if ($canupdate === JSLEARNMANAGER_ALREADY_EXIST){
            $html = "<div class='alert alert-danger' id='section-message'>
                       <button type='button' data-dismiss='alert' class='close'>×</button>
                       Course Section already exists!
                    </div>";
            $html = htmlentities($html);
            return json_encode(array('flag'=>$flag, 'msg'=>'exists', 'html' => $html));
        }
        $row = JSLEARNMANAGERincluder::getJSTable('coursesection');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if($data['id'] == ''){
            $data['created_at'] = date_i18n('Y-m-d');
            $data['updated_at'] = date_i18n('Y-m-d H:i:s');
            $data['alias'] = JSLEARNMANAGERincluder::getJSModel('common')->removeSpecialCharacter($data['name']);
        }
        if(isset($data['id']))
            $data['updated_at'] = date_i18n('Y-m-d H:i:s');

        if (!$row->bind($data)) {
            $html = "<div class='alert alert-danger' id='section-message'>
                       <button type='button' data-dismiss='alert'class='close'>×</button>
                       Course Section has not saved successfully!
                    </div>";
            $html = htmlentities($html);
            return json_encode(array('flag'=>$flag, 'msg'=>'error', 'html' => $html));
        }
        if (!$row->store()) {
            $html = "<div class='alert alert-danger' id='section-message'>
                       <button type='button' data-dismiss='alert' class='close'>×</button>
                       Course Section has not saved successfully!
                    </div>";
            $html = htmlentities($html);
            return json_encode(array('flag'=>$flag, 'msg'=>'error', 'html' => $html));
        }
        $sectionid = $row->id;
        $data['id'] = $sectionid;
        $sectionname = $data["name"];
        $newlectureurl = "";
        $deleteurl = "";
        if($isadmin == 1){
            $newlectureurl = admin_url('admin.php?page=jslm_lecture&jslmslay=addlecture&jslearnmanagerid=sec_'.$data['id'].'');
            $deleteurl = wp_nonce_url(admin_url('admin.php?page=jslm_course&action=jslmstask&task=removesection&jslearnmanagerid='.$sectionid.'&jslearnmanagercid='.$course_id),'delete-section');
            $headingtag = 4;
            $nodataclass = "alert alert-danger jslm_alert_danger lms_alert_danger";
        }else{
            $newlectureurl  = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'lecture', 'jslmslay'=>'addlecture', 'jslearnmanagerid'=>'sec_'.$sectionid));
            $deleteurl = wp_nonce_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'action'=>'jslmstask', 'task'=>'removesection', 'jslearnmanagerid' => $sectionid, 'jslearnmanagercid' => $course_id  ,'jslearnmanagerpageid'=>jslearnmanager::getPageid())),'delete-section');
            $headingtag = 6;
            $nodataclass = "alert jslm_alert_danger lms_alert_danger";
        }


        if($flag == 1){
            if(jslearnmanager::$_learn_manager_theme == 1){
                $newcontent =  "<div class='lms-panel-body-wrp'>
                                    <div class='panel-heading' id='wrapper_".$sectionid."'>
                                        <h5 class='lms-section-heading lms-inline-block'>
                                            <a title='".$data["name"]."' href='#panelBodyOne_".$sectionid."' class='accordion-toggle accordion collapsed lms-section-title-link' data-toggle='collapse'>
                                              ".$data["name"]."
                                            </a>
                                        </h5>
                                        <span id='lms_span_".$sectionid."' class='lms_section_action lms-mt4'>
                                            <a title='Edit button' href='#myModalFullscreen' id='edit_".$sectionid."' onclick='getSectionFormData(".'"$sectionid"'.",".'"$sectionname"'.")' data-toggle='modal' class='lms_delete_sec'><img src='".LEARN_MANAGER_IMAGE."/section-edit.png' alt='Edit Lecture'></a>
                                            <a title='Delete' title='Delete Section' href='".$deleteurl."#curriculum' class='lms_delete_sec' onclick='return confirm(".'"Are you sure to delete this section?"'.")'><img src='".LEARN_MANAGER_IMAGE."/section-delete.png' alt='Edit Lecture'></a>
                                        </span>
                                    </div>
                                </div>
                                <a title='". __('New Lecture','learn-manager') ."' href='".$newlectureurl."' class='lms_new_section lms-new-section-top lms-border-color'><i class='fa fa-plus'></i>" . __('New Lecture','learn-manager') . "</a>
                            ";
            }else{
                $newcontent = "<div class='jslm_panel_single_group lms_panel_single_group'>
                                <div class='jslm-panel-heading lms-panel-heading panel-heading jslm_zero_padding'>
                                    <div class='panel-title jslm_panel_title lms_panel_title'>
                                        <div id='wrapper_".$sectionid."' class='jslm_edit_section_wrapper lms_edit_section_wrapper'>
                                            <div class='jslm_edit_section_left lms_edit_section_left jslm_edit_heading_width lms_edit_heading_width'>
                                            <h".$headingtag." id='h4_".$sectionid."' class='jslm_section_heading lms_section_heading'>
                                                <a href='#panelBodyOne_".$sectionid."' class='accordion-toggle accordion collapsed jslm_accordian_anchor lms_accordian_anchor jslm_section_title lms_section_title' data-toggle='collapse' data-parent='#accordion'>".$data["name"]."</a>
                                            </h".$headingtag.">
                                        </div>
                                        <div class='jslm_edit_section_right lms_edit_section_right'>
                                            <span id='jslm_span_".$sectionid."' class='jslm_section_action lms_section_action'>
                                                <a href='".$newlectureurl."' class='jslm_new_sec lms_new_sec'><i class='fa fa-plus'></i> ". __('New Lecture','learn-manager') ."</a>
                                                <a href='#myModalFullscreen' id='edit_".$sectionid."' onclick='getSectionFormData('".$sectionid."','".$sectionname."')' data-toggle='modal' class='jslm_delete_sec lms_delete_sec'><i class='fa fa-edit'></i></a>
                                                <a href='".$deleteurl."#curriculum' class='jslm_delete_sec lms_delete_sec' onclick='return confirm(".'Are you sure to delete this section?'.")'><i class='fa fa-trash'></i></a>
                                            </span>
                                         </div>
                                        </div>
                                    </div>
                                </div>
                                <div id='panelBodyOne_".$sectionid."' class='panel-collapse collapse in'>
                                    <div class='panel-body jslm_panel_style lms_panel_style'>";
                                        if($isadmin != 1){
                                            $newcontent .= "<div class='container jslm_container_style lms_container_style'>";
                                        }
                                            $newcontent .= "<div class='".$nodataclass."'>No Data Found</div>";
                                        if($isadmin != 1){
                                            $newcontent .= "</div>";
                                        }
                                    $newcontent .= "
                                    </div>
                                </div>
                            </div>
                ";
            }
        }else{
            if(jslearnmanager::$_learn_manager_theme == 1){
                $newcontent = "
                                <h5 class='lms-section-heading lms-inline-block'>
                                    <a title='".$data["name"]."' href='#panelBodyOne_".$sectionid."' class='accordion-toggle accordion collapsed lms-section-title-link' data-toggle='collapse'>
                                      ".$data["name"]."
                                    </a>
                                </h5>
                                <span id='lms_span_".$sectionid."' class='lms_section_action lms-mt4'>
                                    <a title='Edit button' href='#myModalFullscreen' id='edit_".$sectionid."' onclick='getSectionFormData(".'"$sectionid"'.",".'"$sectionname"'.")' data-toggle='modal' class='lms_delete_sec'><img src='".LEARN_MANAGER_IMAGE."/section-edit.png' alt='Edit Lecture'></a>
                                    <a title='Delete' title='Delete Section' href='".$deleteurl."#curriculum' class='lms_delete_sec' onclick='return confirm(".'"Are you sure to delete this section?"'.")'><img src='".LEARN_MANAGER_IMAGE."/section-delete.png' alt='Edit Lecture'></a>
                                </span>
                            ";

            }else{
                $newcontent = " <div class='jslm_edit_section_left lms_edit_section_left jslm_edit_heading_width lms_edit_heading_width'>
                                <h".$headingtag." class='jslm_section_heading lms_section_heading'>
                                    <a href='#panelBodyOne_".$sectionid."' class='accordion-toggle accordion collapsed jslm_accordian_anchor lms_accordian_anchor jslm_section_title lms_section_title' data-toggle='collapse' data-parent='#accordion' >".$data['name']."</a>
                                </h".$headingtag."></div>
                <div class='jslm_edit_section_right lms_edit_section_right'>
                    <span id='jslm_span_".$sectionid."' class='jslm_section_action lms_section_action'>
                        <a href='".$newlectureurl."' class='jslm_new_sec lms_new_sec'><i class='fa fa-plus'></i> ". __('New Lecture','learn-manager') ."</a>
                        <a href='#myModalFullscreen' id='edit_".$sectionid."' onclick='getSectionFormData(".'"$sectionid"'.",".'"$sectionname"'.")' data-toggle='modal' class='jslm_delete_sec lms_delete_sec'><i class='fa fa-edit'></i></a>
                        <a href='".$deleteurl."#curriculum' class='jslm_delete_sec lms_delete_sec' onclick='return confirm(".'"Are you sure to delete this section?"'.")'><i class='fa fa-trash'></i></a>
                    </span>
                </div>";
            }
        }
        $html = "<div class='alert alert-success' id='section-message'>
                   <button type='button' data-dismiss='alert' class='close'>×</button>
                    Section has been saved successfully!
                </div>";
        // str_replace('"', "'", $html);
        // str_replace('"', "'", $newcontent);
        $html = htmlentities($html);
        $newcontent = htmlentities($newcontent);
        $array = array('flag'=>$flag,'newcontent' => $newcontent, 'html' => $html);
        return json_encode($array);
    }

    function storeEnrollmentinCourse($courseid,$status=null,$uid = ''){
        if(!is_numeric($courseid)) return false;
        $data = array();
        if(!isset($data['id'])){
            $data['created_at'] = date_i18n('Y-m-d H:i:s');
            $data['updated_at'] = date_i18n('Y-m-d H:i:s');
        }
        $allowenroll = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allow_enroll');
        if($uid == ''){
            $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        }
        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
        if($usertype == 'Student'){
            $data['course_id'] = $courseid;
            $data['student_id'] = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
            if(!is_numeric($data['student_id']))
                return false;
            $isenroll = $this->isStudentEnroll($uid,$courseid);
            $getprice = apply_filters("jslm_paidcourse_get_course_price",0,$courseid);
            $access_type = JSLEARNMANAGERincluder::getJSModel('accesstype')->getCourseAccessType($courseid);
            if($allowenroll == 1){
                if(!$isenroll){
                    if($status == "Verified" || ($getprice == 0 && $access_type == "Free")){
                        $row = JSLEARNMANAGERincluder::getJSTable('studentenrollment');
                        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
                        if(!$row->bind($data)){
                            return ENROLLED_ERROR;
                        }
                        if(!$row->store()){
                            return ENROLLED_ERROR;
                        }
                        JSLEARNMANAGERincluder::getJSModel('emailtemplate')->sendMail(2 , 1 , $row->id);
                        return JSLEARNMANAGER_ENROLLED;
                    }else{
                        return ENROLLED_ERROR;
                    }
                }else{
                    return JSLEARNMANAGER_ALREADY_ENROLLED;
                }
            }else{
                return JSLEARNMANAGER_DISABLED;
            }
        }else{
            return false;
        }
        return false;
    }

    function getStudentCourseById($courseid, $uid){
        if(!is_numeric($courseid))
            return false;
        if(!is_numeric($uid))
            return false;

        $db = new jslearnmanagerdb();
        $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
        $query = "SELECT * FROM `#__js_learnmanager_student_enrollment` WHERE course_id =" .$courseid. " AND student_id=" .$studentid;
        $db->setQuery($query);
        $studentcourse = $db->loadObject();
        jslearnmanager::$_data['student_course'] = $studentcourse;
        return ;
    }

    function removeFileCustom($id,$key,$layout){ // For remove course custom files
        $filename = str_replace(' ', '_', $key);
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        if($layout == 0){ //For remove course custom files
            $path = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$id.'/' ;
        }
        $userpath = $path.$filename;
        unlink($userpath);
        return ;
    }

    function getDownloadFileByName($file_name,$id){
        if(empty($file_name)) return false;
        if(!is_numeric($id)) return false;
        $layout = JSLEARNMANAGERrequest::getVar('layout');
        $filename = str_replace(' ', '_',$file_name);
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        if($layout == 2){
            $courseid = $this->getCourseIdByLectureId($id);
            $path = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$courseid.'/lecture_'.$id. '/' ;
        }elseif($layout == 0){
            $path = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$id.'/' ;
        }else{
            $path = $wpdir['basedir'] . '/' . $data_directory . '/data/profile/profile_' .$id.'/' ;
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

    function deleteAllCourse($ids,$uid){
        if(empty($ids))
            return false;
        $total = 0;
        foreach($ids as $id){
            if(is_numeric($id)){
               $coursedelete = $this->deleteCourse($id,$uid);
               if($coursedelete != JSLEARNMANAGER_DELETED){
                    $total += 1;
               }
            }else{
                $total += 1;
            }
        }

        if ($total == 0) {
            JSLEARNMANAGERmessages::$counter = false;
            return JSLEARNMANAGER_DELETED;
        }else {
            JSLEARNMANAGERmessages::$counter = $total;
            return JSLEARNMANAGER_DELETE_ERROR;
        }
        return;
    }


    function publishUnpublish($id, $status, $instructorid) {

        if (!is_numeric($status))
            return false;
        if (!is_numeric($id))
            return false;
        if (!is_numeric($instructorid))
            return false;

        $isOwner = $this->getIfCourseOwner($id,$instructorid);

        $row = JSLEARNMANAGERincluder::getJSTable('course');
        $total = 0;
        if($isOwner){
            if ($status == 1) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'course_status' => $status))) {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            } else {

                if(is_numeric($id)){
                    if ($this->courseCanUnpublish($id)) {
                        if (!$row->update(array('id' => $id, 'course_status' => $status))) {
                            $total += 1;
                        }
                    } else {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            }
        }else{
            $total += 1;
        }
        if ($total == 0) {
            JSLEARNMANAGERmessages::$counter = false;
            if ($status == 1){
                return JSLEARNMANAGER_PUBLISHED;
            }
            else
                return JSLEARNMANAGER_UN_PUBLISHED;
        }else {
            JSLEARNMANAGERmessages::$counter = $total;
            if ($status == 1)
                return JSLEARNMANAGER_PUBLISH_ERROR;
            else
                return JSLEARNMANAGER_UN_PUBLISH_ERROR;
        }
    }

    function courseCanUnpublish($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT
                    ( SELECT COUNT(id)
                        FROM `#__js_learnmanager_student_enrollment`
                            WHERE `course_id` =" .$id. " )
                                +( SELECT COUNT(id) FROM `#__js_learnmanager_wishlist` WHERE `course_id` =" .$id. ")";
        if(in_array("paidcourse", jslearnmanager::$_active_addons)){
            $query .= "+( SELECT COUNT(id) FROM `#__js_learnmanager_paymenthistory` WHERE `course_id` =" .$id. ")";
        }

        $query .= " AS total";

        $db->setQuery($query);
        $total = $db->loadResult();
        if($total > 0){
            return false;
        }else{
            return true;
        }
    }

    function courseCanReject($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT
                    ( SELECT COUNT(id)
                        FROM `#__js_learnmanager_student_enrollment`
                            WHERE `course_id` =" .$id. " )
                                +( SELECT COUNT(id) FROM `#__js_learnmanager_wishlist` WHERE `course_id` =" .$id. ")";
        if(in_array('paidcourse', jslearnmanager::$_active_addons)){
            $query .= "+( SELECT COUNT(id) FROM `#__js_learnmanager_paymenthistory` WHERE `course_id` =" .$id. ")";
        }
        $query .= " AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if($total > 0){
            return false;
        }else{
            return true;
        }
    }

    function getCoursesForBundleCombo($cid){
        if(!is_numeric($cid))
            return false;

        $db = new jslearnmanagerdb();
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $user_id = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanageruidbyuserid($uid);
        $instructor_id = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($user_id);
        $query = " SELECT c.id as course_id, c.title as course_title
                        FROM `#__js_learnmanager_course` AS c
                            INNER JOIN `#__js_learnmanager_instructor` AS i ON i.id = c.instructor_id
                                WHERE i.id =" .$instructor_id;
        $query .= "  AND NOT EXISTS
                        (SELECT *
                            FROM `#__js_learnmanager_course_bundle` AS cb
                                WHERE c.id = cb.bundle_course_id AND cb.parent_course_id =" .$cid;
        $query .=") AND NOT EXISTS
                            (SELECT *
                                FROM `#__js_learnmanager_course_bundle` AS cb
                                    WHERE c.id = cb.parent_course_id and cb.parent_course_id = " .$cid;
        $query .= ")";
        $db->setQuery($query);
        $bundle = $db->loadObjectList();
        jslearnmanager::$data[0] = $bundle;
        return;

    }

    function getTotalSectionBycid($cid){
        if(!is_numeric($cid))  return false;

        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(sec.id) as count, c.id as course
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_course_section` AS sec ON c.id = sec.course_id
                        WHERE c.id =" .$cid;
        $db->setQuery($query);
        $totalsection = $db->loadResult();
        return $totalsection;
    }

    function courseCanDelete($id) {
        if (!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb;

        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_learnmanager_student_enrollment` WHERE `course_id` = " . $id . ")
                    + ( SELECT COUNT(id) FROM `#__js_learnmanager_wishlist` WHERE `course_id` =" .$id. ")";
        if(in_array('paidcourse', jslearnmanager::$_active_addons)){
            $query .= "+ ( SELECT COUNT(id) FROM `#__js_learnmanager_paymenthistory` WHERE course_id = " .$id. ")";
        }
        $query .= "AS total";
        $db->setQuery($query);
        $total = $db->loadResult();
        // if(is_admin()){
        //     $total = 0;
        // }
        if ($total > 0)
            return false;
        else
            return true;
    }

    function sectionCanDelete($id){
        if(!is_numeric($id))
            return false;

        $db = new jslearnmanagerdb();
        $query = "SELECT
                    (
                    SELECT COUNT(sec.id)
                        FROM `#__js_learnmanager_course_section` AS sec
                            INNER JOIN `#__js_learnmanager_course` AS c ON sec.course_id = c.id
                            INNER JOIN `#__js_learnmanager_student_enrollment` AS sc ON sc.course_id = c.id
                            WHERE sec.id =" .$id. "
                    )+
                    (
                    SELECT COUNT(sec.id)
                        FROM `#__js_learnmanager_course_section` AS sec
                            INNER JOIN `#__js_learnmanager_course` AS c ON sec.course_id = c.id
                            INNER JOIN `#__js_learnmanager_wishlist` AS w ON w.course_id = c.id
                            WHERE sec.id =" .$id. "
                    )";
        $db->setQuery($query);
        $lectures = $db->loadResult();
        if($lectures > 0)
            return false;
        else
            return true;

    }

    function getCourseForCombo() {
        $db = new jslearnmanagerdb;
        $query = "SELECT id, title AS text FROM `#__js_learnmanager_course` WHERE course_status = 1 ";
        $db->setQuery($query);
        $courses = $db->loadObjectList();
        return $courses;
    }

    function getCourseIdByLectureId($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT c.id
                    FROM `#__js_learnmanager_course_section_lecture` AS sec_l
                        INNER JOIN `#__js_learnmanager_course_section` AS sec ON sec.id = sec_l.section_id
                        INNER JOIN `#__js_learnmanager_course` AS c ON c.id = sec.course_id
                        WHERE sec_l.id =" .$id;
        $db->setQuery($query);
        $courseid = $db->loadResult();
        return $courseid;
    }

    function getCourseNameByLectureId($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT c.title
                    FROM `#__js_learnmanager_course_section_lecture` AS sec_l
                        INNER JOIN `#__js_learnmanager_course_section` AS sec ON sec.id = sec_l.section_id
                        INNER JOIN `#__js_learnmanager_course` AS c ON c.id = sec.course_id
                        WHERE sec_l.id =" .$id;
        $db->setQuery($query);
        $coursename = $db->loadResult();
        return $coursename;
    }

    function getCourseIdBySectionId($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT sec.course_id
                    FROM `#__js_learnmanager_course_section` AS sec
                        WHERE sec.id =" .$id;
        $db->setQuery($query);
        $courseid = $db->loadResult();
        return $courseid;
    }

    function getCourseInfoBySectionId($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT c.id as course_id, c.title as course_name, c.description as description, c.file as course_file, s.name as section_name, cat.category_name, i.name as instructor_name, i.image, s.id as section_id
                    FROM `#__js_learnmanager_course` AS c
                        LEFT JOIN `#__js_learnmanager_course_section` AS s ON c.id = s.course_id ";
        $query .= " LEFT JOIN `#__js_learnmanager_category` AS cat ON cat.id = c.category_id
                    LEFT JOIN `#__js_learnmanager_instructor` AS i ON i.id = c.instructor_id
                    WHERE s.id = " .$id;
        $db->setQuery($query);
        $course = $db->loadObject();
        jslearnmanager::$_data['course_info'] = $course;
        return;
    }

    function getSectionIdsbyCourseId($courseid){
        if(!is_numeric($courseid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT sec.id
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_course_section` AS sec ON c.id = sec.course_id
                        INNER JOIN `#__js_learnmanager_course_section_lecture` lec ON sec.id = lec.section_id
                        AND c.id =" .$courseid ." GROUP BY sec.id";
        $query .= " ORDER BY sec.id ASC";
        $db->setQuery($query);
        $sectionsId = $db->loadObjectList();
        foreach ($sectionsId as $value)
            $sectionsArray[] = $value->id;
        if(!empty($sectionsArray))
            return $sectionsArray;
        else return;
    }

    function getSectionIdsforLecturedetail($courseid){
        if(!is_numeric($courseid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT sec.id
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_course_section` AS sec ON c.id = sec.course_id AND c.id =" .$courseid;
        $query .= " ORDER BY sec.id ASC";
        $db->setQuery($query);
        $sectionsId = $db->loadObjectList();
        foreach ($sectionsId as $value)
            $sectionsArray[] = $value->id;
        if(!empty($sectionsArray))
            return $sectionsArray;
        else return;
    }

    function getSectionByLectureId($lectureid){
        if(!is_numeric($lectureid))
            return false;
        $db = new jslearnmanagerdb();

        $query = "SELECT section_id FROM `#__js_learnmanager_course_section_lecture` WHERE id =" .$lectureid;
        $db->setQuery($query);
        $id = $db->loadResult();
        return $id;
    }

    function deleteSectionbyId($id,$course_id){
        if(!is_numeric($id))
            return false;
        if(!is_numeric($course_id))
            return false;

        $notdeleted = 0;
        $row = JSLEARNMANAGERincluder::getJSTable('coursesection');
        if(is_admin()){
            $isOwner = 1;
        }else{
            $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
            $isOwner = $this->getIfCourseOwner($course_id,$uid);
        }
        if($isOwner == true){
            $getalllectureIds = JSLEARNMANAGERincluder::getJSModel('lecture')->getLectureIdbySectionId($id);
            if($getalllectureIds != '' &&  count($getalllectureIds) > 0){
                foreach($getalllectureIds as $lectureid){
                    $lecturedeleted = JSLEARNMANAGERincluder::getJSModel('lecture')->deleteLectureById($lectureid,$course_id);
                }
            }else{
                $lecturedeleted = JSLEARNMANAGER_DELETED;
            }
            if($lecturedeleted === JSLEARNMANAGER_DELETED){
                if (!$row->delete($id)) {
                    $notdeleted += 1;
                }
            }
            if($notdeleted == 0){
                JSLEARNMANAGERmessages::$counter = false;
                return JSLEARNMANAGER_DELETED;
            }else{
                JSLEARNMANAGERmessages::$counter = $notdeleted;
                return JSLEARNMANAGER_DELETE_ERROR;
            }
        }else{
            return JSLEARNMANAGER_DELETE_ERROR;
        }
    }

    function canSectionContainsData($id){
        if(!is_numeric($id))
            return fasle;

        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(id)
                    FROM `#__js_learnmanager_course_section_lecture`
                        WHERE section_id =" .$id;
        $db->setQuery($query);
        $section = $db->loadResult();
        if($section > 0){
            return fasle;
        }else{
            return true;
        }

    }

    function isAlreadyExist($data,$id) {
        $db = new jslearnmanagerdb;
        if($id == 1){ // For course form
            $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE title = '" . $data['title'] . "' AND instructor_id ='".$data['instructor_id']."'";
        }else if($id == 2){ // For Section form
            $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course_section` WHERE name = '" . $data['name'] . "' AND course_id ='".$data['course_id']."'";
        }
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function isCourseCodeExist($data,$datafor){

        $db = new jslearnmanagerdb();
        if($datafor == 1){
            $query = "SELECT COUNT(id)
                    FROM `#__js_learnmanager_course`
                        WHERE course_code ='".$data['course_code']."'  AND instructor_id=".$data['instructor_id'];
        }elseif($datafor == 2){
            $query = "SELECT COUNT(id)
                    FROM `#__js_learnmanager_course`
                        WHERE course_code ='".$data['course_code']."'  AND instructor_id=".$data['instructor_id']." AND id !=" .$data['id'];
        }

        $db->setQuery($query);
        $result = $db->loadResult();
        if($result > 0){
            return EXISTS;
        }else{
            return JSLEARNMANAGER_NOT_EXIST;
        }
    }

    function getSortingForListing($ordering_ajax = '' , $sorting_ajax = '') {
        $ordering = JSLEARNMANAGERrequest::getVar('cmordring');
        $sorting = JSLEARNMANAGERrequest::getVar('cmsorting');
        if($ordering_ajax != ''){
            $ordering = $ordering_ajax;
            $sorting = $sorting_ajax;
        }

        $sorting = (!$sorting) ? 1 : $sorting;
        $odering_query = '';
        switch ($ordering) {
            case '1':
            $odering_query .= " ORDER BY id ";
            break;
            case '2':
            $odering_query .= " ORDER BY price ";
            break;
            case '3':
            $odering_query .= " ORDER BY created_at ";
            break;
            case '4':
            $odering_query .= " ORDER BY access_type ";
            break;
            case '5':
            $odering_query .= " ORDER BY category ";
            break;
        }

        if($sorting == 1){
            $odering_query .= ' ASC ';
        }else{
            $odering_query .= ' DESC ';
        }
        jslearnmanager::$_data['ordering_query'] = $odering_query;
        jslearnmanager::$_data['filter']['ordering'] = jslearnmanager::$_data['ordering'] = $ordering;
        jslearnmanager::$_data['filter']['sorting'] = jslearnmanager::$_data['sorting'] = $sorting;
        return;
    }

    function getCourseforForm($id) {         //getCourseforForm
        $db = new jslearnmanagerdb;
        if ($id) {
            if (is_numeric($id) == false) return false;
            $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
            $isOwner = $this->getIfCourseOwner($id,$uid);
            if(is_admin()){
                $isOwner = true;
            }
            if($isOwner){
                $query = "SELECT cou.*, i.name as instructorname
                            FROM `#__js_learnmanager_course` AS cou
                            LEFT JOIN `#__js_learnmanager_instructor` AS i ON i.id = cou.instructor_id
                            WHERE cou.id = " . $id;
                $db->setQuery($query);
                $course = $db->loadObject();
                if (isset($course)){
                   jslearnmanager::$_data[0] = $course;
                }
            }else{
                return false;
            }
        }
        $fieldorderings = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingByFor(1);
        jslearnmanager::$_data[3] = $fieldorderings;
        return;
    }

    function sectionforform($id){
        if(!is_numeric($id))
            return false;
        $courseid = $this->getCourseIdBySectionId($id);
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $isOwner = $this->getIfCourseOwner($courseid,$uid);
        if($isOwner){
            $db = new jslearnmanagerdb();
            $query = "SELECT * FROM `#__js_learnmanager_course_section` WHERE id=" .$id;
            $db->setQuery($query);
            $section = $db->loadObject();
            jslearnmanager::$_data[0] = $section;
        }else{
            return false;
        }
        return;
    }

    function getShortListedIDArray() {
        $returnarray = array();
        if (isset($_COOKIE)) {
            if (isset($_COOKIE['wp_jslearnmanager_cookie'])) {
                $value = sanitize_key($_COOKIE['wp_jslearnmanager_cookie']);
                $array = @unserialize($value);
                if (is_array($array) && !empty($array)) {
                    foreach ($array AS $id => $courseid) {
                        $returnarray[] = $id;
                    }
                }
            }
        }
        return $returnarray;
    }

    function getDataForShortlist($courseid) {
        $db = new jslearnmanagerdb;
        if (!is_numeric($courseid))
            return false;
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $query = null;
        if ($uid == null) {
            if (!isset($_COOKIE['wp_jslearnmanager_cookie']))
                return false;
            $value = sanitize_key($_COOKIE['wp_jslearnmanager_cookie']);
            $data = @unserialize($value);
            if ($data !== false) {
                $value = unserialize($value);
            } else {
                return false;
            }
            foreach ($value AS $slid => $slcourseid) {
                if ($slcourseid == $courseid) {
                    if(!is_numeric($slid)) return false;
                    $query = "SELECT id,comments,reviews FROM `#__js_learnmanager_wishlist` WHERE id = " . $slid ;
                    break; // Donot iterate all element if one is got from the cookie
                }
            }
        } else

        if (is_numeric($uid)) {
            $query = "SELECT id,comments,rate FROM `#__js_learnmanager_wishlist` WHERE course_id = " . $courseid . " AND uid = " . $uid;
        }
        if ($query != null) {
            $db->setQuery($query);
            $result = $db->loadObject();
            return $result;
        } else {
            return false;
        }
    }

    function saveShortlistCourse() {

        $data = array();
        $data['course_id'] = JSLEARNMANAGERrequest::getVar('courseid');
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        if(!is_numeric($data['course_id'])){
            return false;
        }
        // $data['reviews'] = JSLEARNMANAGERrequest::getVar('rate');
        $data['created_at'] = date_i18n('Y-m-d H:i:s');
        $data['status'] = 1;
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $data['student_id'] = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
        $row = JSLEARNMANAGERincluder::getJSTable('courseshortlist');
        $return = JSLEARNMANAGER_SAVED;
        if (!$row->bind($data)) {
            $return = JSLEARNMANAGER_SAVE_ERROR;
        }


        if (!$row->store()) {
            $return = JSLEARNMANAGER_SAVE_ERROR;
        }
        if($uid == null){
            $this->customJSLearnManagerCookie($data['course_id'], $row->id);
        }
        $shortlistid = $row->id;
        $courseid = $data['course_id'];
        if($return != JSLEARNMANAGER_SAVE_ERROR){
            if ($call_from == 1 || $call_from == 3) {
                if(jslearnmanager::$_learn_manager_theme){
                    $html = '<a href="#" data-toggle="tooltip" data-placement="top" onclick="removeShortlist('.$shortlistid.','.$courseid.','.$call_from.')" class="lms-buynow-btn lms-shortlist-btn " title="Remove Shortlist">'.esc_html__("Un Shortlist","learn-manager").'</a>';
                }else{
                    $html = '<a href="#" data-toggle="tooltip" data-placement="top" title="Add to Shortlist" onclick="removeShortlist('.$shortlistid.','.$courseid.','.$call_from.')" class="jslm_right_actions active"><i class="fa fa-heart"></i></a>';
                }
            }elseif($call_from == 2){
                if(jslearnmanager::$_learn_manager_theme){
                    $html = '<a href="#" data-toggle="tooltip" data-placement="top" onclick="removeShortlist('.$shortlistid.','.$courseid.','.$call_from.')" class="lms-buynow-btn lms-shortlist-btn " title="Remove Shortlist">'.esc_html__("Un Shortlist","learn-manager").'</a>';
                }else{
                    $html = '<a href="#" data-toggle="tooltip" data-placement="top" title="Add to Shortlist" onclick="removeShortlist('.$shortlistid.','.$courseid.','.$call_from.')" class="jslm_edit_anchor_styling jslm_heart active"><i class="fa fa-heart"></i></a>';
                }
            }
        }
        $msg = JSLEARNMANAGERMessages::getMessage($return, 'course');
        JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'], $this->getMessagekey());
        return ($html);
    }

    function customJSLearnManagerCookie($cookievalue, $cookieindex) {
        $value = array();
        if (isset($_COOKIE['wp_jslearnmanager_cookie'])) {
            $cookie = sanitize_key($_COOKIE['wp_jslearnmanager_cookie']);
            $value = unserialize($cookie);
        }
        $value[(int) $cookieindex] = (int) $cookievalue;
        setcookie('wp_jslearnmanager_cookie', serialize($value), time() + 1209600, SITECOOKIEPATH, null, false, true);
    }

    function deleteCourse($courseid,$uid){
        if (!is_numeric($courseid))
            return false;
        if(!is_admin()){
            if (!is_numeric($uid))
                return false;
            $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT  c.instructor_id
                    FROM `#__js_learnmanager_course` AS c
                        WHERE c.id = " . $courseid ;
        $db->setQuery($query);
        $cinstructorid = $db->loadResult();
        $shc = "";
        $shortlist = "";
        $inquery = "";
        $courseowner = false;
        if(is_admin()){
            $courseowner = true;
            $shc = " ,shc,shortlist";
            $inquery = " LEFT JOIN `#__js_learnmanager_student_enrollment` AS shc ON course.id = shc.course_id
                         LEFT JOIN `#__js_learnmanager_wishlist` AS shortlist ON course.id = shortlist.course_id";
        }else{
            if($instructorid == $cinstructorid){
                $courseowner = true;
            }
        }
        $row = JSLEARNMANAGERincluder::getJSTable('course');
        $cancoursedelete = $this->courseCanDelete($courseid);
        if($cancoursedelete){
            if($courseowner){
                do_action("jslm_addon_delete_query_data_for_course","course",".course_id");
                do_action("jslm_addon_delete_query_data_for_course_paymentplan","course",".course_id");
                do_action("jslm_quiz_select_query_data_for_delete_course_quiz","lecture","lecturequiz");
                $query = "DELETE ". jslearnmanager::$_addon_query['select'] ."section,lecture,lecturefiles $shc
                            FROM `#__js_learnmanager_course` AS course
                                LEFT JOIN `#__js_learnmanager_course_section` AS section ON course.id = section.course_id
                                LEFT JOIN `#__js_learnmanager_course_section_lecture` AS lecture ON section.id = lecture.section_id
                                ". jslearnmanager::$_addon_query['join'] ."
                                LEFT JOIN `#__js_learnmanager_lecture_file` AS lecturefiles ON lecture.id = lecturefiles.lecture_id";
                $query .= $inquery;
                $query .= " WHERE course.id =" .$courseid;
                $db->setQuery($query);
                do_action("reset_jslmaddon_query");
                if($db->query()){
                    $this->getCourseInfoForMailById($courseid);
                    $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                    $wpdir = wp_upload_dir();
                    $path = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$courseid;
                    if(is_dir($path)){
                        $this->removeCourseContents($path);
                    }
                    if($row->delete($courseid)){
                        JSLEARNMANAGERincluder::getJSModel('emailtemplate')->sendMail(1 , 2 , $courseid);
                        return JSLEARNMANAGER_DELETED;
                    }else{
                        return JSLEARNMANAGER_DELETE_ERROR;
                    }
                }else{
                    return JSLEARNMANAGER_DELETE_ERROR;
                }
            }else{
                return JSLEARNMANAGER_DELETE_ERROR;
            }
        }else{
            return JSLEARNMANAGER_DELETE_ERROR;
        }
        return;
    }

    function removeCourseContents($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir"){
                        $this->removeCourseContents($dir."/".$object);
                    }else{
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    function deleteCoMembers($courseid){ // Delete All co-instructor by courseid
        if(!is_numeric($courseid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "DELETE r.* FROM `#__js_learnmanager_co_instructor` AS r WHERE r.course_id=" .$courseid;
        $db->setQuery($query);
        if($db->query()){
            return 1;
        }else{
            return 0;
        }
    }

    function deleteCoMemberbyId($ids){ // By comemeber id
        if(empty($ids))
            return false;
        $notdeleted = 0;
        $db = new jslearnmanagerdb();

        $row = JSLEARNMANAGERincluder::getJSTable('coinstructor');
        foreach ($ids as $id) {
            if(is_numeric($id))
            {
                if(!$row->delete($id)){
                    $notdeleted += 1;
                }
            }else{
                $notdeleted += 1;
            }
        }
        if($notdeleted == 0){
            JSLEARNMANAGERmessages::$counter = false;
            return JSLEARNMANAGER_DELETED;
        }else{
            JSLEARNMANAGERmessages::$counter = $notdeleted;
            return JSLEARNMANAGER_DELETE_ERROR;
        }
    }

    function removeCourseData($courseid){
        if (!is_numeric($courseid))
            return false;
        // to remove files from dire
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        if(is_dir($wpdir['basedir'] . '/' . $data_directory . "/data/course/course_".$courseid)){
            array_map('unlink', glob($wpdir['basedir'] . '/' . $data_directory . "/data/course/course_".$courseid."/*.*"));//deleting files
            rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/course/course_".$courseid);
            return JSLEARNMANAGER_DELETED;
        }else{
            return JSLEARNMANAGER_DELETE_ERROR;
        }
        return;
    }

    function deleteShortListedCourse($cid=null,$sid=null,$ajaxcall = null){
        if($cid == null){
            $cid = JSLEARNMANAGERrequest::getVar('cid');
        }
        if($sid == null){
            $sid = JSLEARNMANAGERrequest::getVar('rid');
        }
        $call_from = JSLEARNMANAGERrequest::getVar('call_from');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
        $cookie_delete = false;
        $array = $this->getShortListedIDArray();
        if(in_array($sid, $array)){
            $cookie_delete = true;
        }else{
            if (!is_numeric($uid))
                return false;
        }

        if(!is_numeric($cid)){
            return false;
        }

        $row = JSLEARNMANAGERincluder::getJSTable('courseshortlist');
        $return = JSLEARNMANAGER_DELETED;
        if($cookie_delete){

            if (!$row->delete($sid)) {
                $return = JSLEARNMANAGER_DELETE_ERROR;
            }

            //Remove from cookie if exists
            if (isset($_COOKIE)) {
                if (isset($_COOKIE['wp_jslearnmanager_cookie'])) {
                    $value = sanitize_key($_COOKIE['wp_jslearnmanager_cookie']);
                    $array = @unserialize($value);
                    $newarray = array();
                    if (is_array($array) && !empty($array)) {
                        foreach ($array AS $slid => $shcourseid) {
                            if ($sid != $slid) {
                                $newarray[$slid] = $shcourseid;
                            }
                        }
                    }
                    setcookie('wp_jslearnmanager_cookie', serialize($newarray), time() + 1209600, SITECOOKIEPATH, null, false, true);
                }
            }

            $return = JSLEARNMANAGER_DELETED;
        }else{
            $db = new jslearnmanagerdb;
            $query = "SELECT  count(scou.student_id)
                        FROM `#__js_learnmanager_wishlist` AS scou
                        WHERE scou.course_id = " . $cid. " AND scou.student_id =" .$studentid ;
            $db->setQuery($query);
            $suids = $db->loadResult();

            // to hanlde if any course has been shortlisted multiple times
            if($suids > 0){
                for($i=0; $i<$suids; $i++){
                    if (!$row->delete($sid)){
                        $return = JSLEARNMANAGER_DELETE_ERROR;
                    }else{
                        $return = JSLEARNMANAGER_DELETED;
                    }
                }
            }else{
                $return = JSLEARNMANAGER_DELETE_ERROR;
            }
        }
        $html = '';
        if($return == JSLEARNMANAGER_DELETED && $ajaxcall == null){
            if($call_from == 1 || $call_from == 3){
                if(jslearnmanager::$_learn_manager_theme){
                    $html = '<a href="#" data-toggle="tooltip" data-placement="bottom" onclick="storeCourseShortlist('.$cid.','.$call_from.')" class="lms-buynow-btn lms-shortlist-btn " title="Make Shortlist">'.esc_html__("Make Shortlist","learn-manager").'</a>';
                }else{
                    $html = '<a href="#" data-toggle="tooltip" data-placement="bottom" title="Add to Shortlist" onclick="storeCourseShortlist('.$cid.','.$call_from.')" class="jslm_right_actions"><i class="fa fa-heart-o"></i></a>';
                }
            }elseif($call_from == 2){
                if(jslearnmanager::$_learn_manager_theme){
                    $html = '<a href="#" data-toggle="tooltip" data-placement="bottom" onclick="storeCourseShortlist('.$cid.','.$call_from.')" class="lms-buynow-btn lms-shortlist-btn " title="Make Shortlist">'.esc_html__("Make Shortlist","learn-manager").'</a>';
                }else{
                    $html = '<a href="#" data-toggle="tooltip" data-placement="bottom" title="Add to Shortlist" class="jslm_edit_anchor_styling jslm_heart" onclick="storeCourseShortlist('.$cid.','.$call_from.')" ><i class="fa fa-heart-o"></i></a>';
                }
            }
        }elseif($ajaxcall == 1){
            return $return;
        }
        return ($html);
    }

    function setListStyleSession(){
        $listingstyle = JSLEARNMANAGERrequest::getVar('styleid');
        $_SESSION['jslm_listing_style'] = $listingstyle;
        return $listingstyle;
    }

    function getCourseNameById($id) {
        if(!is_numeric($id)) return '';
        $db = new jslearnmanagerdb;
        $query = "SELECT c.title AS title
            FROM `#__js_learnmanager_course` AS c
            WHERE c.id = ".$id;
        $db->setQuery($query);
        $row = $db->loadResult();
        $name = $row;
        return $name;
    }

    function getInstructorIdByCourseId($id){
        if(!is_numeric($id)) return '';

        $db = new jslearnmanagerdb;
        $query = "SELECT c.instructor_id AS instructor_id
            FROM `#__js_learnmanager_course` AS c
            WHERE c.id = ".$id;
        $db->setQuery($query);
        $row = $db->loadResult();

        $instructor_id = $row;
        return $instructor_id;
    }

    function getCourseInfoForMailById($id){
        if(!is_numeric($id))
            return '';
        $name = $this->getCourseNameById($id);

        $db = new jslearnmanagerdb;
        $query = "SELECT i.name AS instructorname, user.email
                    FROM `#__js_learnmanager_course` AS c
                        JOIN `#__js_learnmanager_instructor` AS i ON i.id = c.instructor_id
                        JOIN `#__js_learnmanager_user` AS user ON user.id = i.user_id
                        WHERE c.id = ".$id;
        $db->setQuery($query);
        $data = $db->loadObject($query);
        $_SESSION['learnmanager-email']['coursetitle'] = $name;
        if($data != ''){
            $_SESSION['learnmanager-email']['instructorname'] = $data->instructorname;
            $_SESSION['learnmanager-email']['useremail'] = $data->email;
        }else{
            $_SESSION['learnmanager-email']['instructorname'] = 'Instructor Name';
            $_SESSION['learnmanager-email']['useremail'] = 'Instructor Email';
        }
        return;
    }

    function isShorlistCourse($courseid){
        if(!is_numeric($courseid))
            return false;

        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid(); // User Id
        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid); // User Type
        $student_id = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid); // Student Id

        $db = new jslearnmanagerdb;
        if (!is_numeric($courseid))
            return false;
        $query = null;
        if ($uid == null) {
            if (!isset($_COOKIE['wp_jslearnmanager_cookie']))
                return false;
            $value = sanitize_key($_COOKIE['wp_jslearnmanager_cookie']);
            $data = @unserialize($value);
            if ($data !== false) {
                $value = unserialize($value);
            } else {
                return false;
            }
            foreach ($value AS $slid => $slcourseid) {
                if ($slcourseid == $courseid) {
                    if(!is_numeric($slid)){
                        return false;
                    }

                    $query = "SELECT COUNT(id) as count, id FROM `#__js_learnmanager_wishlist` WHERE id = " . $slid ;
                    $db->setQuery($query);
                    $result = $db->loadObject();
                    return $result;
                    break; // Donot iterate all element if one is got from the cookie
                }
            }
        }elseif (is_numeric($student_id)) {
            $query = "SELECT COUNT(id) as count , id FROM `#__js_learnmanager_wishlist` WHERE student_id =" .$student_id. " AND course_id=" .$courseid;
        }
        if ($query != null) {
            $db->setQuery($query);
            $result = $db->loadObject();
            return $result;
        } else {
            return false;
        }
    }

    function courseEnforceDelete($courseid, $uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (!is_numeric($courseid))
            return false;
        $db = new jslearnmanagerdb();
        do_action("jslm_addon_delete_query_data_for_course","course",".course_id");
        do_action("jslm_addon_delete_query_data_for_course_paymentplan","course",".course_id");
        do_action("jslm_quiz_select_query_data_for_delete_course_quiz","lecture","question");
        $query = "DELETE  course,". jslearnmanager::$_addon_query['select'] ."section,lecture,lecturefile,shc,shortlist
                    FROM `#__js_learnmanager_course` AS course
                    ". jslearnmanager::$_addon_query['join'] ."
                    LEFT JOIN `#__js_learnmanager_course_section` AS section ON course.id=section.course_id
                    LEFT JOIN `#__js_learnmanager_course_section_lecture` AS lecture ON section.id=lecture.section_id
                    LEFT JOIN `#__js_learnmanager_lecture_file` AS lecturefile ON lecture.id=lecturefile.lecture_id
                    LEFT JOIN `#__js_learnmanager_student_enrollment` AS shc ON course.id=shc.course_id
                    LEFT JOIN `#__js_learnmanager_wishlist` AS shortlist ON course.id=shortlist.course_id
                    WHERE course.id = " . $courseid;
        // code for preparing data for delete course email
        do_action('reset_jslmaddon_query');
        $resultforsendmail = JSLEARNMANAGERincluder::getJSModel('course')->geCourseInfoForEmail($courseid);
        $username = $resultforsendmail->username;
        $email = $resultforsendmail->useremail;
        $coursetitle = $resultforsendmail->title;

        $_SESSION['learnmanager-email']['coursetitle'] = $coursetitle;
        $_SESSION['learnmanager-email']['instructorname'] = $username;
        $_SESSION['learnmanager-email']['useremail'] = $email;
        $db->setQuery($query);
        if (!$db->query()) {
            return JSLEARNMANAGER_DELETE_ERROR; //error while delete course
        }

        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$courseid;
        if(is_dir($path)){
            $this->removeCourseContents($path);
        }
        JSLEARNMANAGERincluder::getJSModel('emailtemplate')->sendMail(1, 2, $courseid); // 1 for course,2 for DELETE course
        return JSLEARNMANAGER_DELETED;
    }

    function createThumbnail($filename,$width,$height,$file,$path,$crop_flag = 0,$watermark_flag = 0) {
        $handle = new jslearnmanager_upload($file);
        $parts = explode(".",$filename);
        $extension = end($parts);
        $filename = str_replace("." . $extension,"",$filename);
        if ($handle->uploaded) {
            if($crop_flag != 3){
                $handle->file_new_name_body   = $filename;
                $handle->image_resize         = true;
                $handle->image_x              = $width;
                $handle->image_y              = $height;
                $handle->image_ratio_fill     = true;
                // global $car_manager_options;
                // $handle->image_background_color = $car_manager_options['cm_slide_image_background_color'];
                if($crop_flag == 1){
                    $handle->image_ratio_crop     = true;
                    $handle->image_ratio          = true;
                }
            }else{
                $handle->file_auto_rename = false;
                $handle->file_overwrite = true;
            }
            // water mark implimentation
            /*
            if($watermark_flag == 1){
                $config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('watermark');
                if($config_array['show_water_mark'] == 1 && $config_array['water_mark_img_name'] != '' ){
                    $wpdir = wp_upload_dir();
                    $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                    $handle->image_watermark = $wpdir['basedir'] . '/' . $data_directory . '/data/'.$config_array['water_mark_img_name'];
                    switch ($config_array['water_mark_position']) {
                        case 1:
                            $x = 10;
                            $y = 10;
                        break;
                        case 2:
                            $x = 10;
                            $y = -10;
                        break;
                        case 3:
                            $x = -10;
                            $y = 10;
                        break;
                        case 4:
                            $x = -10;
                            $y = -10;
                        break;
                        default:
                            $x = 10;
                            $y = -10;
                        break;
                    }
                    $handle->image_watermark_x = $x;
                    $handle->image_watermark_y = $y;
                }
            }
            */
            $handle->process($path);
            @$handle->processed;
            // uncomment this code to check for error.
            // if ($handle->processed) {
            //     // opration successful
            // } else {
            //     echo 'error : ' . $handle->error;
            //     return false;
            // }
        }else{
            echo 'error : ' . $handle->error;
        }
    }

    function geCourseInfoForEmail($courseid) {
        if ((is_numeric($courseid) == false))
            return false;
        $db = new jslearnmanagerdb();
        $query = 'SELECT course.title AS title, instructor.name AS username
                        ,user.email AS useremail
                        FROM `#__js_learnmanager_course` AS course
                        LEFT JOIN `#__js_learnmanager_instructor` AS instructor ON instructor.id = course.instructor_id
                        LEFT JOIN `#__js_learnmanager_user` AS user ON instructor.user_id = user.id
                        WHERE course.id = '.$courseid;
        $db->setQuery($query);
        $return_value = $db->loadObject();
        return $return_value;
    }

    function getCourseNameByEnrollmentId($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT title FROM `#__js_learnmanager_course` AS c INNER JOIN `#__js_learnmanager_student_enrollment` AS se ON c.id = se.course_id WHERE se.id= " .$id;
        $db->setQuery($query);
        $courseid = $db->loadResult();
        return $courseid;
    }

    function getCourseNameByShortlistId($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb;
        $query = "SELECT c.title FROM `#__js_learnmanager_course` AS c INNER JOIN `#__js_learnmanager_wishlist` AS wishlist ON c.id  = wishlist.course_id WHERE wishlist.id = " .$id;
        $db->setQuery($query);
        return $db->loadResult();
    }

    // App Data
    function getCoursesForAppListing(){
        $limit = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_limit','get',10);
        $offset = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_offset','get',0);
        //DB Object
        $db = new jslearnmanagerdb();
        $datafor = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-datafor', 'get','latest');

        $title = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-coursetitle','get');
        $category = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-category','get');
        $access_type = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-access_type','get');
        $courselevel = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-courselevel','get');

        $inquery = '';
        jslearnmanager::$_data['filter'] = array();
        if (is_string($title)){
            jslearnmanager::$_data['filter']['coursetitle'] = $title;
            $inquery .= " AND c.title LIKE '%" . $title . "%'";
        }
        if(is_numeric($category)){
            jslearnmanager::$_data['filter']['category'] = $category;
            $inquery .= " AND c.category_id = " . $category;
        }
        if(is_numeric($access_type)){
            jslearnmanager::$_data['filter']['access_type'] = $access_type;
            $inquery .= " AND c.access_type = " . $access_type;
        }
        if(is_numeric($courselevel)){
            jslearnmanager::$_data['filter']['skilllevel'] = $courselevel;
            $inquery .= " AND c.course_level =" .$courselevel;
        }


        //pagination
        $query = "SELECT COUNT(c.id)
                    FROM `#__js_learnmanager_course` AS c WHERE c.course_status = 1 AND c.isapprove = 1 ";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        $return_data['total'] = $total;

        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data'); // need some change in reviews
        do_action('jslm_featuredcourse_select_query_data');
        do_action('jslm_paidcourse_join_select_query_data');
        //data
        $query = "SELECT c.id as courseid, c.title as coursetitle, c.course_level as courselevel, c.file as courseimage,
                        cat.category_name as coursecategory, intr.name as instructorname, intr.id as instructorid, intr.image as instructorimage, accesstype.access_type,
                        COUNT(DISTINCT sect_lec.id) as totallessons, ". jslearnmanager::$_addon_query['select'] ." COUNT(DISTINCT stdnt_c.id) as totalstudents
                        FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id
                            ". jslearnmanager::$_addon_query['join'] ."
                            WHERE c.course_status = 1 AND c.isapprove = 1";
        if($datafor == 'category'){
            $id  = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp-categoryid','get','');
            if($id != '' && is_numeric($id)){
                $query .= " AND c.category_id =" .$id;
            }else{
                return false;
            }
        }
        $query .= $inquery;
        $query .= " GROUP BY c.id";
        switch($datafor){
            case 'latest':
                $query .= " ORDER BY c.created_at DESC";
            break;
            case 'popular':
                $query .= " ORDER BY totalstudents DESC";
            break;
            case 'toprated':
                $query .= apply_filters("jslm_coursereview_inquery_data_for_app",'');
            break;
        }
        $query .= " LIMIT " . $offset . "," . $limit;
        do_action("reset_jslmaddon_query");
        $db->setQuery($query);
        $data = $db->loadObjectList();
        $courses = $data;
        foreach($courses as $course){
            if($course->courseimage == ""){
                $course->courseimage = JSLEARNMANAGER_PLUGIN_URL."includes/images/default-course-image.png";
            }
        }
        $return_data['data'] = $courses;
        $return_data['filter'] = jslearnmanager::$_data['filter'];
        $return_data['datafor'] = $datafor;
        return $return_data;
    }

    function getCoursesForAppHome(){
        $db = new jslearnmanagerdb();
        $curdate = date_i18n('Y-m-d');

        $inquery = "";
        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data'); // need some change in reviews
        do_action('jslm_featuredcourse_select_query_data');
        do_action("jslm_paidcourse_join_select_query_data");
        //data
        $query = "SELECT c.id as courseid, c.title as coursetitle, c.course_level as courselevel, c.file as courseimage,
                        cat.category_name as coursecategory, intr.name as instructorname, intr.id as instructorid, intr.image as instructorimage, accesstype.access_type,
                        COUNT(DISTINCT sect_lec.id) as totallessons, ". jslearnmanager::$_addon_query['select'] ." COUNT(DISTINCT stdnt_c.id) as totalstudents
                        FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            ".jslearnmanager::$_addon_query['join']."
                            WHERE c.course_status = 1 AND c.isapprove = 1";
        $query .= " GROUP BY c.id";
        $homedata = array();
        // Popular Courses
        $inquery = $query;
        $inquery .= " ORDER BY totalstudents DESC";
        $inquery .= " LIMIT 6";
        $db->setQuery($inquery);
        do_action("reset_jslmaddon_query");
        $data = $db->loadObjectList();
        $popularcourses = $data;

        // Top Rated Courses
        $inquery = $query;
        $inquery .= apply_filters("jslm_coursereview_inquery_data_for_app",'');
        $inquery .= " LIMIT 6";
        $db->setQuery($inquery);
        $data = $db->loadObjectList();
        $topratedcourses = $data;

        // Latest Course
        $inquery = $query;
        $inquery .= " ORDER BY c.created_at DESC";
        $inquery .= " LIMIT 6";
        $db->setQuery($inquery);
        $data = $db->loadObjectList();
        $latestcourses = $data;

        // Count Course Categorie
        $categories = $this->countCourseByCategory(1);

        // data
        $homedata['popularcourses'] = $popularcourses;
        $homedata['topratedcourses'] = $topratedcourses;
        $homedata['latestcourses'] = $latestcourses;
        $homedata['countcategories'] = $categories;
        return $homedata;
    }

    function getCourseDetailByIdForApp($id,$uid=0){
        if(!is_numeric($id))
            return false;
        $coursedata = array();
        do_action('jslm_coursereview_join_query_data');
        do_action('jslm_coursereview_select_query_data'); // need some change in reviews
        do_action('jslm_paidcourse_join_select_query_data',1);
        $db = new jslearnmanagerdb();
        $query = " SELECT c.id as course_id, c.title as title, accesstype.access_type as access_type, c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c_level.level, lang.language, c.learningoutcomes,
                    c.course_duration as duration, c.meta_description as meta_description, c.course_status, c.file as course_logo, c.video as course_video,cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id,
                    COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students, ". jslearnmanager::$_addon_query['select'] ." intr.image as instructor_image
                        FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON sect.course_id =c.id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` as sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            -- LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            LEFT JOIN `#__js_learnmanager_course_level` AS c_level ON c_level.id = c.course_level
                            LEFT JOIN `#__js_learnmanager_language` AS lang ON lang.id = c.language
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                            WHERE c.course_status = 1 AND c.isapprove = 1 AND c.id =" .$id;
        $db->setQuery($query);
        $coursedata['coursedetail'] = $db->loadObject();
        do_action("reset_jslmaddon_query");
        if(isset($coursedata['coursedetail'])){
            $enrolledstudents = $this->getAllEnrolledStudentinCourse($id);
            $coursedata['enrolledstudents'] = $enrolledstudents;

            $totalquiz = apply_filters("jslm_quiz_get_course_total_quiz",0,$id);
            $coursedata['totalQuiz'] = $totalquiz;

            $coursedata['coursereviews'] = apply_filters("jslm_coursereview_get_coursereviews_by_courseid",0,$id);

            $coursedata['countreviews'] = apply_filters("jslm_coursereview_count_reviews_for_app",0,$id);

            $coursesections = $this->getCourseSectionForApp($id);
            $coursedata['coursesections'] = $coursesections;
            if($uid != 0){
                $isstudentenroll = $this->isStudentEnroll($uid,$id);
                $coursedata['isenroll'] = $isstudentenroll;
                if($isstudentenroll){
                    $coursedata['student_course'] = $this->getStudentCourseByIdForApp($id,$uid);
                    $coursedata['student_course']['coursedata']->lecture_completion_params = json_decode($coursedata['student_course']['coursedata']->lecture_completion_params);
                    $coursedata['student_course']['coursedata']->quiz_result_params = apply_filter("jslm_quiz_result_params_for_coursedetail_app",'',$coursedata);
                }
            }

        }
        return $coursedata;
    }

    function getStudentCourseByIdForApp($courseid, $uid){
        if(!is_numeric($courseid))
            return false;
        if(!is_numeric($uid))
            return false;
        $studentcourse = array();
        $db = new jslearnmanagerdb();
        $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
        $query = "SELECT * FROM `#__js_learnmanager_student_enrollment` WHERE course_id =" .$courseid. " AND student_id=" .$studentid;
        $db->setQuery($query);
        $studentcourse['coursedata'] = $db->loadObject();
        return $studentcourse;
    }

    function getDataForStudentForApp() {

        $limit = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_limit','post',10);
        $offset = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_offset','post',0);
        $slids = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_slids','get','');
        $uid = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_uid','get','0');
        $studentid = 0;
        if($uid > 0){
            $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
        }
        $status = 1;

        $db = new jslearnmanagerdb;
        if ($slids == '' && !is_numeric($uid)){
            $status = 0;
            $return_data['data'] = array();
        }
        $innerjoin = "";
        $query = null;
        $subquery = '';
        $paramquery = "";
        if($status == 1){
            if ($studentid != 0 ) {
                $innerjoin = ' INNER JOIN `#__js_learnmanager_wishlist` AS w ON c.id = w.course_id';
                $subquery .= " AND ( w.student_id = $studentid ";
                $paramquery = ", w.id as shortlist_id";
            }

            if (!empty($slids)) {
                if ($uid == 0 ) {
                    $subquery .= ' AND ';
                }
                if(strstr($slids,",")){
                    $subquery .= ' c.id IN(' . $slids. ')';
                }elseif(is_numeric($slids)){
                    $subquery .= ' c.id = '.$slids;
                }
            }
            if ( $studentid != 0 ) {
                $subquery .= ' ) ';
            }
            if($subquery != ""){
                do_action('jslm_featuredcourse_select_query_data');
                do_action('jslm_paidcourse_join_select_query_data');
                $query = "SELECT c.id as course_id, c.title as title, accesstype.access_type as access_type,c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c_level.level as level, c.file as courseimage,".jslearnmanager::$_addon_query['select']."
                        cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, intr.image as instructor_image, c.params,
                        COUNT(DISTINCT sect_lec.id) as total_lessons, COUNT(DISTINCT stdnt_c.id) as total_students";
                $query .= $paramquery;
                $query .= " FROM `#__js_learnmanager_course` AS c ";
                $query .= $innerjoin;
                $query .= " INNER JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ". jslearnmanager::$_addon_query['join'] ."
                            LEFT JOIN `#__js_learnmanager_course_level` AS c_level ON c_level.id = c.course_level
                            JOIN `#__js_learnmanager_course_access_type` AS accesstype ON c.access_type = accesstype.id AND accesstype.status = 1
                            WHERE 1 = 1 ";
                $query .= $subquery;
                $query .= " GROUP BY c.id ";
                if($studentid != 0){
                    $query .= " ,w.id";
                    $query .= " ORDER BY w.created_at DESC";
                }
                $query .=" LIMIT " . $offset . "," . $limit;
                do_action("reset_jslmaddon_query");
                $db->setQuery($query);
                $data = $db->loadObjectList();
                foreach($data as $course){
                        $course->params = json_decode($course->params);
                    if($course->courseimage == ""){
                        $data->courseimage = JSLEARNMANAGER_PLUGIN_URL."includes/images/default-course-image.png";
                    }else{
                        continue;
                    }
                }
                $return_data['data'] = $data;
            }
        }

        $return_data['status'] = $status;

        return $return_data;
    }

    function storeShortlistCourseForApp(){

        $status = 1;
        $data = array();
        $db = new jslearnmanagerdb();
        $data['course_id'] = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_courseid','get','');
        $data['created_at'] = date_i18n('Y-m-d H:i:s');
        $data['uid'] = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_uid','get',0);
        $data['student_id'] = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($data['uid']);
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_wishlist` WHERE course_id =".$data['course_id']." AND student_id=" .$data['student_id'];
        $db->setQuery($query);
        if($db->loadResult() > 0){
            $status = 0;
        }else{
            $status = 1;
        }
        if(!is_numeric($data['course_id'])) {
            $status = 0;
        }
        $row = JSLEARNMANAGERincluder::getJSTable('courseshortlist');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if($status == 1){
            $result = array();
            if (!$row->bind($data)) {
                $status = 0;
            }
            if (!$row->store()) {
                $status = 0;
            }
        }
        if ($status == 1) {
            $msg =  __("Course has been Shortlisted", "learn-manager") ;
        } else {
            $msg =  __("Failed while performing this action", "learn-manager") ;
        }
        $return_data['status']= $status;
        $return_data['message']= $msg;
        $return_data['slid']= $row->id;
        return $return_data;
    }

    function getCourseSectionForApp($cid){ // By Course Id, for those students who are not enroll in course

        if(!is_numeric($cid)) return false;

        //db Object
        $db = new jslearnmanagerdb();

        //section data
        $query = "SELECT sec.id as section_id, sec.name as section_name, sec.access_type as section_access , COUNT(sec_l.id) as lec_count
                    FROM `#__js_learnmanager_course_section` AS sec
                        INNER JOIN `#__js_learnmanager_course` AS c ON c.id = sec.course_id
                        INNER JOIN `#__js_learnmanager_course_section_lecture` AS sec_l ON sec.id = sec_l.section_id
                        INNER JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type AND accesstype.status = 1
                        WHERE c.course_status = 1 AND c.isapprove = 1 AND sec.course_id =" .$cid;
        $query .= " ORDER BY sec.id ASC";
        $db->setQuery($query);
        $sections = $db->loadObjectList();
        $lecturesarray = array();
        foreach ($sections as $section) {
            $lectures = JSLEARNMANAGERincluder::getJSModel('lecture')->getCourseLecturesForApp($section->section_id);

            $lecturesarray[$section->section_id] = $lectures;
        }
        return array("sections" => $sections, "lectures" => $lecturesarray);
    }

    function deleteShortlistedCourseByIdForApp() {
        $id = JSLEARNMANAGERrequest::getVar('jslearnmanagerapp_slid','get','');
        if (!is_numeric($id)){
            $return_data['status'] = 0;
            return $return_data;
        }
        $row = JSLEARNMANAGERincluder::getJSTable('courseshortlist');
        if ($row->delete($id)) {
            $return_data['status'] = 1;
            return $return_data;
        } else {
            $return_data['status'] = 0;
            return $return_data;
        }
    }

    function isInstructorHasAStudent($studentuid){
        if(!is_numeric($studentuid))
            return false;
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
        $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($studentuid);
        if(!is_numeric($studentid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(enrollment.id) FROM `#__js_learnmanager_student_enrollment` AS enrollment
                    INNER JOIN `#__js_learnmanager_course` AS course ON course.id = enrollment.course_id AND course.instructor_id = " .$instructorid."
                    WHERE enrollment.student_id=" .$studentid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result > 0){
            return true;
        }else{
            return false;
        }
    }

    function getMessagekey(){
        $key = 'course';
        if(is_admin())
            {
                $key = 'admin_'.$key;
            }
            return $key;
    }

    function getAdminCourseSearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $title = ($isadmin) ? 'title' : 'jslm_course';
        $jslms_search_array['title'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($title)));
        $jslms_search_array['category'] = trim(JSLEARNMANAGERrequest::getVar('category' , ''));
        $jslms_search_array['sorton'] = trim(JSLEARNMANAGERrequest::getVar('sorton' , '',3));
        $jslms_search_array['sortby'] = trim(JSLEARNMANAGERrequest::getVar('sortby' , '',2));
        $jslms_search_array['language']  = trim(JSLEARNMANAGERrequest::getVar('language' , ''));
        $jslms_search_array['skilllevel'] = trim(JSLEARNMANAGERrequest::getVar('skilllevel' , ''));
        $jslms_search_array['access_type'] = trim(JSLEARNMANAGERrequest::getVar('access_type' , ''));
        $jslms_search_array['isapprove'] = trim(JSLEARNMANAGERrequest::getVar('isapprove' , ''));
        $jslms_search_array['status'] = trim(JSLEARNMANAGERrequest::getVar('status' , ''));
        $jslms_search_array['isgfcombo'] = trim(JSLEARNMANAGERrequest::getVar('isgfcombo' , ''));
        $jslms_search_array['courselevel'] = trim(JSLEARNMANAGERrequest::getVar('courselevel' , ''));
        $jslms_search_array['coursetitle'] = trim(JSLEARNMANAGERrequest::getVar('coursetitle' , ''));
        $jslms_search_array['category'] = trim(JSLEARNMANAGERrequest::getVar('category' , ''));
        $jslms_search_array['instructorname'] = trim(JSLEARNMANAGERrequest::getVar('instructorname' , ''));
        $jslms_search_array['category'] = trim(JSLEARNMANAGERrequest::getVar('category' , ''));
        $jslms_search_array['search_from_course'] = 1;
        return $jslms_search_array;
    
    }

}
?>
