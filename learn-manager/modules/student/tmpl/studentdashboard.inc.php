<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<?php 
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script('jquery-masonry ');
  wp_enqueue_script("corechart" , $protocol."www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}");
  wp_enqueue_script('charts-loder' , $protocol.'www.gstatic.com/charts/loader.js');
?>
<script>
var ajaxurl = "<?php echo admin_url("admin-ajax.php"); ?>";

jQuery(document).ready(function(e){
  jQuery("#mycourses_jslm_view_all_mycourse").css("display","block");
  jQuery('ul#myTabdashboard li a').click(function(){
    jQuery(this).tab('show');
    var dashboardid = jQuery(this).attr('data-toggle');
    if(dashboardid == "#shortlisted"){
      jQuery("#jslm_view_all_shortlisted").css("display","block");
      jQuery("#jslm_view_all_mycourse").css("display","none");
    }else{
      jQuery("#jslm_view_all_shortlisted").css("display","none");
      jQuery("#jslm_view_all_mycourse").css("display","block");
    }
    jQuery('#myTabdashboarddata .fade').removeClass('active');
    jQuery(dashboardid).addClass('active in');
  });
  
  jQuery('ul#myrewardquiztab li a').click(function(){
    jQuery(this).tab('show');
    var dashboardid = jQuery(this).attr('data-toggle');
    jQuery('#myrewardquiztabdata .fade').removeClass('active');
    jQuery(dashboardid).addClass('active in');
  });
  alertify.logPosition("bottom right");
  // changeIconTabs();   
  //jQuery("#loading").show();
	jQuery('.jslm_progress_bar_percent2').animate({width:'50%'}, 1000);
	jQuery('.jslm_progress_bar_percent3').animate({width:'66%'}, 1000);
});

/*masonry For Box Style*/
  jQuery(document).ready(function(){
       
        jQuery('#lms-course-list-wrp-mc').masonry({
      // options
      itemSelector: '.grid-item',
      columnWidth: '.grid-item'
    });
   });





jQuery(window).load(function(){
  jQuery("#loading").hide();
});
</script>	
<script>
    document.storeCourseShortlist = function (cid,call_from) {
    	var courseid = cid;
      var classname = jQuery('#span_sh_'+cid).find('i').prop('className');
      jQuery('#span_sh_'+cid).find('i').removeClass(classname);
      jQuery('#span_sh_'+cid).find('i').addClass("fa fa-spinner fa-spin");
      jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "saveShortlistCourse",courseid: courseid, call_from:call_from} , function (data) {
        if (data) {
          var shortlistdata = (data);
       		jQuery('#span_sh_'+cid).html("");
       		jQuery('#span_sh_'+cid).append(shortlistdata);
          alertify.delay(5000).success("Course has been successfully shortlist");
       	}else{
          alertify.delay(5000).error("Course has not been shortlist");
       		jQuery('#span_sh_'+cid).find('i').addClass(classname);
       	}
      });
    };
    
    // Remove shortlist code
    document.removeShortlist = function(rid,cid,call_from){
      var classname = jQuery('#span_sh_'+cid).find('i').prop('className');
      jQuery('#span_sh_'+cid).find('i').removeClass(classname);
      jQuery('#span_sh_'+cid).find('i').addClass("fa fa-spinner fa-spin");
      var courseid = cid;
      var rowid = rid;
      jQuery.post(ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "deleteShortListedCourse",cid: courseid, call_from: call_from, rid:rowid} , function (data) {
        if (data) {
          var shortlistdata = (data);
          jQuery('#span_sh_'+cid).html("");
          jQuery('#span_sh_'+cid).append(shortlistdata);
          alertify.delay(5000).success("Course has been successfully remove from shortlist");
        }else{
          jQuery('#span_sh_'+cid).find('i').addClass(classname);
          alertify.delay(5000).error("Course has not been remove from shortlist");
        }
      });
    }


    jQuery(function(){
      var hash = window.location.hash;
      hash && jQuery('ul.nav a[href*="\\' + hash + '"]').tab('show');
      jQuery('.nav-tabs li a').click(function (e) {
        // jQuery("#loading").show();
        jQuery(this).tab('show');
        changeIconTabs();
        var scrollmem = jQuery('body').scrollTop() || jQuery('html').scrollTop();
        window.location.hash = this.hash;
        jQuery('html,body').scrollTop(scrollmem);
      });
    });
    
    jQuery(document).ready(function() {
       
      jQuery('.jslm_select_full_width').selectable();
      jQuery('a.jslm_sort_img').click(function (e) {
          e.preventDefault();
          changeSortBy();
      });

    });

    function changeSortBy() {
      var value = jQuery('a.jslm_sort_img').attr('data-sortby');
      var img = '';
        if (value == 1) {
            value = 2;
            img = jQuery('a.jslm_sort_img').attr('data-image2');
        } else {
            img = jQuery('a.jslm_sort_img').attr('data-image1');
            value = 1;
        }
        jQuery("img#jslm_sortingimage").attr('src', img);
        jQuery('input#sortby').val(value);
        jQuery('form#jslearnmanagermycourseform').submit();
    }

    function changeCombo() {
        jQuery("input#sorton").val(jQuery('select#jslm_sorting').val());
        changeSortBy();
    }

    document.changeListStyle= function (val){
      jQuery.post(ajaxurl, {action: "jslearnmanager_ajax", jslmsmod: "student", task: "setListStyleSession", styleid: val}, function(data){
         if (data){
            location.reload();
         }
      });
    }
    function resetMyCourseListFrom() {
        jQuery("select#category").val('');
        jQuery("select#coursetitle").val('');
    }

