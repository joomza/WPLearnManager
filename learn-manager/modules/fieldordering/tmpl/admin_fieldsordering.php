<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

wp_enqueue_script('jquery.min');
wp_enqueue_script('jquery-ui-sortable');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-core');
?>
<script type="text/javascript">
 jQuery(document).ready(function () {
        jQuery("div#jsvm_full_background").click(function () {
            searchclosePopup();
        });

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


<div style="display:none;" id="jslm_ajaxloaded_wait_overlay"></div>
<img style="display:none;" id="jslm_ajaxloaded_wait_image" src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/loading.gif'; ?>">

<div id="jslearnmanageradmin-wrapper">
    <div id="jslm_full_background" style="display:none;"></div>
    <div id="jslm_popup_main" style="display:none;">
    </div>
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <?php
        $msgkey = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getMessagekey();
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
                            <?php
                                if(jslearnmanager::$_data['fieldfor'] == 1){
                                   $fieldName = 'Course Fields';
                                }elseif(jslearnmanager::$_data['fieldfor'] == 2){
                                   $fieldName = 'Lecture Fields';
                                }elseif(jslearnmanager::$_data['fieldfor'] == 3){
                                    $fieldName = 'User Fields';
                                }
                            ?>
                        <li><?php echo esc_html(__($fieldName,'js-support-ticket')); ?></li>
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
         <?php
                if(jslearnmanager::$_data['fieldfor'] == 1){
                   $fieldName = 'Course Fields';
                   $imgPath = JSLEARNMANAGER_PLUGIN_URL."includes/images/add_icon.png";
                   $BtnName = __("Add New","learn-manager") ." ". __("User Field", "learn-manager");
                   $ActionButton = '<a class="jslmsadmin-add-link " href="?page=jslm_fieldordering&jslmslay=formuserfield&ff='.jslearnmanager::$_data["fieldfor"].'"><img src="'. esc_url($imgPath) .'" />'. $BtnName .'</a>';
                   echo wp_kses_post($ActionButton);

                   $videoLink = 'https://www.youtube.com/watch?v=kY15Kjkb8is';
                   $videoTitle = __("Watch Video", "learn-manager");
                   $imgalt = __("arrow", "learn-manager");
                   $videoImgPath = JSLEARNMANAGER_PLUGIN_URL.'includes/images/play-btn.png';
                   $videoBtn = '<a href="'.$videoLink.'" target="_blank" class="jslmsadmin-add-link black-bg  js-cp-video-popup" title="'.$videoTitle.'">
                                <img alt="'.$imgalt.'" src="'.$videoImgPath.'"/>
                                '. $videoTitle.'</a>';
                    echo wp_kses_post($videoBtn);
                }elseif(jslearnmanager::$_data['fieldfor'] == 2){
                   $fieldName = 'Lecture Fields';
                   $imgPath = JSLEARNMANAGER_PLUGIN_URL."includes/images/add_icon.png";
                   $BtnName = __("Add New","learn-manager") ." ". __("User Field", "learn-manager");
                   $ActionButton = '<a class="jslmsadmin-add-link " href="?page=jslm_fieldordering&jslmslay=formuserfield&ff='.jslearnmanager::$_data["fieldfor"].'"><img src="'.esc_url($imgPath) .'" />'. $BtnName .'</a>';
                   echo wp_kses_post($ActionButton);
                }elseif(jslearnmanager::$_data['fieldfor'] == 3){
                    $fieldName = 'User Fields';
                }
            ?>
            <span class="jslm_heading-dashboard"><?php echo esc_html(__($fieldName, 'learn-manager')); ?></span>
                
        </div>            
    <div id="jslms-data-wrp">
            <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
        <div class="jslm_page-actions js-row no-margin ">
             <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_html(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="fieldpublished" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/user-publish.png" /><?php echo __('User Publish', 'learn-manager') ?></a>
            <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_html(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="fieldunpublished" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/user-unpublish.png" /><?php echo __('User Unpublished', 'learn-manager') ?></a>
            <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_html(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="visitorfieldpublished" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/publish-icon.png" /><?php echo __('Visitor Publish', 'learn-manager') ?></a>
            <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_html(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="visitorfieldunpublished" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/unbuplish.png" /><?php echo __('Visitor Unpublished', 'learn-manager') ?></a>
            <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_html(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="fieldrequired" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" /><?php echo __('Required', 'learn-manager') ?></a>
            <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_html(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" data-for="fieldnotrequired" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/no.png" /><?php echo __('Not Required', 'learn-manager') ?></a>
        </div>

        <form class="jslm_js-filter-form" name="jslearnmanagerformtwo" id="jslearnmanagerformtwo" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jslm_fieldordering&ff=" . jslearnmanager::$_data['fieldfor'])); ?>">
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('pagesize', array((object) array('id'=>20,'text'=>20), (object) array('id'=>50,'text'=>50), (object) array('id'=>100,'text'=>100)), jslearnmanager::$_data['filter']['pagesize'],__("Records per page",'learn-manager '), array('class' => 'jslm_inputbox','onchange'=>'document.jslearnmanagerformtwo.submit();')), JSLEARNMANAGER_ALLOWED_TAGS); 
                ?>
        </form>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery("div#jslm_full_background").click(function () {
                    closePopup();
                });
            });

            function resetFrom() {
                jQuery("input#title").val('');
                jQuery("select#ustatus").val('');
                jQuery("select#vstatus").val('');
                jQuery("select#required").val('');
                jQuery("form#jslearnmanagerform").submit();
            }

            function showPopupAndSetValues(id) {
                jQuery('div#jslm_ajaxloaded_wait_overlay').show();
                jQuery('img#jslm_ajaxloaded_wait_image').show();
                jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'fieldordering', task: 'getOptionsForFieldEdit', field: id}, function (data) {
                    if (data) {
                        var d = data;
                        jQuery("div#jslm_full_background").css("display", "block");
                        jQuery("div#jslm_popup_main").html(d);
                        jQuery('div#jslm_ajaxloaded_wait_overlay').hide();
                        jQuery('img#jslm_ajaxloaded_wait_image').hide();
                        jQuery("div#jslm_popup_main").slideDown('slow');
                    }
                });
            }

            function closePopup() {
                jQuery("div#jslm_popup_main").slideUp('slow');
                setTimeout(function () {
                    jQuery("div#jslm_full_background").hide();
                    jQuery("div#jslm_popup_main").html('');
                }, 700);
            }
        </script>
        <form class="jslm_js-filter-form" name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jslm_fieldordering&ff=" . jslearnmanager::$_data['fieldfor'])); ?>">
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('title', jslearnmanager::$_data['filter']['title'], array('class' => 'inputbox', 'placeholder' => __('Title', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('ustatus', JSLEARNMANAGERincluder::getJSModel('common')->getStatus(), is_numeric(jslearnmanager::$_data['filter']['ustatus']) ? jslearnmanager::$_data['filter']['ustatus'] : '', __('Select user status', 'learn-manager'), array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('vstatus', JSLEARNMANAGERincluder::getJSModel('common')->getStatus(), is_numeric(jslearnmanager::$_data['filter']['vstatus']) ? jslearnmanager::$_data['filter']['vstatus'] : '', __('Select visitor status', 'learn-manager'), array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('required', JSLEARNMANAGERincluder::getJSModel('common')->getYesNo(), is_numeric(jslearnmanager::$_data['filter']['required']) ? jslearnmanager::$_data['filter']['required'] : '', __('Required', 'learn-manager'), array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <div class="jslm_filter-bottom-button">
                <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Search', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::button('reset', __('Reset', 'learn-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            </div>
        </form>
        <?php
        if (!empty(jslearnmanager::$_data[0])) {
            ?>
            <form id="jslearnmanager-list-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_fieldordering&task=saveordering"); ?>">
                <table id="jslm_js-table">
                    <thead>
                        <tr>
                            <th class="jslm_grid"><input type="checkbox" name="selectall" id="jslm_selectall" value=""></th>
                            <th class="jslm_ordering"><?php echo __('Ordering', 'learn-manager'); ?></th>
                            <th class="jslm_left-row"><?php echo __('Field Title', 'learn-manager'); ?></th>
                            <th class="jslm_centered"><?php echo __('User Published', 'learn-manager'); ?></th>
                            <th class="jslm_centered"><?php echo __('Visitor Published', 'learn-manager'); ?></th>
                            <th class="jslm_centered"><?php echo __('Required', 'learn-manager'); ?></th>
                            <th class="jslm_action"><?php echo __('Action', 'learn-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum', 'get', 1);
                        $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                        for ($i = 0, $n = count(jslearnmanager::$_data[0]); $i < $n; $i++) {
                            $row = jslearnmanager::$_data[0][$i];
                            if (isset(jslearnmanager::$_data[0][$i + 1]))
                                $row1 = jslearnmanager::$_data[0][$i + 1];
                            else
                                $row1 = jslearnmanager::$_data[0][$i];

                            $uptask = 'fieldorderingup';
                            $downtask = 'fieldorderingdown';
                            $upimg = 'uparrow.png';
                            $downimg = 'downarrow.png';

                            $pubtask = $row->published ? 'fieldunpublished' : 'fieldpublished';
                            $pubimg = $row->published ? 'tick.png' : 'publish_x.png';
                            $alt = $row->published ? __('Published', 'learn-manager') : __('Unpublished', 'learn-manager');
                            $visitorpubtask = $row->isvisitorpublished ? 'visitorfieldunpublished' : 'visitorfieldpublished';
                            $visitorpubimg = $row->isvisitorpublished ? 'tick.png' : 'publish_x.png';
                            $visitoralt = $row->isvisitorpublished ? __('Published', 'learn-manager') : __('Unpublished', 'learn-manager');

                            $requiredtask = $row->required ? 'fieldnotrequired' : 'fieldrequired';
                            $requiredpubimg = $row->required ? 'tick.png' : 'publish_x.png';
                            $requiredalt = $row->required ? __('Required', 'learn-manager') : __('Not Required', 'learn-manager');
                            ?>
                            <tr valign="top" id="id_<?php echo esc_attr($row->id);?>"   >
                                <td class="jslm_grid-rows">
                                    <input type="checkbox" class="jslearnmanager-cb" id="jslearnmanager-cb" name="jslearnmanager-cb[]" value="<?php echo esc_attr($row->id); ?>" />
                                </td>
                                <td class="jsst-order-grab-column">
                                        <img alt="<?php echo __('grab','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/list-full.png'?>"/>
                                </td>

                                <?php
                                $sec = substr($row->field, 0, 8); //get section_
                                $newsection = 0;
                                if ($sec == 'section_') {
                                    $newsection = 1;
                                    $subsec = substr($row->field, 0, 12);
                                    if ($subsec == 'section_sub_') {
                                        ?>
                                        <td class="jslm_left-row" ><strong><?php echo esc_html(__($row->fieldtitle,'learn-manager')); ?></strong></td>
                                    <?php } else { ?>
                                        <td class="jslm_left-row" ><strong><font size="2"><?php echo esc_html(__($row->fieldtitle,'learn-manager')); ?></font></strong></td>
                                    <?php } ?>
                                    <td>
                                        <?php if ($row->cannotunpublish == 1) { ?>
                                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'learn-manager'); ?>" />
                                        <?php
                                        } else {
                                            $icon_name = "no.png";
                                            $task = "fieldpublished";
                                            if ($row->published == 1) {
                                                $task = "fieldunpublished";
                                                $icon_name = "yes.png";
                                            }
                                            ?>
                                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_fieldordering&task='.$task.'&action=jslmstask&jslearnmanager-cb[]='.$row->id.$pageid.'&ff='.jslearnmanager::$_data['fieldfor']),'fieldordering-published')); ?>">
                                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($alt); ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($row->cannotunpublish == 1) { ?>
                                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'learn-manager'); ?>" />
                                        <?php
                                        } else {
                                            $icon_name = "no.png";
                                            $task = "visitorfieldpublished";
                                            if ($row->isvisitorpublished == 1) {
                                                $task = "visitorfieldunpublished";
                                                $icon_name = "yes.png";
                                            }
                                            ?>
                                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_fieldordering&task='.$task.'&action=jslmstask&jslearnmanager-cb[]'.$row->id.$pageid.'&ff='.jslearnmanager::$_data['fieldfor']),'fieldordering-visitorpublished')); ?>">
                                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($visitoralt); ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td><a href="#" onclick="showPopupAndSetValues(<?php echo esc_js($row->id); ?>)" ><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/edit.png" title="<?php echo __('Edit', 'learn-manager'); ?>"></a></td>
                        <?php } else { ?>
                                    <td class="jslm_left-row">
                                        <?php echo esc_html(__($row->fieldtitle,'learn-manager')); ?>
                                    </td>
                                    <td>
                                        <?php if ($row->cannotunpublish == 1) { ?>
                                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'learn-manager'); ?>" />
                                        <?php
                                        } else {
                                            $icon_name = "no.png";
                                            $task = "fieldpublished";
                                            if ($row->published == 1) {
                                                $task = "fieldunpublished";
                                                $icon_name = "yes.png";
                                            }
                                            ?>
                                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_fieldordering&task='.$task.'&action=jslmstask&jslearnmanager-cb[]='.$row->id.$pageid.'&ff='.jslearnmanager::$_data['fieldfor']),'fieldordering-published')); ?>">
                                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($alt); ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($row->cannotunpublish == 1) { ?>
                                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" title="<?php echo __('Cannot unpublished', 'learn-manager'); ?>" />
                                        <?php
                                        } else {
                                            $icon_name = "no.png";
                                            $task = "visitorfieldpublished";
                                            if ($row->isvisitorpublished == 1) {
                                                $task = "visitorfieldunpublished";
                                                $icon_name = "yes.png";
                                            }
                                            ?>
                                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_fieldordering&task='.$task.'&action=jslmstask&jslearnmanager-cb[]='.$row->id.$pageid.'&ff='.jslearnmanager::$_data['fieldfor']),'fieldordering-visitorpublished')); ?>">
                                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($visitoralt); ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($row->sys == 1) { ?>
                                            <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/yes.png" title="<?php echo __('Cannot required', 'learn-manager'); ?>" />
                                        <?php
                                        } else {
                                            $icon_name = "no.png";
                                            $task = "fieldrequired";
                                            if ($row->required == 1) {
                                                $task = "fieldnotrequired";
                                                $icon_name = "yes.png";
                                            }
                                            ?>
                                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_fieldordering&task='.$task.'&action=jslmstask&jslearnmanager-cb[]='.$row->id.$pageid.'&ff='.jslearnmanager::$_data['fieldfor']),'fieldordering-required')); ?>">
                                                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/<?php echo esc_attr($icon_name); ?>" alt="<?php echo esc_attr($requiredalt); ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                   
                                    <td class="jslm_action">
                                        <a href="#" onclick="showPopupAndSetValues(<?php echo esc_js($row->id); ?>)" ><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/edit.png" title="<?php echo __('Edit', 'learn-manager'); ?>"></a>
                                        <?php if ($row->isuserfield == 1) { ?>
                                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_fieldordering&task=remove&action=jslmstask&fieldid='.$row->id.'&ff='.jslearnmanager::$_data['fieldfor']),'fieldordering-remove')); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/remove.png" title="<?php echo __('Delete', 'learn-manager'); ?>"></a>
                                        <?php } ?>
                                    </td>
                        <?php
                        $newsection = 0;
                    }
                    ?>

                            </tr>
                <?php
            }
            ?>
                    </tbody>
                </table>

            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('fields_ordering_new', '123'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('task', ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('fieldfor',jslearnmanager::$_data['fieldfor']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('ff',jslearnmanager::$_data['fieldfor']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
			<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('_wpnonce', wp_create_nonce('fieldordering-saveordering')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <div class="jslms_saveordering_btn" style="display: none;">
                <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save Ordering', 'jslearnmanager'), array('class' => 'button js-form-save')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            </div>

            </form>
        <?php
        if (jslearnmanager::$_data[1]) {
            echo '<div class="tablenav">
                <div class="tablenav-pages">
                ' . jslearnmanager::$_data[1] . '
                </div>
            </div>';
         }
     } else {
        $msg = __('No record found','learn-manager');
        $link[] = array(
                    'link' => 'admin.php?page=jslm_fieldordering&jslmslay=formuserfield&ff='.jslearnmanager::$_data['fieldfor'],
                    'text' => __('Add New','learn-manager') .' '. __('User Field','learn-manager')
                );
        echo JSLEARNMANAGERlayout::getNoRecordFound($msg,$link);
    }
    ?>
      </div>
    </div>
</div>
