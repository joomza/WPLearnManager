<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
$msgkey = JSLEARNMANAGERincluder::getJSModel('lecture')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_style('jslms-mainbootstrap-css', jslearnmanager::$_pluginpath . 'includes/css/admin_tabs.css');
?>
<script type="text/javascript">
    var acc = document.getElementsByClassName("accordion");
    var i;
    for (i = 0; i < acc.length; i++){
        acc[i].onclick = function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight){
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        }
    }

    // For accordion
    function toggleIcon(e) {
        jQuery(e.target)
          .prev('.panel-heading')
          .find(".more-less")
          .toggleClass('fa-plus fa-minus');
    }
    jQuery('.panel-group').on('hidden.bs.collapse', toggleIcon);
    jQuery('.panel-group').on('shown.bs.collapse', toggleIcon);

    jQuery(function () {
        var url = window.location.hash;
        if (url != "") {
            jQuery(".tab-pane").removeClass("active").addClass("fade");
            jQuery(url).addClass("active in").removeClass("fade");
            jQuery('a[href="'+ url +'"]').tab('show');
        }
    });
    var sectionid = '';
    var sectionname = '';
    function insertSection() {
        jQuery("#notsaveerror").remove();
        jQuery("#emptyerror").remove();
        var section_name=document.getElementById("jslm_sname").value;
        var course_id=document.getElementById("jslm_cid").value;
        var section_id = document.getElementById("jslm_sid").value;
        if(section_name != "" && course_id != ""){
            jQuery("#loading").show();
            jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "storeSection",name:section_name,course_id:course_id,section_id:section_id,redirect_id:1} , function (data,response) {
                if(data){
                    document.getElementById("jslm_sname").value = "";
                    document.getElementById("jslm_sid").value = "";
                    if(document.getElementById("section-message")){
                        jQuery('#section-message').remove('');
                    }
                    var json_section = jQuery.parseJSON(data);
                    var decodeHTML = function (html) {
                        var txt = document.createElement('textarea');
                        txt.innerHTML = html;
                        return txt.value;
                    };

                    json_section['html'] = decodeHTML(json_section['html']);
                    json_section['newcontent'] = decodeHTML(json_section['newcontent']);
                    if(json_section['msg']){
                        jQuery('span.jslm_search_field').append().before(json_section['html']);
                        jQuery("#loading").hide();
                        return false;
                    }
                    if(json_section["flag"] == 1){
                        if(document.getElementById('no_data_section')){
                            jQuery('#no_data_section').remove();
                        }
                        jQuery('span.jslm_search_field').append().before(json_section['html']);
                        jQuery("#section_panel").append(json_section["newcontent"]);
                    }else{
                        jQuery('span.jslm_search_field').append().before(json_section['html']);
                        jQuery('#wrapper_'+section_id).html('');
                        jQuery('#wrapper_'+section_id).append(json_section["newcontent"]);
                    }
                    jQuery("#loading").hide();
                }else{
                    jQuery( "#jslm_sname" ).append().after("<div id='notsaveerror' class='validation' style='color:red;margin-bottom: 20px;'>Error While Saving Section Name!</div>");
                }
            });
        }else{
            jQuery( "#jslm_sname" ).append().after("<div id='emptyerror' class='validation' style='color:red;margin-bottom: 20px;'>Section name required!</div>");
        }
    };

    function getSectionFormData(id,name){
        sectionid = id;
        document.getElementById('jslm_sid').value = sectionid;
        sectionname = name;
        document.getElementById('jslm_sname').value = sectionname;
        if(document.getElementById("section-message")){
            jQuery('#section-message').remove('');
        }
    }

