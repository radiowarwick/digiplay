<?php

Output::require_group("Uploader");
Output::set_title("Music Upload");
Output::add_script(LINK_ABS."js/jquery.ui.widget.js");
Output::add_script(LINK_ABS."js/tmpl.min.js");
Output::add_script(LINK_ABS."js/jquery.fileupload.js");
Output::add_script(LINK_ABS."js/jquery.fileupload-ui.js");

MainTemplate::set_subtitle("Drag and drop music to add to the file importer");

echo("
<script>
$(function () {
	'use strict';

	$('#fileupload').fileupload();

	$('#fileupload').fileupload('option', {
		acceptFileTypes: /(\.|\/)(wav|mp3|aac|flac|m4a|ogg|pcm|wma|aif)$/i,
		url: '".LINK_ABS."ajax/file-upload.php',
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
	<form id=\"fileupload\" action=\"".LINK_ABS."ajax/file-upload.php\" method=\"POST\" enctype=\"multipart/form-data\">
		<div class=\"row fileupload-buttonbar\">
			<div class=\"col-md-6\">
				<span class=\"btn btn-success fileinput-button\">
					".Bootstrap::fontawesome("plus")."
					<span>Add files</span>
					<input type=\"file\" name=\"files[]\" multiple>
				</span>
				<button type=\"submit\" class=\"btn btn-primary start\">
					".Bootstrap::fontawesome("arrow-alt-circle-up")."
					<span>Start upload</span>
				</button>
				<button type=\"reset\" class=\"btn btn-warning cancel\">
					".Bootstrap::fontawesome("ban")."
					<span>Cancel upload</span>
				</button>
			</div>
			<div class=\"col-md-6 fileupload-progress\">
				<div class=\"progress-extended\">&nbsp;</div>
			</div>
		</div>
		<div class=\"fileupload-loading\"></div>
		<br>
		<table class=\"table table-striped\"><tbody class=\"files\"></tbody></table>
	</form>

<script>
</script>
<script id=\"template-upload\" type=\"text/x-tmpl\">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<tr class=\"template-upload\">
		<td class=\"name col-md-5\"><span>{%=file.name%}</span></td>
		{% if (file.error) { %}
			<td class=\"error\" colspan=\"2\"><span class=\"label label-important\">Error</span> {%=file.error%}</td>
		{% } else if (o.files.valid && !i) { %}
			<td class=\"col-md-3\">
				<div class=\"progress progress-striped active\"><div class=\"progress-bar\"></div></div>
			</td>
			<td class=\"size col-md-2\"><span>{%=o.formatFileSize(file.size)%}</span></td>
			<td class=\"start\">{% if (!o.options.autoUpload) { %}
				<button class=\"btn btn-primary pull-right\">
					<span>Start</span>
				</button>
			{% } %}</td>
		{% } else { %}
			<td colspan=\"2\"></td>
		{% } %}
		<td class=\"cancel col-md-1\">{% if (!i) { %}
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
			<td class=\"name col-md-7\" colspan=\"2\"><span>{%=file.name%}</span></td>
			<td class=\"error\"><span class=\"label label-important\">Error</span> {%=file.error%}</td>
			<td class=\"size col-md-2\"><span>{%=o.formatFileSize(file.size)%}</span></td>
		{% } else { %}
			<td class=\"name col-md-7\" colspan=\"2\">
				<a href=\"{%=file.url%}\" title=\"{%=file.name%}\" download=\"{%=file.name%}\">{%=file.name%}</a>
			</td>
			<td class=\"size col-md-2\"><span>{%=o.formatFileSize(file.size)%}</span></td>
		{% } %}
	</tr>
{% } %}
</script>

");
echo Bootstrap::alert_message_basic("info","<a href=\"".LINK_ABS."music/import\">Click here to go import the files to Digiplay.</a>","Finished uploading?");

?>