</script>
<script type="text/javascript">
    function changeIconTabs(){
      jQuery(document).ready(function(){
        var tabValue = jQuery(".nav-tabs .active > a > img").attr("id");
        if(tabValue == "dashboardicon"){
          jQuery("#mycourseicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/my-course-black.png'; ?>");
          jQuery("#shortlisticon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/shortlist-black.png'; ?>");
          jQuery("#payouticon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/earning-black.png'; ?>");
          jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/dashboard-white.png'; ?>");
        }else if(tabValue == "mycourseicon"){
          jQuery("#shortlisticon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/shortlist-black.png'; ?>");
          jQuery("#dashboardicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/dashboard-black.png'; ?>");
          jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/my-course-white.png'; ?>");
          jQuery("#payouticon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/earning-black.png'; ?>");
        }else if(tabValue == "shortlisticon"){
          jQuery("#dashboardicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/dashboard-black.png'; ?>");
          jQuery("#mycourseicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/my-course-black.png'; ?>");
          jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/shortlist-white.png'; ?>");
          jQuery("#payouticon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/earning-black.png'; ?>");
        }else if(tabValue == "payouticon"){
          jQuery("#dashboardicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/dashboard-black.png'; ?>");
          jQuery("#mycourseicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/my-course-black.png'; ?>");
          jQuery("#shortlisticon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/shortlist-black.png'; ?>");
          jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/stu_dash_icon/earning-white.png'; ?>");
        }
      });
    }

    // For Payouts Graph
  google.charts.load('current', {packages: ['corechart']});
  google.setOnLoadCallback(purchasehistoryGraphDetail);
  function purchasehistoryGraphDetail() { // For Earning Tab
    var data = google.visualization.arrayToDataTable([
       <?php
          if(isset(jslearnmanager::$_data['history_graph_detail'])){
            echo jslearnmanager::$_data['history_graph_detail']['title'] . ',';
            echo jslearnmanager::$_data['history_graph_detail']['data'];
          }
       ?>
    ]);
    var view = new google.visualization.DataView(data);
    var options = {
       curveType: 'function',
       height:'100%',
       width:'80%',
       legend: { position: 'top', maxLines: 3 },
       pointSize: 4,
       isStacked: true,
       focusTarget: 'category',
       chartArea: {left:60,right:50,top:35,bottom:60},
       series: {
       0: { color: '#FFC300' },
       1: { color: '#0A9955' },
       2: { color: '#FF0000' },
    }
    };
    if(document.getElementById("history_graph_detail")){
      document.getElementById("history_graph_detail").innerhtml = "";
      var chart = new google.visualization.LineChart(document.getElementById("history_graph_detail"));
      chart.draw(view, options);
    }  
  }

  function payoutForm() {
      jQuery("select#payoutfrom").val('');
      jQuery("select#payoutto").val('');
      location.reload();
   }

  jQuery(function() {
    jQuery( "#payoutfrom" ).datepicker({
      dateFormat: 'yy-mm-dd'
    });
    jQuery( "#payoutto" ).datepicker({
      dateFormat: 'yy-mm-dd'
    });
  });
</script>
