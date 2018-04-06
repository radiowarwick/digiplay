<?php

Output::set_title("Music Library");

echo("<script>
	$(function () {
		$('.track-info').popover({
			'html': true, 
			'trigger': 'hover',
			'title': function() { 
				return($(this).parent().parent().find('.title').html())
			},
			'content': function() {
				return($(this).parent().find('.hover-info').html());
			}
		});

".(Session::is_group_user("Playlist Editor") ? "
		var item;
		$('.playlist-add').click(function() {
			item = $(this).parent().parent();
			playlists = $(this).attr('data-playlists-in').split(',');
			$('.playlist-select').parent().removeClass('active');
			$('.playlist-select').find('span').removeClass('glyphicon-minus').addClass('glyphicon-plus');
			$('.playlist-select').each(function() {
				if($.inArray($(this).attr('data-playlist-id'),playlists) > -1) {
					$(this).find('span').removeClass('icon-plus').addClass('glyphicon-minus');
					$(this).parent().addClass('active');
				}
			})
		});

		$('.playlist-select').click(function() {
			obj = $(this);
			if($(this).parent().hasClass('active')) {
				$(this).find('span').removeClass('glyphicon-minus').addClass('glyphicon-refresh');
				$.ajax({
					url: '".LINK_ABS."ajax/track-playlist-update.php',
					data: 'trackid='+item.attr('id')+'&playlistid='+obj.attr('data-playlist-id')+'&action=del',
					type: 'POST',
					error: function(xhr,text,error) {
						value = $.parseJSON(xhr.responseText);
						obj.find('span').removeClass('glyphicon-refresh').addClass('glyphicon-minus');
						alert(value.error);
					},
					success: function(data,text,xhr) {
						values = $.parseJSON(data);
						obj.find('span').removeClass('glyphicon-refresh').addClass('glyphicon-plus');
						obj.parent().removeClass('active');
						item.find('.playlist-add').attr('data-playlists-in',values.playlists.join(','));
					}
				});
			} else {
				$(this).find('span').removeClass('glyphicon-plus').addClass('glyphicon-refresh');
				$.ajax({
					url: '".LINK_ABS."ajax/track-playlist-update.php',
					data: 'trackid='+item.attr('id')+'&playlistid='+obj.attr('data-playlist-id')+'&action=add',
					type: 'POST',
					error: function(xhr,text,error) {
						value = $.parseJSON(xhr.responseText);
						obj.find('span').removeClass('glyphicon-refresh').addClass('glyphicon-plus');
						alert(value.error);
					},
					success: function(data,text,xhr) {
						values = $.parseJSON(data);
						obj.find('span').removeClass('glyphicon-refresh').addClass('glyphicon-minus');
						obj.parent().addClass('active');
						item.find('.playlist-add').attr('data-playlists-in',values.playlists.join(','));
					}
				});
				$(this).parent().addClass('active');
				$(this).find('span').removeClass('glyphicon-plus').addClass('glyphicon-minus');
			}
		});		
" : "").
(Session::is_group_user("Librarian") ? "
		var trackid;
		$('.track-delete').click(function() {
			$('.delete-track-title').html($(this).parent().parent().find('.title').html());
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
" : "").
"	});
</script>");

MainTemplate::set_subtitle("Add and remove tracks, edit track details");

if(($flagged = Tracks::get_flagged()) && Session::is_group_user("Censor")) echo(Bootstrap::alert_message_basic("warning","<a href=\"".LINK_ABS."music/censor\">Click here to view them.</a>", "Tracks have been flagged for censorship."));

echo("
<div class=\"row\">
	<div class=\"col-sm-5\">
	<h3>Library Statistics</h3>
		<dl>
			<dt>Tracks Stored</dt>
			<dd>".number_format(Tracks::get_total_tracks())."</dd>
			<dt>Length of Tracks</dt>
			<dd>".Time::format_pretty(Tracks::get_total_length())."</dd>
			<dt>Playlisted Tracks</dt>
			<dd>".count(Tracks::get_playlisted())."</dd>
		</dl>
	</div>
	<div class=\"col-sm-7\">
		<h3>Requested Tracks</h3>
		");
		if($requested = Requests::get_latest(3)) {
			echo("
		<table class=\"table table-striped table-condensed\" cellspacing=\"0\">
			<thead>
				<tr>
					<th class=\"icon\"></th>
					<th class=\"artist\">Artist</th>
					<th class=\"title\">Title</th>".(Session::is_group_user("Requests Admin")? "
					<th class=\"icon\"></th>" : "")."
				</tr>
			</thead>");
			foreach($requested as $request) {
				echo("
			<tr id=\"".$request->get_id()."\">
				<td class=\"icon\">
					<a href=\"#\" class=\"track-info\">
						".Bootstrap::fontawesome("info-circle")."
					</a>
					<div class=\"hover-info\">
						<strong>Artist:</strong> ".$request->get_artist_name()."<br />
						<strong>Title:</strong> ".$request->get_name()."<br />
						<strong>Date Requested:</strong> ".date("d/m/Y H:i",$request->get_date())."<br />
						<strong>Requester:</strong> ".$request->get_user()->get_username()."<br />
					</div>
				</td>
				<td class=\"artist\">".$request->get_artist_name()."</td>
				<td class=\"title\">".$request->get_name()."</td>
				".(Session::is_group_user("Requests Admin")? "<td class=\"icon\"><a href=\"".LINK_ABS."music/request/delete?id=".$request->get_id()."\" class=\"request-delete\" title=\"Delete this request\" rel=\"twipsy\">".Bootstrap::glyphicon("minus-sign")."</td>" : "")."
			</tr>");
			}
			echo("
		</table>");
			$total_requests = Requests::count();
			if($total_requests <= count($requested)) {
				echo("<a href=\"".LINK_ABS."music/request\">&raquo; Go to requests</a>");
			} else {
				echo("<a href=\"".LINK_ABS."music/request\">&raquo; See ".($total_requests - count($requested))." more requests</a>");
			}
		} else {
			echo("
		<strong>No new requested tracks.</strong><br />
		<a href=\"".LINK_ABS."music/request\">&raquo; Go to requests</a>");
		}
		echo("
	</div>
</div>
<hr />
");

$tracks = Tracks::get_newest();

if($tracks) {
	echo("<h3>10 newest tracks</h3>");
	echo("<div class=\"table-responsive\"><table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"> </th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"date-added nowrap\">Date Added</th>
			<th class=\"length nowrap\">Length</th> 
			".(Session::is_group_user("Playlist Editor")? "<th class=\"icon\"></th>" : "")."
			".(Session::is_group_user("Librarian")? "<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($tracks as $track) {
		$import_date = date("d/m/Y H:i",$track->get_import_date());
		echo("
		<tr id=\"".$track->get_id()."\">
			<td class=\"icon\">
				<a href=\"".LINK_ABS."music/detail/".$track->get_id()."\" class=\"track-info\">
					".Bootstrap::fontawesome("info-circle")."
				</a>
				<div class=\"hover-info\">
					<strong>Artist:</strong> ".$track->get_artists_str()."<br />
					<strong>Album:</strong> ".$track->get_album()->get_name()."<br />
					<strong>Year:</strong> ".$track->get_year()."<br />
					<strong>Length:</strong> ".Time::format_succinct($track->get_length())."<br />
					<strong>Origin:</strong> ".$track->get_origin()."<br />
					".($track->get_reclibid()? "<strong>Reclib ID:</strong> ".$track->get_reclibid()."<br />" : "")."
					<strong>Censored:</strong> ".($track->is_censored()? "Yes" : "No")."<br /> 
				</div>
			</td>
			<td class=\"artist\">".$track->get_artists_str()."</td>
			<td class=\"title\">".$track->get_title()."</td>
			<td class=\"date-added nowrap\">".$import_date."</td>
			<td class=\"length nowrap\">".Time::format_succinct($track->get_length())."</td>
			");
			if(Session::is_group_user("Playlist Editor")) {
				$playlists = array();
				foreach($track->get_playlists_in() as $playlist) $playlists[] = $playlist->get_id();
				echo("<td class=\"icon\"><a href=\"#\" data-toggle=\"modal\" data-target=\"#playlist-modal\" data-backdrop=\"true\" data-keyboard=\"true\" data-dps-id=\"".$track->get_id()."\" data-playlists-in=\"".implode(",",$playlists)."\" class=\"playlist-add\" title=\"Add to playlist\" rel=\"twipsy\">".Bootstrap::fontawesome("plus-circle")."</i></a></td>"); 
			}
			echo((Session::is_group_user("Librarian")? "<td class=\"icon\"><a href=\"#\" data-toggle=\"modal\" data-target=\"#delete-modal\" data-dps-id=\"".$track->get_id()."\" class=\"track-delete\" title=\"Delete this track\" rel=\"twipsy\">".Bootstrap::fontawesome("times-circle")."</i></a></td>" : "")."
		</tr>");
	}
	echo("</table></div>");
} else {
	echo("Sorry, no results");
}
if(Session::is_group_user("Playlist Editor")) {
	$playlist_modal_content = "<p>Select a playlist to add/remove <span class=\"playlist-track-title\">this track</span> to/from:</p><ul class=\"nav nav-pills nav-stacked\">";
	foreach(Playlists::get_all() as $playlist) $playlist_modal_content .= "<li><a href=\"#\" class=\"playlist-select\" data-playlist-id=\"".$playlist->get_id()."\">".Bootstrap::fontawesome("plus-circle").$playlist->get_name()."</a></li>";
	$playlist_modal_content .= "</ul>";
	echo(Bootstrap::modal("playlist-modal", $playlist_modal_content, "Add to playlist", "<a href=\"#\" class=\"btn btn-primary\" data-dismiss=\"modal\">Done</a> <a href=\"".LINK_ABS."playlists\" class=\"btn btn-default\">Manage playlists</a>"));
}

if(Session::is_group_user("Librarian")) echo(Bootstrap::modal("delete-modal", "<p>Are you sure you want to move <span class=\"delete-track-title\">this track</span> to the trash?</p>", "Delete track", "<a href=\"#\" class=\"btn btn-primary yes-definitely-delete\">Yes</a> <a href=\"#\" class=\"btn btn-default\" data-dismiss=\"modal\">No</a>"));
?>
