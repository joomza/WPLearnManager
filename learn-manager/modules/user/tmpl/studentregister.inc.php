<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  wp_enqueue_script('jquery-google-repaptcha', $protocol.'www.google.com/recaptcha/api.js');
?>
<script>
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
    jQuery.validate({
      rules:{
        field:{
          url: true,
        }
      }
    });
    jQuery("#upload").change(function () {
      if(fileExtValidate(this)) {
        if(fileSizeValidate(this)) {
          preview_image(this);
        }  
      }    
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

</script>
