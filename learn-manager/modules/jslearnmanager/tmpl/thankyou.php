<div class="jslm_main-up-wrapper">
<?php 
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);?>
	<div class="lms_lm_error_message_wrapper lms_background_grad">
		<div class="lms_lm_error_msg_image">
		    <img alt="<?php echo esc_attr(__('Thank You Image','learn-manager'));?>" title=" <?php echo esc_attr(__('Thank You Image','learn-manager'));?>" src="<?php echo esc_attr(LEARN_MANAGER_IMAGE);?>/thankyou.png"/>
		</div>
		<div class="lms_lm_error_msg_text">
		    <div class="lms_message-text-bold">
		        <h4 class="lms-heading-style">
		            <?php echo esc_attr(__('Thank You so much for visiting Js Learn Manager','learn-manager'));?>
		        </h4>
		    </div>
		    <div class="lms_message-text">
		    	<h5 class="lms-subheading-style">
		        <?php echo esc_attr(__('We will love to see you again','learn-manager'));?>
		        </h5>
		    </div>
		</div>
	</div>
</div> 
