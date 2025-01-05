<?php
if(!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery.min');
wp_enqueue_script('jquery-ui-sortable');
?>
<script type="text/javascript">
 jQuery(document).ready(function () {
        jQuery('table#jslm_js-table tbody').sortable({ 
            handle : ".jsst-order-grab-column",
            update  : function () {
                jQuery('.jslms_saveordering_btn').slideDown('slow');
                var abc =  jQuery('table#jslm_js-table tbody').sortable('serialize');
                jQuery('input#fields_ordering_new').val(abc);
            }
        });
    });
</script>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <?php
        $msgkey = JSLEARNMANAGERincluder::getJSModel('currency')->getMessagekey();
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
                        <li><?php echo __('Currency','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Currency', 'learn-manager'); ?></span>
            <a class="jslmsadmin-add-link " href="<?php echo admin_url('admin.php?page=jslm_currency&jslmslay=formcurrency'); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/add_icon.png" /><?php echo __('Add New','learn-manager') .' '. __('Currency','learn-manager'); ?></a>
        </div>            
    <div id="jslms-data-wrp">
          <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
          <div class="jslm_page-actions">
             <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="publish" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/publish-icon.png" /><?php echo __('Publish', 'learn-manager') ?></a>
             <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="unpublish" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/unbuplish.png" /><?php echo __('Unpublished', 'learn-manager') ?></a>
             <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo __('Are you sure to delete', 'learn-manager').' ?'; ?>" data-for="remove" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'learn-manager') ?></a>
           
          </div>
         <form class="jslm_js-filter-form" name="recordperpageform" id="recordperpageform" method="post" action="<?php echo admin_url("admin.php?page=jslm_currency"); ?>">
          <?php echo wp_kses(JSLEARNMANAGERformfield::select('pagesize', array((object) array('id'=>20,'text'=>20), (object) array('id'=>50,'text'=>50), (object) array('id'=>100,'text'=>100)), jslearnmanager::$_data['filter']['pagesize'],__("Records per page",'learn-manager '), array('class' => 'jslm_inputbox','onchange'=>'document.recordperpageform.submit();')), JSLEARNMANAGER_ALLOWED_TAGS); 
                ?>
          </form>
        <script type="text/javascript">
            function resetFrom() {
                jQuery("input#title").val('');
                jQuery("select#status").val('');
                jQuery("form#jslearnmanagerform").submit();
            }
        </script>
        <form class="jslm_js-filter-form" name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_currency"); ?>">
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('title', jslearnmanager::$_data['filter']['title'], array('class' => 'jslm_inputbox', 'placeholder' => __('Title', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('status', JSLEARNMANAGERincluder::getJSModel('common')->getstatus(), jslearnmanager::$_data['filter']['status'], __('Select status', 'learn-manager'), array('class' => 'jslm_inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Search', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
           
           <?php echo wp_kses(JSLEARNMANAGERformfield::button('reset', __('Reset', 'learn-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>

        </form>

        <?php
        if (!empty(jslearnmanager::$_data[0])) {
            ?>
            <form id="jslearnmanager-list-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_currency&task=saveordering"); ?>">
                <table id="jslm_js-table">
                    <thead>
                        <tr>
                            <th class="jslm_grid"><input type="checkbox" name="selectall" id="jslm_selectall" value=""></th>
                            <th class="jslm_centered"><?php echo __('Ordering', 'learn-manager'); ?></th>
                            <th class="jslm_left-row"><?php echo __('Title', 'learn-manager'); ?></th>
                            <th class="jslm_centered"><?php echo __('Published', 'learn-manager'); ?></th>
                            <th class="jslm_centered"><?php echo __('Default', 'learn-manager'); ?></th>
                            <th class="jslm_action"><?php echo __('Action', 'learn-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum', 'get', 1);
                        $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                        $islastordershow = JSLEARNMANAGERpagination::isLastOrdering(jslearnmanager::$_data['total'], $pagenum);
                        for ($i = 0, $n = count(jslearnmanager::$_data[0]); $i < $n; $i++) {
                            $row = jslearnmanager::$_data[0][$i];
                            $upimg = 'uparrow.png';
                            $downimg = 'downarrow.png';
                            ?>
                            <tr valign="top" id="id_<?php echo esc_attr($row->id);?>">
                                <td class="jslm_grid-rows">
                                    <input type="checkbox" class="jslearnmanager-cb" id="jslearnmanager-cb" name="jslearnmanager-cb[]" value="<?php echo esc_attr($row->id); ?>" />
                                </td>
                                <td class="jsst-order-grab-column">
                                        <img alt="<?php echo __('grab','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/list-full.png'?>"/>   
                               </td>
                                <td class="jslm_left-row">
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=jslm_currency&jslmslay=formcurrency&jslearnmanagerid='.$row->id)); ?>">
                                        <?php echo esc_html(__($row->title,'learn-manager')); ?></a>
                                </td>

                                <td>
                                    <?php if ($row->status == 1) { ?>
                                        <a href="<?php echo admin_url('admin.php?page=jslm_currency&task=unpublish&action=jslmstask&jslearnmanager-cb[]='.$row->id.$pageid); ?>">
                                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" border="0" alt="<?php echo __('Published', 'learn-manager'); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo admin_url('admin.php?page=jslm_currency&task=publish&action=jslmstask&jslearnmanager-cb[]='.$row->id.$pageid); ?>">
                                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" border="0" alt="<?php echo __('Not Published', 'learn-manager'); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($row->isdefault == 1) { ?>
                                        <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/default.png" alt="Default" border="0" />
                                    <?php } else { ?>
                                        <a href="<?php echo admin_url('admin.php?page=jslm_common&task=makedefault&action=jslmstask&for=currency&id='.$row->id.$pageid.'&jslmslay=currencies'); ?>">
                                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/notdefault.png" border="0" alt="Not Default" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td class="jslm_action">
                                    <a href="<?php echo admin_url('admin.php?page=jslm_currency&jslmslay=formcurrency&jslearnmanagerid='.$row->id); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/edit.png" title="<?php echo __('Edit', 'learn-manager'); ?>"></a>
                                    <a href="<?php echo admin_url('admin.php?page=jslm_currency&task=remove&action=jslmstask&jslearnmanager-cb[]='.$row->id); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/remove.png" title="<?php echo __('Delete', 'learn-manager'); ?>"></a>
                                </td>
                            </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('fields_ordering_new', '123'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'adexpiry_remove'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('task', ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <div class="jslms_saveordering_btn" style="display: none;">
                        <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save Ordering', 'jslearnmanager'), array('class' => 'button js-form-save')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
             </div>
            </form>
            <?php
            if (jslearnmanager::$_data[1]) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . jslearnmanager::$_data[1] . '</div></div>';
            }
        } else {
            $msg = __('No record found','learn-manager');
            $link[] = array(
                        'link' => 'admin.php?page=jslm_currency&jslmslay=formcurrency',
                        'text' => __('Add New','learn-manager') .' '. __('Currency','learn-manager')
                    );
            echo JSLEARNMANAGERlayout::getNoRecordFound($msg,$link);
        }
        ?>
      </div>
    </div>
</div>
