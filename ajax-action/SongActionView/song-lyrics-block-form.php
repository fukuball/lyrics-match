<?php
/**
 * song-lyrics-block-form.php is the song lyrics block content
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /ajax-action/SongActionView
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

$song_lyrics_block_id = $song_lyrics_block_id;
$lyrics_block_truth = new LMLyricsBlockTruth($song_lyrics_block_id);
?>
<div id="song-lyrics-block-form-item-<?=$song_lyrics_block_id?>">
   <input id="song-lyrics-block-truth-id-<?=$song_lyrics_block_id?>" type="hidden" value="<?=$song_lyrics_block_id?>" />
   <input id="song-lyrics-block-line-<?=$song_lyrics_block_id?>" type="text" class="input-medium" placeholder="用,分隔，比如：1,5" value="<?=$lyrics_block_truth->block?>" />
   <select id="song-lyrics-block-lable-<?=$song_lyrics_block_id?>">
      <option value="0">
         請選擇分段類型
      </option>
      <?php
      $lyrics_block_truth_god_obj = new LMLyricsBlockTruthGod();
      $lyrics_block_label_result = $lyrics_block_truth_god_obj->getLyricsBlockLabel();
      foreach ($lyrics_block_label_result as $lyrics_block_label_result_data) {
         $lyrics_block_label_id = $lyrics_block_label_result_data['id'];
         $lyrics_block_label_cname = $lyrics_block_label_result_data['c_name'];
         $is_selected = "";
         if ($lyrics_block_label_id==$lyrics_block_truth->label_id) {
            $is_selected = 'selected="selected"';
         }
         ?>
         <option value="<?=$lyrics_block_label_id?>" <?=$is_selected?>><?=$lyrics_block_label_cname?></option>
         <?php
      }
      unset($lyrics_block_truth_god_obj);
      ?>
   </select>
   <button id="save-lyrics-block-btn-<?=$song_lyrics_block_id?>" class="btn">
      儲存
   </button>
   <button id="delete-lyrics-block-btn-<?=$song_lyrics_block_id?>" class="btn">
      刪除
   </button>
</div>
<?php
unset($lyrics_block_truth);
?>
<script>
$('button#save-lyrics-block-btn-<?=$song_lyrics_block_id?>').click(function(){

   var lyrics_block_truth_id = $('#song-lyrics-block-truth-id-<?=$song_lyrics_block_id?>').val();
   var block = $('#song-lyrics-block-line-<?=$song_lyrics_block_id?>').val();
   var label_id = $('#song-lyrics-block-lable-<?=$song_lyrics_block_id?>').val();

   $.ajax({
      url: '<?=SITE_HOST?>/ajax-action/song-action/save-lyric-block',
      type: "POST",
      data: {lyrics_block_truth_id: lyrics_block_truth_id, block: block, label_id: label_id},
      dataType: "json",
      beforeSend: function( xhr ) {
         $('button#save-lyrics-block-btn-<?=$song_lyrics_block_id?>').attr("disabled", "disabled");
         $('button#delete-lyrics-block-btn-<?=$song_lyrics_block_id?>').attr("disabled", "disabled");
         $('#system-message').html('處理中');
         $('#system-message').show();
      },
      success: function( json_data ) {
         if(json_data.response.status.code==0){
            $('#system-message').html('完成');
            $('#system-message').fadeOut();
         } else {
            $('#system-message').html('失敗，請重新操作');
            $('#system-message').fadeOut();
         }
         $('button#save-lyrics-block-btn-<?=$song_lyrics_block_id?>').removeAttr("disabled");
         $('button#delete-lyrics-block-btn-<?=$song_lyrics_block_id?>').removeAttr("disabled");
      }
   });

});

$('button#delete-lyrics-block-btn-<?=$song_lyrics_block_id?>').click(function(){

   var lyrics_block_truth_id = $('#song-lyrics-block-truth-id-<?=$song_lyrics_block_id?>').val();

   $.ajax({
      url: '<?=SITE_HOST?>/ajax-action/song-action/delete-lyric-block',
      type: "POST",
      data: {lyrics_block_truth_id: lyrics_block_truth_id},
      dataType: "json",
      beforeSend: function( xhr ) {
         $('button#save-lyrics-block-btn-<?=$song_lyrics_block_id?>').attr("disabled", "disabled");
         $('button#delete-lyrics-block-btn-<?=$song_lyrics_block_id?>').attr("disabled", "disabled");
         $('#system-message').html('處理中');
         $('#system-message').show();
      },
      success: function( json_data ) {
         if(json_data.response.status.code==0){
            $('#system-message').html('完成');
            $('#system-message').fadeOut();
            $('#song-lyrics-block-form-item-<?=$song_lyrics_block_id?>').remove();
         } else {
            $('#system-message').html('失敗，請重新操作');
            $('#system-message').fadeOut();
         }
         $('button#save-lyrics-block-btn-<?=$song_lyrics_block_id?>').removeAttr("disabled");
         $('button#delete-lyrics-block-btn-<?=$song_lyrics_block_id?>').removeAttr("disabled");
      }
   });

});
</script>