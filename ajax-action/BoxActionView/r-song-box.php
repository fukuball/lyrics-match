<?php
/**
 * alert-no-licence.php is the view of alert-no-licence
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /ajax-action/BoxActionView/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

$url = "http://sarasti.cs.nccu.edu.tw/lyrics-match/p-data/mp3/1.mp3";

$short_url_json = shell_exec("curl https://www.googleapis.com/urlshortener/v1/url -H 'Content-Type: application/json' -d '{\"longUrl\": \"".$url."\"}'");
echo $short_url_json;
$message = "我發現「".$song_r_obj->title."」歌詞可以配唱「".$song_o_obj->title."」歌曲，你覺得好聽嗎？ 連結：".$url;
$message = "連結：".$url;
?>
<div id="p-modal" class="modal hide fade" style="width:<?php echo htmlspecialchars($size); ?>;display: none; ">
   <div class="modal-header">
      <h3>
         <?=$song_r_obj->title?>
      </h3>
   </div>
   <div class="modal-body">
      <h4 style="margin-bottom:10px;">
         音樂合成試聽
      </h4>
      <div class="row">
         <p id="audioplayer" class="pull-left">Load Song</p>
         <script type="text/javascript">
         AudioPlayer.embed("audioplayer", {
             soundFile: "<?=$url?>",
             titles: "<?=$song_r_obj->title?>",
             artists: "若天依",
             autostart: "no"
         });
         </script>
         <p class="pull-right">
            <button id="send-sms-btn" data-message="<?=$message?>" class="btn btn-primary">
               <i class="icon-share icon-white"></i>
               <span>
                  簡訊分享
               </span>
            </button>
         </p>
      </div>
      <hr/>
      <h4 style="margin-bottom:10px;">
         歌詞
      </h4>
      <p style="width:480px;height:150px;overflow:auto;text-align:center;line-height:25px;">
         <?=nl2br($song_r_obj->lyric)?>
      </p>
   </div>
   <div class="modal-footer align-center">
      <button id="alert-licence-close" type="button" class="btn" data-dismiss="modal">
         關閉
      </button>
   </div>
</div>
<script>
   $('#p-modal').ready(function() {

      $('#p-modal').modal('show');

      $('#p-modal').on('hidden', function () {

         $('#p-modal-block').empty();

      });

      $('#send-sms-btn').on('click', function () {

          $('#p-modal').modal('hide');

          $.ajax({
             url: '<?=SITE_HOST?>/ajax-action/box-action/send-sms-box',
             type: "GET",
             data: {message: $(this).attr("data-message")},
             dataType: "html",
             beforeSend: function( xhr ) {
             },
             success: function( html_block ) {
                $('#p-modal-block').html(html_block);
             }
          });

      });

   });// end $('#p-modal').ready
</script>