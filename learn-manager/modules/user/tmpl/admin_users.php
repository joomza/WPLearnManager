<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    function confirmdelete() {
        if (confirm("<?php echo __('Are you sure to remove','learn-manager') . ' ?'; ?>") == true) {
            return false;
        } else {
            event.preventDefualt();
            return false;
        }
        return false;
    }
    function resetFrom() {
        jQuery("input#searchname").val('');
        jQuery("input#email").val('');
        jQuery("select#status").val('');
        jQuery('searchrole').val('');
        jQuery("form#jslearnmanagerform").submit();
    }
</script>
<?php
$categoryarray = array(
    (object) array('id' => 2, 'text' => __('Name', 'learn-manager')),
    (object) array('id' => 3, 'text' => __('Created', 'learn-manager')),
);
?>

<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <?php
        $msgkey = JSLEARNMANAGERincluder::getJSModel('user')->getMessagekey();
        JSLEARNMANAGERMessages::getLayoutMessage($msgkey);
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
                        <li><?php echo __('Users','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Users', 'learn-manager'); ?></span>
        </div>            
       <div id="jslms-data-wrp">
        <?php
        $message = __('Are you sure to delete','learn-manager') . ' ?';
        $desc = __('Delete user data','learn-manager').'\'s '.__('data only from our system','learn-manager');
        $desc2 = __('Delete user and their data also from wp','learn-manager');
        ?>
        <div class="jslm_page-actions js-row jslm_no-margin">
            <label class="jslm_js-bulk-link button" for="jslm_selectall"><input type="checkbox" name="selectall" id="jslm_selectall" value=""><?php echo __('Select All', 'learn-manager') ?></label>
            <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_attr(JSLEARNMANAGERMessages::getMSelectionEMessage()); ?>" data-for="publish" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/publish-icon.png" /><?php echo __('Active', 'learn-manager') ?></a>
            <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_attr(JSLEARNMANAGERMessages::getMSelectionEMessage()); ?>" data-for="unpublish" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/unbuplish.png" /><?php echo __('Disable', 'learn-manager') ?></a>
            <a class="jslm_js-bulk-link button multioperation" title="<?php echo esc_attr($desc); ?>" message="<?php echo esc_attr(JSLEARNMANAGERMessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo esc_attr($message); ?>" data-for="remove" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete-icon.png" /><?php echo __('Delete Data', 'learn-manager') ?></a>
            <a class="jslm_js-bulk-link button multioperation" title="<?php echo esc_attr($desc2); ?>" message="<?php echo esc_attr(JSLEARNMANAGERMessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo esc_attr($message); ?>" data-for="enforceremove" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/fe-forced-delete.png" /><?php echo __('Delete WP User', 'learn-manager') ?></a>
            <?php
            $image1 = JSLEARNMANAGER_PLUGIN_URL."includes/images/up.png";
            $image2 = JSLEARNMANAGER_PLUGIN_URL."includes/images/down.png";
            if (jslearnmanager::$_data['sortby'] == 1) {
                $image = $image1;
            } else {
                $image = $image2;
            }
            ?>
            <span class="jslm_sort">
                <span class="jslm_sort-text"><?php echo __('Sort by', 'learn-manager'); ?>:</span>
                <span class="jslm_sort-field"><?php echo wp_kses(JSLEARNMANAGERformfield::select('jslm_sorting', $categoryarray, jslearnmanager::$_data['combosort'], '', array('class' => 'jslm_inputbox', 'onchange' => 'changeCombo();')), JSLEARNMANAGER_ALLOWED_TAGS); ?></span>
                <a class="jslm_sort-icon" href="#" data-image1="<?php echo esc_attr($image1); ?>" data-image2="<?php echo esc_attr($image2); ?>" data-sortby="<?php echo esc_attr(jslearnmanager::$_data['sortby']); ?>"><img id="jslm_sortingimage" src="<?php echo esc_url($image); ?>" /></a>
            </span>
            <script type="text/javascript">
                function changeSortBy() {
                    var value = jQuery('a.jslm_sort-icon').attr('data-sortby');
                    var img = '';
                    if (value == 1) {
                        value = 2;
                        img = jQuery('a.jslm_sort-icon').attr('data-image2');
                    } else {
                        img = jQuery('a.jslm_sort-icon').attr('data-image1');
                        value = 1;
                    }
                    jQuery("img#jslm_sortingimage").attr('src', img);
                    jQuery('input#sortby').val(value);
                    jQuery('form#jslearnmanagerform').submit();
                }
                jQuery('a.jslm_sort-icon').click(function (e) {
                    e.preventDefault();
                    changeSortBy();
                });
                function changeCombo() {
                    jQuery("input#sorton").val(jQuery('select#jslm_sorting').val());
                    changeSortBy();
                }
            </script>
        </div>
        <form class="jslm_js-filter-form" name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_user&jslmslay=users"); ?>">
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('searchname', jslearnmanager::$_data['filter']['searchname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('email', jslearnmanager::$_data['filter']['email'], array('class' => 'inputbox', 'placeholder' => __('Email', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('status', JSLEARNMANAGERincluder::getJSModel('common')->getstatus(), is_numeric(jslearnmanager::$_data['filter']['status']) ? jslearnmanager::$_data['filter']['status'] : '', __('Select status', 'learn-manager'), array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Search', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::button('reset', __('Reset', 'learn-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>

            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sortby', jslearnmanager::$_data['sortby']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sorton', jslearnmanager::$_data['sorton']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </form>
        <?php
        if (!empty(jslearnmanager::$_data[0])) {
            ?>
            <form id="jslearnmanager-list-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_user"); ?>">
                   <?php
                        $pagenum = JSLEARNMANAGERrequest::getVar('pagenum', 'get', 1);
                        $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                        for ($i = 0, $n = count(jslearnmanager::$_data[0]); $i < $n; $i++) {
                            $row = jslearnmanager::$_data[0][$i];
                            if($row->stuimage){
                                $logo = $row->stuimage;
                            }elseif($row->insimage){
                                $logo = $row->insimage;
                            }
                            else{
                                $logo = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
                            }
                            $type = "";
                            if($row->role == "Student"){
                                $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($row->id);
                                $link = admin_url('admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid=' . $studentid);
                                $type = $row->role;
                                $classtype = "student";
                            }elseif($row->role == "Instructor"){
                                $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($row->id);
                                $link = admin_url('admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid=' . $instructorid);
                                $type = $row->role;
                                $classtype = "instructor";
                            }
                            ?>
                            <div id="jslearnmanager-user-listing-wrap">
                                <div id="jslearnmanager-user-listing-limage">
                                   <img src="<?php echo esc_url($logo); ?>" />
                                </div>
                                <div id="jslearnmanager-user-listing-top-wrap">
                                    <div id="jslearnmanager-user-listing-heading-wrap">
                                        <div id="jslearnmanager-user-listing-heading">
                                            <span id="jslearnmanager-user-listing-heading-name">
                                                <input type="checkbox" class="jslearnmanager-cb" id="jslearnmanager-cb" name="jslearnmanager-cb[]" value="<?php echo esc_attr($row->id); ?>" />
                                                <a href="<?php echo esc_url($link); ?>"><?php echo esc_html(__($row->username,'learn-manager')); ?></a>
                                                <span class="role-<?php echo esc_attr($classtype); ?>">
                                                    <?php  echo esc_html($type); ?>
                                                </span>
                                            </span>
                                            <?php
                                            if($row->status == 1){
                                                $status[0] = 'jslm_active';
                                                $status[1] = __('Active', 'learn-manager');
                                            }else{
                                                $status[0] = 'jslm_disabled';
                                                $status[1] = __('Disable', 'learn-manager');
                                            } ?>
                                            <span class="jslearnmanager-user-listing-right">
                                                <span class="jslearnmanager-user-listing-heading-status <?php echo esc_attr($status[0]); ?>"><?php echo esc_html($status[1]); ?></span>
                                                <span class="jslearnmanager-user-listing-smedia-wrap">
                                                    <?php  if($row->facebook_url !=''){
                                                            if(!strstr($row->facebook_url, 'http')){
                                                                $row->facebook_url = 'http://'.$row->facebook_url;
                                                            }
                                                    ?>
                                                        <a class="jslearnmanager-user-listing-smedia-links" href="<?php echo esc_url($row->facebook_url); ?>" target="_blank"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/facebook.png" title="<?php echo __('Facebook','learn-manager');?>" /></a>
                                                    <?php   } ?>
                                                    <?php if($row->twitter !=''){
                                                            if(!strstr($row->twitter, 'http')) {
                                                                $row->twitter = 'http://'.$row->twitter;
                                                            }
                                                    ?>
                                                        <a class="jslearnmanager-user-listing-smedia-links" href="<?php echo esc_url($row->twitter); ?>" target="_blank"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/twitter.png" title="<?php echo __('Twitter','learn-manager');?>" /></a>
                                                    <?php } ?>
                                                    <?php if($row->linkedin !='') {
                                                            if(!strstr($row->linkedin, 'http')){
                                                                $row->linkedin = 'http://'.$row->linkedin;
                                                            }
                                                    ?>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="jslearnmanager-user-listing-data-wrap">
                                        <div class="jslearnmanager-user-listing-left">
                                            <span class="jslearnmanager-user-listing-data">
                                                <span class="jslearnmanager-user-listing-data-title"><?php echo __('Email','learn-manager'); ?>:</span>
                                                <span class="jslearnmanager-user-listing-data-value"><?php echo esc_html($row->email); ?></span>
                                            </span>
                                            <span class="jslearnmanager-user-listing-data">
                                                <span class="jslearnmanager-user-listing-data-title"><?php echo __('Location','learn-manager'); ?>:</span>
                                                <span class="jslearnmanager-user-listing-data-value"><?php echo esc_html($row->location); ?></span>
                                            </span>
                                           <!--  <?php
                                            $customfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsData(3,0,1);
                                            foreach($customfields AS $field){
                                                $array = JSLEARNMANAGERincluder::getObjectClass('customfields')->showCustomFields($field, 2,$row->params);?>
                                                <span class="jslearnmanager-user-listing-data">
                                                    <span class="jslearnmanager-user-listing-data-title">
                                                        <?php echo __($array[0],'learn-manager')." : "; ?>
                                                    </span>
                                                    <span class="jslearnmanager-user-listing-data-value">
                                                        <?php echo esc_html($array[1]);?>
                                                    </span>
                                                </span>
                                            <?php
                                                }
                                            ?> -->
                                        </div>
                                    </div>
                                </div>
                                  <div id="item-actions" class="item-actions js-row no-margin">
                                    <div class="item-text-block js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding user-layout-id">
                                        <span class="heading"><?php echo __('ID', 'learn-manager') . ': '; ?></span><span class="item-action-text"><?php echo esc_html($row->id); ?></span>
                                    </div>
                                    <?php if($type != ""){ ?>
                                        <div class="item-values js-col-lg-7 js-col-md-7 js-col-xs-12 no-padding">
                                            <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_user&action=jslmstask&task=enforceremove&jslearnmanager-cb[]='.$row->id),'delete-users')); ?>" onclick="return confirm('<?php echo __('This will delete every thing about this record','learn-manager').'. '.__('Are you sure to delete','learn-manager').'?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/force-delete.png" /><?php echo __('Enforce Delete', 'learn-manager') ?></a>
                                            <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jslm_user&action=jslmstask&task=remove&jslearnmanager-cb[]='.$row->id),'delete-users')); ?>" onclick="return confirm('<?php echo __('Are you sure to delete', 'learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete-icon.png" /><?php echo __('Delete data', 'learn-manager') ?></a>
                                            <a class="js-action-link button" href="<?php echo ($row->status == 1) ? esc_url(admin_url('admin.php?page=jslm_user&action=jslmstask&task=unpublish&jslearnmanager-cb[]='.$row->id.'&jslearnmanagerpageid='.JSLEARNMANAGERrequest::getVar('pagenum'))) : esc_url(admin_url('admin.php?page=jslm_user&action=jslmstask&task=publish&jslearnmanager-cb[]='.$row->id.'&jslearnmanagerpageid='.JSLEARNMANAGERrequest::getVar('pagenum'))); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/disable-icon.png" /><?php echo ($row->status == 1) ? __('Disable', 'learn-manager') : __('Enable', 'learn-manager'); ?></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php
                        } ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('task', ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('uid', isset($row->uid) ? $row->uid :''  ), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', isset($row->id) ? $row->id :''  ), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('_wpnonce', wp_create_nonce('delete-users')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            </form>
            <?php
            if (jslearnmanager::$_data[1]) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . jslearnmanager::$_data[1] . '</div></div>';
            }
        } else {
            $msg = __('No record found','learn-manager');
            $link[] = array(
                        'link' => 'admin.php?page=jslm_user&jslmslay=formprofile',
                        'text' => __('Add New','learn-manager') .' '. __('Profile','learn-manager')
                    );
            echo JSLEARNMANAGERlayout::getNoRecordFound();
        }
       ?>
      </div>
    </div>
</div>
