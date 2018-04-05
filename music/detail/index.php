<?php

Output::set_title("Track Detail");
MainTemplate::set_subtitle("View and edit track metadata");
Output::add_stylesheet(LINK_ABS."css/bootstrap-select.css");
Output::add_script(LINK_ABS."js/bootstrap-select.js");

if(!isset($_GET['id'])) exit("<h3>No track specified</h3><h4>You must access this via another page, to get metadata for a specified track.</h4>");
if(!$track = Tracks::get($_GET["id"])) exit("<h3>Invalid track ID</h3><h4>If you got to this page via a link from somewhere else on the site, there may be a bug.  A bug you should bug the techies about!</h4>");
if(!Session::is_group_user("Music Admin")) $disabled = " disabled";
else $disabled = "";

echo("
	<script>
		$(function () {
			$('.track-detail-form').submit(function(event) {
				event.preventDefault();
				submit = $(this).find('button[type=\"submit\"]');
				submit.find('svg').attr('class', 'fas fa-sync fa-spin');
				$.ajax({
					url: '".LINK_ABS."ajax/track-detail-update.php',
					data: $(this).serialize(),
					type: 'POST',
					error: function(xhr,text,error) {
						value = $.parseJSON(xhr.responseText);
						submit.find('svg').attr('class', 'fa-save');
						$('h3').after('".Bootstrap::alert_message_basic("danger","'+value.error+'","Error!")."');
						console.error(value.detail);
						$('.alert-message').alert();
					},
					success: function(data,text,xhr) {
						values = $.parseJSON(data);
						submit.find('svg').attr('class', 'fa-save');
						$('h3').after('".Bootstrap::alert_message_basic("success","Track details altered.","Success!",false)."');
						$('[id=new_artist]').val('');
						$('[id=artist\\\\[\\\\]]').parent('.col-md-10').remove();
						artists_str = '';
						first = true;
						$.each(values.artists, function(i, val) {
							artists_str += '<div class=\"col-md-10'+(first? '' : ' col-md-offset-2')+'\"><input type=\"text\" id=\"artist[]\" name=\"artist[]\" class=\"form-control".$disabled."\" value=\"'+val+'\"></div>';
							first = false;
						});
						$('[for=artist]').after(artists_str);
						$('[id=new_keyword]').val('');
						$('.keyword').parent().remove();
						keywords_str = '';
						$.each(values.keywords, function(i, val) {
							keywords_str += '<div class=\"input-group\"><span class=\"input-group-addon\"><a class=\"keyword-remove\" href=\"".LINK_ABS."ajax/del-keywords.php?track_id=".$track->get_id()."&keyword='+val+'\">".Bootstrap::glyphicon("remove-sign")."</a></span><input type=\"text\" class=\"form-control\" disabled value=\"'+val+'\"></div></div>'
						});
						$('.for-keywords').html(keywords_str);
						setTimeout(function() {
    						$('.alert').hide('fast', function(){
        						$(this).remove(); 
           				});},4000);
					}
				});
			});

			$('.keyword-remove').on(\"click\", function(event) {
				event.preventDefault();
				parent = $(this).parent().parent();
				$.get($(this).attr('href'), function(data) {
					if(data == \"success\") {
						parent.remove();
					} else {
						$('h3').after('".Bootstrap::alert_message_basic("danger","'+data+'","Error!")."');
						$('.alert-message').show('fast').alert();
					}
				});
				return false;
			});

			$('[id=flag]').click(function() {
				event.preventDefault();
				t = $(this);
				t.find('.censor-flag').attr('class', 'censor-flag fas fa-sync fa-spin');
				$.ajax({
					url: '".LINK_ABS."ajax/flag.php',
					data: 'id=".$track->get_id()."&flag=toggle',
					type: 'GET',
					error: function(xhr,text,error) {
						value = $.parseJSON(xhr.responseText);
						$('h3').after('".Bootstrap::alert_message_basic("danger","'+value.error+'","Error!")."');
						$('.alert-message').alert();
						t.find('.censor-flag').attr('class', 'censor-flag fa-exclamation-triangle');
					},
					success: function(data,text,xhr) {
							value = $.parseJSON(xhr.responseText);
							t.find('.censor-flag').attr('class', 'censor-flag fa-exclamation-triangle');
							if(value.response == 'flagged') {
								t.addClass('active');
								response = 'This track has been flagged for censorship and will be reviewed in due course.';
							} else {
								t.removeClass('active');
								response = 'This track has been unflagged.';
							}
							$('h3').after('".Bootstrap::alert_message_basic("warning","'+response+'","Success!",false)."'); 
							setTimeout(function() {
	    						$('.alert').hide('fast', function(){
	        						$(this).remove(); 
	           				});},4000);
					}
				});
			});
".(Session::is_group_user("Playlist Editor") ? "
			var item;
			$('.playlist-add').click(function() {
				item = $(this);
				playlists = $(this).attr('data-playlists-in').split(',');
				$('.playlist-select').parent().removeClass('active');
				$('.playlist-select').find('svg').attr('class', 'fa-plus-circle');
				$('.playlist-select').each(function() {
					if($.inArray($(this).attr('data-playlist-id'),playlists) > -1) {
						$(this).find('svg').attr('class fa-minus-circle');
						$(this).parent().addClass('active');
					}
				})
			});

			$('.playlist-select').click(function() {
				obj = $(this);
				if($(this).parent().hasClass('active')) {
					$(this).find('svg').attr('class', 'fas fa-sync fa-spin');
					$.ajax({
						url: '".LINK_ABS."ajax/track-playlist-update.php',
						data: 'trackid='+item.attr('data-dps-id')+'&playlistid='+obj.attr('data-playlist-id')+'&action=del',
						type: 'POST',
						error: function(xhr,text,error) {
							value = $.parseJSON(xhr.responseText);
							obj.find('svg').attr('class', 'fa-minus-circle');
							alert(value.error);
						},
						success: function(data,text,xhr) {
							values = $.parseJSON(data);
							obj.find('svg').attr('class', 'fa-plus-circle');
							obj.parent().removeClass('active');
							item.attr('data-playlists-in',values.playlists.join(','));
						}
					});
				} else {
					$(this).find('svg').attr('class', 'fas fa-sync fa-spin');
					$.ajax({
						url: '".LINK_ABS."ajax/track-playlist-update.php',
						data: 'trackid='+item.attr('data-dps-id')+'&playlistid='+obj.attr('data-playlist-id')+'&action=add',
						type: 'POST',
						error: function(xhr,text,error) {
							value = $.parseJSON(xhr.responseText);
							obj.find('svg').attr('class', 'fa-plus-circle');
							alert(value.error);
						},
						success: function(data,text,xhr) {
							values = $.parseJSON(data);
							obj.find('svg').attr('class', 'fa-minus-circle');
							obj.parent().addClass('active');
							item.attr('data-playlists-in',values.playlists.join(','));
						}
					});
					$(this).parent().addClass('active');
					$(this).find('svg').attr('class', 'fa-minus-circle');
				}
			});		
" : "").
(Session::is_group_user("Music Admin") ? "
		var trackid;
		$('.track-delete').click(function() {
			$('.delete-track-title').html($(this).attr('data-dps-title'));
			trackid = $(this).attr('data-dps-id');
		});

		$('.yes-definitely-delete').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/delete-track.php',
				data: 'id='+trackid,
				type: 'POST',
				error: function(xhr,text,error) {
					value = $.parseJSON(xhr.responseText);
					alert(value.error);
				},
				success: function(data,text,xhr) {
					window.location.reload(true); 
				}
			});
		});

		$('.restore-track').click(function(){
			trackid = $(this).attr('data-dps-id');
			$.ajax({
				url: '".LINK_ABS."ajax/restore-track.php',
				data: 'id='+trackid,
				type: 'POST',
				error: function(xhr,text,error) {
					value = $.parseJSON(xhr.responseText);
					alert(value.error);
				},
				success: function(data,text,xhr) {
					window.location.reload(true); 
				}
			});
		});
