<?php
$msgkey = JSLEARNMANAGERincluder::getJSModel('premiumplugin')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php'); ?>
<div class="jslm_content_wrapper">
   <div class="jslm_content_data">
   		<div class="jslm_data_container">
			<?php
			$msg = __('Page not found','js-learn-manager');
		    echo JSLEARNMANAGERlayout::getNoRecordFound($msg);
			?>
		</div>
	</div>
</div>

