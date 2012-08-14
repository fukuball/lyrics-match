<div class="page-header align-center" style="padding-top: 28px;padding-bottom: 28px;background-color: whiteSmoke; margin: 0px auto 18px auto;">
   <h1>Lyrics Recommendation By Song</h1>
</div>
<br class="clearboth" />
<div id="midi-upload-block" class="row well" style="width: 400px; margin: 10px auto;">
   <div style="width: 120px; margin: 0px auto;">
      <a id="pick-midi-file">
         <button type="submit" class="btn btn-primary start">
            <i class="icon-upload icon-white"></i>
            <span>
               開始上傳
            </span>
         </button>
      </a>
   </div>
</div>
<div style="width:470px;margin: 10px auto;">
   <div class="progress progress-success progress-striped margin-all hide" style="width: 450px;">
      <div class="bar" style="width: 0%"></div>
   </div>
</div>
<br class="clearboth" />
<div style="width: 800px; margin: 20px auto;">
   <img width="800" src="<?=SITE_HOST?>/p-asset/image/ui-icon/banner.png" />
</div>
<br class="clearboth" />
<script>

   var audio_uploader = new plupload.Uploader({
      runtimes : 'html5,flash,gears,silverlight,browserplus',
      browse_button : 'pick-midi-file',
      container: 'midi-upload-block',
      max_file_size : '100mb',
      chunk_size : '1mb',
      url : '<?=SITE_HOST?>/ajax-action/user-action/user-upload',
      flash_swf_url : '<?=SITE_HOST?>/p-library/plupload/js/plupload.flash.swf',
      silverlight_xap_url : '<?=SITE_HOST?>/p-library/plupload/js/plupload.silverlight.xap',
      multiple_queues : false,
      multi_selection : false,
      max_file_count : 1,
      multipart : true,
      multipart_params : {song_id: '<?php echo $song_id; ?>'},
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

               $('#system-message').html('完成');
               $('#system-message').fadeOut();

            } else {
               $('#system-message').fadeOut();
               $('.progress .bar').css('width' , '0%');
               $.ajax({
                  url: '<?=SITE_HOST?>/ajax-action/box-action/alert-no-licence',
                  type: "GET",
                  data: {},
                  dataType: "html",
                  beforeSend: function( xhr ) {
                  },
                  success: function( html_block ) {
                     $('#p-modal-block').html(html_block);
                  }
               });

            }

         }
      }
   });

   audio_uploader.init();

</script>