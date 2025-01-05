<div class="jslm_main-up-wrapper">
<?php if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('lecture')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
$image_file_types = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
$file_size = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_file_size');
$file_file_types = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('file_file_type');
?>
<div id="loading" class="jslm_loader_loading" style="display: none"><img alt="<?php echo esc_attr(__("Loading image","learn-manager")); ?>" title="<?php echo esc_attr(__("Loading image","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/loading.gif'); ?>"></div>
<div class="jslm_content_wrapper">
   	<div class="jslm_content_data">
		<div class="jslm_search_content no-border no-padding-bottom">
			<?php  if(isset(jslearnmanager::$_data[0]->id)){
				$titlevalue = "Edit Lecture";
			}else{
				$titlevalue = "Add Lecture";
			}

			?>
   			<div class="jslm_top_title">
				<div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo esc_html(__($titlevalue,"learn-manager")); ?></h3></div>
				<?php if(jslearnmanager::$_error_flag_message == null){ ?>
		      		<div class="jslm_right_data">
						<span class="jslm_sorting">
							<a href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course','jslmslay'=>'editcourse', 'jslearnmanagerid'=>jslearnmanager::$_data['course_info']->course_id))); ?>" class="jslm_link"><?php echo __("Back to course","learn-manager"); ?></a>
						</span>
					</div>
				<?php } ?>
			</div>
		</div>
   		<?php
   		if (jslearnmanager::$_error_flag_message == null) { ?>
			<div class="jslm_data_container">
				<div class="jslm_top_content_data">
			   		<?php $course = jslearnmanager::$_data['course_info']; ?>
					<div class="jslm_title_content">
						<h4 class="jslm_title_heading">
							<a title="<?php echo esc_attr(__("Edit course","learn-manager")); ?>" class="jslm_course_title" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>JSLEARNMANAGERrequest::getVar('page_id'), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$course->course_id))); ?>"><?php if (strlen($course->course_name) > 40){ echo substr(esc_html($course->course_name), 0, 70)." ... ";} else { echo esc_html($course->course_name); } ?></a>
						</h4>
						<span class="jslm_title_category">
							<?php echo esc_html($course->category_name); ?>
						</span>
					</div>
				</div>
				<div class="jslm_addlecture_wrapper">
					<form method="post" name="detail_lectureForm" id="jslm_detail_lectureForm" enctype="multipart/form-data" action="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'action'=>'jslmstask', 'task'=>'savelecture', 'jslearnmanagerpageid'=>jslearnmanager::getPageid()))); ?>">
						<div class="jslm_form_wrapper">
							<?php foreach (jslearnmanager::$_data[3] AS $field) {
									switch ($field->field) {
										case 'name':?>
											<div class="jslm_input_field">
												<div class="jslm_input_title"><?php echo esc_html__("Lecture Title","learn-manager")?>
													<?php
													if ($field->required == 1) {
						                				$req = 'required';
						                				echo '<font color="red">&nbsp*</font>';
						             				} ?>
					             				</div>
												<div class="jslm_input"><?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->name) ? __(jslearnmanager::$_data[0]->name , 'learn-manager') : '',array('placeholder'=>'Add Title','class'=>'jslm_input_field_style', 'data-validation'=>$req , 'maxlength'=>$field->maxlength)), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
											</div>
										<?php
										break;
										case 'description': ?>
											<div class="jslm_input_field">
												<div class="jslm_input_title"><?php echo esc_html__("Lecture Description","learn-manager")?>
													<?php
													if ($field->required == 1) {
						                				$req = 'required';
						                				echo '<font color="red">&nbsp*</font>';
						             				} ?>
					             				</div>
												<div class="jslm_textarea">
													<?php echo wp_editor(isset(jslearnmanager::$_data[0]->description) ? jslearnmanager::$_data[0]->description: '', $field->field, array('media_buttons' => false, 'data-validation' => $req)); ?>
												</div>
											</div>

										<?php break;
										default:
											echo JSLEARNMANAGERincluder::getObjectClass('customfields')->formCustomFields($field,2);

								 		break;
									}
								}
							?>
							<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', isset(jslearnmanager::$_data[0]->id) ? __(jslearnmanager::$_data[0]->id , 'learn-manager') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
							<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('section_id', isset(jslearnmanager::$_data['course_info']->section_id) ? jslearnmanager::$_data['course_info']->section_id : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
							<!-- Add Article -->
							<!-- Add Files & Images -->
							<div class="jslm_file_wrapper">
						    	<h6 class="jslm_heading"><?php echo esc_html__("Add images & files","learn-manager")?> </h6>
								<?php foreach (jslearnmanager::$_data[3] AS $field) {
										switch ($field->field) {
											case 'filename':	?>
												<div class="jslm_file_wrapper" id="js_uploadFiles">
													<div class="jslm_file_upload jslm_add_file_upload">
							                          	<label for="upload" class="jslm_file_upload_label"><?php echo esc_html__("Upload Files","learn-manager")?></label>
							                          	<input id="uploadfiles_0" class="jslm_file_upload_input" type="file" name="filename[]" multiple="multiple" onchange="getUploadedFiles()"/>
								                    </div>
													<span class="jslm_file_extension"><?php echo esc_html__("Max file size","learn-manager")?> <?php echo esc_html($file_size). esc_html__(" Kb.","learn-manager") ; ?><?php echo esc_html__(" Allowed Type: ","learn-manager")?><?php echo "(".esc_html($image_file_types).", ".esc_html($file_file_types).")"; ?></span>
													<span class="jslm_file_extension"><?php echo esc_html__("Files Allowed :","learn-manager")?> <?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('max_allowed_lecturesfiles') != "" ? JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('max_allowed_lecturesfiles') : 0; ?></span>
												</div>
												<div id="appendfiles" class="jslm_append_files">
													<div class="jslm_heading_wrp" style="display: none" id="addnewfilediv"><h6 class="jslm_heading"><?php echo esc_html__("Recently added files","learn-manager")?> </h6></div>
												</div>
												<?php if(isset(jslearnmanager::$_data['files']) && count(jslearnmanager::$_data['files']) > 0){ ?>
					 								<div class="jslm_img_list">
									      				<div class="jslm_heading_wrp"><h6 class="jslm_heading"><?php echo esc_html__("Existing Images & Files","learn-manager")?> </h6></div>
									      				<?php
										      				for($f=0, $fp = count(jslearnmanager::$_data['files']); $f < $fp; $f++ ){
										      					$filedata = jslearnmanager::$_data['files'][$f]; ?>
													      		<div class="jslm_row jslm-ml-10" id="jslm_file_<?php echo esc_attr($filedata->file_id); ?>">
													      			<span class="jslm_left jslm_left_files"><i class="fa fa-file-image-o"></i>
													      				<?php echo esc_html($filedata->filename); ?>
													      			</span>
													      			<span class="jslm_right">
													      				<input type="hidden" name="deletefiles[]" id="remove_old_<?php echo esc_attr($filedata->file_id);?>" data-atr-lecture_id="<?php echo esc_attr($filedata->file_id); ?>" value="" />
													      				<span class="jslm_logo"><a title="<?php echo esc_attr(__("Delete File","learn-manager")); ?>" href="#" onclick="removeOldfile(jslm_file_<?php echo esc_js($filedata->file_id); ?>,<?php echo esc_js($filedata->file_id); ?>)" class="jslm_logos"><i class="fa fa-trash"></i></a></span>
													      				<?php if (strstr($image_file_types, $filedata->filetype)) {
																		    $allow = "image";
																		} else {
																		    $allow = "iframe";
																		} ?>
													      				<span class="jslm_logo"><a data-gall="gallery01" data-vbtype="<?php echo esc_attr($allow); ?>" title="<?php echo esc_attr(__("View File","learn-manager")); ?>" href="<?php echo esc_url($filedata->fileurl); ?>" class="jslm_logos venobox" ><i class="fa fa-eye"></i></a></span>
													      			</span>
													      		</div>
														<?php
														}	?>
									      			</div>
									      		<?php } ?>

								<?php  	break;
										}
									}	?>
									<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_sid', isset(jslearnmanager::$_data['course_info']->section_id) ? __(jslearnmanager::$_data['course_info']->section_id , 'learn-manager') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
									<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('lecture_id', isset(jslearnmanager::$_data[0]->id) ? __(jslearnmanager::$_data[0]->id , 'learn-manager') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
							</div>
							<!-- Add Files & Images -->
							<!-- Add Videos-->
					    	<?php foreach (jslearnmanager::$_data[3] AS $field) {
									switch ($field->field) {
					    				case 'file_type': ?>
								    		<div class="jslm_file_wrapper">
									    		<h6 class="jslm_heading"><?php echo esc_html__("Add media and Videos","learn-manager")?> </h6>
									    		<div class="jslm_file_wrapper jslm_video_wrapper">
													<input class="jslm_video_title" type="text" name="videotitle" id="videotitle" placeholder="<?php echo esc_html__("Video Title","learn-manager"); ?>"  >
													<input class="jslm_video_url" type="text" name="videourl" id="videourl" placeholder="<?php echo esc_html__("Video Url","learn-manager"); ?>">
													<span class="jslm_file_extension"><?php echo esc_html__("Allowed Types:","learn-manager")?><?php echo esc_html__("Youtube,Vimeo","learn-manager")?></span>
													<div class="jslm_save_button">
														<button class="jslm_btn_style" onclick="getvideourl()" type="button" required><?php echo esc_html__("Add to List","learn-manager"); ?></button>
													</div>
												</div>
												<div class="jslm_img_list">
													<div id="add_video_url" class="jslm_append_files">
														<div class="jslm_heading_wrp" style="display: none" id="addnewvideodiv"><h6 class="jslm_heading"><?php echo esc_html__("Recently added videos","learn-manager")?> </h6></div>
													</div>
													<?php if(isset(jslearnmanager::$_data['videofiles']) && count(jslearnmanager::$_data['videofiles'])>0){ ?>
															<div class="jslm_heading_wrp"><h6 class="jslm_heading"><?php echo esc_html__("Existing media and Videos","learn-manager")?> </h6></div>
															<?php
															for ($f=0, $fp =count(jslearnmanager::$_data['videofiles']); $f < $fp; $f++) {
																if(jslearnmanager::$_data['videofiles'][$f]->filetype == 'video'){
																	$videofiles = jslearnmanager::$_data['videofiles'][$f];
																}
													?>
											      		<div id="jslm_file_<?php echo esc_attr($videofiles->file_id); ?>" class="jslm_row jslm_video_row">
															<span class="jslm_video_left ">
																<span class="jslm_left_logo">
															 		<i class="fa fa-youtube-play"></i>
																</span>
																<span class="jslm_right_data">
																	 <span class="jslm_video_title"><?php echo isset($videofiles->filename) ? __($videofiles->filename , 'learn-manager') : '' ;?></span>
																	 <span class="jslm_video_url"><a title="<?php echo esc_attr(__("Video link","learn-manager")); ?>" class="jslm_video_url_link" href="<?php echo esc_url($videofiles->videourl); ?>" target="_blank"> <?php echo isset($videofiles->videourl) ? __($videofiles->videourl , 'learn-manager') : '' ;?></a></span>
																</span>
															</span>
															<span class="jslm_right jslm_right_for_video">
																<input type="hidden" name="deletevideos[]" id="remove_old_<?php echo esc_attr($videofiles->file_id);?>" value="" />
																<span class="jslm_logo"><a title="<?php echo esc_attr(__("Delete Video","learn-manager")); ?>" href="#" onclick="removeOldfile(jslm_file_<?php echo esc_js($videofiles->file_id); ?>,<?php echo esc_js($videofiles->file_id); ?>)" class="jslm_logos"><i class="fa fa-trash"></i></a></span>
																<span class="jslm_logo"><a data-gall="gallery02" data-vbtype="video" data-title="<?php echo esc_attr($videofiles->filename); ?>" title="<?php echo esc_attr(__("View Video","learn-manager")); ?>" href="<?php echo esc_url($videofiles->videourl); ?>" target="_blank" class="jslm_logos venobox"><i class="fa fa-eye"></i></a></span>
															</span>
														</div>
											      	<?php }
											      		}
											      		?>
											      	</div>
											      	<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_sid', isset(jslearnmanager::$_data['course_info']->section_id) ? __(jslearnmanager::$_data['course_info']->section_id , 'learn-manager') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
													<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('lecture_id', isset(jslearnmanager::$_data[0]->id) ? __(jslearnmanager::$_data[0]->id , 'learn-manager') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
											</div>
								<?php 	break;
								}
							} ?>
							<!-- Add Videos-->

							<!-- Add Quiz-->
							<?php do_action("jslm_quiz_wrapper_for_quiz_for_frontend"); ?>
							<!-- Add Quiz-->
							<div class="jslm_save_button">
				      			<button id="jslm_save_lecture_btn" class="jslm_btn_style" type="submit"><?php echo esc_html__("Save Lecture","learn-manager"); ?></button>
				      			<button id="jslm_save_lecture_btn" class="jslm_btn_style jslm_save_new" type="submit" name="saveandnew" value="<?php echo esc_attr(JSLEARNMANAGERrequest::getVar('jslearnmanagerid')); ?>"><?php echo esc_html__("Save & New","learn-manager"); ?></button>
								<a title="<?php echo esc_attr(__("Cancel Lecture","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid' =>$course->course_id,'jslearnmanagerpageid'=>jslearnmanager::getPageid()))); ?>#curriculum"><button type="button" class="jslm_btn_style jslm_cancel"><?php echo esc_html__("Cancel","learn-manager"); ?></button></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		<?php }else{
			echo jslearnmanager::$_error_flag_message;
		} ?>
	</div>
</div>
</div>
