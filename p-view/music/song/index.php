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
   <h2>音樂特徵值</h2>
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th>Feature</th>
            <th>Value</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td>echonest_track_id</td>
            <td><?=$song_obj->echonest_track_id?></td>
         </tr>
         <tr>
            <td>key</td>
            <td><?=$song_obj->key?></td>
         </tr>
         <tr>
            <td>mode</td>
            <td><?=$song_obj->mode?></td>
         </tr>
         <tr>
            <td>tempo</td>
            <td><?=$song_obj->tempo?></td>
         </tr>
         <tr>
            <td>time_signature</td>
            <td><?=$song_obj->time_signature?></td>
         </tr>
         <tr>
            <td>energy</td>
            <td><?=$song_obj->energy?></td>
         </tr>
         <tr>
            <td>danceability</td>
            <td><?=$song_obj->danceability?></td>
         </tr>
         <tr>
            <td>speechiness</td>
            <td><?=$song_obj->speechiness?></td>
         </tr>
         <tr>
            <td>loudness</td>
            <td><?=$song_obj->loudness?></td>
         </tr>
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