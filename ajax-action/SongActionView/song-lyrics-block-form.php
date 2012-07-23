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
<div class="song-lyrics-block-form-item">
   <input id="song-lyrics-song-id-<?=$song_lyrics_block_id?>" type="hidden" value="<?=$lyrics_block_truth->song_id?>" />
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
   <button class="btn save-lyrics-block-btn">
      儲存
   </button>
   <button class="btn delete-lyrics-block-btn">
      刪除
   </button>
</div>
<?php
unset($lyrics_block_truth);
?>