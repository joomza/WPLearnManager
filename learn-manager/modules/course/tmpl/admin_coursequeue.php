<?php
if (!defined('ABSPATH'))die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
$curdate = date_i18n('Y-m-d');
if(jslearnmanager::$_config['date_format'] == 'd-m-Y' ){
  $date_format_string = 'd-F-Y';
}elseif(jslearnmanager::$_config['date_format'] == 'm/d/Y'){
  $date_format_string = 'F-d-Y';
}elseif(jslearnmanager::$_config['date_format'] == 'Y-m-d'){
  $date_format_string = 'Y-F-d';
}
?>
<script type="text/javascript">
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
});

function resetFrom() {
  jQuery("select#category").val('');
  jQuery("input#title").val('');
  jQuery("select#access_type").val('');
  jQuery("select#isgfcombo").val('');
  jQuery("input#pricestart").val('');
  jQuery("input#priceend").val('');
  jQuery("select#skilllevel").val('');
  jQuery("input#language").val('');
  jQuery("select#status").val('');
  jQuery("form#jslmlearnmanagerform").submit();
}
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
                        <li><?php echo __('Approval Queue','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Approval Queue', 'learn-manager'); ?></span>
        </div>            
    <div id="jslms-data-wrp">
        <div id="jslm_ajax_pleasewait" style="display:none;"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/pleasewait.gif"/></div>
        <div class="jslm_page-actions jslm-row jslm_no-margin">
          <?php
          $image1 = JSLEARNMANAGER_PLUGIN_URL."includes/images/up.png";
          $image2 = JSLEARNMANAGER_PLUGIN_URL."includes/images/down.png";
          if (jslearnmanager::$_data['sortby'] == 1) {
              $image = $image1;
          } else {
              $image = $image2;
          }
          ?>
        </div>
        <form class="jslm_js-filter-form" name="jslmlearnmanagerform" id="jslmlearnmanagerform" method="post" action="<?php echo admin_url("admin.php?page=jslm_course&jslmslay=coursequeue"); ?>">
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('title', jslearnmanager::$_data['filter']['title'], array('class' => 'inputbox', 'placeholder' => __('Title', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('category', JSLEARNMANAGERincluder::getJSModel('category')->getCategoryForCombobox(), jslearnmanager::$_data['filter']['category'], __('Select category', 'learn-manager'), array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('access_type', JSLEARNMANAGERincluder::getJSModel('accesstype')->getaccesstypeForCombo(), jslearnmanager::$_data['filter']['access_type'],__('Select access type', 'learn-manager'), array('class' => 'inputbox')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('isgfcombo', JSLEARNMANAGERincluder::getJSModel('common')->getShowAllCombo(), jslearnmanager::$_data['filter']['isgfcombo'], '', array('class' => 'inputbox jslm_default-hidden' )), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php do_action("jslm_paidcourse_price_search_field_admin_listing"); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('skilllevel', JSLEARNMANAGERincluder::getJSModel('common')->getCourseLevel(), jslearnmanager::$_data['filter']['skilllevel'], __('Select Skill Level', 'learn-manager'), array('class' => 'inputbox jslm_default-hidden')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::text('language', jslearnmanager::$_data['filter']['language'], array('class' => 'inputbox jslm_default-hidden', 'placeholder' => __(' Language', 'learn-manager'))), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::select('status', JSLEARNMANAGERincluder::getJSModel('common')->getStatus(), jslearnmanager::$_data['filter']['status'], __('Select Status', 'learn-manager'), array('class' => 'inputbox jslm_default-hidden')), JSLEARNMANAGER_ALLOWED_TAGS); ?>

            <div class="filterbutton">
                <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('btnsubmit', __('Search', 'learn-manager'), array('class' => 'button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::button('reset', __('Reset', 'learn-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            </div>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sortby', jslearnmanager::$_data['sortby']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sorton', jslearnmanager::$_data['sorton']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <span id="jslm_showhidefilter"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/filter-down.png"/></span>
        </form>
        <hr class="listing-hr" />
    <?php 
    if (!empty(jslearnmanager::$_data[0])) { ?>
        <form id="jslearnmanager-list-form" method="post" action="<?php echo admin_url("admin.php?page=jslm_course&jslmslay=coursequeue"); ?>">
            <?php
            foreach (jslearnmanager::$_data[0] AS $course) {
                ?>
                <div id="course<?php echo esc_attr($course->course_id);?>" class="course-container js-col-lg-12 js-col-md-12 no-padding">
                    <div id="item-data" class="item-data js-row no-margin">

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
                          <div class="item-title js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding">
                            <?php do_action("jslm_featuredcourse_feature_icon_admin_course_queue",$course,$curdate); ?>
                            <span class="value"><a href="<?php echo admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id); ?>"><?php echo esc_html($course->title); ?></a></span>
                          </div>
                            <div class="item-title item-values js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding text-align-right">
                            <?php
                              if($course->course_status == 1){
                                $publishstatus = __('Publish','learn-manager');
                                $publishstyle = 'background:#00A859;color:#ffffff;border:unset;';
                              }else{
                                $publishstatus = __('Not publish','learn-manager');
                                $publishstyle = 'background:#FEA702;color:#ffffff;border:unset;';
                              }
                              ?>
                                <span class="value"><span class="item-action-text" style="padding:4px 8px;<?php echo esc_attr($publishstyle); ?>"><?php echo esc_html($publishstatus); ?></span></span>
                            </div>
                            <div class="jslm_price js-col-lg-1 js-col-md-1 js-col-xs-12 no-padding">
                              <?php $data = apply_filters("jslm_paidcourse_get_paid_course_price_admin",false,$course);
                              if(!$data){ ?>
                                <span class="value right price"><?php echo esc_html(__("Free","learn-manager")); ?></span>
                              <?php } ?>

                            </div>
                            <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                              <span class="heading"><?php echo __("Instructor:","learn-manager"); ?></span><span class="value"><?php echo esc_html(__($course->instructor_name)); ?></span>
                            </div>
                            <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                              <span class="heading"><?php echo __("Created:","learn-manager"); ?></span><span class="value"><?php echo esc_html(date_i18n($date_format_string, strtotime($course->created_at))); ?></span>
                            </div>
                            <div class="item-values jslm-padding js-col-lg-6 js-col-md-6 js-col-xs-12">
                              <span class="heading"><?php echo __("Category:","learn-manager"); ?></span><span class="value"><?php echo esc_html(__($course->category,'learn-manager')); ?></span>
                            </div>
                            <?php do_action("jslm_paymentplan_admin_paymentplan_label",$course); ?>



                          <?php
                          $print = true;
                          $startdate = date_i18n('Y-m-d',strtotime($course->start_date));
                          $enddate = date_i18n('Y-m-d',strtotime($course->expire_date));
                          if($course->isapprove == 1){
                            $approvestatus = __('Approved','learn-manager');
                            $approvestyle = 'background:#00A859;color:#ffffff;border:unset;';
                          }elseif($course->isapprove == 0){
                            $approvestatus = __('Pending','learn-manager');
                            $approvestyle = 'background:#FEA702;color:#ffffff;border:unset;';
                          }else{
                            $approvestatus = __('Rejected','learn-manager');
                            $approvestyle = 'background:#FF0000;color:#ffffff;border:unset;';
                          }

                          ?>
                          <span class="bigupper-coursestatus" style="padding:4px 8px;<?php echo esc_attr($approvestyle); ?>"><?php echo esc_html($approvestatus); ?></span>
                        </div>
                    </div>
                  <div id="for_ajax_only_<?php echo esc_attr($course->course_id); ?>">
                    <div id="item-actions" class="item-actions js-row no-margin">
                      <div class="item-text-block js-col-lg-4 js-col-md-4 js-col-xs-12 no-padding">
                        <span class="heading"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/students.png" title="<?php echo __("Enrolled Students","learn-manager"); ?>"></span><span class="item-action-text"><?php echo esc_html($course->total_students); ?></span>
                        <span class="heading"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/lesson.png" title="<?php echo __("Lectures","learn-manager"); ?>"></span><span class="item-action-text"><?php echo esc_html($course->total_lessons); ?></span>
                      </div>
                      <div class="item-values js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding">
                        <a class="js-action-link button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=jslm_course&action=jslmstask&task=remove&jslearnmanagerid='.$course->course_id),'delete-course'); ?>&call_from=2" onclick="return confirm('<?php echo __('Are you sure to delete','learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/delete-icon.png" alt="del" message="<?php echo esc_attr(JSLEARNMANAGERmessages::getMSelectionEMessage()); ?>"/>&nbsp;&nbsp;<?php echo __('Delete', 'learn-manager'); ?></a>
                        <a class="js-action-link button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=jslm_course&action=jslmstask&task=courseenforcedelete&courseid='.$course->course_id),'delete-course'); ?>&call_from=2" onclick="return confirmdelete('<?php echo __('This will delete every thing about this record','learn-manager').'. '.__('Are you sure to delete','learn-manager').' ?'; ?>');"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/fe-forced-delete.png" /><?php echo __('Force Delete', 'learn-manager') ?></a>
                        <?php do_action("jslm_featuredcourse_course_queue_features_button",$course,$curdate);
                        if($course->isapprove == 0){ ?>
                          <a class="js-action-link button" href="<?php echo admin_url('admin.php?page=jslm_course&action=jslmstask&task=reject&jslearnmanagerid='.$course->course_id).'&jslearnmanagerpageid='.JSLEARNMANAGERrequest::getVar('pagenum'); ?>" onclick="return confirm('<?php echo __('Are you sure to reject this course','learn-manager').' ?'; ?>');" ><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/unpublish.png" /><?php echo __('Reject', 'learn-manager') ?></a>
                        <?php }
                        if($course->isapprove == 0 || $course->isapprove == 2 || $course->isapprove == -1){ ?>
                          <a class="js-action-link button" href="<?php echo admin_url('admin.php?page=jslm_course&action=jslmstask&task=approve&jslearnmanagerid='.$course->course_id).'&jslearnmanagerpageid='.JSLEARNMANAGERrequest::getVar('pagenum'); ?>" onclick="return confirm('<?php echo __('Are you sure to approve this course','learn-manager').' ?'; ?>');" ><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/approve-h.png" /><?php echo __('Approve', 'learn-manager') ?></a>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
            }
            ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('action', 'course_remove'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('task', ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('form_request', 'jslearnmanager'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('callfrom', 1), JSLEARNMANAGER_ALLOWED_TAGS); ?>
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
    } ?>
   </div>
  </div>
</div>
