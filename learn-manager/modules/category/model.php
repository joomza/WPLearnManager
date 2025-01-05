<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcategoryModel {

    function getCategorybyId($id) {
        if (is_numeric($id) == false) return false;
        //db Object
        $db = new jslearnmanagerdb();

        //data
        $query = "SELECT * FROM `#__js_learnmanager_category` WHERE id = " . $id;
        $db->setQuery($query);
        $category = $db->loadObject();
        jslearnmanager::$_data[0] = $category;
        return ;
    }

    function getAllCategories() {
        //DB Object
        $db = new jslearnmanagerdb;
        $isadmin = is_admin();
        $catname = ($isadmin) ? 'searchname' : 'jslm_category';
        $categoryname = isset(jslearnmanager::$_data['filter']['searchname']) ? jslearnmanager::$_data['filter']['searchname'] : '';
        $status =isset(jslearnmanager::$_data['filter']['status']) ? jslearnmanager::$_data['filter']['status'] : '';
        $pagesize = absint(JSLEARNMANAGERrequest::getVar('pagesize',''));
        $inquery = '';
        $statusop = 'WHERE';
        $filter_flag = 0;
        if ($categoryname != null) {
        	$inquery .= $statusop;
            $inquery .= " category_name LIKE '%$categoryname%'";
            $filter_flag = 1;
            $statusop = ' AND';
        }
        if (is_numeric($status)) {
        	$inquery .= $statusop;
            $inquery .="  status = " . $status;
            $filter_flag = 1;
            $statusop = 'AND';
        }
        $inquery .= "";
        if(!is_admin() && !is_numeric($status)){
            $inquery .= $statusop;
            $inquery .="  status = 1";
        }

        jslearnmanager::$_data['filter'][$catname] = $categoryname;
        jslearnmanager::$_data['filter']['status'] = $status;
        jslearnmanager::$_data['filter']['pagesize'] = $pagesize;
        //pagination
        if($pagesize){
            JSLEARNMANAGERpagination::setLimit($pagesize);
        }
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_category` ";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);
        //data
        $result = array();
        $query = "SELECT * FROM `#__js_learnmanager_category` ";
        $query .= $inquery;
        $query .= " ORDER BY ordering ASC";
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset ." , ". JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        jslearnmanager::$_data[0] = $categories;
        return;
    }

    private function getCategoryChild($parentid, $prefix, &$result) {
        $db = new jslearnmanagerdb;
        if (!is_numeric($parentid))
            return false;
        $query = "SELECT * FROM `#__js_learnmanager_category` AS category WHERE category.parentid = " . $parentid ." ORDER by category.ordering ";
        $db->setQuery($query);
        $kbcategories = $db->loadObjectList();
        // jslearnmanager::$_data[0] = $categories;
        if (!empty($kbcategories)) {
            foreach ($kbcategories as $cat) {
                $subrecord = (object) array();
                $subrecord->id = $cat->id;
                $subrecord->category_name = $prefix . __($cat->category_name, 'learn-manager');
                $subrecord->alias = $cat->alias;
                $subrecord->status = $cat->status;
                $subrecord->isdefault = $cat->isdefault;
                $subrecord->ordering = $cat->ordering;
                $result[] = $subrecord;
                $this->getCategoryChild($cat->id, $prefix . '|-- ', $result);
            }
            return $result;
        }
    }

    function getCourseCountByCatIdForhp($category_id){
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(c.id) as total
                    FROM `#__js_learnmanager_course` AS c
                    WHERE c.course_status = 1 AND c.isapprove = 1 AND c.category_id =" .$category_id;

        $db->setQuery($query);
        $coursescount = $db->loadResult();
        return $coursescount;
    }


    function getCategoriesCountList(){
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(cat.id) FROM `#__js_learnmanager_category` AS cat
                    WHERE cat.status = 1";
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        $query = " SELECT cat.category_name, cat.category_img, cat.id as category_id, (SELECT COUNT(c.id) FROM  `#__js_learnmanager_course` AS c
            LEFT JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type
            WHERE cat.id = c.category_id AND c.course_status = 1 AND c.isapprove = 1 AND accesstype.status = 1) as total
                    FROM `#__js_learnmanager_category` AS cat
                        GROUP BY cat.id ORDER BY cat.category_name ASC";
        //$query .= " LIMIT ". JSLEARNMANAGERpagination::$_offset . ", " . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        $coursescount = $db->loadObjectList();
        jslearnmanager::$_data[0] = $coursescount;
        return;
    }

    function storeOrderingFromPage($data) {//
        if (empty($data)) {
            return false;
        }
        $sorted_array = array();
        parse_str($data['fields_ordering_new'],$sorted_array);
        $sorted_array = reset($sorted_array);
        if(!empty($sorted_array)){
                $row = JSLEARNMANAGERincluder::getJSTable('category');
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
        return ;
    }


    function getCategoryForCombobox($themecall=null) {
        $db = new jslearnmanagerdb;
        $result = array();
        $query = "SELECT category.* from `#__js_learnmanager_category` AS category
                    WHERE category.status = 1 ORDER by category.ordering ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        // if (isset($knowledgebase)) {
        //     foreach ($knowledgebase as $kb) {
        //         $record = (object) array();
        //         $record->id = $kb->id;
        //         $record->category_name = $kb->category_name;
        //         $result[] = $record;
        //         $this->getCategoryChild($kb->id, $prefix, $result);
        //     }
        // }

        $list = array();
        foreach ($result AS $category) {
            if(null != $themecall){
                //$list[$category->id] = $category->category_name;
                $list[$category->category_name] = intval($category->id);
            }else{
                $list[] = (object) array('id' => $category->id, 'text' => $category->category_name);

            }
        }
        return $list;
    }

    function getCategoryForComboboxForApp(){
        $db = new jslearnmanagerdb();
        $query = "SELECT category.id, category.category_name, COUNT(course.category_id) as total
                    FROM `#__js_learnmanager_category` AS category
                        LEFT JOIN `#__js_learnmanager_course` AS course ON category.id = course.category_id AND course.course_status = 1
                        WHERE category.status = 1 GROUP BY category.id ORDER BY `category`.`id`";
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        return $categories;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        //DB class limitations
        $db = new jslearnmanagerdb();
        $query = "UPDATE `#__js_learnmanager_category` SET isdefault = 0 WHERE id != " . $id;
        $db->setQuery($query);
        $db->query();
    }

    function validateFormData(&$data) {
        $db = new jslearnmanagerdb();
        $category = JSLEARNMANAGERrequest::getVar('parentid');
        $inquery = ' ';
        if ($category) {
            $inquery .=" WHERE parentid = $category ";
        }
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isCategoryExist($data['category_name']);
            if ($result == true) {
                return JSLEARNMANAGER_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM `#__js_learnmanager_category` " . $inquery;
                $db->setQuery($query);
                $data['ordering'] = $db->loadResult($query);
                if ($data['ordering'] == null)
                    $data['ordering'] = 1;
            }
            if ($data['isactive'] == 0) {
                $data['isdefault'] = 0;
            } else {
                if (isset($data['isdefault']) AND $data['isdefault'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['isdefault'] == 1) {
                $data['isdefault'] = 1;
                $data['isactive'] = 1;
            } else {
                if ($data['isactive'] == 0) {
                    $data['isdefault'] = 0;
                } else {
                    if ($data['isdefault'] == 1) {
                        $canupdate = true;
                    }
                }
            }
        }
        return $canupdate;
    }

    function storeCategory($data) {
        if (empty($data))
            return false;
        $db = new jslearnmanagerdb();
        $canupdate = $this->validateFormData($data);
        if ($canupdate === JSLEARNMANAGER_ALREADY_EXIST)
            return JSLEARNMANAGER_ALREADY_EXIST;

        if (!empty($data['alias']))
            $cat_title_alias = JSLEARNMANAGERincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $cat_title_alias = JSLEARNMANAGERincluder::getJSModel('common')->removeSpecialCharacter($data['category_name']);

        $cat_title_alias = strtolower(str_replace(' ', '-', $cat_title_alias));
        $cat_title_alias = strtolower(str_replace('/', '-', $cat_title_alias));
        $data['alias'] = $cat_title_alias;
        $data['status'] = $data['isactive'];
        if($data['id'] == ''){
        	$data['created_at'] = date('Y-m-d');
        	$data['updated_at'] = date('Y-m-d');
        }else{
            if((isset($_FILES['category_img']['size']) && $_FILES['category_img']['size']) > 0 || (isset($data['jslms_category_image_del']) && $data['jslms_category_image_del'] == 1)){
                $this->deleteCategoryImage($data['id'],0);
            }
        }

        $row = JSLEARNMANAGERincluder::getJSTable('category');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);

        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if ($canupdate) {
            $this->updateIsDefault($row->id);
        }
        $res[] = '';
        if ($_FILES['category_img']['size'] > 0) {  // file
            $file = array(
                    'name'     => sanitize_file_name($_FILES['category_img']['name']),
                    'type'     => filter_var($_FILES['category_img']['type'], FILTER_SANITIZE_STRING),
                    'tmp_name' => filter_var($_FILES['category_img']['tmp_name'], FILTER_SANITIZE_STRING),
                    'error'    => filter_var($_FILES['category_img']['error'], FILTER_SANITIZE_STRING),
                    'size'     => filter_var($_FILES['category_img']['size'], FILTER_SANITIZE_STRING)
                    );
            $res = JSLEARNMANAGERincluder::getObjectClass('uploads')->learnManagerUpload($row->id,0,$file,6); // if parent id is zero or none than second parameter will be zero
            if ($res[0] == 6){
                $msg = JSLEARNMANAGERMessages::getMessage(JSLEARNMANAGER_FILE_TYPE_ERROR, '');
                JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
            }
            if($res[0] == 5){
                $msg = JSLEARNMANAGERMessages::getMessage(JSLEARNMANAGER_FILE_SIZE_ERROR, '');
                JSLEARNMANAGERMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
            }
        }
        return JSLEARNMANAGER_SAVED;
    }

    function deleteCategoryImage($id,$ajaxcall){
        if(! is_numeric($id))
            return false;
        $db = new jslearnmanagerdb();
        // select photo so that custom uploaded files are not delted
        $query = "SELECT category_img FROM `#__js_learnmanager_category` AS c WHERE id = ".$id;
        $db->setQuery($query);
        $photo = $db->loadResult();
        // path to file so that it can be removed
        $wpdir = wp_upload_dir();
        $data_directory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/category/category_' .$cid;
        $files = glob( $path . '/*');
        $filename = basename($photo);
        if($filename != "" && $filename != null){
            $explodeimage = explode(".", $filename); // For removing  resizing image

            $explodeimage = $explodeimage[0].'_1.'.$explodeimage[1];
            foreach($files as $file){
                if(is_file($file) && strstr($file, $filename) ) {
                    unlink($file);
                }
                if(is_file($file) && strstr($file, $explodeimage)){
                    unlink($file);
                }
            }

            $query = "UPDATE `#__js_learnmanager_category` SET category_img = '' WHERE id = ".$id;
            $db->setQuery($query);
            if($ajaxcall == 1){
                if($db->query()){
                    return true;
                }
            }else{
                $db->query();
            }
        }
        return;
    }

    function deletecategoryimageAjax(){
        $id = JSLEARNMANAGERrequest::getVar('categoryid');
        if(!is_numeric($id))
            return false;
        $isdelete = $this->deleteCategoryImage($id , 1);
        return $isdelete;
    }

    function deleteCategories($ids) {
        if (empty($ids))
            return false;
        $db = new jslearnmanagerdb();
        $row = JSLEARNMANAGERincluder::getJSTable('category');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->categoryCanDelete($id) == true) {
                if (!$row->delete($id)) {
                    $notdeleted += 1;
                }
            } else {
                $notdeleted += 1;
            }
        }
        if ($notdeleted == 0) {
            JSLEARNMANAGERMessages::$counter = false;
            return JSLEARNMANAGER_DELETED;
        } else {
            JSLEARNMANAGERMessages::$counter = $notdeleted;
            return JSLEARNMANAGER_DELETE_ERROR;
        }
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSLEARNMANAGERincluder::getJSTable('category');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->categoryCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'status' => $status))) {
                        $total += 1;
                    }
                } else {
                    $total += 1;
                }
            }
        }
        if ($total == 0) {
            JSLEARNMANAGERMessages::$counter = false;
            if ($status == 1)
                return JSLEARNMANAGER_PUBLISHED;
            else
                return JSLEARNMANAGER_UN_PUBLISHED;
        }else {
            JSLEARNMANAGERMessages::$counter = $total;
            if ($status == 1)
                return JSLEARNMANAGER_PUBLISH_ERROR;
            else
                return JSLEARNMANAGER_UN_PUBLISH_ERROR;
        }
    }

    function categoryCanUnpublish($categoryid) {
        if (!is_numeric($categoryid))
            return false;
        $db = new jslearnmanagerdb;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_learnmanager_category` WHERE id = " . $categoryid . " AND isdefault = 1)
                    AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function categoryCanDelete($categoryid) {
        if (!is_numeric($categoryid))
            return false;
         $db = new jslearnmanagerdb();
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_learnmanager_course` WHERE category_id = " . $categoryid." )
                    ";
        $db->setQuery($query);
        $total  = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCategoryExist($title) {
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_category` WHERE category_name = '" . $title . "'";
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getCategoriesForCombo() {
        $rows = $this->getCategoryForCombobox();
        return $rows;
    }

    function getMessagekey(){
        $key = 'category';if(is_admin()){$key = 'admin_'.$key;} 
        return $key;
    }

    function getAdminCategorySearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $searchname = ($isadmin) ? 'searchname' : 'jslm_category';
        $jslms_search_array['searchname'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($searchname)));
        $jslms_search_array['status'] = trim(JSLEARNMANAGERrequest::getVar('status' , ''));
        $jslms_search_array['pagesize'] = absint(JSLEARNMANAGERrequest::getVar('pagesize'));
        $jslms_search_array['search_from_category'] = 1;
        return $jslms_search_array;
    }


}

?>
