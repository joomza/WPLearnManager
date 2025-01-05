<div class="jslm_main-up-wrapper">
<?php if (!empty(jslearnmanager::$_data['mycourses'])) {
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
        for ($i = 0, $n = count(jslearnmanager::$_data['mycourses']); $i < $n; $i++) {
            $row = jslearnmanager::$_data['mycourses'][$i];
            $courseid = $row->course_id; ?>
            <div class="jslm_data_wrapper">
                <?php $print = checkLinks('logo');
                    if ($print[0] == 1) { ?>
                        <div class="jslm_left">
                            <div class="jslm_left_img_wrp">
                                <?php if($row->file != '' && $row->file != null){
                                    $imageadd = $row->file;
                                }else{
                                    $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                                }
                                ?>
                                <img alt="<?php echo esc_attr(__("Course Image","learn-manager")); ?>" src="<?php echo esc_attr($imageadd); ?>">

                                <div class="overlay">
                                    <div class="jslm_links">
                                        <span class="jslm_link_icon">
                                            <a title="<?php echo __("Edit Course","learn-manager"); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'addcourse', 'jslearnmanagerid'=>$row->course_id)))?>"><i class="fa fa-edit"></i></a>
                                        </span>
                                        <span class="jslm_link_icon">
                                            <a title="<?php echo __("Delete Course","learn-manager"); ?>" href="<?php echo esc_url(wp_nonce_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'action'=>'jslmstask', 'task'=>'removecourse', 'jslearnmanagerid'=>$row->course_id, 'jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('page_id'))),'delete-course'));?>" onclick="return confirm('Are you sure to delete this course?')"><i class="fa fa-trash"></i></a>
                                        </span>
                                        <?php do_action("jslm_featuredcourse_featureicon_addtofeature_icon_my_courselist",$row,$curdate); ?>
                                    </div>
                                    <?php do_action("jslm_paymentplan_addtofeatured_paymentplan_modal",$row); ?>
                                    <div class="jslm_overlay_bottom">
                                        <div class="jslm_overlay_bottom_img">
                                            <img alt="<?php echo esc_attr(__("image","learn-manager")); ?>" title="<?php echo esc_attr(__("instructor image","learn-manager")); ?>" src="<?php echo isset($row->instructor_image) && $row->instructor_image != '' ? esc_attr($row->instructor_image) : esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png');?>">
                                        </div>
                                        <div class="jslm_overlay_bottom_name">
                                            <span class="jslm_overlay_bottom_instructor"><?php echo isset($row->instructor_name) ? esc_html__($row->instructor_name,'learn-manager') : __("Admin","learn-manager"); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php do_action("jslm_featuredcourse_feature_icon_admin_course_queue",$row,$curdate);
                                if($row->course_status == 1){
                                    $status = "Publish";
                                }else{
                                    $status = "Unpublish";
                                } ?>
                                <span class="jslm_course_publish"><?php echo esc_html(__($status)); ?></span>
                            </div>
                        </div>
                <?php } ?>
                <div class="jslm_right">
                    <div class="jslm_right_top">
                        <div class="jslm_right_top_left">
                            <h4 class="jslm_heading_style">
                                <a title="<?php echo esc_attr(__("View Course","learn-manager")); ?>" class="jslm_course_title title_anchor" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$row->course_id)))?>#curriculum"> <?php if (strlen($row->title) > 60){ echo substr(ucwords(esc_html__($row->title , 'learn-manager')), 0, 60)." ... ";} else { echo ucwords(esc_html__($row->title,'learn-manager')); } ?></a>
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
                                            <?php echo esc_html($row->category,"learn-manager");?>
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
