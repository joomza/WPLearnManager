<div class="jslm_main-up-wrapper">
<?php  if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
function getDataRow($title,$value){
	$html = '<div class="jslm_custom_field">
				<span class="jslm_heading">'. $title .':</span>';
	$html .=	'<span class="jslm_text">'. $value .'</span>
			</div>';
	return $html;
}

if(!empty(jslearnmanager::$_data[0]['coursedetail'])){
	$row = jslearnmanager::$_data[0]['coursedetail'];
}else{
	$row = "";
}
function parsevideoUrlType($url) {
    if (strpos($url, 'youtu') > 0) {
        return 'youtube';
    } elseif (strpos($url, 'vimeo') > 0) {
        return 'vimeo';
    } else {
        return 'unknown';
    }
}

function createEmbedUrl($url){
	$finalurl = '';
	$type = parsevideoUrlType($url);
	$regs = array();
	if($type == 'youtube'){
		$regex = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
		preg_match($regex, $url,$regs);
		// $id = preg_replace( $regex, '$1', $url );
		$id = $regs[1];
		$finalurl = '//www.youtube.com/embed/'.$id;
	}elseif($type == 'vimeo'){
		$regex = '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im';
		preg_match($regex, $url,$regs);
		$id = $regs[3];
		$finalurl = '//player.vimeo.com/video/'.$id;
	}else{
		$finalurl = '';
	}
	return $finalurl;
}
$validExtImages = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
?>
<center><div id="loading" style="display: none"><img alt="<?php echo esc_attr(__("Loading image","learn-manager")); ?>" title="<?php echo esc_attr(__("Loading image","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/loading.gif'); ?>"></div></center>
<div class="jslm_content_wrapper">
   	<div class="jslm_content_data">
   		<?php /*if(isset($row->course_id) && $row->course_id != ""){*/ ?>
   		<div class="jslm_search_content no-border no-padding-bottom">
	      	<div class="jslm_top_title">
	        	<div class="jslm_left_data">
	        		<h3 class="jslm_title_heading">
	        	<?php if(jslearnmanager::$_error_flag_message == null){
	        		echo esc_html(__($row->title,"learn-manager"));
        		}else{
        			echo __("Lecture","learn-manager");
    			} ?>
    				</h3>
    			</div>
	      		<?php if(jslearnmanager::$_error_flag_message == null){ ?>
		      		<div class="jslm_right_data">
						<span class="jslm_sorting">
							<a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course','jslmslay'=>'coursedetails', 'jslearnmanagerid'=>$row->course_id))); ?>" class="jslm_link"><?php echo __("Back to course","learn-manager"); ?></a>
						</span>
					</div>
				<?php } ?>
	      	</div>
	    </div>
	    <?php if(jslearnmanager::$_error_flag_message == null){ ?>
	   		<div class="jslm_data_container no-padding-top">
	   			<div class="js-col-md-12 jslm_lecture_curriculum_wrp">
		            <div id="jslm_section_list" class="js-col-md-4 jslm_left_section_list jslm_col_md_4_for_modal_rtl">
		   				<div class="jslm_accordian_wrp">
			   				<div id="accordion" class="jslm-panel-group">
				   				<?php if(count(jslearnmanager::$_data['sections']) > 0){
				   						$a=1;
				   						$p = 0;
				   						$coursecomplete = 0;
				   						for($i=0; $i<count(jslearnmanager::$_data['sections']); $i++){
				   							$countlecture = 0;
				   							$section = jslearnmanager::$_data['sections'][$i];
				   							$student_course = jslearnmanager::$_data['student_course'];
				   							$studentattnd_lecture = json_decode($student_course->lecture_completion_params);
				   							if(isset($studentattnd_lecture->{$section->section_id})){
				   								$countlecture = count($studentattnd_lecture->{$section->section_id});
				   							}	?>
				   							<div class="panel jslm_panel_style">
										      	<div class="panel-heading">
						                          	<div class="panel-title jslm_panel_title">
						                             	<div class="jslm_edit_section_wrapper">
					                                		<h5 class="jslm_section_heading">
					                                			<a title="<?php echo esc_attr(__("Section","learn-manager")); ?>" href="#panelModal_jslm<?php echo esc_attr($section->section_id); ?>" class="accordion-toggle accordion collapsed jslm_accordian_anchor jslm_section_title" data-toggle="collapse" >
					                                				<?php if (strlen($section->section_name) > 35) { echo substr(__($section->section_name,"learn-manager"), 0, 35). " .... ";} else  { echo __($section->section_name,"learn-manager"); } ?>
						                                		</a>
					                                		</h5>
						                            	</div>
						                            </div>
						                        </div>
						                        <div id="panelModal_jslm<?php echo esc_attr($section->section_id); ?>" class="panel-collapse collapse">
						                            <div class="panel-body jslm_lecture_panel_style">
														<div class="jslm_panel_body_wrapper">
												        <?php if(count(jslearnmanager::$_data['lectures'][$section->section_id]) > 0){
												        		for($l = 0; $l<count(jslearnmanager::$_data['lectures'][$section->section_id]); $l++){
												        			$lecture = jslearnmanager::$_data['lectures'][$section->section_id][$l];
												        			$lecture->progress = 0;
												        			if(isset($studentattnd_lecture->{$section->section_id})){
												        				$curatndlecture = $studentattnd_lecture->{$section->section_id};
												        				for($attnd = 0; $attnd < count($curatndlecture); $attnd++){
												        					if($curatndlecture[$attnd]->lecture_id == $lecture->lecture_id){
												        						$lecture->progress = 1;
												        						break;
												        					}
												        				}
												        			}	?>
														          	<div id="panellecture_jslm<?php echo esc_attr($lecture->lecture_id); ?>" class="jslm_row_data">
														          		<a title="<?php echo esc_attr(__("Lecture","learn-manager")); ?>" class="jslm_view_section_anchor" onclick="saveLectureProgress(<?php echo jslearnmanager::$_data['lecture']->lecture_id; ?>)" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'jslmslay'=>'lecturedetails', 'jslearnmanagerid'=>$lecture->lecture_id, 'jslearnmanagerpageid'=>jslearnmanager::getPageid())))?>"><span class="jslm_bold jslm_title"><?php echo esc_html__("Lecture","learn-manager") . " " . $a; ?>: <?php if (strlen($lecture->lecture_name) > 30) { echo substr(__($lecture->lecture_name,"learn-manager"), 0, 30). " .... ";} else  { echo __($lecture->lecture_name,"learn-manager"); } ?> </span></a>
														     		</div>
												     	<?php $a++;	}
												     		} ?>
											     		</div>
											        </div>
										        </div>
										    </div>
								<?php 	}
									}	?>
							</div>
						</div>
					</div>
					<div class="jslm_close_btn">
						<button id="closesectionpanel" class="closebtn jslm-section-panel-btn"><i class="fa fa-close"></i></button>
						<button id="opensectionpanel" style="display: none;" class="arrowright jslm-section-panel-btn"><i class="fa fa-angle-right"></i></button>
					</div>
					<div id="jslm_right_panel" class="js-col-md-8 js-col-xs-12 jslm-no-padding-right jslm_col_md_8_for_modal_rtl">
						<div class="jslm_curriculum_right_wrp">
							<div class="jslm_lecture_heading">
								<h3 class="jslm_lecture_title"><?php echo esc_html(__(jslearnmanager::$_data['lecture']->lecture_name)); ?></h3>
							</div>
							<div class="jslm_right_content_wrapper">
								<ul class="nav nav-tabs jslm_modal_ul">
								    <li class="active jslm_modal_li"><a title="<?php echo esc_attr(__("Text","learn-manager")); ?>" class="jslm_modal_anchor jslm_left_border jslm_bigfont" data-toggle="tab" href="#text"><img id="description" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/description-grey.png"><?php echo esc_html__("Overview","learn-manager")?></a></li>
								    <li class="jslm_modal_li"><a title="<?php echo esc_attr(__("Files","learn-manager")); ?>" class="jslm_modal_anchor jslm_bigfont" data-toggle="tab" href="#images"><img id="fileid" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/curriculum-grey.png"><?php echo esc_html__("Files","learn-manager")?></a></li>
								    <li class="jslm_modal_li"><a title="<?php echo esc_attr(__("Videos","learn-manager")); ?>" class="jslm_modal_anchor jslm_bigfont" data-toggle="tab" href="#videos"><img id="lecturevideosid" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/course/members-grey.png"><?php echo esc_html__("Videos","learn-manager")?></a></li>
								    <?php do_action("jslm_quiz_show_lecture_quiz_tab_for_student"); ?>
								</ul>
								<div class="tab-content">
								    <div id="text" class="tab-pane fade in active"><!-- content CODE -->
								    	<div class="jslm_lecture_content_wrapper">
								    		<?php
								    			if(!empty(jslearnmanager::$_data['lecture'])){
								    				$lectureinfo = jslearnmanager::$_data['lecture'];	?>
									    			<div class="jslm_middle_content">
										    			<div class="jslm_heading">
									    					<h4 class="jslm_heading_style"><?php echo esc_html__("Lecture Description","learn-manager")?></h4>
									    				</div>
									    				<div id="lecturedescription" class="jslm_body jslm_description">
									    					<?php if($lectureinfo->description != ""){
									    						echo wp_kses_post($lectureinfo->description);
									    					}else{
									    						echo JSLEARNMANAGERlayout::getNoRecordFound('No Data Found');
									    					} ?>
									    				</div>
										    		</div>
									    		<?php $iscustomfield = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsData(2);
		                  							if(!empty($iscustomfield)){ ?>
											    		<div class="jslm_custom_fields_wrapper">
															<div class="jslm_custom_fields_heading">
																<h4 class="jslm_heading_style"><?php echo esc_html__("Additional Features","learn-manager")?></h4>
															</div>
															<?php foreach(jslearnmanager::$_data['lecturecustom'] as $fields){
																	switch($fields->field){
																		default:
																		if($fields->isuserfield == 1){
																			$array = JSLEARNMANAGERincluder::getObjectClass('customfields')->showCustomFields($fields, 1, $lectureinfo->params,2,$lectureinfo);
			                            									echo getDataRow(__($array[0],'learn-manager'), __($array[1],'learn-manager'));
																		}
																		break;
																	}
															 	}
														 	?>
														</div>
									    		<?php
									    			}
						    					}else{
						    						$msg = __('No record found','learn-manager');
	        										echo JSLEARNMANAGERlayout::getNoRecordFound($msg);
						    					}
					    					?>
								    	</div>
								    </div>
								    <div id="images" class="tab-pane fade"><!-- Files CODE -->
								    	<div id="lectureimages" class="jslm_lecture_content_wrapper">
											<?php if(count(jslearnmanager::$_data['files']) > 0){ ?>
													<div class="jslm_download_all">
														<a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'action'=>'jslmstask', 'task'=>'downloadall', 'id'=>$lectureinfo->lecture_id, 'layout'=>2)))?>"><?php echo __("Download All Files","learn-manager"); ?></a>
													</div>
													<?php for($f = 0; $f < count(jslearnmanager::$_data['files']); $f++){
									    				$file = jslearnmanager::$_data['files'][$f];
									    				if (strstr($validExtImages, $file->filetype)) {
														    $allow = "image";
														} else {
														    $allow = "iframe";
														}?>
											    		<div class="jslm_files_content_data">
												    		<span class="jslm_left_data">
												    			<i class="fa fa-file"></i>
												    			<a title="<?php echo esc_attr(__("File","learn-manager")); ?>" href="#" class="jslm_image_anchor">
												    				<?php if (strlen($file->filename) >15) { echo substr(__($file->filename,"learn-manager"), 0, 15). " ... "; } else { echo __($file->filename,"learn-manager"); } ?>
												    			</a>
												    		</span>
												    		<span class="jslm_right_anchor">
												    			<a data-gall="gallery01" data-vbtype="<?php echo esc_attr($allow); ?>" title="<?php echo esc_attr(__("View File","learn-manager")); ?>" href="<?php echo esc_url($file->fileurl); ?>" class="jslm_actions venobox" target="_blank"><i class="fa fa-eye"></i></a>
												    			<?php
												    				$filelectureid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
																	$splitch = explode("_",$filelectureid);
												                    if($splitch[0] == 'retake'){
												                    	$filelectureid = $splitch[1];
													     				// unset($studentresult->{$lectureid});
													     			}
												    			?>
												    			<a title="<?php echo esc_attr(__("Download","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'action'=>'jslmstask', 'task'=>'downloadbyname', 'name'=>$file->filename, 'id'=>$filelectureid, 'layout'=>2)))?>" class="jslm_actions"><i class="fa fa-download"></i></a>
												    		</span>
											    		</div>
									    	<?php 	}
									    		}else{
									    			echo JSLEARNMANAGERlayout::getNoRecordFound('No Data Found');
									    		}?>
								    	</div>
								    </div>
								    <div id="videos" class="tab-pane fade"><!-- videos CODE -->
								    	<div id="lecturevideos" class="jslm_lecture_content_wrapper">
								    		<?php if(count(jslearnmanager::$_data['videofiles']) > 0){
													for($v=0; $v < count(jslearnmanager::$_data['videofiles']); $v++){
														$video = jslearnmanager::$_data['videofiles'][$v];	?>
										                <div class="jslm-iframe">
											                <iframe class="jslm-iframe-element" src="<?php echo createEmbedUrl($video->videourl); ?>" frameborder="0" allowfullscreen></iframe>
											                <a class="venobox" data-gall="gallery02" data-vbtype="video" data-title="<?php echo esc_attr($video->filename); ?>" href="<?php echo esc_url($video->videourl); ?>"><i class="fa fa-play"></i></a>
										                </div>
											<?php 	}
							  			  		}else{
							  			  			echo JSLEARNMANAGERlayout::getNoRecordFound('No Data Found');
							  			  		} ?>
								        </div>
								    </div>
								    <?php do_action("jslm_quiz_lecturedetail_quiz_form"); ?>
								</div>
							</div>
						</div>
						<?php
						$currentlectureid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
						$splitch = explode("_",$currentlectureid);
	                    if($splitch[0] == 'retake'){
	                    	$currentlectureid = $splitch[1];
		     				// unset($studentresult->{$lectureid});
		     			}
						$courseid = JSLEARNMANAGERincluder::getJSModel('course')->getCourseIdByLectureId($currentlectureid);
						$allsection = JSLEARNMANAGERincluder::getJSModel('course')->getSectionIdsbyCourseId($courseid);
						$sectioncount = count($allsection);
						$lequal = false;
						if($sectioncount > 0){
							$next = -1;
							$previous = -1;
							for($s = 0; $s< $sectioncount; $s++){
								$alllecture = JSLEARNMANAGERincluder::getJSModel('lecture')->getLectureIdbySectionId($allsection[$s]);
								$lecturecount = count($alllecture);
								for($l = 0; $l < $lecturecount; $l++){
									if($currentlectureid == $alllecture[$l]){
										if(($l+1) == $lecturecount){
											$lequal = true;
											if($lecturecount > 1){
												$previous = $alllecture[$l-1];
											}else{
												if($s > 0 && ($s+1) <= $sectioncount){
													$previouslecture = JSLEARNMANAGERincluder::getJSModel('lecture')->getLectureIdbySectionId($allsection[$s-1]);
													$countpreviouslectures = count($previouslecture);
													$previous = $previouslecture[$countpreviouslectures-1];
												}
											}
											break;
										}elseif(($l+1) < $lecturecount){
											if($l > 0){
												$previous = $alllecture[$l-1];
											}elseif($l == 0){
												if($s > 0 && ($s+1) <= $sectioncount){
													$previouslecture = JSLEARNMANAGERincluder::getJSModel('lecture')->getLectureIdbySectionId($allsection[$s-1]);
													$countpreviouslectures = count($previouslecture);
													$previous = $previouslecture[$countpreviouslectures-1];
												}
											}
											$next = $alllecture[$l+1];
											break;
										}
										break;
									}
								}
								if($lequal){
									if(($s+1)==$sectioncount){
										break;
									}elseif(($s+1)<$sectioncount){
										$alllecture = JSLEARNMANAGERincluder::getJSModel('lecture')->getLectureIdbySectionId($allsection[$s+1]);
										if(count($alllecture) > 0){
											$next = $alllecture[0];
										}
									}
									break;
								}
								if($next != -1){
									break;
								}
							}
							if($next == -1){
								echo '<script>jQuery(document).ready(function(){
									saveLectureProgress('.jslearnmanager::$_data['lecture']->lecture_id.');
								});</script>';
							}
						}
						if(count(jslearnmanager::$_data['sections']) > 0){	?>
							<div class="jslm_bottom_actions_button jslm_bottom_margin">
								<?php if($previous != -1){ ?>
								<div class="jslm_left_button">
									<a title="<?php echo esc_attr(__("Previous Lecture","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'jslmslay'=>'lecturedetails', 'jslearnmanagerid'=>$previous, 'jslearnmanagerpageid'=>jslearnmanager::getPageid())))?>" class="jslm_back"><i class="fa fa-long-arrow-left"></i> <?php echo esc_html__("Previous","learn-manager")?><?php echo esc_html__(" Lecture","learn-manager")?></a>
								</div>
								<?php }	?>
								<div class="jslm_right_button">
									<?php if($coursecomplete == count(jslearnmanager::$_data['sections'])){	?>
										<a title="<?php echo esc_attr(__("Course Completed","learn-manager")); ?>" class="jslm_text"><?php echo esc_html__("Completed","learn-manager")?></a>
									<?php }if($next != -1){ ?>
										<a title="<?php echo esc_attr(__("Next Lecture","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'jslmslay'=>'lecturedetails', 'jslearnmanagerid'=>$next, 'jslearnmanagerpageid'=>jslearnmanager::getPageid())))?>" id="nextlecture" onclick="saveLectureProgress(<?php echo jslearnmanager::$_data['lecture']->lecture_id; ?>)"  class="jslm_next"><?php echo esc_html__("Next","learn-manager")?><?php echo esc_html__(" Lecture","learn-manager")?> <i class="fa fa-long-arrow-right"></i></a>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>

				</div>
	        </div>
		<?php }else{
			echo jslearnmanager::$_error_flag_message;
		} ?>
		<?php /*}else{
			JSLEARNMANAGERLayout::getNoRecordFound();
		}*/ ?>
    </div>
</div>
</div>


