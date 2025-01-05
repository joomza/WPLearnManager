<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERlanguageModel {

    function getlanguageForCombo() {
        $db = new jslearnmanagerdb();
        $query = "SELECT id, language AS text FROM `#__js_learnmanager_language` WHERE status = 1";
        $db->setQuery($query);
        $alllanguage= $db->loadObjectList();
        return $alllanguage;
    }

    function getLanguageforForm($id) {
        $db = new jslearnmanagerdb;
        if ($id) {
            if (is_numeric($id) == false) return false;
            $query = "SELECT l.*
                        FROM `#__js_learnmanager_language` AS l
                        WHERE l.id = " . $id;
            $db->setQuery($query);
            $language = $db->loadObject();
            if (isset($language)){
               jslearnmanager::$_data[0] = $language;
            }
        }
        return;
    }

    function storeLanguage($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data,1);
        if ($canupdate === JSLEARNMANAGER_ALREADY_EXIST)
            return JSLEARNMANAGER_ALREADY_EXIST;
        $row = JSLEARNMANAGERincluder::getJSTable('courselanguage');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        return JSLEARNMANAGER_SAVED;
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
        }else{
            $canupdate = true;
        }
        return $canupdate;
    }

    function isAlreadyExist($data,$id) {
        $db = new jslearnmanagerdb;
        if($id == 1){ // For language form
            $query = "SELECT COUNT(id) FROM `#__js_learnmanager_language` WHERE language = '" . $data['language'] ."'";
        }$db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getAllLanguages(){

        $language = isset(jslearnmanager::$_data['filter']['language']) ? jslearnmanager::$_data['filter']['language'] : '';
        $status = isset(jslearnmanager::$_data['filter']['status']) ? jslearnmanager::$_data['filter']['status'] : '';
        // $code = JSLEARNMANAGERrequest::getVar('code');
        $inquery = '';
        $clause = ' WHERE ';
        if ($language != null) {
            $inquery .= $clause . "language LIKE '%" . $language . "%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .= $clause . " status = " . $status;
        
        jslearnmanager::$_data['filter']['language'] = $language;
        jslearnmanager::$_data['filter']['status'] = $status;
        //Pagination
        $db = new jslearnmanagerdb();
        $query = "SELECT count(id) FROM `#__js_learnmanager_language` ";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);
        //Data
        $query = "SELECT * FROM `#__js_learnmanager_language` $inquery ORDER BY language ASC ";
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset . ", " . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObjectList();

        return;
    }

    function deleteLanguages($ids) {
        if (empty($ids))
            return false;
        $row = JSLEARNMANAGERincluder::getJSTable('courselanguage');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if(is_numeric($id)){
                if ($this->languageCanDelete($id) == true) {
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
    }

    function languageCanDelete($languageid) {
        if (is_numeric($languageid) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE language = " . $languageid . ") AS total ";
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

        $row = JSLEARNMANAGERincluder::getJSTable('courselanguage');
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
        $key = 'language';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

   function getAdminLanguageSearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $language = ($isadmin) ? 'language' : 'jslm_language';
        $jslms_search_array['language'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($language)));
        $jslms_search_array['status'] = trim(JSLEARNMANAGERrequest::getVar('status' , ''));
        $jslms_search_array['search_from_language'] = 1;
        return $jslms_search_array;
    }





}

?>
