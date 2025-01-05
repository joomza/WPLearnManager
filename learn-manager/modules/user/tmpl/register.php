<div class="jslm_main-up-wrapper">
<?php 
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
if(isset(jslearnmanager::$_error_flag_message)){
    echo jslearnmanager::$_error_flag_message;
}
if (JSLEARNMANAGERincluder::getObjectClass('user')->isguest()) {
?>
<div class="jslm_content_wrapper">
	<div class="jslm_content_data">
		<div class="jslm_search_content no-border no-padding-bottom">
   			<div class="jslm_top_title">
				<div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Register","learn-manager"); ?></h3></div>
			</div>
   		</div>
   		<div class="jslm_register_wrapper">
			<div class="jslm_register_student">
				<a title="<?php echo esc_attr(__("Student register","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'studentregister')))?>" class="jslm_register_anchor">
					<div class="jslm_register_as_a_student">
						<div class="jslm_logo"><img alt="<?php echo esc_attr(__("Student image","learn-manager")); ?>" title="<?php echo esc_attr(__("Student image","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/student.png'); ?>"></div>
						<div class="jslm_title">
							<h2 class="jslm_title_heading"><?php echo esc_html__("To become a student","learn-manager"); ?></h2>
						</div>
						<div class="jslm_text"><?php echo esc_html__("Learn new skills, advance your career, and explore your hobbies by getting advance knowledge.","learn-manager"); ?></div>
					</div>
				</a>
			</div>
			<div class="jslm_register_instructor">
				<a title="<?php echo esc_attr(__("Instructor register","learn-manager")); ?>" href="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'instructorregister')))?>" class="jslm_register_anchor">
					<div class="jslm_register_as_a_instructor">
						<div class="jslm_logo"><img alt="<?php echo esc_attr(__("Instructor image","learn-manager")); ?>" title="<?php echo esc_attr(__("Instructor image","learn-manager")); ?>" src="<?php echo esc_attr(JSLEARNMANAGER_PLUGIN_URL.'includes/images/instuctor.png'); ?>"></div>
						<div class="jslm_title">
							<h2 class="jslm_title_heading"><?php echo esc_html__("To become an instructor","learn-manager"); ?></h2>
						</div>
						<div class="jslm_text"><?php echo esc_html__("Help people learn new skills, advance their careers, and explore their hobbies by sharing your knowledge.","learn-manager"); ?></div>
					</div>
				</a>	
			</div>
		</div>
	</div>
</div>
<?php } ?>
</div>
