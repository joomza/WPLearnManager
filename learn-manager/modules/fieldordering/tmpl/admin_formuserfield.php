<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.validate();
    });
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
                        <li><?php echo __('Add Fields','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Add Fields', 'learn-manager'); ?></span>
                    <?php
                    // $heading = isset(jslearnmanager::$_data[0]['fieldvalues']) ? __('Edit', 'learn-manager') : __('Add', 'learn-manager');
                    // echo esc_html($heading) . '&nbsp' . __('Fields', 'learn-manager');
                    ?>

        </div>            
    <div id="jslms-data-wrp">
        <?php
        $yesno = array(
            (object) array('id' => 1, 'text' => __('Yes', 'learn-manager')),
            (object) array('id' => 0, 'text' => __('No', 'learn-manager')));

        $fieldtypes = array(
            (object) array('id' => 'text', 'text' => __('Text Field', 'learn-manager')),
            (object) array('id' => 'checkbox', 'text' => __('Check Box', 'learn-manager')),
            (object) array('id' => 'date', 'text' => __('Date', 'learn-manager')),
            (object) array('id' => 'combo', 'text' => __('Drop Down', 'learn-manager')),
            (object) array('id' => 'email', 'text' => __('Email Address', 'learn-manager')),
            (object) array('id' => 'textarea', 'text' => __('Text Area', 'learn-manager')),
            (object) array('id' => 'radio', 'text' => __('Radio Button', 'learn-manager')),
            (object) array('id' => 'depandant_field', 'text' => __('Dependent Field', 'learn-manager')),
            (object) array('id' => 'file', 'text' => __('Upload File', 'learn-manager')),
            (object) array('id' => 'multiple', 'text' => __('Multi Select', 'learn-manager')));
        ?>
        <div class="jslearnmanager-form-wrap">
            <form id="jslearnmanager-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_fieldordering&task=saveuserfield"); ?>">
                <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Field Type', 'learn-manager'); ?><font class="jslm_required-notifier">*</font></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::select('userfieldtype', $fieldtypes, isset(jslearnmanager::$_data[0]['userfield']->userfieldtype) ? jslearnmanager::$_data[0]['userfield']->userfieldtype : 'text', __('Select type', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin" id="jslm_for-combo-wrapper" style="display:none;">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Select','learn-manager') .' '. __('Parent Field', 'learn-manager'); ?><font class="jslm_required-notifier">*</font></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding" id="jslm_for-combo"></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Field Title', 'learn-manager'); ?><font class="jslm_required-notifier">*</font></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text('fieldtitle', isset(jslearnmanager::$_data[0]['userfield']->fieldtitle) ? jslearnmanager::$_data[0]['userfield']->fieldtitle : '', array('class' => 'jslm_inputbox jslm_one', 'data-validation' => 'required')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin jslm_show-on-listing">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Show on listing', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::select('showonlisting', $yesno, isset(jslearnmanager::$_data[0]['userfield']->showonlisting) ? jslearnmanager::$_data[0]['userfield']->showonlisting : 0, __('Select show on listing', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('User Published', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::select('published', $yesno, isset(jslearnmanager::$_data[0]['userfield']->published) ? jslearnmanager::$_data[0]['userfield']->published : 1, __('Select published', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Visitor Published', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::select('isvisitorpublished', $yesno, isset(jslearnmanager::$_data[0]['userfield']->isvisitorpublished) ? jslearnmanager::$_data[0]['userfield']->isvisitorpublished : 1, __('Select visitor published', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_js-row no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('User Search', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::select('search_user', $yesno, isset(jslearnmanager::$_data[0]['userfield']->search_user) ? jslearnmanager::$_data[0]['userfield']->search_user : 1, __('Select user search', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Visitor Search', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::select('search_visitor', $yesno, isset(jslearnmanager::$_data[0]['userfield']->search_visitor) ? jslearnmanager::$_data[0]['userfield']->search_visitor : 1, __('Select visitor search', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Required', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::select('required', $yesno, isset(jslearnmanager::$_data[0]['userfield']->required) ? jslearnmanager::$_data[0]['userfield']->required : 0, __('Select required', 'learn-manager'), array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Field Size', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text('size', isset(jslearnmanager::$_data[0]['userfield']->size) ? jslearnmanager::$_data[0]['userfield']->size : '', array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div id="jslm_for-combo-options-head" >
                    <span class="js-admin-title"><?php echo __('Use The Table Below To Add New Values', 'learn-manager'); ?></span>
                </div>
                <div id="jslm_for-combo-options" >
                    <?php
                    $arraynames = '';
                    $comma = '';
                    if (isset(jslearnmanager::$_data[0]['userfieldparams']) && jslearnmanager::$_data[0]['userfield']->userfieldtype == 'depandant_field') {
                        foreach (jslearnmanager::$_data[0]['userfieldparams'] as $key => $val) {
                            $textvar = $key;
                            $textvar .='[]';
                            $arraynames .= $comma . "$key";
                            $comma = ',';
                            ?>
                            <div class="jslm_js-field-wrapper js-row no-margin">
                                <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding">
                                    <?php echo esc_html($key); ?>
                                </div>
                                <div class="js-col-lg-9 js-col-md-9 jslm_no-padding jslm_combo-options-fields" id="<?php echo esc_attr($key); ?>">
                                <?php
                                    if (!empty($val)) {
                                        foreach ($val as $each) {
                                            ?>
                                            <span class="jslm_input-field-wrapper">
                                                <input name="<?php echo esc_attr($textvar); ?>" id="<?php echo esc_attr($textvar); ?>" value="<?php echo esc_attr($each); ?>" class="jslm_inputbox jslm_one jslm_user-field" type="text">
                                                <img class="jslm_input-field-remove-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/remove.png">
                                            </span><?php
                                        }
                                    }
                                    ?>
                                    <input id="jslm_depandant-field-button" onclick="getNextField( &quot;<?php echo esc_js($key); ?>&quot;,this );" value="Add More" type="button">
                                </div>
                            </div>
                        <?php
                        }
                    }
                    ?>
                </div>

                <div id="jslm_divText" class="jslm_js-field-wrapper jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Max Length', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text('maxlength', isset(jslearnmanager::$_data[0]['userfield']->maxlength) ? jslearnmanager::$_data[0]['userfield']->maxlength : '', array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_divColsRows jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Columns', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text('cols', isset(jslearnmanager::$_data[0]['userfield']->cols) ? jslearnmanager::$_data[0]['userfield']->cols : '', array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div class="jslm_js-field-wrapper jslm_divColsRows jslm_js-row jslm_no-margin">
                    <div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __('Rows', 'learn-manager'); ?></div>
                    <div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 jslm_no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text('rows', isset(jslearnmanager::$_data[0]['userfield']->rows) ? jslearnmanager::$_data[0]['userfield']->rows : '', array('class' => 'jslm_inputbox jslm_one')), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
                </div>
                <div id="jslm_divValues" class="jslm_js-field-wrapper jslm_divColsRows jslm_js-row jslm_no-margin">
                    <span class="jslm_js-admin-title"><?php echo __('Use The Table Below To Add New Values', 'learn-manager'); ?></span>
                    <div class="jslm_page-actions jslm_js-row jslm_no-margin">
                        <div id="jslm_user-field-values" class="jslm_no-padding">
                            <?php
                            if (isset(jslearnmanager::$_data[0]['userfield']) && jslearnmanager::$_data[0]['userfield']->userfieldtype != 'depandant_field') {
                                if (isset(jslearnmanager::$_data[0]['userfieldparams']) && jslearnmanager::$_data[0]['userfieldparams'] != "") {
                                    foreach (jslearnmanager::$_data[0]['userfieldparams'] as $key => $val) {
                                        ?>
                                        <span class="jslm_input-field-wrapper">
                                            <?php echo wp_kses(JSLEARNMANAGERformfield::text('values[]', isset($val) ? $val : '', array('class' => 'jslm_inputbox jslm_one jslm_user-field')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                            <img class="jslm_input-field-remove-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/remove.png" />
                                        </span>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <span class="jslm_input-field-wrapper">
                                    <?php echo wp_kses(JSLEARNMANAGERformfield::text('values[]', isset($val) ? $val : '', array('class' => 'jslm_inputbox jslm_one jslm_user-field')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                                        <img class="jslm_input-field-remove-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/remove.png" />
                                    </span>
                                <?php
                                }
                            }
                            ?>
                            <a class="jslm_js-button-link jslm_button jslm_user-field-val-button" id="jslm_user-field-val-button" onclick="insertNewRow();"><?php echo __('Add Value', 'learn-manager') ?></a>
                        </div>
                    </div>
                </div>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', isset(jslearnmanager::$_data[0]['userfield']->id) ? jslearnmanager::$_data[0]['userfield']->id : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('isuserfield', 1), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo JSLEARNMANAGERformfield::hidden('fieldfor', jslearnmanager::$_data[0]['fieldfor']); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('ordering', isset(jslearnmanager::$_data[0]['userfield']->ordering) ? jslearnmanager::$_data[0]['userfield']->ordering : '' ), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'fieldordering_saveuserfield'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('field', isset(jslearnmanager::$_data[0]['userfield']->field) ? jslearnmanager::$_data[0]['userfield']->field : '' ), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('fieldname', isset(jslearnmanager::$_data[0]['userfield']->field) ? jslearnmanager::$_data[0]['userfield']->field : '' ), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('arraynames2', $arraynames), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
				<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('_wpnonce', wp_create_nonce('fieldordering-form')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <div class="jslm_js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
                    <div class="jslm_js-button-container">
                        <a id="jslm_form-cancel-button" href="<?php echo admin_url('admin.php?page=jslm_fieldordering&ff='.sanitize_key($_GET['ff'])); ?>" ><?php echo __('Cancel', 'learn-manager'); ?></a>
                        <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Save','learn-manager') .' '. __('User Field', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                    </div>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            var fieldstype = {
                text : '<?php echo __('Text Field','learn-manager'); ?>',
                checkbox : '<?php echo __('Check Box','learn-manager'); ?>',
                date : '<?php echo __('Date','learn-manager'); ?>',
                combo : '<?php echo __('Drop Down','learn-manager'); ?>',
                email : '<?php echo __('Email Address','learn-manager'); ?>',
                textarea : '<?php echo __('Text Area','learn-manager'); ?>',
                radio : '<?php echo __('Radio Button','learn-manager'); ?>',
                depandant_field : '<?php echo __('Dependent Field','learn-manager'); ?>',
                file: '<?php echo __('Upload File','learn-manager'); ?>',
                multiple : '<?php echo __('Multi Select','learn-manager'); ?>'
            };
            jQuery(document).ready(function () {
                toggleType(jQuery('#userfieldtype').val());
            });
            function disableAll() {
                jQuery("#jslm_divValues").slideUp();
                jQuery(".jslm_divColsRows").slideUp();
                jQuery("#jslm_divText").slideUp();
            }
            function toggleType(type) {
                disableAll();

                selType(type);
            }
            
            function selType(sType) {
                var elem;
                /*
                 text
                 checkbox
                 date
                 combo
                 email
                 textarea
                 radio
                 editor
                 depandant_field
                 multiple*/

                switch (sType) {
                    case 'file':
                        jQuery("#jslm_divText").slideUp();
                        jQuery("#jslm_divValues").slideUp();
                        jQuery(".jslm_divColsRows").slideUp();
                        jQuery("div.jslm_show-on-listing").slideUp();
                        jQuery("div#jslm_for-combo-wrapper").hide();
                        jQuery("div#jslm_for-combo-options").hide();
                        jQuery("div#jslm_for-combo-options-head").hide();
                        break;
                    case 'editor':
                        jQuery("#jslm_divText").slideUp();
                        jQuery("#jslm_divValues").slideUp();
                        jQuery(".jslm_divColsRows").slideUp();
                        jQuery("div.jslm_show-on-listing").slideUp();
                        jQuery("div#jslm_for-combo-wrapper").hide();
                        jQuery("div#jslm_for-combo-options").hide();
                        jQuery("div#jslm_for-combo-options-head").hide();
                        break;
                    case 'textarea':
                        jQuery("#jslm_divText").slideUp();
                        jQuery(".jslm_divColsRows").slideDown();
                        jQuery("#jslm_divValues").slideUp();
                        jQuery("div.jslm_show-on-listing").slideUp();
                        jQuery("div#jslm_for-combo-wrapper").hide();
                        jQuery("div#jslm_for-combo-options").hide();
                        jQuery("div#jslm_for-combo-options-head").hide();
                        break;
                    case 'email':
                    case 'password':
                    case 'text':
                        jQuery("#jslm_divText").slideDown();
                        jQuery("div#jslm_for-combo-wrapper").hide();
                        jQuery("div#jslm_for-combo-options").hide();
                        jQuery("div#jslm_for-combo-options-head").hide();
                        break;
                    case 'combo':
                    case 'multiple':
                        jQuery("#jslm_divValues").slideDown();
                        jQuery("div#jslm_for-combo-wrapper").hide();
                        jQuery("div#jslm_for-combo-options").hide();
                        jQuery("div#jslm_for-combo-options-head").hide();
                        break;
                    case 'depandant_field':
                        comboOfFields();
                        break;
                    case 'radio':
                    case 'checkbox':
                        jQuery("#jslm_divValues").slideDown();
                        jQuery("div#jslm_for-combo-wrapper").hide();
                        jQuery("div#jslm_for-combo-options").hide();
                        jQuery("div#jslm_for-combo-options-head").hide();
                        break;
                    case 'delimiter':
                    default:
                }
            }

            function comboOfFields() {
                var ff = jQuery("input#fieldfor").val();
                var pf = jQuery("input#fieldname").val();
                jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'fieldordering', task: 'getFieldsForComboByFieldFor', fieldfor: ff, parentfield: pf}, function (data) {
                    if (data) {
                        var d = data;
                        jQuery("div#jslm_for-combo").html(d);
                        jQuery("div#jslm_for-combo-wrapper").show();
                    }
                });
            }

            function getDataOfSelectedField() {
                var field = jQuery("select#parentfield").val();
                jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'fieldordering', task: 'getSectionToFillValues', pfield: field}, function (data) {
                    if (data) {
                        var d = data;
                        jQuery("div#jslm_for-combo-options-head").show();
                        jQuery("div#jslm_for-combo-options").html(d);
                        jQuery("div#jslm_for-combo-options").show();
                    }
                });
            }

            function getNextField(divid,object) {
                var textvar = divid + '[]';
                var fieldhtml = "<span class='jslm_input-field-wrapper' ><input type='text' name='" + textvar + "' class='jslm_inputbox jslm_one jslm_user-field'  /><img class='jslm_input-field-remove-img' src='<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/remove.png' /></span>";
                jQuery(object).before(fieldhtml);
            }

            function getObject(obj) {
                var strObj;
                if (document.all) {
                    strObj = document.all.item(obj);
                } else if (document.getElementById) {
                    strObj = document.getElementById(obj);
                }
                return strObj;
            }

            function insertNewRow() {
                var fieldhtml = '<span class="jslm_input-field-wrapper" ><input name="values[]" id="values[]" value="" class="jslm_inputbox jslm_one jslm_user-field" type="text" /><img class="jslm_input-field-remove-img" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/remove.png" /></span>';
                jQuery("#jslm_user-field-val-button").before(fieldhtml);
            }
            jQuery(document).ready(function () {
                jQuery("body").delegate("img.jslm_input-field-remove-img", "click", function () {
                    jQuery(this).parent().remove();
                });
            });

        </script>
      </div>
    </div>
</div>
