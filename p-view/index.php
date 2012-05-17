<div class="page-header padding-all align-center">
   <h1>Lyrics Match - Please Upload Youre Music Work!</h1>
</div>
<br/>
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