<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLearnManageractivitylogModel {

    function __construct() {
    }

    function storeActivity($tablename, $columns, $id=null, $activityfor) {
        if ($id == null) {
            $id = $columns['id'];
        }
        if (!is_numeric($id))
            return false;
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $uid = ($uid != null) ? $uid : 0;

        if($activityfor == 2){// in case of status change of entity it does not contain all the columns of the record
            $columns = $this->getEntityData($id,$tablename);
        }

        $text = $this->getActivityDescription($tablename, $uid, $columns, $id, $activityfor);
        if ($text == false) {
            return;
        }

        $name = $text[0];
        $desc = $text[1];
        $created = date("Y-m-d H:i:s");
        $data = array();
        $data['description'] = $desc;
        $data['referencefor'] = $name;
        $data['referenceid'] = $id;
        $data['uid'] = $uid;
        $data['created'] = $created;
        $db = new jslearnmanagerdb();
        $db->_insert('activitylog',$data);
        return JSLEARNMANAGER_SAVED;
    }

    function storeActivityLogForActionDelete($text, $id) {
        if (!is_numeric($id))
            return false;
        if ($text == false)
            return;
        $name = $text[0];
        $desc = $text[1];
        $uid = $text[2];
        $uid = $uid != null ? $uid : 0;
        $created = date("Y-m-d H:i:s");

        $data = array();
        $data['description'] = $desc;
        $data['referencefor'] = $name;
        $data['referenceid'] = $id;
        $data['uid'] = $uid;
        $data['created'] = $created;
        $db = new jslearnmanagerdb();
        $db->_insert('activitylog',$data);

        return JSLEARNMANAGER_SAVED;
    }

    function sorting() {
     jslearnmanager::$_data['sorton'] = isset(jslearnmanager::$_data['filter']['sorton']) ? jslearnmanager::$_data['filter']['sorton'] : 4;
        jslearnmanager::$_data['sortby'] = isset(jslearnmanager::$_data['filter']['sortby']) ? jslearnmanager::$_data['filter']['sortby'] : 2;

        switch (jslearnmanager::$_data['sorton']) {
            case 1: // created
                jslearnmanager::$_data['sorting'] = ' act.id ';
                break;
            case 2: // user name
                jslearnmanager::$_data['sorting'] = ' u.name ';
                break;
            case 3: // category
                jslearnmanager::$_data['sorting'] = ' act.referencefor ';
                break;
            case 4: // location
                jslearnmanager::$_data['sorting'] = ' act.created ';
                break;
        }
        if (jslearnmanager::$_data['sortby'] == 1) {
            jslearnmanager::$_data['sorting'] .= ' ASC ';
        } else {
            jslearnmanager::$_data['sorting'] .= ' DESC ';
        }
        jslearnmanager::$_data['combosort'] = jslearnmanager::$_data['sorton'];
    }

    function getAllActivities() {

        $this->sorting();
        $data = JSLEARNMANAGERrequest::getVar('filter');
        $string = '';
        $comma = '';

        if (isset($data['course'])) {
            $string .= $comma . '"course"';
            $comma = ',';
        }

        if (isset($data['reviews'])) {
            $string .= $comma . '"reviews"';
            $comma = ',';
        }

        if (isset($data['section'])) {
            $string .= $comma . '"section"';
            $comma = ',';
        }

        if (isset($data['lecture'])) {
            $string .= $comma . '"lecture"';
            $comma = ',';
        }

        if (isset($data['file'])) {
            $string .= $comma . '"file"';
            $comma = ',';
        }

        if (isset($data['questions'])) {
            $string .= $comma . '"questions"';
            $comma = ',';
        }

        if (isset($data['messages'])) {
            $string .= $comma . '"messages"';
            $comma = ',';
        }

        if (isset($data['paymenthistory'])) {
            $string .= $comma . '"paymenthistory"';
            $comma = ',';
        }

        if (isset($data['enrollment'])) {
            $string .= $comma . '"enrollment"';
            $comma = ',';
        }

        if (isset($data['shortlist'])) {
            $string .= $comma . '"shortlist"';
            $comma = ',';
        }

        if (isset($data['config'])) {
            $string .= $comma . '"config"';
            $comma = ',';
        }

        if (isset($data['payouts'])) {
            $string .= $comma . '"payouts"';
            $comma = ',';
        }

        if (isset($data['paymentplan'])) {
            $string .= $comma . '"paymentplan"';
            $comma = ',';
        }

        if (isset($data['country'])) {
            $string .= $comma . '"country"';
            $comma = ',';
        }

        if (isset($data['category'])) {
            $string .= $comma . '"category"';
            $comma = ',';
        }

        if (isset($data['emailtemplate'])) {
            $string .= $comma . '"emailtemplates"';
            $comma = ',';
        }

        $inquery = " ";
        $db = new jslearnmanagerdb();
        $searchsubmit = JSLEARNMANAGERrequest::getVar('searchsubmit');
        if(!empty($searchsubmit) AND $searchsubmit == 1){
            $query = "UPDATE `#__js_learnmanager_config`
                set configvalue = '$string' WHERE configname = 'activity_log_filter'";
            $db->setQuery($query);
            $db->query();
        }

        $activity_log_filter = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('activity_log_filter');

        if ($string != '') {
            $inquery = "WHERE act.referencefor IN ($string) ";
        } else if ($activity_log_filter != null) {

            $data = array();
            $string = $activity_log_filter;
            $inquery = "WHERE act.referencefor IN ($string) ";
            //showing check boxes checked
            $array = explode(',', $string);
            foreach ($array as $var) {
                switch ($var) {
                    case '"course"':
                        $data['course'] = 1;
                        break;
                    case '"reviews"':
                        $data['reviews'] = 1;
                        break;
                    case '"section"':
                        $data['section'] = 1;
                        break;
                    case '"lecture"':
                        $data['lecture'] = 1;
                        break;
                    case '"file"':
                        $data['file'] = 1;
                        break;
                    case '"questions"':
                        $data['questions'] = 1;
                        break;
                    case '"messages"':
                        $data['messages'] = 1;
                        break;
                    case '"paymenthistory"':
                        $data['paymenthistory'] = 1;
                        break;
                    case '"enrollment"':
                        $data['enrollment'] = 1;
                        break;
                    case '"shortlist"':
                        $data['shortlist'] = 1;
                        break;
                    case '"config"':
                        $data['config'] = 1;
                        break;
                    case '"payouts"':
                        $data['payouts'] = 1;
                        break;
                    case '"paymentplan"':
                        $data['paymentplan'] = 1;
                        break;
                    case '"country"':
                        $data['country'] = 1;
                        break;
                    case '"category"':
                        $data['category'] = 1;
                        break;
                    case '"emailtemplates"':
                        $data['emailtemplate'] = 1;
                        break;
                }
            }
        }

        jslearnmanager::$_data['filter']['course'] = isset($data['course']) ? 1 : 0;
        jslearnmanager::$_data['filter']['reviews'] = isset($data['reviews']) ? 1 : 0;
        jslearnmanager::$_data['filter']['section'] = isset($data['section']) ? 1 : 0;
        jslearnmanager::$_data['filter']['lecture'] = isset($data['lecture']) ? 1 : 0;
        jslearnmanager::$_data['filter']['file'] = isset($data['file']) ? 1 : 0;
        jslearnmanager::$_data['filter']['questions'] = isset($data['questions']) ? 1 : 0;
        jslearnmanager::$_data['filter']['messages'] = isset($data['messages']) ? 1 : 0;
        jslearnmanager::$_data['filter']['paymenthistory'] = isset($data['paymenthistory']) ? 1 : 0;
        jslearnmanager::$_data['filter']['shortlist'] = isset($data['shortlist']) ? 1 : 0;
        jslearnmanager::$_data['filter']['config'] = isset($data['config']) ? 1 : 0;
        jslearnmanager::$_data['filter']['enrollment'] = isset($data['enrollment']) ? 1 : 0;
        jslearnmanager::$_data['filter']['payouts'] = isset($data['payouts']) ? 1 : 0;
        jslearnmanager::$_data['filter']['paymentplan'] = isset($data['paymentplan']) ? 1 : 0;
        jslearnmanager::$_data['filter']['country'] = isset($data['country']) ? 1 : 0;
        jslearnmanager::$_data['filter']['category'] = isset($data['category']) ? 1 : 0;
        jslearnmanager::$_data['filter']['emailtemplate'] = isset($data['emailtemplate']) ? 1 : 0;


        $query = "SELECT COUNT(act.id)
        FROM `#__js_learnmanager_activitylog` AS act
        LEFT JOIN `#__js_learnmanager_user` AS u ON u.id = act.uid " . $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        $query = "SELECT act.description,act.created,act.id,act.referencefor,u.name AS display_name
        FROM `#__js_learnmanager_activitylog` AS act
        LEFT JOIN `#__js_learnmanager_user` AS u ON u.id = act.uid " . $inquery;
        $query .= "ORDER BY " . jslearnmanager::$_data['sorting'];
        $query .=" LIMIT " . JSLEARNMANAGERpagination::$_offset . "," . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        $result = $db->loadObjectList();
        jslearnmanager::$_data[0] = $result;
        return;
    }

    function getEntityNameOrTitle($id, $text, $tablename) {
        if (!is_numeric($id))
            return false;
        if ($text == '' OR $tablename == '')
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT $text FROM `$tablename` WHERE id = " . $id;
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    private function getEntityData($id, $tablename) {
        if (!is_numeric($id))
            return false;
        if ($tablename == '')
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `$tablename` WHERE id = " . $id;
        $db->setQuery($query);
        $result = $db->loadObject();
        if($result != ''){
            $result = json_decode(json_encode($result),true);// to conver std obect into array
        }
        return $result;
    }

    function getActivityDescription($tablename, $uid, $columns, $id, $activityfor) {
        if (!is_numeric($uid)) return false;
        $array = explode('_', $tablename);
        $name = $array[count($array) - 1];
        $target = "_blank";
        switch ($name) {
            //all the tables which have title as column
            case 'course':
                $entityname = __('Course', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=".$id;
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'reviews':
                $entityname = __('Reviews', 'learn-manager');
                $linktext = JSLEARNMANAGERincluder::getJSModel('course')->getCourseNameById($columns['course_id']);
                $path = "?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=".$columns['course_id']."#comments";
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'section':
                $entityname = __('Course Section', 'learn-manager');
                $linktext = $columns['name'];
                $path = "?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=".$columns['course_id']."#curriculum";
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'lecture':
                $entityname = __('Lecture', 'learn-manager');
                $linktext = $columns['name'];
                $path = "?page=jslm_lecture&jslmslay=addlecture&jslearnmanagerid=lec_".$id;
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'file':
                $entityname = __('Lecture File of lecture', 'learn-manager');
                $linktext = JSLEARNMANAGERincluder::getJSModel('lecture')->getLectureNameById($columns['lecture_id']);
                $path = "?page=jslm_lecture&jslmslay=addlecture&jslearnmanagerid=lec_".$columns['lecture_id'];
                if($columns['file_type'] == 'video'){
                    $path .= "#addvideo";
                }else{
                    $path .= "#addfile";
                }
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'questions':
                $entityname = __('Quiz Question', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'question', $tablename);
                $linktext = strlen($linktext) > 25 ? substr($linktext, 0 , 24).'...' : $linktext;
                $path = "?page=jslm_lecture&jslmslay=addlecture&jslearnmanagerid=lec_".$columns['lecture_id']."#addquiz";
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'messages':
                $entityname = __('Messages', 'learn-manager');
                $linktext = $columns['subject'];
                $path = "?page=jslm_message&jslmslay=messagedetail&jslearnmanagerid=".$id;
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'paymenthistory':
                $entityname = __('Course Purchase', 'learn-manager');
                $linktext = $columns['purchasetitle'];
                $path = "#";
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'enrollment':
                $store = false;
                if(!empty($columns['quiz_result_params'])){
                    $entityname = __('Student Attempted a Quiz','learn-manager');
                    $store = true;
                }elseif($activityfor == 1){
                    $entityname = __('Enrollment in Course', 'learn-manager');
                    $store = true;
                }

                if($store){
                    $linktext = JSLEARNMANAGERincluder::getJSModel('course')->getCourseNameById($columns['course_id']);
                    $path = "?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=".$columns['course_id']."#member";
                    $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                }else{
                    return false;
                }
                break;
            case 'wishlist':
                $entityname = __('Shortlist Course', 'learn-manager');
                $linktext = JSLEARNMANAGERincluder::getJSModel('course')->getCourseNameById($columns['course_id']);
                $path = "?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=".$columns['course_id'];
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                $name = "shortlist";
                break;
            case 'config':
                $entityname = __('Configuration', 'learn-manager');
                $linktext = $columns['title'];
                $path = "?page=jslm_configuration&jslmslay=formconfiguration&jslearnmanagerid=".$id;
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'payouts':
                $entityname = __('Payouts', 'learn-manager');
                $linktext = $columns['payment'];
                $instructorname = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorNameBYId($columns['instructor_id']);
                $path = "?page=jslm_payouts&jslmslay=formpayout&jslearnmanagerid=".$id;
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                $path = "?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid=".$columns['instructor_id'];
                $html .= " to instructor (<a href=" . $path . " target=$target><strong>" . $instructorname . "</strong></a>)";
                break;
            case 'paymentplan':
                $entityname = __('Payment Plan', 'learn-manager');
                $linktext = $columns['title'];
                $path = "?page=jslm_paymentplan&jslmslay=formpaymentplan&jslearnmanagerid=".$id;
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'country':
                $entityname = __('Country', 'learn-manager');
                //$linktext = $flag == 1 ? $columns['country_name'] : $this->getEntityNameOrTitle($id, 'country_name', $tablename);
                $linktext = $this->getEntityNameOrTitle($id, 'country_name', $tablename);
                $path = "?page=jslmsmod_country&jslmslay=formcountry&jslearnmanagerid=$id";
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'category':
                $entityname = __('Category', 'learn-manager');
                //$linktext = $flag == 1 ? $columns['category_name'] : $this->getEntityNameOrTitle($id, 'category_name', $tablename);
                $linktext = $this->getEntityNameOrTitle($id, 'category_name', $tablename);
                $path = "?page=jslmsmod_category&jslmslay=formcategory&jslearnmanagerid=$id";
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            case 'emailtemplates':
                $entityname = __('Email Template', 'learn-manager');
                //$linktext = $flag == 1 ? $columns['templatefor'] : $this->getEntityNameOrTitle($id, 'templatefor', $tablename);
                $linktext = $this->getEntityNameOrTitle($id, 'templatefor', $tablename);
                $path = "?page=jslmsmod_emailtemplate&jslmslay=formemailtemplte&jslearnmanagerid=$id";
                $html = "<a href=" . $path . " target=$target><strong>" . $linktext . "</strong></a>";
                break;
            default:
                return false;
                break;
        }
        $username = $this->getNameFromUid($uid);
        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
        if($usertype == "Student"){
            $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
            $link = admin_url('admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid=' . $studentid);
        }elseif($usertype == "Instructor"){
            $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
            $link = admin_url('admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid=' . $instructorid);
        }else{
            $link = "#";
        }
        $path2 = $link;
        if(current_user_can('manage_options')){
            $html2 = __('Administrator','learn-manager');
        }else{
            $html2 = "<a href=" . $path2 . " target=$target><strong>" . $username . "</strong></a>";
        }
        switch ($activityfor) {
            case 1:
                $entityaction = __('added a new', 'learn-manager');
                break;
            case 2:
                $entityaction = __('edited a existing', 'learn-manager');
                break;
            case 3:
                $entityaction = __('delete a record', 'learn-manager');
                break;
            default:
                $entityaction = __('unknown', 'learn-manager');
                break;
        }
        $result = array();
        $result[0] = $name;
        $result[1] = $html2 . " " . $entityaction . " " . $entityname . " " . $html;
        return $result;
    }

    function getNameFromUid($uid) {

        if (!is_numeric($uid))
            return false;
        if ($uid == 0) {
            return "guest";
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT name FROM `#__js_learnmanager_user` WHERE id = " . $uid;
        $db->setQuery($query);
        $name = $db->loadResult();
        return $name;
    }

    function getDeleteActionDataToStore($tablename, $id) {
        $array = explode('_', $tablename);
        $name = $array[count($array) - 1];
        switch ($name) {
            //all the tables which have title as column
            case 'course':
                $entityname = __('Course', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'section':
                $entityname = __('Course Section', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'name', $tablename);
                break;
            case 'lecture':
                $entityname = __('Lecture', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'name', $tablename);
                break;
            case 'file':
                $entityname = __('Lecture File', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'filename', $tablename);
                break;
            case 'questions':
                $entityname = __('Quiz Question', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'question', $tablename);
                $linktext = strlen($linktext) > 25 ? substr($linktext, 0 , 24).'...' : $linktext;
                break;
            case 'messages':
                $entityname = __('Message', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'subject', $tablename);
                break;
            case 'enrollment':
                $entityname = __('Enrollment', 'learn-manager');
                $linktext = JSLEARNMANAGERincluder::getJSModel('course')->getCourseNameById($id);
                break;
            case 'wishlist':
                $entityname = __('Shortlist', 'learn-manager');
                $linktext = JSLEARNMANAGERincluder::getJSModel('course')->getCourseNameByShortlistId($id);
                $name = 'shortlist';
                break;
            case 'payouts':
                $entityname = __('Payouts','learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'payment', $tablename);
                break;
            case 'paymentplan':
                $entityname = __('Payment Plan','learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'messages':
                $entityname = __('Message','learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'subject', $tablename);
                break;
            case 'country':
                $entityname = __('Country', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'country_name', $tablename);
                break;
            case 'emailtemplates':
                $entityname = __('Email Template', 'learn-manager');
                $linktext = $this->getEntityNameOrTitle($id, 'templatefor', $tablename);
                break;
            default:
                return false;
                break;
        }
        $target = "_blank";
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $username = $this->getNameFromUid($uid);
        $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
        if($usertype == "Student"){
            $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
            $link = admin_url('admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid=' . $studentid);
        }elseif($usertype == "Instructor"){
            $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($uid);
            $link = admin_url('admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid=' . $instructorid);
        }else{
            $link = "#";
        }
        $path2 = $link;
        if(current_user_can('manage_options')){
            $html2 = __('Administrator','learn-manager');
        }else{
            $html2 = "<a href=" . $path2 . " target=$target><strong>" . $username . "</strong></a>";
        }
        // $html2 = "<a href='" . $path2 . "' target=$target><strong>" . $username . "</strong></a>";
        $entityaction = __("Deleted a", "learn-manager");
        $result = array();
        $result[0] = $name;
        $result[1] = $html2 . " " . $entityaction . " " . $entityname . " ( " . $linktext . " )";
        $result[2] = $uid;

        return $result;
    }



    function getAdminActivitySearchFormData(){
        $jslms_search_array = array();
        $jslms_search_array['sorton'] = trim(JSLEARNMANAGERrequest::getVar('sorton' , '', 4));
        $jslms_search_array['sortby'] = trim(JSLEARNMANAGERrequest::getVar('sortby' , '', 2));
        $jslms_search_array['search_from_activitylog'] = 1;
        return $jslms_search_array;
    
    }

}

?>
