<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$categoryarray = array(
    (object) array('id' => 1, 'text' => __('ID', 'learn-manager')),
    (object) array('id' => 2, 'text' => __('User Name', 'learn-manager')),
    (object) array('id' => 3, 'text' => __('Reference For', 'learn-manager')),
    (object) array('id' => 4, 'text' => __('Created', 'learn-manager'))
);
?>
<script>
    jQuery(document).ready(function () {
        jQuery("div#jslm_full_background,img#jslm_popup_cross").click(function () {
            HidePopup();
        });
    });

    function ShowPopup() {
        jQuery("div#jslm_full_background").show();
        jQuery("div#jslm_popup_main").fadeIn(300);
    }

    function HidePopup() {
        jQuery("div#jslm_full_background").hide();
        jQuery("div#jslm_popup_main").fadeOut(300);
    }
    function submitfrom() {
        jQuery("form#jslm_filter_form").submit();

    }

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
        jQuery('form#jslm_filter_form').submit();
    }

    function buttonClick() {
        changeSortBy();
    }
    function changeCombo() {
        jQuery("input#sorton").val(jQuery('select#jslm_sorting').val());
        changeSortBy();
    }
</script>
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
                        <li><?php echo __(' Activity Log','js-support-ticket'); ?></li>
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
                        <?php echo esc_html(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('versioncode') ); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="jslm_dashboard">
            <span class="jslm_heading-dashboard"><?php echo __('Activity Log', 'learn-manager'); ?></span>
        </div>            
        <div id="jslms-data-wrp">
            <div id="jslm_full_background" style="display:none;"></div>
            <div id="jslm_popup_main" style="display:none;">
                <span class="jslm_popup-top">
                    <span id="jslm_popup_title" >
                        <?php echo __('Settings', 'learn-manager'); ?>
                    </span>
                    <img id="jslm_popup_cross" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/popup-close.png">
                </span>
                <div id="jslm_checkbox-popup-wrapper">
                    <form id="jslm_filter_form" method="post" action="?page=jslm_activitylog&jslmslay=activitylogs">
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[course]', array('1' => __('Courses', 'learn-manager')), isset(jslearnmanager::$_data['filter']['course']) ? jslearnmanager::$_data['filter']['course'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[section]', array('1' => __('Sections', 'learn-manager')), isset(jslearnmanager::$_data['filter']['section']) ? jslearnmanager::$_data['filter']['section'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[lecture]', array('1' => __('Lectures', 'learn-manager')), isset(jslearnmanager::$_data['filter']['lecture']) ? jslearnmanager::$_data['filter']['lecture'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[file]', array('1' => __('Lecture Files', 'learn-manager')), isset(jslearnmanager::$_data['filter']['file']) ? jslearnmanager::$_data['filter']['file'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[questions]', array('1' => __('Quiz Questions', 'learn-manager')), isset(jslearnmanager::$_data['filter']['questions']) ? jslearnmanager::$_data['filter']['questions'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <?php if(in_array('coursereview', jslearnmanager::$_active_addons)){ ?>
                            <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[reviews]', array('1' => __('Reviews', 'learn-manager')), isset(jslearnmanager::$_data['filter']['reviews']) ? jslearnmanager::$_data['filter']['reviews'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <?php } ?>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[messages]', array('1' => __('Messages', 'learn-manager')), isset(jslearnmanager::$_data['filter']['messages']) ? jslearnmanager::$_data['filter']['messages'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[paymenthistory]', array('1' => __('Purchase Course', 'learn-manager')), isset(jslearnmanager::$_data['filter']['paymenthistory']) ? jslearnmanager::$_data['filter']['paymenthistory'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[enrollment]', array('1' => __('Enrollment', 'learn-manager')), isset(jslearnmanager::$_data['filter']['enrollment']) ? jslearnmanager::$_data['filter']['enrollment'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[shortlist]', array('1' => __('Shortlist', 'learn-manager')), isset(jslearnmanager::$_data['filter']['shortlist']) ? jslearnmanager::$_data['filter']['shortlist'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[config]', array('1' => __('Config', 'learn-manager')), isset(jslearnmanager::$_data['filter']['config']) ? jslearnmanager::$_data['filter']['config'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[payouts]', array('1' => __('Payouts', 'learn-manager')), isset(jslearnmanager::$_data['filter']['payouts']) ? jslearnmanager::$_data['filter']['payouts'] : 0, array('class' => 'checkbox')),JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[paymentplan]', array('1' => __('Payment Plan', 'learn-manager')), isset(jslearnmanager::$_data['filter']['paymentplan']) ? jslearnmanager::$_data['filter']['paymentplan'] : 0, array('class' => 'checkbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[country]', array('1' => __('Country', 'learn-manager')), isset(jslearnmanager::$_data['filter']['country']) ? jslearnmanager::$_data['filter']['country'] : 0, array('class' => 'checkbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <div class="jslm_checkbox-filter"><?php echo wp_kses(JSLEARNMANAGERformfield::checkbox('filter[emailtemplate]', array('1' => __('Email Template', 'learn-manager')), isset(jslearnmanager::$_data['filter']['emailtemplate']) ? jslearnmanager::$_data['filter']['emailtemplate'] : 0, array('class' => 'checkbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('searchsubmit', 1 ), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sortby', jslearnmanager::$_data['sortby']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sorton', jslearnmanager::$_data['sorton']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                        <span class="jslm_submit-button-popup" onclick="submitfrom()"><?php echo __('Submit', 'learn-manager'); ?></span>
                    </form>
                </div>
            </div>

                <div class="jslm_page-actions js-row jslm_no-margin">
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
                        <span class="jslm_sort-field"><?php echo wp_kses(JSLEARNMANAGERformfield::select('jslm_sorting', $categoryarray, jslearnmanager::$_data['combosort'], '', array('class' => 'inputbox', 'onchange' => 'changeCombo();')), JSLEARNMANAGER_ALLOWED_TAGS); ?></span>
                        <a class="jslm_sort-icon" href="#" data-image1="<?php echo esc_attr($image1); ?>" data-image2="<?php echo esc_attr($image2); ?>" data-sortby="<?php echo esc_attr(jslearnmanager::$_data['sortby']); ?>" onclick="buttonClick();"><img id="jslm_sortingimage" src="<?php echo esc_url($image); ?>" /></a>
                    </span>
                    <span onclick="ShowPopup()" id="jslm_filter-activity-log">
                        <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/settings.png">
                        <?php echo __('Settings', 'learn-manager'); ?>
                    </span>
                </div>
        <?php if (!empty(jslearnmanager::$_data[0])) { ?>
                <table id="jslm_js-table">
                    <thead>
                        <tr>
                            <th ><?php echo __('ID', 'learn-manager'); ?></th>
                            <th class="jslm_left-row"><?php echo __('User Name', 'learn-manager'); ?></th>
                            <th class="jslm_left-row"><?php echo __('Description', 'learn-manager'); ?></th>
                            <th ><?php echo __('Reference For', 'learn-manager'); ?></th>
                            <th ><?php echo __('Created', 'learn-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (jslearnmanager::$_data[0] AS $data) { ?>
                            <tr >
                                <td><?php echo esc_html($data->id); ?></td>
                                <td class="jslm_left-row"><?php echo esc_html( __($data->display_name, 'learn-manager') ); ?></td>
                                <td class="jslm_left-row"><?php echo wp_kses(__($data->description, 'learn-manager'), JSLEARNMANAGER_ALLOWED_TAGS); ?></td>
                                <td><?php echo esc_html(ucwords(__($data->referencefor , 'learn-manager'))); ?></td>
                                <td><?php echo esc_html($data->created); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

            </table>
            <?php
            if (jslearnmanager::$_data[1]) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . jslearnmanager::$_data[1]  . '</div></div>';
            }
        } else {
            $msg = __('No record found','learn-manager');
            echo wp_kses(JSLEARNMANAGERlayout::getNoRecordFound($msg), JSLEARNMANAGER_ALLOWED_TAGS);
        }
        ?>
      </div>
    </div>
</div>
