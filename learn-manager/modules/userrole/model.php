<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERUserRoleModel {

    private $_param_array;

    function getRoleNamebyId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT role FROM `#__js_learnmanager_user_role` WHERE id = " . $id;
        $db->setQuery($query);
        return $db->loadResult();
    }

    function getRoleIdbyName($role) {
        if (!is_string($role))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT id FROM `#__js_learnmanager_user_role` WHERE role = " . '"'.$role.'"';
        $db->setQuery($query);
        return $db->loadResult();
    }

    function sorting() {
        jslearnmanager::$_data['sorton'] = JSLEARNMANAGERrequest::getVar('sorton', 'post', 1);
        jslearnmanager::$_data['sortby'] = JSLEARNMANAGERrequest::getVar('sortby', 'post', 2);
        switch (jslearnmanager::$_data['sorton']) {
            case 1: // created
                $sort_string = ' ur.role ';
                break;
            case 2: // price
                $sort_string = ' ur.status ';
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

    function getRoleforCombo(){

        $db = new jslearnmanagerdb();
        $query = "SELECT role as text, id FROM `#__js_learnmanager_user_role`";
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function getRoleforCombobyName($name){

        $db = new jslearnmanagerdb();
        $query = "SELECT role as text, id FROM `#__js_learnmanager_user_role` WHERE role = " . '"'.$name.'"';
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function getAllRoles($datafor){
        // sorting
        $sort_string = $this->sorting();
        // DB Object
        $db = new jslearnmanagerdb();
        // Filter

        $status = JSLEARNMANAGERrequest::getVar('status');
        $role = JSLEARNMANAGERrequest::getVar('role');
        $formsearch = JSLEARNMANAGERrequest::getVar('JSLEARNMANAGER_form_search', 'post');
        if ($formsearch == 'JSLEARNMANAGER_SEARCH') {
            $_SESSION['JSLEARNMANAGER_SEARCH']['status'] = $status;
            $_SESSION['JSLEARNMANAGER_SEARCH']['role'] = $role;
        }

        if (JSLEARNMANAGERrequest::getVar('pagenum', 'get', null) != null) {
            $status = (isset($_SESSION['JSLEARNMANAGER_SEARCH']['status']) && $_SESSION['JSLEARNMANAGER_SEARCH']['status'] != '') ? filter_var($_SESSION['JSLEARNMANAGER_SEARCH']['status'], FILTER_SANITIZE_STRING) : null;
            $role = (isset($_SESSION['JSLEARNMANAGER_SEARCH']['role']) && $_SESSION['JSLEARNMANAGER_SEARCH']['role'] != '') ? filter_var($_SESSION['JSLEARNMANAGER_SEARCH']['role'], FILTER_SANITIZE_STRING) : null;
        }elseif ($formsearch !== 'JSLEARNMANAGER_SEARCH') {
            unset($_SESSION['JSLEARNMANAGER_SEARCH']);
        }

        if($datafor == 1){
            $status_opr = (is_numeric($status)) ? ' = '.$status : ' <> 0 ';
            $inquery = " WHERE ur.status".$status_opr;
        }
        if (is_string($role)){
            $inquery .= " AND ur.role LIKE '%" . $role . "%'";
        }

        jslearnmanager::$_data['filter']['status'] = $status;
        jslearnmanager::$_data['filter']['role'] = $role;
        $query = "SELECT COUNT(ur.id)
                    FROM `#__js_learnmanager_user_role` AS ur
                    ";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        $query = "SELECT *
                    FROM `#__js_learnmanager_user_role` AS ur
                    ";
        $query .= $inquery;
        $db->setQuery($query);
        $result = $db->loadObjectList();
        jslearnmanager::$_data[0] = $result;

        return;
    }

    function getRoleByUid($uid){
        if(!is_numeric($uid))
            return false;
        $db = new jslearnmanagerdb;
        $query = "SELECT role FROM `#__js_learnmanager_user_role` AS r
                    INNER JOIN `#__js_learnmanager_user` AS u ON r.id = u.user_role_id
                    WHERE u.id =" .$uid;
        $db->setQuery($query);
        $role = $db->loadObject();
        return $role;
    }


    function getMessagekey(){
        $key = 'userrole';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }
}
?>
