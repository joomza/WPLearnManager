<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
    	 <div id="jslearnmanageradmin-wrapper-top">
    	    <div id="jslearnmanageradmin-wrapper-top-left">
    	        <div id="jslearnmanageradmin-breadcrunbs">
    	            <ul>
                        <li>
                            <a href="admin.php?page=jslearnmanager">
                                <?php echo __('Dashboard', 'learn-manager'); ?>
                            </a>
                        </li>
                        <li><?php echo __(' Help','learn-manager'); ?></li>
                    </ul>
    	        </div>
    	    </div>
    	   <div id="jslearnmanageradmin-wrapper-top-right">
                <div id="jslearnmanageradmin-help-txt">
                   <a href="<?php echo esc_url(admin_url("admin.php?page=jslearnmanager&jslmslay=help")); ?>" title="<?php echo __('Help','learn-manager'); ?>">
                        <img alt="<?php echo __('Help','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help.png" />
                    </a>
                </div>
                <div id="jslearnmanageradmin-vers-txt">
                    <?php echo __('Version :'); ?>
                    <span class="jslearnmanageradmin-ver">
                        <?php echo esc_html(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?>
                    </span>
                </div>

            </div>
    	</div>
         <div class="jslm_dashboard">
            <span class="jslm_heading-dashboard"><?php echo __(' Help', 'learn-manager'); ?></span>
        </div>
    	 <div id="jslms-help-data-wrp" class="p0 bg-n bs-n">
    		<!-- help page -->
    		<div class="jslmadmin-help-top">
    			<div class="jslmadmin-help-top-left">
    				<div class="jslmadmin-help-top-left-cnt-img">
    					<img alt="<?php echo __('Help icon','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/support-icon.jpg" />
    				</div>
    				<div class="jslmadmin-help-top-left-cnt-info">
    					<h2><?php echo __('We are here to help you','learn-manager'); ?></h2>
    					<p><?php echo __('WP Learn Manager is a professional, simple, easy to use and complete customer support system.','learn-manager'); ?></p>
    					<a href="https://www.youtube.com/channel/UC89tFPkjkrqHcKmEDL0KlRg/videos" target="_blank" class="jslmadmin-help-top-middle-action" title="<?php echo __('View all videos','learn-manager'); ?>"><img alt="<?php echo __('Video icon','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/play-icon.jpg" /><?php echo __('View All Videos','learn-manager'); ?></a>
    				</div>
    			</div>
    			<div class="jslmadmin-help-top-right">
    				<div class="jslmadmin-help-top-right-cnt-img">
    					<img alt="<?php echo __('Learn Manager icon','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/support.png" />
    				</div>
    				<div class="jslmadmin-help-top-right-cnt-info">
    					<h2><?php echo __('WP Learn Manager Support','learn-manager'); ?></h2>
    					<p><?php echo __("WP Learn Manager delivers timely customer support if you have any query then we're here to show you the way.",'learn-manager'); ?></p>
    					<a target="_blank" href="https://wplearnmanager.com/support/" class="jslmadmin-help-top-middle-action second" title="<?php echo __('Submit ticket','learn-manager'); ?>"><img alt="<?php echo __('Video icon','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/ticket.png" /><?php echo __('Submit Ticket','learn-manager'); ?></a>
    				</div>
    			</div>
    		</div>
    		<div class="jslmadmin-help-btm">
    			<!-- courses -->
    			<div class="jslmadmin-help-btm-wrp">
    				<h2 class="jslmadmin-help-btm-title"><?php echo __('Courses','learn-manager'); ?></h2>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=AWsse6gyz_M" class="jslmadmin-help-btm-link"  target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to create a course','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to create a course','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=cPH5zKlhNpo" class="jslmadmin-help-btm-link"  target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to create a free course','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to create free a course','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=erq_E_9Kfuk" class="jslmadmin-help-btm-link"  target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('Create paid course','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('Create paid course','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=_qk_9Lme4RU" class="jslmadmin-help-btm-link"  target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to shortlist course','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to shortlist course','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=gLs0mlzbvwU" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('Featured course','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('Featured course','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                      <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=0zSAuMeqWqY" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to review course','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to review course','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=mLZYP5EA-lg" class="jslmadmin-help-btm-link"  target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to mark the course as new','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to mark the course as new','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=RdhBUCdBAjU" class="jslmadmin-help-btm-link"  target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to enroll in a course','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to enroll in a course','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=QNCdyvdeOXU" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to enroll in course front-end side','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to enroll in course front-end side','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=kY15Kjkb8is" class="jslmadmin-help-btm-link"  target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to set up custom fields','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to set up custom fields','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                     <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=LpPYFKZZKHQ" class="jslmadmin-help-btm-link"  target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to set up custom fields (Instructor)','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to set up custom fields (Instructor)','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>


                </div>
            <!-- Quiz -->
                <div class="jslmadmin-help-btm-wrp">
                    <h2 class="jslmadmin-help-btm-title"><?php echo __('Quiz','learn-manager'); ?></h2>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=jful1hjhigc" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('Quiz','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('Quiz','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=pHbz8Mcr4rc" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to take quiz','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to take quiz','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                       <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=i-VvP9kScyA" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to retake quiz','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to retake quiz','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>

                </div>
    			<!-- configurations -->
    			<div class="jslmadmin-help-btm-wrp">
    				<h2 class="jslmadmin-help-btm-title"><?php echo __('Configurations','learn-manager'); ?></h2>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=OGISy5aTqFg" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to set captcha','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to set captcha','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>

    			</div>
    			<!-- setup -->
    			<div class="jslmadmin-help-btm-wrp">
    				<h2 class="jslmadmin-help-btm-title"><?php echo __('Setup','learn-manager'); ?></h2>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=veQt-3QlMw8" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to setup','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to setup','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=jm9SgfuMqMc" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('Registration in WP Learn Manager','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('Registration','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>


    				<div class="jslmadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=fY6uVljRYeE" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to use shortcodes','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to use shortcodes','learn-manager'); ?></span>
                            </div>
    					</a>
    				</div>


    			</div>
    			<!-- misc -->
    			<div class="jslmadmin-help-btm-wrp">
    				<h2 class="jslmadmin-help-btm-title"><?php echo __('Misc','learn-manager'); ?></h2>
    				<div class="jslmadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=rjGY0eYmGa0" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to use the award system','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to use the award system','learn-manager'); ?></span>
                            </div>
    					</a>
    				</div>
    				<div class="jslmadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=2lF2BFVDXRk" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to use the social share','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to use the social share','learn-manager'); ?></span>
                            </div>
    					</a>
    				</div>
    				<div class="jslmadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=VmnSdesIpHQ" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to use message system','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to use message system','learn-manager'); ?></span>
                            </div>
    					</a>
    				</div>
    				<div class="jslmadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=ZE-TcbvE8jQ" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to use a payment plan','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to use a payment plan','learn-manager'); ?></span>
                            </div>
    					</a>
    				</div>
                      <div class="jslmadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=2KrkdIDV5_k" class="jslmadmin-help-btm-link" target="_blank">
                            <div class="jslmadmin-help-btm-cnt-img">
                                <img alt="<?php echo __('How to use reports','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="jslmadmin-help-btm-cnt-title">
                                <span><?php echo __('How to use reports','learn-manager'); ?></span>
                            </div>
                        </a>
                    </div>
    			</div>

    		</div>
		</div>
	</div>
</div>
