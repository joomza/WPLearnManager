<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<?php wp_enqueue_script('jquery-masonry '); ?>
<script>
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

</script>	
