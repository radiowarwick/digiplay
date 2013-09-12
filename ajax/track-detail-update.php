<?php


if(Session::is_group_user('Music Admin')){
	$track = Tracks::get_by_id($_REQUEST["id"]);
	if(!$_REQUEST["title"]) {
		http_response_code(400);
		exit(json_encode(array("error" => "You did not specify a title.")));
	};
	if($_REQUEST["title"] != $track->get_title()) $track->set_title($_REQUEST["title"]);

	$curr_artists_obj = $track->get_artists();
	$curr_artists_arr = array();
	foreach($curr_artists_obj as $artist) $curr_artists_arr[] = $artist->get_name();
	$track->del_artists(array_diff($curr_artists_arr,$_REQUEST["artist"]));
	$track->add_artists(array_diff($_REQUEST["artist"],$curr_artists_arr));
	
	if($_REQUEST["new_artist"]) $track->add_artists($_REQUEST["new_artist"]);

	if(!$_REQUEST["album"]) $_REQUEST["album"] = "(none)";
	if($_REQUEST["album"] != $track->get_album()->get_name()) $track->set_album($_REQUEST["album"]);

	if($_REQUEST["year"] != $track->get_year()) $track->set_year($_REQUEST["year"]);

	if(!$_REQUEST["origin"]) exit("You must specify an origin");
	if($_REQUEST["origin"] != $track->get_origin()) $track->set_origin($_REQUEST["origin"]);

	if($_REQUEST["reclibid"] != $track->get_reclibid()) $track->set_reclibid($_REQUEST["reclibid"]);

	$track->set_censored($_REQUEST["censored"]);

	if($_REQUEST["notes"] != $track->get_notes()) $track->set_notes($_REQUEST["notes"]);
	if($_REQUEST["new_keyword"]) $track->add_keywords($_REQUEST["new_keyword"]);

	$result = $track->save();
	if(!$result) { 
		http_response_code(400);
		exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!")));
	}

	$result = $track->save_audio();
	if(!$result) { 
		http_response_code(400);
		exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!")));
	}

	$new_track = Tracks::get_by_id($_REQUEST["id"]);
	http_response_code(200);
	$json = array(
		"title" => $new_track->get_title(),
		"album" => $new_track->get_album()->get_name(),
		"year" => $new_track->get_year(),
		"length" => $new_track->get_length(),
		"origin" => $new_track->get_origin(),
		"censored" => $new_track->is_censored(),
		"notes" => $new_track->get_notes()
		);

	$artists_return = array(); $keywords_return = array();
	foreach($track->get_artists() as $key=>$artist) $artists_return[] = $artist->get_name();
	foreach($track->get_keywords() as $key=>$keyword) $keywords_return[] = $keyword->get_text();
	$json["artists"] = $artists_return;
	$json["keywords"] = $keywords_return;

	exit(json_encode($json));

} else {
	http_response_code(403);
}
?>