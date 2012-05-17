<div class="page-header padding-all align-center">
   <h1>Lyrics Match - Please Upload Your Music Work!</h1>
</div>
<br class="clearboth" />
<div class="row well" style="width: 400px; margin: 10px auto;">
   <div class="pull-left">
      <input id="fileupload" type="file" name="files[]" data-url="<?=SITE_HOST?>/p-library/blueimp/server/php/" multiple />
   </div>
   <div class="pull-right">
      <button type="submit" class="btn btn-primary start">
         <i class="icon-upload icon-white"></i>
         <span>Start upload</span>
      </button>
   </div>
</div>
<br class="clearboth" />
<div style="width: 800px; margin: 20px auto;">
   <img width="800" src="<?=SITE_HOST?>/p-asset/image/ui-icon/banner.png" />
</div>
<br class="clearboth" />
<script>
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });
});
</script>