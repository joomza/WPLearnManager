<div class="jslm_main-up-wrapper">
<?php if (!empty(jslearnmanager::$_data[0])) {
    $marknew = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('course');
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
    $curdate = date_i18n('Y-m-d');
    $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
    $usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
    for ($i = 0, $n = count(jslearnmanager::$_data[0]); $i < $n; $i++) {
        $row = jslearnmanager::$_data[0][$i];
        $courseid = $row->course_id;
        $isenroll = JSLEARNMANAGERincluder::getJSModel('course')->isStudentEnroll($uid,$courseid);  ?>
        <div class="jslm_data_wrapper">
            <?php $print = checkLinks('logo');
                if ($print[0] == 1) { ?>
                    <div class="jslm_left">
                        <div class="jslm_left_img_wrp">
                            <?php if($row->fileloc !='' && $row->fileloc != null){
                                $imageadd = $row->fileloc;
                            }else{
                                $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                            }?>
                            <img alt="<?php echo esc_attr(__("Course Image","learn-manager")); ?>" src="<?php echo esc_attr($imageadd); ?>">

                            <div class="overlay">
                                <div class="jslm_links">
                                    <span class="jslm_link_icon">
                                        <a title="<?php echo esc_attr("View Detail","learn-manager"); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'coursedetails', 'jslearnmanagerid'=>$row->course_id)))?>"><i class="fa fa-eye"></i></a>
                                    </span>
                                    <?php
                                        $currentShortlist = JSLEARNMANAGERincluder::getJSModel('course')->isShorlistCourse($row->course_id);
                                        if($usertype == 'Student'){
                                            $studentcanshortlist = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('student_have_shortlist_course');
                                            $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
                                            $studentapprove = JSLEARNMANAGERincluder::getJSModel('student')->getStudentApprovalStatus($studentid);
                                            if($studentcanshortlist && $studentapprove == 1){  ?>
                                                <span id="span_sh_<?php echo esc_attr($row->course_id); ?>" class="jslm_link_icon">
                                                <?php
                                                if($currentShortlist){
                                                    if($currentShortlist->count == 0){ ?>
                                                        <a title="<?php echo esc_attr("Add to shortlist","learn-manager"); ?>" href="#" onclick="storeCourseShortlist(<?php echo esc_js($row->course_id); ?>,1)" ><i class="fa fa-heart-o"></i></a>
                                                    <?php }else{ ?>
                                                        <a class="active" title="<?php echo esc_attr("Remove to shortlist","learn-manager"); ?>" href="#" onclick="removeShortlist(<?php echo esc_js($currentShortlist->id); ?>,<?php echo esc_js($row->course_id); ?>,1);"><i class="fa fa-heart"></i></a>
                                                    <?php }
                                                }else{ ?>
                                                    <a title="<?php echo esc_attr("Add to shortlist","learn-manager"); ?>" href="#" onclick="storeCourseShortlist(<?php echo esc_js($row->course_id); ?>,1)" ><i class="fa fa-heart-o"></i></a>
                                                <?php } ?>
                                                </span>
                                            <?php } ?>
                                        <?php }elseif(!is_user_logged_in()){
                                                $visitorcanshortlist = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_have_shortlist_course');
                                                if($visitorcanshortlist){ ?>
                                                    <span id="span_sh_<?php echo esc_attr($row->course_id); ?>" class="jslm_link_icon">
                                                    <?php if($currentShortlist){
                                                        if($currentShortlist->count == 0){ ?>
                                                            <a title="<?php echo esc_attr("Add to shortlist","learn-manager"); ?>" href="#" onclick="storeCourseShortlist(<?php echo esc_js($row->course_id); ?>,1)" ><i class="fa fa-heart-o"></i></a>
                                                        <?php }else{ ?>
                                                            <a class="active" title="<?php echo esc_attr("Remove to shortlist","learn-manager"); ?>" href="#" onclick="removeShortlist(<?php echo esc_js($currentShortlist->id); ?>,<?php echo esc_js($row->course_id); ?>,1);"><i class="fa fa-heart"></i></a>
                                                        <?php }
                                                    }else{ ?>
                                                        <a title="<?php echo esc_attr("Add to shortlist","learn-manager"); ?>" href="#" onclick="storeCourseShortlist(<?php echo esc_js($row->course_id); ?>,1)" ><i class="fa fa-heart-o"></i></a>
                                                    <?php } ?>
                                                    </span>
                                                <?php }
                                            }
                                        ?>
                                </div>
                                <div class="jslm_overlay_bottom">
                                    <div class="jslm_overlay_bottom_img">
                                        <img alt="<?php echo esc_attr(__("image","learn-manager")); ?>" title="<?php echo esc_attr(__("image","learn-manager")); ?>" src="<?php echo isset($row->instructor_image) && $row->instructor_image != '' ? esc_attr($row->instructor_image) : esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png');?>">
                                    </div>
                                    <div class="jslm_overlay_bottom_name">
                                        <span class="jslm_overlay_bottom_instructor"><a class="jslm_instructor_link" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'instructor','jslmslay'=>'instructordetails','jslearnmanagerid'=>$row->instructor_id))); ?>"><?php echo isset($row->instructor_name) ? esc_html__($row->instructor_name,"learn-manager") : __("Admin","learn-manager"); ?></a></span>
                                    </div>
                                </div>
                            </div>
                            <?php do_action("jslm_featuredcourse_featured_icon_course_list",$row,$curdate);
                            $fromDate = date_i18n('Y-m-d',strtotime($row->created_at));
                            $daysLeft = abs(strtotime($curdate) - strtotime($fromDate));
                            if(is_numeric($marknew['newdays']) && $marknew['newdays'] != 0 && ($daysLeft / 86400) <= $marknew['newdays']){ ?>
                                <span class="jslm_featured_box jslm_mark_new"><?php echo __("New","learn-manager"); ?></span>
                            <?php } ?>
                        </div>
                    </div>
            <?php } ?>
            <div class="jslm_right">
                <div class="jslm_right_top">
                    <div class="jslm_right_top_left">
                        <h4 class="jslm_heading_style">
                            <a title="<?php echo esc_attr(__($row->title,"learn-manager")); ?>" class="jslm_course_title title_anchor" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'coursedetails', 'jslearnmanagerid'=>$row->course_id)))?>"> <?php if (strlen($row->title) > 60){ echo esc_html__(substr(ucwords($row->title), 0, 60))." ... ";} else { echo ucwords(esc_html__($row->title,"learn-manager")); } ?></a>
                        </h4>
                    </div>
                    <?php do_action("jslm_coursereview_courselist_review_stars",$row); ?>
                </div>
                <div class="jslm_right_middle">
                    <div class="jslm_middle_description">
                        <?php echo __(substr(strip_tags($row->description),0,150),"learn-manager").'....'; ?>
                    </div>
                </div>
                <?php $iscustomfield = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsData(1);
                if(!empty($iscustomfield)){ ?>
                <div class="jslm_custom_fields_wrapper">
                <?php foreach(jslearnmanager::$_data[2] AS $key => $fields){
                        switch($fields->field){
                            default:
                                if($fields->isuserfield == 1){ ?>
                                <?php $array = JSLEARNMANAGERincluder::getObjectClass('customfields')->showCustomFields($fields, 2, $row->params,0,$row);
                                    if($array[1] != ""){
                                        echo getDataRow(sprintf(__('%s','learn-manager'),$array[0]), sprintf(__('%s','learn-manager'),$array[1]));
                                    }
                                ?>

                        <?php }
                            break;
                    }
                }?>
                </div>
                <?php } ?>
            </div>
            <div class="jslm_right_bottom">
                <div class="jslm_right_bottom_left">
                    <span class="jslm_logos">
                        <?php $print = checkLinks('category_id');
                                if($print[0] == 1){ ?>
                                    <span class="jslm_logo_wrp">
                                        <img alt="<?php echo esc_attr(__("Category","learn-manager")); ?>" title="<?php echo esc_attr(__("Category","learn-manager")); ?>"  src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/category.png'); ?>">
                                        <?php echo esc_html($row->category,"learn-manager"); ?>
                                    </span>
                        <?php   } ?>
                        <span class="jslm_logo_wrp">
                            <img alt="<?php echo esc_attr(__("Lectures","learn-manager")); ?>" title="<?php echo esc_attr(__("Lectures","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/lesson.png'); ?>">
                            <?php echo esc_html($row->total_lessons); ?>
                        </span>
                        <span class="jslm_logo_wrp">
                            <img alt="<?php echo esc_attr(__("Students","learn-manager")); ?>" title="<?php echo esc_attr(__("Students","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/students.png'); ?>">
                            <?php echo esc_html($row->total_students); ?>
                        </span>
                    </span>
                </div>
                <?php if(isset($row->isdiscount) && $row->isdiscount == 1) $dsclass = 'jslm_discount_price'; else $dsclass = ''; ?>
                <div class="jslm_right_bottom_right <?php echo esc_attr($dsclass); ?>">
                    <?php $print = checkLinks('price');
                    if ($print[0] == 1) { ?>
                        <div class="jslm_right_bottom_price">
                            <?php
                            $print = checkLinks('currency');
                            $pricehtml = apply_filters("jslm_paidcourse_get_paid_course_price_tag_listing","",$row,$print);
                            $class = "jslm_free_colr";
                            if($pricehtml == ""){
                                echo '<h3 class="jslm_heading_style ' . esc_attr($class) . '">' . esc_html("Free","learn-manager") . '</h3>';
                            }else{
                                echo esc_html($pricehtml);
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php   }
    }else {
        $msg = __('No course found','learn-manager');
        echo JSLEARNMANAGERlayout::getNoRecordFound($msg);
    }   ?>
</div>
