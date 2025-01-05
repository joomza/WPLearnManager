<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLearnManagerwidgetModel {

    function __construct() {
    }

    function getCoursesWidgetHTMl($courses,$pageid,$heading,$description,$number_of_columns,$layout_name){
        $html = '';
        $html .= '<div class="jslearnmanager_courses_widget_wrapper" >
                    <div class="jslearnmanager_courses_widget_top" >
                        <div class="jslearnmanager_courses_widget_title" >
                            '. __($heading,'learn-manager') .'
                        </div>';
        if(trim($description) != '' ){
            $html .='
                    <div class="jslearnmanager_courses_widget_desc" >
                        '. __($description,'learn-manager') .'
                    </div>';
        }
        $html .='
                    </div>
                    <div class="jslearnmanager_courses_widget_data_wrapper" > ';
                        $number_of_columns_css = '';
                        if($number_of_columns != 1){
                            $percentage = 100 / $number_of_columns;
                            $number_of_columns_css = 'style="width:calc('.$percentage.'% - 20px);"';
                        }
                        foreach ($courses as $row) {
                            $html .= '
                            <div class="jslm_data_wrapper">
                                <div class="jslm_left">
                                    <div class="jslm_left_img_wrp">';
                                        if($row->fileloc !='' && $row->fileloc != null){
                                            $imageadd = $row->fileloc;
                                        }else{
                                            $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                                        }
                                        $html .= '<img src=' . $imageadd . '>
                                        <div class="overlay">
                                            <div class="jslm_links">
                                                <span class="jslm_link_icon">';
                                                    $courselink = esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'coursedetails', 'jslearnmanagerid'=> $row->course_id , 'jslearnmanagerpageid' => jslearnmanager::getPageid()))); 
                                                    $html .= '<a href="' . $courselink . '"><i class="fa fa-eye"></i></a>
                                                </span>
                                            </div>
                                            <div class="jslm_overlay_bottom">
                                                <div class="jslm_overlay_bottom_img">';
                                                    $instructor_image = isset($row->instructor_image) && $row->instructor_image != '' ? $row->instructor_image : JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                                                    $html .= '<img src="' . $instructor_image . '">
                                                </div>
                                                <div class="jslm_overlay_bottom_name">
                                                    <span class="jslm_overlay_bottom_instructor">';
                                                        
                                                        $instructorlink = esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'instructor','jslmslay'=>'instructordetails','jslearnmanagerid'=>$row->instructor_id , 'jslearnmanagerpageid' => jslearnmanager::getPageid())));
                                                        $instructor_name = isset($row->instructor_name) ? esc_html__($row->instructor_name,'learn-manager') : __("Admin","learn-manager");

                                                        $html .= '<a class="jslm_instructor_link" href="' . $instructorlink . '">' . $instructor_name . '</a></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jslm_right">
                                    <div class="jslm_right_top">
                                        <div class="jslm_right_top_left">
                                            <h4 class="jslm_heading_style">
                                                <a class="jslm_course_title title_anchor" href="' . $courselink . '">'. __($row->title,"learn-manager") .'</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="jslm_right_bottom">
                                    <div class="jslm_right_bottom_left">
                                        <span class="jslm_logos">
                                            <span class="jslm_logo_wrp">
                                                <img alt="' . __("Category","learn-manager") . '" src="' . esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/category.png') . '">
                                                ' . __($row->category,"learn-manager") . '
                                            </span>
                                            <span class="jslm_logo_wrp">
                                                <img alt="' . __("Lectures","learn-manager") . '" src="' . esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/lesson.png') . '">
                                                ' . __($row->total_lessons,"learn-manager") . '
                                            </span>
                                            <span class="jslm_logo_wrp">
                                                <img alt="' . __("Students","learn-manager") . '" src="' . esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/students.png') . '">
                                                ' . __($row->total_students,"learn-manager") . '
                                            </span>
                                        </span>
                                    </div>
                                    <div class="jslm_right_bottom_right">
                                        <div class="jslm_right_bottom_price">';
                                            $print = array("0" => 1);
                                            $pricehtml = apply_filters("jslm_paidcourse_get_paid_course_price_tag_listing","",$row,$print);
                                            if($pricehtml == ""){
                                                $html .= '<h3 class="jslm_heading_style">' . __("Free","learn-manager") . '</h3>';
                                            }else{
                                                $html .= $pricehtml;
                                            }
                                        $html .= '</div>
                                    </div>
                                </div>
                            </div>';
                        }
        $html .='</div>';
        return $html;
    }

    function getSearchCourse_Widget($title, $showtitle, $fieldtitle, $category, $price, $language, $courselevel, $instructor, $columnperrow) {

        if ($columnperrow <= 0)
            $columnperrow = 1;
        $width = round(100 / $columnperrow);
        $style = "style='width:" . $width . "%'";

        $html = '
                <div id="jslms_mod_wrapper" class="jslearnmanager_search_courses_widget_wrapper">';
        if ($showtitle == 1) {
            $html .= '<div id="jslms-mod-heading"> ' . $title . ' </div>';
        }
        $html .='<form class="learnmanager_form" id="learnmanager_form" method="post" action="' . jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'courselist', 'jslearnmanagerpageid'=>jslearnmanager::getPageid())) . '">';

        if ($fieldtitle == 1) {
            $title = __('Title', 'learn-manager');
            $value = JSLEARNMANAGERformfield::text('coursetitle', '', array('class' => 'inputbox '));
            $html .= '<div class="js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }

        if ($category == 1) {
            $title = __('Category', 'learn-manager');
            $value = JSLEARNMANAGERformfield::select('category', JSLEARNMANAGERincluder::getJSModel('category')->getCategoriesForCombo(), isset(jslearnmanager::$_data[0]['filter']->category) ? jslearnmanager::$_data[0]['filter']->category : '', __('Select','learn-manager') .'&nbsp;'. __('Category', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width '));
            $html .= '<div class="js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }

        if ($price == 1) {
            $title = __('Course Price', 'learn-manager');
            $value = JSLEARNMANAGERformfield::select('currencyid', JSLEARNMANAGERincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jslearnmanager::$_data[0]['filter']->currencyid) ? jslearnmanager::$_data[0]['filter']->currencyid : '', __('Select','learn-manager') .'&nbsp;'. __('Currency', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width sal'));
            $value .= '<div class="jslm_price_wrapper">';
            $value .= JSLEARNMANAGERformfield::text('rangestart', '', array('placeholder' => 'Price start from'));
            $value .= JSLEARNMANAGERformfield::text('rangeend', '', array('placeholder' => 'price end to'));
            $value .= '</div>';

            $html .= '<div class="js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($language == 1) {
            $title = __('Course Language', 'learn-manager');
            $value = JSLEARNMANAGERformfield::select('language', JSLEARNMANAGERincluder::getJSModel('language')->getlanguageForCombo(),'', __('Select','learn-manager') .'&nbsp;'. __('Language', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width '));

            $html .= '<div class="js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($courselevel == 1) {
            $title = __('Course Level', 'learn-manager');
            $value = JSLEARNMANAGERformfield::select('courselevel', JSLEARNMANAGERincluder::getJSModel('courselevel')->getLevelForCombo(), '', __('Select','learn-manager') .'&nbsp;'. __('Course Level', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width '));

            $html .= '<div class="js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($instructor == 1) {
            $title = __('Instructor', 'learn-manager');
            $value = JSLEARNMANAGERformfield::text('instructorname', '', array('placeholder' => 'Instructor'));
            $html .= '<div class="js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        
        $html .= '<div class="js-mod-valwrapper">
                        <div class="bottombutton">                 
                            ' . JSLEARNMANAGERformfield::submitbutton('save', __('Search Course', 'learn-manager'), array('class' => 'jslm_save_button')) . '
                        </div>
                        <div class="bottombutton">
                            <a class="anchor" href="' . jslearnmanager::makeUrl(array('jslmsmod'=>'coursesearch', 'jslmslay'=>'coursesearch', 'jslearnmanagerpageid'=>jslearnmanager::getPageid())) . '"> 
                            ' . __('Advance Search', 'learn-manager') . '
                            </a>
                        </div>
                    </div>
                </form>
                </div>
                
        
            <script >
                jQuery(document).ready(function ($) {
                    jQuery(".jslm_select_full_width").selectable();
                    //Validation
                    $.validate();
                });
            </script>
            ';



        $html .= '<input type="hidden" id="issearchform" name="issearchform" value="1"/>';
        return $html;
    }
}

?>
