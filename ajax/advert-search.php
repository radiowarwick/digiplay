<?php

Output::set_template();

$query = $_REQUEST['q'];

if($query) $search = Search::adverts(str_replace(" ", " | ", $query),10);
$tracks = $search["results"];
$tracks_array = array();

if($tracks) {	
	foreach($tracks as $track_id) {
		$track_object = Tracks::get($track_id);
		$track = array(
			'id' => $track_object->get_id(),
			'title' => $track_object->get_title(),
			'by' => $track_object->get_artists_str(),
			'href' => LINK_ABS."music/detail/".$track_object->get_id()
			);
		array_push($tracks_array, $track);
	}
}

$array = array( 
		"title" => "Adverts",
		"href" => LINK_ABS."music/search/?i=title&q=".$query,
		"data" => $tracks_array
	);

echo json_encode($array);

?>