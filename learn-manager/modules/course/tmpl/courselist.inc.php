<script type="text/javascript">
    
  var ajaxurl = "<?php echo admin_url("admin-ajax.php"); ?>";
	document.storeCourseShortlist = function (cid,call_from) {
		var classname = jQuery('#span_sh_'+cid).find('i').prop('className');
    jQuery('#span_sh_'+cid).find('i').removeClass(classname);
    jQuery('#span_sh_'+cid).find('i').addClass("fa fa-spinner fa-spin");
    var courseid = cid;
    jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "saveShortlistCourse",courseid: courseid, call_from: call_from} , function (data) {
       if (data) {
          var shortlistdata = (data);
          jQuery('#span_sh_'+cid).html("");
          jQuery('#span_sh_'+cid).append(shortlistdata);
          alertify.delay(2000).success("Course has been successfully shortlist");

       	}else{
          alertify.delay(2000).error("Course has not been shortlist");
       		jQuery('#span_sh_'+cid).find('i').addClass(classname);
       	}
    });
  };

  document.removeShortlist = function(rid,cid,call_from){
  	var courseid = cid;
  	var rowid = rid;
    var classname = jQuery('#span_sh_'+cid).find('i').prop('className');
    jQuery('#span_sh_'+cid).find('i').removeClass(classname);
    jQuery('#span_sh_'+cid).find('i').addClass("fa fa-spinner fa-spin");
    jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "deleteShortListedCourse",cid: courseid, call_from: call_from, rid:rowid} , function (data) {
      if (data) {
        var shortlistdata = (data);
        jQuery('#span_sh_'+cid).html("");
        jQuery('#span_sh_'+cid).append(shortlistdata);
        alertify.delay(2000).success("Course has been successfully remove from shortlist");
      }else{
        alertify.delay(2000).error("Course has not been remove from shortlist");
     		jQuery('#span_sh_'+cid).find('i').addClass(classname);
     	}
    });
  }
    
  jQuery(document).ready(function() {
    alertify.logPosition("bottom right");
        
    jQuery('.jslm_select_full_width').selectable();

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
      jQuery('form#jslearnmanagerform').submit();
  }

    
  function changeSortByTheme() {
 		var value = jQuery('a.lms_sort_img').attr('data-sortby');
 		var img = '';
      if (value == 1) {
          value = 2;
          img = jQuery('a.lms_sort_img').attr('data-image2');
      } else {
          img = jQuery('a.lms_sort_img').attr('data-image1');
          value = 1;
      }
      jQuery("img#lms_sortingimage").attr('src', img);
      jQuery('input#sortby').val(value);
      jQuery('form#jslearnmanagerform').submit();
  }



  jQuery(document).ready(function(){
    jQuery('a.jslm_sort_img').click(function (e) {
      e.preventDefault();
      changeSortBy();
    });
 	 	jQuery('a.lms_sort_img').click(function (e) {
  		e.preventDefault();
    	changeSortByTheme();
	 	});


/*    jQuery('.lms_box_style_listing').masonry({
      // options
      itemSelector: '.grid-item',
      columnWidth: '.grid-item'
    });
*/

  });
    
  function changeCombo() {
    jQuery("input#sorton").val(jQuery('select#jslm_sorting').val());
    changeSortBy();
  }

  function resetCourseListFrom() {
    jQuery("select#category").val('');
    jQuery('input#coursetitle').val("");
    jQuery('input#issearchform').val(0);
    jQuery("form#jslearnmanagerform").submit();
  }

  document.changeListStyle= function (val){
    jQuery.post(ajaxurl, {action: "jslearnmanager_ajax", jslmsmod: "course", task: "setListStyleSession", styleid: val}, function(data){
      if (data){
        location.reload();
      }
    });
  }
</script>
