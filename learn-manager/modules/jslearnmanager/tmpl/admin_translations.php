<?php
   if(!defined('ABSPATH'))
    die('Restricted Access');
?>
    <?php $msgkey = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getMessagekey();
    JSLEARNMANAGERmessages::getLayoutMessage($msgkey); ?>
<style>
   #jstran_loading {display: none;}
</style>
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
                        <li><?php echo __(' Translations','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __(' Translations', 'learn-manager'); ?></span>
        </div>            
     <div id="jslms-data-wrp">
        <div id="jslearnmanager-content">
            <div id="black_wrapper_translation"></div>
            <div id="jslm_jstran_loading">
                <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/spinning-wheel.gif" />
            </div>

            <div id="jslm_js-language-wrapper">
                <div class="jslm_jstopheading"><?php echo __('Get Learn Manager','learn-manager') . ' ' . __('Translations') ;?></div>
                <div id="jslm_gettranslation" class="jslm_gettranslation"><img style="width:18px; height:auto;" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/download-icon.png" /><?php echo __('Get','learn-manager') . ' ' . __('Translations');?></div>
                <div id="jslm_js_ddl">
                    <span class="jslm_title"><?php echo __('Select Translation','learn-manager');?>:</span>
                    <span class="jslm_combo" id="jslm_js_combo"></span>
                    <span class="jslm_button" id="jslm_jsdownloadbutton"><img style="width:14px; height:auto;" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/download-icon.png" /><?php echo __('Download','learn-manager');?></span>
                    <div id="jslm_jscodeinputbox" class="jslm_js-some-disc"></div>
                    <div class="jslm_js-some-disc"><img style="width:18px; height:auto;" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/info-icon.png" /><?php echo __('When WordPress language change to ro, Learn Manager language will auto change to ro');?></div>
                </div>
                <div id="jslm_js-emessage-wrapper">
                    <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/c_error.png" />
                    <div id="jslm_jslang_em_text"></div>
                </div>
                <div id="jslm_js-emessage-wrapper_ok">
                    <img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/saved.png" />
                    <div id="jslm_jslang_em_text_ok"></div>
                </div>
            </div>
            <div id="jslm_js-lang-toserver">
                <div class="js-col-xs-12 js-col-md-8 col"><a class="jslm_anc jslm_one" href="https://www.transifex.com/joom-sky/learn-manager" target="_blank"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/translation-icon.png" /><?php echo __('Contribute In Translation','learn-manager');?></a></div>
                <div class="js-col-xs-12 js-col-md-4 col"><a class="jslm_anc jslm_two" href="http://www.joomsky.com/translations.html" target="_blank"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/manual-download.png" /><?php echo __('Manual Download','learn-manager');?></a></div>
            </div>
        </div>
       </div>
    </div>
</div>

<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
    jQuery(document).ready(function(){
        jQuery('#jslm_gettranslation').click(function(){
            jsShowLoading();
            jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'jslearnmanager', task: 'getListTranslations'}, function (data) {
                if (data) {

                    jsHideLoading();
                    data = (data);
                    if(data['error']){
                        jQuery('#jslm_js-emessage-wrapper div').html(data['error']);
                        jQuery('#jslm_js-emessage-wrapper').show();
                    }else{
                        jQuery('#jslm_js-emessage-wrapper').hide();
                        jQuery('#jslm_gettranslation').hide();
                        jQuery('div#jslm_js_ddl').show();
                        jQuery('span#jslm_js_combo').html(data['data']);
                    }
                }
            });
        });

        jQuery(document).on('change', 'select#translations' ,function() {
            var lang_name = jQuery( this ).val();
            if(lang_name != ''){
                jQuery('#jslm_js-emessage-wrapper_ok').hide();
                jsShowLoading();
                jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'jslearnmanager', task: 'validateandshowdownloadfilename',langname:lang_name}, function (data) {
                    if (data) {
                        jsHideLoading();
                        data = (data);
                        if(data['error']){
                            jQuery('#jslm_js-emessage-wrapper div').html(data['error']);
                            jQuery('#jslm_js-emessage-wrapper').show();
                            jQuery('#jslm_jscodeinputbox').slideUp('400' , 'swing' , function(){
                                jQuery('input#languagecode').val("");
                            });
                        }else{
                            jQuery('#jslm_js-emessage-wrapper').hide();
                            jQuery('#jslm_jscodeinputbox').html(data['path']+': '+data['input']);
                            jQuery('#jslm_jscodeinputbox').slideDown();
                        }
                    }
                });
            }
        });

        jQuery('#jslm_jsdownloadbutton').click(function(){
            jQuery('#jslm_js-emessage-wrapper_ok').hide();
            var lang_name = jQuery('#translations').val();
            var file_name = jQuery('#languagecode').val();
            if(lang_name != '' && file_name != ''){
                jsShowLoading();
                jQuery.post(ajaxurl, {action: 'jslearnmanager_ajax', jslmsmod: 'jslearnmanager', task: 'getlanguagetranslation',langname:lang_name , filename: file_name}, function (data) {
                    if (data) {
                        jsHideLoading();
                        data = (data);
                        if(data['error']){
                            jQuery('#jslm_js-emessage-wrapper div').html(data['error']);
                            jQuery('#jslm_js-emessage-wrapper').show();
                        }else{
                            jQuery('#jslm_js-emessage-wrapper').hide();
                            jQuery('#jslm_js-emessage-wrapper_ok div').html(data['data']);
                            jQuery('#jslm_js-emessage-wrapper_ok').slideDown();
                        }
                    }
                });
            }
        });
    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#jslm_jstran_loading').show();
    }

    function jsHideLoading(){
        jQuery('div#black_wrapper_translation').hide();
        jQuery('div#jslm_jstran_loading').hide();
    }
</script>
