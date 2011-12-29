<?php
require_once('pre.php');
Output::set_title("Music Library");
MainTemplate::set_subtitle("Add and remove tracks, edit track details");
$tracks = Search::title("run");

function track_length($time_arr) {
	$time_str = ($time_arr["days"])? $time_arr["days"]."d " : "";
	$time_str .= ($time_arr["hours"])? $time_arr["hours"]."h " : "";
	$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]."m " : "";
	$time_str .= ($time_arr["seconds"])? $time_arr["seconds"]."s " : "";
	return $time_str;
}

if($tracks) {
	echo("<table class=\"zebra-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th style=\"width:16px;\"> </th>
			<th>Artist</th>
			<th>Title</th>
			<th>Album</th>
			<th>Length</th> 
			<th style=\"width:16px;\"></th>
			<th style=\"width:16px;\"></th>
		</tr>
	</thead>");
	foreach($tracks as $track_id) {
		$track = Tracks::get($track_id);
		echo("
		<tr>
			<td><a href=\"detail/".$track->get_id()."\" class=\"track-info\"><img src=\"".SITE_LINK_REL."images/icons/information.png\"></a></td>
			<td>".$artist."</td>
			<td>".$track->get_title()."</td>
			<td>".$album."</td>
			<td>".track_length(Time::seconds_to_dhms($track->get_length()))."</td>
			<td><a href=\"preview/".$track->get_id()."\" class=\"track-preview\"><img src=\"".SITE_LINK_REL."images/icons/sound.png\"></td>
			<td><a href=\"delete/".$track->get_id()."\" class=\"track-delete\"><img src=\"".SITE_LINK_REL."images/icons/delete.png\"></td>
		</tr>");
	}
	echo("</table>");
} else {
	echo("Sorry, no results");
}
?>