" : "") . "
		});
	</script>
	<h3>Edit Track: ".$track->get_id()." <small>Added ".date("d/m/Y H:i",$track->get_import_date())."</small></h3>
	".(Session::is_group_user("Music Admin")? "":Bootstrap::alert_message_basic("info","You can't edit the details of this track, because you aren't a Music Admin.","Notice:")));

	if($track->get_dirid() == 3 && Session::is_group_user("Music Admin"))
		echo("<div class=\"alert alert-danger\"><strong>Warning!</strong> This track is currently in the trash. <a href=\"#\" class=\"restore-track\" data-dps-id=\"" . $track->get_id() . "\">Restore from trash</a>.</div>");
	
	echo($track->player()."
	<form class=\"track-detail-form\" action=\"\" method=\"post\">
		<fieldset>
			<div class=\"row\">
				<div class=\"col-md-7 form-horizontal\">
					<input type=\"hidden\" id=\"id\" name=\"id\" value=\"".$track->get_id()."\">
					<div class=\"form-group\">
						<label class=\"control-label col-md-2\" for=\"title\">Title</label>
						<div class=\"col-md-10\">
							<input type=\"text\" id=\"title\" name=\"title\" class=\"form-control\" ".$disabled." value=\"".$track->get_title()."\">
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-md-2\" for=\"artist\">Artists</label>");
						$first = true;
						foreach($track->get_artists() as $key=>$artist) {
							echo("
						<div class=\"col-md-10".(!$first? " col-md-offset-2" : "")."\">
							<input type=\"text\" id=\"artist[]\"  name=\"artist[]\" class=\"form-control\" ".$disabled." value=\"".$artist->get_name()."\">
						</div>");
							$first = false;
						}
					echo("
						<div class=\"col-md-10 col-md-offset-2\">
							<input type=\"text\" id=\"new_artist\" name=\"new-artist\" class=\"form-control\" ".$disabled." placeholder=\"Add new artist...\">
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-md-2\" for=\"album\">Album</label>
						<div class=\"col-md-10\">
							<input type=\"text\" id=\"album\" name=\"album\" class=\"form-control\" ".$disabled." value=\"".$track->get_album()->get_name()."\">
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-md-2\" for=\"year\">Year</label>
						<div class=\"col-md-10\">
							<input type=\"text\" class=\"form-control\" id=\"year\" name=\"year\" ".$disabled." value=\"".$track->get_year()."\">
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-md-2\" for=\"length\">Length</label>
						<div class=\"col-md-10\">
							<p class=\"form-control-static\">".Time::format_succinct($track->get_length())."</span>
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-md-2\" for=\"origin\">Origin</label>
						<div class=\"col-md-10\">
							<input type=\"text\" id=\"origin\" name=\"origin\" class=\"form-control\" ".$disabled." value=\"".$track->get_origin()."\">
						</div>
					</div>
					<div class=\"form-group\">
						<div class=\"col-md-10 col-md-offset-2\">
							<div class=\"checkbox\">
								<label for=\"censored\">
									<input type=\"checkbox\" id=\"censored\" name=\"censored\" ".$disabled." ".($track->is_censored()? "checked" : "").">
									Explicit
								</label>
							</div>
						</div>
					</div>
					<div class=\"form-group\">
						<div class=\"col-md-10 col-md-offset-2\">
							<button type=\"submit\" class=\"btn btn-primary btn-block\">
								".Bootstrap::fontawesome("save", "save")."
								Save
							</button>
						</div>
					</div>");
				if(Session::is_user("Music Admin") && $track->get_dirid() != 3) {
					echo("<div class=\"form-group\">
						<div class=\"col-md-10 col-md-offset-2\">
							<a data-toggle=\"modal\" data-target=\"#delete-modal\" class=\"btn btn-danger btn-block track-delete\" data-dps-id=\"" . $track->get_id() . "\" data-dps-title=\"" . $track->get_title() . "\">
								".Bootstrap::fontawesome("trash")."
								Delete
							</a>
						</div>
					</div>");
				}
				echo("</div>
				<div class=\"col-md-5 form\">
					<div class=\"form-group\">
						<label for=\"notes\">Notes</label>
						<textarea class=\"form-control\" id=\"notes\" name=\"notes\" ".$disabled.">".$track->get_notes()."</textarea>
					</div>
					<div class=\"form-group\">
						<label for=\"keyword\">Keywords</label>
						<div class=\"for-keywords\">");
							foreach($track->get_keywords() as $keyword) {
								echo("
								<div class=\"input-group\">
									<span class=\"input-group-addon\"><a class=\"keyword-remove\" href=\"".LINK_ABS."ajax/del-keywords.php?track_id=".$track->get_id()."&keyword=".$keyword->get_text()."\">".Bootstrap::glyphicon("remove-sign")."</a></span>
									<input type=\"text\" class=\"form-control\" disabled value=\"".$keyword->get_text()."\">
								</div>
							");
							}
					echo("
						</div>
						<div class=\"input-group\">
							<span class=\"input-group-addon\">".Bootstrap::fontawesome("tag")."</span>
							<input type=\"text\" id=\"new_keyword\" name=\"new_keyword\" class=\"form-control\" ".$disabled." placeholder=\"Add new keyword...\">
						</div>
					</div>
					<div class=\"form-group\">
						<label for=\"type\">Audio Type</label>
						<select class=\"selectpicker\" id=\"type\" name=\"type\" data-width=\"100%\" ".$disabled.">");
						foreach(AudioTypes::get_all() as $audiotype) {
							if ($audiotype->get_id() == $track->get_type()->get_id()) {
								echo("<option value=\"".$audiotype->get_id()."\" selected=\"selected\">".$audiotype->get_name()."</option>");
							} 
							else {
								echo("<option value=\"".$audiotype->get_id()."\">".$audiotype->get_name()."</option>");
							}
						}
					echo("</select>
					</div>
					<hr />
					");
					if(Session::is_group_user("Playlist Editor")) {
						$playlists = array();
						foreach($track->get_playlists_in() as $playlist) $playlists[] = $playlist->get_id();
						echo("<a href=\"#\" data-toggle=\"modal\" data-target=\"#playlist-modal\" data-backdrop=\"true\" data-keyboard=\"true\" data-dps-id=\"".$track->get_id()."\" data-playlists-in=\"".implode(",",$playlists)."\" id=\"playlists\" class=\"playlist-add btn btn-primary btn-block\">".Bootstrap::glyphicon("th-list")." Playlists</a>");
					}
					echo("
					<a href=\"#\" id=\"flag\" class=\"btn btn-danger btn-block".($track->is_flagged()? " active" : "")."\">".Bootstrap::fontawesome("exclamation-triangle", "censor-flag")." Flag for censorship</a>
					".(Session::is_group_user("Music Admin")? "<hr /><a href=\"".LINK_ABS."audio/get/".$track->get_id().".flac\" class=\"btn btn-primary btn-block\">".Bootstrap::fontawesome("download")." Download FLAC</a>" : "")."
				</div>
			</div>
		</fieldset>
	</form>
");
if(Session::is_group_user("Playlist Editor")) {
	$playlist_modal_content = "<p>Select a playlist to add/remove <span class=\"playlist-track-title\">this track</span> to/from:</p><ul class=\"nav nav-pills nav-stacked\">";
	foreach(Playlists::get_all() as $playlist) $playlist_modal_content .= "<li><a href=\"#\" class=\"playlist-select\" data-playlist-id=\"".$playlist->get_id()."\">".Bootstrap::fontawesome("plus-circle").$playlist->get_name()."</a></li>";
	$playlist_modal_content .= "</ul>";
	echo(Bootstrap::modal("playlist-modal", $playlist_modal_content, "Add to playlist", "<a href=\"#\" class=\"btn btn-primary\" data-dismiss=\"modal\">Done</a> <a href=\"".LINK_ABS."playlists\" class=\"btn btn-default\">Manage playlists</a>"));
}

if(Session::is_group_user("Music Admin"))
	echo(Bootstrap::modal("delete-modal", "<p>Are you sure you want to move <span class=\"delete-track-title\">this track</span> to the trash?</p>", "Delete track", "<a href=\"#\" class=\"btn btn-primary yes-definitely-delete\">Yes</a> <a href=\"#\" class=\"btn btn-default\" data-dismiss=\"modal\">No</a>"));

?>
