<script>
  var ajaxurl = "<?php echo admin_url("admin-ajax.php"); ?>";
  document.removeShortlist = function(rid,cid,call_from){
  	var courseid = cid;
  	var rowid = rid;
      jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "course",task: "deleteShortListedCourse",cid: courseid, call_from: call_from, rid:rowid} , function (data) {
         if (data) {
         	console.log(data);
         		if(call_from != 4){				
         			var shortlistdata = (data);
           		jQuery('#span_sh_'+cid).html("");
           		jQuery('#span_sh_'+cid).append(shortlistdata);
           	}else{
           		window.location.reload();
           	}
         	}else{
         		console.log("Error");
         	}
      });
  }
    
  jQuery(document).ready(function() {
        
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
  });



    
  function changeCombo() {
    jQuery("input#sorton").val(jQuery('select#jslm_sorting').val());
    changeSortBy();
  }

  function resetCourseListFrom() {
    jQuery("select#category").val('');
    jQuery("input#coursetitle").val('');
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
