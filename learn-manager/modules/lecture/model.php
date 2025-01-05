<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERlectureModel {

    function getLectureById($lectureid){

        if(!is_numeric($lectureid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT l.name as lecture_name, l.description as description , s.name as section_name, l.params , l.id as lecture_id
                    FROM `#__js_learnmanager_course_section` AS s
                        INNER JOIN `#__js_learnmanager_course_section_lecture` AS l ON s.id = l.section_id
                        INNER JOIN `#__js_learnmanager_course` AS c ON c.id = s.course_id
                    WHERE c.course_status = 1 AND c.isapprove = 1 AND l.id=" .$lectureid;
        $db->setQuery($query);
        $lecture = $db->loadObject();

        jslearnmanager::$_data['lecture'] = $lecture;
        jslearnmanager::$_data['lecturecustom'] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(2);
        return ;
    }

    function getLectureNameById($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT name FROM `#__js_learnmanager_course_section_lecture` WHERE id =" .$id;
        $db->setQuery($query);
        return $db->loadResult();
    }

    function getCourseLectureFiles($lectureid,$forapp=0){ //By Lecture Id
        if(!is_numeric($lectureid)) return false;

        //db Object
        $db = new jslearnmanagerdb();

        //data
        $query = "SELECT lfile.id as file_id, lfile.filename as filename, lfile.downloadable as downloadable, lfile.file_type as filetype, lfile.lecture_id as lecture_id, lfile.fileurl
                     FROM `#__js_learnmanager_lecture_file` AS lfile
                        WHERE lfile.file_type != 'video' AND  lfile.`lecture_id` =" .$lectureid;
        $db->setQuery($query);
        $files = $db->loadObjectList();
        if(isset($files)){
            if($forapp == 0){
                jslearnmanager::$_data['files'] = $files;
            }else{
                return $files;
            }
        }
        return ;
    }

    function getCourseLectureVideos($lectureid,$forapp=0){ //By Lecture Id
        if(!is_numeric($lectureid)) return false;

        //db Object
        $db = new jslearnmanagerdb();

        //data
        $query = "SELECT lfile.id as file_id, lfile.filename as filename, lfile.file_type as filetype, lfile.lecture_id as lecture_id, lfile.fileurl as videourl
                     FROM `#__js_learnmanager_lecture_file` AS lfile
                        WHERE lfile.file_type = 'video' AND  lfile.`lecture_id` =" .$lectureid;
        $db->setQuery($query);
        $files = $db->loadObjectList();
        if(isset($files)){
            if($forapp == 0){ // 0 For webview
                jslearnmanager::$_data['videofiles'] = $files;
            }else{
                return $files;
            }
        }
        return ;
    }

    function storeLectureCompletionProgress(){
        $id = JSLEARNMANAGERrequest::getVar('id');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $student_id = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
        $db = new jslearnmanagerdb();
        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($id);
        $isenroll = JSLEARNMANAGERincluder::getJSModel('course')->isStudentEnroll($uid,$courseid);
        $sectionid = JSLEARNMANAGERincluder::getJSModel('course')->getSectionByLectureId($id);
        $lectureprogress = array("progress"=>1, "lecture_id"=>$id, "section_id"=>$sectionid);
        if($isenroll){
            $query = "SELECT id, lecture_completion_params FROM `#__js_learnmanager_student_enrollment` WHERE student_id =" .$student_id. " AND course_id =" .$courseid;
            $db->setQuery($query);
            $studentcourse = $db->loadObject();
            $data['id'] = $studentcourse->id;
            $json = json_decode($studentcourse->lecture_completion_params,true);
            $match = false;
            if(is_array($json[$sectionid]) || $json[$sectionid] instanceof Countable){
                for($i=0; $i<count($json[$sectionid]); $i++){
                    if($json[$sectionid][$i]['lecture_id'] == $lectureprogress['lecture_id']){
                        $match = true;
                    }
                }
            }
            if(!$match){
                $json[$sectionid][] = $lectureprogress;
                $data['lecture_completion_params'] = json_encode($json);
                $row = JSLEARNMANAGERincluder::getJSTable('studentenrollment');
                if(!$row->bind($data)){
                    return JSLEARNMANAGER_SAVE_ERROR;
                }
                if(!$row->store()){
                    return JSLEARNMANAGER_SAVE_ERROR;
                }
                return $row->id;
            }
        }else{
            return false;
        }
    }


    function isLectureExistsinCourse($cid,$lid){
        if(!is_numeric($cid)) return false;
        if(!is_numeric($lid)) return false;

        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(l.id)
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_course_section` AS s ON c.id = s.course_id
                        INNER JOIN `#__js_learnmanager_course_section_lecture` AS l ON s.id = l.section_id
                        WHERE c.id =" .$cid. " AND l.id=".$lid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result > 0){
            return true;
        }else{
            return false;
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

    // Custom Field File code
    function uploadFileCustom($id,$courseid,$field,$layout){

        if($layout == 2){  // For upload lecture custom files
            $result = JSLEARNMANAGERincluder::getObjectClass('uploads')->storeCustomUploadFile($id,$courseid,$field,$layout);
        }
        return $result;
    }

    function storeUploadFieldValueInLectureParams($lectureid,$filename,$field){
        if( ! is_numeric($lectureid))
            return;
        $db = new jslearnmanagerdb();
        $query = "SELECT params FROM `#__js_learnmanager_course_section_lecture` WHERE id = ".$lectureid;
        $db->setQuery($query);
        $params = $db->loadResult();
        if(!empty($params)){
            $decoded_params = json_decode($params,true);
        }else{
            $decoded_params = array();
        }
        $decoded_params[$field] = $filename;
        $encoded_params = json_encode($decoded_params);
        $query = "UPDATE `#__js_learnmanager_course_section_lecture` SET params = '" . $encoded_params . "' WHERE id = " . $lectureid;
        $db->setQuery($query);
        $db->query();
        return;
    }

    function storeLecture($data){

        if(empty($data['name']))
            return false;
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $db = new jslearnmanagerdb();
        // Check by Lecture id (Course Owner or not)
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if($data['id'] != '' && is_numeric($data['id'])){
            $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($data['id']);
            $isOwner = JSLEARNMANAGERincluder::getJSModel('course')->getIfCourseOwner($courseid,$uid);
            if(!$isOwner && !is_admin()){
                return JSLEARNMANAGER_UNKNOW_INSTRUCTOR;
            }
        }else{
            // Check by section Id (Course Owner or not)
            $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdBySectionId($data['section_id']);
            $isOwner = JSLEARNMANAGERincluder::getJSModel('course')->getIfCourseOwner($courseid,$uid);
            if(!$isOwner && !is_admin()){
                return JSLEARNMANAGER_UNKNOW_INSTRUCTOR;
            }

            $query = "SELECT MAX(lecture_order) FROM `#__js_learnmanager_course_section_lecture` WHERE section_id = ". $data['section_id'] ."";
            $db->setQuery($query);
            $maxorder = $db->loadResult();
            $data['lecture_order'] = $maxorder + 1;
        }

        $canupdate = $this->validateFormData($data,3);
        if ($canupdate === JSLEARNMANAGER_ALREADY_EXIST)
            return JSLEARNMANAGER_ALREADY_EXIST;

        if($data['id'] == ''){
            $data['created_at'] = date_i18n('Y-m-d H:i:s');
            $data['updated_at'] = date_i18n('Y-m-d H:i:s');
            $data['alias'] = JSLEARNMANAGERincluder::getJSModel('common')->removeSpecialCharacter($data['name']);
        }

        if(isset($data['id'])){
            $data['updated_at'] = date_i18n('Y-m-d H:i:s');
        }

        //custom field code start
        $customflagforadd = false;
        $customflagfordelete = false;
        $custom_field_namesforadd = array();
        $custom_field_namesfordelete = array();
        $userfield = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getUserfieldsfor(2);
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
                $query = "SELECT params FROM `#__js_learnmanager_course_section_lecture` WHERE id = " . $data['id'];
                $db->setQuery($query);
                $oParams = $db->loadResult();
                if(!empty($oParams)){
                    $oParams = json_decode($oParams,true);
                    $unpublihsedFields = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getUserUnpublishFieldsfor(2);
                    foreach($unpublihsedFields AS $field){
                        if(isset($oParams[$field->field])){
                            $params[$field->field] = $oParams[$field->field];
                        }
                    }
                }
            }
        }


        $params = json_encode($params);
        $data['params'] = $params;

        $data['name'] = ucwords($data['name']);
        $row = JSLEARNMANAGERincluder::getJSTable('sectionlecture');
        if(!empty($data['description']))
			$data['description'] = wptexturize(stripslashes($data['description']));

        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        $lectureid = $row->id;

        //removing custom field attachments
        if($customflagfordelete == true){
            foreach ($custom_field_namesfordelete as $key) {
                $res = $this->removeFileCustom($lectureid,$key,2);
            }
        }

        //storing custom field attachments
        if($customflagforadd == true){
            foreach ($custom_field_namesforadd as $key) {
                if ($_FILES[$key]['size'] > 0) { // file
                    $res = $this->uploadFileCustom($lectureid,$courseid,$key,2);
                }
            }
        }
        do_action("jslm_quiz_store_quiz_questions",$data,$lectureid);
        if(!empty($data['video_url']) || isset($data['deletevideos'])){
            $this->storeVideoUrl($lectureid,$data);
        }
        if(!empty($data['filedata']) || isset($data['deletefiles'])){
            $this->storeLectureFile($lectureid,'filename',$data);
        }
        return array('lectureid' =>$lectureid, 'msg' => JSLEARNMANAGER_SAVED);
    }

    function storeLectureFile($id,$field,$data){
        if(!is_numeric($id)) return false;
        if(empty($data)) return false;
        if(!is_string($field)) return false;
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);


        $nooffilesallowed = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('max_allowed_lecturesfiles');
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($id);
        $isOwner = JSLEARNMANAGERincluder::getJSModel('course')->getIfCourseOwner($courseid,$uid);
        if(!$isOwner && !is_admin()){
            return JSLEARNMANAGER_UNKNOW_INSTRUCTOR;
        }


        $deltearray = array();
        $return = JSLEARNMANAGER_SAVE_ERROR;
        if(isset($data['deletefiles'])){
            $deltearray = array_filter($data['deletefiles']);
            if(!empty($deltearray)){
                $return = $this->deleteLectureFiles($deltearray,$id);
            }
        }

        // Store Files
        if(!empty(array_filter($_FILES[$field]['name']))){
            $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($id);
            $db = new jslearnmanagerdb();
            $query = "SELECT id,filename, file_type
                        FROM `#__js_learnmanager_lecture_file`
                            WHERE lecture_id=" .$id. " AND file_type != 'video'";
            $db->setQuery($query);

            $alloldfiles = $db->loadObjectList();
            $fileresult = array();
            // $totaluploadedfiles = count(array_filter(sanitize_file_name($_FILES[$field]['name']))_ + count($alloldfiles);
            $allowfilestoupload = $nooffilesallowed - count($alloldfiles);
            if(isset($data['filedata'])){
                $totaluploadedfiles = count($data['filedata']); // uploadedfiles
                foreach($_FILES[$field]['tmp_name'] as $uploadfile => $value){
                    $upload = false;
                    $file = array(
                    'name'     => sanitize_file_name($_FILES[$field]['name'][$uploadfile]),
                    'type'     => filter_var($_FILES[$field]['type'][$uploadfile], FILTER_SANITIZE_STRING),
                    'tmp_name' => filter_var($_FILES[$field]['tmp_name'][$uploadfile], FILTER_SANITIZE_STRING),
                    'error'    => filter_var($_FILES[$field]['error'][$uploadfile], FILTER_SANITIZE_STRING),
                    'size'     => filter_var($_FILES[$field]['size'][$uploadfile], FILTER_SANITIZE_STRING)
                    );
                    foreach($data['filedata'] as $allfiledata => $filevalue){
						if($file['name'] == $filevalue){	
                            $upload = true;
                            unset($data['filedata'][$allfiledata]);
                            break;
                        }
                    }
                    if($upload){
                        if($allowfilestoupload < $totaluploadedfiles){
                            if($allowfilestoupload < $uploadfile + 1){
                                $return = JSLEARNMANAGER_LIMIT_EXCEED;
                                JSLEARNMANAGERmessages::$counter = $totaluploadedfiles - $allowfilestoupload;
                                $msg = JSLEARNMANAGERMessages::getMessage(JSLEARNMANAGER_LIMIT_EXCEED, 'lecture');
                                JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
                                break;
                            }
                        }
                        if(!empty($file['name']) && $file['name'] != ''){
                            $fileresult[] = JSLEARNMANAGERincluder::getObjectClass('uploads')->learnManagerUpload($id,$courseid,$file,2);
                        }
                        if ($fileresult[0] == 6){
                            $msg = JSLEARNMANAGERMessages::getMessage(JSLEARNMANAGER_FILE_TYPE_ERROR, '');
                            JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
                        }
                        if($fileresult[0] == 5){
                            $msg = JSLEARNMANAGERMessages::getMessage(JSLEARNMANAGER_FILE_SIZE_ERROR, '');
                            JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
                        }
                    }
                }
                if(count($fileresult)>0){
                    foreach ($fileresult as $newfile) {
                        if(is_array($newfile) && !empty($newfile)){
                            $temp_file_name = basename( $newfile[0] );
                            $insert = true;
                            foreach ($alloldfiles as $oldfile) {
                                if($temp_file_name == $oldfile->filename){
                                    $insert = false;
                                    break;
                                }
                            }
                            if($insert){
                                $return = $this->storeLectureFileRecord($id,$newfile,$temp_file_name);
                            }else{
                                $return = JSLEARNMANAGER_SAVE_ERROR;
                            }
                        }
                    }
                }
            }
        }
        return $return;
    }

    function deleteLectureFiles($filearray,$lectureid) {
        $db = new jslearnmanagerdb();
        $row = JSLEARNMANAGERincluder::getJSTable('lecturefiles');
        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($lectureid);
        $return = 0;
        foreach ($filearray as $key => $val) {
            if(is_numeric($val)){
                $query = "SELECT filename
                        FROM `#__js_learnmanager_lecture_file`
                        WHERE id = " . $val;
                $db->setQuery($query);
                $lecturefile = $db->loadObject();
                // path to file so that it can be removed
                $wpdir = wp_upload_dir();
                $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                $path = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$courseid.'/lecture_'.$lectureid. '/';
                if(!empty($lecturefile)){
                    $filename = $path.$lecturefile->filename;
                    if(file_exists($filename)){
                        unlink($filename);
                    }
                }
                if($row->delete($val)){
                    $return += 1;
                }else{
                    $return = 0;
                }

            }
        }
        if($return > 0){
            return JSLEARNMANAGER_SAVED;
        }else{
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        return;
    }

    function deleteVideos($filearray){
        $db = new jslearnmanagerdb();
        $row = JSLEARNMANAGERincluder::getJSTable('lecturefiles');
        $return = 0;
        foreach ($filearray as $key => $val) {
           if(is_numeric($val)){
                $row->delete($val);
                $return += 1;
            }else{
                $return = -1;
            }
        }
        return $return;
    }

    function storeLectureFileRecord($lectureid,$image,$name){
        if(is_numeric($lectureid)){
            $row = JSLEARNMANAGERincluder::getJSTable('lecturefiles');
            $data['lecture_id'] = $lectureid;
            $data['fileurl'] = $image[2];
            $data['filename'] = $name;
            $data['file_type'] = $image[1];
            $data['status'] = 1;
            $data['created'] = date_i18n('Y-m-d H:i:s');
            $row->bind($data);
            $row->store();
            return JSLEARNMANAGER_SAVED;
        }
        return;
    }

    function storeVideoUrl($lectureid,$data){
        if(empty($data))
            return false;

        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        //delete video url
        $videodelete = 0;
        if(!empty($data['deletevideos'])){
            $deltevideos = array_filter($data['deletevideos']);
            $videodelete = $this->deleteVideos($deltevideos); // name = video_url[][filename];
        }

        $return = 0;
        $counter = 0;
        if(!empty($data['video_url'])){

            $videotitle = $data['video_title'];
            $data = $data['video_url'];
            $db = new jslearnmanagerdb();
            $query = "SELECT fileurl FROM `#__js_learnmanager_lecture_file` WHERE file_type='video' AND lecture_id=" .$lectureid;
            $db->setQuery($query);
            $alloldurl = $db->loadObjectList();
            foreach($data as $videourl => $url){
                $insert = true;
                foreach ($alloldurl as $oldurl) {
                    if($url['fileurl'] == $oldurl->fileurl){
                        $insert = false;
                        $counter++;
                        break;
                    }
                }

                if($insert){
                    if(!isset($url['created_at'])){
                        $url['created_at'] = date_i18n('Y-m-d H:i:s');
                        $url['updated_at'] = date_i18n('Y-m-d H:i:s');
                    }
                    if(!empty($videotitle[$videourl]['filename'])){
                        $url['filename'] = $videotitle[$videourl]['filename'];
                    }else{
                        $url['filename'] = "";
                    }
                    $url['file_type'] = 'video';
                    $url['lecture_id'] = $lectureid;

                    $row = JSLEARNMANAGERincluder::getJSTable('lecturefiles');
                    $url = filter_var_array($url, FILTER_SANITIZE_STRING);
                    if (!$row->bind($url)){
                        $return = 0;
                        $error[] = jslearnmanager::$_db->last_error;
                    }
                    if (!$row->store()){
                        $return = 0;
                        $error[] = jslearnmanager::$_db->last_error;
                    }
                    $return += 1;
                }else{
                    $return = 0;
                }
            }
        }

        if($return > 0  && $videodelete >= 0 && $counter == 0){
            return JSLEARNMANAGER_SAVED;
        }elseif($counter > 0){
            JSLEARNMANAGERmessages::$counter = $counter;
            $msg = JSLEARNMANAGERMessages::getMessage(JSLEARNMANAGER_ALREADY_EXIST, 'lecture');
            JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
        }else{
            return JSLEARNMANAGER_SAVE_ERROR;
        }
    }

    function removeFileCustom($id,$key,$layout){ // For remove course custom files
        $filename = str_replace(' ', '_', $key);
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        if($layout == 2){ //For remove lecture custom files
            $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($id);
            $path = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$courseid.'/lecture_'.$id. '/' ;
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
            $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($id);
            $path = $wpdir['basedir'] . '/' . $data_directory . '/data/course/course_' .$courseid.'/lecture_'.$id. '/' ;
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

    function getTotalLecturesArray($courseid){
        if(!is_numeric($course_id)) return false;

        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(sec_l.id) AS count_2
                    FROM `#__js_learnmanager_course` AS c
                    INNER JOIN
                     `#__js_learnmanager_course_section` AS sec ON c.id = sec.course_id
                    INNER JOIN
                      `#__js_learnmanager_course_section_lecture` AS sec_l ON sec.id = sec_l.section_id
                    WHERE
                      c.id =" .$courseid;
        $query .= " GROUP BY c.id";
        $db->setQuery($query);
        $totallectures['total'] = $db->loadResult();
        $query = "SELECT COUNT(sec_l.id) as count , sec.id as section
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_course_section` AS sec On c.id = sec.course_id
                        INNER JOIN `#__js_learnmanager_course_section_lecture` AS sec_l ON sec.id = sec_l.section_id
                        WHERE c.id =" .$courseid;
        $query.= " GROUP BY sec.id";
        $db->setQuery($query);
        $totallectures['lecturelist'] = $db->loadObjectList();
        return $totallectures;
    }

    function getTotalLecturesFile($courseid){
        if(!is_numeric($courseid)) return false;

        $db = new jslearnmanagerdb();

        $query = "SELECT COUNT(fl.lecture_id) as count , sec_l.id as lecture
                    FROM `#__js_learnmanager_course` AS c
                        INNER JOIN `#__js_learnmanager_course_section` AS sec On c.id = sec.course_id
                        INNER JOIN `#__js_learnmanager_course_section_lecture` AS sec_l ON sec.id = sec_l.section_id
                        INNER JOIN `#__js_learnmanager_lecture_file` AS fl ON sec_l.id = fl.lecture_id
                        WHERE c.id =" .$courseid;
        $query.=" GROUP BY sec_l.id";
        $db->setQuery();
        $totalfiles = $db->loadObjectList();
        return $totalfiles;

    }

    function lectureCanDelete($lectureid){
        if(!is_numeric($lectureid))
            return false;

        $db = new jslearnmanagerdb();
        $query = "SELECT
                    (
                    SELECT COUNT(sec_l.id)
                        FROM `#__js_learnmanager_course_section_lecture` AS sec_l
                            INNER JOIN `#__js_learnmanager_course_section` AS sec ON sec_l.section_id = sec.id
                            INNER JOIN `#__js_learnmanager_course` AS c ON sec.course_id = c.id
                            INNER JOIN `#__js_learnmanager_student_enrollment` AS sc ON sc.course_id = c.id
                            WHERE sec_l.id =" .$lectureid. "
                    )+
                    (
                    SELECT COUNT(sec_l.id)
                        FROM `#__js_learnmanager_course_section_lecture` AS sec_l
                            INNER JOIN `#__js_learnmanager_course_section` AS sec ON sec_l.section_id = sec.id
                            INNER JOIN `#__js_learnmanager_course` AS c ON sec.course_id = c.id
                            INNER JOIN `#__js_learnmanager_wishlist` AS w ON w.course_id = c.id
                            WHERE sec_l.id =" .$lectureid. "
                    )";
        $db->setQuery($query);
        $lectures = $db->loadResult();
        if($lectures > 0)
            return false;
        else
            return true;

    }

    function deleteFilebyId($ids){
        if(empty($ids))
            return false;
        $notdeleted = 0;
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data';

        $db = new jslearnmanagerdb();
        $row = JSLEARNMANAGERincluder::getJSTable('lecturefiles');
        foreach ($ids as $id) {
            if(is_numeric($id))
            {
                $filedata = $this->getFileDatabyId($id);
                if(!$row->delete($id)){
                    $notdeleted += 1;
                }else{
                    $filepath = $path . '/course/course_' . $filedata[0]->course_id .'/lecture_' . $filedata[0]->lecture_id;
                    $file = $filepath . '/' .$filedata[0]->filename;
                    if(is_file($file)){
                        unlink($file);
                    }
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

    function getFileDatabyId($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT lf.filename , sec_l.id as lecture_id, c.id as course_id
                    FROM `#__js_learnmanager_lecture_file` AS lf
                        INNER JOIN `#__js_learnmanager_course_section_lecture` AS sec_l ON sec_l.id = lf.lecture_id
                        INNER JOIN `#__js_learnmanager_course_section` AS sec ON sec.id = sec_l.section_id
                        INNER JOIN `#__js_learnmanager_course` AS c ON c.id = sec.course_id
                        WHERE lf.id =" .$id;
        $db->setQuery($query);
        $filedata = $db->loadObjectList();
        return $filedata;
    }

    function getFileIdbyLectureId($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT lf.id
                    FROM `#__js_learnmanager_course_section_lecture` AS sec
                    INNER JOIN `#__js_learnmanager_lecture_file` AS lf ON sec.id = lf.lecture_id AND sec.id =" .$id;
        $db->setQuery($query);
        $fileids=$db->loadObjectList();
        $allfileids[] = $fileids;

        $allfileids = call_user_func_array('array_merge', $allfileids);
        foreach ($allfileids as $value){
            $filesArray[] = $value->id;
        }
        if(isset($filesArray))
            return $filesArray;
        else
            return;
    }

    function deleteLectureById($id,$cid){
        if(!is_numeric($id))
            return false;
        if(!is_numeric($cid))
            return false;
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data';

        $notdeleted = 0;
        $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
        $isOwner = JSLEARNMANAGERincluder::getJSModel('course')->getIfCourseOwner($cid,$uid);
        if(is_admin()){
            $isOwner = true;
        }
        if($isOwner == true){
            $row = JSLEARNMANAGERincluder::getJSTable('sectionlecture');
            $db = new jslearnmanagerdb();
            do_action("jslm_quiz_select_query_data_for_delete_course_quiz","lecture","lecturequiz");
            $query = "DELETE  ".jslearnmanager::$_addon_query['select']." lecturefiles
                        FROM `#__js_learnmanager_course_section_lecture` AS lecture
                            JOIN `#__js_learnmanager_lecture_file` AS lecturefiles ON lecture.id = lecturefiles.lecture_id
                            ".jslearnmanager::$_addon_query['join']."
                            WHERE lecture.id =" .$id;
            $db->setQuery($query);
            do_action("reset_jslmaddon_query");
            if($db->query()){
                if(!$row->delete($id)){
                    $notdeleted += 1;
                }
                $filepath = $path . '/course/course_' . $cid .'/lecture_' . $id;
                $files = glob( $filepath . '/*');
                foreach($files as $file){
                    if(is_file($file)) unlink($file);
                }
                if(is_dir($filepath)){
                    rmdir($filepath);
                    $notdeleted = 0;
                }
            }else{
                $notdeleted += 1;
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

    function getLectureIdbySectionId($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT sec_l.id
                    FROM `#__js_learnmanager_course_section` AS sec
                    INNER JOIN `#__js_learnmanager_course_section_lecture` AS sec_l ON sec.id = sec_l.section_id AND sec.id =" .$id;
        $db->setQuery($query);
        $lectureids=$db->loadObjectList();
        $alllectureids[] = $lectureids;
        $alllectureids = call_user_func_array('array_merge', $alllectureids);
        foreach ($alllectureids as $value)
            $lecturesArray[] = $value->id;
        if(isset($lecturesArray))
            return $lecturesArray;
        else
            return;
    }

    function isAlreadyExist($data,$id) {
        $db = new jslearnmanagerdb;
        if($id == 3){ // for lecture form
            $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course_section_lecture` WHERE name = '" . $data['name'] . "' AND section_id ='".$data['section_id']."'";
        }else if ($id == 4) {
            $query = apply_filters("jslm_quiz_query_for_check_question_exists_or_not",1,$data);
        }
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function lectureForForm($id){
        if(is_numeric($id)){
            $db = new jslearnmanagerdb();
            $query = "SELECT * FROM `#__js_learnmanager_course_section_lecture` WHERE id=" .$id;
            $db->setQuery($query);
            $lecture = $db->loadObject();
            if(isset($lecture)){
                jslearnmanager::$_data[0] = $lecture;
            }
        }
        $fieldorderings = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingByFor(2);
        jslearnmanager::$_data[3] = $fieldorderings;
        return;
    }

    function sort_marks($a,$subkey) {
        foreach($a as $k=>$v) {
            $b[$k] = strtolower($v[$subkey]);
        }
        asort($b,SORT_NUMERIC);
        foreach($b as $key=>$val) {
            $c[] = $a[$key];
        }
        return $c;
    }

    function calculateGrade($marks){
        if(!is_numeric($marks))
            return false;
        switch(true){
            case ($marks >= 90):
                $grade = "Excellent";
            break;
            case ($marks >= 80):
                $grade = "Very Good";
            break;
            case ($marks >= 70):
                $grade = "Good";
            break;
            case ($marks >= 60):
                $grade = "Better";
            break;
            case ($marks >= 55):
                $grade = "Average";
            break;
            case ($marks >= 50):
                $grade = "Just Passed";
            break;
            case ($marks < 50):
                $grade = "Failed";
            break;
        }
        return $grade;
    }

    // App Data
    function getLectureByIdForApp($id,$uid){
        if(!is_numeric($id))
            return false;
        $lecturedata = array();
        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($id);
        $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
        $db = new jslearnmanagerdb();
        $query = "SELECT l.name as lecturetitle, l.description as description , s.name as sectiontitle, l.params , l.id as lectureid
                    FROM `#__js_learnmanager_course_section_lecture` AS l
                        INNER JOIN `#__js_learnmanager_course_section` AS s ON s.id = l.section_id
                        INNER JOIN `#__js_learnmanager_course` AS c ON c.id = s.course_id
                    WHERE c.course_status = 1 AND c.isapprove = 1 AND l.id=" .$id;
        $db->setQuery($query);
        $lecture = $db->loadObject();
        $lecturedata['lecture'] = $lecture;
        if(!empty($lecturedata['lecture'])){
            $lecturedata['lecture']->description = strip_tags($lecturedata['lecture']->description);
            $lecturedata['lecture']->params = json_decode($lecturedata['lecture']->params);
            $lecturedata['filesdata'] = $this->getCourseLectureFiles($id,1);
            $lecturedata['videosdata'] = $this->getCourseLectureVideos($id,1);
            $data = apply_filters("jslm_quiz_question_for_lecture_add_detail",'',$id,1);
            if($data != ''){
                for($i=0; $i<count($data); $i++){
                    $lecturedata['questiondata'][$i] = $data[$i];
                    $lecturedata['questiondata'][$i]->params = json_decode($data[$i]->params);
                }
            }
            if($uid != 0 && $uid != ''){
                $quizresult = apply_filters("jslm_quiz_lecture_quiz_result_for_app",'',$id,$courseid,$studentid);
                if(!empty($quizresult)){
                    $lecturedata['quizresult'] = $quizresult;
                }
            }
        }
        return $lecturedata;
    }

    function getVideoByIdForApp($id){
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT lfile.id as file_id, lfile.filename as filename, lfile.file_type as filetype, lfile.lecture_id as lecture_id, lfile.fileurl as videourl
                    FROM `#__js_learnmanager_lecture_file` AS lfile
                        WHERE lfile.file_type = 'video' AND  lfile.`id` =" .$id;
        $db->setQuery($query);
        return $db->loadObject();
    }

    function getCourseLecturesForApp($sectionid){ //By section Id
        if(!is_numeric($sectionid)) return false;

        //db Object
        $db = new jslearnmanagerdb();

        //data
        $query = "SELECT lec.id as lecture_id, lec.name as lecture_name, lec.section_id as section_id
                    FROM `#__js_learnmanager_course_section_lecture` AS lec
                        WHERE lec.section_id =" .$sectionid;
        $query .= " ORDER BY lec.lecture_order ASC";
        $db->setQuery($query);
        $lectures = $db->loadObjectList();
        return $lectures;
    }

    function getCourseLectures($sectionid){ //By section Id
        if(!is_numeric($sectionid)) return false;

        //db Object
        $db = new jslearnmanagerdb();

        //data
        $query = "SELECT lec.id as lecture_id, lec.name as lecture_name, lec.description as description, lec.section_id as section_id
                    FROM `#__js_learnmanager_course_section_lecture` AS lec
                        WHERE lec.section_id =" .$sectionid;
        $query .= " ORDER BY lec.lecture_order ASC";
        $db->setQuery($query);
        $lectures = $db->loadObjectList();
        return $lectures;
    }

    function getAllDownloads($downloadid){
        if(!class_exists('PclZip')){
            require_once(ABSPATH . "wp-admin" . '/includes/class-pclzip.php');
        }
        $path = jslearnmanager::$_path;
        $path .= 'zipdownloads';
        JSLEARNMANAGERincluder::getJSModel('common')->makeDir($path);
        $randomfolder = 'lecture_'.$downloadid;
        $path .= '/' . $randomfolder;
        JSLEARNMANAGERincluder::getJSModel('common')->makeDir($path);
        $archive = new PclZip($path . '/alldownloads.zip');

        $datadirectory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $maindir = wp_upload_dir();
        $jpath = $maindir['basedir'];
        $jpath = $jpath .'/'.$datadirectory;
        $courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($downloadid);
        $directory = $jpath . '/data/course/course_'.$courseid.'/lecture_' . $downloadid;
        if(!is_dir($directory))
                return false;
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        $filelist = '';
        foreach ($scanned_directory AS $file) {
            $filelist .= $directory . '/' . $file . ',';
        }
        $filelist = substr($filelist, 0, strlen($filelist) - 1);
        $v_list = $archive->create($filelist, PCLZIP_OPT_REMOVE_PATH, $directory);
        if ($v_list == 0) {
            die("Error : '" . $archive->errorInfo() . "'");
        }
        $file = $path . '/alldownloads.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        @unlink($file);
        $path = jslearnmanager::$_path;
        $path .= 'zipdownloads';
        $path .= '/' . $randomfolder;
        @unlink($path . '/index.html');
        rmdir($path);
        exit();
    }

    function getMessagekey(){
        $key = 'lecture';
        if(is_admin()){
            $key = 'admin_'.$key;
        }
        return $key;
    }
}
?>
