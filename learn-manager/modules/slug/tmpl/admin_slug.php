<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-sortable');
$configuration = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('default');
$msgkey = JSLEARNMANAGERincluder::getJSModel('slug')->getMessagekey();
JSLEARNMANAGERMessages::getLayoutMessage($msgkey);
?>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php  JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
	<div id="full_background" style="display:none;"></div>
    <div id="jslm_popup_main" style="display:none;"></div>
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
                        <li><?php echo __('Slug','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Slug', 'learn-manager'); ?></span>
        </div>            
    <div id="jslms-data-wrp">
        <script type="text/javascript">/*Function to Show popUp,Reset*/
            var slug_for_edit = 0;
            jQuery(document).ready(function () {
            jQuery("div#full_background").click(function () {
              closePopup();
               });
            });
            
            function resetFrom() {// Resest Form
                jQuery("input#slug").val('');
                jQuery("form#jslearnmanagerform").submit();
            }
            function showPopupAndSetValues(id,slug) {//Showing PopUp
                slug = jQuery('td#td_'+id).html();
                slug_for_edit = id;
                jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'slug', task: 'getOptionsForEditSlug',id:id ,slug:slug }, function (data) {
                if (data) {
                    var d = (data);
                    jQuery("div#full_background").css("display", "block");
                    jQuery("div#jslm_popup_main").html(d);
                    jQuery("div#jslm_popup_main").slideDown('slow');
                }
                });
            }
            function closePopup() {// Close PopUp
                jQuery("div#jslm_popup_main").slideUp('slow');
                setTimeout(function () {
                jQuery("div#full_background").hide();
                jQuery("div#jslm_popup_main").html('');
                }, 700);
            }
            function getFieldValue() {
                var slugvalue = jQuery("#slugedit").val();
                jQuery('input#'+slug_for_edit).val(slugvalue);
                jQuery('td#td_'+slug_for_edit).html(slugvalue);
                closePopup();
            }
        </script>
        
        <!-- <form class="jslm_js-filter-form slug-configform" name="jslearnmanagerform" id="conjslearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_slug&task=saveprefix"); ?>">
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('prefix', $configuration['home_slug_prefix'], array('class' => 'inputbox', 'placeholder' => __('Slug Prefix', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?> 
            <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Save', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <span class="slug-prefix-msg"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/info-icon.png"><?php echo __("This prefix will be added to slug incase of homepage links","learn-manager"); ?></span>
        </form> -->
        <form class="jslm_js-filter-form slug-configform" name="jslearnmanagerform" id="conjslearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_slug&task=saveprefix"); ?>">
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('prefix', $configuration['slug_prefix'], array('class' => 'inputbox', 'placeholder' => __('Slug Prefix', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?> 
            <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Save', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <span class="slug-prefix-msg"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/info-icon.png"><?php echo __("This prefix will be added to slug incase of conflict","learn-manager"); ?></span>
        </form>
        <form class="jslm_js-filter-form" name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_slug"); ?>">
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('slug', jslearnmanager::$_data['slug'], array('class' => 'inputbox', 'placeholder' => __('Search By Slug', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Search', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::button('reset', __('Reset', 'learn-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEANMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </form>
        <a class="button" href="admin.php?page=jslm_slug&task=resetallslugs&action=jslmstask" >
            <?php echo __('Reset All','learn-manager'); ?>
        </a>
        <?php
        if (!empty(jslearnmanager::$_data[0])) {
            ?>
            <form id="jslearnmanager-list-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_slug&task=saveSlug"); ?>">
                <table id="js-table">
                    <thead>
                        <tr>
                            <th class="left-row"><?php echo __('Slug List', 'learn-manager'); ?></th>
                            <th class="left-row"><?php echo __('Description', 'learn-manager'); ?></th>
                            <th class="action"><?php echo __('Action', 'learn-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum', 'get', 1);
                        $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                        foreach (jslearnmanager::$_data[0] as $row){
                            ?>
                            <tr valign="top">
                                <td class="left-row" id="<?php echo 'td_'.$row->id;?>"><?php echo esc_html($row->slug);?></td>
                                <td class="left-row"><?php echo esc_html(__($row->description,'jslearnmanager'));?></td>
                                <td class="action">
                                <a href="#" onclick="showPopupAndSetValues(<?php echo esc_js($row->id); ?>)">
                                    <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/edit.png" title="<?php echo __('Edit','learn-manager'); ?>"> </a></td>
                            </tr>
                                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden($row->id, $row->slug), JSLEARNMANAGER_ALLOWED_TAGS);?>
                            <?php
                             }
                            ?>
                    </tbody>
                </table>
                <!-- Hidden Fields -->
                <div class="jslm_js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
                    <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Save', 'learn-manager'), array('class' => 'button savebutton')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('task', ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                </div>    
            </form>
         <?php
            if (jslearnmanager::$_data[1]) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . jslearnmanager::$_data[1] . '</div></div>';
            }
            ?>
            <?php
        } else {
            $msg = __('No record found','learn-manager');
            echo JSLEARNMANAGERlayout::getNoRecordFound($msg);
        }
        ?>
       </div>
    </div>
</div>
