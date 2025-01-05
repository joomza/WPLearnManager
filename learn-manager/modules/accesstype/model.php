<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERAccessTypeModel {

    function getaccesstypeForCombo() {
        $db = new jslearnmanagerdb();
        $query = "SELECT id, access_type AS text FROM `#__js_learnmanager_course_access_type` WHERE status = 1";
        $db->setQuery($query);
        $alltypes= $db->loadObjectList();
        return $alltypes;
    }

    function getaccesstypeIdByName($name) {
        $db = new jslearnmanagerdb();
        $query = "SELECT id FROM `#__js_learnmanager_course_access_type` WHERE status = 1 AND access_type = '" .$name . "'";
        $db->setQuery($query);
        $accesstypeid= $db->loadResult();
        return $accesstypeid;
    }

    function getAllAccessTypes(){
        $db = new jslearnmanagerdb();
        $query = "SELECT id,access_type FROM `#__js_learnmanager_course_access_type` WHERE status = 1";
        $db->setQuery($query);
        $accesstypes= $db->loadObjectList();
        jslearnmanager::$_data[0] = $accesstypes;
    }

    function getCourseAccessType($cid){
        if(!is_numeric($cid)) return '';

        $db = new jslearnmanagerdb;
        $query = "SELECT ca.access_type
            FROM `#__js_learnmanager_course` AS c
                INNER JOIN `#__js_learnmanager_course_access_type` AS ca ON ca.id = c.access_type
            WHERE c.id = ".$cid;
        $db->setQuery($query);
        $row = $db->loadResult();

        $access_type = $row;
        return $access_type;
    }
    
    function getaccesstypeByIdAjax() {
        $id = JSLEARNMANAGERrequest::getVar('id','');
        if(!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT access_type FROM `#__js_learnmanager_course_access_type` WHERE status = 1 AND id = '" .$id . "'";
        $db->setQuery($query);
        $accesstype= $db->loadResult();
        return $accesstype;
    }

    function getMessagekey(){
        $key = 'access type';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

}

?>
