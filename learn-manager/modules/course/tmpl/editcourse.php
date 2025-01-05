<div class="jslm_main-up-wrapper">
<?php
if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
$msgkey = JSLEARNMANAGERincluder::getJSModel('lecture')->getMessagekey();
if($msgkey != "unknown")
  JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
function getDataRow($title, $value) {
    $html = '<div class="jslm_row">
                <span class="jslm_left_data">' . $title . '</span>';
                if(isset($value) && $value != ''){
                   $html .= '<span class="jslm_right_data">' . $value . '</span>';
                }else{
                   $html .= '<span class="jslm_right_data">------</span>';
                }
    $html .= '</div>';
    return $html;
}

function additionfields($title,$value,$i){
    $html = '';
    if($i%2 != 0){
        $html = '<div class="jslm_custom_field">';
    }
        $html .= '<div class="jslm_heading_right">
            <span class="jslm_heading">' . __($title,"learn-manager") . ':</span>';
            if($value != ""){
                $html .=  '<span class="jslm_text">' . __($value,"learn-manager") . '</span>';
            }
        $html .= "</div>";
    if($i%2 == 0){
        $html .= '</div>';
    }
    return $html;
}

function checkLinks($name) {
    foreach (jslearnmanager::$_data[2] as $field) {
       $array =  array();
       $array[0] = 0;
       switch ($field->field) {
           case $name:
           if($field->showonlisting == 1){
               $array[0] = 1;
               $array[1] =  $field->fieldtitle;
           }
           return $array;
           break;
       }
    }
    return $array;
}

function checkshareLinks($name) {
    $print = false;
    $configname = $name;

    $config_array = jslearnmanager::$_data['config'];
    if ($config_array["$configname"] == 1) {
        $print = true;
    }
    return $print;
}

