<script>
   var song_offset = 0;
   function moreSong() {

      var action_url = '<?=SITE_HOST?>/ajax-action/index.php/song-action/song-list';
      song_offset = song_offset+30;

      alert(action_url);
      alert(song_offset);

      /*if ($('#song-show-more').length != 0) {
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
      }*/
   }

   $('#song-show-more').live('click.song_show_more', function() {
      moreSong();
   });

   $(window).on('scroll.song_show_more', function(){
      if ($(window).scrollTop() == $(document).height() - $(window).height()) {
         moreSong();
      }
   });
</script>