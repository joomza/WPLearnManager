<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<?php  wp_enqueue_script('jquery-google-repaptcha', $protocol.'www.google.com/recaptcha/api.js'); ?>
<script>
  jQuery(document).ready(function(){
        jQuery.validate();
        changeIconTabs();
  });

	function preview_image(fdata){
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('imagePreview');
      output.src = reader.result;
    }
    reader.readAsDataURL(fdata.files[0]);
  }
  
  jQuery(document).ready(function(){
    jQuery('.jslm_select_full_width').selectable();
    jQuery("#upload").change(function () {
      if(fileExtValidate(this)) {
        if(fileSizeValidate(this)) {
          preview_image(this);
        }  
      }    
    });
    
    jQuery('.nav-tabs a').click(function (e) {
      jQuery(this).tab('show');
      changeIconTabs();
    });

  });

  var validExt = "<?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type'); ?>";
  function fileExtValidate(fdata) {
    var filePath = fdata.value;
    var getFileExt = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
    var pos = validExt.indexOf(getFileExt);
    if(pos < 0) {
      alert("<?php echo esc_html(__("This file is not allowed, please upload valid file.","learn-manager"));?>");
      return false;
    } else {
      return true;
    }
  }

  function fileSizeValidate(fdata){
    var filesize = "<?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_profileimage_size'); ?>";
    if(fdata.files[0] != undefined){
      var uploadedfilesize = fdata.files[0].size;
      if((uploadedfilesize / 1000) > filesize){
        alert("<?php echo __("File Size Must be less than ","learn-manager"); ?>"+filesize+"<?php echo __(" KB","learn-manager");?>");
        return false;
      }else{
        return true;
      }
    }
  }
  function changeIconTabs(){
      jQuery(document).ready(function(){
         var tabValue = jQuery(".nav-tabs .active > a > img").attr("id");
         if(tabValue == "registericon"){
            jQuery("#rulesicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/register_icon/rules.png'; ?>");
            jQuery("#courseicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/register_icon/courses.png'; ?>");
            jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/register_icon/Instuctor-register-white.png'; ?>");
         }else if(tabValue == "rulesicon"){
            jQuery("#courseicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/register_icon/courses.png'; ?>");
            jQuery("#registericon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/register_icon/Instuctor-register.png'; ?>");
            jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/register_icon/rules-white.png'; ?>");
         }else if(tabValue == "courseicon"){
            jQuery("#registericon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/register_icon/Instuctor-register.png'; ?>");
            jQuery("#rulesicon").attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/register_icon/rules.png'; ?>");
            jQuery("#"+tabValue).attr("src","<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images/register_icon/courses-white.png'; ?>");
         }
      
      });
   }
</script>
