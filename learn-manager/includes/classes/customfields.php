<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcustomfields {
    function formCustomFields($field, $layout=null, $reg_form = 0, $admin_profile_page = 0) {
        if($field->isuserfield != 1 ){
            return false;
        }

        $cssclass = "";
        $html = '';
        $div3 = '';
        $div1 = '';
        $div2 = '';
        $input='';
        $select='';
        $textarea='';
        if($layout == 1){ // For add new course
            $field = $this->userFieldData($field->field, 1);
            if (empty($field)) {
                return;
            }
            if(is_admin()){
                $div1 = 'jslm_js-field-wrapper lms_js-field-wrapper js-row no-margin';
                $lbl = 'jslm_js-field-title lms_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding lms_no-padding';
                $div2 = 'jslm_js-field-obj lms_js-field-obj js-col-lg-9 js-col-md-9 no-padding';
                $div3 = 'jslm_inputbox jslm_one lms_inputbox lms_one';
            }else{
                if(jslearnmanager::$_learn_manager_theme == 1){

                    $div1 = (($field->size == 100) ? 'js-col-md-12' : 'js-col-md-6').' pr-0 lms-form-field-wrp lms-mb20';
                    $lbl =' lms-form-field-title';
                    $div2 = ' lms-form-field';
                    $input=' lms-form-field-input';
                    $select=' lms-form-field-type-select';
                    $textarea=' lms-form-field-type-textarea';
                    $div3='';

                }else{
                    $div1 = 'jslm_row lms_row';
                    $lbl =  'jslm_title lms_title';
                    $div2 = 'jslm_input_field lms_input_field';
                }
            }
        }elseif($layout == 3){ // For Registration
            $field = $this->userFieldData($field->field, 3);
            if (empty($field)) {
                return;
            }
            if(is_admin()){
                $div1 .= 'jslm_js-field-wrapper lms_js-field-wrapper js-row lms-row no-margin';
                $lbl = 'jslm_js-field-title lms_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding lms_no-padding';
                $div2 = 'jslm_js-field-obj lms_js-field-obj js-col-lg-9 js-col-md-9 no-padding';
                $div3 = 'jslm_inputbox lms_inputbox jslm_one lms_one';
            }else{

                $div1 = 'jslm_input_wrapper lms_input_wrapper';
                $lbl =  'jslm_input_title jslm_big_font lms_input_title lms_big_font';
                $div2 = 'jslm_input_field lms_input_field';
                $div3 = 'jslm_input_style lms_input_style';
            }
        }elseif($layout == 2){ // For lecture
            $field = $this->userFieldData($field->field, 2);
            if (empty($field)) {
                return;
            }
            if(is_admin()){
                $div1 = 'jslm_js-field-wrapper lms_js-field-wrapper js-row no-margin';
                $lbl = 'jslm_js-field-title lms_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding';
                $div2 = 'jslm_js-field-obj lms_js-field-obj js-col-lg-9 js-col-md-9 no-padding';
                $div3 = 'jslm_inputbox lms_inputbox jslm_one lms_one';
            }else{
                if(jslearnmanager::$_learn_manager_theme == 1){
                    $div1 = (($field->size == 100) ? 'js-col-md-12' : 'js-col-md-6').' pr-0 lms-form-field-wrp lms-mb20';
                    $lbl =' lms-form-field-title';
                    $div2 = ' lms-form-field';
                    $input=' lms-form-field-input';
                    $select=' lms-form-field-type-select';
                    $textarea=' lms-form-field-type-textarea';
                    $div3='';

                }else{
                    $div1 = 'jslm_input_field lms_input_field';
                    $lbl = 'jslm_input_title lms_input_title';
                    $div2 = 'jslm_input lms_input';
                    $div3 = 'jslm_input_field_style lms_input_field_style';
                }
            }
        }else{
            $div1 = (is_admin()) ? 'jslm_js-form-wrapper lms_js-form-wrapper' : (($field->size == 100) ? 'col-sm-12 col-md-6' : 'col-sm-12 col-md-6');
            $div2 = (is_admin()) ? 'jslearnmanager-value' : 'form-group';
            $lbl = (is_admin()) ? 'control-label' : 'control-label';
        }
        if($reg_form == 1){
            $lbl = '';
        }

        $required = $field->required;
        if($reg_form == 0){
            $html = '<div class="' . $div1 . '">';
            if(($layout == 1 || $layout == 2)){
                $html .= '<div class="' . $lbl . '">';
            }elseif($layout == 3){
                $html .= '<div class="' . $lbl . '">';
            }
        }elseif($reg_form == 1){
            $html .= '<p>';
        }



        if ($required == 1) {
            // if($field->userfieldtype != 'checkbox')
            $html .= $field->fieldtitle . '<font color="red">*</font>';
            if ($field->userfieldtype == 'email')
                $cssclass = "email";
            else
                $cssclass = "required";
        }else {
            $html .= $field->fieldtitle;
            if ($field->userfieldtype == 'email')
                $cssclass = "email";
            else
                $cssclass = "";
        }
        if(($layout == 1 || $layout == 2)){
           $html .= ' </div>';
        }elseif($layout == 3){
            $html .= '</div>';
        }

        $adminFormClass = '';
        // if(is_admin()){
        //         $html .= '<div class="' . $div2 . '">';
        //         $adminFormClass = "jslm_inputbox ";
        // }else{
            if($layout == 1 || $layout == 2){
                $html .= '<div for="'.$field->field.'" class="' . $div2 . '">';
            }elseif($layout == 3){
                $html .= '<div for="'.$field->field.'" class="' . $div2 . '">';
            }

        // }
        //$readonly = $field->readonly ? "'readonly => 'readonly'" : "";
        $readonly = "";
        $maxlength = $field->maxlength ? "$field->maxlength" : "";
        $fvalue = "";
        $value = "";
        $userdataid = "";
        if (isset(jslearnmanager::$_data[0]->id)) { // For course
            $userfielddataarray = json_decode(jslearnmanager::$_data[0]->params);
            $uffield = $field->field;
            if (isset($userfielddataarray->$uffield) || !empty($userfielddataarray->$uffield)) {
                $value = $userfielddataarray->$uffield;
            } else {
                $value = '';
            }
        }
        if($admin_profile_page == 1){
            $html = '';
        }

        switch ($field->userfieldtype) {
            case 'text':
            case 'email':
                $html .= JSLEARNMANAGERformfield::text($field->field, $value, array('class'=>$div3. $input, 'data-validation' => $cssclass, 'maxlength' => $maxlength, $readonly));
                break;
            case 'date':
                $html .= JSLEARNMANAGERformfield::text($field->field, $value, array('class' => 'custom_date '.$div3. $input, 'data-validation' => $cssclass));
                break;
            case 'textarea':
                $html .= JSLEARNMANAGERformfield::textarea($field->field, $value, array('class' => ' '.$div3. $textarea, 'data-validation' => $cssclass, 'rows' => $field->rows, 'cols' => $field->cols, $readonly));
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode($field->userfieldparams);
                    $i = 0;
                    $valuearray = explode(', ',$value);
                    foreach ($obj_option AS $option) {
                        $check = '';
                        if(in_array($option, $valuearray)){
                            $check = 'checked';
                        }
                        $html .= '<div class="jslm_checkbox_cf js-col-md-4 lms-check-box-field">';
                        $html .=    '<input class="jslm_checkbox_input lms-checkbox" type="checkbox"  ' . $check . ' value="' . $option . '" id="' . $field->field . '_' . $i . '" name="' . $field->field . '[]" data-validation="' .$cssclass. '" />';
                        $html .=    '<label for="' . $field->field . '_' . $i . '">' . $option . '</label>';
                        $html .= '</div>';
                        $i++;
                    }
                    $html .= '<p/>';
                } else {
                    $comboOptions = array('1' => $field->fieldtitle);
                    $html .= JSLEARNMANAGERformfield::checkbox($field->field, $comboOptions, $value, array('class' => 'radiobutton'));
                }
                break;
            case 'radio':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    for ($i = 0; $i < count($obj_option); $i++) {
                        $comboOptions[$obj_option[$i]] = "$obj_option[$i]";
                    }
                }
                $jsFunction = '';
                $html .= '<div class="jslm_checkbox_cf js-col-md-12 lms-check-box-field">';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',2);";
                }
                $html .= JSLEARNMANAGERformfield::radiobutton($field->field, $comboOptions, $value, array('data-validation' => $cssclass, 'onclick' => $jsFunction, 'style'=>'width:auto', 'class'=>"jslm_radio_input lms-checkbox"));
                $html .= "</div>";
                break;
            case 'combo':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',1);";
                }
                //end
                $html .= JSLEARNMANAGERformfield::select($field->field, $comboOptions, $value, __('Select', 'learn-manager') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox jslm_select_style '.$select.'',"data-live-search"=>"true", 'data-placeholder'=>__('Select', 'learn-manager') . ' ' .$field->fieldtitle));
                break;
            case 'depandant_field':
                $comboOptions = array();
                if ($value != null) {
                    if (!empty($field->userfieldparams)) {
                        $obj_option = $this->getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                        foreach ($obj_option as $opt) {
                            $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                        }
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "');";
                }
                //end
                $html .= JSLEARNMANAGERformfield::select($field->field, $comboOptions, $value, __('Select', 'learn-manager') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox jslm_select_style '.$select.'',"data-live-search"=>"true", 'data-placeholder'=>__('Select', 'learn-manager') . ' ' . $field->fieldtitle));
                break;
            case 'multiple':

                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                $array = $field->field;
                $array .= '[]';
                $valuearray = explode(', ', $value);
                $html .= JSLEARNMANAGERformfield::select($array, $comboOptions, $valuearray, __('Select', 'learn-manager') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'multiple' => 'multiple', 'class' => 'inputbox jslm_select_style '.$select.' ' ,"data-live-search"=>"true" ,"multiple"=>"multiple"));
                break;
            case 'file':
                if($layout == 1 && is_admin()){
                    $div3 .= " jslm_upload_border";
                }else{
                    $div3 .= ' jslm_padding_upl_file';
                }
                if(is_admin()){
                    $div3 = " ";
                }
                if($value != ""){
                    $cssclass = " ";
                }
                $html .= '<input type="file" class="'.$div3.'" name="'.$field->field.'" id="'.$field->field.'" data-validation="'.$cssclass.'" value="'.$value.'" onchange="checkcustomfile(this)" />';
                $image_file_type = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('image_file_type');
                $doc_file_type = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('file_file_type');
                $allowed_size = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('allowed_file_size');
                $fileext  = '';
                if($image_file_type != ""){
                    $fileext .= ',';
                    $fileext .= $doc_file_type;
                }
                if($fileext == ""){
                    $fileext = $doc_file_type;
                }
                if($fileext != ""){
                    $fileext = explode(',', $fileext);
                    $fileext = array_unique($fileext);
                    $fileext = implode(',', $fileext);
                    $html .= '<div id="jslms_cust_file_ext" class="lms_cust_file_ext">'.__('Files','learn-manager').'&nbsp;('.$fileext.')<br> '.__('Maximum Size','learn-manager').' '.$allowed_size.'(kb)</div>';
                }
                if($value != null){
                    $html .= JSLEARNMANAGERformfield::hidden($field->field.'_1', 0);
                    $html .= JSLEARNMANAGERformfield::hidden($field->field.'_2',$value);
                    $jsFunction = "deleteCutomUploadedFile('".$field->field."_1')";
                    $html .='<div class="jslm_uploaded_file_wraper">';
                    $html .='<div class="jslm_uploaded_box">';
                    $html .='<div class="jslm_uploaded '.$field->field.'_1" >'.$value.'';
                    $html .= '</div>';
                    $html .= "<a class='jslm_delete_file' href='#' onClick=".$jsFunction." >". esc_html__('Delete', 'learn-manager')."</a>";
                    $html .= '</div>';
                    $html .= '</div>';
                }
                break;
        }

        if($admin_profile_page == 1){
            return $html;
        }

        if($reg_form == 1){
            $html .= '</p>';
        }elseif($layout==1 || $layout==2){
            $html .='</div>';
            $html .= '</div>';
        }elseif($layout == 3) {
            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

    function formCustomFieldsForSearch($field, &$i, $isadmin = 0,$search_popup=0) {
        if($field->isuserfield != 1 ){
            return false;
        }
        $cssclass = "";
        $html = '';
        $i++;
        $required = $field->required;
        $input='';
        $select='';
        $textarea='';

        if(jslearnmanager::$_learn_manager_theme == 1){
            $div1 = (($field->size == 100) ? 'js-col-md-12' : 'js-col-md-6').' pr-0 lms-form-field-wrp lms-mb20';
            $lbl =' lms-form-field-title';
            $div3 = ' lms-form-field';
            $input=' lms-form-field-input';
            $select=' lms-form-field-type-select';
            $textarea=' lms-form-field-type-textarea';

        }else{
            // $div3 = 'form-group';
            $div1 = 'jslm_row lms_row';
            $lbl =  'jslm_title lms_title';
            $div3 = 'jslm_input_field lms_input_field';
        }



        $html = '<div class="' . $div1 . '"> ';
        if(jslearnmanager::$_learn_manager_theme == 1){
            $html .= '<div class="' . $lbl . '">';
            $html .= $field->fieldtitle;
            $html .= ' </div>';
            $html .= ' <div class="' . $div3 . '">';
        }else{
            $html .= ' <div class="' . $div3 . '">';
            $html .= '<div class="' . $lbl . '">';
            $html .= $field->fieldtitle;
            $html .= ' </div>';
        }

        $readonly = ''; //$field->readonly ? "'readonly => 'readonly'" : "";
        $maxlength = ''; //$field->maxlength ? "'maxlength' => '".$field->maxlength : "";
        $fvalue = "";
        $value = null;
        $userdataid = "";
        $userfielddataarray = array();
        if (isset(jslearnmanager::$_data['filter']['params'])) {
            $userfielddataarray = jslearnmanager::$_data['filter']['params'];
            $uffield = $field->field;

            //had to user || oprator bcz of radio buttons

            if (isset($userfielddataarray[$uffield]) || !empty($userfielddataarray[$uffield])) {
                $value = $userfielddataarray[$uffield];
            } else {
                $value = '';
            }
        }

        switch ($field->userfieldtype) {
            case 'text':
            case 'file':
            case 'email':
                $html .= JSLEARNMANAGERformfield::text($field->field, $value, array('class'=> $input, 'data-validation' => $cssclass,'placeholder' =>$field->fieldtitle, $maxlength, $readonly));
                break;
            case 'date':
                $html .= JSLEARNMANAGERformfield::text($field->field, $value, array('class' => 'custom_date' .$input, 'data-validation' => $cssclass));
                break;
            case 'editor':
                $html .= wp_editor(isset($value) ? $value : '', $field->field, array('media_buttons' => false, 'data-validation' => $cssclass));
                break;
            case 'textarea':
                $html .= JSLEARNMANAGERformfield::textarea($field->field, $value, array('class' => $textarea, 'data-validation' => $cssclass, 'rows' => $field->rows, 'cols' => $field->cols, $readonly));
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode($field->userfieldparams);
                    $i = 0;
                    $valuearray = explode(', ',$value);
                    foreach ($obj_option AS $option) {
                        $check = '';
                        if(in_array($option, $valuearray)){
                            $check = 'checked';
                        }
                        $html .= '<div class="jslm_checkbox_cf js-col-md-4 lms-check-box-field">';
                        $html .=    '<input class="jslm_checkbox_input lms-checkbox" type="checkbox"  ' . $check . ' value="' . $option . '" id="' . $field->field . '_' . $i . '" name="' . $field->field . '[]" data-validation="' .$cssclass. '" />';
                        $html .=    '<label for="' . $field->field . '_' . $i . '">' . $option . '</label>';
                        $html .= '</div>';
                        $i++;
                    }
                    $html .= '<p/>';
                } else {
                    $comboOptions = array('1' => $field->fieldtitle);
                    $html .= JSLEARNMANAGERformfield::checkbox($field->field, $comboOptions, $value, array('class' => 'radiobutton'));
                }
                break;
            case 'radio':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    for ($i = 0; $i < count($obj_option); $i++) {
                        $comboOptions[$obj_option[$i]] = "$obj_option[$i]";
                    }
                }
                $jsFunction = '';
                $html .= '<div class="jslm_checkbox_cf js-col-md-12 lms-check-box-field">';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',2);";
                }
                $html .= JSLEARNMANAGERformfield::radiobutton($field->field, $comboOptions, $value, array('data-validation' => $cssclass, 'onclick' => $jsFunction, 'style'=>'width:auto', 'class'=>"jslm_radio_input lms-checkbox"));
                $html .= "</div>";
                break;
             case 'combo':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',1);";
                }
                //end
                $html .= JSLEARNMANAGERformfield::select($field->field, $comboOptions, $value, __('Select', 'learn-manager') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox jslm_select_style '.$select.'',"data-live-search"=>"true", 'data-placeholder'=>__('Select', 'learn-manager') . ' ' .$field->fieldtitle));
                break;

            case 'depandant_field':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = $this->getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                    if (!empty($obj_option)) {
                        foreach ($obj_option as $opt) {
                            $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                        }
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "');";
                }
                //end
                $html .= JSLEARNMANAGERformfield::select($field->field, $comboOptions, $value, __('Select', 'learn-manager') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => '  inputbox jslm_select_style lms_select_style jslm_select_full_width lms_select_full_width' ,'data-placeholder'=>__('Select', 'learn-manager') . ' ' . $field->fieldtitle));
                break;
            case 'multiple':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                $array = $field->field;
                $array .= '[]';
                $html .= JSLEARNMANAGERformfield::select($array, $comboOptions, $value, __('Select', 'learn-manager') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'multiple' => 'multiple', 'class' => 'inputbox jslm_select_style lms_select_style jslm_select_full_width lms_select_full_width ' ,"data-live-search"=>"true" ,"multiple"=>"multiple", 'data-placeholder'=>__('Select', 'learn-manager') . ' ' . $field->fieldtitle));
                break;
        }
        if($isadmin == 1){
            return $html;

        }
        $html .= '</div></div>';
        return $html;

    }

    function showCustomFields($field, $fieldfor, $params, $layout = 0,$row=null) { // $row in case for listing, for lecture detail
        $html = '';
        $fvalue = '';

        if(!empty($params)){
            $data = json_decode($params,true);
            if(!empty($data)){
                if(array_key_exists($field->field, $data)){
                    $fvalue = $data[$field->field];
                }
            }
        }

        if($fieldfor == 1){ // For Detail
            $return_array[0] = $field->fieldtitle;
            $html = '';
            if($field->userfieldtype=='file'){
                if($fvalue !=null){
                    if($layout == 0){ // For course
                        $path = admin_url("?page=course&action=jslmstask&task=downloadbyname&id=".jslearnmanager::$_data[0]['coursedetail']->course_id."&name=".$fvalue."&layout=".$layout);
                    }elseif($layout == 2){ // For lecture
                       $path = admin_url("?page=lecture&action=jslmstask&task=downloadbyname&id=".jslearnmanager::$_data['lecture']->lecture_id."&name=".$fvalue."&layout=".$layout);
                    }elseif($layout == 3){ // For Student Profile
                       $path = admin_url("?page=student&action=jslmstask&task=downloadbyname&id=".jslearnmanager::$_data['profile']->user_id."&name=".$fvalue."&layout=".$layout);
                    }elseif($layout == 4){ // For Instructor Profile
                       $path = admin_url("?page=instructor&action=jslmstask&task=downloadbyname&id=".jslearnmanager::$_data['profile']->user_id."&name=".$fvalue."&layout=".$layout);
                    }
                    if(strlen($fvalue) > 15){
                        $fvalue = substr($fvalue, 0 , 15).'.....';
                    }
                    $html .= '
                        <div class="jslms_file lms_file">
                             ( ' . $fvalue . ' ) ' . '
                            <a class="button" href="' . $path . '">' . esc_html__('Download', 'learn-manager') . '</a>
                        </div>';
                }
                $return_array[1] = $html;

            }else{
                $return_array[1] = $fvalue;
            }
            return $return_array;

        }elseif($fieldfor == 2){  // For Listing
            $return_array[0] = $field->fieldtitle;
            $return_array[1] = $fvalue;
            return $return_array;
        }
    }

    function userFieldsData($fieldfor,$section = 0,$listing = null) {
        if(!is_numeric($fieldfor))
            return '';
        if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1' ;
        } else {
            $published = ' published = 1 ';
        }
        if ($section != 0) {
            $published .= ' AND section = '.$section ;
        } else {
            $published .= '';
        }

        $db = new jslearnmanagerdb();
        $inquery = '';
        if ($listing == 1) {
            $inquery = ' AND showonlisting = 1 ';
        }
        $db = new jslearnmanagerdb();
        $query = "SELECT field,fieldtitle,isuserfield,userfieldtype,userfieldparams  FROM `#__js_learnmanager_fieldsordering` WHERE isuserfield = 1 AND " . $published . " AND fieldfor =" . $fieldfor . $inquery." ORDER BY ordering";
        $db->setQuery($query);
        $data = $db->loadObjectList();
        return $data;
    }

    function userFieldsForSearch($fieldfor) {
        if(!is_numeric($fieldfor))
            return '';
        $db = new jslearnmanagerdb();
        if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
            $inquery = ' isvisitorpublished = 1 AND search_user =1';
        } else {
            $inquery = ' published = 1 AND search_visitor =1';
        }

        $db = new jslearnmanagerdb();
        $query = "SELECT 'rows',cols,required,field,fieldtitle,isuserfield,userfieldtype,userfieldparams
                ,depandant_field  FROM `#__js_learnmanager_fieldsordering` WHERE isuserfield = 1 AND " . $inquery . " AND fieldfor =" . $fieldfor ."  ORDER BY ordering ";
        $db->setQuery($query);
        $data = $db->loadObjectList();
        return $data;
    }

    function userFieldData($field, $fieldfor, $section = null) {

        if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $db = new jslearnmanagerdb();
        $ff = '';
        if($fieldfor == 1){
            $ff = " AND fieldfor = 1";
        }
        $query = "SELECT field,fieldtitle,required,isuserfield,userfieldtype,readonly,maxlength,depandant_field,userfieldparams,'rows',cols,size  from `#__js_learnmanager_fieldsordering` WHERE isuserfield = 1 AND " . $published . " AND field ='" . $field . "'" . $ff;
        $db->setQuery($query);
        $data = $db->loadObject();
        return $data;
    }

    function getDataForDepandantFieldByParentField($fieldfor, $data) {
        $db = new jslearnmanagerdb();
        if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $value = '';
        $returnarray = array();
        $query = "SELECT field from `#__js_learnmanager_fieldsordering` WHERE isuserfield = 1 AND " . $published . " AND depandant_field ='" . $fieldfor . "'";
        $db->setQuery($query);
        $field = $db->loadResult();
        if ($data != null) {
            foreach ($data as $key => $val) {
                if ($key == $field) {
                    $value = $val;
                }
            }
        }
        $query = "SELECT userfieldparams from `#__js_learnmanager_fieldsordering` WHERE isuserfield = 1 AND " . $published . " AND field ='" . $fieldfor . "'";
        $db->setQuery($query);
        $field = $db->loadResult();
        $fieldarray = json_decode($field);
        foreach ($fieldarray as $key => $val) {
            if ($value == $key)
                $returnarray = $val;
        }
        return $returnarray;
    }

}

?>