</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#loadmore").click(function(){
            var total_pages = parseInt(jQuery("#total_pages").val());
            var page = parseInt(jQuery("#pagei").val())+1;
            if(page <= total_pages) {
                load_more_data(page, total_pages);
            }
        });

        jQuery("#loadmore_reviews").click(function(){
            var total_pages = parseInt(jQuery("#reviews_pages").val());
            var page = parseInt(jQuery("#reviewpage").val())+1;
            if(page <= total_pages) {
                load_more_reviews(page, total_pages);
            }
        });

        jQuery("#section-modal").on('click',function(){
            document.getElementById("jslm_sname").value = "";
            document.getElementById("jslm_sid").value = "";
            if(document.getElementById("section-message")){
                jQuery('#section-message').remove('');
            }
        });
    });

    function load_more_data(page, total_pages) {
        jQuery("#loadmore").html("");
        jQuery("#loadmore").html("<a class='jslm_show_more'><i class='fa fa-spinner fa-spin'></i>Showing</a>");
        jQuery("#total_pages, #pagei").remove();
        var cid = <?php echo JSLEARNMANAGERrequest::getVar('jslearnmanagerid'); ?>;
        jQuery.get(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "getAllEnrolledStudentinCourse",pagei:page,cid:cid, call_from:1} , function (data,response) {
          if(data){
                jQuery("#loadmore").append().before((data));
                if(page == total_pages){
                    jQuery("#loadmore").hide();
                }
                jQuery("#loadmore").html("");
                jQuery("#loadmore").html("<a class='jslm_show_more'>Show More</a>")
            }else{
                jQuery("#loadmore").html("");
                jQuery("#loadmore").html("<a class='jslm_show_more'>Data No Load</a>")
            }
        });
    }

    function load_more_reviews(page, total_pages) {
        jQuery("#loadmore_reviews").html("");
        jQuery("#loadmore_reviews").html("<a class='jslm_show_more'><i class='fa fa-spinner fa-spin'></i>Showing</a>");
        jQuery("#reviews_pages, #reviewpage").remove();
        var cid = <?php echo JSLEARNMANAGERrequest::getVar('jslearnmanagerid'); ?>;
        jQuery.get(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "coursereview",task: "getReviewOnCourse",pagei:page,cid:cid} , function (data,response) {
            if(data){
                jQuery("#loadmore_reviews").append().before((data));
                if(page == total_pages){
                    jQuery("#loadmore_reviews").hide();
                }
                jQuery("#loadmore_reviews").html("");
                jQuery("#loadmore_reviews").html("<a class='jslm_show_more'>Show More Reviews</a>")
            }else{
                jQuery("#loadmore_reviews").html("");
                jQuery("#loadmore_reviews").html("<a class='jslm_show_more'>Data No Load</a>")
            }
        });
    }

</script>
<?php
    function additionfields($title,$value,$l=0){
        $html = "";
        if($value != ''){
            $html = '<div class="jslm_custom_field">
                    <span class="jslm_heading">' . $title . '</span>';
                if($l == 1){
                    if(strlen($value) > 25){
                        $value = substr($value,0,25).'....';
                    }
                }
            if($value != ""){
                  $html .=  '<span class="jslm_text">' . $value . '</span>';
            }
            $html .='</div>';
        }
        return $html;
    }

