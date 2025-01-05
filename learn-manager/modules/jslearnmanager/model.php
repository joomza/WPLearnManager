<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERjslearnmanagerModel {
    var $nodata;

    function getAdminControlPanelData() {

        $db = new jslearnmanagerdb();

        // Data for the control panel graph
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course`";
        $db->setQuery($query);
        jslearnmanager::$_data['totalcourses'] = $db->loadResult();
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE course_status = 1";
        $db->setQuery($query);
        jslearnmanager::$_data['publishcourses'] = $db->loadResult();
        $query = "SELECT COUNT(i.id) FROM `#__js_learnmanager_instructor` AS i
                    INNER JOIN `#__js_learnmanager_user` AS u ON u.id = i.user_id
                    WHERE u.status = 1";
        $db->setQuery($query);
        jslearnmanager::$_data['totalinstructors'] = $db->loadResult();
        $query = "SELECT COUNT(s.id) FROM `#__js_learnmanager_student` AS s
                    INNER JOIN `#__js_learnmanager_user` AS u ON u.id = s.user_id
                    WHERE u.status = 1";
        $db->setQuery($query);
        jslearnmanager::$_data['totalstudents'] = $db->loadResult();

        // Graph
        $curdate = date('Y-m-d');
        $fromdate = date('Y-m-d', strtotime("now -6 days"));
        jslearnmanager::$_data['curdate'] = $curdate;
        jslearnmanager::$_data['fromdate'] = $fromdate;

        // New Courses
        $query = "SELECT c.created_at
            FROM `#__js_learnmanager_course` AS c WHERE date(c.created_at) >= '" . $fromdate . "' AND date(c.created_at) <= '" . $curdate . "' ORDER BY c.created_at";
        $db->setQuery($query);
        $allcourses = $db->loadObjectList();
        jslearnmanager::$_data['allcourses'] = $allcourses;
        jslearnmanager::$_data['allcourses_s']=count($allcourses);
        $totalcourses = array();
        foreach ($allcourses AS $obj) {
            $date = date('Y-m-d', strtotime($obj->created_at));
            $totalcourses[$date] = isset($totalcourses[$date]) ? ($totalcourses[$date] + 1) : 1;
        }

        // New Featured

        $featured_courses = apply_filters("jslm_featuredcourse_get_cpaneldata_for_feature",0,$curdate);
        jslearnmanager::$_data['featured_courses'] = $featured_courses;
        if(is_array($featured_courses))
            jslearnmanager::$_data['featured_courses_s']=count($featured_courses);
        else
            jslearnmanager::$_data['featured_courses_s'] = 0;

        $featuredcourses = array();

        if($featured_courses != 0){
            foreach ($featured_courses AS $obj) {
                $date = date('Y-m-d', strtotime($obj->created_at));
                $featuredcourses[$date] = isset($featuredcourses[$date]) ? ($featuredcourses[$date] + 1) : 1;
            }
        }

        // New Instructors
        $query = "SELECT i.created_at
            FROM `#__js_learnmanager_instructor` AS i WHERE date(i.created_at) >= '" . $fromdate . "' AND date(i.created_at) <= '" . $curdate . "' ORDER BY i.created_at";
        $db->setQuery($query);
        $allinstructors = $db->loadObjectList();
        jslearnmanager::$_data['all_instructors'] = $allinstructors;
        jslearnmanager::$_data['all_instructors_s']=count($allinstructors);
        $totalinstructors = array();
        foreach ($allinstructors AS $obj) {
            $date = date('Y-m-d', strtotime($obj->created_at));
            $totalinstructors[$date] = isset($totalinstructors[$date]) ? ($totalinstructors[$date] + 1) : 1;
        }

        // New Students
        $query = "SELECT s.created_at
            FROM `#__js_learnmanager_student` AS s WHERE date(s.created_at) >= '" . $fromdate . "' AND date(s.created_at) <= '" . $curdate . "' ORDER BY s.created_at";
        $db->setQuery($query);
        $allstudents = $db->loadObjectList();
        jslearnmanager::$_data['allstudents'] = $allstudents;
        jslearnmanager::$_data['allstudents_s']=count($allstudents);

        $totalstudents = array();
        foreach ($allstudents AS $obj) {
            $date = date('Y-m-d', strtotime($obj->created_at));
            $totalstudents[$date] = isset($totalstudents[$date]) ? ($totalstudents[$date] + 1) : 1;
        }

        jslearnmanager::$_data['stack_chart_horizontal']['title'] = "['" . __('Dates', 'learn-manager') . "','" . __('New courses', 'learn-manager') . "','" . __('Featured courses', 'learn-manager') . "','" . __('New instructors', 'learn-manager') . "','" . __('New students', 'learn-manager') . "']";
        jslearnmanager::$_data['stack_chart_horizontal']['data'] = '';
        for ($i = 6; $i >= 0; $i--) {
            $checkdate = date('Y-m-d', strtotime($curdate . " -$i days"));
            if ($i != 6) {
                jslearnmanager::$_data['stack_chart_horizontal']['data'] .= ',';
            }
            jslearnmanager::$_data['stack_chart_horizontal']['data'] .= "['" . date_i18n('d-M', strtotime($checkdate)) . "',";
            $all = isset($totalcourses[$checkdate]) ? $totalcourses[$checkdate] : 0;
            $allfeaturedcourses = isset($featuredcourses[$checkdate]) ? $featuredcourses[$checkdate] : 0;
            $all_instructors = isset($totalinstructors[$checkdate]) ? $totalinstructors[$checkdate] : 0;
            $all_students = isset($totalstudents[$checkdate]) ? $totalstudents[$checkdate] : 0;
            jslearnmanager::$_data['stack_chart_horizontal']['data'] .= "$all,$allfeaturedcourses,$all_instructors,$all_students]";
        }

        // newest courses
        $query = "SELECT c.id as course_id, c.title as title,cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id, c.course_status, c.created_at as created_date, c.file as file
                    FROM `#__js_learnmanager_course` AS c
                        LEFT JOIN `#__js_learnmanager_category` AS cat ON cat.id = c.category_id
                        LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                        ORDER by c.created_at DESC LIMIT 7";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        jslearnmanager::$_data['newest'] = $result;

        // newest enrolled students
        $query = "SELECT c.id as course_id, c.title as title,cat.category_name as category, s.name as student_name,s.image as image, s.id as student_id,u.status as student_status, sc.created_at as enrolled_date
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_student_enrollment` AS sc ON c.id = sc.course_id
                        INNER JOIN `#__js_learnmanager_student` AS s ON s.id = sc.student_id
                        INNER JOIN `#__js_learnmanager_user` AS u ON u.id = s.user_id
                        INNER JOIN `#__js_learnmanager_category` AS cat ON cat.id = c.category_id
                        ORDER BY sc.created_at DESC LIMIT 7";

        $db->setQuery($query);
        $result = $db->loadObjectList();
        jslearnmanager::$_data['enrolled'] = $result;

        return;
    }

    function getConfigValue($configname){
        $db = new jslearnmanagerdb();
        $query = "SELECT c.configvalue as configvalue FROM `#__js_learnmanager_config` as c WHERE configname = '".$configname."'";
        $db->setQuery($query);
        $configvalue = $db->loadObjectList();
        jslearnmanager::$_db['version']=$configvalue;
        return ;
    }

    function getRecentEnrollStudentsforcp(){

        do_action("jslm_coursereview_join_query_data");
        do_action("jslm_coursereview_select_query_data");
        do_action("jslm_featuredcourse_select_query_data");
        do_action("jslm_paidcourse_course_price_select_query_data");
        $db = new jslearnmanagerdb();
        $query = "SELECT c.id as course_id, c.title as title, c.access_type as access_type, c.course_code as course_code, c.subtitle as subtitle, c.description as c_description, c.course_level as level,
                         cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id,c.file as filename,c.start_date as start_date,c.expire_date as expire_date,c.course_status as course_status,c.created_at as created_at,
                         COUNT(DISTINCT sect_lec.id) as total_lessons, ".jslearnmanager::$_addon_query['select']." COUNT(DISTINCT stdnt_c.id) as total_students
                          FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON cat.id = c.category_id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            LEFT JOIN `#__js_learnmanager_course_section` AS sect ON c.id = sect.course_id
                            LEFT JOIN `#__js_learnmanager_course_section_lecture` AS sect_lec ON sect.id = sect_lec.section_id
                            LEFT JOIN `#__js_learnmanager_student_enrollment` AS stdnt_c ON c.id = stdnt_c.course_id
                            LEFT JOIN `#__js_learnmanager_student` AS stdnt ON stdnt.id = stdnt_c.student_id
                            ".jslearnmanager::$_addon_query['join']."
                            GROUP BY c.id
                            ORDER by stdnt_c.created_at DESC LIMIT 7";

        $db->setQuery($query);
        do_action('reset_jslmaddon_query');
        $result = $db->loadObjectList();
        jslearnmanager::$_data['recentlyenroll'] = $result;

        return;
    }

    function getTodayStatsForWidget(){
        $db = new jslearnmanagerdb();
        //Today
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE DATE(created_at) = CURDATE()";
        $db->setQuery($query);
        $data = $db->loadResult();
        $result['today_add'] = $data;
        //Today
        $result['today_featured'] = apply_filters("jslm_featuredcourse_count_active_feature_course_today_report",'');
        //Today
        $result['today_purchase'] = apply_filters( 'jslm_addon_count_today_purchase_course', '' );
        //Today
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_student_enrollment` WHERE DATE(created_at) = CURDATE()";
        $db->setQuery($query);
        $data = $db->loadResult();
        $result['today_enrolled'] = $data;

        $for = 1;
        $html = $this->makeHTMLOfWidget($result , $for);

        return $html;
    }

    function getLastWeekStatsForWidget(){
        $newindays = 7;
        $curdate = date('Y-m-d');
        $time = strtotime($curdate . ' -' . $newindays . ' days');
        $lastdate = date("Y-m-d", $time);

        $db = new jslearnmanagerdb();
        //Today
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE DATE(created_at) >= DATE('" . $lastdate . "') AND DATE(created_at) <= '" . $curdate . "'";
        $db->setQuery($query);
        $data = $db->loadResult();
        $result['today_add'] = $data;
        //Today
        $result['today_featured'] = apply_filters("jslm_featuredcourse_count_active_feature_course_today_report",'');
        //Today
        $result['today_purchase'] = apply_filters( 'jslm_addon_count_per_weeks_purchase_course', '',$curdate,$lastdate );
        //Today
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_student_enrollment` WHERE DATE(created_at) >= DATE('" . $lastdate . "') AND DATE(created_at) <= '" . $curdate . "'";
        $db->setQuery($query);
        $data = $db->loadResult();
        $result['today_enrolled'] = $data;
        $result['curdate'] = $curdate;
        $result['lastdate'] = $lastdate;
        $for = 1;
        $html = $this->makeHTMLOfWidget($result , $for);
        return $html;
    }

    function getLatestCoursesForWidget(){

        do_action("jslm_paidcourse_course_price_select_query_data");
        $db = new jslearnmanagerdb();
        // Courses
        $query = "SELECT c.id as course_id, c.title as title, ". jslearnmanager::$_addon_query['select'] ." cat.category_name as category, intr.name as instructor_name, intr.id as instructor_id,c.file as file, c.isapprove
                          FROM `#__js_learnmanager_course` AS c
                            LEFT JOIN `#__js_learnmanager_category` AS cat ON c.category_id = cat.id
                            LEFT JOIN `#__js_learnmanager_instructor` AS intr ON intr.id = c.instructor_id
                            ORDER BY c.created_at DESC LIMIT 0,5";
        do_action("reset_jslmaddon_query");
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $courses_list = $results;
        $html = $this->makeHTMLOfWidget($courses_list , 3);
        return $html;
    }

    function getLatestEnrollmentForWidget(){

        do_action("jslm_paidcourse_course_price_select_query_data");
        $db = new jslearnmanagerdb();
        // enrollment
        $query = "SELECT c.id as course_id, c.title as title, s.name as student_name, s.id as student_id, s.image, ". jslearnmanager::$_addon_query['select'] ." c.file
                          FROM `#__js_learnmanager_course` AS c
                            INNER JOIN `#__js_learnmanager_student_enrollment` AS se ON c.id = se.course_id
                            INNER JOIN `#__js_learnmanager_student` AS s ON s.id = se.student_id
                            ORDER BY se.created_at DESC LIMIT 0,5";
        do_action("reset_jslmaddon_query");
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $enrollment_list = $results;
        $html = $this->makeHTMLOfWidget($enrollment_list , 2);
        return $html;
    }

    function getTotalStatsForWidget(){

        $db = new jslearnmanagerdb();
        $result = array();

        // total course
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course`";
        $db->setQuery($query);
        $data = $db->loadResult();
        $result['total_course'] = $data;

        // publish course
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE course_status = 1 AND isapprove = 1";
        $db->setQuery($query);
        $data = $db->loadResult();
        $result['publish_course'] = $data;

        // Active features course
        $result['feature_active_course'] = apply_filters("jslm_featuredcourse_get_data_for_widget_stats_for_active_feature",0);

        // total students
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_student`";
        $db->setQuery($query);
        $data = $db->loadResult();
        $result['total_students'] = $data;

        // total instructors
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_instructor`";
        $db->setQuery($query);
        $data = $db->loadResult();
        $result['total_instructors'] = $data;

        $html = $this->makeHTMLOfWidget($result , 4);
        return $html;
    }

    function getLatestInstructorsForWidget(){

        $db = new jslearnmanagerdb();
        // instructor
        $query = "SELECT i.id,i.name,user.email,i.image FROM `#__js_learnmanager_instructor` AS i
                    INNER JOIN `#__js_learnmanager_user` AS user ON user.id = i.user_id
            ORDER BY user.created_at DESC LIMIT 0,5";

        $db->setQuery($query);
        $data = $db->loadObjectList();

        $html = $this->makeHTMLOfWidget($data , 5,1); // 1 for instructor
        return $html;
    }

    function getLatestStudentsForWidget(){

        $db = new jslearnmanagerdb();
        // student
        $query = "SELECT s.id,s.name,user.email,s.image FROM `#__js_learnmanager_student` AS s
                    INNER JOIN `#__js_learnmanager_user` AS user ON user.id = s.user_id
            ORDER BY user.created_at DESC LIMIT 0,5";

        $db->setQuery($query);
        $data = $db->loadObjectList();

        $html = $this->makeHTMLOfWidget($data , 5,2); // 2 for instructor
        return $html;
    }

    function makeHTMLOfWidget($data , $for,$foruser = null){
        $html = '';
        switch ($for) {
            case '1':
                $html = '
                <div class="jslearnmanager-widget-contaner">';
                    if(isset($data['curdate']) && isset($data['lastdate'])){
                        $html .= '<div class="header-date">
                            ' . $data['curdate'] . ' - ' . $data['lastdate'] . '
                        </div>';
                    }
                    $html .= '<div class="jslearnmanager-widget-block mrgn-right mrgn-bottm">
                        <div class="jslearnmanager-widget-img">
                            <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/widgets/course.png'.'" />
                        </div>
                        <div class="jslearnmanager-widget-data">
                            <h1 class="jslm_heading">'.$data['today_add'].'</h1>
                            <h4 class="jslm_detail">'.__('New Courses','learn-manager').'</h4>
                        </div>
                    </div>';
                    $html .= apply_filters("jslm_featuredcourse_html_widget_for_feature_course",'',$data['today_featured'],1);
                    $html .= '<div class="jslearnmanager-widget-block mrgn-right">
                        <div class="jslearnmanager-widget-img">
                            <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/widgets/purchase-course.png'.'" />
                        </div>
                        <div class="jslearnmanager-widget-data">
                            <h1 class="jslm_heading">'.$data['today_purchase'].'</h1>
                            <h4 class="jslm_detail">'.__('Purchase Courses','learn-manager').'</h4>
                        </div>
                    </div>
                    <div class="jslearnmanager-widget-block">
                        <div class="jslearnmanager-widget-img">
                            <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/widgets/enrolled-course.png'.'" />
                        </div>
                        <div class="jslearnmanager-widget-data">
                            <h1 class="jslm_heading">'.$data['today_enrolled'].'</h1>
                            <h4 class="jslm_detail">'.__('Enrolled Courses','learn-manager').'</h4>
                        </div>
                    </div>
                </div>';
            break;
            case '2': // latest enrollment
                $html = '
                    <div class="jslearnmanager-widget-contaner"> ';
                        foreach ($data as $row) {
                            $course_link = admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$row->course_id);
                            $html .= '
                            <div class="jslearnmanager-widget-course-row">
                                <div class="widg-course-image">
                                    <div class="course-widget-image">
                                        <img class="widg-course-image" src="'.$row->file.'" />
                                    </div>
                                </div>
                                <div class="course-row-top">
                                    <span class="widg-course-title"><a href="'.$course_link.'">'.$row->title.'</a></span>';
                                        if(isset($row->price) && $row->price > 0){
                                            $html .= '<span class="widg-course-price">'.__("Paid","learn-manager").'</span>';
                                        }else{
                                            $html .= '<span class="widg-course-price">'.__("Free","learn-manager").'</span>';
                                        }
                                $html .= '</div>
                                <div class="course-row-bottom">
                                    <span class="widg-course-enrolled_name">'.$row->student_name.'</span>
                                </div>
                            </div>';
                        }
                $html .='</div>';
            break;
            case '3': // latest courses
                $html = '
                    <div class="jslearnmanager-widget-contaner"> ';
                        foreach ($data as $row) {
                            $course_link = admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$row->course_id);
                            if(isset($row->instructor_name) && $row->instructor_name != ""){
                                $instructorname = $row->instructor_name;
                            }else{
                                $instructorname = "Admin";
                            }
                            if($row->file == null || $row->file == ""){
                                $image = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                            }else{
                                $image = $row->file;
                            }
                            $html .= '
                            <div class="jslearnmanager-widget-course-row">
                                <div class="widg-course-image">
                                    <div class="course-widget-image">
                                        <img class="widg-course-image" src="'.$image.'" />
                                    </div>
                                </div>
                                <div class="course-row-top">
                                    <span class="widg-course-title"><a href="'.$course_link.'">'.$row->title.'</a></span>';
                                        if(isset($row->price) && $row->price > 0){
                                            $html .= '<span class="widg-course-price">'.__("Paid","learn-manager").'</span>';
                                        }else{
                                            $html .= '<span class="widg-course-price">'.__("Free","learn-manager").'</span>';
                                        }
                                $html .= '</div>
                                <div class="course-row-bottom">
                                    <span class="widg-course-enrolled_name">'.__($row->category , 'learn-manager').'</span>
                                    <span class="widg-course-comments">'.$instructorname.'</span>
                                </div>
                            </div>';
                        }
                $html .='</div>';
            break;
            case '4': // total stats
                $html = '
                <div class="jslearnmanager-widget-contaner">
                    <div class="jslearnmanager-widget-total-block">
                        <img class="jslm_totalimage" src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/widgets/total-course.png'.'" />
                        <span class="jslm_total-detail">'.__('Total Courses','learn-manager').'</span>
                        <span class="jslm_total-total">'.$data['total_course'].'</span>
                    </div>
                    <div class="jslearnmanager-widget-total-block">
                        <img class="jslm_totalimage" src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/widgets/publish-course.png'.'" />
                        <span class="jslm_total-detail">'.__('Publish Courses','learn-manager').'</span>
                        <span class="jslm_total-total">'.$data['publish_course'].'</span>
                    </div>';
                    $html .= apply_filters("jslm_featuredcourse_html_widget_for_feature_course",'',$data['feature_active_course'],4);
                    $html .= '<div class="jslearnmanager-widget-total-block">
                        <img class="jslm_totalimage" src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/widgets/total-instuctor.png'.'" />
                        <span class="jslm_total-detail">'.__('Total Instructors','learn-manager').'</span>
                        <span class="jslm_total-total">'.$data['total_instructors'].'</span>
                    </div>
                    <div class="jslearnmanager-widget-total-block">
                        <img class="jslm_totalimage" src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/widgets/total-students.png'.'" />
                        <span class="jslm_total-detail">'.__('Total Students','learn-manager').'</span>
                        <span class="jslm_total-total">'.$data['total_students'].'</span>
                    </div>
                </div>';
            break;
            case '5': // latest user
                $html = '
                    <div class="jslearnmanager-widget-contaner"> ';
                        foreach ($data as $row) {
                            if($row->image){
                                $logo = $row->image;
                            }else{
                                $logo = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                            }
                            if($foruser == 1){ // 1 for instructor
                                $link = admin_url('admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid='.$row->id);
                            }else{
                                $link = admin_url('admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid='.$row->id);
                            }
                            $html .= '
                            <div class="jslearnmanager-widget-user-row">
                                <div class="widg-user-image">
                                    <div class="user-image">
                                        <img class="widg-user-image" src="'.$logo.'" />
                                    </div>
                                </div>
                                <div class="user-row-top">
                                    <span class="widg-user-title"><a href="'.$link.'">'.$row->name.'</a></span>
                                </div>
                                <div class="user-row-bottom">
                                    <span class="widg-user-email">'.$row->email.'</span>
                                </div>
                            </div>';
                        }
                $html .='</div>';
                break;
                case '6': // latest reviews
                $html = apply_filters("jslm_coursereview_admin_widget_html_data",'',$data);

                break;
        }
        return $html;
    }

    function storeServerSerailNumber($data) {
        if (empty($data))
            return false;
        // DB class limitations
        if ($data['server_serialnumber']) {
            $query = "UPDATE  `#__js_learnmanager_config` SET configvalue='" . $data['server_serialnumber'] . "' WHERE configname='server_serial_number'";
            $db = new jslearnmanagerdb();
            if (!$db->query($query))
                return JSLEARNMANAGER_SAVE_ERROR;
            else
                return JSLEARNMANAGER_SAVED;
        } else
            return JSLEARNMANAGER_SAVE_ERROR;
    }


    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die(__('Cannot open file', 'learn-manager'));
            fclose($ourFileHandle);
        }
    }

    function getListTranslations() {
        $result = array();
        $result['error'] = false;
        // $path = jslearnmanager::$_path.'languages';
        $path = WP_LANG_DIR;
        if(!is_dir($path)){
            $this->makeDir($path);
        }else{
            $path = WP_LANG_DIR . '/plugins/';
            if(!is_dir($path)){
                $this->makeDir($path);
            }
        }


        if(!is_writeable($path)){
            $result['error'] = __('Dir is not writable','learn-manager').' '.$path;

        }else{

            if($this->isConnected()){
                $version = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('default');
                $url = "https://wplearnmanager.com/translations/api/1.0/index.php";
                $post_data['product'] ='learn-manager';
                $post_data['domain'] = get_site_url();
                // $post_data['producttype'] = $version['versiontype'];
                $post_data['productcode'] = 'learn-manager';
                $post_data['productversion'] = $version['productversion'];
                $post_data['JVERSION'] = get_bloginfo('version');
                $post_data['method'] = 'getTranslations';
                $curl_response = wp_remote_post($url,array('body'=>$post_data));
                $response = $curl_response['body'];
                $result['data'] = $response;
            }else{
                $result['error'] = __('Unable to connect to server','learn-manager');
            }
        }

        $result = ($result);

        return $result;
    }

    function makeLanguageCode($lang_name){
        $langarray = wp_get_installed_translations('core');
        $langarray = $langarray['default'];
        $match = false;
        if(array_key_exists($lang_name, $langarray)){
            $lang_name = $lang_name;
            $match = true;
        }else{
            $m_lang = '';
            foreach($langarray AS $k => $v){
                if($lang_name[0].$lang_name[1] == $k[0].$k[1]){
                    $m_lang .= $k.', ';
                }
            }

            if($m_lang != ''){
                $m_lang = substr($m_lang, 0,strlen($m_lang) - 2);
                $lang_name = $m_lang;
                $match = 2;
            }else{
                $lang_name = $lang_name;
                $match = false;
            }
        }

        return array('match' => $match , 'lang_name' => $lang_name);
    }

    function validateAndShowDownloadFileName( ){
        $lang_name = JSLEARNMANAGERrequest::getVar('langname');
        if($lang_name == '') return '';
        $result = array();
        $f_result = $this->makeLanguageCode($lang_name);
        // $path = jslearnmanager::$_path.'languages';
        $path = WP_LANG_DIR . '/plugins/';
        $result['error'] = false;
        if($f_result['match'] === false){
            $result['error'] = $lang_name. ' ' . __('Language is not installed','learn-manager');
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . __('Language directory is not writable','learn-manager').': '.$path;
        }else{
            $result['input'] = '<input id="languagecode" class="text_area" type="text" value="'.$lang_name.'" name="languagecode">';
            if($f_result['match'] === 2){
                $result['input'] .= '<div id="js-emessage-wrapper" style="display:block;margin:20px 0px 20px;">';
                $result['input'] .= __('Required language is not installed but similar language[s] like').': "<b>'.$f_result['lang_name'].'</b>" '.__('is found in your system','learn-manager');
                $result['input'] .= '</div>';

            }
            $result['path'] = __('Language code','learn-manager');
        }
        $result = ($result);
        return $result;
    }

    function getLanguageTranslation(){

        $lang_name = JSLEARNMANAGERrequest::getVar('langname');
        $language_code = JSLEARNMANAGERrequest::getVar('filename');

        $result = array();
        $result['error'] = false;
        // $path = jslearnmanager::$_path.'languages';
        $path = WP_LANG_DIR . '/plugins/';
        if(!is_dir($path)){
            mkdir($path);
        }
        if($lang_name == '' || $language_code == ''){
            $result['error'] = __('Empty values','learn-manager');
            return ($result);
        }

        $final_path = $path.'/learn-manager-'.$language_code.'.po';


        $langarray = wp_get_installed_translations('core');
        $langarray = $langarray['default'];

        if(!array_key_exists($language_code, $langarray)){
            $result['error'] = $lang_name. ' ' . __('Language is not installed','learn-manager');
            return ($result);
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . __('Language directory is not writable','learn-manager').': '.$path;
            return ($result);
        }

        if( ! file_exists($final_path)){
            touch($final_path);
        }

        if( ! is_writeable($final_path)){
            $result['error'] = __('File is not writable','learn-manager').': '.$final_path;
        }else{

            if($this->isConnected()){
                $version = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('default');
                $url = "https://wplearnmanager.com/translations/api/1.0/index.php";
                $post_data['product'] ='learn-manager';
                $post_data['domain'] = get_site_url();
                // $post_data['producttype'] = $version['versiontype'];
                $post_data['productcode'] = 'jslearnmanager';
                $post_data['productversion'] = $version['productversion'];
                $post_data['JVERSION'] = get_bloginfo('version');
                $post_data['translationcode'] = $lang_name;
                $post_data['method'] = 'getTranslationFile';
                $curl_response = wp_remote_post($url,array('body'=>$post_data));
                if( !is_wp_error($curl_response) && $curl_response['response']['code'] == 200 && isset($curl_response['body']) ){
                    $response = $curl_response['body'];
                    $array = json_decode($response, true);
                    $ret = $this->writeLanguageFile( $final_path , $array['file']);
                    if($ret != false){
                        $url = "https://wplearnmanager.com/translations/api/1.0/index.php";
                        $post_data['product'] ='learn-manager';
                        $post_data['domain'] = get_site_url();
                        // $post_data['producttype'] = $version['versiontype'];
                        $post_data['productcode'] = 'jslearnmanager';
                        $post_data['productversion'] = $version['productversion'];
                        $post_data['JVERSION'] = get_bloginfo('version');
                        $post_data['folder'] = $array['foldername'];
                        $curl_response = wp_remote_post($url,array('body'=>$post_data));
                        $response = $curl_response['body'];
                    }
                    $result['data'] = __('File Downloaded Successfully','learn-manager');
                }else{
                    $result['error'] = $curl_response->get_error_message();
                }
            }else{
                $result['error'] = __('Unable to connect to server','learn-manager');
            }
        }

        $result = ($result);

        return $result;
    }

    function writeLanguageFile( $path , $url ){
        $result = true;
        include(ABSPATH . "wp-admin/includes/admin.php");
        $tmpfile = download_url( $url);
        copy( $tmpfile, $path );
        @unlink( $tmpfile ); // must unlink afterwards
        //make mo for po file
        $this->phpmo_convert($path);
        return $result;
    }

    function isConnected(){

        $connected = @fsockopen("www.google.com", 80);
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    function phpmo_convert($input, $output = false) {
        if ( !$output )
            $output = str_replace( '.po', '.mo', $input );
        $hash = $this->phpmo_parse_po_file( $input );
        if ( $hash === false ) {
            return false;
        } else {
            $this->phpmo_write_mo_file( $hash, $output );
            return true;
        }
    }

    function phpmo_clean_helper($x) {
        if (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->phpmo_clean_helper($v);
            }
        } else {
            if ($x[0] == '"')
                $x = substr($x, 1, -1);
            $x = str_replace("\"\n\"", '', $x);
            $x = str_replace('$', '\\$', $x);
        }
        return $x;
    }
    /* Parse gettext .po files. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files */
    function phpmo_parse_po_file($in) {
        if (!file_exists($in)){ return false; }
        $ids = array();
        $strings = array();
        $language = array();
        $lines = file($in);
        foreach ($lines as $line_num => $line) {
            if (strstr($line, 'msgid')){
                $endpos = strrchr($line, '"');
                $id = substr($line, 7, $endpos-2);
                $ids[] = $id;
            }elseif(strstr($line, 'msgstr')){
                $endpos = strrchr($line, '"');
                $string = substr($line, 8, $endpos-2);
                $strings[] = array($string);
            }else{}
        }
        for ($i=0; $i<count($ids); $i++){
            //Shoaib
            if(isset($ids[$i]) && isset($strings[$i])){
                if($entry['msgstr'][0] == '""'){
                    continue;
                }
                $language[$ids[$i]] = array('msgid' => $ids[$i], 'msgstr' =>$strings[$i]);
            }
        }
        return $language;
    }


    // function phpmo_parse_po_file($in) {
    //     if (!file_exists($in)){ return false; }
    //     $ids = array();
    //     $strings = array();
    //     $language = array();
    //     $lines = file($in);
    //     foreach ($lines as $line_num => $line) {
    //         if (strstr($line, 'msgid')){
    //             $endpos = strrchr($line, '"');
    //             $id = substr($line, 7, $endpos-2);
    //             $ids[] = $id;
    //         }elseif(strstr($line, 'msgstr')){
    //             $endpos = strrchr($line, '"');
    //             $string = substr($line, 8, $endpos-2);
    //             $strings[] = array($string);
    //         }else{}
    //     }
    //     for ($i=0; $i<count($ids); $i++){
    //         //Shoaib
    //         if(isset($ids[$i]) && isset($strings[$i])){
    //             if($entry['msgstr'][0] == '""'){
    //                 continue;
    //             }
    //             $language[$ids[$i]] = array('msgid' => $ids[$i], 'msgstr' =>$strings[$i]);
    //         }
    //     }
    //     return $language;
    // }

    /* Write a GNU gettext style machine object. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files */
    function phpmo_write_mo_file($hash, $out) {
        // sort by msgid
        ksort($hash, SORT_STRING);
        // our mo file data
        $mo = '';
        // header data
        $offsets = array ();
        $ids = '';
        $strings = '';
        foreach ($hash as $entry) {
            $id = $entry['msgid'];
            $str = implode("\x00", $entry['msgstr']);
            // keep track of offsets
            $offsets[] = array (
                            strlen($ids), strlen($id), strlen($strings), strlen($str)
                            );
            // plural msgids are not stored (?)
            $ids .= $id . "\x00";
            $strings .= $str . "\x00";
        }
        // keys start after the header (7 words) + index tables ($#hash * 4 words)
        $key_start = 7 * 4 + sizeof($hash) * 4 * 4;
        // values start right after the keys
        $value_start = $key_start +strlen($ids);
        // first all key offsets, then all value offsets
        $key_offsets = array ();
        $value_offsets = array ();
        // calculate
        foreach ($offsets as $v) {
            list ($o1, $l1, $o2, $l2) = $v;
            $key_offsets[] = $l1;
            $key_offsets[] = $o1 + $key_start;
            $value_offsets[] = $l2;
            $value_offsets[] = $o2 + $value_start;
        }
        $offsets = array_merge($key_offsets, $value_offsets);
        // write header
        $mo .= pack('Iiiiiii', 0x950412de, // magic number
        0, // version
        sizeof($hash), // number of entries in the catalog
        7 * 4, // key index offset
        7 * 4 + sizeof($hash) * 8, // value index offset,
        0, // hashtable size (unused, thus 0)
        $key_start // hashtable offset
        );
        // offsets
        foreach ($offsets as $offset)
            $mo .= pack('i', $offset);
        // ids
        $mo .= $ids;
        // strings
        $mo .= $strings;
        file_put_contents($out, $mo);
    }

    function updateDate($addon_name,$plugin_version){
        return JSLEARNMANAGERincluder::getJSModel('premiumplugin')->verfifyAddonActivation($addon_name);
    }

    function getAddonSqlForActivation($addon_name,$addon_version){
        return JSLEARNMANAGERincluder::getJSModel('premiumplugin')->verifyAddonSqlFile($addon_name,$addon_version);
    }

    function hidePopupFromAdmin(){
        update_option( 'jslms_hide_lmadmin_top_banner', 1 );
    }

    function installPluginFromAjax(){
        $pluginslug = JSLEARNMANAGERrequest::getVar('pluginslug');
        if(file_exists( content_url() . '/plugins/' . $pluginslug.'/'.$pluginslug.'.php')){
            return false;
        }
        if($pluginslug != ""){
            require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
            require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
            require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
            require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );

            // Get Plugin Info
            $api = plugins_api( 'plugin_information',
                array(
                    'slug' => $pluginslug,
                    'fields' => array(
                        'short_description' => false,
                        'sections' => false,
                        'requires' => false,
                        'rating' => false,
                        'ratings' => false,
                        'downloaded' => false,
                        'last_updated' => false,
                        'added' => false,
                        'tags' => false,
                        'compatibility' => false,
                        'homepage' => false,
                        'donate_link' => false,
                    ),
                )
            );
            $skin     = new WP_Ajax_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader( $skin );
            $upgrader->install( $api->download_link );
            if(file_exists(content_url() . '/plugins/' . $pluginslug.'/'.$pluginslug.'.php')){
                return true;
            }
        }
        return false;
    }

    function activatePluginFromAjax(){
        $pluginslug = JSLEARNMANAGERrequest::getVar('pluginslug');
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if(file_exists(content_url() . '/plugins/' . $pluginslug.'/'.$pluginslug.'.php')){
            $isactivate = is_plugin_active($pluginslug.'/'.$pluginslug.'.php');
            if($isactivate){
                return false;
            }
            if($pluginslug != ""){
                if(!defined( 'WP_ADMIN')){
                    define( 'WP_ADMIN', TRUE );
                }
                // define( 'WP_NETWORK_ADMIN', TRUE ); // Need for Multisite
                if(!defined( 'WP_USER_ADMIN')){
                    define( 'WP_USER_ADMIN', TRUE );
                }

                ob_get_clean();
                do_action('jslm_load_file_path');
                require_once( ABSPATH . 'wp-admin/includes/admin.php' );
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                activate_plugin( $pluginslug.'/'.$pluginslug.'.php' );
                // $isactivate = $this->run_activate_plugin( $pluginslug.'/'.$pluginslug.'.php' );
                $isactivate = is_plugin_active($pluginslug.'/'.$pluginslug.'.php');
                if($isactivate){
                    return true;
                }
            }
        }
        return false;
    }

    function getMessagekey(){
        $key = 'jslearnmanager';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

}

?>
