<div class="jslm_main-up-wrapper">
<div id="jslm_ajaxloaded_wait_overlay" style="display:none;"><img alt="<?php echo esc_attr(__("image","learn-manager")); ?>" title="<?php echo esc_attr(__("image","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/loading.gif'); ?>"></div>
<div id="loading" class="jslm_loader_loading" style="display: none"><img alt="<?php echo esc_attr(__("image","learn-manager")); ?>" title="<?php echo esc_attr(__("image","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/loading.gif'); ?>"></div>
<?php if(isset(jslearnmanager::$_data[0]['coursedetail']->course_id)){
if(jslearnmanager::$_config['date_format'] == 'd-m-Y' ){
  $date_format_string = 'd/F/Y';
}elseif(jslearnmanager::$_config['date_format'] == 'm/d/Y'){
  $date_format_string = 'F/d/Y';
}elseif(jslearnmanager::$_config['date_format'] == 'Y-m-d'){
  $date_format_string = 'Y/F/d';
}?>
<div class="jslm_content_wrapper"><!-- Body Here -->
   	<div class="jslm_content_data">
   		<div class="jslm_search_content no-border no-padding-bottom">
   			<div class="jslm_top_title">
				<div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Course Detail","learn-manager"); ?></h3></div>
			</div>
   		</div>
      	<div class="jslm_detail_wrapper"><!-- First Row -->
      		<?php if (!empty(jslearnmanager::$_data[0]['coursedetail'])) {
				$totalQuiz = apply_filters("jslm_quiz_course_totalquiz_for_admin_coursedetail",0);
				$curdate = date_i18n('Y-m-d');
            	$row = jslearnmanager::$_data[0]['coursedetail'];
            	$uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
           		$courseid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
           		$usertype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
       		  	$isenroll = JSLEARNMANAGERincluder::getJSModel('course')->isStudentEnroll($uid,$courseid);
       		  	$studentapprove = -1;
       		  	$canreview = 0;
       		  	if($usertype == 'Student'){
       		  		if(in_array('coursereview', jslearnmanager::$_active_addons)){
       		  			$canreview = JSLEARNMANAGERincluder::getJSModel('coursereview')->canReviewonCourse($courseid);
       		  		}
       		  		$studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($uid);
           			$studentapprove = JSLEARNMANAGERincluder::getJSModel('student')->getStudentApprovalStatus($studentid);
       		  	} ?>
       		<div class="jslm_top_wrapper">
				<div class="jslm_left_wrap">
					<div class="jslm_title">
						<h2><?php echo esc_html( __($row->title,"learn-manager")); ?></h2>
						<?php do_action("jslm_coursereview_coursedetail_avgreview_after_title",$row); ?>
					</div>
				</div>
				<div class="jslm_right_wrap">
					<div class="jslm_enroll_btn_wrap">
						<?php
						$freecondition = false;
						$allowenroll = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allow_enroll');
						$studentflag = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_student');
						if(!$allowenroll){ ?>
							<div class="jslm_price_btn">
	              				<h3><?php echo esc_html__("Enrollment disabled by Admin","learn-manager"); ?></h3>
	              			</div>
						<?php
						}
						if($row->access_type == "Free"){
							$freecondition = true; ?>
							<div class="jslm_price_btn">
              					<h3 class="jslm_free"><?php echo esc_html__("Free","learn-manager"); ?></h3>
              				</div>
						<?php
						}
						do_action("jslm_paidcourse_course_detail_price_tag",$row); // paid course price tag here
						if($usertype == 'Student' && !$isenroll && $studentflag == 1 && $studentapprove == 1){
							if($freecondition){ ?>
								<div class="jslm_enroll_course_btn">
	              					<h3 class="jslm_enroll_btn_h3">
										<a title="<?php echo esc_attr(__("Enroll in course","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'action'=>'jslmstask', 'task'=>'saveenrollment', 'jslearnmanagerid' => JSLEARNMANAGERrequest::getVar('jslearnmanagerid') ,'jslearnmanagerpageid'=>jslearnmanager::getPageid()))); ?>"><?php echo __("Enroll in","learn-manager"); ?></a>
									</h3>
								</div>
							<?php
							}else{
								do_action("jslm_paidcourse_course_detail_purchase_course_options"); // get payment method popup
							}
						}else if(!is_user_logged_in()){ ?>
							<div class="jslm_enroll_course_btn">
	              				<h3 class="jslm_enroll_btn_h3">
	              					<?php if($freecondition){
	              						jslearnmanager::$_js_login_redirct_link = JSLEARNMANAGERincluder::getJSModel('common')->jsMakeRedirectURL('course', $layout,JSLEARNMANAGERrequest::getVar('jslearnmanagerid'),1);?>
	              						<a title="<?php echo esc_attr(__("Enroll in course","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::$_js_login_redirct_link); ?>" title="<?php echo esc_attr("Click to Enroll in ","learn-manager"); ?>"><?php echo esc_html__("Enroll in Course","learn-manager"); ?></a>
	              					<?php }else{
	              						do_action("jslm_paidcourse_coursedetail_visitor_login_for_paid_course",$layout);
              						}?>
	              				</h3>
	              			</div>
						<?php
						}
						if($isenroll){ ?>
							<div class="jslm_top jslm_top_enrolled">
		  						<h3 class="jslm_take_course jslm_top_enrolled_padding"><?php echo esc_html__("Enrolled","learn-manager"); ?></h3>
							</div>
						<?php
						} ?>


					</div>
				</div>
			</div>
			<div class="jslm_middle_potion">
				<div class="jslm_middle_top">
					<?php if($row->instructor_image !='' && $row->instructor_image != null){
						$imageadd = $row->instructor_image;
					}else{
						$imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
					}?>
					<div class="jslm_logo_wrp">
						<div class="jslm_detail_img_wrap">
							<img alt="<?php echo esc_attr(__("Instructor Image","learn-manager")); ?>" title="<?php echo esc_attr(__("Instructor Image","learn-manager")); ?>" src="<?php echo esc_attr($imageadd); ?>">
					 	</div>
					 	<div class="jslm_detail_title"><a href="<?php echo isset($row->instructor_id) ? esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'instructor','jslmslay'=>'instructordetails','jslearnmanagerid'=>$row->instructor_id))) : "#" ; ?>"><?php echo isset($row->instructor_name) ? esc_html(__($row->instructor_name,"learn-manager")) : "Admin"; ?></a></div>
					</div>
					<?php if(isset($row->category) && checkFields('category_id')[0] == 1){ ?>
						<div class="jslm_logo_wrp">
							<img title="<?php echo esc_attr(__("Category","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/category.png">
							<?php echo esc_html(__($row->category,"learn-manager")); ?>
						</div>
					<?php }
					if($row->total_lessons > 0){
					?>
					<div class="jslm_logo_wrp">
						<img title="<?php echo esc_attr(__("Lectures","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/lessons.png">
						<?php echo round($row->total_lessons,0); ?>
					</div>
					<?php }
					if($row->total_students > 0){
					?>
						<div class="jslm_logo_wrp">
							<img title="<?php echo esc_attr(__("Students","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/members.png">
							<?php echo esc_html(__($row->total_students,"learn-manager")); ?>
						</div>
					<?php }
					if($row->duration != "" && checkFields('course_duration')[0] == 1){
					?>
						<div class="jslm_logo_wrp">
							<img title="<?php echo esc_attr(__("Course duration","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/course-duration.png">
							<?php echo esc_html($row->duration); ?>
						</div>
					<?php }
					if($row->level != "" && checkFields('course_level')[0] == 1){
					?>
						<div class="jslm_logo_wrp">
							<img title="<?php echo esc_attr(__("Course Level","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/course-level.png">
							<?php echo esc_html(__($row->level,"learn-manager")); ?>
						</div>
					<?php }
					if($row->language != "" && checkFields('language')[0] == 1){ ?>
						<div class="jslm_logo_wrp">
	                        <img title="<?php echo esc_attr(__("Course language","learn-manager")); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/language.png">
	                        <?php echo esc_html(__($row->language,"learn-manager")); ?>
	                    </div>
                    <?php } ?>
				</div>
			</div>
			<?php $print = checkFields('logo');
        	if($print[0] == 1){ ?>
               	<div class="jslm_img_wrapper">
               		<?php if($row->course_logo !='' && $row->course_logo != null){
                        $imageadd = $row->course_logo;
                    }else{
                        $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                    }?>
                 	<img alt="<?php echo esc_attr(__("image","learn-manager")); ?>" title="<?php echo esc_attr(__("image","learn-manager")); ?>" src="<?php echo esc_attr($imageadd); ?>">
               	</div>
            <?php } ?>
			<div class="jslm_tabs_wrapper">
				<ul class="nav nav-tabs jslm_ul_menu jslm_ul_display_inline">
                	<li class="active jslm_li_border"><a title="<?php echo esc_attr(__("Home","learn-manager")); ?>" class="jslm_li_anchor_styling jslm_bigfont jslm_left_border" data-toggle="tab" href="#home"><img id="description" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/description-grey.png"><?php echo esc_html__("Overview","learn-manager"); ?></a></li>
                  	<li class="jslm_li_border"><a title="<?php echo esc_attr(__("Curriculum","learn-manager")); ?>" class="jslm_li_anchor_styling jslm_bigfont" data-toggle="tab" href="#curriculum"><img id="curriculumimgid" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/curriculum-grey.png"><?php echo esc_html__("Curriculum","learn-manager"); ?></a></li>
                  	<?php if(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('showstudentlistincoursedetail') == 1){ ?>
                  		<li class="jslm_li_border"><a title="<?php echo esc_attr(__("Students","learn-manager")); ?>" class="jslm_li_anchor_styling jslm_bigfont" data-toggle="tab" href="#member"><img id="enrolledstudentsimg" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/members-grey.png"><?php echo esc_html__("Students","learn-manager"); ?></a></li>
                  	<?php } ?>
                  	<?php do_action('jslm_coursereview_coursedetail_tab_btn',1); ?>
               	</ul>
               	<div class="tab-content jslm_my_content">
	              	<div id="home" class="tab-pane fade in active"><!-- Home CODE -->
	                  	<div class="jslm_home_data_wrapper">
		                    <?php foreach(jslearnmanager::$_data[2] as $fields){
		                        	switch($fields->field){
		                        		case 'description': ?>
											<div class="jslm_home_data_row">
					                           	<div class="jslm_row_heading">
					                            	<h3 class="jslm_row_heading_style"><?php echo esc_html__("Course Description","learn-manager"); ?></h3>
					                           	</div>
					                          	 <span class="jslm_row_body_text">
					                           		<?php echo html_entity_decode(wp_kses_post($row->c_description)); ?>
					                           	</span>
					                        </div>
				                <?php 	break;
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
						        <?php 	break;
						        	}
				        		}
				        		$iscustomfield = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsData(1);
				        		if(!empty($iscustomfield)){ ?>
						        <div class="jslm_home_data_row">
		                           	<div class="jslm_custom_fields_wrapper">
										<div class="jslm_custom_fields_heading">
											<h4 class="jslm_heading_style"><?php echo esc_html__("Additional Information","learn-manager"); ?></h4>
										</div>
										<?php $i = 1;
										foreach(jslearnmanager::$_data[2] as $fields){
												switch($fields->field){
													default:
														if($fields->isuserfield == 1){
															$array = JSLEARNMANAGERincluder::getObjectClass('customfields')->showCustomFields($fields, 1, jslearnmanager::$_data[0]['coursedetail']->params,0);
	                    									echo additionfields($array[0],$array[1],$i);
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
				        <?php do_action("jslm_socialshare_sharing_btns_for_detail"); ?>
				    </div>
                  	<div id="curriculum" class="tab-pane fade">
                   		<div class="jslm_curriculum_wrapper">
	                   		<div class="jslm_curriculum_title">
	                           	<div class="jslm_row_heading">
	                            	<h3 class="jslm_row_heading_style"><?php echo esc_html__("Curriculum","learn-manager"); ?></h3>
	                           	</div>
	                        </div>
	                   		<div id="accordion" class="jslm_panel_group">
		                        <?php if(count(jslearnmanager::$_data['sections']) > 0){
		                        		$j = 0;
		                        		for ($s = 0, $sp = count(jslearnmanager::$_data['sections']); $s < $sp; $s++) {
		                    				$sections = jslearnmanager::$_data['sections'][$s];
		                    				$countlecture = 0;
		                    				if(!empty(jslearnmanager::$_data['student_course'])){
		                    					$student_course = jslearnmanager::$_data['student_course'];
					   							$studentattnd_lecture = json_decode($student_course->lecture_completion_params);
					   							if(isset($studentattnd_lecture->{$sections->section_id})){
					   								$countlecture = count($studentattnd_lecture->{$sections->section_id});
					   							}
		                    				}	?>
											<div class="jslm_panel_single_group">
												<div class="jslm-panel-heading">
					                              	<div class="jslm_edit_section_left">
			                                    		<h6 class="jslm_section_heading">
			                                    			<a title="<?php echo esc_attr(__($sections->section_name,"learn-manager")); ?>" href="#panelBodyOne_<?php echo esc_attr($sections->section_id); ?>" class="accordion-toggle accordion collapsed jslm_accordian_anchor jslm_section_title " data-toggle="collapse" ><?php echo esc_html__($sections->section_name , 'learn-manager');?>
																<i class=" more-less fa fa-chevron-down"></i>
			                                    				<?php if($sections->lec_count == $countlecture && $sections->lec_count != 0 && $countlecture != 0){	?>
				                               						<i class="fa fa-check-square-o"></i>
				                               					<?php } ?>
			                                    			</a>
			                                    		</h6>
			                                    	</div>
				                                </div>
					                           	<div id="panelBodyOne_<?php echo esc_attr($sections->section_id); ?>" class="panel-collapse collapse">
					                             	<div class="container jslm_container_style">
				                                 	<?php if(count(jslearnmanager::$_data['lectures'][$sections->section_id]) > 0){
				                                 			for ($l = 0, $lp = count(jslearnmanager::$_data['lectures'][$sections->section_id]); $l < $lp; $l++) {
				                    							$j++;
				                    							$lectures = jslearnmanager::$_data['lectures'][$sections->section_id][$l];
				                    							$lectures->progress = 0;
											        			if(isset($studentattnd_lecture->{$sections->section_id})){
											        				$curatndlecture = $studentattnd_lecture->{$sections->section_id};
											        				for($attnd = 0; $attnd < count($curatndlecture); $attnd++){
											        					if($curatndlecture[$attnd]->lecture_id == $lectures->lecture_id){
											        						$lectures->progress = 1;
											        						break;
											        					}
											        				}
											        			}	?>
				                                    	<div class="row jslm_edit_row_style">
				                                       		<?php if($isenroll){ ?>
				                                       		<a title="<?php echo esc_attr(__($lectures->lecture_name,"learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'jslmslay'=>'lecturedetails', 'jslearnmanagerid'=>$lectures->lecture_id)))?>"  class="jslm_edit_lec_anchor"><span class="jslm_bold_text"><?php echo esc_html__("Lecture","learn-manager").'-'. esc_html__($j,'learn-manager'); ?>:</span><?php echo esc_html__($lectures->lecture_name , 'learn-manager'); ?>
				                                       		</a>
											     			<span class="jslm_edit_section_action ">
											     				<?php if($lectures->progress == 1){ ?>
											     					<i class="fa fa-check"></i>
											     				<?php } ?>
			                                       				<a title="<?php echo esc_attr(__("link","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'jslmslay'=>'lecturedetails', 'jslearnmanagerid'=>$lectures->lecture_id)))?>"  class="jslm_delete_sec"><i class="fa fa-eye" contenteditable="false"></i></a>
			                                      			</span>
											     			<?php }else{ ?>
				                                      			<a title="<?php echo esc_attr(__("link","learn-manager")); ?>" class="jslm_edit_lec_anchor"><span class="jslm_bold_text"><?php echo esc_html__("Lecture","learn-manager").'-'. esc_html__($j,'learn-manager'); ?>:</span><?php echo esc_html__($lectures->lecture_name,'learn-manager'); ?>.</a>
				                                      		<?php } ?>
				                                      	</div>
				                                   		<?php }
				                                   			}else{?>
				                                   			<div class="alert jslm_alert_danger"><?php echo esc_html__("No Data Found","learn-manager"); ?></div>
				                                   		<?php }?>
				                                	</div>
					                            </div>
					                        </div>
									<!-- /.modal -->
		                        <?php }
	                        	 	}else{?>
			                        	<div class="alert jslm_alert_danger"><?php echo esc_html__("No Data Found","learn-manager"); ?></div>
								<?php } ?>
		                    </div>
	                    </div>
                 	</div>
                 	<?php if(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('showstudentlistincoursedetail') == 1){ ?>
	                  	<div id="member" class="tab-pane fade">
                 			<!-- Members CODE -->
                 			<div class="jslm_members_wrapper">
                 				<div class="jslm_home_data_row">
		                           	<div class="jslm_row_heading">
		                            	<h3 class="jslm_row_heading_style"><?php echo esc_html__("Enrolled Students","learn-manager"); ?></h3>
		                           	</div>
		                        </div>
			                  	<?php if(count(jslearnmanager::$_data['enrolledstudents']) > 0){
		                           	for ($co = 0, $cop = count(jslearnmanager::$_data['enrolledstudents']); $co < $cop; $co++) {
		                              	$comembers = jslearnmanager::$_data['enrolledstudents'][$co];	?>
			                     	<div class="jslm_member_data">
				                        <div class="jslm_left_side">
				                           <div class="jslm_img_wrapper">
				                           		<?php if($comembers->image == "" && empty($comembers->image)){
				                           			$coimage = $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
				                           		}else{
				                           			$coimage = $comembers->image;
				                           		}	?>
				                              <img alt="<?php echo esc_attr(__("Student image","learn-manager")); ?>" title="<?php echo esc_attr(__("Student image","learn-manager")); ?>" src="<?php echo esc_attr($coimage); ?>">
				                           </div>
				                        </div>
				                        <div class="jslm_right_side">
			                              	<span class="jslm_text2 jslm_text1"><?php echo esc_html__($comembers->name,'learn-manager'); ?></span>
			                              	<?php if($comembers->country_name != ""){ ?>
			                              		<span class="jslm_text2"><?php echo esc_html__($comembers->country_name,'learn-manager'); ?></span>
			                              	<?php } ?>
			                              	<span class="jslm_text2"><?php echo date_i18n($date_format_string, strtotime($comembers->created_at)); ?></span>
			                           	</div>
				                    </div>
		                     	<?php 	} ?>
		                     	<input type="hidden" name="total_pages" id="total_pages" value="<?php echo esc_attr(jslearnmanager::$_data['enrolledstudentspagination']); ?>">
		                     	<input type="hidden" name="pagei" id="pagei" value="<?php echo esc_attr(jslearnmanager::$_data['offset']) + 1; ?>">
		                     	<?php if(jslearnmanager::$_data['enrolledstudentspagination'] != (jslearnmanager::$_data['offset'] + 1)){ ?>
			            	 	<div id="loadmore" class="jslm_show_button_wrapper">
			                        <a title="<?php echo esc_attr(__("Show More","learn-manager")); ?>" class="jslm_show_more"><?php echo esc_html__("Show More","learn-manager"); ?></a>
			                 	</div>
		                     	<?php } ?>
		                        <?php	}else{ ?>
		                           	<div class="alert jslm_alert_danger"><?php echo esc_html__("No Student Enroll.....","learn-manager"); ?></div>
		                     	<?php } ?>
	                     	</div>
              			</div>
          			<?php } ?>
          			<?php do_action("jslm_coursereview_coursedetail_all_reviews",$row,$uid,$courseid,$canreview,$studentapprove); ?>
               	</div>
			</div>
        	<?php
		    	}else{
		        	$msg = __('No record found','learn-manager');
		        }
			?>
		</div>
   	</div>
</div>

<?php
}else{
	$msg = __('No record found','learn-manager');
	echo JSLEARNMANAGERlayout::getNoRecordFound($msg);
}
?>
</div>
