<div class="page-header padding-all align-center">
   <h1>Lyrics Match - Please Upload Your Music Work!</h1>
</div>
<br/>
<div style="width: 800px; margin: 20px auto;">
   <img width="800" src="<?=SITE_HOST?>/p-asset/image/ui-icon/banner.png" />
</div>
<div class="well" style="width: 300px; margin: 10px auto;">
   <input id="fileupload" type="file" name="files[]" data-url="<?=SITE_HOST?>/p-library/blueimp/server/php/" multiple />
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