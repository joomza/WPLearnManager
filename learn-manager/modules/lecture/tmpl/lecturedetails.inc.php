<script type="text/javascript">

    jQuery(document ).ready(function() {
        jQuery(".container").css("width","100%");
        jQuery(".container").css("padding","0");
    });

  // For accordion
  function toggleIcon(e) {
    jQuery(e.target)
      .prev('.panel-heading')
      .find(".more-less")
      .toggleClass('fa-chevron-down fa-chevron-up');
  }

    jQuery(document).ready(function(){
      jQuery('.panel-group').on('hidden.bs.collapse', toggleIcon);
      jQuery('.panel-group').on('shown.bs.collapse', toggleIcon);
    });


    jQuery(document).ready(function(){
        var learn_manager_theme = "<?php echo jslearnmanager::$_learn_manager_theme; ?>";
		var jslm_lid = '<?php echo JSLEARNMANAGERrequest::getVar('jslearnmanagerid'); ?>';
        var res = jslm_lid.split("_");
        if(res[0] === "retake"){
            jslm_lid = res[1];
        }
        var jslm_sid = <?php
        $jslm_id = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
        $splitch = explode("_",$jslm_id);
        if($splitch[0] == 'retake'){
            $jslm_id = $splitch[1];
        }
        echo JSLEARNMANAGERincluder::getJSModel('course')->getSectionByLectureId($jslm_id); ?>;
	    jQuery(".jslm-panel-group .jslm_panel_style .panel-collapse").addClass('fade');
		jQuery('#panelModal_jslm'+jslm_sid).addClass("collapse in").removeClass('fade');
        jQuery('#panelModal_jslm'+jslm_sid).attr("aria-expanded","true");
        jQuery('a[href="#panelModal_jslm'+jslm_sid+'"]').addClass("active");
		jQuery('#panellecture_jslm'+jslm_lid).addClass("jslm_border_color_primary");
		jQuery("h5.jslm_section_heading a").on('click',function(e){
            jQuery(this).attr('href',function(i,id){
                if(jQuery(id).hasClass('fade')){
                    jQuery(id).removeClass('fade');
                }else{
                    jQuery(id).addClass('fade');
                }

            });
        });
		jQuery('.nav-tabs a').click(function (e) {
            jQuery(this).tab('show');
            if(learn_manager_theme == false){
                changeIconTabs();
            }
        });
        var toggle = 'left';
        jQuery(".venobox").venobox({
            infinigall: true,
            framewidth: 850,
            titleattr: 'data-title',
        });

        jQuery("#closesectionpanel,#opensectionpanel").on('click',function(){
            jQuery('#jslm_section_list').toggle("slide");
            if(toggle == 'left'){
                toggle = 'right';
                jQuery('#jslm_right_panel').css('width','100%');
                jQuery('div.jslm-iframe').css('width','calc(100% / 3 - 10px)');
                jQuery('.closebtn').hide();
                jQuery('.arrowright').show();
            }else{
                toggle = 'left';
                jQuery('#jslm_right_panel').css('width','66.667%');
                jQuery('div.jslm-iframe').css('width','calc(100% / 2 - 10px)');
                jQuery('.closebtn').show();
                jQuery('.arrowright').hide();
            }

        });

	});

	function saveLectureProgress(id){
		// var id = <?php //echo JSLEARNMANAGERrequest::getVar('jslearnmanagerid');	?>;
        if(id != ""){
			jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "lecture",task: "storeLectureCompletionProgress",id:id} , function (data,response) {});
      	}
	}

    jQuery(window).ready(function () {
        var url = window.location.hash;
        if (url != "") {
            jQuery(".tab-pane").removeClass("active").addClass("fade");
            // jQuery(url).addClass("active in").removeClass("fade");
            jQuery('a[href="'+ url +'"]').tab('show');
        }else{
            jQuery('.jslm_li_border:first').addClass('active');
        }
        
    });
</script>
<script>

	// Quiz Panel Setting
    jQuery(document).ready(function(){
        jQuery('.cont').addClass('hide');
            count=jQuery('.jslm_question_top_content').length;
            jQuery('#qname'+1).removeClass('hide');
            jQuery(document).on('click','.next',function(){
            element=jQuery(this).attr('id');
            last = parseInt(element.substr(element.length - 1));
            nex=last+1;
            jQuery('#qname'+last).addClass('hide');
            jQuery('#qname'+nex).removeClass('hide');
        });
        jQuery(document).on('click','.previous',function(){
            element=jQuery(this).attr('id');
            last = parseInt(element.substr(element.length - 1));
            pre=last-1;
            jQuery('#qname'+last).addClass('hide');
            jQuery('#qname'+pre).removeClass('hide');
        });
        var learn_manager_theme = "<?php echo jslearnmanager::$_learn_manager_theme; ?>";
        if(learn_manager_theme == false){
            changeIconTabs();
        }
    });

    function changeIconTabs(){
        jQuery(document).ready(function(){
          var tabValue = jQuery(".nav-tabs .active > a > img").attr("id");
          if(tabValue == "description"){
            jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/description-white.png'; ?>");
            jQuery("#fileid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/curriculum-grey.png'; ?>");
            jQuery("#lecturevideosid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/members-grey.png'; ?>");
            jQuery("#lecturequizid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/review-grey.png'; ?>");
          }else if(tabValue == "fileid"){
            jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/curriculum-white.png'; ?>");
            jQuery("#lecturevideosid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/members-grey.png'; ?>");
            jQuery("#lecturequizid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/review-grey.png'; ?>");
            jQuery("#description").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/description-grey.png'; ?>");
          }else if(tabValue == "lecturevideosid"){
            jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/members-white.png'; ?>");
            jQuery("#lecturequizid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/review-grey.png'; ?>");
            jQuery("#fileid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/curriculum-grey.png'; ?>");
            jQuery("#description").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/description-grey.png'; ?>");
          }else if(tabValue == "lecturequizid"){
            jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/review-white.png'; ?>");
            jQuery("#lecturevideosid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/members-grey.png'; ?>");
            jQuery("#fileid").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/curriculum-grey.png'; ?>");
            jQuery("#description").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/course/description-grey.png'; ?>");
          }
        });
    }
</script>
