<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
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
                        <li><?php echo __(' Information','js-support-ticket'); ?></li>
                    </ul>
                </div>
            </div>
                        <div id="jslearnmanageradmin-wrapper-top-right">
                 <div id="jslearnmanageradmin-help-txt">
                   <a Href="<?php echo esc_url(admin_url("admin.php?page=jslearnmanager&jslmslay=help")); ?>" title="<?php echo __('help','leARN-MANAGER'); ?>">
                        <img alt="<?Php ecHo __('help','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help.png" />
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
            <span class="jslm_heading-dashboard"><?php echo __('Information', 'learn-manager'); ?></span>
        </div>            
    <div id="jslms-data-wrp">
	        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
	    <span class="jslm_js-admin-component"><?php echo __('Component Details', 'learn-manager'); ?></span>
	    <div class="jslm_detail-part">
	        <span class="jslm_js-admin-component-detail">    </span>
	        <div class="jslm_js-admin-info-wrapper">
	            <span class="jslm_js-admin-info-title"><?php echo __('Created By', 'learn-manager'); ?></span>
	            <span class="jslm_js-admin-info-vlaue"><?php echo 'Ahmed Bilal'; ?></span>
	        </div>
	        <div class="jslm_js-admin-info-wrapper">
	            <span class="jslm_js-admin-info-title"><?php echo __('Company', 'learn-manager'); ?></span>
	            <span class="jslm_js-admin-info-vlaue"><?php echo 'Joom Sky'; ?></span>
	        </div>
	        <div class="jslm_js-admin-info-wrapper">
	            <span class="jslm_js-admin-info-title"><?php echo __('Plugins', 'learn-manager'); ?></span>
	            <span class="jslm_js-admin-info-vlaue"><?php echo 'Learn Manager'; ?></span>
	        </div>
	        <div class="jslm_js-admin-info-wrapper">
	            <span class="jslm_js-admin-info-title"><?php echo __('Version', 'learn-manager'); ?></span>
	            <span class="jslm_js-admin-info-vlaue"><?php echo esc_html(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
	        </div>
	    </div>
	    <div class="jslm_js-admin-joomsky-wrapper ">
	        <span class="jslm_js-admin-title">
	            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/about-us/logo.png" />
	        </span>
	        <span class="jslm_detail-text">
	            <span class="jslm_detail-heading">
	                <?php echo 'About JoomSky'; ?>
	            </span>
	            <?php echo 'Our philosophy on project development is quite simple. We deliver exactly what you need to ensure the growth and effective running of your business. To do this we undertake a complete analysis of your business needs with you, then conduct thorough research and use our knowledge and expertise of software development programs to identify the products that are most beneficial to your business projects.'; ?>
	            <span class="jslm_js-joomsky-link">
	                <a href="http://www.joomsky.com" target="_blank">www.joomsky.com</a>
	            </span>
	        </span>
	    </div>
        <div class="jslm_products">
            <div class="jslm_components" id="jslm_jobs-free">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/about-us/joomla.png" />
                <span class="jslm_component-text">
                    <span class="jslm_component-title">
                        <?php echo 'JS Jobs'; ?>
                    </span>    
                    <span class="jslm_component-type">
                        <?php echo 'Joomla'; ?>
                    </span>    
                    <span class="jslm_component-detail">
                        <?php echo 'JS Jobs for any business, industry body or staffing company wishing to establish a presence on the internet where job seekers can come to view the latest jobs and apply to them.JS Jobs allows you to run your own, unique jobs classifieds service where you or employer can advertise their jobs and jobseekers can upload their Resume'; ?>
                    </span>    
                </span>
                <span class="jslm_info-urls">
                    <a class="jslm_pro" href="http://www.joomsky.com/products/js-jobs-pro.html">
                        <?php echo 'Pro Download'; ?>
                    </a>
                    <a class="jslm_free" href="http://www.joomsky.com/products/js-jobs.html">
                        <?php echo 'Free Download'; ?>
                    </a>
                </span>
            </div>
            <div class="jslm_components" id="jslm_autoz-pro">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/about-us/wordpress.png" />
                <span class="jslm_component-text">
                    <span class="jslm_component-title">
                        <?php echo 'JS Jobs'; ?>
                    </span>    
                    <span class="jslm_component-type">
                        <?php echo 'WordPress'; ?>
                    </span>    
                    <span class="jslm_component-detail">
                        <?php echo 'JS Jobs for any business, industry body or staffing company wishing to establish a presence on the internet where job seekers can come to view the latest jobs and apply to them.JS Jobs allows you to run your own, unique jobs classifieds service where you or employer can advertise their jobs and jobseekers can upload their Resumes'; ?>
                    </span>    
                </span>
                <span class="jslm_info-urls">
                    <a class="jslm_pro" href="http://www.joomsky.com/products/js-jobs-pro-wp.html">
                        <?php echo 'Pro Download'; ?>
                    </a>
                    <a class="jslm_free" href="http://www.joomsky.com/products/js-jobs-wp.html">
                        <?php echo 'Free Download'; ?>
                    </a>
                </span>
            </div>
            <div class="jslm_components" id="jslm_ticket-free">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/about-us/joomla.png" />
                <span class="jslm_component-text">
                    <span class="jslm_component-title">
                        <?php echo 'JS Support Ticket'; ?>
                    </span>    
                    <span class="jslm_component-type">
                        <?php echo 'Joomla'; ?>
                    </span>    
                    <span class="jslm_component-detail">
                        <?php echo 'JS Support Ticket is a trusted open source ticket system. JS Support ticket is a simple, easy to use, web-based customer support system. User can create ticket from front-end. JS support ticket comes packed with lot features than most of the expensive(and complex) support ticket system on market.'; ?>
                    </span>    
                </span>
                <span class="jslm_info-urls">
                    <a class="jslm_pro" href="http://www.joomsky.com/products/js-supprot-ticket-pro-joomla.html">
                        <?php echo 'Pro Download'; ?>
                    </a>
                    <a class="jslm_free" href="http://www.joomsky.com/products/js-supprot-ticket-joomla.html">
                        <?php echo 'Free Download'; ?>
                    </a>
                </span>
            </div>
            <div class="jslm_components" id="jslm_autoz-free">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/about-us/wordpress.png" />
                <span class="jslm_component-text">
                    <span class="jslm_component-title">
                        <?php echo 'JS Support Ticket'; ?>
                    </span>    
                    <span class="jslm_component-type">
                        <?php echo 'WordPress'; ?>
                    </span>    
                    <span class="jslm_component-detail">
                        <?php echo 'JS Support Ticket is a trusted open source ticket system. JS Support ticket is a simple, easy to use, web-based customer support system. User can create ticket from front-end. JS support ticket comes packed with lot features than most of the expensive(and complex) support ticket system on market.'; ?>
                    </span>    
                </span>
                <span class="jslm_info-urls">
                    <a class="jslm_pro" href="http://www.joomsky.com/products/js-supprot-ticket-pro-wp.html">
                        <?php echo 'Pro Download'; ?>
                    </a>
                    <a class="jslm_free" href="http://www.joomsky.com/products/js-supprot-ticket-wp.html">
                        <?php echo 'Free Download'; ?>
                    </a>
                </span>
            </div>
            <div class="jslm_components" id="jslm_ticket-pro">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/about-us/joomla.png" />
                <span class="jslm_component-text">
                    <span class="jslm_component-title">
                        <?php echo 'JS Autoz'; ?>
                    </span>    
                    <span class="jslm_component-type">
                        <?php echo 'Joomla'; ?>
                    </span>    
                    <span class="jslm_component-detail">
                        <?php echo 'JS Autoz is robust and powerful vehicles show room component for Joomla. JS Autoz help you build online show room with clicks. With admin power you can easily manage makes, models, types etc. in admin area.'; ?>
                    </span>    
                </span>
                <span class="jslm_info-urls">
                    <a class="jslm_pro" href="http://www.joomsky.com/products/js-autoz-pro.html">
                        <?php echo 'Pro Download'; ?>
                    </a>
                    <a class="jslm_free" href="http://www.joomsky.com/products/js-autoz.html">
                        <?php echo 'Free Download'; ?>
                    </a>
                </span>
            </div>
            <div class="jslm_components" id="jslm_vehicle-pro">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/about-us/wordpress.png" />
                <span class="jslm_component-text">
                    <span class="jslm_component-title">
                        <?php echo 'JS Vehicle Manager'; ?>
                    </span>    
                    <span class="jslm_component-type">
                        <?php echo 'WordPress'; ?>
                    </span>    
                    <span class="jslm_component-detail">
                        <?php echo 'JS Vehicle Mana is robust and powerful vehicles show room component for Joomla. JS Autoz help you build online show room with clicks. With admin power you can easily manage makes, models, types etc. in admin area.'; ?>
                    </span>    
                </span>
                <span class="jslm_info-urls">
                    <a class="jslm_pro" href="http://www.joomsky.com/products/js-vehicle-manager-pro-wp.html">
                        <?php echo 'Pro Download'; ?>
                    </a>
                    <a class="jslm_free" href="http://www.joomsky.com/products/js-vehicle-manager-wp.html">
                        <?php echo 'Free Download'; ?>
                    </a>
                </span>
            </div>
        </div>
  

      </div>
    </div>
</div>
