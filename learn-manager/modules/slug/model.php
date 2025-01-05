<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERslugModel {

    private $_params_flag;
    private $_params_string;

    function __construct() {
        $this->_params_flag = 0;
    }

    function getSlug() {
    // Filter
        $db = new jslearnmanagerdb();
        $slug = isset(jslearnmanager::$_data['filter']['slug']) ? jslearnmanager::$_data['filter']['slug'] : '';


        $inquery = '';
        if ($slug != null){
            $inquery .= " AND slug.slug LIKE '%".$slug."%'";
        }
        jslearnmanager::$_data['slug'] = $slug;
        //pagination
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_slug` AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        
        jslearnmanager::$_data['total'] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        //Data
        $query = "SELECT *
                  FROM `#__js_learnmanager_slug` AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $query .= " LIMIT " . JSLEARNMANAGERpagination::$_offset . " , " . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObjectList();
        
        return;
    }


    function storeSlug($data) {
        if (empty($data)) {
            return false;
        }
        $db = new jslearnmanagerdb();
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $row = JSLEARNMANAGERincluder::getJSTable('slug');
        foreach ($data as $id => $slug) {
            if($id != '' && is_numeric($id)){
                $slug = sanitize_title($slug);
                if($slug != ''){
                    $query = "SELECT COUNT(id) FROM `#__js_learnmanager_slug`
                            WHERE slug = '" . $slug."' ";
                    $db->setQuery($query);        
                    $slug_flag = $db->loadResult();
                    if($slug_flag > 0){
                        continue;
                    }else{
                        $row->update(array('id' => $id, 'slug' => $slug));
                    }
                }
            }
        }
        flush_rewrite_rules();
        return JSLEARNMANAGER_SAVED;
    }

    function savePrefix($data) {
        if (empty($data)) {
            return false;
        }
        $db = new jslearnmanagerdb();
        $data['prefix'] = sanitize_title($data['prefix']);
        if($data['prefix'] == ''){
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        $query = "UPDATE `#__js_learnmanager_config`
                    SET configvalue = '".$data['prefix']."'
                    WHERE configname = 'slug_prefix'";
        $db->setQuery($query);            
        if($db->query()){
            flush_rewrite_rules();
            return JSLEARNMANAGER_SAVED;
        }else{
            flush_rewrite_rules();
            return JSLEARNMANAGER_SAVE_ERROR;
        }
    }

    function resetAllSlugs() {
        $db = new jslearnmanagerdb();
        $query = "UPDATE `#__js_learnmanager_slug`
                    SET slug = defaultslug ";
        $db->setQuery($query);
        if($db->query()){
            update_option('rewrite_rules', '');
            return JSLEARNMANAGER_SAVED;
        }else{
            update_option('rewrite_rules', '');
            return JSLEARNMANAGER_SAVE_ERROR;
        }
    }

    function getOptionsForEditSlug() {
        $slug = JSLEARNMANAGERrequest::getVar('slug');
        $html = '<span class="jslm_popup-top">
                    <span id="jslm_popup_title" >' . __("Edit Slug", "learn-manager") . '</span>
                        <img id="jslm_popup_cross" onClick="closePopup();" src="' . JSLEARNMANAGER_PLUGIN_URL.'includes/images/popup-close.png"></span>';
        
        $html .= '<div class="jslm_popup-field-wrapper">
                    <div class="jslm_popup-field-title">' . __('Slug Name', 'learn-manager') . '<font class="jslearnmanager_required-notifier">*</font></div>
                         <div class="jslm_popup-field-obj">' . JSLEARNMANAGERformfield::text('slugedit', isset($slug) ? $slug : 'text', '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
        $html .='<div class="jslm_js-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-1 js-col-md-offset-1">
                    ' . JSLEARNMANAGERformfield::button('save', __('Save', 'jslearnmanager'), array('class' => 'button savebutton','onClick'=>'getFieldValue();'));
        $html .='</div>';
        return ($html);
    }

    function getDefaultSlugFromSlug($layout) {
        $db = new jslearnmanagerdb();
        $query = "SELECT  defaultslug FROM `#__js_learnmanager_slug` WHERE slug = '".$layout."'";
        $db->setQuery($query);
        $val = $db->loadResult();
        return sanitize_title($val);
    }

    function getSlugFromFileName($layout,$module) {
        $db = new jslearnmanagerdb();
        $where_query = '';
        if($layout == 'dashboard'){
            if($module == 'student'){
                $where_query = " AND defaultslug = 'student-dashboard'";                            
            }elseif($module == 'instructor'){
                $where_query = " AND defaultslug = 'instructor-dashboard'";
            }
        }
        if($layout == 'myprofile'){
            if($module == 'student'){
                $where_query = " AND defaultslug = 'student-profile'";                            
            }elseif($module == 'instructor'){
                $where_query = " AND defaultslug = 'instructor-details'";
            }
        }
        $query = "SELECT slug FROM `#__js_learnmanager_slug` WHERE filename = '".$layout."' ".$where_query;
        $db->setQuery($query);
        $val = $db->loadResult();
        return $val;
    }

    function getSlugString($home_page = 0) {
        
            //$query = "SELECT slug AS value, pkey AS akey FROM `".jslearnmanager::$_db->prefix."js_learnmanager_slug`";
            global $wp_rewrite;
            $db = new jslearnmanagerdb();
            $rules = json_encode($wp_rewrite->rules);
            $query = "SELECT slug AS value FROM `#__js_learnmanager_slug`";
            $db->setQuery($query);
            $val = $db->loadObjectList();
            $string = '';
            $bstring = '';
            //$rules = json_encode($rules);
            $prefix = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
            $homeprefix = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
            foreach ($val as $slug) {

                    if($home_page == 1){
                        $slug->value = $homeprefix.$slug->value;
                    }

                    if(strpos($rules,$slug->value) === false){
                        $string .= $bstring. $slug->value;
                    }else{
                        $string .= $bstring.$prefix. $slug->value;
                    }
                $bstring = '|';
            }
        return $string;
    }

    function getRedirectCanonicalArray() {
        $db = new jslearnmanagerdb();
        global $wp_rewrite;
        $slug_prefix = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
        $homeprefix = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
        $rules = json_encode($wp_rewrite->rules);
        $query = "SELECT slug AS value FROM `#__js_learnmanager_slug`";
        $db->setQuery($query);
        $val = $db->loadObjectList();
        $string = array();
        $bstring = '';
         foreach ($val as $slug) {
            $slug->value = $homeprefix.$slug->value;
            $string[] = $bstring.$slug->value;
            $bstring = '/';
        }
        return $string;
    }
    
    function checkIfSlugExist($layout){
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(id) FROM `#__js_learnmanager_slug` WHERE slug = '" . $layout . "'";
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result > 0){
            return true;
        }else{
            return false;
        }
    }

    function getMessagekey(){
        $key = 'slug';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }




 function getAdminSlugSearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $slug = ($isadmin) ? 'slug' : 'jslm_slug';
        $jslms_search_array['slug'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($slug)));
        $jslms_search_array['search_from_slug'] = 1;
        return $jslms_search_array;
    }}

?>
