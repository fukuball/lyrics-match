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
            <td rowspan="2">音高及音程</td>
            <td>average pitch vector</td>
            <td><?=($music_feature_obj->pitch_avg_vector)?></td>
         </tr>
         <tr>
            <td>variance of pitch vector</td>
            <td><?=($music_feature_obj->pitch_std_vector)?></td>
         </tr>
         <tr>
            <td rowspan="3">音色</td>
            <td>speechiness</td>
            <td><?=$song_obj->speechiness?></td>
         </tr>
         <tr>
            <td>average timbre vector</td>
            <td><?=$song_obj->timbre_avg_vector?></td>
         </tr>
         <tr>
            <td>variance of timbre vector</td>
            <td><?=$song_obj->timbre_std_vector?></td>
         </tr>
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