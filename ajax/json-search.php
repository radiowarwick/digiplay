<?php
require_once("pre.php");
Output::set_template();

$query = $_REQUEST['q'];

if($query) $search = Search::tracks(str_replace(" ", " | ", $query),"title",5);
$tracks = $search["results"];
$tracks_array = array();

if($tracks) {	
	foreach($tracks as $track_id) {
		$track_object = Tracks::get($track_id);
		$track = array(
			'id' => $track_object->get_id(),
			'title' => $track_object->get_title(),
			'by' => $track_object->get_artists_str(),
			'href' => SITE_LINK_ABS."music/detail/".$track_object->get_id()
			);
		array_push($tracks_array, $track);
	}
}

if($query) $search = Search::artists($query,5);
$artists = $search["results"];
$artists_array = array();

if($artists) {	
	foreach ($artists as $artist) {
		$artist_object = Artists::get_by_id($artist);
		$artist = array(
				'id' => $artist_object->get_id(),
				'title' => $artist_object->get_name(),
				'href' => SITE_LINK_ABS."music/search/?q=%22".urlencode($artist_object->get_name())."%22&i=artist"
				);
		array_push($artists_array, $artist);
	}
}


if($query) $search = Search::albums($query,5);
$albums = $search["results"];
$albums_array = array();

if($albums) {	
	foreach($albums as $album) {
		$album_object = Albums::get_by_id($album);
		$album = array(
			'id' => $album_object->get_id(),
			'title' => $album_object->get_name(),
			'href' => SITE_LINK_ABS."music/search/?q=%22".urlencode($album_object->get_name())."%22&i=album"
			);
		array_push($albums_array, $album);
	}
}

$array = array(
	array( 
		"title" => "Tracks",
		"href" => SITE_LINK_ABS."music/search/?i=title&q=".$query,
		"data" => $tracks_array),
	array(
		"title" => "Artists",
		"href" => SITE_LINK_ABS."music/search/?i=artist&q=".$query,
		"data" => $artists_array),
	array(
		"title" => "Albums",
		"href" => SITE_LINK_ABS."music/search/?i=album&q=".$query,
		"data" => $albums_array)
	);

echo json_encode($array);
?>