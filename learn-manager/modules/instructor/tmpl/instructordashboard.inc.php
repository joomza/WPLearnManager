<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<?php
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery.min');
  wp_enqueue_script("corechart" , $protocol."www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}");
  wp_enqueue_script('charts-loder' , $protocol.'www.gstatic.com/charts/loader.js');
?>
<script>
   var ajaxurl = "<?php echo admin_url("admin-ajax.php"); ?>";
   jQuery(document).ready(function(){

    jQuery('#paymentplanModal').on('shown.bs.modal', function (e) {
      var correct_link  = jQuery("#jslms_temp_hold_value_for_url").val();
      <?php if(jslearnmanager::$_learn_manager_theme == 1){ ?>
        var str = correct_link;
        var cid = str.split('jslearnmanagerid=');
        cid = cid[1];
        jQuery.post(ajaxurl, {action: "jslearnmanager_ajax", jslmsmod: "paymentplan", task: "getPaymentPlanForCourseAjax", courseid: cid}, function(data){
          if (data){
            var price = (data);
            jQuery("span.lms-plan-amount.simplepriceplan").html('<img src="<?php echo esc_html(LEARN_MANAGER_IMAGE);?>/cash-icon.png"> '+price.simpleprice);
            jQuery("span.lms-plan-amount.lms-bg-secondary").html('<img src="<?php echo esc_html(LEARN_MANAGER_IMAGE);?>/cash-icon.png"> '+price.featuredprice);
          }
        });
      <?php } ?>
      jQuery('#lms-make-course-featured-insturctor-dashboard').attr('href',correct_link);
    });

      jQuery('ul#myTabdashboard li a').click(function(){
         jQuery(this).tab('show');
         var dashboardid = jQuery(this).attr('data-toggle');
         jQuery('#myTabdashboarddata .fade').removeClass('active');
         jQuery(dashboardid).addClass('active in');
      });
      jQuery('ul#myearningtabs li a').click(function(){
         jQuery(this).tab('show');
         var dashboardid = jQuery(this).attr('data-toggle');
         jQuery('#myearningtabsdata .fade').removeClass('active');
         jQuery(dashboardid).addClass('active in');
      });
      //jQuery('.jslm_select_full_width').selectable();
      //jQuery("#loading").show();
      // jQuery('.jslm_progress_bar_percent1').animate({width:'80%'}, 1000);
      // jQuery('.jslm_progress_bar_percent2').animate({width:'50%'}, 1000);
      // jQuery('.jslm_progress_bar_percent3').animate({width:'66%'}, 1000);
   });

   jQuery(window).load(function(){
      jQuery("#loading").hide();
   });


   function resetCourseListFrom() {
      jQuery("select#category").val('');
      jQuery("select#coursetitle").val('');
   }

   // For url # tabs
   jQuery(function(){
      var hash = window.location.hash;
      hash && jQuery('ul.nav a[href*="\\' + hash + '"]').tab('show');
      // jQuery('.nav-tabs a').click(function (e) {
      //    alert("hello");
         // //jQuery("#loading").show();
         // jQuery(this).tab('show');
            // var scrollmem = jQuery('body').scrollTop() || jQuery('html').scrollTop();
         // window.location.hash = this.hash;
         // jQuery('html,body').scrollTop(scrollmem);
      // });
   });

   // Confirmation Dialog for delete
   var elems = document.getElementsByClassName('fa fa-trash');
   var confirmIt = function (e) {
      if (!confirm('Are you sure?')) e.preventDefault();
   };
   for (var i = 0, l = elems.length; i < l; i++) {
      elems[i].addEventListener('click', confirmIt, false);
   }

   // For Earning Graph
   google.charts.load('current', {packages: ['corechart']});
   <?php if(isset(jslearnmanager::$_data['stack_chart_horizontal']) && isset(jslearnmanager::$_data['stack_chart_horizontal']['title'])){ ?>

   google.setOnLoadCallback(drawStackChartHorizontal);
   function drawStackChartHorizontal() { // For Earning Tab
      var data = google.visualization.arrayToDataTable([
         <?php
            echo jslearnmanager::$_data['stack_chart_horizontal']['title'] . ',';
            echo jslearnmanager::$_data['stack_chart_horizontal']['data'];
         ?>
      ]);
      var view = new google.visualization.DataView(data);
      var options = {
         curveType: 'function',
         height:'100%',
         width:'80%',
         legend: { position: 'top', maxLines: 3 },
         pointSize: 4,
         isStacked: true,
         focusTarget: 'category',
         chartArea: {left:60,right:50,top:35,bottom:60},
         series: {
         0: { color: '#FFC300' },
         1: { color: '#0A9955' },
         2: { color: '#FF0000' },
      }
      };
      if(document.getElementById("stack_chart_horizontal")){
         document.getElementById("stack_chart_horizontal").innerhtml = "";
         var chart = new google.visualization.LineChart(document.getElementById("stack_chart_horizontal"));
         chart.draw(view, options);
      }
   }
   // For Payouts Graph
   google.charts.load('current', {packages: ['corechart']});

   <?php } ?>

   <?php if(isset(jslearnmanager::$_data['payout_graph_detail'])){ ?>

   google.setOnLoadCallback(payoutsGraphDetail);
   function payoutsGraphDetail() { // For Earning Tab
      var data = google.visualization.arrayToDataTable([
         <?php
            echo jslearnmanager::$_data['payout_graph_detail']['title'] . ',';
            echo jslearnmanager::$_data['payout_graph_detail']['data'];
         ?>
      ]);
      var view = new google.visualization.DataView(data);
      var options = {
         curveType: 'function',
         height:'100%',
         width:'80%',
         legend: { position: 'top', maxLines: 3 },
         pointSize: 4,
         isStacked: true,
         focusTarget: 'category',
         chartArea: {left:60,right:50,top:35,bottom:60},
         series: {
         0: { color: '#FFC300' },
         1: { color: '#0A9955' },
         2: { color: '#FF0000' },
      }
      };
      if(document.getElementById("payout_graph_detail")){
         document.getElementById("payout_graph_detail").innerhtml = "";
         var chart = new google.visualization.LineChart(document.getElementById("payout_graph_detail"));
         chart.draw(view, options);
      }
   }
   <?php } ?>



   //For ajax live search
   jQuery(document).ready(function(){
      jQuery("#search-box").keyup(function(){
         var image = '<?php echo JSLEARNMANAGER_PLUGIN_URL.'includes/images' ?>';
         jQuery("#search-box").css("background","#FFF url("+image+"/3.gif) no-repeat 100%");
         jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "instructor",task: "getInstructorCoursesForAjaxCombo",course:jQuery("#search-box").val(), minlength:3},function (data,response) {
            jQuery("#suggesstion-box").show();
            jQuery("#suggesstion-box").html(data);
            jQuery("#search-box").css("background","#FFF");
         });
      });
   });
   //To select course name
   function selectCourse(val,id) {
      jQuery("#search-box").val(val);
      jQuery("#search-course").val(id);
      jQuery("#suggesstion-box").hide();
   }

   function earningForm() {
      jQuery("select#search-course").val('');
      jQuery("select#search-box").val('');
      jQuery("select#from").val('');
      jQuery("select#to").val('');
      jQuery("select#suggesstion-box").html("");
   }

   function earningForm() {
      jQuery("select#payoutfrom").val('');
      jQuery("select#payoutto").val('');
      location.reload();
   }
  // Dashboard tab earning
   <?php if(isset(jslearnmanager::$_data['stack_chart_horizontal_curmon']) && isset(jslearnmanager::$_data['stack_chart_horizontal_curmon']['title'])){ ?>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
         var data = google.visualization.arrayToDataTable([
         <?php
            echo jslearnmanager::$_data['stack_chart_horizontal_curmon']['title'] . ',';
            echo jslearnmanager::$_data['stack_chart_horizontal_curmon']['data'];
         ?>
         ]);

         var options = {
            title: 'Earning History',
            curveType: 'function',
            legend: { position: 'bottom' },
            series: {
               0: { color: '#FFC300' },
               1: { color: '#0A9955' },
               2: { color: '#FF0000' },
            }
         };
         if(document.getElementById('curve_chart')){
            document.getElementById('curve_chart').innerhtml = "";
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
         }
      }
   <?php } ?>



   jQuery(function() {
      jQuery( "#from" ).datepicker({
         dateFormat: 'yy-mm-dd'
      });
      jQuery( "#to" ).datepicker({
         dateFormat: 'yy-mm-dd'
      });
      jQuery( "#payoutfrom" ).datepicker({
         dateFormat: 'yy-mm-dd'
      });
      jQuery( "#payoutto" ).datepicker({
         dateFormat: 'yy-mm-dd'
      });

      // responsive tables
      var headertext = [];
      headers = document.querySelectorAll(".jslm-table th");
      tablerows = document.querySelectorAll(".jslm-table th");
      tablebody = document.querySelector(".jslm-table tbody");

      if(tablebody != null){
          for (var i = 0; i < headers.length; i++) {
              var current = headers[i];
              headertext.push(current.textContent.replace(/\r?\n|\r/, ""));
          }
          for (var i = 0; row = tablebody.rows[i]; i++) {
              for (var j = 0; col = row.cells[j]; j++) {
                  col.setAttribute("data-th", headertext[j]);
              }
          }
      }

      jQuery('table.jslm-table').each(function(i){
          var headertext = [];

          headers = jQuery(this).find('th');
          tablerows = jQuery(this).find('th');
          tablebody = jQuery(this).find('tbody tr');

          for (var i = 0; i < headers.length; i++) {
              var current = headers[i];
              headertext.push(current.textContent.replace(/\r?\n|\r/, ""));
          }

          for (var i = 0; row = tablebody[i]; i++) {
              var cols = jQuery(row).find('td');
              for (var j = 0; col = cols[j]; j++) {
                  col.setAttribute("data-th", headertext[j]);
              }
          }
      });

   });

   document.changeListStyle= function (val){
      jQuery.post(ajaxurl, {action: "jslearnmanager_ajax", jslmsmod: "instructor", task: "setListStyleSession", styleid: val}, function(data){
         if (data){
            location.reload();
         }
      });
    }

    function setCurrentCourseId(course_featured_url){
      jQuery("#jslms_temp_hold_value_for_url").val(course_featured_url);
    }


</script>
