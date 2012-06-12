<script>
   // start home upload
   var uploader = new plupload.Uploader({
      runtimes : 'gears,html5,flash,silverlight,browserplus',
      browse_button : 'pick-midi-file',
      multi_selection: false,
      container: 'midi-upload-block',
      max_file_size : '10mb',
      url : 'upload.php',
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
   // end home upload

   // start song load more
   var song_offset = 0;
   function moreSong() {

      var action_url = '<?=SITE_HOST?>/ajax-action/index.php/song-action/song-list';
      song_offset = song_offset+30;

      if ($('#song-show-more').length != 0) {
         $.ajax({
            url: action_url,
            type: "GET",
            data: {offset : song_offset, length : $("#song-show-more").attr("data-length")},
            dataType: "html",
            beforeSend: function( xhr ) {
               $('#system-message').html('處理中...');
               $('#system-message').show();
               $(window).off('scroll.song_show_more');
            },
            success: function( html_block ) {
               $('#song-list-tbody').append(html_block);
               $('#system-message').html('完成');
               $('#system-message').fadeOut();
               $(window).on('scroll.song_show_more', function(){
                  if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                     moreSong();
                  }
               });
            }
         });
      }
   }

   $('#song-show-more').live('click.song_show_more', function() {
      moreSong();
   });

   $(window).on('scroll.song_show_more', function(){
      if ($(window).scrollTop() == $(document).height() - $(window).height()) {
         moreSong();
      }
   });
   // end song load more

   // start song audio upload
   $('a.upload-audio-link').live('click.upload_audio_box', function() {

      var song_id = $(this).attr("data-songid");
      $.ajax({
         url: '<?=SITE_HOST?>/ajax-action/box-action/index.php/upload-audio-form',
         type: "GET",
         data: {song_id : song_id},
         dataType: "html",
         beforeSend: function( xhr ) {
         },
         success: function( html_block ) {
            $('#p-modal-block').html(html_block);
         }
      });

   });
   // end song audio upload
</script>