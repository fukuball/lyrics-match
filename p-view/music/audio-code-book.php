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

$db_obj = LMDBAccess::getInstance();

$select_sql = "SELECT ".
              "id, audio_word ".
              "FROM muisc_audio_code_word ".
              "WHERE is_deleted = '0' ".
              "AND code_book_id = '1' ".
              "ORDER BY id";

$query_result = $db_obj->selectCommand($select_sql);

?>
<div class="row">
<?php
foreach ($query_result as $query_result_data) {

   $audio_word_id = $query_result_data['id'];
   $audio_word = json_decode($query_result_data['audio_word']);

   ?>
   <div id="chartdiv<?=$audio_word_id;?>" class="pull-left" style="width: 100px; height: 300px;"></div>
   <script type="text/javascript">
      var chart<?=$audio_word_id;?>;

      var chartData<?=$audio_word_id;?> = [{
          code_word<?=$audio_word_id;?>: "code word<?=$audio_word_id;?>",
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
         chart<?=$audio_word_id;?> = new AmCharts.AmSerialChart();
         chart<?=$audio_word_id;?>.dataProvider = chartData<?=$audio_word_id;?>;
         chart<?=$audio_word_id;?>.categoryField = "code_word<?=$audio_word_id;?>";

         // sometimes we need to set margins manually
         // autoMargins should be set to false in order chart to use custom margin values
         chart<?=$audio_word_id;?>.autoMargins = false;
         chart<?=$audio_word_id;?>.marginLeft = 0;
         chart<?=$audio_word_id;?>.marginRight = 0;
         chart<?=$audio_word_id;?>.marginTop = 30;
         chart<?=$audio_word_id;?>.marginBottom = 40;

         // AXES
         // category
         var categoryAxis = chart<?=$audio_word_id;?>.categoryAxis;
         categoryAxis.gridAlpha = 0;
         categoryAxis.axisAlpha = 0;
         categoryAxis.gridPosition = "start";

         // value
         var valueAxis = new AmCharts.ValueAxis();
         valueAxis.stackType = "100%"; // this line makes the chart 100% stacked
         valueAxis.gridAlpha = 0;
         valueAxis.axisAlpha = 0;
         valueAxis.labelsEnabled = false;
         chart<?=$audio_word_id;?>.addValueAxis(valueAxis);

         <?php
         $count = 1;
         foreach ($audio_word as $key=>$audio_word_value) {
            ?>
            var graph = new AmCharts.AmGraph();
            graph.title = "pitch<?=$count?>";
            graph.labelText = "";
            graph.balloonText = "<?=$audio_word_value?>";
            graph.valueField = "pitch<?=$count?>";
            graph.type = "column";
            graph.lineAlpha = 0;
            graph.fillAlphas = <?=$audio_word_value?>;
            graph.lineColor = "#D41313";
            chart<?=$audio_word_id;?>.addGraph(graph);
            <?php
            $count++;
         }
         ?>

         // WRITE
         chart<?=$audio_word_id;?>.write("chartdiv<?=$audio_word_id;?>");
      });

   </script>
   <?php

}
?>
</div>
