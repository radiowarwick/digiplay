<?php
require_once('pre.php');
Output::set_title("Music Upload");
Output::add_stylesheet(SITE_LINK_REL."css/jquery.fileupload-ui.css");
Output::add_script(SITE_LINK_REL."js/jquery.ui.widget.js");
Output::add_script(SITE_LINK_REL."js/tmpl.min.js");
Output::add_script(SITE_LINK_REL."js/jquery.fileupload.js");
Output::add_script(SITE_LINK_REL."js/jquery.fileupload-ui.js");

MainTemplate::set_subtitle("Drag and drop music to add to the file importer");

echo("
<style>
.name { width: 80%; }
.size { width: 10%; }
</style>
<script>
$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload();

    $('#fileupload').fileupload('option', {
        acceptFileTypes: /(\.|\/)(wav|mp3|aac|flac|m4a|ogg|aif|pcm|raw|wma|cda)$/i
    });

    // Load existing files:
    $.getJSON($('#fileupload').prop('action'), function (files) {
        var fu = $('#fileupload').data('fileupload'),
            template;
        fu._adjustMaxNumberOfFiles(-files.length);
        template = fu._renderDownload(files)
            .appendTo($('#fileupload .files'));
        // Force reflow:
        fu._reflow = fu._transition && template.length &&
            template[0].offsetWidth;
        template.addClass('in');
    });

    $('#fileupload').bind('fileuploadsend', function (e, data) {
        if (data.dataType.substr(0, 6) === 'iframe') {
            var target = $('<a/>').prop('href', data.url)[0];
            if (window.location.host !== target.host) {
                data.formData.push({
                    name: 'redirect',
                    value: redirectPage
                });
            }
        }
    });

    // Open download dialogs via iframes,
    // to prevent aborting current uploads:
    $('#fileupload .files').delegate(
        'a:not([rel^=gallery])',
        'click',
        function (e) {
            e.preventDefault();
            $('<iframe style=\"display:none;\"></iframe>')
                .prop('src', this.href)
                .appendTo(document.body);
        }
    );
});
</script>
    <form id=\"fileupload\" action=\"".SITE_LINK_REL."ajax/file-upload.php\" method=\"POST\" enctype=\"multipart/form-data\">
        <div class=\"row\">
            <div class=\"span9 fileupload-buttonbar\">
                <div class=\"progressbar fileupload-progressbar\"><div style=\"width:0%;\"></div></div>
                <span class=\"btn btn-success fileinput-button\">
                    <span>Add files...</span>
                    <input type=\"file\" name=\"files[]\" multiple>
                </span>
                <button type=\"submit\" class=\"btn btn-primary start\">Start upload</button>
                <button type=\"reset\" class=\"btn btn-info cancel\">Cancel upload</button>
                <button type=\"button\" class=\"btn btn-danger delete\">Delete selected</button>
            </div>
        </div>
        <br>
        <div class=\"row\">
            <div class=\"span9\">
                <table class=\"table table-striped\"><tbody class=\"files\"></tbody></table>
            </div>
        </div>
    </form>
    <script>
var fileUploadErrors = {
    maxFileSize: 'File is too big',
    minFileSize: 'File is too small',
    acceptFileTypes: 'Filetype not allowed',
    maxNumberOfFiles: 'Max number of files exceeded',
    uploadedBytes: 'Uploaded bytes exceed file size',
    emptyResult: 'Empty file upload result'
};
</script>
<script id=\"template-upload\" type=\"text/html\">
{% for (var i=0, files=o.files, l=files.length, file=files[0]; i<l; file=files[++i]) { %}
    <tr class=\"template-upload fade\">
        <td class=\"name\">{%=file.name%}</td>
        <td class=\"size\">{%=o.formatFileSize(file.size)%}</td>
        {% if (file.error) { %}
            <td class=\"error\" colspan=\"2\"><span class=\"label important\">Error</span> {%=fileUploadErrors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td class=\"progress\"><div class=\"progressbar\"><div style=\"width:0%;\"></div></div></td>
            <td class=\"start\">{% if (!o.options.autoUpload) { %}<button class=\"btn btn-primary\">Start</button>{% } %}</td>
        {% } else { %}
            <td colspan=\"2\"></td>
        {% } %}
        <td class=\"cancel\">{% if (!i) { %}<button class=\"btn btn-info\">Cancel</button>{% } %}</td>
    </tr>
{% } %}
</script>
<script id=\"template-download\" type=\"text/html\">
{% for (var i=0, files=o.files, l=files.length, file=files[0]; i<l; file=files[++i]) { %}
    <tr class=\"template-download fade\">
        {% if (file.error) { %}
            <td class=\"name\">{%=file.name%}</td>
            <td class=\"size\">{%=o.formatFileSize(file.size)%}</td>
            <td class=\"error\" colspan=\"2\"><span class=\"label important\">Error</span> {%=fileUploadErrors[file.error] || file.error%}</td>
        {% } else { %}
            <td class=\"name\">
                <a href=\"{%=file.url%}\" title=\"{%=file.name%}\" rel=\"{%=file.thumbnail_url&&'gallery'%}\">{%=file.name%}</a>
            </td>
            <td class=\"size\">{%=o.formatFileSize(file.size)%}</td>
            <td colspan=\"2\"></td>
        {% } %}
        <td class=\"delete\">
            <button class=\"btn btn-danger\" data-type=\"{%=file.delete_type%}\" data-url=\"{%=file.delete_url%}\">Delete</button>
        </td>
    </tr>
{% } %}
</script>
");
echo AlertMessage::basic("info","<a href=\"".SITE_LINK_REL."import\">Click here to go import the files to Digiplay.</a>","Finished uploading?");

?>
