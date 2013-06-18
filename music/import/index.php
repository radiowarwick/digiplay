<?php 
Output::set_title("Import Music");
MainTemplate::set_subtitle("Add new music to the Digiplay system");

$basepath = FILE_ROOT."uploads/";
$files = scandir($basepath);
require_once('/usr/share/php-getid3/getid3.php');
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
			url: '".LINK_ABS."ajax/file-upload.php?file='+elem.parents('fieldset').find('[name=filename]').attr('value'),
			dataType: 'json',
			success: function(data) {
				elem.parents('.fileinfo-tr').prev('.file').find('.icon').find('i').remove();
				elem.parents('.fileinfo-tr').prev('.file').addClass('deleted').find('.name').html(elem.parents('fieldset').find('[name=filename]').attr('value'));
				elem.parents('.fileinfo').slideUp('fast', function() { $(this).remove() });
			}
		});
	});

	$('input').keyup(function(){
		closest = $(this).parents('fieldset').find('.import');
		if(closest.hasClass('btn-warning')) closest.removeClass('btn-warning').addClass('btn-primary');
	});

	$('.import').click(function() {
		event.preventDefault();
		elem = $(this);
		elem.button('loading');
		if(elem.hasClass('btn-primary')) {
				$.ajax({
					type: 'GET',
					url: '".LINK_ABS."ajax/similar-tracks.php?artist='+elem.parents('fieldset').find('[name=artist]').attr('value')+'&title='+elem.parents('fieldset').find('[name=title]').attr('value'),
					dataType: 'json',
					success: function(data) {
						if(data) {
							elem.button('reset');
							elem.removeClass('btn-primary').addClass('btn-warning');
							if(data.tracks.length > 1) {
								elem.parents('.fileinfo').append('".Bootstrap::alert_message_basic("warn","There are other songs in the database that look similar to this. <br />Check you aren\'t importing a duplicate! <a href=\"".LINK_ABS."music/search/?q='+data.q+'\" target=\"_blank\">Click here to see the suggestions.</a><br /><strong>Click Import again to add the song anyway.</strong>","Hold up!",true)."');
							} else {
								elem.parents('.fileinfo').append('".Bootstrap::alert_message_basic("warn","There is another song in the database that looks similar to this. <br />Check you aren\'t importing a duplicate! <a href=\"".LINK_ABS."music/detail/'+data.tracks[0]+'\" target=\"_blank\">Click here to see the suggestion.</a><br /><strong>Click Import again to add the song anyway.</strong>","Hold up!",true)."');
							}
						} else {
							importTrack(elem.parents('form'),elem)
						}
					}
				});
		} else {
			importTrack(elem.parents('form'),elem);
		}
	});

	function importTrack(form,button) {
		console.log(form);
		$.ajax({
			type: 'GET',
			url: '".LINK_ABS."ajax/import-track.php',
			dataType: 'json',
			data: form.serialize(),
			success: function(data) {
                                form.parents('.fileinfo-tr').prev('.file').find('.icon').find('i').remove();
                                form.parents('.fileinfo-tr').prev('.file').addClass('deleted').find('.name').html(elem.parents('fieldset').find('[name=filename]').attr('value'));
                                form.parents('.fileinfo').slideUp('fast', function() { $(this).remove() });
				button.button('reset');
			},
			error: function(data) {
				console.log(data);
				form.parents('.fileinfo').append('".Bootstrap::alert_message_basic("error","There was an error when trying to upload this file.<br />'+data.error+'","Oh no!",true)."');
				button.button('reset');
			}
		});
	}
});
</script>
<table class=\"table table-file-import\">");
foreach($files as $file) {
	if(substr($file,0,1) == ".") continue;
	$tags = $getid3->analyze($basepath.$file);
	@getid3_lib::CopyTagsToComments($tags);

	$title = isset($tags["comments"]["title"]) ? implode(";", $tags["comments"]["title"]) : "";
	$artist = isset($tags["comments"]["artist"]) ? implode(";", $tags["comments"]["artist"]) : "";
	$album = isset($tags["comments"]["album"]) ? implode(";", $tags["comments"]["album"]) : "";
	$year = (strlen($tags["comments"]["recording_time"]) == 4)? $tags["comments"]["recording_time"][0] : date("Y");
	$length = isset($tags["playtime_string"])? $tags["playtime_string"] : "Unknown";
	$origin = (strlen(Session::get_name()) > 0) ? Session::get_name() : Session::get_username();
	$filetype = isset($tags["audio"]["dataformat"])? $tags["audio"]["dataformat"] : "Unknown";
	$codec = isset($tags["audio"]["codec"])? $tags["audio"]["codec"] : "Unknown";
	$bitrate = isset($tags["audio"]["bitrate"])? round($tags["audio"]["bitrate"] / 1000) : "Unknown";
	$lossless = $tags["audio"]["lossless"] === false ? false : true;
	$warnings = "";

	$acceptable_bitrate = isset($acceptable_bitrates[$filetype])? $acceptable_bitrates[$filetype] : null;
	$warnings .= (!($bitrate >= $acceptable_bitrate))? Bootstrap::alert_message_basic("warn","File bitrate is below the recommended minimum.  Try and find a better quality version!","Warning!",true) : "";

	echo("<tr class=\"file\">
			<td class=\"icon\"> <i class=\"icon-chevron-right\"></i></td>
			<td class=\"name col-lg-7\">".$file." (<a href=\"".LINK_ABS."uploads/".$file."\">Download</a>)</td>
		</tr>
		<tr class=\"fileinfo-tr\">
			<td colspan=\"2\">
				<div class=\"fileinfo\" style=\"display: none\">
					<div class=\"warnings\">".$warnings."</div>
					<div class=\"row\">
						<div class=\"col-lg-5\">
							<form class=\"form-horizontal\" action=\"".LINK_ABS."ajax/file-import.php\" method=\"POST\" enctype=\"multipart/form-data\">
								<fieldset>
									<input type=\"hidden\" name=\"filename\" value=\"".$file."\" />
									<input type=\"hidden\" name=\"origin\" value=\"".$origin."\" />
									<div class=\"control-group\">
										<label class=\"control-label\" for=\"title\">Title</label>
										<div class=\"controls\">
											<input type=\"text\" id=\"title\" name=\"title\" value=\"".$title."\" />
										</div>
									</div>
									<div class=\"control-group\">
										<label class=\"control-label\" for=\"artist\">Artist</label>
										<div class=\"controls\">
											<input type=\"text\" id=\"artist\" name=\"artist\" value=\"".$artist."\" />
										</div>
									</div>
									<div class=\"control-group\">
										<label class=\"control-label\" for=\"album\">Album</label>
										<div class=\"controls\">
											<input type=\"text\" id=\"album\" name=\"album\" value=\"".$album."\" />
										</div>
									</div>
									<div class=\"control-group\">
										<label class=\"control-label\" for=\"year\">Year</label>
										<div class=\"controls\">
											<input type=\"text\" id=\"year\" name=\"year\" value=\"".$year."\" />
										</div>
									</div>
									<div class=\"control-group\">
										<div class=\"controls\">
											<button class=\"import btn btn-primary\" data-loading=\"Loading...\"><i class=\"icon-ok icon-white\"></i> Import</button>
											<button class=\"delete btn btn-danger\" ><i class=\"icon-trash icon-white\"></i> Delete</button>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
						<div class=\"col-lg-3\">
							<strong>Length: </strong>".$length."<br />
							<strong>Origin: </strong>".$origin."<br />
							<strong>Filetype: </strong>".$filetype."<br />
							<strong>Codec: </strong>".$codec."<br />
							<strong>Bitrate: </strong>".$bitrate." kbps".($lossless? " (lossless)" : "")."<br />
						</div>
					</div>
				</div>
			</td>
		</tr>");
}
echo("</table>");
?>
