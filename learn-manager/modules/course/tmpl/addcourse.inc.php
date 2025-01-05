<script type="text/javascript">

   jQuery(document).ready(function() {
        jQuery('.jslm_select_full_width').selectable();
        jQuery("#access_type").change(function(){
            var access_type = jQuery( "#access_type option:selected" ).val();
            jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "accesstype",task: "getaccesstypeByIdAjax",id: access_type} , function (access_type) {
                if (access_type) {
                    if(access_type == "Paid"){
                        jQuery("#jslm_price_id").show();
                        <?php if(in_array("paymentplan",jslearnmanager::$_active_addons)){ ?>
                          jQuery("#paymentplanModal").modal("show");
                        <?php } ?>
                    }else{
                        jQuery("#jslm_price_id").hide();
                    } 
                }
            });
        });

      jQuery(window).load(function(){
        var access_type = jQuery( "#access_type option:selected" ).val();
        jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "accesstype",task: "getaccesstypeByIdAjax",id: access_type} , function (access_type) {
            if(access_type == "Paid"){
                jQuery("#jslm_price_id").show();
            }else{
                jQuery("#jslm_price_id").hide();
            }
        });
        isDiscountEnable();
      });

      jQuery.validate();

        jQuery( "#jslm_courseForm" ).submit(function( e ) {
            var access_type = jQuery( "#access_type option:selected" ).val();
            jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "accesstype",task: "getaccesstypeByIdAjax",id: access_type} , function (access_type) {
                 if(access_type == "Paid"){
                    var price = document.getElementById("price").value;
                    var discount_price = document.getElementById("discount_price").value;
                    jQuery("#pricevalidate").remove();
                    jQuery("#discountvaldiate").remove();
                    jQuery("#currencyvalidate").remove();
                    if(price < 1){
                       e.preventDefault();
                       jQuery( "#price" ).append().after("<div id='pricevalidate' class='validation'><?php echo __("Course Price Must be greater or equal than 1","learn-manager"); ?></div>");
                    }
                 }else{
                    document.getElementById("price").value = 0;
                    document.getElementById("discount_price").value = 0;
                    document.getElementById("currency").value = 0;
                 }
            });
            discountTypeAndPriceCheck(e,price,discount_price);
      });

      jQuery("#discount_checkbox").change(function(){
         isDiscountEnable();
      });

      jQuery("#course_courseimage").click(function(){

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
         alert("This file is not allowed, please upload valid file.");
         jQuery("#uploadFile").val("");
         return false;
      } else {
         return true;
      }
   }

   function fileSizeValidate(fdata){
      var filesize = "<?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_logo_size'); ?>";
      var uploadedfilesize = fdata.files[0].size;
      if((uploadedfilesize / 1000) > filesize){
         alert("File Size Must be less than "+filesize+" KB");
         jQuery("#uploadFile").val("");
         return false;
      }else{
         return true;
      }
   }

   function isDiscountEnable(){
      var isdiscount = jQuery('#isdiscount1').prop('checked');
      if(isdiscount === true){
         jQuery("#isdiscount1").val("1");
         jQuery("#discount_type").show();
         jQuery("#discount_amount").show();
      }else if(isdiscount != true){
         jQuery("#isdiscount1").val("0");
         jQuery("#discount_type").hide();
         jQuery("#discount_amount").hide();
      }
   }

   function discountTypeAndPriceCheck(e,price,disprice){
      var isdiscount = jQuery('#isdiscount1').prop('checked');
      if(isdiscount == true){
         var discounttype = jQuery("#discount_type option:selected").val();
         if(discounttype == ""){
            e.preventDefault();
            jQuery( "#discount_type" ).append("<div id='pricevalidate' class='validation'><?php echo __("Please Select Valid Discount Type","learn-manager"); ?></div>");
         }
         if(parseInt(price) < parseInt(disprice) && discounttype != 1){
            e.preventDefault();
            jQuery( "#discount_price" ).append().after("<div id='discountvaldiate' class='validation'><?php echo __("Discount price must be less or equal than Course price","learn-manager"); ?></div>");
         }
         if(discounttype == 1 && (parseInt(disprice) > 100 || parseInt(disprice) < 1)){
            e.preventDefault();
            jQuery( "#discount_price" ).append().after("<div id='discountvaldiate' class='validation form-error'><?php echo __("Discount percentage must be less or equal to 100 and greater than 0","learn-manager"); ?></div>");
         }
         if(parseInt(disprice) <= 0 && discounttype == 2){
            e.preventDefault();
            jQuery( "#discount_price" ).append().after("<div id='discountvaldiate' class='validation form-error'><?php echo __("Discount price must be greater or equal than 0","learn-manager"); ?></div>");
         }
      }
   }

   var inputs = document.querySelectorAll( '.jslm_file_upload_input' );
   Array.prototype.forEach.call( inputs, function( input )
   {
      var label    = input.nextElementSibling,
         labelVal = label.innerHTML;
      input.addEventListener( 'change', function( e ){
         var fileName = '';
         fileName = e.target.value.split( '\\' ).pop();
         if( fileName )
            label.querySelector( 'span' ).innerHTML = fileName.substring(0, 20)+"....";
         else
            label.innerHTML = labelVal.substring(0, 20)+"....";
      });
   });

   <?php if(jslearnmanager::$_learn_manager_theme == 1){ ?>
      function preview_image(fdata){
         var reader = new FileReader();
         reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
         }
            reader.readAsDataURL(fdata.files[0]);
      }

      jQuery(document).ready(function() {
         jQuery('.jslm_select_full_width').selectable();
         jQuery("#uploadFile").change(function () {
            if(fileExtValidate(this)) {
               if(fileSizeValidate(this)) {
                  preview_image(this);
               }
            }
         });
      });



   <?php }else{ ?>
      jQuery(document).ready(function() {
         jQuery("#uploadFile").change(function () {
            if(fileExtValidate(this)) {
               if(fileSizeValidate(this)) {
                  var inputs = document.querySelectorAll( '.jslm_file_upload_input' );
                  Array.prototype.forEach.call( inputs, function( input ){
                     var filename = input.files[0].name;
                     var label    = input.nextElementSibling,
                     labelVal = label.innerHTML;
                     if( filename )
                        label.innerHTML = filename.substring(0, 20);
                  });
               }
            }
         });
      });
   <?php } ?>
   

</script>
