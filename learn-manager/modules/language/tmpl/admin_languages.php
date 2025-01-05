<?php if (!defined('ABSPATH'))die('Restricted Access'); ?>
<script type="text/javascript">
    function resetFrom() {
        jQuery("input#language").val('');
        jQuery("select#status").val('');
        jQuery("form#jslearnmanagerform").submit();
    }
</script>

<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php echo  JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
    <?php 
    $msgkey = JSLEARNMANAGERincluder::getJSModel('language')->getMessagekey();
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
                        <li><?php echo __('Course Languages ','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Course Languages ', 'learn-manager'); ?></span>
            <a class="jslmsadmin-add-link" href="<?php echo esc_url(admin_url('admin.php?page=jslm_language&jslmslay=formlanguage')); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/add_icon.png" /><?php echo __('Add New','learn-manager') .'&nbsp;'. __('Language', 'learn-manager') ?></a>
        </div>            
   <div id="jslms-data-wrp">
     <div class="jslm_page-actions js-row no-margin">
        <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="publish" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/publish-icon.png" /><?php echo __('Publish', 'learn-manager') ?></a>
        <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="unpublish" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/unbuplish.png" /><?php echo __('Unpublish', 'learn-manager') ?></a>
        <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo __('Are you sure to delete','learn-manager'); ?>" data-for="remove" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete-icon.png" /><?php echo __('Delete','learn-manager') ?></a>
    </div>
    <form class="jslm_js-filter-form" name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_language"); ?>">
        <?php echo wp_kses(JSLEARNMANAGERformfield::text('language', jslearnmanager::$_data['filter']['language'], array('class' => 'jslm_inputbox', 'placeholder' => __('Language', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::select('status', JSLEARNMANAGERincluder::getJSModel('common')->getstatus(), jslearnmanager::$_data['filter']['status'], __('Select status', 'learn-manager'), array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Search', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSLEARNMANAGERformfield::button('reset', __('Reset', 'learn-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
    </form>
    <?php
    if (!empty(jslearnmanager::$_data[0])) {
        ?>          
        <form id="jslearnmanager-list-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_language"); ?>">
            <table id="jslm_js-table">
                <thead>
                    <tr>
                        <th class="jslm_grid"><input type="checkbox" name="selectall" id="jslm_selectall" value=""></th>
                        <th class="jslm_left-row"><?php echo __('Title', 'learn-manager'); ?></th>
                        <th class="jslm_centered"><?php echo __('Published', 'learn-manager'); ?></th>
                        <th class="jslm_action"><?php echo __('Action', 'learn-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0, $n = count(jslearnmanager::$_data[0]); $i < $n; $i++) {
                        $row = jslearnmanager::$_data[0][$i]; ?>
                        <tr valign="top">
                            <td class="jslm_grid-rows">
                                <input type="checkbox" class="jslearnmanager-cb" id="jslearnmanager-cb" name="jslearnmanager-cb[]" value="<?php echo esc_attr($row->id); ?>" />
                            </td>
                            <td class="jslm_left-row">
                                <a href="<?php echo admin_url('admin.php?page=jslm_language&jslmslay=formlanguage&jslearnmanagerid='.$row->id); ?>">
                                    <?php echo esc_html(__($row->language,'learn-manager')); ?></a>
                            </td>

                            <td>
                                <?php if ($row->status == 1) { ?>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_language&task=unpublish&action=jslmstask&jslearnmanager-cb[]='.$row->id.'&pagenum='.JSLEARNMANAGERrequest::getVar('pagenum'))); ?>">
                                        <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Published', 'learn-manager'); ?>" />
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_language&task=publish&action=jslmstask&jslearnmanager-cb[]='.$row->id.'&pagenum='.JSLEARNMANAGERrequest::getVar('pagenum'))); ?>">
                                        <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Not Published', 'learn-manager'); ?>" />
                                    </a>
                                <?php } ?>
                            </td>
                            <td class="jslm_action">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_language&jslmslay=formlanguage&jslearnmanagerid='.$row->id)); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/edit.png" title="<?php echo __('Edit', 'learn-manager'); ?>"></a>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jslm_language&task=remove&action=jslmstask&jslearnmanager-cb[]='.$row->id),'delete-courselanguage'); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/remove.png" title="<?php echo __('Delete', 'learn-manager'); ?>"></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('task', ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('_wpnonce', wp_create_nonce('delete-courselanguage')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </form>
        <?php
        if (jslearnmanager::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . jslearnmanager::$_data[1] . '</div></div>';
        }
    } else {
        $msg = __('No record found','learn-manager');
        $link[] = array(
                    'link' => 'admin.php?page=jslm_courselevel&jslmslay=formcourselevel',
                    'text' => __('Add New','learn-manager') .'&nbsp;'. __('Level','learn-manager')
                );
        echo JSLEARNMANAGERlayout::getNoRecordFound($msg,$link);
    }
    ?>
</div>
</div>
</div>
