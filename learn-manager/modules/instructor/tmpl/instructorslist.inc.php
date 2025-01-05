<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script>
function resetInstructorListFrom() {
      jQuery("input#instructorname").val('');
      jQuery("input#instructoremail").val('');
   }

</script>	
