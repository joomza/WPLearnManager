<?php
if (!defined('ABSPATH'))die('Restricted Access');
wp_register_style( 'jslms-jquery', 'bower_components/jquery/dist/jquery.js');
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
$curdate = date_i18n('Y-m-d');
$categoryarray = array(
  (object) array('id' => 1, 'text' => __('Category', 'learn-manager')),
  (object) array('id' => 3, 'text' => __('Created', 'learn-manager')),
  (object) array('id' => 5, 'text' => __('Title', 'learn-manager')));
  if(in_array('paidcourse', jslearnmanager::$_active_addons))
    $categoryarray[] = (object) array('id' => 2, 'text' => __('Price', 'learn-manager'));

if(jslearnmanager::$_config['date_format'] == 'd-m-Y' ){
  $date_format_string = 'd/F/Y';
}elseif(jslearnmanager::$_config['date_format'] == 'm/d/Y'){
  $date_format_string = 'F/d/Y';
}elseif(jslearnmanager::$_config['date_format'] == 'Y-m-d'){
  $date_format_string = 'Y/F/d';
}
?>
<script type="text/javascript">
  function resetFrom() {
    jQuery("select#category").val('');
    jQuery("input#title").val('');
    jQuery("select#access_type").val('');
    jQuery("select#isgfcombo").val('');
    jQuery("input#pricestart").val('');
    jQuery("input#priceend").val('');
    jQuery("select#skilllevel").val('');
    jQuery("select#language").val('');
    jQuery("select#status").val('');
    jQuery("form#jslearnmanagerform").submit();
  }

  function highlight(id) {
    if (jQuery("div#course_" + id + " div span input:checked").length > 0) {
      showBorder(id);
    }else {
      hideBorder(id);
    }
  }

  function showBorder(id) {
    jQuery("div#course_" + id).addClass('blue');
  }

  function hideBorder(id) {
    jQuery("div#course_" + id).removeClass('blue');
  }

  function highlightAll() {
    if(jQuery("span.selector input").is(':checked') == false) {
      jQuery("span.selector").css('display', 'none');
      jQuery("div.course-container div#item-data").css('border', '1px solid #dedede');
      jQuery("div.course-container div#item-actions").css('border', '1px solid #dedede');
      jQuery("div.course-container div#item-actions").css('border-top', 'none');
    }
    if(jQuery("span.selector input").is(':checked') == true) {
      jQuery("span.selector").css('display', 'block');
      jQuery("div.course-container div#item-data").css('border', '1px solid #428BCA');
      jQuery("div.course-container div#item-data").css('border-bottom', '1px solid #dedede');
      jQuery("div.course-container div#item-actions").css('border', '1px solid #428BCA');
      jQuery("div.course-container div#item-actions").css('border-top', 'none');
    }
  }

  jQuery(document).ready(function(){
    jQuery("span#jslm_showhidefilter").click(function (e) {
      e.preventDefault();
      jQuery(".jslm_default-hidden").toggle();
      var height = jQuery(this).height();
      var imgheight = jQuery(this).find('img').height();
      var currenttop = (height - imgheight) / 2;
      jQuery(this).find('img').css('top', currenttop);
    });
    jQuery("div.course-container").each(function () {
      jQuery("div#" + this.id).hover(function () {
          jQuery("div#" + this.id + " div span.selector").show();
      }, function () {
          if (jQuery("div#" + this.id + " div span.selector input:checked").length > 0) {
              jQuery("div#" + this.id + " div span.selector").show();
          } else {
              jQuery("div#" + this.id + " div span.selector").hide();
          }
      });
    });
  });
