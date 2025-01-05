<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcountryModel {

    function storeCountry($data) {
        if (empty($data))
            return false;

        if ($data['id'] == '') {
            $result = $this->isCountryExist($data['country_name']);
            if ($result == true) {
                return JSLEARNMANAGER_ALREADY_EXIST;
            }
        }

        $row = JSLEARNMANAGERincluder::getJSTable('country');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }

        return JSLEARNMANAGER_SAVED;
    }

    function getCountrybyId($id) {
        if (!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_country` WHERE id = " . $id;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObject();
        return;
    }

    function getCountriesForCombo(){

        $db = new jslearnmanagerdb();
        $query = "SELECT id, country_name as text FROM `#__js_learnmanager_country` WHERE isenable = 1";
        $db->setQuery($query);
        $countries = $db->loadObjectList();
        return $countries;
    }

    function getAllCountries() {
        $isadmin = is_admin();
        $catname = ($isadmin) ? 'countryname' : 'jslm_country';
        $pagesize =  absint(JSLEARNMANAGERrequest::getVar('pagesize',''));
        $countryname = isset(jslearnmanager::$_data['filter']['countryname']) ? jslearnmanager::$_data['filter']['countryname'] : '';
        $status = isset(jslearnmanager::$_data['filter']['status']) ? jslearnmanager::$_data['filter']['status'] : '';
        $inquery = '';
        $clause = ' WHERE ';
        if ($countryname) {
            $inquery .= $clause . "  country.country_name LIKE '%" . $countryname . "%' ";
            $clause = " AND ";
        }
        if (is_numeric($status)) {
            $inquery .= $clause . " country.isenable = " . $status;
            $clause = " AND ";
        }


        jslearnmanager::$_data['filter']['countryname'] = $countryname;
        jslearnmanager::$_data['filter']['status'] = $status;
        $db = new jslearnmanagerdb();
        // Pagination
        $query = "SELECT COUNT(country.id)
                    FROM `#__js_learnmanager_country` AS country";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        // Data
        $query = "SELECT country.* FROM `#__js_learnmanager_country` AS country";
        $query .= $inquery;
        $query .= " ORDER BY country.country_name ASC LIMIT " . JSLEARNMANAGERpagination::$_offset . ", " . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObjectList();
        return;
    }

    function deleteCountries($ids) {
        if (empty($ids))
            return false;
        $row = JSLEARNMANAGERincluder::getJSTable('country');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if(is_numeric($id)){
                if ($this->countryCanDelete($id) == true) {
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

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSLEARNMANAGERincluder::getJSTable('country');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'isenable' => $status))) {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'isenable' => $status))) {
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

    function countryCanDelete($countryid) {
        if (!is_numeric($countryid))
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT
                    ( SELECT COUNT(user.id)
                        FROM `#__js_learnmanager_user` AS user
                        WHERE user.country_id = " . $countryid . ")
                   AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCountryExist($country) {
        if (!is_string($country))
            return;
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_country` WHERE country_name = '" . $country . "'";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return true;
        else
            return false;
    }

    function getCountryIdByName($name) { // new function coded
        if (!$name)
            return;
        $db = new jslearnmanagerdb();
        $query = "SELECT id FROM `#__js_learnmanager_country` WHERE REPLACE(LOWER(country_name), ' ', '') = REPLACE(LOWER('" . $name . "'), ' ', '') AND isenable = 1";
        $db->setQuery($query);
        $id = $db->loadResult();
        return $id;
    }

    function getCountryNameById($id) { // new function coded
        if (!is_numeric($id))
            return;
        $db = new jslearnmanagerdb();
        $query = "SELECT country_name FROM `#__js_learnmanager_country` WHERE id = ".$id. " AND isenable = 1";
        $db->setQuery($query);
        $name = $db->loadResult();
        return $name;
    }

    function getMessagekey(){
        $key = 'country';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

     function getAdminCountrySearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $countryname = ($isadmin) ? 'countryname' : 'jslm_country';
        $jslms_search_array['countryname'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($countryname)));
        $jslms_search_array['status'] = trim(JSLEARNMANAGERrequest::getVar('status' , ''));
        $jslms_search_array['pagesize'] = absint(JSLEARNMANAGERrequest::getVar('pagesize'));
        $jslms_search_array['search_from_country'] = 1;
        // print_r($jslms_search_array);
        // die();
        return $jslms_search_array;
    }



}

?>
