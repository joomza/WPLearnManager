<div class="jslm_main-up-wrapper">
<?php 
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
?>
<div class="jslm_content_wrapper"> <!-- lower bottom Content -->
  	<div class="jslm_content_data">
    	<div class="jslm_search_content no-border no-padding-bottom">
      		<div class="jslm_top_title">
        		<div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Dashboard","learn-manager"); ?></h3></div>
      		</div>
    	</div>
    	<?php 
    	if(jslearnmanager::$_error_flag_message != null){
			echo jslearnmanager::$_error_flag_message;
		} ?>
    </div>
</div>    	
</div>
