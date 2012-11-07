<?php
/**
 * add-song-form.php is the add song form
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
<form id="add-song-post-form" class="form-horizontal">
   <fieldset>
      <div class="control-group">
         <label class="control-label" for="artist-name">
            藝人名稱
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="artist-name" name="artist_name" value="<?=$in_performer_name?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="artist-kkbox-url">
            藝人 kkbox 網址
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="artist-kkbox-url" name="artist_kkbox_url" value="<?=$in_performer_url?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="disc-title">
            專輯名稱
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="disc-title" name="disc_title" value="<?=$in_disc_name?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="disc-kkbox-url">
            專輯 kkbox 網址
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="disc-kkbox-url" name="disc_kkbox_url" value="<?=$in_disc_url?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="genre">
            音樂類型
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="genre" name="genre" value="<?=$in_disc_genre?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="release-date">
            發行日期
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="release-date" name="release_date" value="<?=$in_disc_release?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="disc-cover">
            專輯封面網址
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="disc-cover" name="disc_cover" value="<?=$in_disc_src?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="song-title">
            歌曲名稱
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="song-title" name="song_title" value="<?=$in_song_name?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="song-kkbox-url">
            歌曲 kkbox 網址
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="song-kkbox-url" name="song_kkbox_url" value="<?=$song_kkbox_url?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="lyricist">
            作詞
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="lyricist" name="lyricist" value="<?=$in_lyricist_name?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="composer">
            作曲
         </label>
         <div class="controls">
            <input type="text" class="input-xlarge span7" id="composer" name="composer" value="<?=$in_composer_name?>" />
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="lyric">
            歌詞
         </label>
         <div class="controls">
           <textarea class="input-xlarge span7" id="lyric" name="lyric" rows="15"><?=$in_lyric?></textarea>
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="have-english">
            是否有英文
         </label>
         <div class="controls">
            <input type="checkbox" id="have-english" name="have_english" value="1">
         </div>
      </div>
      <div class="control-group">
         <label class="control-label" for="only-english">
            只包含英文
         </label>
         <div class="controls">
            <input type="checkbox" id="only-english" name="only_english" value="1">
         </div>
      </div>
      <div class="form-actions">
         <button type="submit" class="btn btn-primary">
            儲存歌曲
         </button>
      </div>
   </fieldset>
</form>
<script>

   $(function() {

      $('#add-song-post-form').ajaxForm({
         beforeSubmit:  validateAddSongRequest,
         success:       addSongResponse,
         url: '<?=SITE_HOST?>/ajax-action/song-action/add-song',
         type: 'post',
         dataType: 'json'
      });

      function validateAddSongRequest(formData, jqForm, options) {

         var is_validated = true;

         if(!$('#artist-name').val()){
            $('#artist-name').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#artist-name').parent().parent().attr('class', 'control-group');
         }
         if(!$('#artist-kkbox-url').val()){
            $('#artist-kkbox-url').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#artist-kkbox-url').parent().parent().attr('class', 'control-group');
         }
         if(!$('#disc-title').val()){
            $('#disc-title').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#disc-title').parent().parent().attr('class', 'control-group');
         }
         if(!$('#disc-kkbox-url').val()){
            $('#disc-kkbox-url').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#disc-kkbox-url').parent().parent().attr('class', 'control-group');
         }
         if(!$('#genre').val()){
            $('#genre').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#genre').parent().parent().attr('class', 'control-group');
         }
         if(!$('#release-date').val()){
            $('#release-date').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#release-date').parent().parent().attr('class', 'control-group');
         }
         if(!$('#disc-cover').val()){
            $('#disc-cover').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#disc-cover').parent().parent().attr('class', 'control-group');
         }
         if(!$('#song-title').val()){
            $('#song-title').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#song-title').parent().parent().attr('class', 'control-group');
         }
         if(!$('#song-kkbox-url').val()){
            $('#song-kkbox-url').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#song-kkbox-url').parent().parent().attr('class', 'control-group');
         }
         if(!$('#lyricist').val()){
            $('#lyricist').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#lyricist').parent().parent().attr('class', 'control-group');
         }
         if(!$('#composer').val()){
            $('#composer').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#composer').parent().parent().attr('class', 'control-group');
         }
         if(!$('#lyric').val()){
            $('#lyric').parent().parent().attr('class', 'control-group error');
            is_validated = false;
         } else {
            $('#lyric').parent().parent().attr('class', 'control-group');
         }

         if(is_validated){
            $('#system-message').html('處理中...');
            $('#system-message').show();
         }
         return is_validated;
      }

      function addSongResponse(responseText, statusText, xhr, $form)  {

         if(responseText.response.status.code==0){
            $('#system-message').html('完成');
            $('#system-message').fadeOut();
            $('#add-song-form').html('');
         } else {
            $('#system-message').html('失敗，請重新操作');
            $('#system-message').fadeOut();
         }

      }

   });
</script>
