<?php

Output::set_title("Sustainer");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");

Output::require_group("Sustainer Admin");

MainTemplate::set_subtitle("Perform common sustainer tasks");


if ($_POST['trackid'] || $_GET['trackid']) {
	$query = "SELECT * FROM audio WHERE id=:trackid";
	$parameters = array(':trackid' => $_REQUEST['trackid']);
	$result = DigiplayDB::query($query, $parameters);
	if( $result->rowCount() != 1 ) {
		echo(Bootstrap::alert_message_basic("danger","Couldn't find track ID in the digiplay audio DB."));
	} else {
		$track = $result->fetch();
		$query = "SELECT * FROM sustschedule order by id asc limit 1";
		$result = DigiplayDB::query($query);
		$scheduleslot = $result->fetch();
		$query = "UPDATE sustschedule SET audioid=:trackid, trim_start_smpl=0, trim_end_smpl = :tracklength, fade_in = 0, fade_out = :tracklength WHERE id = :scheduleslot";
		$parameters = array(':trackid' => $track['id'], ':tracklength' => $track['length_smpl'], ':scheduleslot' => $scheduleslot['id']);
		DigiplayDB::query($query, $parameters);
		echo(Bootstrap::alert_message_basic("info","Track Scheduled"));       
	}
}

$currentQueue = Sustainer::get_queue();
$i = 0;

echo("<h3>Current queue:</h3>");

echo("<table class=\"table table-striped table-bordered\">
	<thead>
	<tr>
	<th></th>
	<th>Title</th>
	<th>Artist</th>
	<th>Album</th>
	</tr>
	</thead>
	<tbody>");
foreach ($currentQueue as $row) {
	$i++;
	echo("<tr>
		<td>".$i."</td>
	<td>".$row['title']."</td>
	<td>".$row['artist']."</td>
	<td>".$row['album']."</td>
	</tr>");
}
echo("</tbody>
	</table>");

echo("<h3>Schedule audio:</h3>");

echo("<p>You can use this tool to schedule the next audio track to be played on Sue by using its audio id.</p>");
echo("<form action=\"\" method=\"post\">");
echo("Track ID: <input type=\"text\" name=\"trackid\" /><input type=\"submit\" name=\"submit\" value=\"Schedule\" />");
echo("</form>");

?>