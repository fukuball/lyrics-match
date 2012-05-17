<div class="page-header">
   <h1>Lyrics Match - Please Upload Youre Music Work!</h1>
</div>
<blockquote>
   <p>
      File Upload widget with multiple file selection, drag&amp;drop support, progress bars and preview images for jQuery.<br/>
      Supports cross-domain, chunked and resumable file uploads and client-side image resizing.<br/>
      Works with any server-side platform (PHP, Python, Ruby on Rails, Java, Node.js, Go etc.) that supports standard HTML form file uploads.
   </p>
</blockquote>
<br/>
<input id="fileupload" type="file" name="files[]" data-url="server/php/" multiple>
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