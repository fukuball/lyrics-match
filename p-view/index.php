<div class="page-header padding-all align-center">
   <h1>Lyrics Match - Please Upload Your Music Work!</h1>
</div>
<br class="clearboth" />
<div id="midi-upload-block" class="row well" style="width: 400px; margin: 10px auto;">
   <div style="width: 120px; margin: 0px auto;">
      <a id="pick-midi-file">
         <button type="submit" class="btn btn-primary start">
            <i class="icon-upload icon-white"></i>
            <span>Start upload</span>
         </button>
      </a>
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
      max_file_size : '10mb',
      url : 'upload.php',
      flash_swf_url : '<?=SITE_HOST?>/p-library/plupload/js/plupload.flash.swf',
      silverlight_xap_url : '<?=SITE_HOST?>/p-library/plupload/js/plupload.silverlight.xap',
      filters : [
         { title : "audio (.mp3, .midi, .mid)", extensions : "mp3,midi,mid" }
      ]
   });

   uploader.bind('Init', function(up, params) {
     //$('filelist').innerHTML = "<div>Current runtime: " + params.runtime + "</div>";
   });

   uploader.bind('FilesAdded', function(up, files) {
     alert('added');
     /*for (var i in files) {
       $('filelist').innerHTML += '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
     }*/
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