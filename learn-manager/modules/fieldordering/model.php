<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERFieldorderingModel {

    function __construct() {

    }

    function fieldsRequiredOrNot($ids, $value) {
        if (empty($ids))
            return false;
        if (!is_numeric($value))
            return false;
        $db = new jslearnmanagerdb();
        $total = 0;
        foreach ($ids as $id) {
            if(is_numeric($id) && is_numeric($value)){
                $query = "UPDATE `#__js_learnmanager_fieldsordering` SET required = " . $value . " WHERE id = " . $id . " AND (sys = 1 OR sys = '' OR sys IS NULL )";
                $db->setQuery($query);
                if (false === $db->query()) {
                    $total += 1;
                }
            }else{
                $total += 1;
            }
        }
        if ($total == 0) {
            JSLEARNMANAGERMessages::$counter = false;
            if ($value == 1)
                return JSLEARNMANAGER_REQUIRED;
            else
                return JSLEARNMANAGER_NOT_REQUIRED;
        }else {
            JSLEARNMANAGERMessages::$counter = $total;
            if ($value == 1)
                return JSLEARNMANAGER_REQUIRED_ERROR;
            else
                return JSLEARNMANAGER_NOT_REQUIRED_ERROR;
        }
    }

    function getFieldsOrdering($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $title = isset(jslearnmanager::$_data['filter']['title']) ? filter_var(jslearnmanager::$_data['filter']['title'], FILTER_SANITIZE_STRING) : '';
        $ustatus = isset(jslearnmanager::$_data['filter']['ustatus']) ? filter_var(jslearnmanager::$_data['filter']['ustatus'], FILTER_SANITIZE_STRING) : '';
        $vstatus = isset(jslearnmanager::$_data['filter']['vstatus']) ? filter_var(jslearnmanager::$_data['filter']['vstatus'], FILTER_SANITIZE_STRING) : '';
        $required = isset(jslearnmanager::$_data['filter']['required']) ? filter_var(jslearnmanager::$_data['filter']['required'], FILTER_SANITIZE_STRING) : '';
        $pagesize = absint(JSLEARNMANAGERrequest::getVar('pagesize'));
        
        $inquery = '';
        if ($title != null)
            $inquery .= " AND field.fieldtitle LIKE '%$title%'";
        if (is_numeric($ustatus))
            $inquery .= " AND field.published = $ustatus";
        if (is_numeric($vstatus))
            $inquery .= " AND field.isvisitorpublished = $vstatus";
        if (is_numeric($required))
            $inquery .= " AND field.required = $required";

        jslearnmanager::$_data['filter']['title'] = $title;
        jslearnmanager::$_data['filter']['ustatus'] = $ustatus;
        jslearnmanager::$_data['filter']['vstatus'] = $vstatus;
        jslearnmanager::$_data['filter']['required'] = $required;
        jslearnmanager::$_data['filter']['pagesize'] = $pagesize;
        $db = new jslearnmanagerdb();
        //Pagination
        if($pagesize){
            JSLEARNMANAGERpagination::setLimit($pagesize);
        }
        $query = "SELECT COUNT(field.id) FROM `#__js_learnmanager_fieldsordering` AS field WHERE field.fieldfor = " . $fieldfor;
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jslearnmanager::$_data[1] = $total;
        jslearnmanager::$_data[1] = JSLEARNMANAGERpagination::getPagination($total);

        //Data
        $query = "SELECT field.*
                    FROM `#__js_learnmanager_fieldsordering` AS field
                    WHERE field.fieldfor = " . $fieldfor;
        $query .= $inquery;
        $query .= ' ORDER BY';
        $query .= ' field.ordering';
        
        $query .=" LIMIT " . JSLEARNMANAGERpagination::$_offset . "," . JSLEARNMANAGERpagination::$_limit;
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObjectList();
        return;
    }

    function getFieldsOrderingByFor($fieldfor) {
        $db = new jslearnmanagerdb();
        if (!is_numeric($fieldfor))
            return false;
        $published = JSLEARNMANAGERincluder::getObjectClass('user')->isguest() ? ' AND isvisitorpublished = 1 ' : ' AND published = 1 ' ;
        $query = "SELECT * FROM `#__js_learnmanager_fieldsordering`
                    WHERE published = 1 AND fieldfor =  " . $fieldfor .$published. " ORDER BY ordering";
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }


    // Save the Ordering system in this Function
    function storeOrderingFromPage($data) {//
        if (empty($data)) {
            return false;
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $sorted_array = array();
        parse_str($data['fields_ordering_new'],$sorted_array);
        $sorted_array = reset($sorted_array);
        if(!empty($sorted_array)){
                $row = JSLEARNMANAGERincluder::getJSTable('fieldsordering');
                $ordering_coloumn = 'ordering';
        }           
           $page_multiplier = 1;
            if($data['pagenum_for_ordering'] > 1){
                $page_multiplier = ($data['pagenum_for_ordering'] - 1) * jssupportticket::$_config['pagination_default_page_size'] + 1;
            }
            for ($i=0; $i < count($sorted_array) ; $i++) {
                $row->update(array('id' => $sorted_array[$i], $ordering_coloumn => $page_multiplier + $i));
            }
        // JSLEARNMANAGERmessages::setLayoutMessage(__('Ordering updated', 'learn-manager'), 'updated',' ');
        return JSLEARNMANAGER_SAVED;
    }
   
    function getFieldsOrderingforForm($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $published = (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) ? "isvisitorpublished" : "published";
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_fieldsordering`
                 WHERE $published = 1 AND fieldfor = " . $fieldfor . " ORDER BY";
        $query.=" ordering";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    function getFieldsOrderingforSearch($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $published = ' AND search_user = 1 ';
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_fieldsordering`
                 WHERE cannotsearch = 0 AND  fieldfor = " . $fieldfor . $published . " ORDER BY ordering";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    function getFieldsOrderingforView($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT field,fieldtitle FROM `#__js_learnmanager_fieldsordering`
                WHERE  fieldfor =  " . $fieldfor ." ORDER BY ordering";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $return = array();
        foreach ($rows AS $row) {
            $return[$row->field] = $row->fieldtitle;
        }
        return $return;
    }

    function getFieldsForListing($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT field,showonlisting FROM `#__js_learnmanager_fieldsordering`
                WHERE  fieldfor =  " . $fieldfor ." ORDER BY ordering";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $return = array();
        foreach ($rows AS $row) {
            $return[$row->field] = $row->showonlisting;
        }
        return $return;
    }

    function fieldsPublishedOrNot($ids, $value) {
        if (empty($ids))
            return false;
        if (!is_numeric($value))
            return false;
        $db = new jslearnmanagerdb();
        $total = 0;
        foreach ($ids as $id) {
            if(is_numeric($id) && is_numeric($value)){
                $query = "UPDATE `#__js_learnmanager_fieldsordering` SET published = " . $value . " WHERE id = " . $id . " AND cannotunpublish=0";
                $db->setQuery($query);
                if (false === $db->query()) {
                    $total += 1;
                }
            }else{
                $total += 1;
            }
        }
        if ($total == 0) {
            JSLEARNMANAGERMessages::$counter = false;
            if ($value == 1)
                return JSLEARNMANAGER_PUBLISHED;
            else
                return JSLEARNMANAGER_UN_PUBLISHED;
        }else {
            JSLEARNMANAGERMessages::$counter = $total;
            if ($value == 1)
                return JSLEARNMANAGER_PUBLISH_ERROR;
            else
                return JSLEARNMANAGER_UN_PUBLISH_ERROR;
        }
    }

    function visitorFieldsPublishedOrNot($ids, $value) {
        if (empty($ids))
            return false;
        if (!is_numeric($value))
            return false;
        $db = new jslearnmanagerdb();
        $total = 0;
        foreach ($ids as $id) {
            if(is_numeric($id) && is_numeric($value)){
                $query = "UPDATE `#__js_learnmanager_fieldsordering` SET isvisitorpublished = " . $value . " WHERE id = " . $id . " AND cannotunpublish=0";
                $db->setQuery($query);
                if (false === $db->query()) {
                    $total += 1;
                }
            }else{
                $total += 1;
            }
        }
        if ($total == 0) {
            JSLEARNMANAGERMessages::$counter = false;
            if ($value == 1)
                return JSLEARNMANAGER_PUBLISHED;
            else
                return JSLEARNMANAGER_UN_PUBLISHED;
        }else {
            JSLEARNMANAGERMessages::$counter = $total;
            if ($value == 1)
                return JSLEARNMANAGER_PUBLISH_ERROR;
            else
                return JSLEARNMANAGER_UN_PUBLISH_ERROR;
        }
    }

    function fieldOrderingUp($field_id) {
        if (is_numeric($field_id) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = "UPDATE `#__js_learnmanager_fieldsordering` AS f1, `#__js_learnmanager_fieldsordering` AS f2
                SET f1.ordering = f1.ordering + 1
                WHERE f1.ordering = f2.ordering - 1
                AND f1.fieldfor = f2.fieldfor
                AND f2.id = " . $field_id;
        $db->setQuery($query);
        if (false == $db->query()) {
            return JSLEARNMANAGER_ORDER_UP_ERROR;
        }

        $query = " UPDATE `#__js_learnmanager_fieldsordering`
                    SET ordering = ordering - 1
                    WHERE id = " . $field_id;
        $db->setQuery($query);
        if (false == $db->query()) {
            return JSLEARNMANAGER_ORDER_UP_ERROR;
        }
        return JSLEARNMANAGER_ORDER_UP;
    }

    function fieldOrderingDown($field_id) {
        if (is_numeric($field_id) == false)
            return false;
        $db = new jslearnmanagerdb();
        $query = "UPDATE `#__js_learnmanager_fieldsordering` AS f1, `#__js_learnmanager_fieldsordering` AS f2
                    SET f1.ordering = f1.ordering - 1
                    WHERE f1.ordering = f2.ordering + 1
                    AND f1.fieldfor = f2.fieldfor
                    AND f2.id = " . $field_id;
        $db->setQuery($query);
        if (false == $db->query()) {
            return JSLEARNMANAGER_ORDER_DOWN_ERROR;
        }

        $query = " UPDATE `#__js_learnmanager_fieldsordering`
                    SET ordering = ordering + 1
                    WHERE id = " . $field_id;
        $db->setQuery($query);
        if (false == $db->query()) {
            return JSLEARNMANAGER_ORDER_DOWN_ERROR;
        }
        return JSLEARNMANAGER_ORDER_DOWN;
    }

    function storeUserField($data) {
        if (empty($data)) {
            return false;
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $row = JSLEARNMANAGERincluder::getJSTable('fieldsordering');
        if ($data['isuserfield'] == 1) {
            // value to add as field ordering
            if ($data['id'] == '') { // only for new
                $db = new jslearnmanagerdb();
                $query = "SELECT max(ordering) FROM `#__js_learnmanager_fieldsordering` WHERE fieldfor = " . $data['fieldfor'];
                $db->setQuery($query);
                $var = $db->loadResult();
                $data['ordering'] = $var + 1;
                $data['cannotsearch'] = 0;
                $query = "SELECT max(id) FROM `#__js_learnmanager_fieldsordering` ";
                $db->setQuery($query);
                $maxid = $db->loadResult();
                $maxid++;
                $data['field'] = 'ufield_'.$maxid;
            }
            $params = array();
            
            //code for depandetn field
            if (isset($data['userfieldtype']) && $data['userfieldtype'] == 'depandant_field') {
                if ($data['id'] != '') {
                    //to handle edit case of depandat field
                    $data['arraynames'] = $data['arraynames2'];
                }
                $flagvar = $this->updateParentField($data['parentfield'], $data['field'], $data['fieldfor']);
                if ($flagvar == false) {
                    return JSLEARNMANAGER_SAVE_ERROR;
                }
                if (!empty($data['arraynames'])) {
                    $valarrays = explode(',', $data['arraynames']);
                    foreach ($valarrays as $key => $value) {
                        $keyvalue = $value;
                        $value = str_replace(' ','_',$value);
                        if (isset($data[$value]) && $data[$value] != null) {
                            $params[$keyvalue] = array_filter($data[$value]);
                        }
                    }
                }
            }
            
            if (!empty($data['values'])) {
                foreach ($data['values'] as $key => $value) {
                    if ($value != null) {
                        $params[] = trim($value);
                    }
                }
            }
            if(!empty($params)){
                $params_string = json_encode($params);
                $data['userfieldparams'] = $params_string;
            }
        }
        if($data['fieldfor'] == 3){
            $data['cannotshowonlisting'] = 1;
        }
        if(isset($data['userfieldtype']) && $data['userfieldtype'] == 'file'){
            $data['cannotshowonlisting'] = 1;
            $data['showonlisting'] = 0;
        }
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        $stored_id = $row->id;
        return JSLEARNMANAGER_SAVED;
    }

    function updateParentField($parentfield, $field, $fieldfor) {
        if(!is_numeric($parentfield)) return false;
        if(!is_numeric($fieldfor)) return false;
        $db = new jslearnmanagerdb();
        $query = "UPDATE `#__js_learnmanager_fieldsordering` SET depandant_field = '' WHERE fieldfor = ".$fieldfor." AND depandant_field = '".$parentfield."'";
        $db->setQuery($query);
        $db->query();
        $row = JSLEARNMANAGERincluder::getJSTable('fieldsordering');
        $row->update(array('id' => $parentfield, 'depandant_field' => $field));
        return true;
    }

    function getFieldsForComboByFieldFor() {
        $fieldfor = JSLEARNMANAGERrequest::getVar('fieldfor');
        $parentfield = JSLEARNMANAGERrequest::getVar('parentfield');
        if(!is_numeric($fieldfor)) return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT fieldtitle AS text ,id FROM `#__js_learnmanager_fieldsordering` WHERE fieldfor = " . $fieldfor . " AND (userfieldtype = 'radio' OR userfieldtype = 'combo') ";
        $db->setQuery($query);
        $data = $db->loadObjectList();
        if($parentfield){
            $query = "SELECT id FROM `#__js_learnmanager_fieldsordering` WHERE fieldfor = $fieldfor AND (userfieldtype = 'radio' OR userfieldtype = 'combo') AND depandant_field = '" . $parentfield . "' ";
            $db->setQuery($query);
            $parent = $db->loadResult();
        }else{
            $parent = '';
        }
        $jsFunction = 'getDataOfSelectedField();';
        $html = JSLEARNMANAGERformfield::select('parentfield', $data, $parent, __('Select parent field','learn-manager'), array('onchange' => $jsFunction, 'class' => 'jslm_inputbox jslm_one' , 'data-validation'=>'required'));
        $data = ($html);
        return $data;
    }

    function getSectionToFillValues() {
        $db = new jslearnmanagerdb();
        $field = JSLEARNMANAGERrequest::getVar('pfield');
        if(!is_numeric($field))
            return '';
        $query = "SELECT userfieldparams FROM `#__js_learnmanager_fieldsordering` WHERE id=$field";
        $db->setQuery($query);
        $data = $db->loadResult();
        $data = json_decode($data);
        $html = '';
        $fieldsvar = '';
        $comma = '';
        for ($i = 0; $i < count($data); $i++) {
            $fieldsvar .= $comma . "$data[$i]";
            $textvar = $data[$i];
            $textvar .='[]';
            $html .= "<div class='jslm_js-field-wrapper jslm_js-row jslm_no-margin'>";
            $html .= "<div class='jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding'>" . $data[$i] . "</div>";
            $html .= "<div class='jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding combo-options-fields' id='" . $data[$i] . "'>
                            <span class='jslm_input-field-wrapper'>
                                " . JSLEARNMANAGERformfield::text($textvar, '', array('class' => 'jslm_inputbox jslm_one jslm_user-field')) . "
                                <img class='jslm_input-field-remove-img' src='" . JSLEARNMANAGER_PLUGIN_URL."includes/images/remove.png' />
                            </span>
                            <input type='button' id='jslm_depandant-field-button' onClick='getNextField(\"" . $data[$i] . "\",this);'  value='Add More' />
                        </div>";
            $html .= "</div>";
            $comma = ',';
        }
        $html .= " <input type='hidden' name='arraynames' value='" . $fieldsvar . "' />";
        $html = ($html);
        return $html;
    }

    function getOptionsForFieldEdit() {
        $field = JSLEARNMANAGERrequest::getVar('field');
        $yesno = array(
            (object) array('id' => 1, 'text' => __('Yes', 'learn-manager')),
            (object) array('id' => 0, 'text' => __('No', 'learn-manager')));

        if(!is_numeric($field)) return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_fieldsordering` WHERE id=" . $field;
        $db->setQuery($query);
        $data = $db->loadObject();

        $html = '<span class="jslm_popup-top">
                    <span id="jslm_popup_title" >
                    ' . __("Edit Field", "learn-manager") . '
                    </span>
                    <img id="jslm_popup_cross" onClick="closePopup();" src="' . JSLEARNMANAGER_PLUGIN_URL.'includes/images/popup-close.png">
                </span>';
        $html .= '<form id="jslearnmanager-form" class="jslm_popup-field-from" method="post" action="' . admin_url("admin.php?page=jslm_fieldordering&task=saveuserfield") . '">';
        $html .= '<div class="jslm_popup-field-wrapper">
                    <div class="jslm_popup-field-title">' . __('Field Title', 'learn-manager') . '<font class="jslm_required-notifier">*</font></div>
                    <div class="jslm_popup-field-obj">' . JSLEARNMANAGERformfield::text('fieldtitle', isset($data->fieldtitle) ? $data->fieldtitle : 'text', '', array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required')) . '</div>
                </div>';
        if ($data->cannotunpublish == 0) {
            $html .= '<div class="jslm_popup-field-wrapper">
                        <div class="jslm_popup-field-title">' . __('User Published', 'learn-manager') . '</div>
                        <div class="jslm_popup-field-obj">' . JSLEARNMANAGERformfield::select('published', $yesno, isset($data->published) ? $data->published : 0, __('Select published', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required')) . '</div>
                    </div>';
            $html .= '<div class="jslm_popup-field-wrapper">
                        <div class="jslm_popup-field-title">' . __('Visitor published', 'learn-manager') . '</div>
                        <div class="jslm_popup-field-obj">' . JSLEARNMANAGERformfield::select('isvisitorpublished', $yesno, isset($data->isvisitorpublished) ? $data->isvisitorpublished : 0, __('Select visitor published', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required')) . '</div>
                    </div>';
            if($data->field != 'termsconditions') {
                $sec = substr($data->field, 0, 8); //get section_
                if($sec != 'section_'){
                    $html .= '<div class="jslm_popup-field-wrapper">
                                    <div class="jslm_popup-field-title">' . __('Required', 'learn-manager') . '</div>
                                    <div class="jslm_popup-field-obj">' . JSLEARNMANAGERformfield::select('required', $yesno, isset($data->required) ? $data->required : 0, __('Select required', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required')) . '</div>
                                </div>';
                }
            }else{
                $html .= '<div class="jslm_popup-field-wrapper">
                             <div class="jslm_popup-field-title">' . __('Terms And Conditions Page', 'learn-manager') . '</div>
                             <div class="jslm_popup-field-obj">' . JSLEARNMANAGERformfield::select('userfieldparams', JSLEARNMANAGERincluder::getJSModel('postinstallation')->getPageList() , isset($data->userfieldparams) ? $data->userfieldparams : '', '', array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required')) . '</div>
                         </div>';
            }

        }

        if ($data->cannotsearch == 0) {
            $html .= '<div class="jslm_popup-field-wrapper">
                        <div class="jslm_popup-field-title">' . __('User Search', 'learn-manager') . '</div>
                        <div class="jslm_popup-field-obj">' . JSLEARNMANAGERformfield::select('search_user', $yesno, isset($data->search_user) ? $data->search_user : 0, __('Select user search', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required')) . '</div>
                    </div>';
            $html .= '<div class="jslm_popup-field-wrapper">
                        <div class="jslm_popup-field-title">' . __('Visitor Search', 'learn-manager') . '</div>
                        <div class="jslm_popup-field-obj">' . JSLEARNMANAGERformfield::select('search_visitor', $yesno, isset($data->search_visitor) ? $data->search_visitor : 0, __('Select visitor search', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required')) . '</div>
                    </div>';
        }
        $showonlisting = true;
        if($data->fieldfor == 3 ){
            $showonlisting = false;
        }
        if (($data->isuserfield == 1 || $data->cannotshowonlisting == 0) && $showonlisting == true) {
            $html .= '<div class="jslm_popup-field-wrapper">
                        <div class="jslm_popup-field-title">' . __('Show On Listing', 'learn-manager') . '</div>
                        <div class="jslm_popup-field-obj">' . JSLEARNMANAGERformfield::select('showonlisting', $yesno, isset($data->showonlisting) ? $data->showonlisting : 0, __('Select show on listing', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required')) . '</div>
                    </div>';
        }
        $html .= JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager');
        $html .= JSLEARNMANAGERformfield::hidden('id', $data->id);
        $html .= JSLEARNMANAGERformfield::hidden('isuserfield', $data->isuserfield);
        $html .= JSLEARNMANAGERformfield::hidden('fieldfor', $data->fieldfor);
        $html .= JSLEARNMANAGERformfield::hidden('_wpnonce', wp_create_nonce('fieldordering-form'));
        $html .='<div class="jslm_js-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-1 js-col-md-offset-1">
                    ' . JSLEARNMANAGERformfield::submitbutton('save', __('Save', 'learn-manager'), array('class' => 'button'));
        if ($data->isuserfield == 1) {
            $html .= '<a id="jslm_user-field-anchor" href="'.admin_url('admin.php?page=jslm_fieldordering&jslmslay=formuserfield&jslearnmanagerid=' . $data->id . '&ff='.$data->fieldfor).'"> ' . __('Advanced', 'learn-manager') . ' </a>';
        }

        $html .='</div>
            </form>';
        return ($html);
    }

    function deleteUserField($id){
        if (!is_numeric($id))
           return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT field,fieldfor FROM `#__js_learnmanager_fieldsordering` WHERE id = " . $id;
        $db->setQuery($query);
        $result = $db->loadObject();
        $row = JSLEARNMANAGERincluder::getJSTable('fieldsordering');
        if ($this->userFieldCanDelete($result) == true) {
            if (!$row->delete($id)) {
                return JSLEARNMANAGER_DELETE_ERROR;
            }else{
                return JSLEARNMANAGER_DELETED;
            }
        }
        return JSLEARNMANAGER_IN_USE;
    }

    function enforceDeleteUserField($id){
        if (is_numeric($id) == false)
           return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT field,fieldfor FROM `#__js_learnmanager_fieldsordering` WHERE id = " . $id;
        $db->setQuery($query);
        $result = $db->loadObject();
        $row = JSLEARNMANAGERincluder::getJSTable('fieldsordering');
        if ($this->userFieldCanDelete($result) == true) {
            if (!$row->delete($id)) {
                return JSLEARNMANAGER_DELETE_ERROR;
            }else{
                return JSLEARNMANAGER_DELETED;
            }
        }
        return JSLEARNMANAGER_IN_USE;
    }

    function userFieldCanDelete($field) {
        $fieldname = $field->field;
        $fieldfor = $field->fieldfor;

        if($fieldfor == 1){//for deleting a course field
            $table = "course";
        }elseif($fieldfor == 2){//for deleting a lecture field
            $table = "course_section_lecture";
        }elseif($fieldfor == 3){//for deleting a registration field
            $table = "user";
        }
        $db = new jslearnmanagerdb();
        $query = ' SELECT
                    ( SELECT COUNT(id) FROM `#__js_learnmanager_'.$table.'` WHERE
                        params LIKE \'%"' . $fieldname . '":%\'
                    )
                    AS total';
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getUserfieldsfor($fieldfor) {
        if (!is_numeric($fieldfor))
            return false;
        if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        
        $db = new jslearnmanagerdb();
        $query = "SELECT field,userfieldparams,userfieldtype FROM `#__js_learnmanager_fieldsordering` WHERE fieldfor = " . $fieldfor . " AND isuserfield = 1 AND " . $published;
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function getUserFieldbyId($id, $fieldfor) {
        $db = new jslearnmanagerdb();
        if ($id) {
            if (is_numeric($id) == false)
                return false;
            $query = "SELECT * FROM `#__js_learnmanager_fieldsordering` WHERE id = " . $id;
            $db->setQuery($query);
            jslearnmanager::$_data[0]['userfield'] = $db->loadObject();
            $params = jslearnmanager::$_data[0]['userfield']->userfieldparams;
            jslearnmanager::$_data[0]['userfieldparams'] = !empty($params) ? json_decode($params, True) : '';
        }
        $query = "SELECT fieldtitle FROM `#__js_learnmanager_fieldsordering` WHERE field LIKE 'section_%' AND fieldfor = 1";
        $db->setQuery($query);
        jslearnmanager::$_data[0]['coursesections'] = $db->loadObjectList();
        jslearnmanager::$_data[0]['fieldfor'] = $fieldfor;
        return;
    }

    function DataForDepandantField(){
        $val = JSLEARNMANAGERrequest::getVar('fvalue');
        $childfield = JSLEARNMANAGERrequest::getVar('child');
        $db = new jslearnmanagerdb();
        $query = "SELECT userfieldparams,fieldtitle, required FROM `#__js_learnmanager_fieldsordering` WHERE field = '".$childfield."'";
        $db->setQuery($query);
        $data = $db->loadObject();
        $decoded_data = json_decode($data->userfieldparams);
        $comboOptions = array();
        $flag = 0;
        foreach ($decoded_data as $key => $value) {
            if($key==$val){
               for ($i=0; $i <count($value) ; $i++) {
                   $comboOptions[] = (object)array('id' => $value[$i], 'text' => $value[$i]);
                   $flag = 1;
               }
            }
        }
        $textvar =  ($flag == 1) ?  __('Select', 'learn-manager').' '.$data->fieldtitle : '';
        $required = '';
        if($data->required == 1){
            $required = 'required';
        }
        if(is_admin())
            $class = 'jslm_inputbox jslm_one';
        else
            $class = 'inputbox jslm_select_style';

        $html = JSLEARNMANAGERformfield::select($childfield, $comboOptions, '',$textvar, array('data-validation' => $required,'class' => $class.' inputbox jslm_select_style lms_select_style lms_select_full_width',"data-live-search"=>"true"));
        
        $phtml = ($html);
        return $phtml;
    }

    function getFieldTitleByFieldAndFieldfor($field,$fieldfor){
        if(!is_numeric($fieldfor)) return false;
        $db = new jslearnmanagerdb();
        $query = "SELECT fieldtitle FROM `#__js_learnmanager_fieldsordering` WHERE field = '".$field."' AND fieldfor = ".$fieldfor;
        $db->setQuery($query);
        $title = $db->loadResult();
        return $title;
    }

    function getUserUnpublishFieldsfor($fieldfor) {
        if (! is_numeric($fieldfor))
            return false;
        $db = new jslearnmanagerdb();
        if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 0 ';
        } else {
            $published = ' published = 0 ';
        }
        $query = "SELECT field FROM `#__js_learnmanager_fieldsordering` WHERE fieldfor = " . $fieldfor . " AND isuserfield = 1 AND " . $published;
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function getFieldTitleByField($field){
        if(!$field)
            return '';
        $db = new jslearnmanagerdb();
        $query = "SELECT fieldtitle FROM `#__js_learnmanager_fieldsordering` WHERE isuserfield = 1 AND field= '".$field."' ";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function getFieldPublishStatusByfield($field,$fieldfor){
        if(!is_numeric($fieldfor)) return false;
        $db = new jslearnmanagerdb();
        $published = JSLEARNMANAGERincluder::getObjectClass('user')->isguest() ? 'isvisitorpublished' : 'published';
        $query = "SELECT $published FROM `#__js_learnmanager_fieldsordering` WHERE fieldfor = $fieldfor AND field = '".$field."'";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function getMessagekey(){
        $key = 'fieldordering';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }
 
    function getAdminUserFieldSearchFormData(){
        $jslms_search_array = array();
        $isadmin = is_admin();
        $title = ($isadmin) ? 'title' : 'jslm_fieldordering';
        $jslms_search_array['title'] = addslashes(trim(JSLEARNMANAGERrequest::getVar($title)));
        $jslms_search_array['ustatus'] = trim(JSLEARNMANAGERrequest::getVar('ustatus' , ''));
        $jslms_search_array['vstatus'] = trim(JSLEARNMANAGERrequest::getVar('vstatus' , ''));
        $jslms_search_array['required'] = trim(JSLEARNMANAGERrequest::getVar('required' , ''));
        $jslms_search_array['pagesize'] = absint(JSLEARNMANAGERrequest::getVar('pagesize'));
        $jslms_search_array['search_from_fieldordering'] = 1;
        return $jslms_search_array;
    }

     
}

?>
