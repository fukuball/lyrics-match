<?php
/**
 * index.php is the /music/song/index.php content
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /music/song/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
if (!empty($_GET['song_id'])) {
?>
<ul class="breadcrumb">
   <li>
      <a href="<?=SITE_HOST?>/music">歌曲列表</a> <span class="divider">/</span>
   </li>
   <li class="active">
      歌曲資料
   </li>
</ul>
<?php
   $model_id = 2;
   if (!empty($_GET['model_id'])) {
      $model_id = $_GET['model_id'];
   }
   $song_obj = new LMSong($_GET['song_id']);
   $disc_obj = new LMDisc($song_obj->disc_id);
   $performer_obj = new LMPerformer($song_obj->performer_id);
   $lyricist_obj = new LMLyricist($song_obj->lyricist_id);
   $composer_obj = new LMComposer($song_obj->composer_id);
?>
<div id='song-item-block'>
   <div class="row">
      <div class="flow-left" style="width:300px;">
         <a href="#" class="thumbnail">
            <img src="<?=$disc_obj->cover_path?>" alt="cover">
         </a>
      </div>
      <div class="flow-left" style="width:300px;margin-left:30px;">
         <h2><?=$song_obj->title?></h2>
         <h3>專輯：<?=$disc_obj->title?></h3>
         <h3>音樂人：<?=$performer_obj->name?></h3>
         <h3>作詞：<?=$lyricist_obj->name?></h3>
         <h3>作曲：<?=$composer_obj->name?></h3>
         <h3>類型：<?=$song_obj->genre?></h3>
         <h3>發行：<?=$song_obj->release_date?></h3>
         <hr />
         <?php
         if (!empty($song_obj->audio_path)) {
         ?>
         <p id="audioplayer">Load Song</p>
         <script type="text/javascript">
         AudioPlayer.embed("audioplayer", {
             soundFile: "<?=$song_obj->getAudioUrl()?>",
             titles: "<?=$song_obj->title?>",
             artists: "<?=$performer_obj->name?>",
             autostart: "no"
         });
         </script>
         <?php
         }
         ?>
      </div>
      <div class="flow-left" style="width:340px;margin-left:30px;">
         <h3>歌詞</h3>
         <p><?=nl2br($song_obj->lyric)?></p>
      </div>
   </div>
   <hr />
   <?php
   $music_feature_god = new LMMusicFeatureGod();
   $music_feature_id = $music_feature_god->findBySongId($song_obj->getId());
   if ($music_feature_id) {
      $music_feature_obj = new LMMusicFeature($music_feature_id);
   ?>
   <h2>音樂特徵值</h2>
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th>種類</th>
            <th>Feature</th>
            <th>Value</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td rowspan="2"></td>
            <td>echonest_track_id</td>
            <td><?=$song_obj->echonest_track_id?></td>
         </tr>
         <tr>
            <td>key</td>
            <td><?=$song_obj->key?></td>
         </tr>
         <tr>
            <td>調性</td>
            <td>mode</td>
            <td><?=$song_obj->mode?></td>
         </tr>
         <tr>
            <td rowspan="5">力度</td>
            <td>energy</td>
            <td><?=$song_obj->energy?></td>
         </tr>
         <tr>
            <td>loudness</td>
            <td><?=$song_obj->loudness?></td>
         </tr>
         <tr>
            <td>ratio of beat count to bar count</td>
            <td><?=($music_feature_obj->beat_count/$music_feature_obj->bar_count)?></td>
         </tr>
         <tr>
            <td>ratio of tatum count to beat count</td>
            <td><?=($music_feature_obj->tatum_count/$music_feature_obj->beat_count)?></td>
         </tr>
         <tr>
            <td>ratio of tatum count to bar count</td>
            <td><?=($music_feature_obj->tatum_count/$music_feature_obj->bar_count)?></td>
         </tr>
         <tr>
            <td rowspan="9">節奏及速度</td>
            <td>tempo</td>
            <td><?=$song_obj->tempo?></td>
         </tr>
         <tr>
            <td>danceability</td>
            <td><?=$song_obj->danceability?></td>
         </tr>
         <tr>
            <td>time_signature</td>
            <td><?=$song_obj->time_signature?></td>
         </tr>
         <tr>
            <td>tatums per second</td>
            <td><?=(1/$music_feature_obj->tatum_avg_second)?></td>
         </tr>
         <tr>
            <td>beats per second</td>
            <td><?=(1/$music_feature_obj->beat_avg_second)?></td>
         </tr>
         <tr>
            <td>bars per second</td>
            <td><?=(1/$music_feature_obj->bar_avg_second)?></td>
         </tr>
         <tr>
            <td>ratio of avg beat length to avg bar length</td>
            <td><?=($music_feature_obj->beat_avg_second/$music_feature_obj->bar_avg_second)?></td>
         </tr>
         <tr>
            <td>ratio of avg tatum length to avg beat length</td>
            <td><?=($music_feature_obj->tatum_avg_second/$music_feature_obj->beat_avg_second)?></td>
         </tr>
         <tr>
            <td>ratio of avg tatum length to avg bar length</td>
            <td><?=($music_feature_obj->tatum_avg_second/$music_feature_obj->bar_avg_second)?></td>
         </tr>
         <tr>
            <td rowspan="1">音高及音程</td>
            <td colspan="2">
               <h3>pitch_audio_word_histogram</h3>
               <div id="pitch_histogram" style="width: 100%; height: 400px;"></div>
               <script type="text/javascript">
                  var chart;
                  <?php
                  $pitch_array = explode(',',$music_feature_obj->pitch_audio_word_histogram);
                  $pitch_data_array = array();
                  $count = 1;
                  foreach ($pitch_array as $key=>$pitch_value) {
                     $pitch_data = '{ pitch_audio_word: "pitch word '.$count.'", word_count: '.$pitch_value.' }';
                     array_push($pitch_data_array, $pitch_data);
                     $count++;
                  }
                  $pitch_data_array_string = implode(',', $pitch_data_array);
                  ?>
                  var chartData = [<?=$pitch_data_array_string?>];

                  AmCharts.ready(function () {
                     // SERIAL CHART
                     chart = new AmCharts.AmSerialChart();
                     chart.dataProvider = chartData;
                     chart.categoryField = "pitch_audio_word";
                     chart.startDuration = 1;

                     // AXES
                     // category
                     var categoryAxis = chart.categoryAxis;
                     categoryAxis.labelRotation = 90;
                     categoryAxis.gridPosition = "start";

                     // value
                     // in case you don't want to change default settings of value axis,
                     // you don't need to create it, as one value axis is created automatically.

                     // GRAPH
                     var graph = new AmCharts.AmGraph();
                     graph.valueField = "word_count";
                     graph.balloonText = "[[category]]: [[value]]";
                     graph.type = "column";
                     graph.lineAlpha = 0;
                     graph.fillAlphas = 0.8;
                     chart.addGraph(graph);

                     chart.write("pitch_histogram");
                  });
               </script>
            </td>
         </tr>
         <tr>
            <td rowspan="2">音色</td>
            <td>speechiness</td>
            <td><?=$song_obj->speechiness?></td>
         </tr>
         <tr>
            <td colspan="2">
               <h3>timbre_audio_word_histogram</h3>
               <div id="timbre_histogram" style="width: 100%; height: 400px;"></div>
               <script type="text/javascript">
                  var chart_t;
                  <?php
                  $timbre_array = explode(',',$music_feature_obj->timbre_audio_word_histogram);
                  $timbre_data_array = array();
                  $count = 1;
                  foreach ($timbre_array as $key=>$timbre_value) {
                     $timbre_data = '{ timbre_audio_word: "timbre word '.$count.'", word_count: '.$timbre_value.' }';
                     array_push($timbre_data_array, $timbre_data);
                     $count++;
                  }
                  $timbre_data_array_string = implode(',', $timbre_data_array);
                  ?>
                  var chartData_t = [<?=$timbre_data_array_string?>];

                  AmCharts.ready(function () {
                     // SERIAL CHART
                     chart_t = new AmCharts.AmSerialChart();
                     chart_t.dataProvider = chartData_t;
                     chart_t.categoryField = "timbre_audio_word";
                     chart_t.startDuration = 1;

                     // AXES
                     // category
                     var categoryAxis = chart_t.categoryAxis;
                     categoryAxis.labelRotation = 90;
                     categoryAxis.gridPosition = "start";

                     // value
                     // in case you don't want to change default settings of value axis,
                     // you don't need to create it, as one value axis is created automatically.

                     // GRAPH
                     var graph = new AmCharts.AmGraph();
                     graph.valueField = "word_count";
                     graph.balloonText = "[[category]]: [[value]]";
                     graph.type = "column";
                     graph.lineAlpha = 0;
                     graph.fillAlphas = 0.8;
                     chart_t.addGraph(graph);

                     chart_t.write("timbre_histogram");
                  });
               </script>
            </td>
         </tr>
      </tbody>
   </table>
   <h2>
      相似音樂
   </h2>
   <table class="table table-bordered table-striped">
        <thead>
           <tr>
              <th>
               排名
              </th>
              <th>
               song_id
              </th>
              <th>
               藝人
              </th>
              <th>
               歌名
              </th>
              <th>
               相似度
              </th>
           </tr>
        </thead>
        <tbody>
   <?php
      $similar_song = shell_exec("python26 ".SITE_ROOT."/p-library/model/music_feature/similar_music_model.py ".$_GET['song_id']." ".$model_id);
      $similar_song_array = explode(",", $similar_song);
      $rank = 0;
      foreach ($similar_song_array as $skey => $svalue) {
         $rank++;
         $similar_song_value_array = explode(":", $svalue);
         $similar_song_obj = new LMSong($similar_song_value_array[0]);
         $artist_obj = new LMPerformer($similar_song_obj->performer_id);
         ?>
         <tr>
            <td>
            <?php echo $rank; ?>
            </td>
            <td>
               <a href="<?=SITE_HOST?>/music/song/index.php?song_id=<?=$similar_song_obj->getId()?>">
                  <?php echo $similar_song_obj->getId(); ?>
               </a>
            </td>
            <td>
               <?php echo $artist_obj->name; ?>
            </td>
            <td>
               <a href="<?=SITE_HOST?>/music/song/index.php?song_id=<?=$similar_song_obj->getId()?>">
                  <?php echo $similar_song_obj->title; ?>
               </a>
            </td>
            <td>
               <?php echo $similar_song_value_array[1]; ?>
            </td>
         </tr>
         <?php
         unset($similar_song_obj);
         unset($artist_obj);
      }
   ?>
      </tbody>
   </table>
   <?php
      unset($music_feature_obj);
   }
   ?>
   <h2>
      相似歌詞
   </h2>
   <table class="table table-bordered table-striped">
        <thead>
           <tr>
              <th>
               排名
              </th>
              <th>
               song_id
              </th>
              <th>
               藝人
              </th>
              <th>
               歌名
              </th>
              <th>
               相似度
              </th>
           </tr>
        </thead>
        <tbody>
        <?php

         $db_obj = LMDBAccess::getInstance();

         $select_sql = "SELECT similar_song_id,similar FROM similar_song WHERE song_id='".$_GET['song_id']."' AND similar_song_id!='920' AND similar_song_id!='921' AND similar_song_id!='922' AND similar_song_id!='923' AND similar_song_id!='924' AND similar_song_id!='925' AND model='lyrics-model-8.txt' ORDER BY similar DESC LIMIT 500";

         $query_result = $db_obj->selectCommand($select_sql);

         $rank = 0;
         foreach ($query_result as $query_result_data) {
            $rank++;
            $similar_song_id = $query_result_data['similar_song_id'];
            $similar = $query_result_data['similar'];

            $similar_song_obj = new LMSong($similar_song_id);
            $artist_obj = new LMPerformer($similar_song_obj->performer_id);
           ?>
           <tr>
              <td>
              <?php echo $rank; ?>
              </td>
              <td>
                 <a href="<?=SITE_HOST?>/music/song/index.php?song_id=<?=$similar_song_obj->getId()?>">
                    <?php echo $similar_song_obj->getId(); ?>
                 </a>
              </td>
              <td>
                 <?php echo $artist_obj->name; ?>
              </td>
              <td>
                 <a href="<?=SITE_HOST?>/music/song/index.php?song_id=<?=$similar_song_obj->getId()?>">
                    <?php echo $similar_song_obj->title; ?>
                 </a>
              </td>
              <td>
                 <?php echo $similar; ?>
              </td>
           </tr>
           <?php

            unset($similar_song_obj);
            unset($artist_obj);
         }
        ?>
        </tbody>
   </table>
</div>
<?php
   unset($song_obj);
   unset($disc_obj);
   unset($performer_obj);
   unset($lyricist_obj);
   unset($composer_obj);
}
?>
