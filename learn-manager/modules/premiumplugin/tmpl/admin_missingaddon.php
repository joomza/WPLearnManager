<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php  JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <span class="jslm_js-admin-title"><a class="jsanchor-backlink" href="<?php echo admin_url('admin.php?page=jslearnmanager');?>"><img alt="image" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/back-icon.png" /></a><span class="jsheadtext"><?php echo __('Premium Add ons', 'learn-manager'); ?></span></span>

        <div id="jslearnmanager-data">
            <h1 class="jslearnmanageradmin-missing-addon-message" >
                <?php
                $addon_name = JSLEARNMANAGERrequest::getVar('page');
                $addon_name = explode("_", $addon_name);
                echo ucfirst($addon_name[1]).'&nbsp;';
                echo esc_html(__('addon in no longer active','learn-manager')).'!';
                ?>

            </h1>
        </div>
    </div>
</div>
