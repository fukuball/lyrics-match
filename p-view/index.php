<div class="page-header align-center" style="padding-top: 28px;padding-bottom: 28px;background-color: whiteSmoke;">
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
      <br/>
      <div class="progress progress-success progress-striped margin-all hide" style="width: 450px;">
         <div class="bar" style="width: 0%"></div>
      </div>
   </div>
</div>
<br class="clearboth" />
<div style="width: 800px; margin: 20px auto;">
   <img width="800" src="<?=SITE_HOST?>/p-asset/image/ui-icon/banner.png" />
</div>
<br class="clearboth" />
<script>
   var uploader = new plupload.Uploader({
      runtimes : 'gears,html5,flash,silverlight,browserplus',
      browse_button : 'pick-midi-file',
      multi_selection: false,
      container: 'midi-upload-block',
      max_file_size : '100mb',
      url : '<?=SITE_HOST?>/ajax-action/user-action/user-upload',
      flash_swf_url : '<?=SITE_HOST?>/p-library/plupload/js/plupload.flash.swf',
      silverlight_xap_url : '<?=SITE_HOST?>/p-library/plupload/js/plupload.silverlight.xap'
      /*filters : [
         {title : "Image files", extensions : "jpg,gif,png"}
      ]*/
   });

   uploader.bind('Init', function(up, params) {
     //$('filelist').innerHTML = "<div>Current runtime: " + params.runtime + "</div>";
   });

   uploader.bind('FilesAdded', function(up, files) {
      $('.progress').removeClass('hide');
      up.start();
      $('#system-message').html('處理中...');
      $('#system-message').show();
     //alert('added');
     /*for (var i in files) {
       $('filelist').innerHTML += '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
     }*/
   });

   uploader.bind('FileUploaded', function(up, file, resp) {
      var responseText = $.parseJSON(resp.response);
      console.log(responseText);
      if(responseText.response.status.code==0){

         $('#system-message').html('完成');
         $('#system-message').fadeOut();

      } else {

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
   });

   uploader.bind('UploadProgress', function(up, file) {
     //$(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
   });

   $('uploadfiles').onclick = function() {
     //uploader.start();
     //return false;
   };

   uploader.init();
</script>