<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcourselevelModel {

    function getLevelbyId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_course_level` WHERE id = " . $id;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObject();
        return;
    }

    function getLevelForCombo() {
        $db = new jslearnmanagerdb();
        $query = "SELECT id, level AS text FROM `#__js_learnmanager_course_level` WHERE status = 1";
        $db->setQuery($query);
        $alllevel= $db->loadObjectList();
        return $alllevel;
    }

    function storeCourseLevel($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === JSLEARNMANAGER_ALREADY_EXIST)
            return JSLEARNMANAGER_ALREADY_EXIST;
        if($data['id'] == ""){
            $data['created_at'] = date_i18n('Y-m-d H:i:s');
        }
        $row = JSLEARNMANAGERincluder::getJSTable('courselevel');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        return JSLEARNMANAGER_SAVED;
    }

    function validateFormData(&$data) {
        $canupdate = false;
        $db = new jslearnmanagerdb;
        if ($data['id'] == '') {
            $result = $this->isAlreadyExist($data);
            if ($result == true) {
                return JSLEARNMANAGER_ALREADY_EXIST;
            } else {
                $canupdate = true;
            }
        }else{
            $canupdate = true;
        }
        return $canupdate;
    }

    function isAlreadyExist($data) {
        $db = new jslearnmanagerdb;
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_course_level` WHERE level = '" . $data['level'] . "'";
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getAllLevels() {
        // Filter
        $isadmin = is_admin();
        $level = ($isadmin) ? 'level' : 'jslm_courselevel';
        $title = isset(jslearnmanager::$_data['filter']['level']) ? jslearnmanager::$_data['filter']['level'] : '';
        $status = isset(jslearnmanager::$_data['filter']['status']) ? jslearnmanager::$_data['filter']['status'] : '';
        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            $inquery .= $clause . "level LIKE '%" . $title . "%'";
            $clause = " AND ";
        }
        if(is_numeric($status)){
            $inquery .= $clause . " status = " .$status;
            $clause = " AND ";
        }
        jslearnmanager::$_data['filter']['level'] = $title;
        jslearnmanager::$_data['filter']['status'] = $status;
        //Pagination
        $db = new jslearnmanagerdb();
        $query = "SELECT count(id) FROM `#__js_learnmanager_course_level` ";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);
        //Data
        $query = "SELECT * FROM `#__js_learnmanager_course_level` $inquery ";
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset . ", " . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObjectList();
        return;
    }

    function deleteLevels($ids) {
        if (empty($ids))
            return false;
        $row = JSLEARNMANAGERincluder::getJSTable('courselevel');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if(is_numeric($id)){
                if ($this->levelCanDelete($id) == true) {
                    if (!$row->delete($id)) {
                        $notdeleted += 1;
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
        return;
    }

    function levelCanDelete($levelid) {
        if (is_numeric($levelid) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE course_level = " . $levelid . ") AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSLEARNMANAGERincluder::getJSTable('courselevel');
        $total = 0;
        foreach ($ids as $id) {
            if(is_numeric($id)){
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }else{
                $total += 1;
            }
        }
        if ($total == 0) {
            JSLEARNMANAGERmessages::$counter = false;
            if ($status == 1)
                return JSLEARNMANAGER_PUBLISHED;
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

    function getMessagekey(){
        $key = 'level';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

    function getAdminCourselevelSearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $level = ($isadmin) ? 'level' : 'jslm_courselevel';
        $jslms_search_array['level'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($level)));
        $jslms_search_array['status'] = trim(JSLEARNMANAGERrequest::getVar('status' , ''));
        $jslms_search_array['search_from_courselevel'] = 1;
        return $jslms_search_array;
    }



}

?>
