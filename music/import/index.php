<?php 
Output::set_title("Import Music");
MainTemplate::set_subtitle("Add new music to the Digiplay system");
Output::add_stylesheet(LINK_ABS."css/bootstrap-select.css");
Output::add_script(LINK_ABS."js/bootstrap-select.js");

$basepath = FILE_ROOT."uploads/";
$files = scandir($basepath);
require_once('/usr/share/php/getid3/getid3.php');
$getid3 = new getID3;

$acceptable_bitrates = array(
	"wav" => 1,
	"mp3" => 256,
	"aac" => 192,
	"flac" => 1,
	"m4a" => 64,
	"ogg" => 192,
	"pcm" => 1,
	"wma" => 256
	);

echo("
<script>
$(function() {
	$('.file').click(function() {
		slider = $(this).next('tr').first('td').find('.fileinfo');
		if(slider.is(':visible')) {
			slider.slideUp();
			$(this).find('i').removeClass('icon-chevron-down').addClass('icon-chevron-right');
		} else {
			slider.slideDown();
			$(this).find('i').removeClass('icon-chevron-right').addClass('icon-chevron-down');
		}
	});

	$('.fileinfo:first').show();
	$('.file:first').find('i').removeClass('icon-chevron-right').addClass('icon-chevron-down');

	$('.delete').click(function() { 
		event.preventDefault();
		elem = $(this);
		$.ajax({
			type: 'DELETE',
			url: '".LINK_ABS."ajax/file-upload.php?file='+elem.parents('form').find('[name=filename]').attr('value'),
			dataType: 'json',
			success: function(data) {
				elem.parents('.panel').find('.glyphicon').removeClass('glyphicon-chevron-right').addClass('glyphicon-remove');
				elem.parents('.panel').removeClass('panel-default').addClass('panel-danger');
				elem.parents('.panel').find('.panel-body').slideUp('fast', function() { $(this).remove() });
			}
		});
	});

	$('input').keyup(function(){
		closest = $(this).parents('form').find('.import');
		if(closest.hasClass('btn-warning')) closest.removeClass('btn-warning').addClass('btn-primary');
	});

	$('.import').click(function(event) {
		event.preventDefault();
		elem = $(this);
		elem.button('loading');
		if(elem.hasClass('btn-primary')) {
			$.ajax({
				type: 'GET',
				url: '".LINK_ABS."ajax/similar-tracks.php?artist='+elem.parents('form').find('[name=artist]').attr('value')+'&title='+elem.parents('form').find('[name=title]').attr('value'),
				dataType: 'json'
			}).done(function(data) {
				if(data.response == 'fail') {
					elem.button('reset');
					elem.removeClass('btn-primary').addClass('btn-warning');
					if(data.tracks.length > 1) {
						elem.parents('.panel-body').find('.warnings').append('".Bootstrap::alert_message_basic("warning","There are other songs in the database that look similar to this. <br />Check you aren\'t importing a duplicate! <a href=\"".LINK_ABS."music/search/?q='+data.q+'\" target=\"_blank\">Click here to see the suggestions.</a><br /><strong>Click Import again to add the song anyway.</strong>","Hold up!",true)."');
					} else {
						elem.parents('.panel-body').find('.warnings').append('".Bootstrap::alert_message_basic("warning","There is another song in the database that looks similar to this. <br />Check you aren\'t importing a duplicate! <a href=\"".LINK_ABS."music/detail/'+data.tracks[0]+'\" target=\"_blank\">Click here to see the suggestion.</a><br /><strong>Click Import again to add the song anyway.</strong>","Hold up!",true)."');
					}
				} else {
					importTrack(elem.parents('form'),elem)
				}
			});
		} else {
			importTrack(elem.parents('form'),elem);
		}
	});

	function importTrack(form,button) {
		console.log($(form).find('#artist').val());
		if ($(form).find('#artist').val() == ''){
			alert('Artist is empty please put one in!');
			button.button('reset');
		} else {
		$.ajax({
			type: 'GET',
			url: '".LINK_ABS."ajax/import-track.php',
			dataType: 'json',
			data: form.serialize()
		}).done(function(data) {
			if(data.error == undefined) {
				form.parents('.panel').find('.glyphicon').removeClass('glyphicon-chevron-right').addClass('glyphicon-ok');
				form.parents('.panel').removeClass('panel-default').addClass('panel-success');
				form.parents('.panel').find('.panel-body').slideUp('fast', function() { $(this).remove() });
				button.button('reset');
			} else {
				console.log(data);
				console.log(form.parentsUntil('.panel-body'));
				form.parents('.panel-body').find('.warnings').append('".Bootstrap::alert_message_basic("danger","There was an error when trying to upload this file.<br />'+data.error+'","Oh no!",true)."');
				button.button('reset');
			}
		});
	}
	}
});
</script>
<div class=\"panel-group\" id=\"tracks\">");
foreach($files as $file) {
	if(substr($file,0,1) == ".") continue;
	$tags = $getid3->analyze($basepath.$file);
	@getid3_lib::CopyTagsToComments($tags);

	$rand = mt_rand(0,10000);
	$title = isset($tags["comments"]["title"]) ? implode(";", $tags["comments"]["title"]) : "";
	$artist = isset($tags["comments"]["artist"]) ? implode(";", $tags["comments"]["artist"]) : "";
	$album = isset($tags["comments"]["album"]) ? implode(";", $tags["comments"]["album"]) : "";
	$year = isset($tags["comments"]["recording_time"]) && (strlen(implode($tags["comments"]["recording_time"])) == 4)? $tags["comments"]["recording_time"][0] : date("Y");
	$length = isset($tags["playtime_string"])? $tags["playtime_string"] : "Unknown";
	$origin = (Session::get_name()) ? Session::get_name() : Session::get_username();
	$filetype = isset($tags["audio"]["dataformat"])? $tags["audio"]["dataformat"] : "Unknown";
	$codec = isset($tags["audio"]["codec"])? $tags["audio"]["codec"] : "Unknown";
	$bitrate = isset($tags["audio"]["bitrate"])? round($tags["audio"]["bitrate"] / 1000) : "Unknown";
	$lossless = $tags["audio"]["lossless"] === false ? false : true;
	$warnings = "";

	$acceptable_bitrate = isset($acceptable_bitrates[$filetype])? $acceptable_bitrates[$filetype] : null;
	$warnings .= (!($bitrate >= $acceptable_bitrate))? Bootstrap::alert_message_basic("warning","File bitrate is below the recommended minimum.  Try and find a better quality version!","Warning!",true) : "";

	echo("
		<div class=\"panel panel-default\">
			<div class=\"panel-heading\" data-toggle=\"collapse\" href=\"#track-".$rand."\">
				".Bootstrap::glyphicon("chevron-right").$file."
			</div>
			<div id=\"track-".$rand."\" class=\"panel-collapse collapse\">
				<div class=\"panel-body\">
					<div class=\"warnings\">".$warnings."</div>
					<div class=\"row\">
						<div class=\"col-sm-8\">
							<form class=\"form-horizontal\" action=\"#\" method=\"POST\" enctype=\"multipart/form-data\">
									<input class=\"form-control\" type=\"hidden\" name=\"filename\" value=\"".$file."\" />
									<input class=\"form-control\" type=\"hidden\" name=\"origin\" value=\"".$origin."\" />
									<div class=\"form-group\">
										<label class=\"col-sm-3 control-label\" for=\"title\">Title</label>
										<div class=\"col-sm-9\">
											<input class=\"form-control\" type=\"text\" id=\"title\" name=\"title\" value=\"".$title."\" />
										</div>
									</div>
									<div class=\"form-group\">
										<label class=\"col-sm-3 control-label\" for=\"artist\">Artist</label>
										<div class=\"col-sm-9\">
											<input class=\"form-control\" required type=\"text\" id=\"artist\" name=\"artist\" value=\"".$artist."\" />
										</div>
									</div>
									<div class=\"form-group\">
										<label class=\"col-sm-3 control-label\" for=\"album\">Album</label>
										<div class=\"col-sm-9\">
											<input class=\"form-control\" type=\"text\" id=\"album\" name=\"album\" value=\"".$album."\" />
										</div>
									</div>
									<div class=\"form-group\">
										<label class=\"col-sm-3 control-label\" for=\"year\">Year</label>
										<div class=\"col-sm-9\">
											<input class=\"form-control\" type=\"text\" id=\"year\" name=\"year\" value=\"".$year."\" />
										</div>
									</div>
									<div class=\"form-group\">
										<label class=\"col-sm-3 control-label\" for=\"type\">Audio Type</label>
										<div class=\"col-sm-9\">
											<select class=\"selectpicker\" id=\"type\" name=\"type\" data-width=\"100%\">");
											foreach(AudioTypes::get_all() as $audiotype) {
												// Make track selected as the default type
												if($audiotype->get_id() === 1)
													echo("<option value=\"" . $audiotype->get_id() . "\" selected>" . $audiotype->get_name() . "</option>");
												else
													echo("<option value=\"" . $audiotype->get_id() . "\">" . $audiotype->get_name() . "</option>");
											}
	echo("									</select>
										</div>
									</div>
									<div class=\"form-group\">
										<div class=\"col-sm-10 col-sm-offset-2\">
											<button class=\"import btn btn-primary\" data-loading=\"Loading...\">".Bootstrap::glyphicon("ok icon-white")." Import</button>
											<button class=\"delete btn btn-danger\" >".Bootstrap::glyphicon("trash icon-white")." Delete</button>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
						<div class=\"col-sm-4\">
							<strong>Length: </strong>".$length."<br />
							<strong>Origin: </strong>".$origin."<br />
							<strong>Filetype: </strong>".$filetype."<br />
							<strong>Codec: </strong>".$codec."<br />
							<strong>Bitrate: </strong>".$bitrate." kbps".($lossless? " (lossless)" : "")."<br />
							<em><a href=\"".LINK_ABS."uploads/".$file."\" target=\"_blank\">".Bootstrap::glyphicon("download-alt")."Download file</a></em>
						</div>
					</div>
				</div>
			</div>
		</div>
			");
}
echo("</div>");

?>