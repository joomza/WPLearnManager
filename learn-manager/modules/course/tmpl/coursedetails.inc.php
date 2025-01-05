<script type="text/javascript">

  var acc = document.getElementsByClassName("accordion");
	var i;
	for (i = 0; i < acc.length; i++){
		acc[i].onclick = function() {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.maxHeight){
				panel.style.maxHeight = null;
			} else {
				panel.style.maxHeight = panel.scrollHeight + "px";
			}
		}
	}
  var ajaxurl = "<?php echo admin_url("admin-ajax.php"); ?>";
  var learn_manager_theme = "<?php echo jslearnmanager::$_learn_manager_theme; ?>";
	// Store Shortlist
	document.storeCourseShortlist = function (cid,call_from) {
    var classname = jQuery('#span_sh_'+cid).find('i').prop('className');
    jQuery('#span_sh_'+cid).find('i').removeClass(classname);
    jQuery('#span_sh_'+cid).find('i').addClass("fa fa-spinner fa-spin");
    var courseid = cid;
    jQuery.post(ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "saveShortlistCourse",courseid: courseid, call_from:call_from} , function (data) {
      if (data) {
     		var shortlistdata = (data);
     		jQuery('#span_sh_'+cid).html("");
     		jQuery('#span_sh_'+cid).append(shortlistdata);
        alertify.delay(5000).success("Course has been successfully shortlist");
     	}else{
     		jQuery('#span_sh_'+cid).find('i').addClass(classname);
        alertify.delay(5000).error("Course has not been shortlist");
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
  // For accordion
  function toggleIcon(e) {
    jQuery(e.target)
      .prev('.panel-heading')
      .find(".more-less")
      .toggleClass('fa-chevron-up fa-chevron-down');
  }

  function coursedetailtoggleicon(e) {
    jQuery(".jslm_panel_group .jslm-panel-heading").removeClass("active");
    jQuery(e.target).prev(".jslm-panel-heading").addClass("active");
    jQuery(e.target)
      .prev('.jslm-panel-heading')
      .find(".more-less")
      .toggleClass('fa-chevron-up fa-chevron-down');
  }




  jQuery(document).ready(function(){
    if(jQuery('.jslm_middle_potion').height() > 90){
      jQuery('.jslm_middle_potion').append('<div class="jslm_append_border"></div>');
    }
    jQuery('.panel-group').on('hidden.bs.collapse', toggleIcon);
    jQuery('.panel-group').on('shown.bs.collapse', toggleIcon);
    jQuery('.jslm_panel_group').on('hidden.bs.collapse', coursedetailtoggleicon);
    jQuery('.jslm_panel_group').on('shown.bs.collapse', coursedetailtoggleicon);
    jQuery('.jslm_panel_group .jslm_panel_single_group:first-child .jslm-panel-heading').addClass('active');
    jQuery('.jslm_panel_group .jslm_panel_single_group:first-child .jslm-panel-heading').find(".more-less").toggleClass("fa-chevron-up fa-chevron-down");
    jQuery('.jslm_panel_group .jslm_panel_single_group:first-child .panel-collapse').addClass('in');
    if(learn_manager_theme == false){
      changeIconTabs();
    }
  });

  jQuery(function () {
    var url = window.location.hash;
    if (url != "") {
      jQuery(".tab-pane").removeClass("active").addClass("fade");
      jQuery(url).addClass("active in").removeClass("fade");
      jQuery('a[href="'+ url +'"]').tab('show');
    }
  });

   jQuery(document).ready(function(){
     alertify.logPosition("bottom right");

    jQuery(document).on('click','.show_more',function(){
      var ID = jQuery(this).attr('id');
      jQuery('.show_more').hide();
      jQuery('.loding').show();
      $.ajax({
        type:'POST',
        url:'ajax_more.php',
        data:'id='+ID,
        success:function(html){
          jQuery('#show_more_main'+ID).remove();
          jQuery('.tutorial_list').append(html);
        }
      });
    });
  });

</script>
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("#loadmore").click(function(){
      var total_pages = parseInt(jQuery("#total_pages").val());
      var page = parseInt(jQuery("#pagei").val())+1;
      if(page <= total_pages) {
        load_more_data(page, total_pages);
      }
    });

    jQuery("#loadmore_reviews").click(function(){
      var total_pages = parseInt(jQuery("#reviews_pages").val());
      var page = parseInt(jQuery("#reviewpage").val())+1;
      if(page <= total_pages) {
        load_more_reviews(page, total_pages);
      }
    });

    jQuery('.nav-tabs a').click(function (e) {
      jQuery(this).tab('show');
      if(learn_manager_theme == false){
        changeIconTabs();
      }
    });

    jQuery(document).on('click','[data-modal="#selectpaymentmethod"]',function(event){
      jQuery('#selectpaymentmethod').modal('show');
    });
  });

  function load_more_data(page, total_pages) {
    jQuery("#loadmore").html("");
    jQuery("#loadmore").html("<a class='jslm_show_more'><i class='fa fa-spinner fa-spin'></i>Showing</a>");
    jQuery("#total_pages, #pagei").remove();
    var cid = <?php echo JSLEARNMANAGERrequest::getVar('jslearnmanagerid'); ?>;
    jQuery.get(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "getAllEnrolledStudentinCourse",pagei:page,cid:cid} , function (data,response) {
      if(data){
        jQuery("#loadmore").append().before((data));
        if(page == total_pages){
           jQuery("#loadmore").hide();
        }
        jQuery("#loadmore").html("");
        jQuery("#loadmore").html("<a class='jslm_show_more'>Show More</a>")
      }else{
        jQuery("#loadmore").html("");
        jQuery("#loadmore").html("<a class='jslm_show_more'>Data No Load</a>")
      }
    });
  }

  function load_more_reviews(page, total_pages) {
    jQuery("#loadmore_reviews").html("");
    jQuery("#loadmore_reviews").html("<a class='jslm_show_more'><i class='fa fa-spinner fa-spin'></i>Showing</a>");
    jQuery("#reviews_pages, #reviewpage").remove();
    var cid = <?php echo JSLEARNMANAGERrequest::getVar('jslearnmanagerid'); ?>;
    jQuery.get(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "coursereview",task: "getReviewOnCourse",pagei:page,cid:cid} , function (data,response) {
      if(data){
        jQuery("#loadmore_reviews").append().before((data));
        if(page == total_pages){
           jQuery("#loadmore_reviews").hide();
        }
        jQuery("#loadmore_reviews").html("");
        jQuery("#loadmore_reviews").html("<a class='jslm_show_more'>Show More Reviews</a>")
      }else{
        jQuery("#loadmore_reviews").html("");
        jQuery("#loadmore_reviews").html("<a class='jslm_show_more'>Data No Load</a>")
      }
    });
  }
  jQuery( document ).ready(function() {
    jQuery(".lms_lm_header_title h1").text(jQuery(".lms-course-detail-top-heading .lms-course-heading").text());
    jQuery(".lms-course-detail-top-heading .lms-course-heading").css("display","none");
    jQuery(".lms-course-img-wrp").css("padding-top","20px");
  });

  function changeIconTabs(){
    jQuery(document).ready(function(){
      var tabValue = jQuery(".nav-tabs .active > a > img").attr("id");
      if(tabValue == "description"){
        jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/description-white.png'; ?>");
        jQuery("#curriculumimgid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/curriculum-grey.png'; ?>");
        jQuery("#enrolledstudentsimg").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/members-grey.png'; ?>");
        jQuery("#reviewsimg").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/review-grey.png'; ?>");
      }else if(tabValue == "curriculumimgid"){
        jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/curriculum-white.png'; ?>");
        jQuery("#enrolledstudentsimg").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/members-grey.png'; ?>");
        jQuery("#reviewsimg").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/review-grey.png'; ?>");
        jQuery("#description").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/description-grey.png'; ?>");
      }else if(tabValue == "enrolledstudentsimg"){
        jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/members-white.png'; ?>");
        jQuery("#reviewsimg").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/review-grey.png'; ?>");
        jQuery("#curriculumimgid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/curriculum-grey.png'; ?>");
        jQuery("#description").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/description-grey.png'; ?>");
      }else if(tabValue == "reviewsimg"){
        jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/review-white.png'; ?>");
        jQuery("#enrolledstudentsimg").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/members-grey.png'; ?>");
        jQuery("#curriculumimgid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/curriculum-grey.png'; ?>");
        jQuery("#description").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/description-grey.png'; ?>");
      }
    });
  }
</script>
