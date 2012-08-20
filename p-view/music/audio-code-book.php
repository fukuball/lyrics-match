<?php
/**
 * audio-code-book.php is the /music/audio-code-book.php content
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /music/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
?>
<div id="chartdiv" style="width: 1000px; height: 600px;"></div>
<script type="text/javascript">
      var chart;

      var chartData = [{
          code_word: 1,
          pitch: 3
      }, {
          code_word: 2,
          pitch: 5
      }, {
          code_word: 3,
          pitch: 8
      }, {
          code_word: 4,
          pitch: 9
      }, {
          code_word: 5,
          pitch: 4
      }];


      AmCharts.ready(function () {
          // SERIAL CHART
          chart = new AmCharts.AmSerialChart();
          chart.dataProvider = chartData;
          chart.categoryField = "code_word";
          chart.startDuration = 1;
          chart.rotate = true;

          // AXES
          // category
          var categoryAxis = chart.categoryAxis;
          categoryAxis.gridPosition = "start";
          categoryAxis.axisColor = "#DADADA";
          categoryAxis.dashLength = 5;

          // value
          var valueAxis = new AmCharts.ValueAxis();
          valueAxis.dashLength = 12;
          valueAxis.axisAlpha = 0.2;
          valueAxis.position = "top";
          valueAxis.title = "Pitch Audio Word";
          chart.addValueAxis(valueAxis);

          // GRAPHS
          // column graph
          var graph1 = new AmCharts.AmGraph();
          graph1.type = "column";
          graph1.title = "Pitch";
          graph1.valueField = "pitch";
          graph1.fillColors = "#ADD981";
          graph1.fillAlphas = 1;
          chart.addGraph(graph1);

          // LEGEND
          var legend = new AmCharts.AmLegend();
          chart.addLegend(legend);

          // WRITE
          chart.write("chartdiv");
      });
  </script>