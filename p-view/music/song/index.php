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
   $lmodel_id = 10;
   if (!empty($_GET['lmodel_id'])) {
      $lmodel_id = $_GET['lmodel_id'];
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
   <h2>
      歌詞特徵值
   </h2>
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th>種類</th>
            <th>Feature</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td rowspan="1">Term Frequency</td>
            <td colspan="2" width="800">
               <?php

               $db_obj = LMDBAccess::getInstance();
               $select_sql = "SELECT term,pos,tf,tfidf FROM lyrics_term_tfidf WHERE song_id='".$_GET['song_id']."' AND is_deleted=0 ORDER BY term";
               $query_result = $db_obj->selectCommand($select_sql);

               $term_data_array = array();
               foreach ($query_result as $query_result_data) {
                  $term = $query_result_data['term'];
                  $word_count = $query_result_data['tf'];
                  $term_data = '{ term_word: "'.addslashes($term).'", word_count: '.$word_count.' }';
                  array_push($term_data_array, $term_data);
               }
               $term_data_array_string = implode(',', $term_data_array);

               if (!empty($term_data_array_string)) {
                  ?>
                  <div id="term_frequency" style="width: 100%; height: 400px;"></div>
                  <script type="text/javascript">
                  var chart_wt;
                  var chartData_wt = [<?=$term_data_array_string?>];

                  AmCharts.ready(function () {
                     // SERIAL CHART
                     chart_wt = new AmCharts.AmSerialChart();
                     chart_wt.dataProvider = chartData_wt;
                     chart_wt.categoryField = "term_word";
                     chart_wt.startDuration = 1;

                     // AXES
                     // category
                     var categoryAxis = chart_wt.categoryAxis;
                     categoryAxis.labelRotation = 90;
                     categoryAxis.gridPosition = "start";
                     categoryAxis.autoGridCount = false;
                     categoryAxis.gridCount = 100000;

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
                     chart_wt.addGraph(graph);

                     chart_wt.write("term_frequency");
                  });
                  </script>
                  <?php
               }
               ?>
            </td>
         </tr>
         <tr>
            <td rowspan="1">TF-IDF</td>
            <td colspan="2" width="800">
               <?php
               $select_sql = "SELECT term,pos,tf,tfidf FROM lyrics_term_tfidf WHERE song_id='".$_GET['song_id']."' AND is_deleted=0 ORDER BY term";
               $query_result = $db_obj->selectCommand($select_sql);

               $term_data_array = array();
               foreach ($query_result as $query_result_data) {
                  $term = $query_result_data['term'];
                  $word_count = round($query_result_data['tfidf'], 4);
                  $term_data = '{ TFIDF: "'.addslashes($term).'", word_count: '.$word_count.' }';
                  array_push($term_data_array, $term_data);
               }
               $term_data_array_string = implode(',', $term_data_array);

               if (!empty($term_data_array_string)) {
                  ?>
                  <div id="tfidf" style="width: 100%; height: 400px;"></div>
                  <script type="text/javascript">
                  var chart_tfidf;
                  var chartData_tfidf = [<?=$term_data_array_string?>];

                  AmCharts.ready(function () {
                     // SERIAL CHART
                     chart_tfidf = new AmCharts.AmSerialChart();
                     chart_tfidf.dataProvider = chartData_tfidf;
                     chart_tfidf.categoryField = "TFIDF";
                     chart_tfidf.startDuration = 1;

                     // AXES
                     // category
                     var categoryAxis = chart_tfidf.categoryAxis;
                     categoryAxis.labelRotation = 90;
                     categoryAxis.gridPosition = "start";
                     categoryAxis.autoGridCount = false;
                     categoryAxis.gridCount = 100000;

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
                     chart_tfidf.addGraph(graph);

                     chart_tfidf.write("tfidf");
                  });
                  </script>
                  <?php
               }
               ?>
            </td>
         </tr>
         <tr>
            <td rowspan="1">LDA Value</td>
            <td colspan="2" width="800">
               <?php
               $db_obj = LMDBAccess::getInstance();
               $select_sql = "SELECT new_song_id FROM lyrics_term_tfidf_continue WHERE song_id = ".$_GET['song_id']." GROUP BY new_song_id LIMIT 1";

               $query_result = $db_obj->selectCommand($select_sql);

               foreach ($query_result as $query_result_data) {
                  $lda_new_song_id = $query_result_data['new_song_id'];
               }

               if ($_GET['lda_model']=='tf_lda') {
                  $topic = shell_exec("python26 ".SITE_ROOT."/p-library/model/music_feature/lda_topic.py ".$lda_new_song_id." ".$_GET['lda_model']);
                  ?>
                  <pre>
2012-09-18 00:31:35,640 : INFO : accepted corpus with 913 documents, 12684 features, 60108 non-zero entries
2012-09-18 00:31:35,686 : INFO : topic #0: 0.060*愚笨 + 0.017*冇掂過 + 0.015*惱河 + 0.011*徹底 + 0.011*厲害 + 0.011*亮麗 + 0.010*誤解 + 0.008*超過 + 0.008*不被 + 0.007*邂逅
2012-09-18 00:31:35,689 : INFO : topic #1: 0.020*愚笨 + 0.011*惱河 + 0.007*徹底 + 0.006*沒收 + 0.006*不被 + 0.005*亮麗 + 0.005*冇掂過 + 0.005*幸 + 0.005*厲害 + 0.004*不心痛
2012-09-18 00:31:35,696 : INFO : topic #2: 0.012*愚笨 + 0.008*待在 + 0.008*惱河 + 0.007*亮麗 + 0.007*沒收 + 0.007*冇掂過 + 0.006*不被 + 0.006*厲害 + 0.005*愛恨 + 0.005*交響
2012-09-18 00:31:35,700 : INFO : topic #3: 0.021*徹底 + 0.011*亮麗 + 0.011*愚笨 + 0.008*惱河 + 0.008*夠多 + 0.006*天黑 + 0.005*三言兩語 + 0.005*沒收 + 0.005*冇掂過 + 0.005*相依為命
2012-09-18 00:31:35,703 : INFO : topic #4: 0.022*愚笨 + 0.011*冇掂過 + 0.008*木馬 + 0.008*惱河 + 0.007*脫下 + 0.006*天黑 + 0.006*待在 + 0.006*沏 + 0.006*謙卑 + 0.006*為什麼
2012-09-18 00:31:35,706 : INFO : topic #5: 0.011*待在 + 0.009*奸黨 + 0.008*三言兩語 + 0.008*傷害 + 0.007*理會 + 0.007*卸便 + 0.006*開墾 + 0.006*患難 + 0.006*邂逅 + 0.006*惱河
2012-09-18 00:31:35,709 : INFO : topic #6: 0.015*眉飛色舞 + 0.013*愚笨 + 0.009*替身 + 0.008*奸黨 + 0.007*惱河 + 0.007*原 + 0.006*誤解 + 0.005*裝飾品 + 0.005*世情 + 0.005*厲害
2012-09-18 00:31:35,712 : INFO : topic #7: 0.044*愚笨 + 0.011*惱河 + 0.011*誤解 + 0.010*徹底 + 0.009*沒收 + 0.008*眉飛色舞 + 0.008*邂逅 + 0.007*裝飾品 + 0.006*亮麗 + 0.006*愛恨
2012-09-18 00:31:35,715 : INFO : topic #8: 0.010*惱河 + 0.008*悲 + 0.008*用錯 + 0.007*愚笨 + 0.007*沏 + 0.007*冇掂過 + 0.007*邂逅 + 0.006*顯眼 + 0.006*木馬 + 0.006*永生
2012-09-18 00:31:35,718 : INFO : topic #9: 0.013*愚笨 + 0.008*徹底 + 0.006*侶 + 0.006*謙卑 + 0.005*誤解 + 0.005*寂寂寞寞 + 0.005*亮麗 + 0.005*惱河 + 0.005*天黑 + 0.005*冇掂過
2012-09-18 00:31:35,721 : INFO : topic #10: 0.012*永生 + 0.012*愚笨 + 0.010*徹底 + 0.008*離歌 + 0.007*喜新厭舊 + 0.006*成佛 + 0.006*裝飾品 + 0.005*今夜 + 0.005*超過 + 0.005*談論
2012-09-18 00:31:35,724 : INFO : topic #11: 0.020*惱河 + 0.008*木馬 + 0.007*厲害 + 0.006*愚笨 + 0.006*怔怔 + 0.006*邂逅 + 0.005*相依為命 + 0.005*沒收 + 0.004*侶 + 0.004*歇
2012-09-18 00:31:35,727 : INFO : topic #12: 0.010*時鐘 + 0.009*眉飛色舞 + 0.009*職業 + 0.009*惱河 + 0.008*所謂 + 0.007*冇掂過 + 0.006*沏 + 0.006*世情 + 0.006*妹 + 0.005*替身
2012-09-18 00:31:35,730 : INFO : topic #13: 0.023*愚笨 + 0.020*惱河 + 0.012*亮麗 + 0.010*徹底 + 0.007*冇掂過 + 0.006*誤解 + 0.006*卸便 + 0.005*一段 + 0.005*赤道 + 0.005*三言兩語
2012-09-18 00:31:35,732 : INFO : topic #14: 0.019*愚笨 + 0.008*相依為命 + 0.008*邂逅 + 0.007*浪子 + 0.007*亮麗 + 0.006*厲害 + 0.006*徹底 + 0.006*惱河 + 0.006*冇掂過 + 0.006*製片家
2012-09-18 00:31:35,735 : INFO : topic #15: 0.015*冇掂過 + 0.009*奸黨 + 0.009*徹底 + 0.008*惱河 + 0.008*亮麗 + 0.006*眉飛色舞 + 0.006*交響 + 0.006*悽慘 + 0.005*沒收 + 0.005*厲害
2012-09-18 00:31:35,738 : INFO : topic #16: 0.017*愚笨 + 0.014*徹底 + 0.010*奸黨 + 0.007*亮麗 + 0.007*誤解 + 0.007*冇掂過 + 0.006*快慰 + 0.006*沒收 + 0.006*手震 + 0.005*木馬
2012-09-18 00:31:35,741 : INFO : topic #17: 0.009*亮麗 + 0.007*沒收 + 0.007*奸黨 + 0.007*徹底 + 0.006*替身 + 0.006*厲害 + 0.005*愛恨 + 0.005*待在 + 0.005*哪裡 + 0.005*刮起
2012-09-18 00:31:35,744 : INFO : topic #18: 0.008*愚笨 + 0.008*徹底 + 0.007*世情 + 0.007*沏 + 0.007*亮麗 + 0.007*邂逅 + 0.006*天黑 + 0.006*手震 + 0.006*玖零年代 + 0.006*替身
2012-09-18 00:31:35,747 : INFO : topic #19: 0.020*愚笨 + 0.019*誤解 + 0.016*冇掂過 + 0.012*徹底 + 0.012*惱河 + 0.009*天黑 + 0.009*奮鬥 + 0.008*沒收 + 0.008*亮麗 + 0.008*甲老
                  </pre>
                  <?php
                  echo nl2br($topic);
               } else {
                  $topic = shell_exec("python26 ".SITE_ROOT."/p-library/model/music_feature/lda_topic.py ".$lda_new_song_id." ".$_GET['lda_model']);
                  ?>
                  <pre>
2012-09-18 00:33:32,269 : INFO : initializing corpus reader from /var/www/html/lyrics-match/p-library/model/music_feature/20120917_lyrics_tfidf.mm
2012-09-18 00:33:32,297 : INFO : accepted corpus with 913 documents, 12684 features, 60108 non-zero entries
2012-09-18 00:33:32,527 : INFO : topic #0: 0.005*命途 + 0.003*冇掂過 + 0.003*超過 + 0.003*沏 + 0.003*徹底 + 0.003*惱河 + 0.003*邂逅 + 0.003*己經 + 0.003*歲數 + 0.003*愚笨
2012-09-18 00:33:32,534 : INFO : topic #1: 0.015*甲老 + 0.015*奮鬥 + 0.007*用錯 + 0.006*各自 + 0.005*奸黨 + 0.003*孤兒仔 + 0.003*天黑 + 0.003*矜貴 + 0.003*離歌 + 0.003*亮麗
2012-09-18 00:33:32,539 : INFO : topic #2: 0.004*理會 + 0.003*傷害 + 0.003*情事 + 0.003*妹 + 0.003*談論 + 0.003*唯美 + 0.003*乳果 + 0.003*怔怔 + 0.003*相依為命 + 0.003*酸苦
2012-09-18 00:33:32,542 : INFO : topic #3: 0.004*願不願意 + 0.004*亮麗 + 0.004*誤解 + 0.004*惱河 + 0.004*難度 + 0.004*沒收 + 0.004*消失 + 0.003*永生 + 0.003*替身 + 0.003*練拳
2012-09-18 00:33:32,545 : INFO : topic #4: 0.003*週到 + 0.003*下午 + 0.003*黃臉婆 + 0.002*夢迴 + 0.002*冇掂過 + 0.002*盡量 + 0.002*燐火 + 0.002*明日 + 0.002*不冀盼 + 0.002*天秤
2012-09-18 00:33:32,548 : INFO : topic #5: 0.006*軀體 + 0.005*愚笨 + 0.004*超過 + 0.004*過冷 + 0.004*惱河 + 0.004*麻木 + 0.003*沒收 + 0.003*守護神 + 0.003*亮麗 + 0.003*愛恨
2012-09-18 00:33:32,551 : INFO : topic #6: 0.005*妹 + 0.004*關顧 + 0.004*鬢 + 0.003*今夜 + 0.003*製片家 + 0.003*相依為命 + 0.003*浪子 + 0.003*慢吞吞 + 0.003*亮麗 + 0.003*惱河
2012-09-18 00:33:32,554 : INFO : topic #7: 0.004*愚笨 + 0.004*惱河 + 0.004*忠貞 + 0.004*按呢 + 0.003*夠多 + 0.003*俯瞰 + 0.003*童稚 + 0.003*冇掂過 + 0.003*亮麗 + 0.003*奸黨
2012-09-18 00:33:32,557 : INFO : topic #8: 0.004*不害怕 + 0.004*錯事 + 0.003*渲洩 + 0.003*錨 + 0.003*姆指 + 0.003*不回頭 + 0.003*不怎麼 + 0.003*危難處 + 0.003*不須要 + 0.003*洗淨
2012-09-18 00:33:32,560 : INFO : topic #9: 0.005*愚笨 + 0.004*乖 + 0.004*齊齊 + 0.003*沒收 + 0.003*呦哼 + 0.003*不易 + 0.003*勇敢 + 0.003*感激 + 0.003*村莊 + 0.003*斬首
2012-09-18 00:33:32,562 : INFO : topic #10: 0.004*陰霾 + 0.004*遙遙 + 0.004*安好 + 0.004*在一起 + 0.004*原 + 0.003*悽慘 + 0.003*榜樣 + 0.003*時光 + 0.003*流浪漢 + 0.003*逛逛
2012-09-18 00:33:32,565 : INFO : topic #11: 0.004*茫茫 + 0.003*不被 + 0.003*悲 + 0.003*誤解 + 0.003*徹底 + 0.002*說說話 + 0.002*無比 + 0.002*冇掂過 + 0.002*總易 + 0.002*曲終人散
2012-09-18 00:33:32,568 : INFO : topic #12: 0.004*邂逅 + 0.004*天份 + 0.004*依舊 + 0.003*世情 + 0.003*玖零年代 + 0.003*兇鈴 + 0.003*手震 + 0.003*好比 + 0.003*但見 + 0.003*洶湧
2012-09-18 00:33:32,571 : INFO : topic #13: 0.004*顯眼 + 0.004*痕跡 + 0.003*永生 + 0.003*即 + 0.003*夠多 + 0.003*裝飾品 + 0.003*冇掂過 + 0.003*不心痛 + 0.003*愚笨 + 0.003*男歡女愛
2012-09-18 00:33:32,574 : INFO : topic #14: 0.007*淌 + 0.005*企鵝 + 0.004*緊握 + 0.004*職業 + 0.003*攤位 + 0.003*難題 + 0.003*逼 + 0.003*歹逗陣 + 0.003*快活 + 0.003*形狀
2012-09-18 00:33:32,577 : INFO : topic #15: 0.009*溜冰 + 0.006*一咧 + 0.005*待在 + 0.005*受不住 + 0.004*愚笨 + 0.004*歇 + 0.004*深潭 + 0.003*亮麗 + 0.003*縱洗 + 0.003*埋首
2012-09-18 00:33:32,580 : INFO : topic #16: 0.008*脫下 + 0.005*愚笨 + 0.005*成佛 + 0.004*回想起 + 0.004*志願 + 0.004*時鐘 + 0.004*徹底 + 0.003*最後 + 0.003*夠多 + 0.003*邂逅
2012-09-18 00:33:32,583 : INFO : topic #17: 0.005*周末 + 0.004*屋簷 + 0.004*狂飲 + 0.004*唯美 + 0.003*替身 + 0.003*徹底 + 0.003*無暇 + 0.003*吹過 + 0.003*沏 + 0.003*怔怔
2012-09-18 00:33:32,586 : INFO : topic #18: 0.006*患難 + 0.005*眉飛色舞 + 0.005*過份 + 0.004*女子 + 0.004*媽 + 0.003*方可襯 + 0.003*火星 + 0.003*封閉 + 0.003*寂寂寞寞 + 0.003*焉
2012-09-18 00:33:32,588 : INFO : topic #19: 0.008*財物 + 0.005*熱烈 + 0.005*刮起 + 0.004*筆跡 + 0.003*喜新厭舊 + 0.003*告解 + 0.003*財神 + 0.003*職業 + 0.003*破損 + 0.003*徹底
                  </pre>
                  <?php
                  echo nl2br($topic);
               }

               ?>
            </td>
         </tr>
      </tbody>
   </table>
   <hr />
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



         if (isset($_GET['lda_model']) && !empty($_GET['lda_model'])) {

            if ($_GET['lda_model']=='tf_lda') {
               $similar_lyrics = shell_exec("python26 ".SITE_ROOT."/p-library/model/music_feature/similar_lyrics_model_tf_lda.py ".$_GET['song_id']." ");
            } else {
               $similar_lyrics = shell_exec("python26 ".SITE_ROOT."/p-library/model/music_feature/similar_lyrics_model_lda.py ".$_GET['song_id']." ");
            }
            //echo $similar_lyrics;
            $similar_lyrics_array = explode(",", $similar_lyrics);
            $rank = 0;
            foreach ($similar_lyrics_array as $skey => $svalue) {
               $rank++;
               $similar_song_value_array = explode(":", $svalue);
               $new_song_id = $similar_song_value_array[0];

               $select_sql = "SELECT song_id FROM lyrics_term_tfidf_continue WHERE new_song_id = $new_song_id GROUP BY song_id LIMIT 1";

               $query_result = $db_obj->selectCommand($select_sql);

               foreach ($query_result as $query_result_data) {

                  $song_id = $query_result_data['song_id'];
                  $similar_song_obj = new LMSong($song_id);
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

            }

         } else {

            $select_sql = "SELECT similar_song_id,similar FROM similar_song WHERE song_id='".$_GET['song_id']."' AND model='lyrics-model-".$lmodel_id.".txt' ORDER BY similar DESC";

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

         }

        ?>
        </tbody>
   </table>
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
                     categoryAxis.autoGridCount = false;
                     categoryAxis.gridCount = 100000;

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
                     categoryAxis.autoGridCount = false;
                     categoryAxis.gridCount = 100000;

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
   <hr />
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
</div>
<?php
   unset($song_obj);
   unset($disc_obj);
   unset($performer_obj);
   unset($lyricist_obj);
   unset($composer_obj);
}
?>
