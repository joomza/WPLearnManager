<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<?php
    wp_enqueue_script('jquery-masonry   ');
    wp_enqueue_script("corechart" , $protocol."www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}");
    wp_enqueue_script('charts-loder' , $protocol.'www.gstatic.com/charts/loader.js');
?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(drawStackChartHorizontal);
    });
    
    function drawStackChartHorizontal() {
        var data = google.visualization.arrayToDataTable([
            <?php
                echo jslearnmanager::$_data['stack_chart_horizontal']['title'] . ',';
                echo jslearnmanager::$_data['stack_chart_horizontal']['data'];
            ?>
        ]);
        var view = new google.visualization.DataView(data);
        var options = {
        curveType: 'function',
                height:300,
                legend: { position: 'top', maxLines: 3 },
                pointSize: 4,
                isStacked: true,
                focusTarget: 'category',
                chartArea: {width:'90%', top:50}
        };
        var chart = new google.visualization.LineChart(document.getElementById("stack_chart_horizontal"));
        chart.draw(view, options);
    }
</script>

     

