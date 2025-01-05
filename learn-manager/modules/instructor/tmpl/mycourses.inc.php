<script type="text/javascript">
	function changeSortBy() {
 		var value = jQuery('a.jslm_sort_img').attr('data-sortby');
 		if (value == 1) {
          value = 2;
      } else {
          value = 1;
      }
      jQuery('form#jslearnmanagerform').submit();
  }

  function resetCourseListFrom() {
  	jQuery("select#category").val('');
  	jQuery('input#coursetitle').val("");
  	jQuery("form#jslearnmanagerform").submit();
  }

  function changeCombo() {
  	jQuery("input#sorton").val(jQuery('select#jslm_sorting').val());
  	changeSortBy();
  }

  jQuery(document).ready(function(){
  	jQuery('.jslm_select_full_width').selectable();
  });

  function showpaymentplan(id,returnP=1){
    if(jQuery("#"+id) != ""){
      jQuery("#"+id).modal("show");  
      return false;
    }
  }
</script>
   
