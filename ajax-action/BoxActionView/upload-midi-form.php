<?php
/**
 * upload-midi-form.php is the view of upload midi form
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
         Upload Midi File
      </h3>
   </div>
   <div class="modal-body align-center">
      <a id="upload-midi-button" href="javascript:;" style="width: 100px;">
         <button type="button" class="btn btn-primary start">
            <i class="icon-upload icon-white"></i>
            <span>Start Upload</span>
         </button>
      </a>
      <br/>
      <div class="progress progress-success progress-striped margin-all hide" style="width: 450px;">
         <div class="bar" style="width: 0%"></div>
      </div>
   </div>
   <div class="modal-footer align-center">
      <button id="upload-midi-cancel" type="button" class="btn" data-dismiss="modal">
         Cancel
      </button>
   </div>
</div>
<script>
   $('#p-modal').ready(function() {

      $('#p-modal').modal('show');

      $('#p-modal').on('hidden', function () {

         $('#p-modal-block').empty();

      });

      var audio_uploader = new plupload.Uploader({
         runtimes : 'html5,flash,gears,silverlight,browserplus',
         browse_button : 'upload-midi-button',
         container: 'p-modal',
         max_file_size : '100mb',
         chunk_size : '1mb',
         url : '<?=SITE_HOST?>/ajax-action/song-action/upload-midi',
         flash_swf_url : '<?=SITE_HOST?>/p-library/plupload/js/plupload.flash.swf',
         silverlight_xap_url : '<?=SITE_HOST?>/p-library/plupload/js/plupload.silverlight.xap',
         multiple_queues : false,
         multi_selection : false,
         max_file_count : 1,
         multipart : true,
         multipart_params : {song_id: '<?php echo $song_id; ?>'},
         filters : [
            {title : "Audio files", extensions : ""}
         ],
         init : {
            FilesAdded: function(up, files) {
               $('.progress').removeClass('hide');
               up.start();
               $('#system-message').html('處理中...');
               $('#system-message').show();
            },
            BeforeUpload: function (up, file) {
            },
            UploadProgress: function(up, file) {
               $('.progress .bar').css('width' , file.percent+'%');
            },
            UploadComplete: function(up, files) {
               $('.progress').addClass('hide').delay(500);
               $.each(files, function(i, file) {
                  // Do stuff with the file. There will only be one file as it uploaded straight after adding!
               });
            },
            FileUploaded: function(up, file, resp) {

               var responseText = $.parseJSON(resp.response);
               console.log(responseText);
               if(responseText.response.status.code==0){

                  $.ajax({
                     url: '<?=SITE_HOST?>/ajax-action/song-action/get-song-audio-td',
                     type: "GET",
                     data: {song_id : '<?php echo $song_id; ?>'},
                     dataType: "html",
                     beforeSend: function( xhr ) {
                     },
                     success: function( html_block ) {
                        $('#song-td-audio-<?php echo $song_id; ?>').html(html_block);
                     }
                  });

                  $('#p-modal').modal('hide');

                  $('#system-message').html('完成');
                  $('#system-message').fadeOut();

               } else {

                  $('#system-message').html('失敗，請重新操作');
                  $('#system-message').fadeOut();

               }

            }
         }
      });

      audio_uploader.init();

   });// end $('#p-modal').ready
</script>