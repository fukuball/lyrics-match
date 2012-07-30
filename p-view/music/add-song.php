<?php
/**
 * add-song.php is the /music/add-song.php content
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
?>
<form id="check-add-song-form" name="check_add_song_form" class="well form-inline">
   <input id="check-song-kkbox-url" name="check_song_kkbox_url" type="text" class="input-medium" placeholder="請輸入歌曲的kkbox網址">
   <button id="check-add-song-btn" type="button" class="btn">
      新增歌曲
   </button>
</form>
<div id="input-invalid-warnig" class="alert hide">
  <!--<button class="close" data-dismiss="alert">×</button>-->
  <strong>Warning!</strong> 輸入資料不可為空
</div>
<div id="song-exist-warnig" class="alert hide">
  <!--<button class="close" data-dismiss="alert">×</button>-->
  <strong>Warning!</strong> 此歌曲已存在，請增加其他歌曲
</div>
<script>
$('#check-add-song-form').ready(function() {

   $('button#check-add-song-btn').click(function(){

      var is_validated = true;
      if(!$('#check-song-kkbox-url').val()){
         $('#input-invalid-warnig').removeClass('hide');
         is_validated = false;
      } else {
         $('#input-invalid-warnig').removeClass('hide');
         $('#input-invalid-warnig').addClass('hide');
      }

      if(is_validated){
         $.ajax({
            url: '<?=SITE_HOST?>/ajax-action/song-action/check-add-song',
            type: "POST",
            data: {check_song_kkbox_url: $('#check-song-kkbox-url').val()},
            dataType: "html",
            beforeSend: function( xhr ) {
               $('#system-message').html('處理中');
               $('#system-message').show();
            },
            success: function( html_block ) {
               $('#system-message').html('完成');
               $('#system-message').fadeOut();
               if (html_block=="song_exist") {
                  $('#song-exist-warnig').removeClass('hide');
               } else {
                  $('#song-exist-warnig').removeClass('hide');
                  $('#song-exist-warnig').addClass('hide');
                  $('#add-song-form').html(html_block);
               }

            }
         });
      }

   });


});
</script>
<hr />
<div id="add-song-form">
</div>