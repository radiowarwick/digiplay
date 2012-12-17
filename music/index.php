<?php
require_once('pre.php');
Output::set_title("Music Library");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");

function total_track_time($time_arr) {
	$time_str = ($time_arr["days"])? $time_arr["days"]." days, " : "";
	$time_str .= ($time_arr["hours"])? $time_arr["hours"]." hours, " : "";
	$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]." minutes, " : "";
	$time_str .= ($time_arr["seconds"])? $time_arr["seconds"]." seconds" : "";
	return $time_str;
}

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

".(Session::is_group_user("Playlist Admin") ? "
		var item;
		$('.playlist-add').click(function() {
			item = $(this).parent().parent();
			playlists = $(this).attr('data-playlists-in').split(',');
			$('.playlist-select').parent().removeClass('active');
			$('.playlist-select').find('i').removeClass('icon-white icon-minus').addClass('icon-plus');
			$('.playlist-select').each(function() {
				if($.inArray($(this).attr('data-playlist-id'),playlists) > -1) {
					$(this).find('i').removeClass('icon-plus').addClass('icon-minus icon-white');
					$(this).parent().addClass('active');
				}
			})
		});

		$('.playlist-select').click(function() {
			obj = $(this);
			if($(this).parent().hasClass('active')) {
				$(this).find('i').removeClass('icon-minus').addClass('icon-refresh');
				$.ajax({
					url: '".SITE_LINK_REL."ajax/track-playlist-update',
					data: 'trackid='+item.attr('id')+'&playlistid='+obj.attr('data-playlist-id')+'&action=del',
					type: 'POST',
					error: function(xhr,text,error) {
						value = $.parseJSON(xhr.responseText);
						obj.find('i').removeClass('icon-refresh').addClass('icon-minus');
						alert(value.error);
					},
					success: function(data,text,xhr) {
						values = $.parseJSON(data);
						obj.find('i').removeClass('icon-refresh icon-white').addClass('icon-plus');
						obj.parent().removeClass('active');
						item.find('.playlist-add').attr('data-playlists-in',values.playlists.join(','));
					}
				});
			} else {
				$(this).find('i').removeClass('icon-plus').addClass('icon-refresh');
				$.ajax({
					url: '".SITE_LINK_REL."ajax/track-playlist-update',
					data: 'trackid='+item.attr('id')+'&playlistid='+obj.attr('data-playlist-id')+'&action=add',
					type: 'POST',
					error: function(xhr,text,error) {
						value = $.parseJSON(xhr.responseText);
						obj.find('i').removeClass('icon-refresh').addClass('icon-plus');
						alert(value.error);
					},
					success: function(data,text,xhr) {
						values = $.parseJSON(data);
						obj.find('i').removeClass('icon-refresh').addClass('icon-minus icon-white');
						obj.parent().addClass('active');
						item.find('.playlist-add').attr('data-playlists-in',values.playlists.join(','));
					}
				});
				$(this).parent().addClass('active');
				$(this).find('i').removeClass('icon-plus').addClass('icon-white icon-minus');
			}
		});		