?>
<div id="loading" class="jslm_loader_loading" style="display: none"><img  alt="<?php echo esc_attr(__("image","learn-manager")); ?>" title="<?php echo esc_attr(__("image","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/loading.gif'); ?>"></div>
<div class="jslm_content_wrapper"> <!-- Body Here -->
    <div class="jslm_content_data">
        <div class="jslm_search_content no-border no-padding-bottom">
            <div class="jslm_top_title">
                <div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Edit course","learn-manager"); ?></h3></div>
                <?php if(jslearnmanager::$_error_flag_message == null){ ?>
                    <div class="jslm_right_data">
                        <span class="jslm_sorting">
                            <?php if(jslearnmanager::$_data[0]['coursedetail']->isapprove == 1){
                                $status = 'Approved';
                            }elseif(jslearnmanager::$_data[0]['coursedetail']->isapprove == 0){
                                $status = 'Waiting for approval';
                            }elseif(jslearnmanager::$_data[0]['coursedetail']->isapprove == 2){
                                $status = 'Rejected';
                            } ?>
                            <span class="jslm_link"><?php echo esc_html(__($status,"learn-manager")); ?></span>
                        </span>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php if(isset(jslearnmanager::$_error_flag_message)){
        echo jslearnmanager::$_error_flag_message;
    }elseif(jslearnmanager::$_error_flag == null){ ?>
        <div class="jslm_detail_wrapper">
            <!-- First Row -->
            <div class="jslm_top_wrapper">
            <?php if (!empty(jslearnmanager::$_data[0]['coursedetail'])) {
                $totalQuiz = apply_filters("jslm_quiz_course_totalquiz_for_admin_coursedetail",0);
                $curdate = date_i18n('Y-m-d');
                $row = jslearnmanager::$_data[0]['coursedetail']; ?>
                <div class="jslm_heading">
                    <div class="jslm_left_wrap">
                        <div class="jslm_title">
                            <h2><?php echo esc_html(__($row->title,"learn-manager")); ?></h2>
                            <?php do_action("jslm_coursereview_coursedetail_avgreview_after_title",$row); ?>
                        </div>
                    </div>
                    <div class="jslm_right_wrap">
                        <div class="jslm_enroll_btn_wrap">
                            <?php $htmldata = apply_filters("jslm_paidcourse_instructor_edit_course_price_tag",false,$row);
                            if($row->access_type == "Free"){ ?>
                                <div class="jslm_price_btn">
                                    <h3 class="jslm_free"><?php echo esc_html("Free","learn-manager"); ?></h3>
                                </div>
                            <?php }elseif($htmldata){
                                echo esc_html($htmldata);
                            } ?>
                            <div class="jslm_enroll_course_btn">
                                <h3 class="jslm_enroll_btn_h3">
                                    <a title="<?php echo esc_attr(__("Edit Course","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'addcourse', 'jslearnmanagerid'=>jslearnmanager::$_data[0]['coursedetail']->course_id))); ?>"><span class="fa fa-edit"></span><?php echo __("Edit Course","learn-manager"); ?></a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
            <div class="jslm_middle_potion">
                <div class="jslm_middle_top">
                    <?php if($row->instructor_image != '' && $row->instructor_image != null){
                        $imageadd = $row->instructor_image;
                    }else{
                        $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-instuctor.png';
                    } ?>
                    <div class="jslm_logo_wrp">
                        <div class="jslm_detail_img_wrap">
                            <img alt="<?php echo esc_attr(__("Instructor Image","learn-manager")); ?>" title="<?php echo esc_attr(__("Instructor Image","learn-manager")); ?>" src="<?php echo esc_attr($imageadd); ?>">
                        </div>
                        <div class="jslm_detail_title"><?php echo esc_html(__($row->instructor_name,"learn-manager")); ?></div>
                    </div>
                    <div class="jslm_logo_wrp">
                        <img title="<?php echo esc_attr(__("Category","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/category.png">
                        <?php echo esc_html(__($row->category,"learn-manager")); ?>
                    </div>
                    <div class="jslm_logo_wrp">
                        <img title="<?php echo esc_attr(__("Lectures","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/lessons.png">
                        <?php echo esc_html(round($row->total_lessons,0)); ?>
                    </div>

                    <div class="jslm_logo_wrp">
                        <img title="<?php echo esc_attr(__("Students","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/members.png">
                        <?php echo esc_html($row->total_students); ?>
                    </div>
                    <div class="jslm_logo_wrp">
                        <img title="<?php echo esc_attr(__("Course duration","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/course-duration.png">
                        <?php echo esc_html($row->duration); ?>
                    </div>
                    <div class="jslm_logo_wrp">
                        <img title="<?php echo esc_attr(__("Course level","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/course-level.png">
                        <?php echo esc_html(__($row->level,"learn-manager")); ?>
                    </div>
                    <div class="jslm_logo_wrp">
                        <img title="<?php echo esc_attr(__("Course language","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/language.png">
                        <?php echo esc_html(__($row->language,"learn-manager")); ?>
                    </div>
                </div>
            </div>
            <div class="jslm_img_wrapper">
                <?php if($row->course_logo !='' && $row->course_logo != null){
                    $imageadd = $row->course_logo;
                }else{
                    $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                }?>
                <img alt="<?php echo esc_attr(__("image","learn-manager")); ?>" title="<?php echo esc_attr(__("image","learn-manager")); ?>" src="<?php echo esc_attr($imageadd); ?>">
            </div>
            <div class="jslm_tabs_wrapper">
                <ul class="nav nav-tabs jslm_ul_menu jslm_ul_display_inline">
                    <li class="jslm_li_border"><a title="<?php echo esc_attr(__("link","learn-manager")); ?>" class="jslm_li_anchor_styling jslm_bigfont jslm_left_border" data-toggle="tab" href="#home"><img id="description" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/description-grey.png"><?php echo esc_html__("Home","learn-manager"); ?></a></li>
                    <li class="active jslm_li_border"><a title="<?php echo esc_attr(__("link","learn-manager")); ?>" class="jslm_li_anchor_styling jslm_bigfont" data-toggle="tab" href="#curriculum"><img id="curriculumimgid" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/curriculum-grey.png"><?php echo esc_html__("Curriculum","learn-manager"); ?></a></li>
                    <?php if(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('showstudentlistincoursedetail') == 1){ ?>
                        <li class="jslm_li_border"><a title="<?php echo esc_attr(__("link","learn-manager")); ?>" class="jslm_li_anchor_styling jslm_bigfont" data-toggle="tab" href="#member"><img id="enrolledstudentsimg" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/members-grey.png"><?php echo esc_html__("Students","learn-manager"); ?></a></li>
                    <?php } ?>
                    <?php do_action('jslm_coursereview_coursedetail_tab_btn',1); ?>
                </ul>
                <div class="tab-content jslm_my_content">
                    <div id="home" class="tab-pane fade"><!-- Home CODE -->
                        <div class="jslm_home_data_wrapper">
                            <?php foreach(jslearnmanager::$_data[2] as $fields){
                                    switch($fields->field){
                                        case 'description': ?>
                                            <div class="jslm_home_data_row">
                                                <div class="jslm_row_heading">
                                                    <h3 class="jslm_row_heading_style"><?php echo esc_html__("Course Description","learn-manager"); ?></h3>
                                                </div>
                                                 <span class="jslm_row_body_text">
                                                    <?php echo html_entity_decode(wp_kses_post(esc_html($row->c_description,'learn-manager'))); ?>
                                                </span>
                                            </div>
                                <?php   break;
                                        case 'learningoutcomes': ?>
                                            <div class="jslm_home_data_row">
                                                <?php if(!empty($row->learningoutcomes) && $row->learningoutcomes != null){ ?>
                                                        <div class="jslm_row_heading">
                                                            <h3 class="jslm_row_learningoutcomes"><?php echo esc_html__("Learning Outcomes","learn-manager"); ?></h3>
                                                        </div>
                                                        <span class="jslm_row_body_text">
                                                            <?php echo html_entity_decode(wp_kses_post(esc_html__($row->learningoutcomes,'learn-manager'))); ?>
                                                        </span>
                                                <?php } ?>
                                            </div>
                                <?php   break;
                                    }
                                }
                                $iscustomfield = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsData(1);
                                if(!empty($iscustomfield)){ ?>
                                <div class="jslm_home_data_row">
                                    <div class="jslm_custom_fields_wrapper">
                                        <div class="jslm_custom_fields_heading">
                                            <h4 class="jslm_heading_style"><?php echo esc_html__("Addition Features","learn-manager"); ?></h4>
                                        </div>
                                        <?php $i = 1; foreach(jslearnmanager::$_data[2] as $fields){
                                                switch($fields->field){
                                                    default:
                                                        if($fields->isuserfield == 1){
                                                            $array = JSLEARNMANAGERincluder::getObjectClass('customfields')->showCustomFields($fields, 1, jslearnmanager::$_data[0]['coursedetail']->params,0);
                                                            echo additionfields($array[0], $array[1],$i);
                                                            if(count($iscustomfield) == $i){
                                                                if($i%2 != 0){
                                                                    echo '</div>';
                                                                }
                                                            }
                                                            $i++;
                                                    break;
                                                    }
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php } ?>
                        </div>
                    </div>
                    <div id="curriculum" class="tab-pane fade in active">
                        <div class="jslm_curriculum_wrapper">
                            <div class="jslm_curriculum_title jslm-margin-bottom">
                                <div class="jslm_row_heading jslm_course_edit_curriculum">
                                    <h3 class="jslm_row_heading_style"><?php echo esc_html__("Curriculum","learn-manager"); ?></h3>
                                </div>
                                <div class="jslm_add_new_btn">
                                    <button data-modal="#myModalFullscreen"><i class="fa fa-plus jslm_add_new_btn_icon"></i><?php echo __("Add New Section","learn-manager"); ?></button>
                                </div>
                            </div>
                            <div id="accordion" class="jslm_panel_group">
                                <div id="section_panel">
                                <?php if(count(jslearnmanager::$_data['sections']) > 0){
                                        $j = 0;
                                        for ($s = 0, $sp = count(jslearnmanager::$_data['sections']); $s < $sp; $s++) {
                                            $sections = jslearnmanager::$_data['sections'][$s];
                                            $countlecture = 0;?>
                                            <div class="jslm_panel_single_group">
                                                <div id="wrapper_<?php echo esc_attr($sections->section_id); ?>" class="jslm-panel-heading" >
                                                    <div class="jslm_edit_section_left jslm_edit_heading_width">
                                                        <h6 class="jslm_section_heading">
                                                            <a title="<?php echo esc_attr(__("link","learn-manager")); ?>" href="#panelBodyOne_<?php echo esc_attr($sections->section_id); ?>" class="accordion-toggle accordion collapsed jslm_accordian_anchor jslm_section_title" data-toggle="collapse" ><?php echo esc_html__($sections->section_name , 'learn-manager');?>
                                                            </a>
                                                        </h6>
                                                    </div>
                                                    <div class="jslm_edit_section_right">
                                                        <span id="jslm_span_<?php echo esc_attr($sections->section_id); ?>" class="jslm_section_action">
                                                            <a title="<?php echo esc_attr(__("Add New Lecture","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'lecture', 'jslmslay'=>'addlecture', 'jslearnmanagerid'=>'sec_'.$sections->section_id))); ?>" class="jslm_new_sec"><i class="fa fa-plus"></i> <?php echo esc_html__("New Lecture","learn-manager"); ?></a>
                                                            <a title="<?php echo esc_attr(__("Edit Section","learn-manager")); ?>" href="#myModalFullscreen" id="edit_<?php echo esc_attr($sections->section_id); ?>" onclick="getSectionFormData('<?php echo esc_js($sections->section_id); ?>' , '<?php echo esc_js($sections->section_name); ?>')" data-toggle="modal" class="jslm_delete_sec"><i class="fa fa-edit"></i></a>
                                                            <a title="<?php echo esc_attr(__("Delete Section","learn-manager")); ?>" title="<?php echo esc_attr(__('Delete Section','learn-manager')); ?>" href="<?php echo esc_url(wp_nonce_url(jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'action'=>'jslmstask', 'task'=>'removesection', 'jslearnmanagerid' => $sections->section_id)),'delete-section')); ?>" class="jslm_delete_sec" onclick="return confirm('Are you sure to delete this section?')"><i class="fa fa-trash"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div id="panelBodyOne_<?php echo esc_attr($sections->section_id); ?>" class="panel-collapse collapse">
                                                    <div class="container jslm_container_style">
                                                    <?php if(count(jslearnmanager::$_data['lectures'][$sections->section_id]) > 0){
                                                            for ($l = 0, $lp = count(jslearnmanager::$_data['lectures'][$sections->section_id]); $l < $lp; $l++) {
                                                                $j++;
                                                                $lectures = jslearnmanager::$_data['lectures'][$sections->section_id][$l];
                                                                $lectures->progress = 0;?>
                                                                <div class="row jslm_edit_row_style">
                                                                    <a title="<?php echo esc_attr(__("link","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'jslmslay'=>'addlecture', 'jslearnmanagerid'=>'lec_'.$lectures->lecture_id)))?>"  class="jslm_edit_lec_anchor"><span class="jslm_bold_text"><?php echo esc_html__("Lecture","learn-manager").'-'. esc_html__($j , 'learn-manager'); ?>:</span><?php echo esc_html__($lectures->lecture_name , 'learn-manager'); ?>
                                                                    </a>
                                                                    <span class="jslm_edit_section_action ">
                                                                        <a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'jslmslay'=>'addlecture','jslearnmanagerid'=>'lec_'.$lectures->lecture_id))); ?>" title="<?php echo __("Edit Lecture","learn-manager"); ?>"><i class="fa fa-edit"></i></a>
                                                                        <a title="<?php echo esc_attr(__("Delete Lecture","learn-manager")); ?>" href="<?php echo esc_url(wp_nonce_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'action'=>'jslmstask', 'task'=>'removecourselecture', 'jslearnmanagerid' => $lectures->lecture_id  , 'jslearnmanagerpageid'=>jslearnmanager::getPageid())),'delete-lecture')); ?>" class="jslm_delete_sec" onclick="return confirm('Are you sure to delete this lecture?')"><i class="fa fa-trash"></i></a>
                                                                    </span>
                                                                </div>
                                                    <?php   }
                                                        }else{?>
                                                            <div class="alert jslm_alert_danger"><?php echo esc_html__("No Data Found","learn-manager"); ?></div>
                                                    <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                    <!-- /.modal -->
                                <?php }
                                    }else{?>
                                        <div id="no_data_section"  class="alert jslm_alert_danger"><?php echo esc_html__("No Data Found","learn-manager"); ?></div>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal jslm_my_modal" id="myModalFullscreen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
                            <div class="modal-dialog">
                                <div class="modal-content jslm_modal_content">
                                    <div class="modal-header jslm_modal_header">
                                        <button type="button" class="close jslm_close_button_style" data-dismiss="modal" aria-hidden="true"><i class="fa fa-window-close"></i></button>
                                        <h2 class="modal-title jslm_title_heading jslm_custom"><?php echo esc_html__("Add Section","learn-manager"); ?></h2>
                                    </div>
                                    <div class="modal-body jslm_modal_body">
                                        <span class="jslm_search_field">
                                           <span class="jslm_input_title"><?php echo esc_html__("Section Name","learn-manager"); ?></span>
                                           <?php echo wp_kses(JSLEARNMANAGERformfield::text('jslm_sname','',array('placeholder' => __('Enter Section Title','learn-manager'), 'class' => 'jslm_field_style')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                           <span class="jslm_save_button">
                                               <input type="submit" name="save" onclick="insertSection()" class="jslm_save_button_style jslm_save" value="<?php echo esc_html__("Save","learn-manager"); ?>">
                                              <input type="submit" name="save" onclick="insertSection()" class="jslm_save_button_style jslm_saveNclose" data-dismiss="modal" aria-hidden="true" value="<?php echo esc_html__("Save & Close","learn-manager"); ?>">
                                                <input type="button" class="jslm_save_button_style jslm_cancel" data-dismiss="modal" aria-hidden="true" value="<?php echo esc_html__("Cancel","learn-manager"); ?>">
                                           </span>
                                           <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_sid',''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                           <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_cid', JSLEARNMANAGERrequest::getVar('jslearnmanagerid') ? JSLEARNMANAGERrequest::getVar('jslearnmanagerid') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="member" class="tab-pane fade">
                        <div class="jslm_members_wrapper">
                            <div class="jslm_home_data_row">
                                <div class="jslm_row_heading">
                                    <h3 class="jslm_row_heading_style"><?php echo esc_html__("Enrolled Students","learn-manager"); ?></h3>
                                </div>
                            </div>
                            <?php if(count(jslearnmanager::$_data['enrolledstudents']) > 0){
                                for ($co = 0, $cop = count(jslearnmanager::$_data['enrolledstudents']); $co < $cop; $co++) {
                                    $comembers = jslearnmanager::$_data['enrolledstudents'][$co];   ?>
                                <div class="jslm_member_data">
                                    <div class="jslm_left_side">
                                       <div class="jslm_img_wrapper">
                                            <?php if($comembers->image == "" && empty($comembers->image)){
                                                $coimage = $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                                            }else{
                                                $coimage = $comembers->image;
                                            }   ?>
                                          <img alt="<?php echo esc_attr(__("image","learn-manager")); ?>" title="<?php echo esc_attr(__("image","learn-manager")); ?>" src="<?php echo esc_attr($coimage); ?>">
                                       </div>
                                    </div>
                                    <div class="jslm_right_side">
                                        <span class="jslm_text2 jslm_text1"><a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'student','jslmslay'=>'studentprofile','jslearnmanagerid'=>$comembers->student_id))); ?>"><?php echo esc_html__($comembers->name , 'learn-manager'); ?></a></span>
                                        <span class="jslm_text2"><?php echo esc_html__($comembers->country_name , 'learn-manager'); ?></span>
                                        <span class="jslm_text2"><?php echo esc_html(date_i18n('d-M-Y', strtotime($comembers->created_at))); ?></span>
                                    </div>
                                </div>
                            <?php   } ?>
                            <input type="hidden" name="total_pages" id="total_pages" value="<?php echo esc_attr(jslearnmanager::$_data['enrolledstudentspagination']); ?>">
                            <input type="hidden" name="pagei" id="pagei" value="<?php echo esc_attr(jslearnmanager::$_data['offset']) + 1; ?>">
                            <?php if(jslearnmanager::$_data['enrolledstudentspagination'] != (jslearnmanager::$_data['offset'] + 1)){ ?>
                            <div id="loadmore" class="jslm_show_button_wrapper">
                                <a title="<?php echo esc_attr(__("link","learn-manager")); ?>" class="jslm_show_more"><?php echo esc_html__("Show More","learn-manager"); ?></a>
                            </div>
                            <?php } ?>
                            <?php   }else{ ?>
                                <div class="alert jslm_alert_danger"><?php echo esc_html__("No Student Enroll.....","learn-manager"); ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php do_action("jslm_coursereview_coursedetail_all_reviews",$row,$uid,jslearnmanager::$_data[0]['coursedetail']->course_id,0,0); ?>
                </div>
            </div>
        </div>
<?php } ?>
    </div>
</div>
</div>


