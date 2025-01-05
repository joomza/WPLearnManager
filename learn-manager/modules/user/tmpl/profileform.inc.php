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
    jQuery.validate();
    jQuery("#upload").change(function () {
      if(fileExtValidate(this)) {
        if(fileSizeValidate(this)) {
          jQuery("#validimage").val("1");
          preview_image(this);
        }else{
          jQuery("#validimage").val("0");
        }
      }else{
        jQuery("#validimage").val("0");
      }
    });
    jQuery("#user_profileimage").click(function(){
      var def_src = jQuery("#imagePreview").attr("data-default-src");
      jQuery("#imagePreview").attr("src",def_src);
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
    var uploadedfilesize = fdata.files[0].size;
    if((uploadedfilesize / 1000) > filesize){
      alert("<?php echo __("File Size Must be less than ","learn-manager"); ?>"+filesize+"<?php echo __(" KB","learn-manager");?>");
      return false;
    }else{
      return true;
    }
  }
</script>