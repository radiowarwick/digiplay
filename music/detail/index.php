<?php
require_once('pre.php');
Output::set_title("Track Detail");
MainTemplate::set_subtitle("View and edit track metadata");

if(!isset($_GET['id'])) {
	exit("<h2>No track specified</h2><h3>You must access this via another page, to get metadata for a specified track.</h3>");
}

if(!$track = Tracks::get($_GET["id"])) {
	exit("<h2>Invalid track ID</h2><h3>If you got to this page via a link from somewhere else on the site, there may be a bug.  A bug you should bug the techies about!</h3>");
}

$artists = Artists::get_by_audio_id($track->get_id());
foreach($artists as $artist) $artist_str .= $artist->get_name()."; ";
$artist_str = substr($artist_str,0,-2);

$album = Albums::get_by_audio_id($track->get_id());

if(isset($_GET['ajax'])) {
	Output::set_template();
	echo("
	<div class=\"row\">
		<div class=\"span16\">
			<strong>".$track->get_title()." by ".$artist_str."</strong>
		</div>
	</div>
	<div class=\"row\">
		<div class=\"span8\">
			<em>Album:</em> ".$album->get_name()."<br />
			<em>Year:</em> ".($track->get_year()? $track->get_year() : "(none)")."<br />
			<em>Length:</em> blah<br />
		</div>
		<div class=\"span8\">
			<em>Some more info:</em> blah
		</div>
	</div>
	");
} else {
	
}
?>