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
      編輯歌詞分段
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
   <div class="row">
      <div class="flow-left" style="width:500px;">
         <h3>歌詞分行</h3>
         <?php
         $lyrics_line_array = explode("\n", $song_obj->lyric);
         ?>
         <p>
         <?php
         foreach ($lyrics_line_array as $line_index => $line_value) {
            echo ($line_index+1).'. '.$line_value.'<br>';
         }
         ?>
         </p>
      </div>
      <div class="flow-left" style="width:470px;margin-left:30px;">
         <h3>歌詞分段</h3>
         <div id="add-block-form" class="margin-top-1">
            <?php
            $query_lyrics_block = $song_obj->getLyricsBlockTruth();
            foreach ($query_lyrics_block as $query_lyrics_block_data) {
               $song_lyrics_block_id = $query_lyrics_block_data['id'];
               require SITE_ROOT."/ajax-action/SongActionView/song-lyrics-block-form.php";
            }
            ?>
         </div>
         <button id="add-block-btn" class="btn btn-primary margin-top-1">
            新增分段
         </button>
      </div>
   </div>
</div>
<script>
$('#song-item-block').ready(function() {

   $('button#add-block-btn').click(function(){

      $.ajax({
         url: '<?=SITE_HOST?>/ajax-action/song-action/add-lyric-block',
         type: "POST",
         data: {song_id: "<?=$song_obj->getId();?>"},
         dataType: "html",
         beforeSend: function( xhr ) {
            $('#system-message').html('處理中');
            $('#system-message').show();
         },
         success: function( html_block ) {
            $('#add-block-form').append(html_block);
            $('#system-message').html('完成');
            $('#system-message').fadeOut();
         }
      });

   });


});
</script>
<?php
   unset($song_obj);
   unset($disc_obj);
   unset($performer_obj);
   unset($lyricist_obj);
   unset($composer_obj);
}
?>