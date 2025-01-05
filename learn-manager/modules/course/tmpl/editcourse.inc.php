<script>
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

  var sectionid = '';
 	var sectionname = '';
 	function insertSection() {
    jQuery("#notsaveerror").remove();
    jQuery("#emptyerror").remove();
    var section_name=document.getElementById("jslm_sname").value;
    var course_id=document.getElementById("jslm_cid").value;
    var section_id = document.getElementById("jslm_sid").value;
  	if(section_name != "" && course_id != ""){
     	jQuery("#loading").show();
        jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "storeSection",name:section_name,course_id:course_id,section_id:section_id,redirect_id:2} , function (data,response) {
        	if(data){
              document.getElementById("jslm_sname").value = "";
              document.getElementById("jslm_sid").value = "";
              if(document.getElementById("section-message")){
                 jQuery('#section-message').remove('');
              }

              var json_section = jQuery.parseJSON(data);
              var decodeHTML = function (html) {
                var txt = document.createElement('textarea');
                txt.innerHTML = html;
                return txt.value;
              };

              json_section['html'] = decodeHTML(json_section['html']);
              json_section['newcontent'] = decodeHTML(json_section['newcontent']);
              if(json_section['msg']){
                 jQuery('span.jslm_search_field').append().before(json_section['html']);
                 jQuery("#loading").hide();
                 return false;
              }
              if(json_section["flag"] == 1){
                if(document.getElementById('no_data_section')){
                 	jQuery('#no_data_section').remove();
              	}
                jQuery('span.jslm_search_field').append().before(json_section['html']);
              	jQuery("#section_panel").append(json_section["newcontent"]);
           	}else{
                jQuery('span.jslm_search_field').append().before(json_section['html']);
                jQuery('#wrapper_'+section_id).html('');
              	jQuery('#wrapper_'+section_id).append(json_section["newcontent"]);
              }
              jQuery("#loading").hide();
        	}else {
              jQuery( "#jslm_sname" ).append().after("<div id='emptyerror' class='validation' style='color:red;margin-bottom: 20px;'>Error While Saving Section!</div>");
  	         jQuery("#loading").hide();
           }
     	});
  	}else{
      jQuery( "#jslm_sname" ).append().after("<div id='emptyerror' class='validation' style='color:red;margin-bottom: 20px;'>Section name required!</div>");
    }
  };

 	function getSectionFormData(id,name){
    sectionid = id;
  	document.getElementById('jslm_sid').value = sectionid;
  	sectionname = name;
  	document.getElementById('jslm_sname').value = sectionname;
  }
  jQuery(document).ready(function(){
    if(jQuery('.jslm_middle_potion').height() > 90){
      jQuery('.jslm_middle_potion').append('<div class="jslm_append_border"></div>');
    }
    jQuery('.jslm_panel_group').on('hidden.bs.collapse', courseedittoggleicon);
    jQuery('.jslm_panel_group').on('shown.bs.collapse', courseedittoggleicon);
    // jQuery('.jslm_panel_group .jslm_panel_single_group:first-child .jslm-panel-heading').addClass('active');
    // jQuery('.jslm_panel_group .jslm_panel_single_group:first-child .jslm-panel-heading').find(".more-less").toggleClass("fa-plus fa-minus");
    jQuery('.jslm_panel_group .jslm_panel_single_group .panel-collapse').addClass('in');

    setTimeout(function(){jQuery('#autohidealert').fadeOut();}, 10000);

    jQuery.validate();

    jQuery(document).on('click','a[href="#myModalFullscreen"],button[data-modal="#myModalFullscreen"]',function(event){
      jQuery("#section-message").remove("");
      event.preventDefault();
      jQuery('#myModalFullscreen').modal('show');
    });
    <?php if(in_array("paymentplan",jslearnmanager::$_active_addons)){ ?>
      jQuery(document).on('click','a[data-target=paymentplanModal]',function(event){
        event.preventDefault();
        jQuery('#paymentplanModal').modal('show');
      });
    <?php } ?>
    jQuery('[data-dismiss=modal]').on('click', function (e) {
      document.getElementById('jslm_sid').value = "";
      document.getElementById('jslm_sname').value = "";
    });
    function courseedittoggleicon(e) {
      jQuery(e.target)
        .prev('.jslm-panel-heading')
        .find(".more-less")
        .toggleClass('fa-chevron-up fa-chevron-down');
    }
  });

  jQuery(document).ready(function(){
    var hash = window.location.hash;
    hash && jQuery('ul.nav a[href*="\\' + hash + '"]').tab('show');
    jQuery("#section-modal").on('click',function(){
       document.getElementById("jslm_sname").value = "";
       document.getElementById("jslm_sid").value = "";
    });

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
      changeIconTabs();
    });
    changeIconTabs();
  });

  function load_more_data(page, total_pages) {
    jQuery("#loadmore").html("");
    jQuery("#loadmore").html("<a class='jslm_show_more'><i class='fa fa-spinner fa-spin'></i>Show More</a>");
    jQuery("#total_pages, #pagei").remove();
    var cid = <?php echo JSLEARNMANAGERrequest::getVar('jslearnmanagerid'); ?>;
    jQuery.get(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "getAllEnrolledStudentinCourse",pagei:page,cid:cid, call_from:1} , function (data,response) {
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

  jQuery( document ).ready(function() {
    jQuery(".lms_lm_header_title h1").text(jQuery(".lms-course-detail-top-heading .lms-course-heading").text());
    jQuery(".lms-course-detail-top-heading .lms-course-heading").css("display","none");
    jQuery(".lms-course-img-wrp").css("padding-top","20px");
  });

</script>