</script>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php  JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
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
                        <li><?php echo __(' Courses','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __(' Courses', 'learn-manager'); ?></span>
            <a class="jslmsadmin-add-link" href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=formcourse'); ?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/add_icon.png" /><?php echo __('Add New','learn-manager') .' '. __('Course' , 'learn-manager'); ?></a>
            <a target="blank" href="https://www.youtube.com/watch?v=cPH5zKlhNpo&ab_channel=WPLearnManager" class="jslmsadmin-add-link black-bg" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                <img alt="arrow" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/play-btn.png">
                <?php echo __('Watch Video', 'learn-manager'); ?>
            </a>
        </div>            
   <div id="jslms-data-wrp">
        <div id="jslm_ajax_pleasewait" style="display:none;"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/pleasewait.gif"/></div>
        <div class="jslm_page-actions jslm-row jslm_no-margin">
            <label class="jslm_js-bulk-link button" onclick="return highlightAll();" for="jslm_selectall"><input type="checkbox" name="selectall" id="jslm_selectall" value=""><?php echo __('Select All', 'learn-manager') ?></label>
            <a class="jslm_js-bulk-link button multioperation" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo __('Are you sure to delete', 'learn-manager') .' ?'; ?>" data-for="removeall" href="#"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'learn-manager') ?></a>
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
        <form class="jslm_js-filter-form" name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_course"); ?>">
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('title', jslearnmanager::$_data['filter']['title'], array('class' => 'inputbox', 'placeholder' => __('Title', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('category', JSLEARNMANAGERincluder::getJSModel('category')->getCategoryForCombobox(), jslearnmanager::$_data['filter']['category'], __('Select category', 'learn-manager'), array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('access_type', JSLEARNMANAGERincluder::getJSModel('accesstype')->getaccesstypeForCombo(), jslearnmanager::$_data['filter']['access_type'],__('Select access type', 'learn-manager'), array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('isgfcombo', JSLEARNMANAGERincluder::getJSModel('common')->getShowAllCombo(), jslearnmanager::$_data['filter']['isgfcombo'], '', array('class' => 'inputbox jslm_default-hidden' )), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php do_action("jslm_paidcourse_price_search_field_admin_listing"); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('skilllevel', JSLEARNMANAGERincluder::getJSModel('courselevel')->getLevelForCombo(), jslearnmanager::$_data['filter']['skilllevel'], __('Select Skill Level', 'learn-manager'), array('class' => 'inputbox jslm_default-hidden')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('language', JSLEARNMANAGERincluder::getJSModel('language')->getlanguageForCombo(), jslearnmanager::$_data['filter']['language'], __('Select Course Language', 'learn-manager'), array('class' => 'inputbox jslm_default-hidden')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('status', JSLEARNMANAGERincluder::getJSModel('common')->getStatus(), jslearnmanager::$_data['filter']['status'], __('Select Status', 'learn-manager'), array('class' => 'inputbox jslm_default-hidden')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <div class="filterbutton">
              <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
              <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Search', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
              <?php echo wp_kses(JSLEARNMANAGERformfield::button('reset', __('Reset', 'learn-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            </div>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sortby', jslearnmanager::$_data['sortby']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sorton', jslearnmanager::$_data['sorton']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <span id="jslm_showhidefilter"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/filter-down.png"/></span>
        </form>
        <hr class="listing-hr" />
    <?php
    if (!empty(jslearnmanager::$_data[0])) { ?>
        <form id="jslearnmanager-list-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_course"); ?>">
            <?php foreach (jslearnmanager::$_data[0] AS $course) {  ?>
                <div id="course<?php echo esc_attr($course->course_id);?>" class="course-container js-col-lg-12 js-col-md-12 no-padding">
                    <div id="item-data" class="item-data js-row no-margin">
                        <span id="selector_<?php echo esc_attr($course->course_id); ?>" class="selector"><input type="checkbox" class="jslearnmanager-cb" onclick="javascript:highlight(<?php echo esc_js($course->course_id); ?>);" id="jslearnmanager-cb" name="jslearnmanager-cb[]" value="<?php echo esc_attr($course->course_id); ?>" /></span>
                        <div class="item-icon">
                            <?php if($course->file !='' && $course->file != null){
                                $imageadd = $course->file;
                            }else{
                                $imageadd = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
                            }?>
                            <a href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id); ?>">
                                <img src="<?php echo esc_url($imageadd); ?>" />
                            </a>
                        </div>
                        <div class="item-details">
                          <div class="item-title js-col-lg-6 js-col-md-6 js-col-xs-10 no-padding">
                            <?php do_action("jslm_featuredcourse_feature_icon_admin_course_queue",$course,$curdate); ?>
                            <span class="value"><a href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id); ?>"><?php echo esc_html(__(ucwords($course->title))); ?></a></span>
                          </div>
                          <div class="item-title jslm_date js-col-lg-4 js-col-md-4 js-col-xs-7 no-padding text-align-right">
                              <span class="value"><span class="heading padding-right"><?php echo __("Created:","learn-manager"); ?></span><span class="item-action-text"><?php echo esc_html(date_i18n($date_format_string, strtotime($course->created_at))); ?></span></span>
                          </div>
                          <div class="jslm_price js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding">
                            <?php
                            $data = apply_filters("jslm_paidcourse_get_paid_course_price_admin","Free",$course); ?>
                            <span class="value right price"><?php echo esc_html(__($data,"learn-manager")); ?></span>

                          </div>
                          <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                              <span class="heading"><?php echo __("Instructor:","learn-manager"); ?></span>
                                <span class="value">
                                  <?php
                                    if ($course->instructor_name == ""){
                                      echo __('Admin','learn-manager');
                                    }else {
                                      echo esc_html(__(ucwords($course->instructor_name),"learn-manager"));
                                    }
                                  ?>
                                </span>
                          </div>
                          <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                              <span class="heading"><?php echo __("Category:","learn-manager"); ?></span><span class="value"><?php echo esc_html(__($course->category,'learn-manager')); ?></span>
                          </div>
                          <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                              <span class="heading"><?php echo __("Lectures:","learn-manager"); ?></span><span class="value"><?php echo esc_html(__($course->total_lessons,'learn-manager')); ?></span>
                          </div>
                          <?php if(isset($course->price) && $course->price > 0){
                            do_action("jslm_paymentplan_admin_paymentplan_label",$course);
                          }
                          $print = true;
                          $startdate = date_i18n('Y-m-d',strtotime($course->start_date));
                          $enddate = date_i18n('Y-m-d',strtotime($course->expire_date));
                          if($course->course_status == 1){
                            $publishstatus = __('Publish','learn-manager');
                            $publishstyle = 'background:#00A859;color:#ffffff;border:unset;';
                          }else{
                            $publishstatus = __('Un-publish','learn-manager');
                            $publishstyle = 'background:#FEA702;color:#ffffff;border:unset;';
                          }

                          ?>
                          <span class="bigupper-coursestatus" style="padding:4px 8px;<?php echo esc_attr($publishstyle); ?>"><?php echo esc_html($publishstatus); ?></span>
                        </div>
                    </div>
                    <div id="for_ajax_only_<?php echo esc_attr($course->course_id); ?>">
                      <div id="item-actions" class="item-actions js-row no-margin">
                          <div class="item-text-block js-col-lg-4 js-col-md-4 js-col-xs-12 no-padding">
                              <span class="heading"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/students.png" title="<?php echo __("Enrolled Students","learn-manager"); ?>"></span><span class="item-action-text"><?php echo esc_html($course->total_students); ?></span>
                            <?php if(in_array("coursereview",jslearnmanager::$_active_addons)){ ?>
                              <span class="heading"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/default.png" title="<?php echo __("Reviews","learn-manager"); ?>"></span><span class="item-action-text"><?php echo esc_html(round($course->reviews,1)); ?></span>
                            <?php } ?>
                          </div>
                          <div class="item-values js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding">
                            <a class="js-action-link button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=jslm_course&action=jslmstask&task=remove&jslearnmanagerid='.$course->course_id),'delete-course'); ?>&call_from=1" onclick="return confirm('<?php echo __('Are you sure to delete','learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete-icon.png" alt="del" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>"/>&nbsp;&nbsp;<?php echo __('Delete', 'learn-manager'); ?></a>
                            <a class="js-action-link button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=jslm_course&action=jslmstask&task=courseenforcedelete&courseid='.$course->course_id),'delete-course'); ?>&call_from=1" onclick="return confirmdelete('<?php echo __('This will delete every thing about this record','learn-manager').'. '.__('Are you sure to delete','learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/fe-forced-delete.png" /><?php echo __('Force Delete', 'learn-manager') ?></a>
                            <?php do_action("jslm_featuredcourse_admin_course_list_features_button",$course,$curdate); ?>
                            <?php if(isset($course->price) && $course->price > 0){ ?>
                              <a class="js-action-link button" href="<?php echo admin_url('admin.php?page=jslm_earning&jslmslay=courseearning&jslearnmanagerid='.$course->course_id); ?>" ><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/popup-coin-icon.png" /><?php echo __('Course Earning', 'learn-manager') ?></a>
                            <?php } ?>
                            <a class="js-action-link button" href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=formcourse&jslearnmanagerid='.$course->course_id);?>"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/edit.png" /><?php echo __('Edit Course', 'learn-manager') ?></a>
                            <a class="js-action-link button" href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id);?>#curriculum"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/add-lecture.png" /><?php echo __('Add Lecture', 'learn-manager') ?></a>
                          </div>
                      </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'jslmstask'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('task', ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('callfrom', '1'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('_wpnonce', wp_create_nonce('delete-course')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
        </form>
        <?php
        if (jslearnmanager::$_data[1]) {
          echo '<div class="tablenav"><div class="tablenav-pages">' . jslearnmanager::$_data[1] . '</div></div>';
        }
    } else {
        $msg = __('No record found','learn-manager');
        $link[] = array(
                    'link' => 'admin.php?page=jslm_course&jslmslay=formcourse',
                    'text' => __('Add New','learn-manager') .'&nbsp;'. __('Course','learn-manager')
                );
        echo JSLEARNMANAGERlayout::getNoRecordFound($msg,$link);
    }
    ?>
    </div>
    </div>
    </div>
