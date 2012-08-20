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
<div id="chartdiv" style="width: 600px; height: 400px;"></div>
<script type="text/javascript">
   var chart;

   var chartData = [{
       code_word: "code word 1",
       pitch1: 1,
       pitch2: 1,
       pitch3: 1,
       pitch4: 1,
       pitch5: 1,
       pitch6: 1,
       pitch7: 1,
       pitch8: 1,
       pitch9: 1,
       pitch10: 1,
       pitch11: 1,
       pitch12: 1
   }];

   AmCharts.ready(function () {
       // SERIAL CHART
       chart = new AmCharts.AmSerialChart();
       chart.dataProvider = chartData;
       chart.categoryField = "code_word";

       // sometimes we need to set margins manually
       // autoMargins should be set to false in order chart to use custom margin values
       chart.autoMargins = false;
       chart.marginLeft = 0;
       chart.marginRight = 0;
       chart.marginTop = 30;
       chart.marginBottom = 40;

       // AXES
       // category
       var categoryAxis = chart.categoryAxis;
       categoryAxis.gridAlpha = 0;
       categoryAxis.axisAlpha = 0;
       categoryAxis.gridPosition = "start";

       // value
       var valueAxis = new AmCharts.ValueAxis();
       valueAxis.stackType = "100%"; // this line makes the chart 100% stacked
       valueAxis.gridAlpha = 0;
       valueAxis.axisAlpha = 0;
       valueAxis.labelsEnabled = false;
       chart.addValueAxis(valueAxis);

       // GRAPHS
        // first graph
        var graph = new AmCharts.AmGraph();
        graph.title = "pitch1";
        graph.labelText = "[[percents]]%";
        graph.balloonText = "[[value]] ([[percents]]%)";
        graph.valueField = "pitch1";
        graph.type = "column";
        graph.lineAlpha = 0;
        graph.fillAlphas = 0.5;
        graph.lineColor = "#ffffff";
        chart.addGraph(graph);

        // first graph
          var graph = new AmCharts.AmGraph();
          graph.title = "pitch1";
          graph.labelText = "[[percents]]%";
          graph.balloonText = "[[value]] ([[percents]]%)";
          graph.valueField = "pitch1";
          graph.type = "column";
          graph.lineAlpha = 0;
          graph.fillAlphas = 0.5;
          graph.lineColor = "#ffffff";
          chart.addGraph(graph);

          // first graph
            var graph = new AmCharts.AmGraph();
            graph.title = "pitch2";
            graph.labelText = "[[percents]]%";
            graph.balloonText = "[[value]] ([[percents]]%)";
            graph.valueField = "pitch2";
            graph.type = "column";
            graph.lineAlpha = 0;
            graph.fillAlphas = 0.5;
            graph.lineColor = "#ffffff";
            chart.addGraph(graph);

            // first graph
              var graph = new AmCharts.AmGraph();
              graph.title = "pitch3";
              graph.labelText = "[[percents]]%";
              graph.balloonText = "[[value]] ([[percents]]%)";
              graph.valueField = "pitch3";
              graph.type = "column";
              graph.lineAlpha = 0;
              graph.fillAlphas = 0.5;
              graph.lineColor = "#ffffff";
              chart.addGraph(graph);

              // first graph
                var graph = new AmCharts.AmGraph();
                graph.title = "pitch4";
                graph.labelText = "[[percents]]%";
                graph.balloonText = "[[value]] ([[percents]]%)";
                graph.valueField = "pitch4";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.5;
                graph.lineColor = "#ffffff";
                chart.addGraph(graph);

                // first graph
                  var graph = new AmCharts.AmGraph();
                  graph.title = "pitch5";
                  graph.labelText = "[[percents]]%";
                  graph.balloonText = "[[value]] ([[percents]]%)";
                  graph.valueField = "pitch5";
                  graph.type = "column";
                  graph.lineAlpha = 0;
                  graph.fillAlphas = 0.5;
                  graph.lineColor = "#ffffff";
                  chart.addGraph(graph);

                  // first graph
                    var graph = new AmCharts.AmGraph();
                    graph.title = "pitch6";
                    graph.labelText = "[[percents]]%";
                    graph.balloonText = "[[value]] ([[percents]]%)";
                    graph.valueField = "pitch6";
                    graph.type = "column";
                    graph.lineAlpha = 0;
                    graph.fillAlphas = 0.5;
                    graph.lineColor = "#ffffff";
                    chart.addGraph(graph);

                    // first graph
                      var graph = new AmCharts.AmGraph();
                      graph.title = "pitch7";
                      graph.labelText = "[[percents]]%";
                      graph.balloonText = "[[value]] ([[percents]]%)";
                      graph.valueField = "pitch7";
                      graph.type = "column";
                      graph.lineAlpha = 0;
                      graph.fillAlphas = 0.5;
                      graph.lineColor = "#ffffff";
                      chart.addGraph(graph);

                      // first graph
                        var graph = new AmCharts.AmGraph();
                        graph.title = "pitch8";
                        graph.labelText = "[[percents]]%";
                        graph.balloonText = "[[value]] ([[percents]]%)";
                        graph.valueField = "pitch8";
                        graph.type = "column";
                        graph.lineAlpha = 0;
                        graph.fillAlphas = 0.5;
                        graph.lineColor = "#ffffff";
                        chart.addGraph(graph);

                        // first graph
                          var graph = new AmCharts.AmGraph();
                          graph.title = "pitch9";
                          graph.labelText = "[[percents]]%";
                          graph.balloonText = "[[value]] ([[percents]]%)";
                          graph.valueField = "pitch9";
                          graph.type = "column";
                          graph.lineAlpha = 0;
                          graph.fillAlphas = 0.5;
                          graph.lineColor = "#ffffff";
                          chart.addGraph(graph);

                          // first graph
                            var graph = new AmCharts.AmGraph();
                            graph.title = "pitch10";
                            graph.labelText = "[[percents]]%";
                            graph.balloonText = "[[value]] ([[percents]]%)";
                            graph.valueField = "pitch10";
                            graph.type = "column";
                            graph.lineAlpha = 0;
                            graph.fillAlphas = 0.5;
                            graph.lineColor = "#ffffff";
                            chart.addGraph(graph);

                            // first graph
                              var graph = new AmCharts.AmGraph();
                              graph.title = "pitch11";
                              graph.labelText = "[[percents]]%";
                              graph.balloonText = "[[value]] ([[percents]]%)";
                              graph.valueField = "pitch11";
                              graph.type = "column";
                              graph.lineAlpha = 0;
                              graph.fillAlphas = 0.5;
                              graph.lineColor = "#ffffff";
                              chart.addGraph(graph);


                              // first graph
                                var graph = new AmCharts.AmGraph();
                                graph.title = "pitch12";
                                graph.labelText = "[[percents]]%";
                                graph.balloonText = "[[value]] ([[percents]]%)";
                                graph.valueField = "pitch12";
                                graph.type = "column";
                                graph.lineAlpha = 0;
                                graph.fillAlphas = 0.5;
                                graph.lineColor = "#ffffff";
                                chart.addGraph(graph);

       // WRITE
       chart.write("chartdiv");
   });

   // this method makes chart 2D/3D
   function setDepth() {
       if (document.getElementById("rb1").checked) {
           chart.depth3D = 0;
           chart.angle = 0;
       } else {
           chart.depth3D = 25;
           chart.angle = 30;
       }
       chart.validateNow();
   }
</script>