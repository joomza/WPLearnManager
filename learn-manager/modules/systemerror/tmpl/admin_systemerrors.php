<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <?php 
        $msgkey = JSLEARNMANAGERincluder::getJSModel('systemerror')->getMessagekey();
        JSLEARNMANAGERmessages::getLayoutMessage($msgkey); 
        ?>
        <div id="jslearnmanageradmin-wrapper-top">
            <div id="jslearnmanageradmin-wrapper-top-left">
                <div id="jslearnmanageradmin-breadcrunbs">
                    <ul>
                        <li>
                            <a href="admin.php?page=jslearnmanager">
                                <?php echo __('Dashboard', 'learn-manager'); ?>
                            </a>
                        </li>
                        <li><?php echo __(' Error Log','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __(' Error Log', 'learn-manager'); ?></span>
        </div>            
    <div id="jslms-data-wrp">
        <?php
        if (!empty(jslearnmanager::$_data[0])) {
            ?>
            <table id="jslm_js-table">
                <thead>
                    <tr>
                        <th class="jslm_left-row"><?php echo __('Error', 'learn-manager'); ?></th>
                        <th ><?php echo __('View', 'learn-manager'); ?></th>
                        <th ><?php echo __('Date', 'learn-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (jslearnmanager::$_data[0] AS $systemerror) {
                        $isview = ($systemerror->isview == 1) ? 'no.png' : 'yes.png';
                        ?>
                        <tr valign="top">
                            <td class="jslm_left-row">
                                <?php echo esc_html($systemerror->error); ?>
                            </td>
                            <td>
                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/<?php echo esc_attr($isview); ?>" />
                            </td>
                            <td>
                                <?php 
                                    echo date_i18n(jslearnmanager::$_config['date_format'], strtotime($systemerror->created)); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <?php
            if (jslearnmanager::$_data[1]) {
                echo '<div class="tablenav"><div class="tablenav-pages">' .jslearnmanager::$_data[1] . '</div></div>';
            }
        } else {
            $msg = __('No record found','learn-manager');
            echo jslearnmanagerlayout::getNoRecordFound($msg);
        }
        ?>
      </div>
   </div>
</div>

