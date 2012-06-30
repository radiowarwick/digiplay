<?php
require_once('pre.php');
Output::set_title("Music Library");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");
Output::add_script(SITE_LINK_REL."js/bootstrap-popover.js");

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
			'title': function() { 
				return($(this).parent().parent().find('.title').html())
			},
			'content': function() {
				return($(this).parent().find('.hover-info').html());
			}
		});
	});
</script>");

MainTemplate::set_subtitle("Add and remove tracks, edit track details");

if($flagged = Tracks::get_flagged()) echo(AlertMessage::basic("warning","<a href=\"".SITE_LINK_REL."music/censor\">Click here to view them.</a>", "Tracks have been flagged for censorship."));

echo("
<div class=\"row\">
	<div class=\"span4\">
	<h2>Library Statistics</h2>
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
		<h2>Requested Tracks</h2>
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
	echo("<h2>10 newest tracks</h2>");
	echo("<table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"> </th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"album\">Date Added</th>
			<th class=\"length\">Length</th> 
			<th class=\"icon\"></th>
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
			<td class=\"icon\"><a href=\"preview/".$track->get_id()."\" class=\"track-preview\" title=\"Preview this track\" rel=\"twipsy\"><i class=\"icon-volume-up\"></i></td>
			".(Session::is_group_user("Music Admin")? "<td class=\"icon\"><a href=\"delete/".$track->get_id()."\" class=\"track-delete\" title=\"Delete this track\" rel=\"twipsy\"><i class=\"icon-remove-sign\"></i></td>" : "")."
		</tr>");
	}
	echo("</table>");
} else {
	echo("Sorry, no results");
}
?>