" : "").
(Session::is_group_user("Music Admin") ? "
		var trackid;
		$('.track-delete').click(function() {
			$('.delete-track-title').html($(this).parent().parent().find('.title').html());
			trackid = $(this).attr('data-dps-id');
		});

		$('.yes-definitely-delete').click(function() {
			$.ajax({
				url: '".SITE_LINK_REL."ajax/delete-track',
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

if($flagged = Tracks::get_flagged()) echo(AlertMessage::basic("warning","<a href=\"".SITE_LINK_REL."music/censor\">Click here to view them.</a>", "Tracks have been flagged for censorship."));

echo("
<div class=\"row\">
	<div class=\"span4\">
	<h3>Library Statistics</h3>
		<dl>
			<dt>Tracks Stored</dt>
			<dd>".number_format(Tracks::get_total_tracks())."</dd>
			<dt>Length of Tracks</dt>
			<dd>".total_track_time(Time::seconds_to_dhms(Tracks::get_total_length()))."</dd>
			<dt>Playlisted Tracks</dt>
			<dd>".count(Tracks::get_playlisted())."</dd>
		</dl>
	</div>
	<div class=\"span5\">
		<h3>Requested Tracks</h3>
		");
		if($requested = Requests::get_latest(3)) {
			echo("
		<table class=\"table table-striped table-condensed\" cellspacing=\"0\">
			<thead>
				<tr>
					<th class=\"icon\"></th>
					<th class=\"artist\">Artist</th>
					<th class=\"title\">Title</th>".(Session::is_group_user("Music Admin")? "
					<th class=\"icon\"></th>
					<th class=\"icon\"></th>" : "")."
				</tr>
			</thead>");
			foreach($requested as $request) {
				echo("
			<tr id=\"".$request->get_id()."\">
				<td class=\"icon\">
					<a href=\"#\" class=\"track-info\">
						<i class=\"icon-info-sign\"></i>
					</a>
					<div class=\"hover-info\">
						<strong>Artist:</strong> ".$request->get_artist_name()."<br />
						<strong>Title:</strong> ".$request->get_name()."<br />
						<strong>Date Requested:</strong> ".date("d/m/Y H:i",$request->get_date())."<br />
						<strong>Requester:</strong> ".$request->get_user()->get_username()."<br />
					</div>
				</td>
				<td class=\"artist\">".$request->get_artist_name()."</td>
				<td class=\"title\">".$request->get_name()."</td>".(Session::is_group_user("Music Admin")? "
				<td class=\"icon\"><a href=\"".SITE_LINK_REL."music/request/upload?id=".$request->get_id()."\" class=\"request-upload\" title=\"Upload this track\" rel=\"twipsy\"><i class=\"icon-plus-sign\"></i></td>
				".(Session::is_group_user("Music Admin")? "<td class=\"icon\"><a href=\"".SITE_LINK_REL."music/request/delete?id=".$request->get_id()."\" class=\"request-delete\" title=\"Delete this request\" rel=\"twipsy\"><i class=\"icon-minus-sign\"></td>" : "") : "")."
			</tr>");
			}
			echo("
		</table>");
			$total_requests = Requests::count();
			if($total_requests <= count($requested)) {
				echo("<a href=\"".SITE_LINK_REL."music/request\">&raquo; Go to requests</a>");
			} else {
				echo("<a href=\"".SITE_LINK_REL."music/request\">&raquo; See ".($total_requests - count($requested))." more requests</a>");
			}
		} else {
			echo("
		<strong>No new requested tracks.</strong><br />
		<a href=\"".SITE_LINK_REL."music/request\">&raquo; Go to requests</a>");
		}
		echo("
	</div>
</div>
<hr />
");

$tracks = Tracks::get_newest();

if($tracks) {
	echo("<h3>10 newest tracks</h3>");
	echo("<table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"> </th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"album\">Date Added</th>
			<th class=\"length\">Length</th> 
			".(Session::is_group_user("Playlist Admin")? "<th class=\"icon\"></th>" : "")."
			".(Session::is_group_user("Music Admin")? "<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($tracks as $track) {
		$artists = Artists::get_by_audio_id($track->get_id());
		$artist_str = "";
		foreach($artists as $artist) $artist_str .= $artist->get_name()."; ";
		$artist_str = substr($artist_str,0,-2);
		$import_date = date("d/m/Y H:i",$track->get_import_date());
		echo("
		<tr id=\"".$track->get_id()."\">
			<td class=\"icon\">
				<a href=\"".SITE_LINK_REL."music/detail/".$track->get_id()."\" class=\"track-info\">
					<i class=\"icon-info-sign\"></i>
				</a>
				<div class=\"hover-info\">
					<strong>Artist:</strong> ".$artist_str."<br />
					<strong>Album:</strong> ".$track->get_album()->get_name()."<br />
					<strong>Year:</strong> ".$track->get_year()."<br />
					<strong>Length:</strong> ".Time::format_succinct($track->get_length())."<br />
					<strong>Origin:</strong> ".$track->get_origin()."<br />
					".($track->get_reclibid()? "<strong>Reclib ID:</strong> ".$track->get_reclibid()."<br />" : "")."
					<strong>On Sue:</strong> ".($track->is_sustainer()? "Yes" : "No")."<br />
					<strong>Censored:</strong> ".($track->is_censored()? "Yes" : "No")."<br /> 
				</div>
			</td>
			<td class=\"artist\">".$artist_str."</td>
			<td class=\"title\">".$track->get_title()."</td>
			<td class=\"album\">".$import_date."</td>
			<td class=\"length\">".Time::format_succinct($track->get_length())."</td>
			");
			if(Session::is_group_user("Playlist Admin")) {
				$playlists = array();
				foreach($track->get_playlists_in() as $playlist) $playlists[] = $playlist->get_id();
				echo("<td class=\"icon\"><a href=\"#\" data-toggle=\"modal\" data-target=\"#playlist-modal\" data-backdrop=\"true\" data-keyboard=\"true\" data-dps-id=\"".$track->get_id()."\" data-playlists-in=\"".implode(",",$playlists)."\" class=\"playlist-add\" title=\"Add to playlist\" rel=\"twipsy\"><i class=\"icon-plus-sign\"></i></a></td>"); 
			}
			echo((Session::is_group_user("Music Admin")? "<td class=\"icon\"><a href=\"#\" data-toggle=\"modal\" data-target=\"#delete-modal\" data-backdrop=\"true\" data-keyboard=\"true\" data-dps-id=\"".$track->get_id()."\" class=\"track-delete\" title=\"Delete this track\" rel=\"twipsy\"><i class=\"icon-remove-sign\"></i></a></td>" : "")."
		</tr>");
	}
	echo("</table>");
} else {
	echo("Sorry, no results");
}
if(Session::is_group_user("Playlist Admin")) {
	echo("
		<div class=\"modal fade hide\" id=\"playlist-modal\">
			<div class=\"modal-header\">
				<a class=\"close\" data-dismiss=\"modal\">&times;</a>
				<h4>Add to playlist</h4>
			</div>
			<div class=\"modal-body\">
				<p>Select a playlist to add/remove <span class=\"playlist-track-title\">this track</span> to/from:</p>
				<ul class=\"nav nav-pills nav-stacked\">
				");
				foreach(Playlists::get_all() as $playlist) {
					echo("<li><a href=\"#\" class=\"playlist-select\" data-playlist-id=\"".$playlist->get_id()."\"><i class=\"icon-music\" style=\"margin-right: 10px\"></i>".$playlist->get_name()."</a></li>");
				}
				echo("
				</ul>
			</div>
			<div class=\"modal-footer\">
				<a href=\"#\" class=\"btn btn-primary\" data-dismiss=\"modal\">Done</a>
				<a href=\"".SITE_LINK_REL."playlists\" class=\"btn\">Manage playlists</a>
			</div>
		</div>"
	);
}

if(Session::is_group_user("Music Admin")) {
	echo("
		<div class=\"modal fade hide\" id=\"delete-modal\">
			<div class=\"modal-header\">
				<a class=\"close\" data-dismiss=\"modal\">&times;</a>
				<h4>Delete Track</h4>
			</div>
			<div class=\"modal-body\">
				<p>Are you sure you want to move <span class=\"delete-track-title\">this track</span> to the trash?</p>
			</div>
			<div class=\"modal-footer\">
				<a href=\"#\" class=\"btn btn-primary yes-definitely-delete\">Yes</a>
				<a href=\"#\" class=\"btn\" data-dismiss=\"modal\">No</a>
			</div>
		</div>"
	);
}
?>
