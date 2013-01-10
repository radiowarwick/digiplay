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

    $('#fileupload').fileupload();

    $('#fileupload').fileupload('option', {
        acceptFileTypes: /(\.|\/)(wav|mp3|aac|flac|m4a|ogg|pcm|wma)$/i,
        url: '".SITE_LINK_REL."ajax/file-upload.php',
        limitConcurrentUploads: 3
    });
        
    // Load existing files:
    $.ajax({
        url: $('#fileupload').fileupload('option', 'url'),
        dataType: 'json',
        context: $('#fileupload')[0]
    }).done(function (result) {
        $(this).fileupload('option', 'done')
            .call(this, null, {result: result});
    });
});
</script>
    <form id=\"fileupload\" action=\"".SITE_LINK_REL."ajax/file-upload.php\" method=\"POST\" enctype=\"multipart/form-data\">
        <div class=\"row fileupload-buttonbar\">
            <div class=\"span6\">
                <span class=\"btn btn-success fileinput-button\">
                    <i class=\"icon-plus icon-white\"></i>
                    <span>Add files</span>
                    <input type=\"file\" name=\"files[]\" multiple>
                </span>
                <button type=\"submit\" class=\"btn btn-primary start\">
                    <i class=\"icon-upload icon-white\"></i>
                    <span>Start upload</span>
                </button>
                <button type=\"reset\" class=\"btn btn-warning cancel\">
                    <i class=\"icon-ban-circle icon-white\"></i>
                    <span>Cancel upload</span>
                </button>
            </div>
            <div class=\"span3 fileupload-progress\">
                <div class=\"progress-extended\">&nbsp;</div>
            </div>
        </div>
        <div class=\"fileupload-loading\"></div>
        <br>
        <table role=\"presentation\" class=\"table table-striped\"><tbody class=\"files\" data-toggle=\"modal-gallery\" data-target=\"#modal-gallery\"></tbody></table>
    </form>

<script>
</script>
<script id=\"template-upload\" type=\"text/x-tmpl\">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class=\"template-upload\">
        <td class=\"name span5\"><span>{%=file.name%}</span></td>
        <td class=\"size span2\"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class=\"error\" colspan=\"2\"><span class=\"label label-important\">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class=\"progress progress-success progress-striped active\" role=\"progressbar\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"0\"><div class=\"bar\" style=\"width:0%;\"></div></div>
            </td>
            <td class=\"start\">{% if (!o.options.autoUpload) { %}
                <button class=\"btn btn-primary pull-right\">
                    <span>Start</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan=\"2\"></td>
        {% } %}
        <td class=\"cancel\">{% if (!i) { %}
            <button class=\"btn btn-warning pull-right\">
                <span>Cancel</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<script id=\"template-download\" type=\"text/x-tmpl\">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class=\"template-download\">
        {% if (file.error) { %}
            <td></td>
            <td class=\"name span7\" colspan=\"3\"><span>{%=file.name%}</span></td>
            <td class=\"error\"><span class=\"label label-important\">Error</span> {%=file.error%}</td>
            <td class=\"size span2\"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% } else { %}
            <td class=\"name span7\" colspan=\"3\">
                <a href=\"{%=file.url%}\" title=\"{%=file.name%}\" download=\"{%=file.name%}\">{%=file.name%}</a>
            </td>
            <td class=\"size span2\"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% } %}
        <td class=\"delete\">
            <button class=\"btn btn-danger pull-right\" data-type=\"{%=file.delete_type%}\" data-url=\"{%=file.delete_url%}\"{% if (file.delete_with_credentials) { %} data-xhr-fields='{\"withCredentials\":true}'{% } %}>
                <i class=\"icon-trash icon-white\"></i>
            </button>
        </td>
    </tr>
{% } %}
</script>

");
echo AlertMessage::basic("info","<a href=\"".SITE_LINK_REL."music/import\">Click here to go import the files to Digiplay.</a>","Finished uploading?");

?>
