<?php 

if(Session::is_user()){

	$query = $_REQUEST['q'];

	if($query) $search = Search::tracks($query);
	$tracks = $search["results"];

	$count = 1;

	foreach($tracks as $track_id) {
		$track = Tracks::get($track_id);
		echo "<div id=\"track-draggable-".$count."\" class=\"ui-draggable dps-aw-item\" data-dps-aw-style=\"118\" data-dps-audio-id=\"".$track->get_id()."\">
		<span class=\"text\">".$track->get_title()."</span>
		</div><br>";
		$count = $count + 1;
	}

}

?>