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

?>
<div id="p-modal" class="modal hide fade" style="width:<?php echo htmlspecialchars($size); ?>;display: none; ">
   <div class="modal-header">
      <h3>
         <?=$song_obj->title?>
      </h3>
   </div>
   <div class="modal-body">
      <h4>
         音樂合成試聽
      </h4>
      <p id="audioplayer">Load Song</p>
      <script type="text/javascript">
      AudioPlayer.embed("audioplayer", {
          soundFile: "http://sarasti.cs.nccu.edu.tw/lyrics-match/p-data/mp3/1.mp3",
          titles: "<?=$song_obj->title?>",
          artists: "若天依",
          autostart: "no"
      });
      </script>
      <hr/>
      <h4>
         歌詞
      </h4>
      <p style="width:480px;height:300px;overflow:auto;">
         <?=nl2br($song_obj->lyric)?>
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

      })

   });// end $('#p-modal').ready
</script>