?>
<center><div id="loading" style="display: none"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/loading.gif"></div></center>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php  JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <div id="jslearnmanageradmin-wrapper-top">
            <div id="jslearnmanageradmin-wrapper-top-left">
                <div id="jslearnmanageradmin-breadcrunbs">
                    <ul>
                        <li>
                            <a href="admin.php?page=jslearnmanager">
                                <?php echo __('Dashboard', 'learn-manager'); ?>
                            </a>
                        </li>
                        <li><?php echo __('Course Detail','js-support-ticket'); ?></li>
                    </ul>
                </div>
            </div>
            <div id="jslearnmanageradmin-wrapper-top-right">
                <div id="jslearnmanageradmin-help-txt">
                   <a href="<?php echo esc_url(admin_url("admin.php?page=jslearnmanager&jslmslay=help")); ?>" title="<?php echo __('Help','learn-manager'); ?>">
                        <img alt="<?php echo __('Help','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help.png" />
                    </a>
                </div>
                <div id="jslearnmanageradmin-vers-txt">
                    <?php echo __('Version :'); ?>
                    <span class="jslearnmanageradmin-ver">
                        <?php echo esc_html(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="jslm_dashboard">
            <span class="jslm_heading-dashboard"><?php echo __('Course Detail', 'learn-manager'); ?></span>
              <?php if(jslearnmanager::$_error_flag_message == null){ ?>
                <a class="jslmsadmin-add-link" href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=formcourse&jslearnmanagerid='.JSLEARNMANAGERrequest::getVar('jslearnmanagerid')); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/add_icon.png" /><?php echo __('Edit','learn-manager') .' '. __('Course' , 'learn-manager'); ?></a>
            <a target="blank" href="https://www.youtube.com/watch?v=cPH5zKlhNpo&ab_channel=WPLearnManager&start=78" class="jslmsadmin-add-link black-bg" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                <img alt="arrow" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/play-btn.png">
                <?php echo __('Watch Video', 'learn-manager'); ?>
            </a>
            <?php } ?>
        
        </div>            
    <div id="jslms-data-wrp">
      <div class="js-row jslm_course_detail_row"><!-- First Row -->
            <div class="js-col-md-12">
                <div class="js-col-md-8 jslm_padding_zero">
                    <?php if (!empty(jslearnmanager::$_data[0]['coursedetail'])){
                            $totalQuiz = apply_filters("jslm_quiz_course_totalquiz_for_admin_coursedetail",0);
                            $curdate = date_i18n('Y-m-d');
                            $row = jslearnmanager::$_data[0]['coursedetail'];
                            $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
                            $courseid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
                            $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
                            $isenroll = JSLEARNMANAGERincluder::getJSModel('course')->isStudentEnroll($uid,$courseid);
                            if($usertype == 'Student'){
                                $canreview = apply_filters("jslm_coursereview_can_student_reviw_on_course",0,$courseid);
                            } ?>
                    <div class="jslm_heading">
                        <div class="jslm_heading_left">
                            <h1 class="jslm_heading_style"><?php echo esc_html(__($row->title)); ?></h1>
                        </div>
                    </div>
                    <div class="js-col-md-12 jslm_zero_padding jslm_margin_bottom">
                        <div class="jslm_img_wrapper">
                            <?php if($row->course_logo !='' && $row->course_logo != null){
                                $imageadd = $row->course_logo;
                            }else{
                                $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                            }?>
                            <img src="<?php echo esc_url($imageadd); ?>">
                        </div>
                        <div class="jslm_coursedetail_wrapper">
                           <div class="jslm_info_wrapper">
                                <div class="jslm_data_wrapper">
                                    <div class="jslm_data">
                                        <div class="jslm_name_title jslm_full_width"><?php echo __("Category","learn-manager"); ?></div>
                                        <div class="jslm_name"><?php echo esc_html($row->category); ?></div>
                                    </div>
                                </div>
                                <div class="jslm_data_wrapper">
                                    <div class="jslm_data">
                                        <div class="jslm_name_title jslm_full_width"><?php echo __("Students","learn-manager"); ?></div>
                                        <div class="jslm_name jslm_complete_box"><?php echo esc_html($row->total_students); ?></div>
                                    </div>
                                </div>
                                <?php do_action("jslm_coursereview_html_for_reviews_admin_course_detail",$row); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="js-col-md-4 jslm_padding_zero">
                    <div class="jslm_data_wrapper">
                        <div class="jslm_left_data">
                            <div class="jslm_img_wrapper">
                               <?php if($row->instructor_image !='' && $row->instructor_image != null){
                                    $imageadd = $row->instructor_image;
                                }else{
                                    $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                                }?>
                               <img src="<?php echo esc_url($imageadd); ?>">
                            </div>
                        </div>
                        <div class="jslm_right_data">
                            <div class="jslm_name" title="<?php echo __("Instructor","learn-manager"); ?>">
                                <span class="value">
                                <?php
                                    if ($row->instructor_name == ""){
                                        echo __('Admin','learn-manager');
                                    }else {
                                        echo esc_html(__($row->instructor_name));
                                    }
                                ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="jslm_course_feature_wrapper">
                        <?php $htmldata = apply_filters('jslm_paidcourse_price_tag_admin_course_detail',false,$row);
                        if(!$htmldata){ ?>
                            <div class="jslm_top jslm_no_border">
                                <h2 class="jslm_price"><?php echo __("Free","learn-manager"); ?></h2>
                            </div>
                        <?php
                        }else{
                            echo esc_html($htmldata);
                        } ?>

                        <div class="jslm_bottom">
                            <div class="jslm_heading">
                                <h4><?php echo __("Course Features","learn-manager"); ?></h4>
                            </div>
                            <span class="jslm_row">
                                <span class="jslm_left_data"><?php echo __("Lectures","learn-manager"); ?></span>
                                <span class="jslm_right_data"><?php echo round($row->total_lessons,0); ?></span>
                            </span>
                            <?php do_action("jslm_quiz_show_course_totalquiz_admin_coursedetail",$totalQuiz); ?>
                            <span class="jslm_row">
                                <span class="jslm_left_data"><?php echo __("Duration","learn-manager"); ?></span>
                                <span class="jslm_right_data">
                                    <?php if(empty($row->duration)){
                                        echo '-----';
                                    }else{
                                        echo esc_html($row->duration);
                                    } ?>
                                </span>
                            </span>
                            <span class="jslm_row">
                                <span class="jslm_left_data"><?php echo __("Skill Level","learn-manager"); ?></span>
                                <?php if(!empty($row->level)){ ?>
                                    <span class="jslm_right_data"><?php echo esc_html($row->level); ?></span>
                                <?php }else{ ?>
                                    <span class="jslm_right_data">-----</span>
                                <?php } ?>
                            </span>
                            <span class="jslm_row">
                                <span class="jslm_left_data"><?php echo __("Language","learn-manager"); ?></span>
                                <?php if(!empty($row->language)){ ?>
                                    <span class="jslm_right_data"><?php echo esc_html($row->language); ?></span>
                                <?php }else{ ?>
                                    <span class="jslm_right_data">-----</span>
                                <?php } ?>
                            </span>
                            <span class="jslm_row">
                                <span class="jslm_left_data"><?php echo __("Students","learn-manager"); ?></span>
                                <span class="jslm_right_data"><?php echo esc_html($row->total_students); ?></span>
                            </span>
                        </div>
                       <!-- Featured Course -->
                    </div>

                <?php }else{
                            $msg = __('No record found','learn-manager');
                        }
                    ?>
                </div>
            </div>
            <?php if (jslearnmanager::$_error_flag_message == null) { ?>
            <div class="js-col-md-12">
                <div class="js-col-md-12 jslm_padding_zero">
                    <ul class="nav nav-tabs jslm_ul">
                        <li class="active jslm_li"><a class="jslm_li_anchor jslm_bigfont jslm_left_border" data-toggle="tab" href="#home"><?php echo __("Home","learn-manager");?></a></li>
                        <li class="jslm_li"><a class="jslm_li_anchor jslm_bigfont " data-toggle="tab" href="#curriculum"><?php echo __("Curriculum","learn-manager");?></a></li>
                        <li class="jslm_li"><a class="jslm_li_anchor jslm_bigfont" data-toggle="tab" href="#member"><?php echo __("Students","learn-manager");?></a></li>
                        <?php do_action('jslm_coursereview_coursedetail_tab_btn',2);  ?>
                    </ul>
                    <div class="tab-content jslm_my_content">
                        <div id="home" class="tab-pane fade in active"><!-- Home CODE -->
                            <div class="jslm_home_data_wrapper">
                                <div class="jslm_home_data_row">
                                    <div class="jslm_row_heading">
                                        <h3 class="jslm_row_heading_style"><?php echo __("Course Description","learn-manager"); ?></h3>
                                    </div>
                                     <span class="jslm_row_body_text">
                                        <?php echo esc_html(__($row->c_description)); ?>
                                    </span>
                                </div>
                                <div class="jslm_home_data_row">
                                    <?php if(!empty($row->learningoutcomes) && $row->learningoutcomes != null){ ?>
                                            <div class="jslm_row_heading">
                                                <h3 class="jslm_row_heading_style"><?php echo __("Learning Outcomes","learn-manager"); ?></h3>
                                            </div>
                                            <span class="jslm_row_body_text">
                                                <?php echo esc_html(__($row->learningoutcomes)); ?>
                                            </span>
                                    <?php } ?>
                                </div>
                                <?php $iscustomfield = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsData(1);
                                if(!empty($iscustomfield)){ ?>
                                <div class="jslm_home_data_row">
                                    <div class="jslm_custom_fields_wrapper">
                                        <div class="jslm_custom_fields_heading">
                                            <h4 class="jslm_heading_style"><?php echo esc_html__("Additional Information","learn-manager"); ?></h4>
                                        </div>
                                        <?php foreach(jslearnmanager::$_data[2] as $fields){
                                                switch($fields->field){
                                                    default:
                                                        if($fields->isuserfield == 1){
                                                            $array = JSLEARNMANAGERincluder::getObjectClass('customfields')->showCustomFields($fields, 1, jslearnmanager::$_data[0]['coursedetail']->params,0);
                                                            echo additionfields($array[0] , $array[1]);
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
                        <div id="curriculum" class="tab-pane fade accordion-body collapse in">
                            <div class="jslm_curriclum_heading">
                                <div class="jslm_curri_heading_left">
                                   <h3 class="jslm_heading_style"><?php echo __("Curriculum","learn-manager"); ?></h3>
                                </div>
                                <div class="jslm_curri_heading_right">
                                   <a id="section-modal" href="#myModalFullscreen" class="jslm_new_section" data-toggle="modal"  data-backdrop="false"><i class="fa fa-plus"></i><?php echo __("New Section","learn-manager"); ?></a>
                                </div>
                            </div>
                            <div id="accordion" class="panel-group">
                                <div id="section_panel" class="panel jslm_panel_margin jslm_no_padding">
                                    <?php if(count(jslearnmanager::$_data['sections']) > 0){
                                            $j = 0;
                                            for ($s = 0, $sp = count(jslearnmanager::$_data['sections']); $s < $sp; $s++) {
                                            $sections = jslearnmanager::$_data['sections'][$s];    ?>
                                            <div class="panel-heading jslm_zero_padding">
                                                <div class="panel-title jslm_panel_title">
                                                    <div id="wrapper_<?php echo esc_attr($sections->section_id); ?>" class="jslm_edit_section_wrapper">
                                                        <div class="jslm_edit_section_left">
                                                            <h4 class="jslm_section_heading">
                                                                <a href="#panelBodyOne_<?php echo esc_attr($sections->section_id); ?>" class="accordion-toggle accordion collapsed jslm_accordian_anchor jslm_section_title" data-toggle="collapse" data-parent="#accordion" ><?php echo __($sections->section_name) ;?></a>
                                                            </h4>
                                                        </div>
                                                        <div class="jslm_edit_section_right">
                                                            <span id="jslm_span_<?php echo esc_attr($sections->section_id); ?>" class="jslm_section_action">
                                                                <a href="<?php echo admin_url('admin.php?page=jslm_lecture&jslmslay=addlecture&jslearnmanagerid=sec_'.$sections->section_id); ?>" class="jslm_new_sec"><i class="fa fa-plus"></i><?php echo __("New Lecture","learn-manager"); ?></a>
                                                                <a href="#?id=<?php echo esc_attr($sections->section_id); ?>" id="edit_<?php echo esc_attr($sections->section_id); ?>" onclick="getSectionFormData('<?php echo esc_js($sections->section_id); ?>' , '<?php echo esc_js($sections->section_name); ?>')" data-toggle="modal" data-target="#myModalFullscreen" data-backdrop="false" class="jslm_delete_sec"><i class="fa fa-edit"></i></a>
                                                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jslm_course&action=jslmstask&task=removesection&jslearnmanagerid='. $sections->section_id . '&jslearnmanagercid=' .JSLEARNMANAGERrequest::getVar('jslearnmanagerid')),'delete-section'); ?>#curriculum" class="jslm_delete_sec" onclick="return confirm('<?php echo __('Are you sure to delete','learn-manager').' ?'; ?>');"><i class="fa fa-trash"></i></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="panelBodyOne_<?php echo esc_attr($sections->section_id); ?>" class="panel-collapse collapse jslm_panel_collapse in">
                                                <div class="panel-body jslm_panel_style">
                                                    <?php if(count(jslearnmanager::$_data['lectures'][$sections->section_id]) > 0){
                                                        for ($l = 0, $lp = count(jslearnmanager::$_data['lectures'][$sections->section_id]); $l < $lp; $l++) {
                                                            $j++;
                                                            $lectures = jslearnmanager::$_data['lectures'][$sections->section_id][$l];  ?>
                                                            <div class="row jslm_edit_row_style">
                                                                <a href="#" class="jslm_edit_lec_anchor"><span class="jslm_bold_text"><?php echo __("Lecture","learn-manager"); ?>-<?php echo esc_html($j); ?>:</span><?php echo __($lectures->lecture_name); ?></a>
                                                                <span class="jslm_edit_section_action">
                                                                    <a href="<?php echo admin_url('admin.php?page=jslm_lecture&jslmslay=addlecture&jslearnmanagerid=lec_'. $lectures->lecture_id); ?>" class="jslm_delete_sec "><i class="fa fa-edit"></i></a>
                                                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jslm_lecture&action=jslmstask&task=removecourselecture&jslearnmanagerid='. $lectures->lecture_id . '&jslearnmanagercid=' .JSLEARNMANAGERrequest::getVar('jslearnmanagerid')),'delete-lecture'); ?>#curriculum" class="jslm_delete_sec " onclick="return confirm('<?php echo __('Are you sure to delete','learn-manager').' ?'; ?>');"><i class="fa fa-trash"></i></a>
                                                                </span>
                                                            </div>
                                                    <?php }
                                                        }else{?>
                                                            <div class="alert alert-danger jslm_alert_danger"><?php echo __("No Data Found","learn-manager"); ?></div>
                                                   <?php }  ?>
                                                </div>
                                            </div>
                                    <?php }
                                        }else{?>
                                            <div id="no_data_section" class="alert alert-danger jslm_alert_danger"><?php echo __("No Data Found","learn-manager"); ?></div>
                                    <?php }?>
                                </div>
                            </div>
                           <!-- Modal Section -->
                            <div id="loading" style="display: none;"><center><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/loading.gif"></center></div>
                            <div class="modal jslm_my_modal fade in" id="myModalFullscreen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div id="full_background_course_section"></div>
                                <div class="modal-dialog jslm_modal_dailog">
                                    <div class="modal-content jslm_modal_content">
                                        <div class="modal-header jslm_modal_header">
                                            <button type="button" class="close jslm_close_button_style" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                                            <h2 class="modal-title jslm_title_heading jslm_custom"><?php echo __("Add Section Name","learn-manager"); ?></h2>
                                        </div>
                                        <div class="modal-body jslm_modal_body">
                                            <span class="jslm_search_field">
                                                <span class="jslm_input_title"><?php echo __("Section Name","learn-manager"); ?></span>
                                                <?php echo wp_kses(JSLEARNMANAGERformfield::text('jslm_sname','',array('placeholder' => __("Enter Section Name","learn-manager"), 'class' => 'jslm_field_style', 'data-validation' => 'required', 'required'=>"required")), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <span class="jslm_save_button">
                                                    <input type="submit" name="save" onclick="insertSection()" class="jslm_save_button_style jslm_save" value="<?php echo __("Save","learn-manager"); ?>">
                                                    <input type="submit" name="save" onclick="insertSection()" class="jslm_save_button_style jslm_saveNclose" data-dismiss="modal" aria-hidden="true" value="<?php echo __("Save & Close","learn-manager"); ?>">
                                                    <input type="button" class="jslm_save_button_style jslm_cancel" data-dismiss="modal" aria-hidden="true" value="<?php echo __("Cancel","learn-manager"); ?>">
                                                </span>
                                                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_sid',''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_cid', JSLEARNMANAGERrequest::getVar('jslearnmanagerid') ? __(JSLEARNMANAGERrequest::getVar('jslearnmanagerid'),'learn-manager') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="member" class="tab-pane fade">
                            <div class="jslm_curriclum_heading">
                                <div class="jslm_curri_heading_left">
                                   <h3 class="jslm_heading_style"><?php echo __("Students","learn-manager"); ?></h3>
                                </div>
                                <div class="jslm_curri_heading_right">
                                   <!-- <a href="#" class="jslm_new_section" data-toggle="modal" data-target="#myModalFullscreen"><i class="fa fa-plus"></i> Add New Co Member</a> -->
                                </div>
                            </div>
                            <?php if(count(jslearnmanager::$_data['enrolledstudents']) > 0){
                                for ($co = 0, $cop = count(jslearnmanager::$_data['enrolledstudents']); $co < $cop; $co++) {
                                    $enrolledstudents = jslearnmanager::$_data['enrolledstudents'][$co];    ?>
                                    <div class="jslm_member_data">
                                        <div class="jslm_left_side">
                                           <div class="jslm_img_wrapper">
                                              <img src="<?php echo esc_url($enrolledstudents->image); ?>">
                                           </div>
                                        </div>
                                        <div class="jslm_right_side">
                                            <span class="jslm_text1 jslm_bigfont"><a href="<?php echo admin_url('admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid='.$enrolledstudents->student_id); ?>"><?php echo esc_html(__($enrolledstudents->name)); ?></a></span>
                                            <span class="jslm_date"><?php echo date_i18n(jslearnmanager::$_config['date_format'],strtotime($enrolledstudents->created_at)); ?></span>
                                            <span class="jslm_text2"><?php echo esc_html($enrolledstudents->country_name); ?></span>
                                        </div>
                                    </div>
                                <?php } ?>

                                <input type="hidden" name="total_pages" id="total_pages" value="<?php echo esc_attr(jslearnmanager::$_data['enrolledstudentspagination']); ?>">
                                <input type="hidden" name="pagei" id="pagei" value="<?php echo esc_attr(jslearnmanager::$_data['offset'] + 1); ?>">
                                <?php if(jslearnmanager::$_data['enrolledstudentspagination'] != (jslearnmanager::$_data['offset'] + 1)){ ?>
                                    <div id="loadmore" class="jslm_show_button_wrapper">
                                        <a class="jslm_show_more"><?php echo __("Show More", "learn-manager"); ?></a>
                                    </div>
                                <?php } ?>

                            <?php }else{ ?>
                                <div class="alert alert-danger jslm_alert_danger"><?php echo __("No Student Enroll.","learn-manager"); ?></div>
                            <?php } ?>
                        </div>
                        <?php do_action("jslm_coursereview_get_reviewslist_for_admin_coursedetail",$row); ?>
                    </div>
                </div>
            </div>
            <?php } else{
                echo jslearnmanager::$_error_flag_message;
            }?>
         </div>
       </div>
    </div>
</div>

