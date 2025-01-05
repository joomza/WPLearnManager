<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcurrencyModel {

    function getCurrencybyId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_currencies` WHERE id = " . $id;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObject();
        return;
    }

    function getCurrencyForCombo() {
        $db = new jslearnmanagerdb();
        $query = "SELECT id, symbol AS text FROM `#__js_learnmanager_currencies` WHERE status = 1 ORDER BY ordering ASC";
        $db->setQuery($query);
        $allcurrency = $db->loadObjectList();
        return $allcurrency;
    }

    function getDefaultCurrency() {
        $db = new jslearnmanagerdb();
        $query = "SELECT currency.id FROM `#__js_learnmanager_currencies` currency WHERE currency.default = 1 AND currency.status = 1 ";
        $db->setQuery($query);
        $defaultValue = $db->loadObject();
        if (!$defaultValue) {
            $query = "SELECT id FROM `#__js_learnmanager_currencies` WHERE status=1";
            $db->setQuery($query);
            $defaultValue = $db->loadObjectList();
        }
        return $defaultValue;
    }

    function getAllCurrencies() {
        // Filter
        $title = isset(jslearnmanager::$_data['filter']['title']) ? jslearnmanager::$_data['filter']['title'] : '';
        $status = isset(jslearnmanager::$_data['filter']['status']) ? jslearnmanager::$_data['filter']['status'] : '';
        $code = JSLEARNMANAGERrequest::getVar('code');
        $pagesize = absint(JSLEARNMANAGERrequest::getVar('pagesize'));
       
        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            $inquery .= $clause . "title LIKE '%" . $title . "%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .= $clause . " status = " . $status;
        if ($code != null)
            $inquery .=$clause . " code LIKE '%" . $code . "%'";

        jslearnmanager::$_data['filter']['title'] = $title;
        jslearnmanager::$_data['filter']['status'] = $status;
        jslearnmanager::$_data['filter']['code'] = $code;
        jslearnmanager::$_data['filter']['pagesize'] = $pagesize;
        //Pagination
        if($pagesize){
          JSLEARNMANAGERpagination::setLimit($pagesize);
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT count(id) FROM `#__js_learnmanager_currencies` ";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);
        //Data
        $query = "SELECT * FROM `#__js_learnmanager_currencies` $inquery ORDER BY ordering ASC ";
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset . ", " . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObjectList();

        return;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        $query = "UPDATE `#__js_learnmanager_currencies` AS cur SET cur.isdefault = 0 WHERE cur.id != " . $id;
        $db->setQuery($query);
        $db->query();
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isCurrencyExist($data['title']);
            $db = new jslearnmanagerdb();
            if ($result == true) {
                return JSLEARNMANAGER_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM `#__js_learnmanager_currencies`";
                $db->setQuery($query);
                $data['ordering'] = $db->loadResult();
            }

            if ($data['status'] == 0) {
                $data['default'] = 0;
            } else {
                if ( isset($data['default']) && $data['default'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['status'] == 0) {
                $data['default'] = 0;
            } else {
                if ( isset($data['default']) && $data['default'] == 1) {
                    $canupdate = true;
                }
            }
        }
        return $canupdate;
    }

    function storeCurrency($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === JSLEARNMANAGER_ALREADY_EXIST)
            return JSLEARNMANAGER_ALREADY_EXIST;

        $row = JSLEARNMANAGERincluder::getJSTable('currency');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if ($row->isdefault == 1) {
            $this->updateIsDefault($row->id);
        }
        return JSLEARNMANAGER_SAVED;
    }

    function isCurrencyExist($title) {
        if(!$title)
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_currencies` WHERE title = '" . $title . "'";
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function storeOrderingFromPage($data) {//
        if (empty($data)) {
            return false;
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $sorted_array = array();
        parse_str($data['fields_ordering_new'],$sorted_array);
        $sorted_array = reset($sorted_array);
        if(!empty($sorted_array)){
                $row = JSLEARNMANAGERincluder::getJSTable('currency');
                $ordering_coloumn = 'ordering';
        }           
       $page_multiplier = 1;
        if($data['pagenum_for_ordering'] > 1){
            $page_multiplier = ($data['pagenum_for_ordering'] - 1) * jslearnmanager::$_config['pagination_default_page_size'] + 1;
        }
        for ($i=0; $i < count($sorted_array) ; $i++) {
            $row->update(array('id' => $sorted_array[$i], $ordering_coloumn => $page_multiplier + $i));
        }
        JSLEARNMANAGERmessages::setLayoutMessage(__('Ordering updated', 'learn-manager'), 'updated',' ');
        return;
    }

    function deleteCurrencies($ids) {
        if (empty($ids))
            return false;
        $row = JSLEARNMANAGERincluder::getJSTable('currency');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if(is_numeric($id)){
                if ($this->currencyCanDelete($id) == true) {
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

        $row = JSLEARNMANAGERincluder::getJSTable('currency');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'status' => $status))) {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if ($this->currencyCanUnpulish($id)) {
                        if (!$row->update(array('id' => $id, 'status' => $status))) {
                            $total += 1;
                        }
                    } else {
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

    function currencyCanUnpulish($currencyid) {
        if(!is_numeric($currencyid)) return false;
        $db = new jslearnmanagerdb();
        $query = " SELECT COUNT(id) FROM `#__js_learnmanager_currencies` AS cur WHERE cur.id = " . $currencyid . " AND cur.isdefault = 1 ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }


    function currencyCanDelete($currencyid) {
        if (is_numeric($currencyid) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE currencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `#__js_learnmanager_currencies` AS cur WHERE cur.id = " . $currencyid . " AND cur.isdefault =1)
                    AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getDefaultCurrencyId() {
        $db = new jslearnmanagerdb();
        $query = "SELECT id FROM `#__js_learnmanager_currencies` WHERE `default` = 1";
        $db->setQuery($query);
        $id = $db->loadResult();
        return $id;
    }

    function getCurrency($title) {
        $db = new jslearnmanagerdb();
        $query = "SELECT  id, symbol,title FROM `#__js_learnmanager_currencies`  WHERE id != 1 AND status = 1 ORDER BY title ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $currency = array();
        if ($title)
            $currency[] = array('value' => '', 'text' => $title);
        foreach ($rows as $row) {
            $currency[] = array('value' => $row->id, 'text' => $row->symbol);
        }
        return $currency;
    }

    function getSymbolPosition($price, $symbol){

        // $price = number_format($price,jslearnmanager::$_config['price_numbers_after_decimel_point'], jslearnmanager::$_config['price_decimal_separator'],jslearnmanager::$_config['price_thousand_separator']);
        if(jslearnmanager::$_config['price_poition_of_currency'] == 1){
            $price = $symbol.' '.$price;
        }else{
            $price = $price.' '.$symbol;
        }

        return $price;
    }

    function getMessagekey(){
        $key = 'currency';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

    function getAdminCurrencySearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $title = ($isadmin) ? 'title' : 'jslm_currency';
        $jslms_search_array['title'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($title)));
        $jslms_search_array['status'] = trim(JSLEARNMANAGERrequest::getVar('status' , ''));
        $jslms_search_array['pagesize'] = absint(JSLEARNMANAGERrequest::getVar('pagesize'));
        $jslms_search_array['search_from_currency'] = 1;
        return $jslms_search_array;
    }


}

?>
