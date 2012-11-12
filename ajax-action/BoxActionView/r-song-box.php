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

if ($original_song_id) {

   $url = "http://sarasti.cs.nccu.edu.tw/lyrics-match/p-data/demo/".$song_id.".mp3";
   $has_file = true;

} else {

   $mp3_path = $_GET['mp3_path'];
   $url = $mp3_path;
   $has_file = $_GET['has_file'];
}

$short_url_json = json_decode(shell_exec("curl https://www.googleapis.com/urlshortener/v1/url -H 'Content-Type: application/json' -d "."'{".'"longUrl"'.": ".'"'.$url.'"'."}'"));
$message = "新詞配舊曲連結：".$short_url_json->id;
//$message = "連結：".$url;
?>
<div id="p-modal" class="modal hide fade" style="height:500px;width:<?php echo htmlspecialchars($size); ?>;display: none;overflow:none;">
   <div class="modal-header">
      <h3>
         <?=$r_title?>
      </h3>
   </div>
   <div class="modal-body">
      <?php
      if ($has_file) {
      ?>
      <h4 style="margin-bottom:10px;">
         Music synthesizer audition
      </h4>
      <div class="row">
         <p id="audioplayer" class="pull-left">Load Song</p>
         <script type="text/javascript">
         AudioPlayer.embed("audioplayer", {
             soundFile: "<?=$url?>",
             titles: "<?=$r_title?>",
             artists: "若天依",
             autostart: "no"
         });
         </script>
         <p class="pull-right">
            <button id="send-sms-btn" data-message="<?=$message?>" class="btn btn-primary">
               <i class="icon-share icon-white"></i>
               <span>
                  SMS Share
               </span>
            </button>
         </p>
      </div>
      <?php
      } else {
         echo '<br/><br/><br/>';
      }
      ?>
      <hr/>
      <p style="width:480px;height:270px;overflow:auto;text-align:center;line-height:25px;">
         <?=nl2br($r_lyric)?>
      </p>
   </div>
   <div class="modal-footer align-center">
      <button id="alert-licence-close" type="button" class="btn" data-dismiss="modal">
         Close
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