<?php
/**
 * upload-audio-form.php is the view of upload audio form
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
         Upload Audio File
      </h3>
   </div>
   <form id="upload-audio-form" name="upload_audio_form" action="/ajax-action/song-action/upload-audio" method="post">
      <div class="modal-body">
      </div>
      <div class="modal-footer align-center">
         <button id="upload-audio-cancel" type="button" class="btn" data-dismiss="modal">
            Cancel
         </button>
      </div>
   </form>
</div>
<script>
   $('#p-modal').ready(function() {

      $('#p-modal').modal('show');

      $('#p-modal').on('hidden', function () {

         $('#p-modal-block').empty();

      });

   });// end $('#p-modal').ready
</script>