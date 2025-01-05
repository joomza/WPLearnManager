<?php

	// Add widgets files
	include_once( 'jslearn_manager_course_search_widget.php');
	
	function js_learn_manager_register_widgets(){
		register_widget('js_learn_manager_search_course_widget');
		// 
	}

	add_action('widgets_init','js_learn_manager_register_widgets');
?>
