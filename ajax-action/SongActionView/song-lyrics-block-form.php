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

?>
<div class="song-lyrics-block-form-item">
   <input type="text" class="input-medium" placeholder="用,分隔，比如：1,5" />
   <select>
      <option value="0">
         請選擇分段類型
      </option>
      <?php
      $lyrics_block_label_result = LMLyricsBlockTruthGod::getLyricsBlockLabel();
      foreach ($lyrics_block_label_result as $lyrics_block_label_result_data) {
         $lyrics_block_label_id = $lyrics_block_label_result_data['id'];
         $lyrics_block_label_cname = $lyrics_block_label_result_data['c_name'];
         ?>
         <option value="<?=$lyrics_block_label_id?>"><?=$lyrics_block_label_cname?></option>
         <?php
      }
      ?>
   </select>
   <button class="btn save-lyrics-block-btn">
      儲存
   </button>
   <button class="btn delete-lyrics-block-btn">
      刪除
   </button>
</div>