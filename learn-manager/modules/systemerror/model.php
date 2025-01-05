<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERsystemerrorModel {

    function getSystemErrors() {
        $db = new jslearnmanagerdb();
        $inquery = '';
        // Pagination
        $query = "SELECT COUNT(`id`) FROM `#__js_learnmanager_system_errors`";
        $query .= $inquery;
        $db->setquery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        // Data
        $query = " SELECT systemerror.*
					FROM `#__js_learnmanager_system_errors` AS systemerror ";
        $query .= $inquery;
        $query .= " ORDER BY systemerror.created DESC LIMIT " . JSLEARNMANAGERpagination::$_offset . ", " . JSLEARNMANAGERpagination::$_limit;
        $db->setquery($query);
        jslearnmanager::$_data[0] = $db->loadObjectList();

        return;
    }

    function addSystemError($error) {
        $query_array = array('error' => $error,
            'uid' => get_current_user_id(),
            'isview' => 0,
            'created' => date("Y-m-d H:i:s")
        );
        $db = new jslearnmanagerdb();
        $db->_insert('system_errors',$query_array);
        return;
    }

    function getMessagekey(){
        $key = 'systemerror';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }
}

?>